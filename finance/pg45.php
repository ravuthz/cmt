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
<script language="JavaScript" src="./javascript/ajax_getamount.js"></script>
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
		if(fbookdeposit.selPaymentID.options[index].text == "Cheque"){
			fbookdeposit.CheckFrom.disabled = false;
			fbookdeposit.CheckNumber.disabled = false;
			fbookdeposit.ChequeDate.disabled = false;
			fbookdeposit.CheckFrom.className = "boxenabled";
			fbookdeposit.CheckNumber.className = "boxenabled";
			fbookdeposit.ChequeDate.className = "boxenabled";
			theCalendar.style.display = "block";
		}else{
			fbookdeposit.CheckFrom.disabled = true;
			fbookdeposit.CheckNumber.disabled = true;
			fbookdeposit.ChequeDate.disabled = true;
			fbookdeposit.CheckFrom.className = "boxdisabled";
			fbookdeposit.CheckNumber.className = "boxdisabled";
			fbookdeposit.ChequeDate.className = "boxdisabled";
			theCalendar.style.display = "none";
		}
	}
	function submitForm(){
		Amount = fbookdeposit.Amount;
		PaymentID = fbookdeposit.selPaymentID;
		CheckFrom = fbookdeposit.CheckFrom;
		CheckNumber = fbookdeposit.CheckNumber;
		ChequeDate = fbookdeposit.ChequeDate;
		Description = fbookdeposit.Description;
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
		}else if(Description.value == ""){
			alert("Please enter payment description");
			Description.focus();
			return;
		}
		fbookdeposit.btnSubmit.disabled = true;
		fbookdeposit.submit();
	}
	
	function RetriveAmount(){
		if(fbookdeposit.TransactionMode.selectedIndex > 0){
			AccountID = fbookdeposit.AccountID.options[fbookdeposit.AccountID.selectedIndex].value;
			type = fbookdeposit.TransactionMode.options[fbookdeposit.TransactionMode.selectedIndex].value;
			url = "./php/ajax_getamount.php?accid=" + AccountID + "&t=" + type;
			Amount(url, "ReqAmount", "Amount");
		}else{
			fbookdeposit.Amount.value = 0;
			fbookdeposit.ReqAmount.value = 0;
		}
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
		$AccountID = FixQuotes($AccountID);
		$TransactionMode = FixQuotes($TransactionMode);
		$Amount = FixQuotes($Amount);
		$CheckFrom = FixQuotes($CheckFrom);
		$CheckNumber = FixQuotes($CheckNumber);
		$ChequeDate = FixQuotes($ChequeDate);
		$Description = FixQuotes($Description);
		

		#	Get drawer ID
		$DrawerID = $Finance->GetDrawerID($user['userid']);
		if($DrawerID == 0){
			$retOut = $myinfo->warning("Failed to book deposit. No cash drawer open for cashier ".$user["FullName"]);
		}else{								
			
			# Get receipt id
			$ReceiptID = $Finance->GetReceiptID();
			
			#	Book payment
			$retOut = $Finance->CreatePaymentReceipt($DrawerID, $CustomerID, $AccountID, 0, $ReceiptID, $Amount, $selPaymentID, $TransactionMode, $user["FullName"], $ReceiptID, $Description, $CheckFrom, $CheckNumber, $ChequeDate);
			if($retOut){
				$Description = "Book deposit ".FormatCurrency($Amount)."- $Description";
				$Audit->AddAudit($CustomerID, $AccountID, "Book deposit", $Description, "", 1, 4);
				print "<script>window.open(\"./finance/receipt.php?CustomerID=".$CustomerID."&PaymentID=".$ReceiptID."\");</script>";
				redirect("./?CustomerID=".$CustomerID."&pg=43");
			}
		}			
	}
						
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
										<td align="left" class="formtitle"><b>BOOK DEPOSIT</b></td>
										<td align="right">&nbsp;
											
										</td>
									</tr> 				
									<tr>
										<td colspan="2">
											<form name="fbookdeposit" method="get" action="./" onSubmit="return false;">
												<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" bgcolor="#feeac2">																
													<tr>
														<td align="left">Account:</td>
														<td align="left">
															<select name="AccountID" class="boxenabled" tabindex="1" onchange="RetriveAmount();">																				
																<?php
																	$sql = "SELECT AccID, UserName = Case
																										When StatusID = 1 then UserName + '_' + convert(varchar,accid) + ' ' + '(Active)'
																										When StatusID = 4 then UserName + '_' + convert(varchar,accid) + ' ' + '(Close)'
																										Else UserName + '_' + convert(varchar,accid) + ' ' + '(InActive)'
																										End
																										
																										 from tblCustProduct where CustID = $CustomerID
																										 --and StatusID <> 4
																										 order by StatusID, tblCustProduct.UserName, AccID desc";													
																	
																	$que = $mydb->sql_query($sql);									
																	if($que){
																		while($rst = $mydb->sql_fetchrow($que)){	
																			$AccID = $rst['AccID'];
																			$UserName = $rst['UserName'];									
																			print "<option value='".$AccID."'>".$UserName."</option>";
																		}
																	}
																	$mydb->sql_freeresult();
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td align="left">Deposit type:</td>
														<td align="left">
															<select name="TransactionMode" class="boxenabled" tabindex="2" onchange="RetriveAmount();">																				
																<option value="0">Select deposit type</option>
																<option value="4">National call deposit</option>
																<option value="5">International call deposit</option>
																<option value="6">Monthly fee deposit</option>
															</select>
														</td>
													</tr>
													<tr>
														<td align="left" nowrap="nowrap">Require deposit:</td>
														<td align="left"><input type="text" name="ReqAmount" id="ReqAmount" readonly="true" tabindex="3" class="boxdisabled" size="20" value="" /></td>					
													</tr>
													<tr><td colspan="2">&nbsp;</td></tr>
													<tr>
														<td align="left" nowrap="nowrap">Book amount:</td>
														<td align="left"><input type="text" name="Amount" id="Amount" tabindex="3" class="boxenabled" size="20" value="<?php print $UnpaidAmount; ?>" /></td>					
													</tr>
													<tr>
														<td align="left">Payment mode:</td>
														<td align="left">
															<select name="selPaymentID" class="boxenabled" tabindex="4" onChange="ChangePayment(this.selectedIndex);">																				
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
															</select>
														</td>
													</tr>
													<tr>
														<td align="left">Cheque from:</td>
														<td align="left">
															<input type="text" name="CheckFrom" class="boxdisabled" size="50" tabindex="5" disabled="disabled" />
														</td>
													</tr>
													<tr>
														<td align="left">Cheque number:</td>
														<td align="left">
															<input type="text" name="CheckNumber" class="boxdisabled" size="50" tabindex="6" disabled="disabled" />
														</td>
													</tr>
													<tr>
														<td align="left" nowrap="nowrap">Dated of issue:</td>
														<td align="left">
															<input type="text" name="ChequeDate" class="boxdisabled" size="20" tabindex="7" disabled="disabled" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
															<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fbookdeposit|ChequeDate', '', 'width=200,height=220,top=250,left=350');">
															<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0" id="calenda" style="display:none">
														</button>									
														</td>
													</tr>
													<tr>
														<td align="left" valign="top">Description:</td>
														<td align="left">
															<textarea name="Description" cols="38" rows="5" tabindex="8" class="boxenabled"></textarea>
														</td>
													</tr>
													<tr><td colspan="2">&nbsp;</td></tr>								
													<tr> 				  
													<td align="center" colspan="2">
														<input type="reset" tabindex="9" name="reset" value="Reset" class="button" />
														<input type="submit" tabindex="10" name="btnSubmit" value="Submit" class="button" <?php print $disabled; ?> onClick="submitForm();" />						
													</td>
												 </tr>
												 <?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
														}
													?>
												
												</table>
											<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
											<input type="hidden" name="pg" value="45" />
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
?>
