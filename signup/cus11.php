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
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/validphone.js"></script>
<script language="JavaScript" src="./javascript/ajax_location.js"></script>
<script language="javascript">
	function storeNameValue(index, cat){		
		if(cat == 5){
			fsignupcust1.txtCusSangkat.value = fsignupcust1.selCusSangkat.options[index].text;
		}	
		else if(cat == 6){			
			fsignupcust1.txtCusKhan.value = fsignupcust1.selCusKhan.options[index].text;
			location(4, fsignupcust1.selCusKhan.options[index].value, "selCusSangkat");
		}
		else if(cat == 7){
			fsignupcust1.txtCusCity.value = fsignupcust1.selCusCity.options[index].text;						
			location(3, fsignupcust1.selCusCity.options[index].value, "selCusKhan");
		}
		else if(cat == 8){
			fsignupcust1.txtCusCountry.value = fsignupcust1.selCusCountry.options[index].text;			
			location(2, fsignupcust1.selCusCountry.options[index].value, "selCusCity");
		}		
		else if(cat == 11){
			fsignupcust1.txtDesSangkat.value = fsignupcust1.selDesSangkat.options[index].text;
		}
		else if(cat == 12){
			fsignupcust1.txtDesKhan.value = fsignupcust1.selDesKhan.options[index].text;
			location(4, fsignupcust1.selDesKhan.options[index].value, "selDesSangkat");
		}
		else if(cat == 13){
			fsignupcust1.txtDesCity.value = fsignupcust1.selDesCity.options[index].text;						
			location(3, fsignupcust1.selDesCity.options[index].value, "selDesKhan");
		}			
		else if(cat == 14){
			fsignupcust1.txtDesCountry.value = fsignupcust1.selDesCountry.options[index].text;			
			location(2, fsignupcust1.selDesCountry.options[index].value, "selDesCity");
		}			
			
	}
	
	function setTheSame(){
		txtCustPhone = fsignupcust1.txtCusPhone;
		txtCustEmail = fsignupcust1.txtCusEmail;
		//selCustDuplicateID = fsignupcust1.selCusDuplicateID.selectedIndex;
		//txtCustDuplicate = fsignupcust1.txtCusDuplicate;
		//selCustNationality = fsignupcust1.selCusNationality.selectedIndex;
		//selCustOccupation = fsignupcust1.selCusOccupation.selectedIndex;
		txtCustAddress = fsignupcust1.txtCusAddress;
		selCusSangkat = fsignupcust1.selCusSangkat.selectedIndex;
		selCusKhan = fsignupcust1.selCusKhan.selectedIndex;
		selCusCity = fsignupcust1.selCusCity.selectedIndex;
		selCusCountry = fsignupcust1.selCusCountry.selectedIndex;				
		
		if(fsignupcust1.thesame.checked == true){
			fsignupcust1.txtDesAddress.value = txtCustAddress.value;
		//	fsignupcust1.selDesDuplicateID.options[selCustDuplicateID].selected = "selected";
			//fsignupcust1.selDesOccupation.options[selCustOccupation].selected = "selected";
			//fsignupcust1.selDesNationality.options[selCustNationality].selected = "selected";			
			
			fsignupcust1.selDesCountry.options[0].text = fsignupcust1.selCusCountry.options[selCusCountry].text;
			fsignupcust1.selDesCountry.options[0].value = fsignupcust1.selCusCountry.options[selCusCountry].value;								
			fsignupcust1.selDesCountry.options[0].selected = "selected";
			
			fsignupcust1.selDesCity.options[0].text = fsignupcust1.selCusCity.options[selCusCity].text;
			fsignupcust1.selDesCity.options[0].value = fsignupcust1.selCusCity.options[selCusCity].value;								
			fsignupcust1.selDesCity.options[0].selected = "selected";
			
			fsignupcust1.selDesKhan.options[0].text = fsignupcust1.selCusKhan.options[selCusKhan].text;
			fsignupcust1.selDesKhan.options[0].value = fsignupcust1.selCusKhan.options[selCusKhan].value;								
			fsignupcust1.selDesKhan.options[0].selected = "selected";
			
			fsignupcust1.selDesSangkat.options[0].text = fsignupcust1.selCusSangkat.options[selCusSangkat].text;
			fsignupcust1.selDesSangkat.options[0].value = fsignupcust1.selCusSangkat.options[selCusSangkat].value;								
			fsignupcust1.selDesSangkat.options[0].selected = "selected";
							
			fsignupcust1.txtDesPhone.value = txtCustPhone.value;
			fsignupcust1.txtDesEmail.value = txtCustEmail.value;
			//fsignupcust1.txtDesDuplicate.value = txtCustDuplicate.value;
			
		}else{
			fsignupcust1.selDesCountry.options[0].text = "Unknown";
			fsignupcust1.selDesCountry.options[0].value = 0;								
			fsignupcust1.selDesCountry.options[0].selected = "selected";
			
			fsignupcust1.selDesCity.options[0].text = "Unknown";
			fsignupcust1.selDesCity.options[0].value = 0;								
			fsignupcust1.selDesCity.options[0].selected = "selected";
			
			fsignupcust1.selDesKhan.options[0].text = "Unknown";
			fsignupcust1.selDesKhan.options[0].value = 0;								
			fsignupcust1.selDesKhan.options[0].selected = "selected";
			
			fsignupcust1.selDesSangkat.options[0].text = "Unknown";
			fsignupcust1.selDesSangkat.options[0].value = 0;								
			fsignupcust1.selDesSangkat.options[0].selected = "selected";
			
			fsignupcust1.txtDesAddress.value = "";				
			fsignupcust1.txtDesPhone.value = "";
			fsignupcust1.txtDesEmail.value = "";
		}
	}
	
	function ValidateForm(){
		if(fsignupcust1.txtCustomerName.value == ""){
			alert("Please enter customer name.");
			fsignupcust1.txtCustomerName.focus();
			return;
		}else{
			fsignupcust1.txtBusinessType.value = fsignupcust1.selBusinessType.options[fsignupcust1.selBusinessType.selectedIndex].text;
			//fsignupcust1.txtCusNationality.value = fsignupcust1.selCusNationality.options[fsignupcust1.selCusNationality.selectedIndex].text;
			//fsignupcust1.txtCusOccupation.value = fsignupcust1.selCusOccupation.options[fsignupcust1.selCusOccupation.selectedIndex].text;
			fsignupcust1.txtCusSangkat.value = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].text;
			fsignupcust1.txtCusKhan.value = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].text;
			fsignupcust1.txtCusCity.value = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].text;
			fsignupcust1.txtCusCountry.value = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].text;
			
			fsignupcust1.txtDesNationality.value = fsignupcust1.selDesNationality.options[fsignupcust1.selDesNationality.selectedIndex].text;
			fsignupcust1.txtDesOccupation.value = fsignupcust1.selDesOccupation.options[fsignupcust1.selDesOccupation.selectedIndex].text;
			fsignupcust1.txtDesSangkat.value = fsignupcust1.selDesSangkat.options[fsignupcust1.selDesSangkat.selectedIndex].text;
			fsignupcust1.txtDesKhan.value = fsignupcust1.selDesKhan.options[fsignupcust1.selDesKhan.selectedIndex].text;
			fsignupcust1.txtDesCity.value = fsignupcust1.selDesCity.options[fsignupcust1.selDesCity.selectedIndex].text;
			fsignupcust1.txtDesCountry.value = fsignupcust1.selDesCountry.options[fsignupcust1.selDesCountry.selectedIndex].text;
			fsignupcust1.btnNext.disabled = true;
			fsignupcust1.submit();
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
						 	<td style="border-top:1px solid" align="left" bgcolor="#ffffff">
								<b>Customer profile</b>
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Contact information
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Product information
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Account information
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Address information
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
			<form name="fsignupcust1" method="post" action="./">
			 	<table border="0" cellpadding="0" cellspacing="0" align="left" width="100%">
					<tr>
						<td>
							<!-- ================================ CUSTOMER PROFILE ================================-->
							<!-- ==================================================================================-->
							<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
								<tr>
									<td align="left" class="formtitle" height="18"><b>CORPORATE CUSTOMER PROFILE</b></td>
								</tr>
								<tr>
									<td valign="top">
										<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">								 							
											
											<tr>
												<td align="left" nowrap="nowrap">
													Name:
												</td>
												<td align="left" colspan="3">
													<input type="text" name="txtCustomerName" class="boxenabled" tabindex="2" size="76" value="" /><img src="./images/required.gif" border="0" />
												</td>
											</tr>
											<tr>
												<td align="left">Category:</td>
												<td align="left">
													<select name="selBusinessType" class="boxenabled" tabindex="2" style="width:190px">
														<?php
															$sql = "SELECT TypeID, Name from tlkpCustBusinessType where IsShow = 1 order by Name";
															// sql 2005
															
															$que = $mydb->sql_query($sql);									
															if($que){
																while($rst = $mydb->sql_fetchrow($que)){	
																	$TypeID = $rst['TypeID'];
																	$Name = $rst['Name'];
																	
																	print "<option value='".$TypeID."'>".$Name."</option>";
																}
															}
															$mydb->sql_freeresult();
														?>
													</select>
												</td>	
												<td align="left" nowrap="nowrap">Business No.:</td>
												<td align="left"><input type="text" tabindex="3" name="txtCusBus" class="boxenabled" size="29" maxlength="30" value="" />													
												</td>										
											<tr>
												<td align="left" nowrap="nowrap">
													VAT charge:
												</td>
												<td align="left">
													<input type="radio" name="radExemption" tabindex="6" value="0" <?php if($radExemption == 0) print("checked"); ?> checked />YES&nbsp;&nbsp;&nbsp;
													<input type="radio" name="radExemption" tabindex="7" value="1" <?php if($radExemption == 1) print("checked"); ?> />NO
												</td>
												<td align="left" nowrap="nowrap">
													VAT number:
												</td>
												<td align="left">
													<input type="text" name="txtVATNumber" class="boxenabled" tabindex="8" size="29" maxlength="30" value="<?php print $txtVATNumber;?>" />	
												</td>
											</tr>
											<!--<tr>												
												<td align="left">
													Occupation:
												</td>
												<td align="left">
													<select name="selCusOccupation" class="boxenabled" tabindex="10" style="width:200px">										
														<?php
															$sql = "SELECT CareerID, Career from tlkpCareer order by CareerID";
															// sql 2005
															
															$que = $mydb->sql_query($sql);									
															if($que){
																while($rst = $mydb->sql_fetchrow($que)){	
																	$CareerID = $rst['CareerID'];
																	$Career = $rst['Career'];												
																	if($selCustOccupation == $CareerID) 
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
												
											</tr>-->
											<tr>
												<td align="left">Telephone:</td>
												<td align="left"><input type="text" name="txtCusPhone" value="<?php print $txtCusPhone; ?>" class="boxenabled" tabindex="11" size="29" onkeyup="ValidatePhone(this);" onblur="CheckPhone(this);" />
												</td>
												<td align="left">Email:</td>
												<td align="left"><input type="text" name="txtCusEmail" value=<?php print $txtCusEmail; ?>"" class="boxenabled" tabindex="12" size="29" /> </td>
											</tr>
											<tr>
												<td align="left">Address:</td>
												<td align="left" colspan="3">
													<input type="text" name="txtCusAddress" class="boxenabled" tabindex="13" size="79" maxlength="100" value="<?php print $txtCustAddress; ?>" />
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
																	if($selDesCountry == $CountryID) 
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
														
														<option value="0" selected="selected">Unknown</option>													
													</select>
													
												</td>																					
											</tr>
											<tr>																				
												<td align="left">Khan:</td>
												<td align="left">
													<select name="selCusKhan" id="selCusKhan" class="boxenabled" tabindex="16" style="width:200px" onChange="storeNameValue(this.selectedIndex, 6);">
														<option value="0">Unknown</option>																										
													</select>
												</td>
												<td align="left">Sangkat:</td>
												<td align="left">
													<select name="selCusSangkat" id="selCusSangkat" class="boxenabled" tabindex="17" style="width:200px" onChange="storeNameValue(this.selectedIndex, 5);">
														<option value="0">Unknown</option>													
													</select>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<!--================================= End custoemr =========================== !-->
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle" height="18"><b>DESIGNATE PROFILE</b>  <font color="#000000">[<input type="checkbox" name="thesame" onClick="setTheSame();" tabindex="18">The same as customer ]</font></td>
							</tr>
							<tr>
								<td valign="top">
									<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">								 							
									<tr>
										<td align="left">Salutation:</td>
										<td align="left" width="200" colspan="3">
											<select name="selDesSalutation" class="boxenabled" tabindex="19">
												<option value="Mr." <?php if($selDesSalutation == "Mr.") print "selected"?>>Mr.</option>
												<option value="Mrs." <?php if($selDesSalutation == "Mrs.") print "selected"?>>Mrs.</option>
												<option value="Miss." <?php if($selDesSalutation == "Miss.") print "selected"?>>Miss.</option>
												<option value="Dr." <?php if($selDesSalutation == "Dr.") print "selected"?>>Dr.</option>
											</select>
										</td>
									</tr>
									<tr>
										<td align="left" nowrap="nowrap">
											Name:
										</td>
										<td align="left" colspan="3">
											<input type="text" name="txtDesignateName" class="boxenabled" tabindex="20" size="78" value="<?php print $txtDesignateName;?>" />
										</td>
									</tr>
									<tr>
										<td align="left" nowrap="nowrap">Date of birth:</td>
										<td align="left"><input type="text" tabindex="21" name="txtDesDOB" class="boxenabled" size="12" maxlength="30" value="<?php print $txtDesDOB; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fsignupcust1|txtDesDOB', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>(YYYY-MM-DD)
										</td>
										<td align="left">Duplicate:</td>							
										<td align="left">
											<select name="selDesDuplicateID" class="boxenabled" tabindex="22">
												<option value="ID Card" <?php if($selDesDuplicateID == "ID Card") print "selected"?>>ID Card</option>
												<option value="Passport" <?php if($selDesDuplicateID == "Passport") print "selected"?>>Passport</option>
												<option value="Family book" <?php if($selDesDuplicateID == "Miss.") print "selected"?>>Family book</option>
											</select>
											<input type="text" name="txtDesDuplicate" class="boxenabled" tabindex="23" size="11" value="<?php print $txtGuaDuplicate;?>" />
										</td>
									</tr>																																	
									<tr>
										<td align="left">Nationality:</td>
										<td align="left">
											<select name="selDesNationality" class="boxenabled" tabindex="24" onChange="storeNameValue(this.selectedIndex, 9);" style="width:200px">
												<option value="38" selected="selected">Cambodian</option>	
												<?php
													$sql = "SELECT CountryID, Country from tlkpCountry order by Country";
													// sql 2005
													
													$que = $mydb->sql_query($sql);									
													if($que){
														while($rst = $mydb->sql_fetchrow($que)){	
															$CountryID = $rst['CountryID'];
															$Country = $rst['Country'];
															if($selDesNationality == $CountryID) 
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
										<td align="left">
											Title:
										</td>
										<td align="left">
											<select name="selDesOccupation" class="boxenabled" tabindex="25" onChange="storeNameValue(this.selectedIndex, 10);" style="width:200px">										
												<?php
													$sql = "SELECT CareerID, Career from tlkpCareer order by CareerID";
													// sql 2005
													
													$que = $mydb->sql_query($sql);									
													if($que){
														while($rst = $mydb->sql_fetchrow($que)){	
															$CareerID = $rst['CareerID'];
															$Career = $rst['Career'];												
															if($selDesOccupation == $CareerID) 
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
										<td align="left">Telephone:</td>
										<td align="left"><input type="text" name="txtDesPhone" value="<?php print $txtDesPhone; ?>" class="boxenabled" tabindex="26" size="29" onkeyup="ValidatePhone(this);" onblur="CheckPhone(this);" />
										</td>
										<td align="left">Email:</td>
										<td align="left"><input type="text" name="txtDesEmail" value=<?php print $txtDesEmail; ?>"" class="boxenabled" tabindex="27" size="29" /> </td>
									</tr>	
									<tr>
											<td align="left">Address:</td>
											<td align="left" colspan="3">
												<input type="text" name="txtDesAddress" class="boxenabled" tabindex="28" size="79" maxlength="100" value="<?php print $txtDesAddress; ?>" />
											</td>
										</tr>
										<tr>
											<tr>
												<td align="left">Country:</td>
												<td align="left">
													<select name="selDesCountry" class="boxenabled" tabindex="29" style="width:200px" onchange="storeNameValue(this.selectedIndex, 14);">	
													<option value="0" selected="selected">Unknown</option>												
														<?php
															$sql = "SELECT id, name from tlkpLocation where type = 1 order by name";

															$que = $mydb->sql_query($sql);									
															if($que){
																while($rst = $mydb->sql_fetchrow($que)){	
																	$CountryID = $rst['id'];
																	$Country = $rst['name'];
																	if($selDesCountry == $CountryID) 
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
													<select name="selDesCity" class="boxenabled" tabindex="30" style="width:200px" onchange="storeNameValue(this.selectedIndex, 13);">	
														<option value="0">Unknown</option>														
													</select>
												</td>																						
											</tr>
											<td align="left">Khan:</td>
											<td align="left">
												<select name="selDesKhan" class="boxenabled" tabindex="31" style="width:200px" onchange="storeNameValue(this.selectedIndex, 12);">
													<option value="0">Unknown</option>																										
												</select>
											</td>
											<td align="left">Sangkat:</td>
											<td align="left">
												<select name="selDesSangkat" class="boxenabled" tabindex="32" style="width:200px" onchange="storeNameValue(this.selectedIndex, 11);">
													<option value="0">Unknown</option>																										
												</select>
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
						<input type="button" tabindex="34" name="btnNext" value="Next >>" class="button" onClick="ValidateForm();" />						
					</td>
				 </tr>
				</table>
					<input type="hidden" name="pg" id="pg" value="3">
					<input type="hidden" name="selCusNationality" value="38" />
					<input type="hidden" name="txtCusNationality" value="Cambodia" />
					<input type="hidden" name="txtCusOccupation" value="" />
					<input type="hidden" name="txtCusSangkat" value="" />
					<input type="hidden" name="txtCusKhan" value="" />
					<input type="hidden" name="txtCusCity" value="" />
					<input type="hidden" name="txtCusCountry" value="" />					
					<input type="hidden" name="txtDesNationality" value="Cambodian" />
					<input type="hidden" name="txtDesOccupation" value="" />
					<input type="hidden" name="txtDesSangkat" value="" />
					<input type="hidden" name="txtDesKhan" value="" />
					<input type="hidden" name="txtDesCity" value="" />
					<input type="hidden" name="txtDesCountry" value="" />
					<input type="hidden" name="selCusDuplicateID" value="" />
					<input type="hidden" name="txtCusDuplicate" value="" />		
					
					<input type="hidden" name="selCusOccupation" value="1" />											
													
					<input type="hidden" name="radCustType" value="2" />
					<input type="hidden" name="txtBusinessType" value="" />
			</form>
		</td>
	</tr>
</table>
								