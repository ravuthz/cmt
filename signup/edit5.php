<?php
	require_once("./common/agent.php");	
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
?>
<script language="javascript" src="./javascript/ajax_checkusername.js"></script>
<script language="javascript">
	//
	// Check valid password
	//
	function checkPassword(Password, Confirm){
		if(Password == Confirm)
			return true;
		else
			return false;
	}
	
	//
	//	Check valid account name
	//
	function ValidAccount(retDiv){
		phonepre = fedit5.SelPhonePreset.options[fedit5.SelPhonePreset.selectedIndex].value;
		phonenum = fedit5.UserName.value;
		username = phonepre + phonenum;
		if(username.length < 9){
			document.getElementById("btnNext").disabled = true;
			document.getElementById("dUserName").innerHTML=" Invalied account name.";
			document.getElementById("dUserName").style.display = "inline";
			return false;
		}else{
			for (i = 0; i < username.length; i++){
				var c = username.charAt(i);
					if ((c < "0") || (c > "9")){
						document.getElementById("btnNext").disabled = true;
						document.getElementById("dUserName").innerHTML=" Invalied account name.";
						document.getElementById("dUserName").style.display = "inline";
						return false;
					}
			}
		}
		url = "./php/ajax_checkusername.php?username=" + username +"&mt=" + new Date().getTime();
		//alert(url);
		checkUserName(url, retDiv);
		
	}
	
	function ValidateForm(){
		Subscription = fedit5.SubscriptionName;
		Package = fedit5.PackageID;
		Regisration = fedit5.RegistrationFee;
		ConfigurationFee = fedit5.ConfigurationFee;
		CPEFee = fedit5.CPEFee;
		ISDNFee = fedit5.ISDNFee;
		SPNFee = fedit5.SPNFee;
		SelStationID = fedit5.SelStationID;
		SelPhonePreset = fedit5.SelPhonePreset.options[fedit5.SelPhonePreset.selectedIndex].value;
		UserName = fedit5.UserName;
		Password = fedit5.Password;
		ConfirmPassword = fedit5.ConfirmPassword;
		selSalesman = fedit5.selSalesman;	
		
		txtSaleman = fedit5.selSalesman;
		txtStation = fedit5.SelStationID;
		txtAccPackage = fedit5.PackageID;
		
		if(Trim(Subscription.value) ==""){
			alert("Please enter subscription fee");
			Subscription.focus();
			return;		
		}else if(SelStationID.selectedIndex < 1){
			alert("Please select station");
			SelStationID.focus();
			return;
		}else if(Trim(UserName.value) == ""){
			alert("Please enter account name");
			UserName.focus();
			return;
		}else if(Trim(Password.value) == ""){
			alert("Please enter account password");
			Password.focus();
			return;
		}else if(Trim(Password.value).length < 6){
			alert("Password must be between 6 to 20 chararater length");
			Password.focus();
			return;
		}else if(!checkPassword(Trim(Password.value), Trim(ConfirmPassword.value))){
			alert("Password and confirm password must be the same");
			Password.focus();
			return;
		}else if(selSalesman.selectedIndex < 1){
			alert("Please select saleaman");
			selSalesman.focus();
			return;
		}
	//========================== registration
		if(Regisration.value == ""){
			Regisration.value = 0;		
		}else if(!isNumber(Regisration.value)){
			alert("Registration fee must be number");
			Regisration.focus();
			return;
		}else if(Number(Regisration.value) < 0){
			alert("Registration must be positive number");
			Regisration.focus();
			return;
		}
//=========================== installation		
		if(ConfigurationFee.value == ""){
			ConfigurationFee.value = 0;		
		}else if(!isNumber(ConfigurationFee.value)){
			alert("Intallation fee must be number");
			ConfigurationFee.focus();
			return;
		}else if(Number(ConfigurationFee.value) < 0){
			alert("Installation must be positive number");
			ConfigurationFee.focus();
			return;
		}
//============================= cpe fee		
		if(CPEFee.value == ""){
			CPEFee.value = 0;		
		}else if(!isNumber(CPEFee.value)){
			alert("CPE fee must be number");
			CPEFee.focus();
			return;
		}else if(Number(CPEFee.value) < 0){
			alert("CPE must be positive number");
			CPEFee.focus();
			return;
		}
//==============================ISDN fee
		if(ISDNFee.value == ""){
			ISDNFee.value = 0;		
		}else if(!isNumber(ISDNFee.value)){
			alert("ISDN fee must be number");
			ISDNFee.focus();
			return;
		}else if(Number(ISDNFee.value) < 0){
			alert("ISDN must be positive number");
			ISDNFee.focus();
			return;
		}
//=============================== special number
		if(SPNFee.value == ""){
			SPNFee.value = 0;		
		}else if(!isNumber(SPNFee.value)){
			alert("Special number fee must be number");
			SPNFee.focus();
			return;
		}else if(Number(SPNFee.value) < 0){
			alert("Special number fee must be positive number");
			SPNFee.focus();
			return;
		}
		fedit5.txtSaleman.value = txtSaleman.options[txtSaleman.selectedIndex].text;
		fedit5.txtStation.value = txtStation.options[txtStation.selectedIndex].text;
		fedit5.txtAccPackage.value = txtAccPackage.options[txtAccPackage.selectedIndex].text;
		fedit5.txtAccountName.value = SelPhonePreset+UserName.value;
		fedit5.btnNext.disabled = true;
		fedit5.submit();
	}
	//
	//	Store name of value
	//
	function storeNameValue(selValue, cat){
		if(cat == 1)
			fedit5.txtSaleman.value = selValue;
		if(cat == 2)
			fedit5.txtStation.value = selValue;
		if(cat == 3)
			fedit5.txtCPE.value = selValue;
	}
