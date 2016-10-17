<?php
	require_once("./common/agent.php");	
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
		txtDesc = frollback.txtDesc;		
		
		if(Trim(txtDesc.value) == ""){
			alert("Please enter rollback description");
			txtDesc.focus();
			return;
		}else{
			frollback.btnSubmit.disabled = true;
			frollback.submit();
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
		
	if(!empty($smt) && isset($smt) && ($smt == "save50")){
		$audit = new Audit();
		$txtDesc = FixQuotes($txtDesc);
		$now = date("Y/M/d H:i:s");
		$sql = "INSERT INTO tblPaymentRollBack(DrawerID, CustID, InvoiceID, PaymentAmount, PaymentDate, Description, Cashier, 
												PaymentModelID, TransactionModeID, IsSubmitted, IsActive, PaymentID, CheckFrom, CheckNumber,
												IssuedDate, AcctID, IsRollback, ProcessDate, GroupPaymentID, rollbackdate, remarks)
						SELECT  DrawerID, CustID, InvoiceID, PaymentAmount, PaymentDate, Description, Cashier, 
												PaymentModelID, TransactionModeID, IsSubmitted, IsActive, PaymentID, CheckFrom, CheckNumber,
												IssuedDate, AcctID, IsRollback, ProcessDate, GroupPaymentID, '".$now."', '".$txtDesc."' 
						FROM tblCustCashDrawer 
						WHERE CustID = $CustomerID and PaymentID = $PaymentID";

		if($mydb->sql_query($sql)){
			$sql = "update tblCustCashDrawer set IsRollback = 1 where PaymentID = $PaymentID";
			if($mydb->sql_query($sql)){			
				$title = "Rollback payment $PaymentID";
				$comment = "Rollback payment $PaymentID; amount: $PaymentAmount as ".$txtDesc;
				$audit->AddAudit($CustomerID, $AccountID, $title, $comment, $user['FullName'], 1, 10);
				redirect('./?CustomerID='.$CustomerID.'&pg=49');
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to update payment rolback in cash drawer.", $error['message']);
			}
		}else{
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to insert payment rollback history.", $error['message']);
		}		
	}
	
	$sql = "select d.DrawerID, d.PaymentAmount, d.PaymentDate, d.Description, d.Cashier, 
						t.TransactionName, d.PaymentModelID, p.PaymentMode, d.CheckFrom, d.CheckNumber, d.IssuedDate, d.AcctID, ac.UserName
					from tblCustCashDrawer d, tlkpTransaction t, tlkpPaymentMode p, tblCustProduct ac
					where d.TransactionModeID = t.TransactionID and d.PaymentModelID = p.PaymentID
						and d.IsActive = 1 and d.IsSubmitted = 0 and (d.IsRollback = 0 or d.IsRollback is null) 
						and d.AcctID = ac.AccID and d.CustID = $CustomerID and d.PaymentID = $PaymentID";

	if($que = $mydb->sql_query($sql)){		
		if($result = $mydb->sql_fetchrow()){
			$DrawerID = $result['DrawerID'];
			$PaymentAmount = $result['PaymentAmount'];
			$PaymentDate = $result['PaymentDate'];
			$Description = $result['Description'];
			$Cashier = $result['Cashier'];
			$TransactionName = $result['TransactionName'];
			$PaymentModelID = $result['PaymentModelID'];
			$PaymentMode = $result['PaymentMode'];
			$CheckFrom = $result['CheckFrom'];
			$CheckNumber = $result['CheckNumber'];
			$IssuedDate = $result['IssuedDate'];
			$AcctID = $result['AcctID'];
			$UserName = $result['UserName'];
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
							<td valign="top" width="180" rowspan="3">
								<?php include("content.php"); ?>
							</td>
							<td valign="top" align="left">
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
									 	<td align="left" class="formtitle"><b>PAYMENT TRANSACTION</b></td>
										<td align="right"></td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">												
												<tr>
													<td align="left" nowrap="nowrap">Drawer id:</td>
													<td align="left"><b><?php print $DrawerID;?></b></td>
													<td align="left" nowrap="nowrap">Transaction name:</td>
													<td align="left" ><b><?php print $TransactionName; ?></b></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">Date:</td>
													<td align="left"><b><?php print $PaymentDate;?></b></td>
													<td align="left">Amount:</td>
													<td align="left"><b><?php print FormatCurrency($PaymentAmount); ?></b></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">Paid as:</td>
													<td align="left"><b><?php print $PaymentMode;?></b></td>
													<td align="left">Cashier:</td>
													<td align="left"><b><?php print $Cashier; ?></b></td>
												</tr>
												<?php
													if($PaymentModelID == 2){
														echo '<tr><td align=left colspan=4>Check from: $CheckFrom; Check number: $CheckNumber; Issued date: '.formatDate($IssuedDate, 5).'</td></tr>';
													}
												?>	
												<tr>
													<td align="left" nowrap="nowrap">Account:</td>
													<td align="left"><b><?php print $UserName;?></b></td>
													<td align="left">Description:</td>
													<td align="left"><b><?php print $Description; ?></b></td>
												</tr>									
											</table>
										</td>
									</tr>							
								</table>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td valign="top">
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
									 	<td align="left" class="formtitle"><b>ROLLBACK TRANSACTION</b></td>
										<td align="right"></td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<form name="frollback" method="get" action="./" onSubmit="return false;">
											<table border="0" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">												
												<tr>
													<td align="left" nowrap="nowrap" valign="top">Description:</td>
													<td align="left">
														<textarea name="txtDesc" cols="50" rows="5" class="boxenabled" tabindex="1"></textarea>
													</td>													
												</tr>
												<tr><td colspan="2">&nbsp;</td></tr>								
													<tr> 				  
													<td align="center" colspan="2">
														<input type="reset" tabindex="2" name="reset" value="Reset" class="button" />
														<input type="submit" tabindex="3" name="btnSubmit" value="Rollback" class="button" <?php print $disabled; ?> onClick="submitForm();" />						
													</td>
												 </tr>
												 <?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
														}
													?>									
											</table>
											<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
											<input type="hidden" name="AccountID" value="<?php print $AcctID; ?>" />
											<input type="hidden" name="PaymentID" value="<?php print $PaymentID; ?>" />
											<input type="hidden" name="PaymentAmount" value="<?php print FormatCurrency($PaymentAmount); ?>" />
											<input type="hidden" name="pg" value="50" />
											<input type="hidden" name="smt" value="save50" />
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
