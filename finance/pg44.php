<?php
	require_once("./common/agent.php");	
	require_once("./common/class.payment.php");
	include("./common/class.audit.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
?>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" type="text/javascript">
	function changeImage(imgCode, imgSource){
		//alert(imgSource);
			 if(imgCode == 1)
				document.customer.src = imgSource;
		else if(imgCode ==2)
				document.product.src = imgSource;
		else if(imgCode ==3)
				document.finance.src = imgSource;
		else if(imgCode ==4)
				document.usage.src = imgSource;
		else if(imgCode ==5)
				document.audit.src = imgSource;	
	}
	
	function ChangePayment(index){
		theCalendar = document.getElementById("calenda");
		if(fpayinvoice.selPaymentID.options[index].text == "Cheque"){
			fpayinvoice.CheckFrom.disabled = false;
			fpayinvoice.CheckNumber.disabled = false;
			fpayinvoice.ChequeDate.disabled = false;
			fpayinvoice.CheckFrom.className = "boxenabled";
			fpayinvoice.CheckNumber.className = "boxenabled";
			fpayinvoice.ChequeDate.className = "boxenabled";
			theCalendar.style.display = "block";
		}else{
			fpayinvoice.CheckFrom.disabled = true;
			fpayinvoice.CheckNumber.disabled = true;
			fpayinvoice.ChequeDate.disabled = true;
			fpayinvoice.CheckFrom.className = "boxdisabled";
			fpayinvoice.CheckNumber.className = "boxdisabled";
			fpayinvoice.ChequeDate.className = "boxdisabled";
			theCalendar.style.display = "none";
		}
	}
	
	function submitForm(){
		Amount = fpayinvoice.Amount;
		PaymentID = fpayinvoice.selPaymentID;
		CheckFrom = fpayinvoice.CheckFrom;
		CheckNumber = fpayinvoice.CheckNumber;
		ChequeDate = fpayinvoice.ChequeDate;
		Description = fpayinvoice.Description;
		if(Trim(Amount.value == "")){
			alert("Please enter payment amount");
			Amount.focus();
			return;
		}else if(!isNumber(Trim(Amount.value))){
			alert("Payment amount must be a number");
			Amount.focus();
			return;
		}else if(Number(Trim(Amount.value)) <= 0){
			alert("Payment amount must be greater than 0");
			Amount.focus();
			return;		
		}else if(PaymentID.selectedIndex > 0){
			if(CheckFrom.value ==""){
				alert("Please enter cheque from");
				CheckFrom.focus();
				return;
			}else if(CheckNumber.value == ""){
				alert("Please enter cheque number");
				CheckNumber.focus();
				return;
			}else if(ChequeDate.value == ""){
				alert("Please enter cheque issued date");
				ChequeDate.focus();
				return;
			}
		}
		fpayinvoice.btnSubmit.disabled = true;
		fpayinvoice.submit();
	}
</script>
<?php
		
	# =============== Get customer header =====================
		
		$sql = "select c.CustName, sum(d.NationalDeposit) as ncDeposit, sum(d.InternationDeposit) as 'icDeposit',
									sum(d.MonthlyDeposit) as mfDeposit, sum(b.Credit) as Credit, sum(b.Outstanding) as Outstanding 
						from tblCustomer c, tblCustProduct a, tblAccountBalance b, tblAccDeposit d
						where c.CustID = a.CustID and a.AccID = b.AccID and a.AccID = d.AccID and c.CustID=$CustomerID
						group by c.CustName";

		if($que = $mydb->sql_query($sql)){
			if($rst = $mydb->sql_fetchrow($que)){
				$CustName = $rst['CustName'];
				$ncDeposit = $rst['ncDeposit'];
				$icDeposit = $rst['icDeposit'];
				$mfDeposit = $rst['mfDeposit'];
				$Deposit = $ncDeposit + $icDeposit + $mfDeposit;
				$Deposit = FormatCurrency($Deposit);
				$Credit = FormatCurrency($rst['Credit']);
				$Outstanding = FormatCurrency($rst['Outstanding']);
			}
		}
		
		$mydb->sql_freeresult();
		
	if(!empty($smt) && isset($smt) && ($smt == "save")){
		$Finance = new Payment();
		$Audit = new Audit();		
		$Amount = FixQuotes($Amount);
		$CheckFrom = FixQuotes($CheckFrom);
		$CheckNumber = FixQuotes($CheckNumber);
		$ChequeDate = FixQuotes($ChequeDate);
		$Description = FixQuotes($Description);
		
		
		#	Get drawer ID
		$DrawerID = $Finance->GetDrawerID($user['userid']);
		
		# Transaction mode id:1 ==> Fee payment
		$TransactionMode = 1;
		if($DrawerID == 0){
			$retOut = $myinfo->warning("Failed to book payment. No cash drawer open for cashier ".$user["FullName"]);
		}else{		
			# Get receipt id
			$ReceiptID = $Finance->GetReceiptID();
			
			#	Book payment
			$retOut = $Finance->CreatePaymentReceipt($DrawerID, $CustomerID, $AccountID, $InvoiceID, $ReceiptID, $Amount, $selPaymentID, $TransactionMode, $user["FullName"], $ReceiptID, $Description, $CheckFrom, $CheckNumber, $ChequeDate);
			if($retOut){
				$Description = "Make payment on Invoice No. $InvoiceID amount ".FormatCurrency($Amount)."- $Description";
				$Audit->AddAudit($CustomerID, $AccountID, "Customer payment", $Description, "", 1, 4);
				print "<script>window.open(\"./finance/receipt.php?CustomerID=".$CustomerID."&PaymentID=".$ReceiptID."\");</script>";
				redirect("./?CustomerID=".$CustomerID."&pg=43");
			}			
		}
	}	
	$sql = "SELECT InvoiceID, InvoiceAmount, UnpaidAmount,
					IsNull((select amount from tblCustomerInvoiceDetail where I.InvoiceID=tblCustomerInvoiceDetail.InvoiceID and BillItemID=11 and InvoiceID = $InvoiceID Union select amount from tblBillingSummaryTmp where I.InvoiceID=tblBillingSummaryTmp.InvoiceID and BillItemID=11 and InvoiceID = $InvoiceID),0) CreditBalance
					, AccID 
					FROM tblCustomerInvoice  I
					 where InvoiceID = $InvoiceID";	

	if($que = $mydb->sql_query($sql)){		
		if($result = $mydb->sql_fetchrow()){
			$InvoiceID = $result['InvoiceID'];
			$InvoiceAmount = $result['InvoiceAmount'];
			$CreditBalance = $result['CreditBalance'];
			$UnpaidAmount =  $result['UnpaidAmount'];
			$UnpaidAmount =  floatval($UnpaidAmount) + floatval($CreditBalance);
			if(is_null($UnpaidAmount) || ($UnpaidAmount == "")) $UnpaidAmount = $InvoiceAmount;
			$AccID = $result['AccID'];
			$sql = "SELECT SUM(PaymentAmount) as 'PaidAmount' 
							FROM tblCustCashDrawer 
							WHERE InvoiceID = $InvoiceID
									AND (IsRollBack = 0 OR IsRollBack Is NULL)";
			if($que = $mydb->sql_query($sql)){
				$result = $mydb->sql_fetchrow($que);
				$PaidAmount = $result['PaidAmount'];
				if(is_null($PaidAmount) || ($PaidAmount == "")) $PaidAmount = 0;
				$UnpaidAmount = floatval($InvoiceAmount) - floatval($PaidAmount) + floatval($CreditBalance);
				if(floatval($UnpaidAmount) < 0) $disabled = "disabled=\"disabled\"";
			}else{
				$error = $mydb->sql_error();				
				$retOut = $myinfo->error("Failed to get previous paid amount.", $error['message']);
				$disabled = "disabled=\"disabled\"";
			}			
		}
	}else{
		$error = $mydb->sql_error();				
		$retOut = $myinfo->error("Failed to get unpaid invoice.", $error['message']);
		$disabled = "disabled=\"disabled\"";
	}
	$mydb->sql_freeresult();
						
?>
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bordercolor="#aaaaaa" style="border-collapse:collapse">
		<tr>
			<td valign="top"  height="50">
					<table border="0" cellpadding="4" cellspacing="0" width="100%">
						<tr>
							<td align="left">ID: <b><?php print $CustomerID ?></b></td>						
							<td align="left" colspan="2">Name:<b><?php print $CustName ?></b></td>						
						</tr>
						<tr>
							<td align="left">Deposit: <b><?php print $Deposit; ?></b></td>						
							<td align="left">Balance: <b><?php print $Credit; ?></b></td>						
							<td align="left">Invoice: <b><?php print $Outstanding; ?></b></td>
						</tr>
					</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<!-- Individual customer tab menu -->
				<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" bordercolor="#ffffff" align="center">
					 <tr>
						<td align="left" width="15" background="./images/tab_null.gif">&nbsp;</td>						
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID;?>&pg=10"><img src="./images/tab/customer.gif" name="customer" border="0" id="customer" onmouseover="changeImage(1, './images/tab/customer_over.gif');" onmouseout="changeImage(1, './images/tab/customer.gif');"/></a></td>						
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=90"><img src="./images/tab/product.gif" name="product" border="0" id="product" onmouseover="changeImage(2, './images/tab/product_over.gif');" onmouseout="changeImage(2, './images/tab/product.gif');" /></a></td>
						
						<td align="left" width="85"><img src="./images/tab/finance_active.gif" name="finance" border="0" id="finance" /></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=70"><img src="./images/tab/usage.gif" name="usage" border="0" id="usage" onmouseover="changeImage(4, './images/tab/usage_over.gif');" onmouseout="changeImage(4, './images/tab/usage.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=30"><img src="./images/tab/audit.gif" name="audit" border="0" id="audit" onmouseover="changeImage(5, './images/tab/audit_over.gif');" onmouseout="changeImage(5, './images/tab/audit.gif');" /></a></td>						
						
						<td align="center" width="*" background="./images/tab_null.gif">&nbsp;</td>		
					</tr>
				</table>
					<!-- end customer table menu -->			
			</td>
		</tr>
		<tr>
			<td height="100%" valign="top">
					<!-- Individual customer main page -->				
					<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
						<tr>
							<td valign="top" width="180">
								<?php include("content.php"); ?>
							</td>
							<td valign="top" align="left">
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
										<td align="left" class="formtitle"><b>PAY INVOICE #<?php print $InvoiceID; ?></b></td>
										<td align="right">&nbsp;
											
										</td>
									</tr> 				
									<tr>
										<td colspan="2">
											<form name="fpayinvoice" method="post" action="./" onSubmit="return false;">
												<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" bgcolor="#feeac2">													
													<tr>
													  <td align="left" nowrap="nowrap">Invoice amount:</td>
													  <td align="left"><input type="text" name="tmpAmount" class="boxdisabled" size="15" disabled="disabled" value="<?php print FormatCurrency($InvoiceAmount); ?>" />&nbsp;&nbsp;Paid: &nbsp;<input type="text" name="tmpUnpaidAmount" class="boxdisabled" size="15" disabled="disabled" value="<?php print FormatCurrency(floatval($PaidAmount) - floatval($CreditBalance)); ?>" />&nbsp;&nbsp;UnPaid: &nbsp;<input type="text" name="tmpUnpaidAmount" class="boxdisabled" size="15" disabled="disabled" value="<?php print FormatCurrency(floatval($UnpaidAmount)); ?>" /></td>
												  </tr>
													
													<tr><td colspan="2">&nbsp;</td></tr>
													<tr>
														<td align="left" nowrap="nowrap">Payment amount:</td>
														<td align="left"><input type="text" name="Amount" tabindex="2" class="boxenabled" size="20" value="<?php print $UnpaidAmount; ?>" /></td>					
													</tr>
													<tr>
														<td align="left">Payment mode:</td>
														<td align="left">
															<select name="selPaymentID" class="boxenabled" tabindex="3" onChange="ChangePayment(this.selectedIndex);">																				
																<?php
																	$sql = "SELECT PaymentID, PaymentMode from tlkpPaymentMode";													
																	
																	$que = $mydb->sql_query($sql);									
																	if($que){
																		while($rst = $mydb->sql_fetchrow($que)){	
																			$PaymentID = $rst['PaymentID'];
																			$PaymentMode = $rst['PaymentMode'];									
																			print "<option value='".$PaymentID."'>".$PaymentMode."</option>";
																		}
																	}
																	$mydb->sql_freeresult();
																?>
															</select>														</td>
													</tr>
													<tr>
														<td align="left">Cheque from:</td>
														<td align="left">
															<input type="text" name="CheckFrom" class="boxdisabled" size="50" tabindex="4" disabled="disabled" />														</td>
													</tr>
													<tr>
														<td align="left">Cheque number:</td>
														<td align="left">
															<input type="text" name="CheckNumber" class="boxdisabled" size="50" tabindex="5" disabled="disabled" />														</td>
													</tr>
													<tr>
														<td align="left" nowrap="nowrap">Dated of issue:</td>
														<td align="left">
															<input type="text" name="ChequeDate" class="boxdisabled" size="20" tabindex="6" disabled="disabled" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
															<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fpayinvoice|ChequeDate', '', 'width=200,height=220,top=250,left=350');">
															<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0" id="calenda" style="display:none">														</button>														</td>
													</tr>
													<tr>
														<td align="left" valign="top">Description:</td>
														<td align="left">
															<textarea name="Description" cols="38" rows="4" tabindex="7" class="boxenabled">Make payment for invoice id: <?php print $InvoiceID;?></textarea>														</td>
													</tr>
													<tr><td colspan="2">&nbsp;</td></tr>								
													<tr> 				  
													<td align="center" colspan="2">
														<input type="reset" tabindex="8" name="reset" value="Reset" class="button" />
														<input type="submit" tabindex="9" name="btnSubmit" value="Submit" class="button" <?php print $disabled; ?> onClick="submitForm();" />													</td>
												 </tr>
												 <?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
														}
													?>
												</table>
											<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
											<input type="hidden" name="InvoiceID" value="<?php print $InvoiceID; ?>" />
											<input type="hidden" name="AccountID" value="<?php print $AccID; ?>" />
											<input type="hidden" name="pg" value="44" />
											<input type="hidden" name="smt" value="save" />
											</form>	
										</td>
									</tr>			
								</table>
							</td>
						</tr>
					</table>		
				</td>
			</tr>
			
</table>
<?php
# Close connection
$mydb->sql_close();
print $DrawerID;
?>
