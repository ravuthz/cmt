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
	
	function ValidateForm(){
		context = fAddAudit.context;
		description = fAddAudit.description;
		if(context.value == ""){
			alert("Please enter title of your audit comment");
			context.focus();
			return;
		}else if(description.value == ""){
			alert("Please enter audit description");
			description.focus();
			return;
		}
		fAddAudit.btnNext.disabled = true;
		fAddAudit.submit();
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
			$context = FixQuotes($context);
			$description = FixQuotes($description);
			$Operator = $user['FullName'];
			$Audit = new Audit();
			$ret = $Audit->AddAudit($CustomerID, "", $context, $description, $Operator, 1, 2);
			if(is_bool($ret))
				$retOut = $myinfo->info("Successfully add audit comment.");
			else
				$retOut = $myinfo->info("Failed to add audit comment.", $ret);
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
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=41"><img src="./images/tab/finance.gif" name="finance" border="0" id="finance" onMouseOver="changeImage(3, './images/tab/finance_over.gif');" onMouseOut="changeImage(3, './images/tab/finance.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=70"><img src="./images/tab/usage.gif" name="usage" border="0" id="usage" onMouseOver="changeImage(4, './images/tab/usage_over.gif');" onMouseOut="changeImage(4, './images/tab/usage.gif');" /></a></td>
						
						<td align="left" width="85"><img src="./images/tab/audit_active.gif" name="audit" border="0" id="audit" /></td>						
						
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
										<td align="left" class="formtitle"><b>Add comment</b></td>
										<td align="right"></td>
									</tr> 
									<tr>
										<form name="fAddAudit" method="get" action="./">
											<td colspan="2">						
												<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">								 							
													<tr>
														<td align="left">Title</td>
														<td align="left"><input type="text" name="context" tabindex="1" class="boxenabled" size="80" value="<?php print $context;?>"><img src="./images/required.gif" border="0" /></td>
													</tr>
													<tr>
														<td valign="top" align="left" nowrap="nowrap">Description</td>
														<td valign="top" align="left"><textarea name="description" tabindex="2" class="boxenabled" rows="10" cols="60"><?php print $description;?></textarea><img src="./images/required.gif" border="0" /></td>
													</tr>
													<tr><td colspan="2">&nbsp;</td></tr>								
															<tr> 				  
															<td align="center" colspan="2">						
																<input type="reset" tabindex="3" name="reset" value="Reset" class="button" />
																<input type="button" tabindex="4" name="btnNext" value="Save" class="button" onClick="ValidateForm();" />						
															</td>
														 </tr>
														 <?php
																if(isset($retOut) && (!empty($retOut))){
																	print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
																}
															?>
												</table>						
											</td>
											<input type="hidden" name="pg" id="pg" value="34" />
											<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />	
											<input type="hidden" name="smt" value="save" />	
										</form>
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
