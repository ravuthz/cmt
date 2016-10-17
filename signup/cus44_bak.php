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
	$sql = "SELECT REPLACE(ServiceName, ' ', '') 'Domain' 
					FROM tlkpService 
					WHERE ServiceTypeID = 1 AND ServiceID = $ServiceID";
	$que = $mydb->sql_query($sql);
	$result = $mydb->sql_fetchrow($que);
	$domain = $result['Domain'];
	$mydb->sql_freeresult($que);
?>
<script language="javascript" src="./javascript/ajax_checkusername.js"></script>
<script language="javascript">
	
	//
	//	Validate from
	//
	function ValidateForm(){
		Subscription = fsignupcust4.SubscriptionName;
		Package = fsignupcust4.PackageID;
		Regisration = fsignupcust4.RegistrationFee;
		ConfigurationFee = fsignupcust4.ConfigurationFee;
		CPEFee = fsignupcust4.CPEFee;
		ISDNFee = fsignupcust4.ISDNFee;
		SPNFee = fsignupcust4.SPNFee;
		SelStationID = fsignupcust4.SelStationID;
		UserName = fsignupcust4.txtAccountName;
		Password = fsignupcust4.Password;
		ConfirmPassword = fsignupcust4.ConfirmPassword;
		selSalesman = fsignupcust4.selSalesman;
		ncdeposit = fsignupcust4.ncDeposit;
		icdeposit = fsignupcust4.icDeposit;
		mfdeposit = fsignupcust4.mfDeposit;
		
		if(Trim(Subscription.value) ==""){
			alert("Please enter subscription fee");
			Subscription.focus();
			return;
		}else if(Package.selectedIndex < 1){
			alert("Please select package value");
			Package.focus();
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
		}else if(Trim(Password.value).length < 2){
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
//================================ national call deposit
		if(ncdeposit.value == ""){
			ncdeposit.value = 0;		
		}else if(!isNumber(ncdeposit.value)){
			alert("National call deposit must be number");
			ncdeposit.focus();
			return;
		}else if(Number(ncdeposit.value) < 0){
			alert("National call deposit must be positive number");
			ncdeposit.focus();
			return;
		}
//================================== international call deposit
		if(icdeposit.value == ""){
			icdeposit.value = 0;		
		}else if(!isNumber(icdeposit.value)){
			alert("International call deposit must be number");
			icdeposit.focus();
			return;
		}else if(Number(icdeposit.value) < 0){
			alert("International call deposit must be positive number");
			icdeposit.focus();
			return;
		}
//================================== international call deposit
		//if(mfdeposit.value == ""){
//			mfdeposit.value = 0;		
//		}else if(!isNumber(mfdeposit.value)){
//			alert("National call deposit must be number");
//			mfdeposit.focus();
//			return;
//		}else if(Number(mfdeposit.value) < 0){
//			alert("National call deposit must be positive number");
//			mfdeposit.focus();
//			return;
//		}				
		domain = fsignupcust4.domain.value;
		
		fsignupcust4.txtAccountName.value = UserName.value + domain;
		fsignupcust4.btnNext.disabled = true;
		fsignupcust4.submit();	
	}
	
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
		username = fsignupcust4.txtAccountName.value;		
		domain = fsignupcust4.domain.value;
		username = username + domain;
		url = "./php/ajax_checkusername.php?username=" + username +"&mt=" + new Date().getTime();
		//alert(url);
		checkUserName(url, retDiv);
		
	}
	
	//
	// Auto assign default value
	//
	function autoValue(index){
		//fsignupcust4.SPNFee.value = fsignupcust4.tmpdeposit.options[index].value;
		fsignupcust4.RegistrationFee.value = fsignupcust4.tmpdeposit.options[index].text;
		fsignupcust4.ConfigurationFee.value = fsignupcust4.tmpother.options[index].value;
		fsignupcust4.CPEFee.value = fsignupcust4.tmpother.options[index].text;
		//fsignupcust4.ISDNFee.value = fsignupcust4.tmpother1.options[index].text;
		fsignupcust4.txtAccPackage.value = fsignupcust4.PackageID.options[index].text;
	}
	
	//
	//	Store name of value
	//
	function storeNameValue(selValue, cat){
		if(cat == 1)
			fsignupcust4.txtSaleman.value = selValue;
		if(cat == 2)
			fsignupcust4.txtStation.value = selValue;
		if(cat == 3)
			fsignupcust4.txtCPE.value = selValue;
	}
	
	//
	//
	//
	function chk(index){
		if(index == 1){
			if(fsignupcust4.chkncDeposit.checked == true){
				fsignupcust4.ncDeposit.disabled = false;
				fsignupcust4.ncDeposit.className = "boxenabled";
			}else{
				fsignupcust4.ncDeposit.disabled = true;
				fsignupcust4.ncDeposit.className = "boxdisabled";
			}
		}else if(index == 2){
			if(fsignupcust4.chkicDeposit.checked == true){
				fsignupcust4.icDeposit.disabled = false;
				fsignupcust4.icDeposit.className = "boxenabled";
			}else{
				fsignupcust4.icDeposit.disabled = true;
				fsignupcust4.icDeposit.className = "boxdisabled";
			}
		}else if(index == 3){
			if(fsignupcust4.chkmfDeposit.checked == true){
				fsignupcust4.mfDeposit.disabled = false;
				fsignupcust4.mfDeposit.className = "boxenabled";
			}else{
				fsignupcust4.mfDeposit.disabled = true;
				fsignupcust4.mfDeposit.className = "boxdisabled";
			}
		}
		
	}