</script>

<form name="fedit5" method="post" action="./">
<table border="0" cellpadding="3" cellspacing="0" align="left">
	<tr>
		<td colspan="2" width="50%">
			<fieldset style="width:380px">
				<legend align="center">EDIT ACCOUNT INFORMATION</legend>
					<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" align="left">													
						<tr>
							<td align="left" nowrap="nowrap">Subscription name:</td>
							<td align="left">
								<input type="text" name="SubscriptionName" value="<?php print $SubscriptionName;?>"  class="boxenabled" tabindex="1" size="65" /><img src="./images/required.gif" border="0" />
							</td>
						</tr>
						<tr>
							<td align="left">Package:</td>
							<td align="left">
								<?php
									$sql = "SELECT PackageID, TarName, CreatedDate, RegistrationFee, ConfigurationFee, CPEFee,
																	ISDNFee, SpecialNumber
														from tblTarPackage where Status = 1 and ServiceID = $ServiceID order by 2";													

									$que = $mydb->sql_query($sql);									
									if($que){
										$tmppackage = "";
										$tmpdeposit = "";
										while($rst = $mydb->sql_fetchrow($que)){	
											$PackageID = $rst['PackageID'];
											$TarName = $rst['TarName'];
											$ISDNFee = $rst['ISDNFee'];
											$SpecialNumber = $rst['SpecialNumber'];
											$CreatedDate = $rst['CreatedDate'];
											$RegistrationFee = $rst['RegistrationFee'];
											$ConfigurationFee = $rst['ConfigurationFee'];
											$CPEFee = $rst['CPEFee'];
											$PackageName = $TarName." (".formatDate($CreatedDate, 4).")";
											$tmppackage .= "<option value='".$PackageID."'>".$TarName."</option><br />";
											$tmpdeposit .= "<option value='".$SpecialNumber."'>".$RegistrationFee."</option><br />";
											$tmpother .= "<option value='".$ConfigurationFee."'>".$CPEFee."</option><br />";
											$tmpother1 .= "<option value='".$ISDNFee."'>".$ISDNFee."</option><br />";
										}
									}
									$mydb->sql_freeresult();

								?>
								<select name="PackageID" tabindex="2" onChange="autoValue(this.selectedIndex);">
								<option value="<?php print $PackageID; ?>"><?php print $txtAccPackage; ?></option>
									<?php print $tmppackage; ?>
								</select>
							</td>
						</tr>										
						<tr>
							<td align="left" nowrap="nowrap">Registration fee:</td>
							<td align="left">
								<input type="text" name="RegistrationFee" value="<?php print $RegistrationFee; ?>" class="boxenabled" tabindex="3" size="30" /> (Exclude VAT)
								<input type="checkbox" name="vatRegistrationFee" <?php if($vatRegistrationFee == 1) print "checked";?> value="1" />Charge VAT
							</td>
						</tr>		
						<tr>
							<td align="left" nowrap="nowrap">Installation fee:</td>
							<td align="left">
								<input type="text" name="ConfigurationFee" value="<?php print $ConfigurationFee; ?>" class="boxenabled" tabindex="4" size="30" /> (Exclude VAT)
								<input type="checkbox" name="vatConfigurationFee" <?php if($vatConfigurationFee == 1) print "checked";?> value="1" />Charge VAT
							</td>
						</tr>		
						<tr>
							<td align="left" nowrap="nowrap">CPE cost:</td>
							<td align="left">
								<input type="text" name="CPEFee" value="<?php print $CPEFee; ?>" class="boxenabled" tabindex="5" size="30" /> (Exclude VAT)
								<input type="checkbox" name="vatCPEFee" <?php if(intval($vatCPEFee) == 1) print "checked";?> value="1" />Charge VAT
							</td>
						</tr>
						<tr>
							<td align="left" nowrap="nowrap">ISDN Cost:</td>
							<td align="left">
								<input type="text" name="ISDNFee" value="<?php print $ISDNFee; ?>" class="boxenabled" tabindex="6" size="30" /> (Exclude VAT)
								<input type="checkbox" name="vatISDNFee" <?php if(intval($vatISDNFee) == 1) print "checked";?> value="1" />Charge VAT
							</td>
						</tr>
						<tr>
							<td align="left" nowrap="nowrap">Special Number Cost:</td>
							<td align="left">
								<input type="text" name="SPNFee" value="<?php print $SPNFee; ?>" class="boxenabled" tabindex="7" size="30" /> (Exclude VAT)
								<input type="checkbox" name="vatSPNFee" <?php if(intval($vatSPNFee) == 1) print "checked";?> value="1" />Charge VAT
							</td>
						</tr>										
						<tr>
							<td align="left">Station:</td>
							<td align="left">
								<select name="SelStationID" class="boxenabled" tabindex="8" onChange="storeNameValue(this.options[this.selectedIndex].text, 2);">
									<option value="">Select Station</option>	
									<?php
										$sql = "SELECT StationID, StationName from tlkpStation order by StationName";
										// sql 2005
										
										$que = $mydb->sql_query($sql);									
										if($que){
											while($rst = $mydb->sql_fetchrow($que)){	
												$StationID = $rst['StationID'];
												$StationName = $rst['StationName'];
												if($SelStationID == $StationID) 
													$sel = "selected";
												else
													$sel = "";
												print "<option value='".$StationID."' ".$sel.">".$StationName."</option>";
											}
										}
										$mydb->sql_freeresult();
									?>
								</select><img src="./images/required.gif" border="0" />
							</td>
						</tr>
						<tr>
							<td align="left">Product type:</td>
							<td align="left">
								<select name="CPETypeID" class="boxenabled" tabindex="9" onchange="storeNameValue(this.options[this.selectedIndex].text, 3);">
									<?php
										$sql = "SELECT CPEID, CPEName, SerialNumber from tlkpCPE WHERE Active = 1 order by CPEName";
										// sql 2005
										
										$que = $mydb->sql_query($sql);									
										if($que){
											while($rst = $mydb->sql_fetchrow($que)){	
												$CPEID = $rst['CPEID'];
												$SerialNumber = $rst['SerialNumber'];
												$CPEName = $rst['CPEName'];	
												if($CPETypeID == $CPEID) 
													$sel = "selected";
												else
													$sel = "";															
												print "<option value='".$CPEID."' ".$sel.">".$CPEName."(".$SerialNumber.")"."</option>";
											}
										}
										$mydb->sql_freeresult();
									?>
								</select><img src="./images/required.gif" border="0" />
							</td>
						</tr>
						<tr>
							<td align="left" nowrap="nowrap">Account name / Telephone:</td>
							<td align="left">
								<select name="SelPhonePreset" class="boxenabled" tabindex="9">														
									<?php
										$sql = "SELECT PresetNumber, [Default] from tlkpPhonePreset order by PresetNumber";
										// sql 2005
										
										$que = $mydb->sql_query($sql);									
										if($que){
											while($rst = $mydb->sql_fetchrow($que)){	
												$PresetNumber = $rst['PresetNumber'];
												$Default = $rst['Default'];
												if($SelPhonePreset == $PresetNumber) 
													$sel = "selected";
												else
													$sel = "";
												print "<option value='".$PresetNumber."' ".$sel.">".$PresetNumber."</option>";
											}
										}
										$mydb->sql_freeresult();
									?>
								</select>
								<input type="text" name="UserName" id="UserName" value="<?php print $UserName;?>"  class="boxenabled" tabindex="10" size="21" onBlur="ValidAccount('dUserName');" maxlength="7" /><img src="./images/required.gif" border="0" /><span style="display:none" id="dUserName" class="error"></span>
							</td>
						</tr>
						<tr>
							<td align="left" nowrap="nowrap">Password:</td>
							<td align="left">
								<input type="password" name="Password" value="<?php print $Password;?>"  class="boxenabled" tabindex="11" size="30" maxlength="20" /><img src="./images/required.gif" border="0" /><span style="display:none" id="dPassword" class="error"></span>
							</td>
						</tr>
						<tr>
							<td align="left" nowrap="nowrap">Confirm password:</td>
							<td align="left">
								<input type="password" name="ConfirmPassword" value="<?php print $Password;?>"  class="boxenabled" tabindex="12" size="30" maxlength="20" /><img src="./images/required.gif" border="0" />
							</td>
						</tr>	
						<tr>
							<td align="left">Salesman:</td>
							<td align="left">
								<select name="selSalesman" class="boxenabled" tabindex="13" onChange="storeNameValue(this.options[this.selectedIndex].text, 1);">
									<option value="">Select salesman</option>	
									<?php
										$sql = "SELECT SalesmanID, Salutation, Name from tlkpSalesman order by Country";
										// sql 2005
										
										$que = $mydb->sql_query($sql);									
										if($que){
											while($rst = $mydb->sql_fetchrow($que)){	
												$SalesmanID = $rst['SalesmanID'];
												$Salutation = $rst['Salutation'];
												$Name = $rst['Name'];
												$salesmanname = $Salutation." ".$Name;
												if($selSalesman == $SalesmanID)
													$sel = "selected";
												else
													$sel = "";
												print "<option value='".$SalesmanID."' ".$sel.">".$salesmanname."</option>";
											}
										}
										$mydb->sql_freeresult();
									?>
								</select><img src="./images/required.gif" border="0" />
							</td>
						</tr>		
							<tr>
							<td align="center" colspan="4">
								<input type="reset" name="reset" value="Reset" class="button" />&nbsp;
								<input type="button" name="btnNext" value="Save" class="button" onClick="ValidateForm();" />
							</td>
						</tr>																																						 		
					 </table>
			</fieldset>
		</td>
	</tr>
