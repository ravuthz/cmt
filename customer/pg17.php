<?php
	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");
	require_once("./common/functions.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright � 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
?>
<script language="JavaScript" src="./javascript/ajax_location.js"></script>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/validphone.js"></script>
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
			feditcontact.txtSangkat.value = feditcontact.selSangkat.options[index].text;
		}
		else if(cat == 6){
			feditcontact.txtKhan.value = feditcontact.selKhan.options[index].text;
			location(4, feditcontact.selKhan.options[index].value, "selSangkat");
		}
		else if(cat == 7){
			feditcontact.txtCity.value = feditcontact.selCity.options[index].text;						
			location(3, feditcontact.selCity.options[index].value, "selKhan");
		}			
		else if(cat == 8){
			feditcontact.txtCountry.value = feditcontact.selCountry.options[index].text;			
			location(2, feditcontact.selCountry.options[index].value, "selCity");
		}
													
	}
	//
	//	Validate form
	//
	function ValidateForm(){
		if(Trim(feditcontact.txtGuarrantor.value) == ""){
			alert("Please enter guarrantor name");
			feditcontact.txtGuarrantor.focus();
			return;
		}else if(Trim(feditcontact.txtAddress.value) == ""){
			alert("Please enter customer address");
			feditcontact.txtAddress.focus();
			return;
		}else if(feditcontact.selCountry.options[feditcontact.selCountry.selectedIndex].text == "Unknown"){
			alert("Pleas select customer country");
			feditcontact.selCountry.focus();
			return;
		}else if(feditcontact.selCity.options[feditcontact.selCity.selectedIndex].text == "Unknown"){
			alert("Pleas select customer city");
			feditcontact.selCity.focus();
			return;
		}else if(feditcontact.selKhan.options[feditcontact.selKhan.selectedIndex].text == "Unknown"){
			alert("Pleas select customer khan");
			feditcontact.selKhan.focus();
			return;
		}else if(feditcontact.selSangkat.options[feditcontact.selSangkat.selectedIndex].text == "Unknown"){
			alert("Pleas select customer sangkat");
			feditcontact.selSangkat.focus();
			return;
		}else if(Trim(feditcontact.comment.value) == ""){
			alert("Please enter comment on change");
			feditcontact.comment.focus();
			return;
		}
		
		feditcontact.txtCountry.value = feditcontact.selCountry.options[feditcontact.selCountry.selectedIndex].text;
		feditcontact.txtCity.value = feditcontact.selCity.options[feditcontact.selCity.selectedIndex].text;
		feditcontact.txtKhan.value = feditcontact.selKhan.options[feditcontact.selKhan.selectedIndex].text;
		feditcontact.txtSangkat.value = feditcontact.selSangkat.options[feditcontact.selSangkat.selectedIndex].text;
		feditcontact.txtOccupation.value = feditcontact.selOccupation.options[feditcontact.selOccupation.selectedIndex].text;
		feditcontact.txtNationality.value = feditcontact.selNationality.options[feditcontact.selNationality.selectedIndex].text;
		
		feditcontact.btnSave.disabled = true;
		feditcontact.submit();
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
	if(!empty($smt) && isset($smt) && ($smt == "savepg17")){
		$selSalutaion = FixQuotes($selSalutaion);
		$txtGuarrantor = FixQuotes($txtGuarrantor);
		$txtDOB = FixQuotes($txtDOB);
		$selDuplicateID = FixQuotes($selDuplicateID);
		$txtDuplicate = FixQuotes($txtDuplicate);
		$selNationality = FixQuotes($selNationality);
		$selOccupation = FixQuotes($selOccupation);
		$txtPhone = FixQuotes($txtPhone);
		$txtEmail = FixQuotes($txtEmail);
		$txtAddress = FixQuotes($txtAddress);
		$selCountry = FixQuotes($selCountry);
		$selCity = FixQuotes($selCity);
		$selKhan = FixQuotes($selKhan);
		$selSangkat = FixQuotes($selSangkat);		
		
		$txtSangkat = FixQuotes($txtSangkat);
		$txtKhan = FixQuotes($txtKhan);
		$txtCity = FixQuotes($txtCity);
		$txtCountry = FixQuotes($txtCountry);
		$txtOccupation = FixQuotes($txtOccupation);
		$txtNationality = FixQuotes($txtNationality);
		$comment = FixQuotes($comment);
		$Operator = $user['FullName'];
		 
		$audit = new Audit();
		
		$sql = "UPDATE tblCustGuarrantor SET 
							GuarrantorName = '".$txtGuarrantor."', 
							Salutation = '".$selSalutaion."', 
							DOB = '".$txtDOB."', 
							Address = '".$txtAddress."', 
							SangkatID = '".$selSangkat."', 
							KhanID = '".$selKhan."', 
							CityID = '".$selCity."', 
							CountryID = '".$selCountry."',
							NationalityID = '".$selNationality."', 
							OccupationID = '".$selOccupation."', 
							Phone = '".$txtPhone."', 
							Email = '".$txtEmail."', 
							IdentityMode = '".$selDuplicateID."', 
							IdentityData = '".$txtDuplicate."'																					
						WHERE GuarrantorID = $GuarrantorID  
					 ";
		if($mydb->sql_query($sql)){			
			$title = "Update customer gurarranter";
			$description = $comment;
			//$description = "Change designate info as: Name: $selSalutaion $txtContact; DOB: $txtDOB; nationality: $txtNationality; occupation: $txtOccupation; phone: $txtPhone; email: $txtEmail; duplicate: $selDuplicateID- $txtDuplicate; address: $txtAddress; sangkat: $txtSangkat; Khan: $txtKhan; City: $txtCity; Country: $txtCountry.";

			$audit->AddAudit($CustomerID, "", $title, $description, $Operator, 1, 7);							
			$retOut = $myinfo->info("Successful save changed customer guarantor");
			//redirect('./?CustomerID='.$CustomerID.'&pg=10');
		}else{
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to update customer guarranter.", $error['message']);
		}
	}
	
	# =============== Get customer information =====================	
	$sql = "select GuarrantorName, Salutation, DOB, Address, SangkatID, KhanID, CityID, CountryID,
								NationalityID, OccupationID, Phone, Email, IdentityMode, IdentityData
					from tblCustGuarrantor where GuarrantorID = $GuarrantorID";

	if($que = $mydb->sql_query($sql)){
		if($rst = $mydb->sql_fetchrow($que)){
			$GuarrantorName = $rst['GuarrantorName'];
			$Salutation = $rst['Salutation'];
			$DOB = $rst['DOB'];
			$NationalityID = $rst['NationalityID'];
			$OccupationID = $rst['OccupationID'];
			$Phone = $rst['Phone'];
			$Email = $rst['Email'];
			$IdentityMode = $rst['IdentityMode'];
			$IdentityData = $rst['IdentityData'];
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
								<form name="feditcontact" method="post" action="./">
									<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
										<tr>
											<td align="left" class="formtitle" height="18"><b>Edit customer guarantor</b></td>
										</tr>
										<tr>
											<td valign="top">
												<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2"> 							
													<tr>
														<td align="left">Salutation:</td>
														<td align="left" colspan="3">
															<select name="selSalutaion" tabindex="1">
																<option value="Mr." <?php if($Salutation == "Mr.") echo 'selected';?>>Mr.</option>
																<option value="Mrs." <?php if($Salutation == "Mrs.") echo 'selected';?>>Mrs.</option>
																<option value="Miss." <?php if($Salutation == "Miss.") echo 'selected';?>>Miss.</option>
																<option value="Dr." <?php if($Salutation == "Dr.") echo 'selected';?>>Dr.</option>
															</select>
													</tr>
													<tr>
														<td align="left">Name:</td>
														<td align="left" colspan="3">
															<input type="text" name="txtGuarrantor" class="boxenabled" size="83" maxlength="50" value="<?php print $GuarrantorName;?>" />
														</td>	
													</tr>
													<tr>
														<td align="left">Date of birth</td>
														<td align="left">
															<input type="text" tabindex="3" name="txtDOB" class="boxenabled" size="27" maxlength="30" value="<?php print formatDate($DOB, 5); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?feditcontact|txtDOB', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
														</td>
														<td align="left">Duplicate:</td>
														<td align="left" nowrap="nowrap">
															<select name="selDuplicateID" class="boxenabled" tabindex="4">
																<option value="ID Card" <?php if($IdentityMode == "ID Card") print "selected"?>>ID Card</option>
																<option value="Passport" <?php if($IdentityMode == "Passport") print "selected"?>>Passport</option>
																<option value="Family book" <?php if($IdentityMode == "Family book") print "selected"?>>Family book</option>
															</select>
															<input type="text" name="txtDuplicate" class="boxenabled" tabindex="5" size="11" value="<?php print $IdentityData;?>" />
														</td>	
													</tr>
													<tr>
														<td align="left">Nationality:</td>
														<td align="left">
															<select name="selNationality" class="boxenabled" tabindex="9" style="width:190px">
																<?php
																	$sql = "SELECT CountryID, Country from tlkpCountry order by Country";
																	// sql 2005
																	
																	$que = $mydb->sql_query($sql);									
																	if($que){
																		while($rst = $mydb->sql_fetchrow($que)){	
																			$nCountryID = $rst['CountryID'];
																			$Country = $rst['Country'];
																			if($NationalityID == $nCountryID) 
																				$sel = "selected";
																			else
																				$sel = "";
																			print "<option value='".$nCountryID."' ".$sel.">".$Country."</option>";
																		}
																	}
																	$mydb->sql_freeresult();
																?>
															</select>
														</td>
														<td align="left">
															Occupation:
														</td>
														<td align="left">
															<select name="selOccupation" class="boxenabled" tabindex="10" style="width:200px">										
																<?php
																	$sql = "SELECT CareerID, Career from tlkpCareer order by CareerID";
																	// sql 2005
																	
																	$que = $mydb->sql_query($sql);									
																	if($que){
																		while($rst = $mydb->sql_fetchrow($que)){	
																			$CareerID = $rst['CareerID'];
																			$Career = $rst['Career'];												
																			if($OccupationID == $CareerID) 
																				$sel = "selected";
																			else
																				$sel = "";
																			print "<option value='".$CareerID."' ".$sel.">".$Career."</option>";
																		}
																	}
																	$mydb->sql_freeresult();
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td align="left">Phone:</td>
														<td align="left">
															<input type="text" name="txtPhone" value="<?php print $Phone; ?>" class="boxenabled" tabindex="49" size="29" onKeyUp="ValidatePhone(this);" onBlur="CheckPhone(this);" />
														</td>
														<td align="left">Email:</td>
														<td align="left">
															<input type="text" name="txtEmail" class="boxenabled" tabindex="" size="29" maxlength="50" value="<?php print $Email; ?>" />
														</td>		 	
													</tr>
													<tr>
														<td align="left">Address:</td>
														<td align="left" colspan="3">
															<input type="text" name="txtAddress" class="boxenabled" tabindex="1" size="83" maxlength="100" value="<?php print $Address; ?>" />
														</td>
													</tr>
													<tr>
														<td align="left">Country:</td>
														<td align="left">
															<select name="selCountry" class="boxenabled" tabindex="8" style="width:200px" onChange="storeNameValue(this.selectedIndex, 8);">			
															<option value="0">Unknown</option>										
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
															<select name="selCity" class="boxenabled" tabindex="9" style="width:200px" onChange="storeNameValue(this.selectedIndex, 7);">	
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
															<select name="selKhan" class="boxenabled" tabindex="10" style="width:200px" onChange="storeNameValue(this.selectedIndex, 6);">
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
															<select name="selSangkat" class="boxenabled" tabindex="11" style="width:200px" onChange="storeNameValue(this.selectedIndex, 5);">
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
													<tr>
														<td align="left" valign="top">Note:</td>
														<td align="left" colspan="3">
															<textarea name="comment" cols="63" rows="3" class="boxenabled"></textarea>
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
									<input type="hidden" name="pg" id="pg" value="17" />
									<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />	
									<input type="hidden" name="GuarrantorID" value="<?php print $GuarrantorID; ?>" />	
									<input type="hidden" name="txtSangkat" value="" />
									<input type="hidden" name="txtKhan" value="" />
									<input type="hidden" name="txtCity" value="" />
									<input type="hidden" name="txtCountry" value="" />	
									<input type="hidden" name="txtOccupation" value="" />								
									<input type="hidden" name="txtNationality" value="" />
									<input type="hidden" name="smt" value="savepg17" />								
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
