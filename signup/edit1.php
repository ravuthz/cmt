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
<script language="JavaScript" src="./javascript/validphone.js"></script>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="javascript">
	function storeNameValue(index, cat){		
		if(cat == 1){
			fedit2.txtDesSangkat.value = fedit2.selDesSangkat.options[index].text;
		}	
		else if(cat == 2){			
			fedit2.txtDesKhan.value = fedit2.selDesKhan.options[index].text;
			location(4, fedit2.selDesKhan.options[index].value, "selDesSangkat");
		}
		else if(cat == 3){
			fedit2.txtDesCity.value = fedit2.selDesCity.options[index].text;						
			location(3, fedit2.selDesCity.options[index].value, "selDesKhan");
		}
		else if(cat == 4){
			fedit2.txtDesCountry.value = fedit2.selDesCountry.options[index].text;			
			location(2, fedit2.selDesCountry.options[index].value, "selDesCity");
		}
		else if(cat == 5){
			fedit2.txtBusinessType.value = fedit2.selBusinessType.options[index].text;			
			
		}				
	}
	
	function ValidateForm(){					
			
		fedit2.txtDesNationality.value = fedit2.selDesNationality.options[fedit2.selDesNationality.selectedIndex].text;
		fedit2.txtDesOccupation.value = fedit2.selDesOccupation.options[fedit2.selDesOccupation.selectedIndex].text;
		fedit2.txtDesSangkat.value = fedit2.selDesSangkat.options[fedit2.selDesSangkat.selectedIndex].text;
		fedit2.txtDesKhan.value = fedit2.selDesKhan.options[fedit2.selDesKhan.selectedIndex].text;
		fedit2.txtDesCity.value = fedit2.selDesCity.options[fedit2.selDesCity.selectedIndex].text;
		fedit2.txtDesCountry.value = fedit2.selDesCountry.options[fedit2.selDesCountry.selectedIndex].text;
		fedit2.btnSubmit.disabled = true;
		fedit2.submit();
		
	}
