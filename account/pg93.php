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
	
	function checkPass(newPass, ConPass){
		if(newPass == ConPass)
			return true;
		else
			return false;
	}
	
	function submitForm(){
		if(Trim(fchangepass.txtNewPass.value) == ""){
			alert("Please enter new password");
			fchangepass.txtNewPass.focus();
			return;
		}else if(fchangepass.txtNewPass.value.length < 6){
			alert("Password must be 6 to 20 character length");
			fchangepass.txtNewPass.focus();
			return;
		}else if(!checkPass(fchangepass.txtNewPass.value, fchangepass.txtConPass.value)){
			alert("New password and confirm password must be the same");
			fchangepass.txtNewPass.focus();
			return;
		}	
			fchangepass.btnSubmit.disabled = true;
			fchangepass.submit();
		
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
						
		if(!empty($smt) && isset($smt) && ($smt == "save93")){		
			$Audit = new Audit();			
			$txtNewPass = FixQuotes($txtNewPass);
			//$txtNewPass = md5($txtNewPass);
			
			// Get old password
			
			$sql = "UPDATE tblCustProduct SET Password = '".$txtNewPass."' WHERE AccID = $AccountID";
			if($mydb->sql_query($sql)){
				$Description = "Change account password for account $AccountID";
				$Audit->AddAudit($CustomerID, $AccountID, "Change account password", $Description, "", 1, 11);
				redirect('./?CustomerID='.$CustomerID.'&AccountID='.$AccountID.'&pg=91');
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to change account password.", $error['message']);
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
									 	<td align="left" class="formtitle"><b>CHANGE ACCOUNT PASSWORD
										- <?php print $aSubscriptionName." (".$aUserName.")"; ?></b>
										</td>
										<td align="right"></td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<form name="fchangepass" method="post" action="./" onSubmit="return false;">
											<table border="0" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">																																				
												<tr>
													<td align="left" nowrap="nowrap">New password:</td>
													<td align="left">
														<input type="password" name="txtNewPass" tabindex="2" class="boxenabled" size="40" maxlength="100" />
													</td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">Confirm password:</td>
													<td align="left">
														<input type="password" name="txtConPass" tabindex="3" class="boxenabled" size="40" maxlength="100" />
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
											<input type="hidden" name="pg" value="93" />
											<input type="hidden" name="smt" value="save93" />
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