</table>

<input type="hidden" name="pg" value="6" />

<select name="tmpdeposit" style="display:none">
<option value="0">0</option>
<?php print $tmpdeposit; ?>
</select>
<select name="tmpother" style="display:none">
<option value="0">0</option>
<?php print $tmpother; ?>
</select>
<select name="tmpother1" style="display:none">
<option value="0">0</option>
<?php print $tmpother1; ?>
</select>

<input type="hidden" name="chkncDeposit" value="<?php print $chkncDeposit; ?>" />
<input type="hidden" name="ncDeposit" value="<?php print $ncDeposit; ?>" />
<input type="hidden" name="chkicDeposit" value="<?php print $chkicDeposit; ?>" />
<input type="hidden" name="icDeposit" value="<?php print $icDeposit; ?>" />
<input type="hidden" name="chkmfDeposit" value="<?php print $chkmfDeposit; ?>" />
<input type="hidden" name="mfDeposit" value="<?php print $mfDeposit; ?>" />
					
<input type="hidden" name="txtAccPackage" value="<?php print $txtAccPackage; ?>" />
<input type="hidden" name="txtSaleman" value="<?php print $txtSaleman; ?>" />
<input type="hidden" name="txtStation" value="<?php print $txtStation; ?>" />
<input type="hidden" name="txtCPE" value="<?php print $txtCPE; ?>" />
<input type="hidden" name="txtAccountName" value="<?php print $txtAccountName; ?>" />

