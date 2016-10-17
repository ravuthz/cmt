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
		
	function submitForm(){
		Amount = frefundcredit.Amount;
		CurAmount = frefundcredit.CurAmount;		
		Description = frefundcredit.Description;
		selAccount = frefundcredit.AccountID;
		if(selAccount.selectedIndex < 1){
			alert("Please select account to refund balance");
			selAccount.focus();
			return;		
		}else	if(Trim(Amount.value == "")){
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
		}else if(Number(Trim(Amount.value)) > Number(Trim(CurAmount.value))){
			alert("Unabled to refund balance amount more than balance remain.");
			CurAmount.focus();
			return;	
		}else if(Description.value == ""){
			alert("Please enter payment description");
			Description.focus();
			return;
		}
		frefundcredit.btnSubmit.disabled = true;
		frefundcredit.submit();
	}
	
	function AutoValue(index){
		if(index > 0)
			frefundcredit.CurAmount.value = frefundcredit.temCredit.options[index].value;
		else
			frefundcredit.CurAmount.value = 0.00;
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
		
	if(!empty($smt) && isset($smt) && ($smt == "save47")){
		$Finance = new Payment();
		$Audit = new Audit();
		$AccountID = FixQuotes($AccountID);
		$Amount = FixQuotes($Amount);
		$CheckFrom = FixQuotes($CheckFrom);
		$CheckNumber = FixQuotes($CheckNumber);
		$ChequeDate = FixQuotes($ChequeDate);
		$Description = FixQuotes($Description);
		
		$DrawerID = $Finance->GetDrawerID($user['userid']);
		if($DrawerID == 0){
			$retOut = $myinfo->warning("Failed to refund balance. No cash drawer open for cashier ".$user["FullName"]);
		}else{						
			if(floatval($Amount) > floatval($CurAmount)){
				$retOut = $myinfo->error("Unabled to refund balance amount more than balance remain");
			}else{
			
				# Check if amount to be refund is less than refund amount.
				
				
				# Transaction mode id:1 ==> Fee payment
				$TransactionMode = 2;
				
				# Get receipt id
				$ReceiptID = $Finance->GetReceiptID();
				
				# Refund in cash
				$selPaymentID = 1;
				
				#	Book payment
				$retOut = $Finance->CreatePaymentReceipt($DrawerID, $CustomerID, $AccountID, 0, $ReceiptID, $Amount, $selPaymentID, $TransactionMode, $user["FullName"], $ReceiptID, $Description, $CheckFrom, $CheckNumber, $ChequeDate);
				if($retOut){
					$Description = "Refund credit ".FormatCurrency($Amount)."- $Description";
					$Audit->AddAudit($CustomerID, $AccountID, "Refund credit", $Description, "", 1, 5);
				}	
			}
		}		
	}
	
	
		$sql = "SELECT a.AccID, UserName = Case
											When StatusID = 1 then a.UserName + '_' + convert(varchar,a.accid) + ' ' + '(Active)'
											When StatusID = 4 then a.UserName + '_' + convert(varchar,a.accid) + ' ' + '(Close)'
											Else a.UserName + '_' + convert(varchar,a.accid) + ' ' + '(InActive)'
										   End
					, b.Credit 
						from tblCustProduct a, tblAccountBalance b 
						where a.AcciD = b.AccID and a.CustID = $CustomerID
						order by StatusID, a.UserName , a.AccID desc ";													

		$que = $mydb->sql_query($sql);									
		if($que){
			$opt1 = "";
			$opt2 = "";
			while($rst = $mydb->sql_fetchrow($que)){	
				$AccID = $rst['AccID'];
				$UserName = $rst['UserName'];
				$cCredit = $rst['Credit'];									
				$opt1 .=  "<option value='".$AccID."'>".$UserName."</option>";
				$opt2 .=  "<option value='".$cCredit."'>".$cCredit."</option>";
			}
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
										<td align="left" class="formtitle"><b>REFUND BALANCE</b></td>
										<td align="right">&nbsp;
											
										</td>
									</tr> 				
									<tr>
										<td colspan="2">
											<form name="frefundcredit" method="get" action="./" onSubmit="return false;">
												<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" bgcolor="#feeac2">																
													<tr>
														<td align="left">Account:</td>
														<td align="left">
															<select name="AccountID" class="boxenabled" tabindex="1" onchange="AutoValue(this.selectedIndex);">
																<option value="0">Select account</option>																				
																<?php print $opt1; ?>
															</select>
														</td>
													</tr>
													<tr>
														<td align="left" nowrap="nowrap">Current balance:</td>
														<td align="left"><input type="text" name="CurAmount" id="CurAmount" tabindex="2" class="boxdisabled" readonly="true" size="20" value="0.00" /></td>					
													</tr>
													<tr><td colspan="2">&nbsp;</td></tr>
													<tr>
														<td align="left" nowrap="nowrap">Refund amount:</td>
														<td align="left"><input type="text" name="Amount" id="Amount" tabindex="2" class="boxenabled" size="20" value="<?php print $UnpaidAmount; ?>" /></td>					
													</tr>								
													<tr>
														<td align="left" valign="top">Description:</td>
														<td align="left">
															<textarea name="Description" cols="38" rows="5" tabindex="7" class="boxenabled"></textarea>
														</td>
													</tr>
													<tr><td colspan="2">&nbsp;</td></tr>								
													<tr> 				  
													<td align="center" colspan="2">
														<input type="reset" tabindex="8" name="reset" value="Reset" class="button" />
														<input type="submit" tabindex="9" name="btnSubmit" value="Submit" class="button" <?php print $disabled; ?> onClick="submitForm();" />						
													</td>
												 </tr>
												 <?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
														}
													?>
												
												</table>
											<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
											<input type="hidden" name="pg" value="47" />
											<input type="hidden" name="smt" value="save47" />
											<select name="temCredit" style="display:none">
												<option value="0"></option>
												<?php print $opt2; ?>
											</select>
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