</script>
<br>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="180">
			<fieldset>
				<legend align="center">Signup Customer Process</legend>
					<table border="0" cellpadding="5" cellspacing="0" align="center" width="100%" bordercolor="#aaaaaa" bgcolor="#feeac2" id="submenu">			
						<tr><td>&nbsp;</td></tr>			
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Customer profile
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Address information
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Product information
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left" bgcolor="#ffffff">
								<b>Account information</b>
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Information summary
						 	</td>		 
						</tr>				
					 </table>
			</fieldset>		
		</td>
		<td valign="top" width="600" align="left"> 
			<form name="fsignupcust4" method="post" action="./">
			 <table border="0" cellpadding="0" cellspacing="0" align="left" width="100%">
				<tr>
					<td>
						<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle" height="18"><b>ACCOUNT INFORMATION</b></td>		
							</tr>		
							<tr>
								<td>
									<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2" align="left">													
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
												<select name="PackageID" tabindex="2" onchange="autoValue(this.selectedIndex);">
												<option value="">Please select package</option>
													<?php print $tmppackage; ?>
												</select>
											</td>
										</tr>	
										<tr>
											<td align="left">Monthly Fee Deposit:</td>
											<td align="left">												
												<input type="hidden" name="chkmfDeposit" value="1" />
												<input type="text" name="mfDeposit" value="0" class="boxenabled" tabindex="18" size="30" />
											</td> 
										</tr>									
										<tr>
											<td align="left" nowrap="nowrap">Registration fee:</td>
											<td align="left">
												<input type="text" name="RegistrationFee" value="0" class="boxenabled" tabindex="3" size="30" /> 
												<input type="checkbox" name="vatRegistrationFee" value="1" />Charge VAT
											</td>
										</tr>		
										<tr>
											<td align="left" nowrap="nowrap">Installation fee:</td>
											<td align="left">
												<input type="text" name="ConfigurationFee" value="0" class="boxenabled" tabindex="4" size="30" /> 
												<input type="checkbox" name="vatConfigurationFee" value="1" />Charge VAT
											</td>
										</tr>		
										<tr>
											<td align="left" nowrap="nowrap">CPE cost:</td>
											<td align="left">
												<input type="text" name="CPEFee" value="0" class="boxenabled" tabindex="5" size="30" /> 
												<input type="checkbox" name="vatCPEFee" value="1" />Charge VAT
											</td>
										</tr>																																										
										<tr>
											<td align="left">Station:</td>
											<td align="left">
												<select name="SelStationID" class="boxenabled" tabindex="8" onchange="storeNameValue(this.options[this.selectedIndex].text, 2);">
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
														$sql = "SELECT CPEID, CPEName from tlkpCPE WHERE Active = 1 and ServiceID = $ServiceID order by CPEName";
														// sql 2005
														
														$que = $mydb->sql_query($sql);									
														if($que){
															while($rst = $mydb->sql_fetchrow($que)){	
																$CPEID = $rst['CPEID'];
																$CPEName = $rst['CPEName'];																
																print "<option value='".$CPEID."'>".$CPEName."</option>";
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
												<input type="text" name="txtAccountName" id="txtAccountName" value="<?php print $txtAccountName;?>"  class="boxenabled" tabindex="10" size="30" onblur="ValidAccount('dUserName');" /><img src="./images/required.gif" border="0" /><span style="display:none" id="dUserName" class="error"></span>
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
												<input type="password" name="ConfirmPassword" value="<?php print $ConfirmPassword;?>"  class="boxenabled" tabindex="12" size="30" maxlength="20" /><img src="./images/required.gif" border="0" />
											</td>
										</tr>	
										<tr>
											<td align="left">Salesman:</td>
											<td align="left">
												<select name="selSalesman" class="boxenabled" tabindex="13" onchange="storeNameValue(this.options[this.selectedIndex].text, 1);">
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
									</table>
								</td>							
							</tr> 							 
						</table>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr> 				  
					<td align="center">												
						<input type="button" tabindex="19" name="btnNext" value="Next >>" class="button" onClick="ValidateForm();" />						
					</td>
				 </tr>		   
			 </table>
					<input type="hidden" name="pg" id="pg" value="7">
					
					<!-- ==================== temporary ================ -->
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
					<!--
					//
					//	Hidden fields
					//
					-->
					<input type="hidden" name="domain" value="@<?php print strtolower($domain); ?>" />
					<input type="hidden" name="ISDNFee" value="0" class="boxenabled" tabindex="6" size="30" /> 
					<input type="hidden" name="SPNFee" value="0" class="boxenabled" tabindex="7" size="30" /> 
					<input type="hidden" name="txtAccPackage" value="" />
					<input type="hidden" name="txtSaleman" value="" />
					<input type="hidden" name="txtCPE" value="" />
					<input type="hidden" name="txtStation" value="" />

					<input type="hidden" name="ServiceID" value="<?php print $ServiceID; ?>" />
					
					<input type="hidden" name="ncDeposit" value="0" />
					<input type="hidden" name="icDeposit" value="0" />
					<input type="hidden" name="mfDeposit" value="0" />
					
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
					
					<input type="hidden" name="radCustType" value="<?php print $radCustType; ?>" />
					<input type="hidden" name="selBusinessType" value="<?php print $selBusinessType; ?>" />
					<input type="hidden" name="txtBusinessType" value="<?php print $txtBusinessType; ?>" />
					
					<input type="hidden" name="ext" value="<?php print $ext; ?>" />
					<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
				</form>
				
			</td>
		</tr>
	</table>	
<br>&nbsp;
<?php
# Close connection
$mydb->sql_close();
?>