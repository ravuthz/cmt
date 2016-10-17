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
	
	function checkValue(Amount){		
		if(Trim(Amount.value) != ""){
			if(!isNumber(Trim(Amount.value))){
				alert("Payment amount must be a number");
				Amount.focus();
				return;
			}else if(Number(Trim(Amount.value)) <= 0){
				alert("Payment amount must be greater than 0");
				Amount.focus();
				return;		
			}
		}
	}
	
	function check(index){
		numinv = fpayinvoiceall.totalinvoice.value;
		
		for (iLoop = 0; iLoop < numinv; iLoop++){
			if(index == 1)
				fpayinvoiceall.i[iLoop].checked = true;
			else
				fpayinvoiceall.i[iLoop].checked = false;
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
	if(!empty($smt) && isset($smt) && ($smt == "save57")){	
	/*	
		$i = 0;
			$totalPaid = 0.00;
			while($i <= $totalinvoice){
					if($l[$i]){				
						$red = false;									
						$AccountID = $AccID[$i];
						$Invoice = $InvoiceID[$i];
						$PaidAmount = $Amount[$i];
						$desc = $comment[$i];
											
						
						#	Book payment
						print "($DrawerID, $CustomerID, $AccountID, $Invoice, $ReceiptID, $PaidAmount, $selPaymentID, $TransactionMode, $desc, $CheckFrom, $CheckNumber, $ChequeDate);<br>";
												
						$totalPaid += $PaidAmount;
					}			
				$i ++;
			}
		print "<br><br>".$i."--".$totalPaid;
		*/
		$totalinvoice = FixQuotes($totalinvoice);
		$Finance = new Payment();
		$Audit = new Audit();
		#	Get drawer ID
		$DrawerID = $Finance->GetDrawerID($user['userid']);
		
		$GroupReceiptID = 0;
		# Transaction mode id:1 ==> Fee payment
		$TransactionMode = 1;
		$selPaymentID = 1; // pay in cash
		if($DrawerID == 0){
			$retOut = $myinfo->warning("Failed to book payment. No cash drawer open for cashier ".$user["FullName"]);
		}else{							
			$i = 0;
			$totalPaid = 0.00;
			while($i <= $totalinvoice){
					if($l[$i]){				
						$red = false;									
						$AccountID = $AccID[$i];
						$Invoice = $InvoiceID[$i];
						$PaidAmount = $Amount[$i];
						$desc = $comment[$i];
						$totalPaid += $PaidAmount;
						# Get receipt id
						$ReceiptID = $Finance->GetReceiptID();
						if($GroupReceiptID == 0) $GroupReceiptID = $ReceiptID;
						
						#	Book payment
						$retOut = $Finance->CreatePaymentReceipt($DrawerID, $CustomerID, $AccountID, $Invoice, $ReceiptID, $PaidAmount, $selPaymentID, $TransactionMode, $user["FullName"], $GroupReceiptID, $desc, $CheckFrom, $CheckNumber, $ChequeDate);
						
						if($retOut){
							$Description = "Make payment on Invoice No. $Invoice amount ".FormatCurrency($PaidAmount)."- $desc";
							$Audit->AddAudit($CustomerID, $AccountID, "Customer payment", $Description, "", 1, 4);							
							$red = true;
						}
						
					}			
				$i ++;
			}
			//print "total paid==> ".$totalPaid;
			if($red)
				redirect("./?CustomerID=".$CustomerID."&pg=43");
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
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID;?>&pg=10"><img src="./images/tab/customer.gif" name="customer" border="0" id="customer" onMouseOver="changeImage(1, './images/tab/customer_over.gif');" onMouseOut="changeImage(1, './images/tab/customer.gif');"/></a></td>						
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=90"><img src="./images/tab/product.gif" name="product" border="0" id="product" onMouseOver="changeImage(2, './images/tab/product_over.gif');" onMouseOut="changeImage(2, './images/tab/product.gif');" /></a></td>
						
						<td align="left" width="85"><img src="./images/tab/finance_active.gif" name="finance" border="0" id="finance" /></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=70"><img src="./images/tab/usage.gif" name="usage" border="0" id="usage" onMouseOver="changeImage(4, './images/tab/usage_over.gif');" onMouseOut="changeImage(4, './images/tab/usage.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=30"><img src="./images/tab/audit.gif" name="audit" border="0" id="audit" onMouseOver="changeImage(5, './images/tab/audit_over.gif');" onMouseOut="changeImage(5, './images/tab/audit.gif');" /></a></td>						
						
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
										<td align="left" class="formtitle"><b>PAY INVOICES AS CASH</td>
										<td align="right">
											<a href="javascript:check(1)">Check all</a> | <a href="javascript:check(2)">Uncheck all</a>
										</td>
									</tr> 				
									<tr>
										<td colspan="2">
											<form name="fpayinvoiceall" method="post" action="./">
												<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" bgcolor="#feeac2" style="border-collapse:collapse" bordercolor="#999999">													
													<tr>
														<th align="center">pay</th>
														<th align="center">Acc id</th>
														<th align="center">Invoice id</th>
														<th align="center">Invoice amount</th>
														<th align="center">Pay amount</th>
														<th align="center">Description</th>
													</tr>	
													<?php
														$disabled = "disabled";
														$sql1 = "SELECT InvoiceID, InvoiceAmount, UnpaidAmount, AccID 
																		FROM tblCustomerInvoice 
																		WHERE CustID = $CustomerID
																			AND UnpaidAmount > 0
																		ORDER BY InvoiceID";	
													
														if($que1 = $mydb->sql_query($sql1)){		
															$iLoop = 0;
															$total = 0.00;
															$totalunpaid = 0.00;
															while($result1 = $mydb->sql_fetchrow($que1)){
																 
																$InvoiceID = $result1['InvoiceID'];
																$InvoiceAmount = $result1['InvoiceAmount'];
																$UnpaidAmount = $result1['UnpaidAmount'];
																$AccID = $result1['AccID'];
																if(is_null($UnpaidAmount) || ($UnpaidAmount == "")) $UnpaidAmount = $InvoiceAmount;																																
																$sql = "SELECT SUM(PaymentAmount) as 'PaidAmount' 
																				FROM tblCustCashDrawer 
																				WHERE InvoiceID = $InvoiceID
																						AND (IsRollBack IS null or IsRollBack = 0)";																
																if($que = $mydb->sql_query($sql)){
																	$result = $mydb->sql_fetchrow($que);
																	$PaidAmount = $result['PaidAmount'];
																	if(is_null($PaidAmount) || ($PaidAmount == "")) $PaidAmount = 0;
																	$UnpaidAmount = floatval($InvoiceAmount) - floatval($PaidAmount);
																	if(floatval($UnpaidAmount) > 0){ 
																		$iLoop ++;
																		$disabled = "";
																		print '<tr>
																						<td>
																							<input type="checkbox" name="l['.$iLoop.']" id=i> 
																						</td>
																						<td>
																							<input type="text" name="AccID['.$iLoop.']" class="boxdisabled" size="5" value="'.$AccID.'" readonly="true">
																						</td>
																						<td>
																							<input type="text" name="InvoiceID['.$iLoop.']" class="boxdisabled" size="5" value="'.$InvoiceID.'" readonly="true">
																						</td>
																						<td>	
																							<input type="text" name="ttAmount['.$iLoop.']" class="boxdisabled" size="8" value="'.$InvoiceAmount.'" readonly="true">
																						</td>
																						<td>	
																							<input type="text" name="Amount['.$iLoop.']" class="boxenabled" size="8" value="'.$UnpaidAmount.'" onblur="checkValue(this);">
																						</td>		
																						<td>
																							<input type="text" name="comment['.$iLoop.']" class="boxenabled" size="40" value="Payment for invoice #'.$InvoiceID.'">
																						</td>																						
																					 </tr>	
																					';
																					$total += floatval($InvoiceAmount);
																					$totalunpaid += floatval($UnpaidAmount);
																	}
																	
																}else{
																	$error = $mydb->sql_error();				
																	$retOut = $myinfo->error("Failed to get previous paid amount.", $error['message']);
																	$disabled = "disabled=\"disabled\"";
																}			
															}
															print '
																					<tr>																						
																						<td colspan=3>Total</td>
																						<td><input type="text" value='.$total.' size="8" class="boxdisabled" readonly="true"></td>
																						<td><input type="text" value='.$totalunpaid.' size="8" class="boxdisabled" readonly="true"></td>
																						<td><input type="text" value='.$iLoop.'invoices size="40" class="boxdisabled" readonly="true"></td>
																					</tr>
																				';
														}
														$mydb->sql_freeresult();
													?>
													<tr><td colspan="6">&nbsp;</td></tr>								
													<tr> 				  
													<td align="center" colspan="6">
														<input type="reset" tabindex="8" name="reset" value="Reset" class="button" />
														<input type="submit" tabindex="9" name="Submit" value="Submit" class="button" <?php print $disabled; ?> />						
													</td>
												 </tr>
												 <?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"6\" align=\"left\">$retOut</td></tr>";
														}
													?>
												
												</table>
											<input type="hidden" name="totalinvoice" value="<?php print $iLoop; ?>" />	
											<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
											<input type="hidden" name="pg" value="57" />
											<input type="hidden" name="smt" value="save57" />
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