<input type="hidden" name="ServiceID" value="<?php print $ServiceID; ?>" />

<input type="hidden" name="selCusSalutation" value="<?php print $selCusSalutation; ?>" />
<input type="hidden" name="txtCustomerName" value="<?php print $txtCustomerName; ?>" />
<input type="hidden" name="txtCusDOB" value="<?php print $txtCusDOB; ?>" />
<input type="hidden" name="txtCusBus" value="<?php print $txtCusBus; ?>" />
<input type="hidden" name="selCusDuplicateID" value="<?php print $selCusDuplicateID; ?>" />
<input type="hidden" name="txtCusDuplicate" value="<?php print $txtCusDuplicate; ?>" />
<input type="hidden" name="radExemption" value="<?php print $radExemption; ?>" />
<input type="hidden" name="txtVATNumber" value="<?php print $txtVATNumber; ?>" />
<input type="hidden" name="selCusNationality" value="<?php print $selCusNationality; ?>" />
<input type="hidden" name="selCusOccupation" value="<?php print $selCusOccupation; ?>" />
<input type="hidden" name="txtCusPhone" value="<?php print $txtCusPhone; ?>" />
<input type="hidden" name="txtCusEmail" value="<?php print $txtCusEmail; ?>" />
<input type="hidden" name="txtCusAddress" value="<?php print $txtCusAddress; ?>" />
<input type="hidden" name="selCusCountry" value="<?php print $selCusCountry; ?>" />
<input type="hidden" name="selCusCity" value="<?php print $selCusCity; ?>" />
<input type="hidden" name="selCusKhan" value="<?php print $selCusKhan; ?>" />
<input type="hidden" name="selCusSangkat" value="<?php print $selCusSangkat; ?>" />

