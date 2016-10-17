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
<script language="JavaScript" src="./javascript/ajax_location.js"></script>
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
	
	function ValidateForm(){
		newtype = feditbillinfo.TypeID.options[feditbillinfo.TypeID.selectedIndex].text;
		feditbillinfo.btnSave.disabled = true;
		feditbillinfo.submit();
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
	
	//
	//	Save customer information
	//	
	if(!empty($smt) && isset($smt) && ($smt == "pg23")){
		$TypeID = FixQuotes($TypeID);
		$oldtype = FixQuotes($oldtype);
		$newtype = FixQuotes($newtype);
		
		$Operator = $user['FullName'];
		 
		$audit = new Audit();
		
		$sql = "UPDATE tblCustomer SET 
							CustTypeID = '".$TypeID."' 												
						WHERE CustID=$CustomerID  
					 ";
		if($mydb->sql_query($sql)){			
			$title = "Update customer type";
			$description = "Change customer type from $oldtype to $newtype";

			$audit->AddAudit($CustomerID, "", $title, $description, $Operator, 1, 7);	
			redirect('./?CustomerID='.$CustomerID.'&pg=10');
		}else{
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to update customer type.", $error['message']);
		}
	}
	
	# =============== Get customer information =====================	
	$sql = "select t.CustTypeID, t.CustTypeName	
					from tblCustomer c, tlkpCustType t
					where c.CustTypeID = t.CustTypeID AND c.CustID=$CustomerID";
					
	if($que = $mydb->sql_query($sql)){
		if($rst = $mydb->sql_fetchrow($que)){
			$CustTypeID = $rst['CustTypeID'];
			$CustTypeName = $rst['CustTypeName'];									
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
						
						<td align="left" width="85"><img src="./images/tab/customer_active.gif" name="customer" border="0" id="customer" /></td>						
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=90"><img src="./images/tab/product.gif" name="product" border="0" id="product" onMouseOver="changeImage(2, './images/tab/product_over.gif');" onMouseOut="changeImage(2, './images/tab/product.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=41"><img src="./images/tab/finance.gif" name="finance" border="0" id="finance" onMouseOver="changeImage(3, './images/tab/finance_over.gif');" onMouseOut="changeImage(3, './images/tab/finance.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=70"><img src="./images/tab/usage.gif" name="usage" border="0" id="usage" onMouseOver="changeImage(4, './images/tab/usage_over.gif');" onMouseOut="changeImage(4, './images/tab/usage.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID;?>&pg=30"><img src="./images/tab/audit.gif" name="audit" border="0" id="audit" onMouseOver="changeImage(5, './images/tab/audit_over.gif');" onMouseOut="changeImage(5, './images/tab/audit.gif');"/></a></td>						
						
						<td align="center" width="*" background="./images/tab_null.gif">&nbsp;</td>		
					</tr>
				</table>
					<!-- end customer table menu -->			
			</td>
		</tr>
		<tr>
			<td height="100%" valign="top">
					<!-- Individual customer main page -->				
					<table border="0" cellpadding="0" cellspacing="5" width="100%" height="100%" align="left">						
						<tr>
							<td width="150" valign="top"><?php include("content.php");?></td>
							<!-- Customer information -->
							<td align="left" valign="top">
								<form name="feditbillinfo" method="post" action="./">
									<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
										<tr>
											<td align="left" class="formtitle" height="18"><b>Edit customer type</b></td>
										</tr>
										<tr>
											<td valign="top">
												<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2"> 							
													<tr>
														<td align="left" nowrap="nowrap">Current type:</td>
														<td align="left" nowrap="nowrap"><b><?php print $CustTypeName; ?></b></td>
													</tr>
													<tr>
														<td align="left" nowrap="nowrap">New type:</td>
														<td align="left">
															<select name="TypeID">
															<?php
																$sql = "SELECT CustTypeID, CustTypeName FROM tlkpCustType WHERE CustTypeID <> $CustTypeID";
																if($que = $mydb->sql_query($sql)){
																	while($result = $mydb->sql_fetchrow($que)){
																		$typeid = $result['CustTypeID'];
																		$TypeName = $result['CustTypeName'];
																		print "<option value='".$typeid."'>".$TypeName."</option>";
																	}
																}
															?>
															</select>
														</td>
														<td align="left" width="80%">&nbsp;</td>
													</tr>														
													<tr><td colspan="3">&nbsp;</td></tr>
													<tr><td align="center" colspan="3">											
														<input type="reset" tabindex="14" name="reset" value="Reset" class="button" />
														<input type="button" tabindex="15" name="btnSave" value="Save" class="button" onClick="ValidateForm();" />
													</td></tr>
													<?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"3\" align=\"left\">$retOut</td></tr>";
														}
													?>
												</table>
											</td>
										</tr>										
									</table>
									<input type="hidden" name="pg" id="pg" value="23" />
									<input type="hidden" name="smt" id="smt" value="pg23" />
									<input type="hidden" name="oldtype" id="oldtype" value="<?php print $CustTypeName; ?>" />
									<input type="hidden" name="newtype" value="" />									
									<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />																	
								</form>
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