</script>
<form name="fedit1" method="post" action="./">
<table border="0" cellpadding="3" cellspacing="0" align="left">
	<tr>
		<td colspan="2" width="50%">
			<fieldset style="width:380px">
				<legend align="center">EDIT CUSTOMER INFORMATION</legend>
					<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%">								 																		
						<tr>
							<td align="left" nowrap="nowrap">
								Name:
							</td>
							<td align="left" colspan="3">
								<input type="text" name="txtCustomerName" class="boxenabled" tabindex="2" size="76" value="<?php print $txtCustomerName;?>" /><img src="./images/required.gif" border="0" />
							</td>
						</tr>
						<?php if($radCustType == 2){ ?>
						<tr>
							<td align="left">Category:</td>
							<td align="left" colspan="3">
								<select name="selBusinessType" class="boxenabled" tabindex="2" style="width:190px" onchange="storeNameValue(this.selectedIndex, 5);">
									<?php
										$sql = "SELECT TypeID, Name from tlkpCustBusinessType where IsShow = 1 order by Name";
										// sql 2005
										
										$que = $mydb->sql_query($sql);									
										if($que){
											while($rst = $mydb->sql_fetchrow($que)){	
												$TypeID = $rst['TypeID'];
												$Name = $rst['Name'];
												if($selBusinessType = $TypeID)
													$sel = "selected";
												else
													$sel = "";
												print "<option value='".$TypeID."' ".$sel.">".$Name."</option>";
											}
										}
										$mydb->sql_freeresult();
									?>
								</select>
							</td>
						</tr>
						
						<tr>
							<td align="left" nowrap="nowrap">Business No.:</td>
							<td align="left">
								<input type="text" tabindex="3" name="txtCusBus" class="boxenabled" size="27" maxlength="30" value="<?php print $txtCusBus; ?>" />													
							</td>
						<?php
							}else{								
						?>
						<tr>
							<td align="left" nowrap="nowrap">Date of birth:</td>
							<td align="left">
							<input type="hidden" name="selBusinessType" value="1" />
							<input type="text" tabindex="3" name="txtCusDOB" class="boxenabled" size="15" maxlength="30" value="<?php print $txtCusDOB;?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
								<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fedit1|txtCusDOB', '', 'width=200,height=220,top=250,left=350');">
									<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
								</button>(YYYY-MM-DD)
							</td>						
							<td align="left">Duplicate:</td>							
							<td align="left">
								<select name="selCusDuplicateID" class="boxenabled" tabindex="4">
									<option value="ID Card" <?php if($selCusDuplicateID == "ID Card") print "selected"?>>ID Card</option>
									<option value="Passport" <?php if($selCusDuplicateID == "Passport") print "selected"?>>Passport</option>
									<option value="Family book" <?php if($selCusDuplicateID == "Family book") print "selected"?>>Family book</option>
								</select>
								<input type="text" name="txtCusDuplicate" class="boxenabled" tabindex="5" size="10" value="<?php print $txtCusDuplicate;?>" />
							</td>
						</tr>
						<?php
							}
						?>
						<tr>
							<td align="left" nowrap="nowrap">
								VAT charge:
							</td>
							<td align="left">
								<input type="radio" name="radExemption" tabindex="6" value="0" <?php if($radExemption == 0) print("checked"); ?> checked />YES&nbsp;&nbsp;&nbsp;
								<input type="radio" name="radExemption" tabindex="7" value="1" <?php if($radExemption == 1) print("checked"); ?> />NO
							</td>
							<?php if($radCustType == 2){ ?>
							<td align="left" nowrap="nowrap">
								VAT Number:
							</td>
							<td align="left">
								<input type="text" name="txtVATNumber" class="boxenabled" tabindex="8" size="27" maxlength="30" value="<?php print $txtVATNumber;?>" />	
							</td>
							<?php }else{	?>
							<td align="left" nowrap="nowrap">&nbsp;
								
							</td>
							<td align="left">
								<input type="hidden" name="txtVATNumber" class="boxenabled" tabindex="8" size="27" maxlength="30" value="<?php print $txtVATNumber;?>" />	
							</td>
							<?php }?>
						</tr>
						<tr>
						<?php if($radCustType == 1){ ?>
						<td align="left">
								Occupation:
							</td>
							<td align="left">
								<select name="selCusOccupation" class="boxenabled" tabindex="10" style="width:190px">										
									<?php
										$sql = "SELECT CareerID, Career from tlkpCareer order by CareerID";
										// sql 2005
										
										$que = $mydb->sql_query($sql);									
										if($que){
											while($rst = $mydb->sql_fetchrow($que)){	
												$CareerID = $rst['CareerID'];
												$Career = $rst['Career'];												
												if($selCusOccupation == $CareerID) 
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
							<?php 
								} else{
							 ?>
							 	<td></td>
								<td>							
								<input type="hidden" name="selCusOccupation" value="1" />
								</td>
							<?php		
								}
							?>
							<?php if($radCustType == 1){ ?>
							<td align="left">Nationality:</td>
							<td align="left">
								<select name="selCusNationality" class="boxenabled" tabindex="9" style="width:190px">
									<option value="38" selected="selected">Cambodian</option>	
									<?php
										$sql = "SELECT CountryID, Country from tlkpCountry order by Country";
										// sql 2005
										
										$que = $mydb->sql_query($sql);									
										if($que){
											while($rst = $mydb->sql_fetchrow($que)){	
												$CountryID = $rst['CountryID'];
												$Country = $rst['Country'];
												if($selCusNationality == $CountryID) 
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
							<?php } else{ ?>
							<td></td>
							<td>							
							<input type="hidden" name="selCusNationality" value="38" />
							</td>
							<?php } ?>																					
						</tr>
						<tr>
							<td align="left">Telephone:</td>
							<td align="left"><input type="text" name="txtCusPhone" value="<?php print $txtCusPhone; ?>" class="boxenabled" tabindex="11" size="29" onkeyup="ValidatePhone(this);" onblur="CheckPhone(this);" />
							</td>
							<td align="left">Email:</td>
							<td align="left"><input type="text" name="txtCusEmail" value="<?php print $txtCusEmail; ?>" class="boxenabled" tabindex="12" size="29" /> </td>
						</tr>
						<tr>
							<td align="left">Address:</td>
							<td align="left" colspan="3">
								<input type="text" name="txtCusAddress" class="boxenabled" tabindex="13" size="79" maxlength="100" value="<?php print $txtCusAddress; ?>" />
							</td>
						</tr>
						<tr>
							<td align="left">Country:</td>
							<td align="left">
								<div id="divDesCountry">
								<select name="selCusCountry" id="selCusCountry" class="boxenabled" tabindex="14" style="width:200px" onChange="storeNameValue(this.selectedIndex, 8);">	
								<option value="0" selected="selected">Unknown</option>												
									<?php
										$sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
										// sql 2005
										
										$que = $mydb->sql_query($sql);									
										if($que){
											while($rst = $mydb->sql_fetchrow($que)){	
												$CountryID = $rst['id'];
												$Country = $rst['name'];
												if($selCusCountry == $CountryID) 
													$sel = "selected";
												else
													$sel = "";
												print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
											}
										}
										$mydb->sql_freeresult();
									?>
								</select>
								</div>
							</td>
							<td align="left">City:</td>
							<td align="left">

								<select name="selCusCity" id="selCusCity" class="boxenabled" tabindex="15" style="width:200px" onChange="storeNameValue(this.selectedIndex, 7);">	
									
									<option value="<?php print $selCusCity;?>" selected="selected"><?php print $txtCusCity;?></option>													
								</select>
								
							</td>																					
						</tr>
						<tr>																				
							<td align="left">Khan:</td>
							<td align="left">
								<select name="selCusKhan" id="selCusKhan" class="boxenabled" tabindex="16" style="width:200px" onChange="storeNameValue(this.selectedIndex, 6);">
									<option value="<?php print $selCusKhan;?>"><?php print $txtCusKhan;?></option>																										
								</select>
							</td>
							<td align="left">Sangkat:</td>
							<td align="left">
								<select name="selCusSangkat" id="selCusSangkat" class="boxenabled" tabindex="17" style="width:200px" onChange="storeNameValue(this.selectedIndex, 5);">
									<option value="<?php print $selCusSangkat;?>" selected="selected"><?php print $txtCusSangkat; ?></option>													
								</select>
							</td>
						</tr>
						<tr>
							<td align="center" colspan="4">
								<input type="reset" name="reset" value="Reset" class="button" />&nbsp;
								<input type="submit" name="submit" value="Save" class="button" />
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
<input type="hidden" name="txtBusinessType" value="<?php print $txtBusinessType; ?>" />

<input type="hidden" name="vatRegistrationFee" value="<?php print $vatRegistrationFee; ?>" />
<input type="hidden" name="vatConfigurationFee" value="<?php print $vatConfigurationFee; ?>" />
<input type="hidden" name="vatCPEFee" value="<?php print $vatCPEFee; ?>" />
<input type="hidden" name="vatISDNFee" value="<?php print $vatISDNFee; ?>" />
<input type="hidden" name="vatSPNFee" value="<?php print $vatSPNFee; ?>" />

<input type="hidden" name="ext" value="<?php print $ext; ?>" />
<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
</form>