<input type="hidden" name="selConSalutation" value="<?php print $selConSalutation; ?>" />
<input type="hidden" name="txtContactName" value="<?php print $txtContactName; ?>" />
<input type="hidden" name="txtConDOB" value="<?php print $txtConDOB; ?>" />
<input type="hidden" name="selConDuplicateID" value="<?php print $selConDuplicateID; ?>" />
<input type="hidden" name="selConNationality" value="<?php print $selConNationality; ?>" />
<input type="hidden" name="selConOccupation" value="<?php print $selConOccupation; ?>" />
<input type="hidden" name="txtConPhone" value="<?php print $txtConPhone; ?>" />
<input type="hidden" name="txtConEmail" value="<?php print $txtConEmail; ?>" />
<input type="hidden" name="txtConAddress" value="<?php print $txtConAddress; ?>" />
<input type="hidden" name="selConCountry" value="<?php print $selConCountry; ?>" />
<input type="hidden" name="selConCity" value="<?php print $selConCity; ?>" />
<input type="hidden" name="selConKhan" value="<?php print $selConKhan; ?>" />
<input type="hidden" name="selConSangkat" value="<?php print $selConSangkat; ?>" />

<input type="hidden" name="selDesSalutation" value="<?php print $selDesSalutation; ?>" />
<input type="hidden" name="txtDesignateName" value="<?php print $txtDesignateName; ?>" />
<input type="hidden" name="txtDesDOB" value="<?php print $txtDesDOB; ?>" />
<input type="hidden" name="selDesDuplicateID" value="<?php print $selDesDuplicateID; ?>" />
<input type="hidden" name="selDesNationality" value="<?php print $selDesNationality; ?>" />
<input type="hidden" name="selDesOccupation" value="<?php print $selDesOccupation; ?>" />
<input type="hidden" name="txtDesPhone" value="<?php print $txtDesPhone; ?>" />
<input type="hidden" name="txtDesEmail" value="<?php print $txtDesEmail; ?>" />
<input type="hidden" name="txtDesAddress" value="<?php print $txtDesAddress; ?>" />
<input type="hidden" name="selDesCountry" value="<?php print $selDesCountry; ?>" />
<input type="hidden" name="selDesCity" value="<?php print $selDesCity; ?>" />
<input type="hidden" name="selDesKhan" value="<?php print $selDesKhan; ?>" />
<input type="hidden" name="selDesSangkat" value="<?php print $selDesSangkat; ?>" />

