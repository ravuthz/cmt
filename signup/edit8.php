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
<script language="JavaScript" src="./javascript/ajax_location.js"></script>
<script language="javascript">
	function storeNameValue(index, cat){		
		if(cat == 1){
			fedit8.txtBilSangkat.value = fedit8.selBilsSangkat.options[index].text;
		}	
		else if(cat == 2){			
			fedit8.txtBilKhan.value = fedit8.selBilKhan.options[index].text;
			location(4, fedit8.selBilKhan.options[index].value, "selBilSangkat");
		}
		else if(cat == 3){
			fedit8.txtBilCity.value = fedit8.selBilCity.options[index].text;						
			location(3, fedit8.selBilCity.options[index].value, "selBilKhan");
		}
		else if(cat == 4){
			fedit8.txtBilCountry.value = fedit8.selBilCountry.options[index].text;			
			location(2, fedit8.selBilCountry.options[index].value, "selBilCity");
		}				
	}
	
	function ValidateForm(){
			if(Trim(fedit8.txtBilAddress.value) == ""){
				alert("Please enter billing address");
				fedit8.txtBilAddress.focus();
				return;
			}else if(fedit8.selBilCountry.options[fedit8.selBilCountry.selectedIndex].text == "Unknown"){
				alert("Pleas select billing country");
				fedit8.selBilCountry.focus();
				return;
			}else if(fedit8.selBilCity.options[fedit8.selBilCity.selectedIndex].text == "Unknown"){
				alert("Pleas select billing city");
				fedit8.selBilCity.focus();
				return;
			}else if(fedit8.selBilKhan.options[fedit8.selBilKhan.selectedIndex].text == "Unknown"){
				alert("Pleas select billing khan");
				fedit8.selBilKhan.focus();
				return;
			}else if(fedit8.selBilSangkat.options[fedit8.selBilSangkat.selectedIndex].text == "Unknown"){
				alert("Pleas select billing sangkat");
				fedit8.selBilSangkat.focus();
				return;
			}else if(fedit8.selBilInvoiceType.options[fedit8.selBilInvoiceType.selectedIndex].value == 1){
				if(Trim(fedit8.txtBilEmail.value) == ""){
					alert("Pleas enter billing email addresss");
					fedit8.txtBilEmail.focus();
					return;
				}
			}else if(fedit8.selBilInvoiceType.selectedIndex == 0){
				if(Trim(fedit8.txtBilEmail.value) == ""){
					alert("Pleas enter billing email addresss");
					fedit8.txtBilEmail.focus();
					return;
				}
			}if(Trim(fedit8.txtBilEmail.value) != ""){
				if(!isValidMail(Trim(fedit8.txtBilEmail.value))){
					alert("Invalid billing email address");
					fedit8.txtBilEmail.focus();
					return;
				}
			}			
								
			fedit8.txtBilSangkat.value = fedit8.selBilSangkat.options[fedit8.selBilSangkat.selectedIndex].text;
			fedit8.txtBilKhan.value = fedit8.selBilKhan.options[fedit8.selBilKhan.selectedIndex].text;
			fedit8.txtBilCity.value = fedit8.selBilCity.options[fedit8.selBilCity.selectedIndex].text;
			fedit8.txtBilCountry.value = fedit8.selBilCountry.options[fedit8.selBilCountry.selectedIndex].text;
			
			fedit8.txtBilInvoiceType.value = fedit8.selBilInvoiceType.options[fedit8.selBilInvoiceType.selectedIndex].text;
			fedit8.txtBilMessenger.value = fedit8.SelMessengerID.options[fedit8.SelMessengerID.selectedIndex].text;
			
			fedit8.btnSubmit.disabled = true;
			fedit8.submit();
		
	}
</script>

