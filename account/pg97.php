<?php
	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");	
	require_once("./common/functions.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	
	
	
?>
<script language="javascript" type="text/javascript" src="./javascript/date.js"></script>
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
		Amount = foneadjust.txtAmount;
		if(Trim(Amount.value == "")){
			alert("Please enter credit amount");
			Amount.focus();
			return;
		}else if(!isNumber(Trim(Amount.value))){
			alert("credit amount must be a number");
			Amount.focus();
			return;
		}else if(Number(Trim(Amount.value)) < 0){
			alert("Ccredit amount must be greater than 0");
			Amount.focus();
			return;		
		}else if(foneadjust.txtDescription.value == ""){
			alert("Please enter description");
			foneadjust.txtDescription.focus();
			return;
		}else{
			foneadjust.btnSubmit.disabled = true;
			foneadjust.submit();
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
						
		if(!empty($smt) && isset($smt) && ($smt == "save97")){		
			$Audit = new Audit();
			$txtAmount = FixQuotes($txtAmount);
			$txtDescription = FixQuotes($txtDescription);
			$txtExpire = FixQuotes($txtExpire);
			$credittype = FixQuotes($credittype);
			$error = false;
			if(!empty($txtExpire)){
				if(datediff($txtExpire) > 0){
					$retOut = $myinfo->warning("Expired date must later than today");
					$error = true;
				}
			}else{
				$txtExpire = NULL;
			}
			
			if(floatval($txtAmount) <= 0){
					$retOut = $myinfo->error("Failed to add recurring credit adjustment", "Recurring credit adjustement must be greater than 0");
					$error = true;						
			}else{
				 if(($IsPercent == 1) && (floatval($txtAmount) > 100)){
				 $retOut = $myinfo->error("Failed to add percentage recurring credit adjustment", "Percentage recurring credit adjustement must be greater less than 100");
					$error = true;
				}
			}
			if(!$error){
				$now = date("Y/M/d H:i:s");
				$Operator = $user["FullName"];
				$CreditType = 2; # One time credit
				$InvoiceItemID = 13; # one time credit
				
				#____Insert to credit credit allowwance
			#____Insert to credit credit allowwance
			$sql = "";
			if(is_null($txtExpire) || ($txtExpire == "")){
			$sql = "INSERT INTO tblAccCreditAllowance(AccID, CreditDes, Credit, CreditType, InvItemID, 
																								CreditExpire, IsPercentage, SubmittedDate, SubmittedBy) 
														VALUES('".$AccountID."', '".$txtDescription."', ".$txtAmount.", ".$CreditType.", ".$InvoiceItemID.", 
																								NULL, ".$IsPercent.", '".$now."', '".$Operator."')";
																								
			}else{
			$sql = "INSERT INTO tblAccCreditAllowance(AccID, CreditDes, Credit, CreditType, InvItemID, 
																								CreditExpire, IsPercentage, SubmittedDate, SubmittedBy) 
														VALUES('".$AccountID."', '".$txtDescription."', ".$txtAmount.", ".$CreditType.", ".$InvoiceItemID.", 
																								'".$txtExpire."', ".$IsPercent.", '".$now."', '".$Operator."')";
			}				
				if($mydb->sql_query($sql)){
					#Create credit history
					if(is_null($txtExpire) || ($txtExpire == "")){
						$sql = "INSERT INTO tblHisAdjustment(CustomerID, AccountID, Amount, DisType, DiscountDate, 
													Operator, Description)
										VALUES($CustomerID, $AccountID, $txtAmount, $CreditType, GetDate(), 
													'".$user['FullName']."', '".$txtDescription."')";
					}else{
						$sql = "INSERT INTO tblHisAdjustment(CustomerID, AccountID, Amount, DisType, DiscountDate, 
													ExpiredDate, Operator, Description)
										VALUES($CustomerID, $AccountID, $txtAmount, $CreditType, GetDate(), '".$txtExpire."', 
													'".$user['FullName']."', '".$txtDescription."')";
					}
					if($mydb->sql_query($sql)){			
						if(!empty($txtExpire))
						$expire = "expire on ".formatDate($txtExpire, 6);
						$Description = "Add recurring credit adjustment amount ".FormatCurrency($txtAmount)." $expire. $txtDescription";
						$Audit->AddAudit($CustomerID, $AccountID, "Recurring credit adjustment", $Description, $user['FullName'], 1, 12);
						redirect('./?CustomerID='.$CustomerID.'&AccountID='.$AccountID.'&pg=98');
					}else{
						$error = $mydb->sql_error();
						$retOut = $myinfo->error("Failed to create credit adjustment history.", $error['message']);
					}
				}else{	
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to add recurring credit adjustment.", $error['message']);				
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
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID;?>&pg=10"><img src="./images/tab/customer.gif" name="customer" border="0" id="customer" onMouseOver="changeImage(1, './images/tab/customer_over.gif');" onMouseOut="changeImage(1, './images/tab/customer.gif');"/></a></td>						
						
						<td align="left" width="85"><img src="./images/tab/product_active.gif" name="product" border="0" id="product" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=41"><img src="./images/tab/finance.gif" name="finance" border="0" id="finance" onMouseOver="changeImage(3, './images/tab/finance_over.gif');" onMouseOut="changeImage(3, './images/tab/finance.gif');" /></a></td>
						
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
									 	<td align="left" class="formtitle"><b>RECURRING CREDIT ADJUSTMENT
										- <?php print $aSubscriptionName." (".$aUserName.")"; ?></b>
										</td>
										<td align="right"></td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<form name="foneadjust" method="post" action="./" onSubmit="return false;">
											<table border="0" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">																																				
												<tr>
													<td align="left" nowrap="nowrap">Credit amount:</td>
													<td align="left">
														<input type="text" name="txtAmount" tabindex="1" class="boxenabled" size="35" maxlength="100" />
													</td>
												</tr>
												<tr>
													<td colspan="2">
														<input type="radio" name="IsPercent" value="0" checked="checked" /> Is fixed amount<br />
														<input type="radio" name="IsPercent" value="1" /> Is percentage
													</td>
												</tr>
												<tr>
														<td align="left">Expiry date:</td>
														<td align="left">
															<input type="text" tabindex="2" name="txtExpire" class="boxenabled" size="27" maxlength="30" value="<?php print $txtCustDOB; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?foneadjust|txtExpire', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
														</td>
													</tr>
												<tr>
													<td align="left" nowrap="nowrap">Comment:</td>
													<td align="left">
														<input type="text" name="txtDescription" tabindex="3" class="boxenabled" size="70" maxlength="100" />
													</td>
												</tr>
												<tr><td colspan="2">&nbsp;</td></tr>								
													<tr> 				  
													<td align="center" colspan="2">
														<input type="reset" tabindex="4" name="reset" value="Reset" class="button" />
														<input type="submit" tabindex="5" name="btnSubmit" value="Submit" class="button" onClick="submitForm();" />						
													</td>
												 </tr>
												 <?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
														}
													?>									
											</table>
											<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
											<input type="hidden" name="AccountID" value="<?php print $AccountID; ?>" />
											<input type="hidden" name="pg" value="97" />
											<input type="hidden" name="smt" value="save97" />
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