<input type="hidden" name="selGuaSalutation" value="<?php print $selGuaSalutation; ?>" />
<input type="hidden" name="txtGarrentorName" value="<?php print $txtGarrentorName; ?>" />
<input type="hidden" name="txtGuaDOB" value="<?php print $txtGuaDOB; ?>" />
<input type="hidden" name="selGuaDuplicateID" value="<?php print $selGuaDuplicateID; ?>" />
<input type="hidden" name="selGuaNationality" value="<?php print $selGuaNationality; ?>" />
<input type="hidden" name="selGuaOccupation" value="<?php print $selGuaOccupation; ?>" />
<input type="hidden" name="txtGuaPhone" value="<?php print $txtGuaPhone; ?>" />
<input type="hidden" name="txtGuaEmail" value="<?php print $txtGuaEmail; ?>" />
<input type="hidden" name="txtGuaAddress" value="<?php print $txtGuaAddress; ?>" />
<input type="hidden" name="selGuaCountry" value="<?php print $selGuaCountry; ?>" />
<input type="hidden" name="selGuaCity" value="<?php print $selGuaCity; ?>" />
<input type="hidden" name="selGuaKhan" value="<?php print $selGuaKhan; ?>" />
<input type="hidden" name="selGuaSangkat" value="<?php print $selGuaSangkat; ?>" />					

<input type="hidden" name="txtCusNationality" value="<?php print $txtCusNationality; ?>" />
<input type="hidden" name="txtCusOccupation" value="<?php print $txtCusOccupation; ?>" />
<input type="hidden" name="txtCusSangkat" value="<?php print $txtCusSangkat; ?>" />
<input type="hidden" name="txtCusKhan" value="<?php print $txtCusKhan; ?>" />
<input type="hidden" name="txtCusCity" value="<?php print $txtCusCity; ?>" />
<input type="hidden" name="txtCusCountry" value="<?php print $txtCusCountry; ?>" />

<input type="hidden" name="txtConNationality" value="<?php print $txtConNationality; ?>" />
<input type="hidden" name="txtConOccupation" value="<?php print $txtConOccupation; ?>" />
<input type="hidden" name="txtConDuplicate" value="<?php print $txtConDuplicate; ?>" />
<input type="hidden" name="txtConSangkat" value="<?php print $txtConSangkat; ?>" />
<input type="hidden" name="txtConKhan" value="<?php print $txtConKhan; ?>" />
<input type="hidden" name="txtConCity" value="<?php print $txtConCity; ?>" />
<input type="hidden" name="txtConCountry" value="<?php print $txtConCountry; ?>" />

<input type="hidden" name="txtGuaNationality" value="<?php print $txtGuaNationality; ?>" />
<input type="hidden" name="txtGuaOccupation" value="<?php print $txtGuaOccupation; ?>" />
<input type="hidden" name="txtGuaDuplicate" value="<?php print $txtGuaDuplicate; ?>" />
<input type="hidden" name="txtGuaSangkat" value="<?php print $txtGuaSangkat; ?>" />
<input type="hidden" name="txtGuaKhan" value="<?php print $txtGuaKhan; ?>" />
<input type="hidden" name="txtGuaCity" value="<?php print $txtGuaCity; ?>" />
<input type="hidden" name="txtGuaCountry" value="<?php print $txtGuaCountry; ?>" />