<form name="fedit8" method="post" action="./">
<table border="0" cellpadding="3" cellspacing="0" align="left">
	<tr>
		<td colspan="2" width="50%">
			<fieldset style="width:380px">
				<legend align="center">EDIT BILLING INFORMATION</legend>
					<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%">								 																
						<tr>
								<td align="left">Address:</td>
								<td align="left" colspan="3">
									<input type="text" name="txtBilAddress" class="boxenabled" tabindex="7" size="79" maxlength="100" value="<?php print $txtBilAddress; ?>" />
								</td>
							</tr>										
							<tr>
								<td align="left">Country:</td>
								<td align="left">
									<select name="selBilCountry" class="boxenabled" tabindex="8" style="width:200px" onChange="storeNameValue(this.selectedIndex, 4);">	
									<option value="<?php print $selBilCountry; ?>" selected="selected"><?php print $txtBilCountry; ?></option>												
										<?php
											$sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
											// sql 2005
											
											$que = $mydb->sql_query($sql);									
											if($que){
												while($rst = $mydb->sql_fetchrow($que)){	
													$CountryID = $rst['id'];
													$Country = $rst['name'];
													if($selIntCountry == $CountryID) 
														$sel = "selected";
													else
														$sel = "";
													print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
												}
											}
											$mydb->sql_freeresult();
										?>
									</select>
								</td>
								<td align="left">City:</td>
								<td align="left">
									<select name="selBilCity" class="boxenabled" tabindex="9" style="width:200px" onChange="storeNameValue(this.selectedIndex, 3);">	
										<option value="<?php print $selBilCity; ?>"><?php print $txtBilCity; ?></option>													
									</select>
								</td>										
								
							</tr>
							<tr>
								<td align="left">Khan:</td>
								<td align="left">
									<select name="selBilKhan" class="boxenabled" tabindex="10" style="width:200px" onChange="storeNameValue(this.selectedIndex, 2);">
										<option value="<?php print $selBilKhan; ?>"><?php print $txtBilKhan; ?></option>																										
									</select>
								</td>
								<td align="left">Sangkat:</td>
								<td align="left">
									<select name="selBilSangkat" class="boxenabled" tabindex="11" style="width:200px" onChange="storeNameValue(this.selectedIndex, 5);">
										<option value="<?php print $selBilSangkat; ?>"><?php print $txtBilSangkat; ?></option>																										
									</select>
								</td>																					
							</tr>
							<tr>
								<td align="left" nowrap="nowrap">
									Invoice type:
								</td>
								<td align="left">
									<select name="selBilInvoiceType" class="boxenabled" tabindex="12" onChange="storeNameValue(this.selectedIndex, 9);" style="width:200px;">
										<?php
											$sql = "SELECT InvoiceTypeID, InvoiceType from tlkpCustInvoiceType order by InvoiceType";
											// sql 2005
											
											$que = $mydb->sql_query($sql);									
											if($que){
												while($rst = $mydb->sql_fetchrow($que)){	
													$InvoiceTypeID = $rst['InvoiceTypeID'];
													$InvoiceType = $rst['InvoiceType'];												
													if($selBilInvoiceType == $InvoiceTypeID) 
														$sel = "selected";
													else
														$sel = "";
													print "<option value='".$InvoiceTypeID."' ".$sel.">".$InvoiceType."</option>";
												}
											}
											$mydb->sql_freeresult();
											
										?>
									</select>
								</td>
								<td align="left">Messenger</td>
								<td align="left">
									<select name="SelMessengerID" class="boxenabled" tabindex="13" onChange="storeNameValue(this.selectedIndex, 10);">
										<?php
											$sql = "SELECT MessengerID, Salutation, Name from tlkpMessenger order by Name";														
											$que = $mydb->sql_query($sql);									
											if($que){
												while($rst = $mydb->sql_fetchrow($que)){	
													$MessengerID = $rst['MessengerID'];
													$Salutation = $rst['Salutation'];
													$Name = $rst['Name'];	
													$MessengerName = $Salutation." ".$Name;
													if($SelMessengerID == $MessengerID) 
														$sel = "selected";
													else
														$sel = "";
													print "<option value='".$MessengerID."' ".$sel.">".$MessengerName."</option>";
												}
											}
											$mydb->sql_freeresult();
											
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td align="left" nowrap="nowrap">Billing Email:</td>
								<td colspan="3" align="left">
									<input type="text" name="txtBilEmail" tabindex="14" class="boxenabled" size="77" maxlength="50" value="<?php print $txtBilEmail; ?>" />
									</td>
							</tr>				
							<tr>
								<td align="center" colspan="4">
									<input type="reset" name="reset" value="Reset" class="button" />&nbsp;
									<input type="button" name="btnSubmit" value="Save" class="button" onClick="ValidateForm();" />
								</td>
							</tr>																																				 		
					 </table>							 
			</fieldset>
		</td>
	</tr>
