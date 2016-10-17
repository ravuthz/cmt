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
	//
	// Store name value
	//
	function storeNameValue(index, cat){		
		
		if(cat == 5){
			feditbillinfo.txtBilSangkat.value = feditbillinfo.selBilSangkat.options[index].text;
		}
		else if(cat == 6){
			feditbillinfo.txtBilKhan.value = feditbillinfo.selBilKhan.options[index].text;
			location(4, feditbillinfo.selBilKhan.options[index].value, "selBilSangkat");
		}
		else if(cat == 7){
			feditbillinfo.txtBilCity.value = feditbillinfo.selBilCity.options[index].text;						
			location(3, feditbillinfo.selBilCity.options[index].value, "selBilKhan");
		}			
		else if(cat == 8){
			feditbillinfo.txtBilCountry.value = feditbillinfo.selBilCountry.options[index].text;			
			location(2, feditbillinfo.selBilCountry.options[index].value, "selBilCity");
		}
													
	}
	//
	//	Validate form
	//
	function ValidateForm(){
		if(Trim(feditbillinfo.txtBilAddress.value) == ""){
			alert("Please enter billing address");
			feditbillinfo.txtBilAddress.focus();
			return;
		}else if(feditbillinfo.selBilCountry.options[feditbillinfo.selBilCountry.selectedIndex].text == "Unknown"){
			alert("Pleas select billing country");
			feditbillinfo.selBilCountry.focus();
			return;
		}else if(feditbillinfo.selBilCity.options[feditbillinfo.selBilCity.selectedIndex].text == "Unknown"){
			alert("Pleas select billing city");
			feditbillinfo.selBilCity.focus();
			return;
		}else if(feditbillinfo.selBilKhan.options[feditbillinfo.selBilKhan.selectedIndex].text == "Unknown"){
			alert("Pleas select billing khan");
			feditbillinfo.selBilKhan.focus();
			return;
		}else if(feditbillinfo.selBilSangkat.options[feditbillinfo.selBilSangkat.selectedIndex].text == "Unknown"){
			alert("Pleas select billing sangkat");
			feditbillinfo.selBilSangkat.focus();
			return;
		}
		
		feditbillinfo.txtBilCountry.value = feditbillinfo.selBilCountry.options[feditbillinfo.selBilCountry.selectedIndex].text;
		feditbillinfo.txtBilCity.value = feditbillinfo.selBilCity.options[feditbillinfo.selBilCity.selectedIndex].text;
		feditbillinfo.txtBilKhan.value = feditbillinfo.selBilKhan.options[feditbillinfo.selBilKhan.selectedIndex].text;
		feditbillinfo.txtBilSangkat.value = feditbillinfo.selBilSangkat.options[feditbillinfo.selBilSangkat.selectedIndex].text;
		
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
	if(!empty($smt) && isset($smt) && ($smt == "savepg13")){
		$txtBilAddress = FixQuotes($txtBilAddress);
		$selBilCountry = FixQuotes($selBilCountry);
		$selBilCity = FixQuotes($selBilCity);
		$selBilKhan = FixQuotes($selBilKhan);
		$selBilSangkat = FixQuotes($selBilSangkat);		
		
		$txtBilSangkat = FixQuotes($txtBilSangkat);
		$txtBilKhan = FixQuotes($txtBilKhan);
		$txtBilCity = FixQuotes($txtBilCity);
		$txtBilCountry = FixQuotes($txtBilCountry);
		
		$Operator = $user['FullName'];
		 
		$audit = new Audit();
		
		$sql = "UPDATE tblCustAddress SET 
							Address = '".$txtBilAddress."', 
							SangkatID = '".$selBilSangkat."', 
							KhanID = '".$selBilKhan."', 
							CityID = '".$selBilCity."', 
							CountryID = '".$selBilCountry."'							
						WHERE AddressID = $AddressID  
					 ";
		if($mydb->sql_query($sql)){			
			$title = "Update billing information";
			$description = "Change billing location to: Address: $txtBilAddress; Sangkat: $txtBilSangkat; Khan: $txtBilKhan; City: $txtBilCity; Country: $txtBilCountry.";

			$audit->AddAudit($CustomerID, "", $title, $description, $Operator, 1, 7);	
			redirect('./?CustomerID='.$CustomerID.'&pg=10');
		}else{
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to update billing information.", $error['message']);
		}
	}
	
	# =============== Get customer information =====================	
	$sql = "select AddressID, Address, SangkatID, KhanID, CityID, CountryID
					from tblCustAddress where IsBillingAddress = 1 and CustID=$CustomerID";
					
	if($que = $mydb->sql_query($sql)){
		if($rst = $mydb->sql_fetchrow($que)){
			$AddressID = $rst['AddressID'];
			$Address = $rst['Address'];
			$SangkatID = $rst['SangkatID'];
			$KhanID = $rst['KhanID'];
			$CityID = $rst['CityID'];
			$CountryID = $rst['CountryID'];							
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
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=90"><img src="./images/tab/product.gif" name="product" border="0" id="product" onmouseover="changeImage(2, './images/tab/product_over.gif');" onmouseout="changeImage(2, './images/tab/product.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=41"><img src="./images/tab/finance.gif" name="finance" border="0" id="finance" onmouseover="changeImage(3, './images/tab/finance_over.gif');" onmouseout="changeImage(3, './images/tab/finance.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=70"><img src="./images/tab/usage.gif" name="usage" border="0" id="usage" onmouseover="changeImage(4, './images/tab/usage_over.gif');" onmouseout="changeImage(4, './images/tab/usage.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID;?>&pg=30"><img src="./images/tab/audit.gif" name="audit" border="0" id="audit" onmouseover="changeImage(5, './images/tab/audit_over.gif');" onmouseout="changeImage(5, './images/tab/audit.gif');"/></a></td>						
						
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
											<td align="left" class="formtitle" height="18"><b>Edit billing information</b></td>
										</tr>
										<tr>
											<td valign="top">
												<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2"> 							
													<tr>
														<td align="left">Address:</td>
														<td align="left" colspan="3">
															<input type="text" name="txtBilAddress" class="boxenabled" tabindex="1" size="83" maxlength="100" value="<?php print $Address; ?>" />
														</td>
													</tr>
													<tr>
														<td align="left">Country:</td>
														<td align="left">
															<select name="selBilCountry" class="boxenabled" tabindex="8" style="width:200px" onchange="storeNameValue(this.selectedIndex, 8);">													
																<?php
																	$sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
																	
																	$que = $mydb->sql_query($sql);									
																	if($que){
																		while($rst = $mydb->sql_fetchrow($que)){	
																			$dbCountryID = $rst['id'];
																			$Country = $rst['name'];
																			if($CountryID == $dbCountryID) 
																				$sel = "selected";
																			else
																				$sel = "";
																			print "<option value='".$dbCountryID."' ".$sel.">".$Country."</option>";
																		}
																	}
																	$mydb->sql_freeresult();
																?>
															</select>
														</td>
														<td align="left">City:</td>
														<td align="left">
															<select name="selBilCity" class="boxenabled" tabindex="9" style="width:200px" onchange="storeNameValue(this.selectedIndex, 7);">	
																<?php
																	$sql = "SELECT id, name from tlkpLocation where type = 2 and country = $CountryID order by name";
																	
																	$que = $mydb->sql_query($sql);									
																	if($que){
																		while($rst = $mydb->sql_fetchrow($que)){	
																			$dbCityID = $rst['id'];
																			$City = $rst['name'];
																			if($CityID == $dbCityID) 
																				$sel = "selected";
																			else
																				$sel = "";
																			print "<option value='".$dbCityID."' ".$sel.">".$City ."</option>";
																		}
																	}
																	$mydb->sql_freeresult();
																?>													
															</select>
														</td>																								
													</tr>
													<tr>
														<td align="left">Khan:</td>
														<td align="left">
															<select name="selBilKhan" class="boxenabled" tabindex="10" style="width:200px" onchange="storeNameValue(this.selectedIndex, 6);">
																<?php
																	$sql = "SELECT id, name FROM tlkpLocation WHERE Type = 3 AND province in
																						(SELECT province FROM tlkpLocation WHERE id = ".$CityID.") ORDER BY name";
																	
																	$que = $mydb->sql_query($sql);									
																	if($que){
																		while($rst = $mydb->sql_fetchrow($que)){	
																			$dbKhanID = $rst['id'];
																			$Khan = $rst['name'];
																			if($KhanID == $dbKhanID) 
																				$sel = "selected";
																			else
																				$sel = "";
																			print "<option value='".$dbKhanID."' ".$sel.">".$Khan ."</option>";
																		}
																	}
																	$mydb->sql_freeresult();
																?>																									
															</select>
														</td>
														<td align="left">Sangkat:</td>
														<td align="left">
															<select name="selBilSangkat" class="boxenabled" tabindex="11" style="width:200px" onchange="storeNameValue(this.selectedIndex, 5);">
																<?php
																	$sql = "Select id, name from tlkpLocation l where district in 
																					(Select district from tlkpLocation where id=".$KhanID." and province=l.province 
																					and country=l.country) and type=4 ORDER BY name";
																	
																	$que = $mydb->sql_query($sql);									
																	if($que){
																		while($rst = $mydb->sql_fetchrow($que)){	
																			$dbSangkatID = $rst['id'];
																			$Sangkat = $rst['name'];
																			if($SangkatID == $dbSangkatID) 
																				$sel = "selected";
																			else
																				$sel = "";
																			print "<option value='".$dbSangkatID."' ".$sel.">".$Sangkat ."</option>";
																		}
																	}
																	$mydb->sql_freeresult();
																?>																										
															</select>
														</td>																					
													</tr>
													<tr><td colspan="4">&nbsp;</td></tr>
													<tr><td align="center" colspan="4">											
														<input type="reset" tabindex="14" name="reset" value="Reset" class="button" />
														<input type="button" tabindex="15" name="btnSave" value="Save" class="button" onClick="ValidateForm();" />
													</td></tr>
													<?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"4\" align=\"left\">$retOut</td></tr>";
														}
													?>
												</table>
											</td>
										</tr>										
									</table>
									<input type="hidden" name="pg" id="pg" value="13" />
									<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />	
									<input type="hidden" name="AddressID" value="<?php print $AddressID; ?>" />	
									<input type="hidden" name="txtBilSangkat" value="" />
									<input type="hidden" name="txtBilKhan" value="" />
									<input type="hidden" name="txtBilCity" value="" />
									<input type="hidden" name="txtBilCountry" value="" />									
									<input type="hidden" name="smt" value="savepg13" />								
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