<input type="hidden" name="txtDesNationality" value="<?php print $txtDesNationality; ?>" />
<input type="hidden" name="txtDesOccupation" value="<?php print $txtDesOccupation; ?>" />
<input type="hidden" name="txtDesDuplicate" value="<?php print $txtDesDuplicate; ?>" />
<input type="hidden" name="txtDesSangkat" value="<?php print $txtDesSangkat; ?>" />
<input type="hidden" name="txtDesKhan" value="<?php print $txtDesKhan; ?>" />
<input type="hidden" name="txtDesCity" value="<?php print $txtDesCity; ?>" />
<input type="hidden" name="txtDesCountry" value="<?php print $txtDesCountry; ?>" />

<input type="hidden" name="selIntCountry" value="<?php print $selIntCountry; ?>" />
<input type="hidden" name="selIntCity" value="<?php print $selIntCity; ?>" />
<input type="hidden" name="selIntKhan" value="<?php print $selIntKhan; ?>" />
<input type="hidden" name="selIntSangkat" value="<?php print $selIntSangkat; ?>" />

<input type="hidden" name="txtIntAddress" value="<?php print $txtIntAddress; ?>" />
<input type="hidden" name="txtIntSangkat" value="<?php print $txtIntSangkat; ?>" />
<input type="hidden" name="txtIntKhan" value="<?php print $txtIntKhan; ?>" />
<input type="hidden" name="txtIntCity" value="<?php print $txtIntCity; ?>" />
<input type="hidden" name="txtIntCountry" value="<?php print $txtIntCountry; ?>" />

<input type="hidden" name="selLeaCountry" value="<?php print $selLeaCountry; ?>" />
<input type="hidden" name="selLeaCity" value="<?php print $selLeaCity; ?>" />
<input type="hidden" name="selLeaKhan" value="<?php print $selLeaKhan; ?>" />
<input type="hidden" name="selLeaSangkat" value="<?php print $selLeaSangkat; ?>" />

<input type="hidden" name="txtLeaAddress" value="<?php print $txtLeaAddress; ?>" />
<input type="hidden" name="txtLeaSangkat" value="<?php print $txtLeaSangkat; ?>" />
<input type="hidden" name="txtLeaKhan" value="<?php print $txtLeaKhan; ?>" />
<input type="hidden" name="txtLeaCity" value="<?php print $txtLeaCity; ?>" />
<input type="hidden" name="txtLeaCountry" value="<?php print $txtLeaCountry; ?>" />

<input type="hidden" name="selBilCountry" value="<?php print $selBilCountry; ?>" />
<input type="hidden" name="selBilCity" value="<?php print $selBilCity; ?>" />
<input type="hidden" name="selBilKhan" value="<?php print $selBilKhan; ?>" />
<input type="hidden" name="selBilSangkat" value="<?php print $selBilSangkat; ?>" />

<input type="hidden" name="txtBilAddress" value="<?php print $txtBilAddress; ?>" />
<input type="hidden" name="txtBilSangkat" value="<?php print $txtBilSangkat; ?>" />
<input type="hidden" name="txtBilKhan" value="<?php print $txtBilKhan; ?>" />
<input type="hidden" name="txtBilCity" value="<?php print $txtBilCity; ?>" />
<input type="hidden" name="txtBilCountry" value="<?php print $txtBilCountry; ?>" />
<input type="hidden" name="SelMessengerID" value="<?php print $SelMessengerID; ?>" />
<input type="hidden" name="txtBilEmail" value="<?php print $txtBilEmail; ?>" />
<input type="hidden" name="selBilInvoiceType" value="<?php print $selBilInvoiceType; ?>" />
<input type="hidden" name="txtBilInvoiceType" value="<?php print $txtBilInvoiceType; ?>" />
<input type="hidden" name="txtBilMessenger" value="<?php print $txtBilMessenger; ?>" />

<input type="hidden" name="radCustType" value="<?php print $radCustType; ?>" />
<input type="hidden" name="selBusinessType" value="<?php print $selBusinessType; ?>" />
<input type="hidden" name="txtBusinessType" value="<?php print $txtBusinessType; ?>" />

<input type="hidden" name="ext" value="<?php print $ext; ?>" />
<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
</form>