</table>

<input type="hidden" name="pg" value="6" />

<input type="hidden" name="SubscriptionName" value="<?php print $SubscriptionName; ?>" />
<input type="hidden" name="PackageID" value="<?php print $PackageID; ?>" />
<input type="hidden" name="RegistrationFee" value="<?php print $RegistrationFee; ?>" />
<input type="hidden" name="ConfigurationFee" value="<?php print $ConfigurationFee; ?>" />
<input type="hidden" name="CPEFee" value="<?php print $CPEFee; ?>" />
<input type="hidden" name="ISDNFee" value="<?php print $ISDNFee; ?>" />
<input type="hidden" name="SPNFee" value="<?php print $SPNFee; ?>" />
<input type="hidden" name="SelStationID" value="<?php print $SelStationID; ?>" />
<input type="hidden" name="SelPhonePreset" value="<?php print $SelPhonePreset; ?>" />
<input type="hidden" name="UserName" value="<?php print $UserName; ?>" />
<input type="hidden" name="Password" value="<?php print $Password; ?>" />
<input type="hidden" name="selSalesman" value="<?php print $selSalesman; ?>" />
<input type="hidden" name="chkncDeposit" value="<?php print $chkncDeposit; ?>" />
<input type="hidden" name="ncDeposit" value="<?php print $ncDeposit; ?>" />
<input type="hidden" name="chkicDeposit" value="<?php print $chkicDeposit; ?>" />
<input type="hidden" name="icDeposit" value="<?php print $icDeposit; ?>" />
<input type="hidden" name="chkmfDeposit" value="<?php print $chkmfDeposit; ?>" />
<input type="hidden" name="mfDeposit" value="<?php print $mfDeposit; ?>" />
<input type="hidden" name="txtCPE" value="<?php print $txtCPE; ?>" />
<input type="hidden" name="CPETypeID" value="<?php print $CPETypeID; ?>" />

<input type="hidden" name="txtAccPackage" value="<?php print $txtAccPackage; ?>" />
<input type="hidden" name="txtSaleman" value="<?php print $txtSaleman; ?>" />
<input type="hidden" name="txtStation" value="<?php print $txtStation; ?>" />
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

<input type="hidden" name="txtBilSangkat" value="<?php print $txtBilSangkat; ?>" />
<input type="hidden" name="txtBilKhan" value="<?php print $txtBilKhan; ?>" />
<input type="hidden" name="txtBilCity" value="<?php print $txtBilCity; ?>" />
<input type="hidden" name="txtBilCountry" value="<?php print $txtBilCountry; ?>" />
<input type="hidden" name="txtBilInvoiceType" value="<?php print $txtBilInvoiceType; ?>" />
<input type="hidden" name="txtBilMessenger" value="<?php print $txtBilMessenger; ?>" />

<input type="hidden" name="radCustType" value="<?php print $radCustType; ?>" />
<input type="hidden" name="selBusinessType" value="<?php print $selBusinessType; ?>" />
<input type="hidden" name="txtBusinessType" value="<?php print $txtBusinessType; ?>" />

<input type="hidden" name="vatRegistrationFee" value="<?php print $vatRegistrationFee; ?>" />
<input type="hidden" name="vatConfigurationFee" value="<?php print $vatConfigurationFee; ?>" />
<input type="hidden" name="vatCPEFee" value="<?php print $vatCPEFee; ?>" />
<input type="hidden" name="vatISDNFee" value="<?php print $vatISDNFee; ?>" />
<input type="hidden" name="vatSPNFee" value="<?php print $vatSPNFee; ?>" />

<input type="hidden" name="ext" value="<?php print $ext; ?>" />
<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
</form>