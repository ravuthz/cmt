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
		if(cat == 1){
			fsignupcust2.txtConSangkat.value = fsignupcust2.selConSangkat.options[index].text;
		}	
		else if(cat == 2){			
			fsignupcust2.txtConKhan.value = fsignupcust2.selConKhan.options[index].text;
			location(4, fsignupcust2.selConKhan.options[index].value, "selConSangkat");
		}
		else if(cat == 3){
			fsignupcust2.txtConCity.value = fsignupcust2.selConCity.options[index].text;						
			location(3, fsignupcust2.selConCity.options[index].value, "selConKhan");
		}
		else if(cat == 4){
			fsignupcust2.txtConCountry.value = fsignupcust2.selConCountry.options[index].text;			
			location(2, fsignupcust2.selConCountry.options[index].value, "selConCity");
		}		
		else if(cat == 11){
			fsignupcust2.txtGuaSangkat.value = fsignupcust2.selGuaSangkat.options[index].text;
		}
		else if(cat == 12){
			fsignupcust2.txtGuaKhan.value = fsignupcust2.selGuaKhan.options[index].text;
			location(4, fsignupcust2.selGuaKhan.options[index].value, "selGuaSangkat");
		}
		else if(cat == 13){
			fsignupcust2.txtGuaCity.value = fsignupcust2.selGuaCity.options[index].text;						
			location(3, fsignupcust2.selGuaCity.options[index].value, "selGuaKhan");
		}			
		else if(cat == 14){
			fsignupcust2.txtGuaCountry.value = fsignupcust2.selGuaCountry.options[index].text;			
			location(2, fsignupcust2.selGuaCountry.options[index].value, "selGuaCity");
		}			
			
	}
	
	function setTheSame(index){
		CustomerName = fsignupcust2.txtDesignateName;
		selCustSalutation = fsignupcust2.selDesSalutation;
		txtCustDOB = fsignupcust2.txtDesDOB;
		txtCustPhone = fsignupcust2.txtDesPhone;
		txtCustEmail = fsignupcust2.txtDesEmail;
		selCustDuplicateID = fsignupcust2.selDesDuplicateID;
		CustDuplicate = fsignupcust2.txtDesDuplicate;
		selCustNationality = fsignupcust2.selDesNationality;
		selCustOccupation = fsignupcust2.selDesOccupation;
		txtCustAddress = fsignupcust2.txtDesAddress;
		selCusSangkat = fsignupcust2.selDesSangkat;
		selCusKhan = fsignupcust2.selDesKhan;
		selCusCity = fsignupcust2.selDesCity;
		selCusCountry = fsignupcust2.selDesCountry;
		
		txtCusSangkat = fsignupcust2.txtDesSangkat;
		txtCusKhan = fsignupcust2.txtDesKhan;
		txtCusCity = fsignupcust2.txtDesCity;
		txtCusCountry = fsignupcust2.txtDesCountry;
		txtCusNationality = fsignupcust2.txtDesNationality;
		txtCusOccupation = fsignupcust2.txtDesOccupation;				
		
		if(index == 1){
			if(fsignupcust2.thesame1.checked == true){
				if(fsignupcust2.radCustType.value == 1){
					fsignupcust2.selConSalutation[0].value = selCustSalutation.value;
					fsignupcust2.selConSalutation[0].text = selCustSalutation.value;
					fsignupcust2.selConSalutation[0].selected = "selected";
				}
				fsignupcust2.txtContactName.value = CustomerName.value;
				fsignupcust2.txtConDOB.value = txtCustDOB.value;
				
				fsignupcust2.selConDuplicateID[0].value = selCustDuplicateID.value;
				fsignupcust2.selConDuplicateID[0].text = selCustDuplicateID.value;
				fsignupcust2.selConDuplicateID[0].selected = "selected";
				
				fsignupcust2.txtConDuplicate.value = CustDuplicate.value;
				fsignupcust2.txtConPhone.value = txtCustPhone.value;
				fsignupcust2.txtConEmail.value = txtCustEmail.value;
				
				fsignupcust2.selConNationality[0].value = selCustNationality.value;
				fsignupcust2.selConNationality[0].text = txtCusNationality.value;
				fsignupcust2.selConNationality[0].selected = "selected";
//				
				fsignupcust2.selConOccupation[0].value = selCustOccupation.value;
				fsignupcust2.selConOccupation[0].text = txtCusOccupation.value;
				fsignupcust2.selConOccupation[0].selected = "selected";
				
				fsignupcust2.txtConAddress.value = txtCustAddress.value;
				
				fsignupcust2.selConSangkat[0].value = selCusSangkat.value;
				fsignupcust2.selConSangkat[0].text = txtCusSangkat.value;
				fsignupcust2.selConSangkat[0].selected = "selected";
				
				fsignupcust2.selConKhan[0].value = selCusKhan.value;
				fsignupcust2.selConKhan[0].text = txtCusKhan.value;
				fsignupcust2.selConKhan[0].selected = "selected";
				
				fsignupcust2.selConCity[0].value = selCusCity.value;
				fsignupcust2.selConCity[0].text = txtCusCity.value;
				fsignupcust2.selConCity[0].selected = "selected";
				
				fsignupcust2.selConCountry[0].value = selCusCountry.value;
				fsignupcust2.selConCountry[0].text = txtCusCountry.value;
				fsignupcust2.selConCountry[0].selected = "selected";
				
			}else{
				if(fsignupcust2.radCustType.value == 1){
					fsignupcust2.selConSalutation[0].value = "Mr.";
					fsignupcust2.selConSalutation[0].text = "Mr.";
					fsignupcust2.selConSalutation[0].selected = "selected";
				}	
					fsignupcust2.txtContactName.value = "";
					fsignupcust2.txtConDOB.value = "";
					
					fsignupcust2.selConDuplicateID[0].value = "ID Card";
					fsignupcust2.selConDuplicateID[0].text = "ID Card";
					fsignupcust2.selConDuplicateID[0].selected = "selected";
					
					fsignupcust2.txtConDuplicate.value = "";
					fsignupcust2.txtConPhone.value = "";
					fsignupcust2.txtConEmail.value = "";
					
					fsignupcust2.selConNationality[0].value = 38;
					fsignupcust2.selConNationality[0].text = "Cambodian";
					fsignupcust2.selConNationality[0].selected = "selected";
					
					fsignupcust2.selConOccupation[0].value = 1;
					fsignupcust2.selConOccupation[0].text = "unknown";
					fsignupcust2.selConOccupation[0].selected = "selected";
					
					fsignupcust2.txtConAddress.value = "";
					
					fsignupcust2.selConSangkat[0].value = 0;
					fsignupcust2.selConSangkat[0].text = "Unknown";
					fsignupcust2.selConSangkat[0].selected = "selected";
					
					fsignupcust2.selConKhan[0].value = 0;
					fsignupcust2.selConKhan[0].text = "Unknown";
					fsignupcust2.selConKhan[0].selected = "selected";
					
					fsignupcust2.selConCity[0].value = 0;
					fsignupcust2.selConCity[0].text = "Unknown";
					fsignupcust2.selConCity[0].selected = "selected";
					
					fsignupcust2.selConCountry[0].value = 0;
					fsignupcust2.selConCountry[0].text = "Unknown";
					fsignupcust2.selConCountry[0].selected = "selected";
				
					
			}
		}else{
			if(index == 2){
				if(fsignupcust2.thesame2.checked == true){
					if(fsignupcust2.radCustType.value == 1){
						fsignupcust2.selGuaSalutation[0].value = selCustSalutation.value;
						fsignupcust2.selGuaSalutation[0].text = selCustSalutation.value;
						fsignupcust2.selGuaSalutation[0].selected = "selected";
					}
					fsignupcust2.txtGarrentorName.value = CustomerName.value;
					fsignupcust2.txtGuaDOB.value = txtCustDOB.value;
					
					fsignupcust2.selGuaDuplicateID[0].value = selCustDuplicateID.value;
					fsignupcust2.selGuaDuplicateID[0].text = selCustDuplicateID.value;
					fsignupcust2.selGuaDuplicateID[0].selected = "selected";
					
					fsignupcust2.txtGuaDuplicate.value = CustDuplicate.value;
					fsignupcust2.txtGuaPhone.value = txtCustPhone.value;
					fsignupcust2.txtGuaEmail.value = txtCustEmail.value;
					
					//fsignupcust2.selGuaNationality[0].value = selCustNationality.value;
//					fsignupcust2.selGuaNationality[0].text = txtCusNationality.value;
//					fsignupcust2.selGuaNationality[0].selected = "selected";
					
					fsignupcust2.selGuaOccupation[0].value = selCustOccupation.value;
					fsignupcust2.selGuaOccupation[0].text = txtCusOccupation.value;
					fsignupcust2.selGuaOccupation[0].selected = "selected";
					
					fsignupcust2.txtGuaAddress.value = txtCustAddress.value;
					
					fsignupcust2.selGuaSangkat[0].value = selCusSangkat.value;
					fsignupcust2.selGuaSangkat[0].text = txtCusSangkat.value;
					fsignupcust2.selGuaSangkat[0].selected = "selected";
					
					fsignupcust2.selGuaKhan[0].value = selCusKhan.value;
					fsignupcust2.selGuaKhan[0].text = txtCusKhan.value;
					fsignupcust2.selGuaKhan[0].selected = "selected";
					
					fsignupcust2.selGuaCity[0].value = selCusCity.value;
					fsignupcust2.selGuaCity[0].text = txtCusCity.value;
					fsignupcust2.selGuaCity[0].selected = "selected";
					
					fsignupcust2.selGuaCountry[0].value = selCusCountry.value;
					fsignupcust2.selGuaCountry[0].text = txtCusCountry.value;
					fsignupcust2.selGuaCountry[0].selected = "selected";
					
				}else{
					if(fsignupcust2.radCustType.value == 1){
						fsignupcust2.selGuaSalutation[0].value = "Mr.";
						fsignupcust2.selGuaSalutation[0].text = "Mr.";
						fsignupcust2.selGuaSalutation[0].selected = "selected";
					}
					fsignupcust2.txtGarrentorName.value = "";
					fsignupcust2.txtGuaDOB.value = "";
					
					fsignupcust2.selGuaDuplicateID[0].value = "ID Card";
					fsignupcust2.selGuaDuplicateID[0].text = "ID Card";
					fsignupcust2.selGuaDuplicateID[0].selected = "selected";
					
					fsignupcust2.txtGuaDuplicate.value = "";
					fsignupcust2.txtGuaPhone.value = "";
					fsignupcust2.txtGuaEmail.value = "";
					
					fsignupcust2.selGuaNationality[0].value = 38;
					fsignupcust2.selGuaNationality[0].text = "Cambodian";
					fsignupcust2.selGuaNationality[0].selected = "selected";
					
					fsignupcust2.selGuaOccupation[0].value = 1;
					fsignupcust2.selGuaOccupation[0].text = "Unknown";
					fsignupcust2.selGuaOccupation[0].selected = "selected";
					
					fsignupcust2.txtGuaAddress.value = "";
					
					fsignupcust2.selGuaSangkat[0].value = 0;
					fsignupcust2.selGuaSangkat[0].text = "Unknown";
					fsignupcust2.selGuaSangkat[0].selected = "selected";
					
					fsignupcust2.selGuaKhan[0].value = 0;
					fsignupcust2.selGuaKhan[0].text = "Unknown";
					fsignupcust2.selGuaKhan[0].selected = "selected";
					
					fsignupcust2.selGuaCity[0].value = 0;
					fsignupcust2.selGuaCity[0].text = "Unknown";
					fsignupcust2.selGuaCity[0].selected = "selected";
					
					fsignupcust2.selGuaCountry[0].value = 0;
					fsignupcust2.selGuaCountry[0].text = "Unknown";
					fsignupcust2.selGuaCountry[0].selected = "selected";
				}
			}
		}
	}
	
	function ValidateForm(){
		
			fsignupcust2.txtGuaNationality.value = fsignupcust2.selGuaNationality.options[fsignupcust2.selGuaNationality.selectedIndex].text;
			fsignupcust2.txtGuaOccupation.value = fsignupcust2.selGuaOccupation.options[fsignupcust2.selGuaOccupation.selectedIndex].text;
			fsignupcust2.txtGuaSangkat.value = fsignupcust2.selGuaSangkat.options[fsignupcust2.selGuaSangkat.selectedIndex].text;
			fsignupcust2.txtGuaKhan.value = fsignupcust2.selGuaKhan.options[fsignupcust2.selGuaKhan.selectedIndex].text;
			fsignupcust2.txtGuaCity.value = fsignupcust2.selGuaCity.options[fsignupcust2.selGuaCity.selectedIndex].text;
			fsignupcust2.txtGuaCountry.value = fsignupcust2.selGuaCountry.options[fsignupcust2.selGuaCountry.selectedIndex].text;
			
			fsignupcust2.txtConNationality.value = fsignupcust2.selConNationality.options[fsignupcust2.selConNationality.selectedIndex].text;
			fsignupcust2.txtConOccupation.value = fsignupcust2.selConOccupation.options[fsignupcust2.selConOccupation.selectedIndex].text;
			fsignupcust2.txtConSangkat.value = fsignupcust2.selConSangkat.options[fsignupcust2.selConSangkat.selectedIndex].text;
			fsignupcust2.txtConKhan.value = fsignupcust2.selConKhan.options[fsignupcust2.selConKhan.selectedIndex].text;
			fsignupcust2.txtConCity.value = fsignupcust2.selConCity.options[fsignupcust2.selConCity.selectedIndex].text;
			fsignupcust2.txtConCountry.value = fsignupcust2.selConCountry.options[fsignupcust2.selConCountry.selectedIndex].text;
			fsignupcust2.btnNext.disabled = true;
			fsignupcust2.submit();
		
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
						 	<td style="border-top:1px solid" align="left"  bgcolor="#ffffff">
								<b>Contact information</b>
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
			<form name="fsignupcust2" method="post" action="./">
			 	<table border="0" cellpadding="0" cellspacing="0" align="left" width="100%">					
					<!--================================= End custoemr =========================== !-->
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle" height="18"><b>GUARANTOR PROFILE</b>  <font color="#000000">[<input type="checkbox" name="thesame2" onClick="setTheSame(2);" tabindex="1">The same as customer profile ]</font></td>
							</tr>
							<tr>
								<td valign="top">
									<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">								 							
									<tr>
										<td align="left">Salutation:</td>
										<td align="left" width="200" colspan="3">
											<select name="selGuaSalutation" class="boxenabled" tabindex="2">
												<option value="Mr." <?php if($selGuaSalutation == "Mr.") print "selected"?>>Mr.</option>
												<option value="Mrs." <?php if($selGuaSalutation == "Mrs.") print "selected"?>>Mrs.</option>
												<option value="Miss." <?php if($selGuaSalutation == "Miss.") print "selected"?>>Miss.</option>
												<option value="Dr." <?php if($selGuaSalutation == "Dr.") print "selected"?>>Dr.</option>
											</select>
										</td>
									</tr>
									<tr>
										<td align="left" nowrap="nowrap">
											Name:
										</td>
										<td align="left" colspan="3">
											<input type="text" name="txtGarrentorName" class="boxenabled" tabindex="3" size="78" value="<?php print $txtConignateName;?>" />
										</td>
									</tr>
									<tr>
										<td align="left" nowrap="nowrap">Date of birth:</td>
										<td align="left"><input type="text" tabindex="4" name="txtGuaDOB" class="boxenabled" size="12" maxlength="30" value="<?php print $txtGuaDOB; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fsignupcust2|txtGuaDOB', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>(YYYY-MM-DD)
										</td>
										<td align="left">Duplicate:</td>							
										<td align="left">
											<select name="selGuaDuplicateID" class="boxenabled" tabindex="5">
												<option value="ID Card" <?php if($selGuaDuplicateID == "ID Card") print "selected"?>>ID Card</option>
												<option value="Passport" <?php if($selGuaDuplicateID == "Passport") print "selected"?>>Passport</option>
												<option value="Family book" <?php if($selGuaDuplicateID == "Miss.") print "selected"?>>Family book</option>
											</select>
											<input type="text" name="txtGuaDuplicate" class="boxenabled" tabindex="31" size="11" value="<?php print $txtGuaDuplicate;?>" />
										</td>
									</tr>																																	
									<tr>
										<td align="left">Nationality:</td>
										<td align="left">
											<select name="selGuaNationality" class="boxenabled" tabindex="6" onChange="storeNameValue(this.selectedIndex, 9);" style="width:200px">
												<option value="38" selected="selected">Cambodian</option>	
												<?php
													//$sql = "SELECT CountryID, Country from tlkpCountry order by Country";
//													// sql 2005
//													
//													$que = $mydb->sql_query($sql);									
//													if($que){
//														while($rst = $mydb->sql_fetchrow($que)){	
//															$CountryID = $rst['CountryID'];
//															$Country = $rst['Country'];
//															if($selGuaNationality == $CountryID) 
//																$sel = "selected";
//															else
//																$sel = "";
//															print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
//														}
//													}
//													$mydb->sql_freeresult();
												?>
											</select>
										</td>
										<td align="left">
											Occupation:
										</td>
										<td align="left">
											<select name="selGuaOccupation" class="boxenabled" tabindex="7" onChange="storeNameValue(this.selectedIndex, 10);" style="width:200px">										
												<?php
													$sql = "SELECT CareerID, Career from tlkpCareer order by CareerID";
													// sql 2005
													
													$que = $mydb->sql_query($sql);									
													if($que){
														while($rst = $mydb->sql_fetchrow($que)){	
															$CareerID = $rst['CareerID'];
															$Career = $rst['Career'];												
															if($selGuaOccupation == $CareerID) 
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
										<td align="left"><input type="text" name="txtGuaPhone" value="" class="boxenabled" tabindex="8" size="29" onkeyup="ValidatePhone(this);" onblur="CheckPhone(this);" />
										</td>
										<td align="left">Email:</td>
										<td align="left"><input type="text" name="txtGuaEmail" value="" class="boxenabled" tabindex="9" size="28" /> </td>
									</tr>
									<tr>
											<td align="left">Address:</td>
											<td align="left" colspan="3">
												<input type="text" name="txtGuaAddress" class="boxenabled" tabindex="10" size="79" maxlength="100" value="<?php print $txtGuaAddress; ?>" />
											</td>
										</tr>										
										<tr>
											<td align="left">Country:</td>
											<td align="left">
												<select name="selGuaCountry" class="boxenabled" tabindex="11" style="width:200px" onchange="storeNameValue(this.selectedIndex, 14);">	
												<option value="0" selected="selected">Unknown</option>												
													<?php
														$sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
														// sql 2005
														
														$que = $mydb->sql_query($sql);									
														if($que){
															while($rst = $mydb->sql_fetchrow($que)){	
																$CountryID = $rst['id'];
																$Country = $rst['name'];
																if($selGuaCountry == $CountryID) 
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
												<select name="selGuaCity" class="boxenabled" tabindex="12" style="width:200px" onchange="storeNameValue(this.selectedIndex, 13);">	
													<option value="0">Unknown</option>													
												</select>
											</td>										
											
										</tr>
										<tr>
											<td align="left">Khan:</td>
											<td align="left">
												<select name="selGuaKhan" class="boxenabled" tabindex="13" style="width:200px" onchange="storeNameValue(this.selectedIndex, 12);">
													<option value="0">Unknown</option>																										
												</select>
											</td>
											<td align="left">Sangkat:</td>
											<td align="left">
												<select name="selGuaSangkat" class="boxenabled" tabindex="14" style="width:200px" onchange="storeNameValue(this.selectedIndex, 11);">
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
						<td>
							<!-- ================================ DESIGNATE PROFILE ================================-->
							<!-- ==================================================================================-->
							<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle" height="18"><b>CONTACT PROFILE</b>  <font color="#000000">[<input type="checkbox" name="thesame1" onClick="setTheSame(1);" tabindex="15">The same as customer profile ]</font></td>
							</tr>
							<tr>
								<td valign="top">
									<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">								 							
										<tr>
											<td align="left">Salutation:</td>
											<td align="left" width="200" colspan="3">
												<select name="selConSalutation" class="boxenabled" tabindex="16">
													<option value="Mr." <?php if($selConSalutation == "Mr.") print "selected"?>>Mr.</option>
													<option value="Mrs." <?php if($selConSalutation == "Mrs.") print "selected"?>>Mrs.</option>
													<option value="Miss." <?php if($selConSalutation == "Miss.") print "selected"?>>Miss.</option>
													<option value="Dr." <?php if($selConSalutation == "Dr.") print "selected"?>>Dr.</option>
												</select>
											</td>
										</tr>
										<tr>
											<td align="left" nowrap="nowrap">
												Name:
											</td>
											<td align="left" colspan="3">
												<input type="text" name="txtContactName" class="boxenabled" tabindex="17" size="78" value="<?php print $txtContactName;?>" />
											</td>
										</tr>
										<tr>
											<td align="left" nowrap="nowrap">Date of birth:</td>
											<td align="left"><input type="text" tabindex="18" name="txtConDOB" class="boxenabled" size="15" maxlength="30" value="<?php print $txtConDOB; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
												<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fsignupcust2|txtConDOB', '', 'width=200,height=220,top=250,left=350');">
													<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
												</button>(YYYY-MM-DD)
											</td>
											<td align="left">Duplicate:</td>							
											<td align="left">
												<select name="selConDuplicateID" class="boxenabled" tabindex="19">
													<option value="ID Card" <?php if($selConDuplicateID == "ID Card") print "selected"?>>ID Card</option>
													<option value="Passport" <?php if($selConDuplicateID == "Passport") print "selected"?>>Passport</option>
													<option value="Family book" <?php if($selConDuplicateID == "Family book") print "selected"?>>Family book</option>
												</select>
												<input type="text" name="txtConDuplicate" class="boxenabled" tabindex="20" size="11" value="<?php print $txtConDuplicate;?>" />
											</td>
										</tr>	
										<tr>
											<td align="left">Telephone:</td>
											<td align="left"><input type="text" name="txtConPhone" value="<?php print $txtConPhone; ?>" class="boxenabled" tabindex="21" size="29" onkeyup="ValidatePhone(this);" onblur="CheckPhone(this);" />
											</td>
											<td align="left">Email:</td>
											<td align="left"><input type="text" name="txtConEmail" value=<?php print $txtConEmail; ?>"" class="boxenabled" tabindex="22" size="28" /> </td>
										</tr>																																
										<tr>
											<td align="left">Nationality:</td>
											<td align="left">
												<select name="selConNationality" class="boxenabled" tabindex="23" onChange="storeNameValue(this.selectedIndex, 3);" style="width:200px">
													<option value="38" selected="selected">Cambodian</option>	
													<?php
														$sql = "SELECT CountryID, Country from tlkpCountry order by Country";
														// sql 2005
														
														$que = $mydb->sql_query($sql);									
														if($que){
															while($rst = $mydb->sql_fetchrow($que)){	
																$CountryID = $rst['CountryID'];
																$Country = $rst['Country'];
																if($selConNationality == $CountryID) 
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
												Occupation:
											</td>
											<td align="left">
												<select name="selConOccupation" class="boxenabled" tabindex="24" onChange="storeNameValue(this.selectedIndex, 4);" style="width:200px">										
													<?php
														$sql = "SELECT CareerID, Career from tlkpCareer order by CareerID";
														// sql 2005
														
														$que = $mydb->sql_query($sql);									
														if($que){
															while($rst = $mydb->sql_fetchrow($que)){	
																$CareerID = $rst['CareerID'];
																$Career = $rst['Career'];												
																if($selConOccupation == $CareerID) 
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
												<td align="left">Address:</td>
												<td align="left" colspan="3">
													<input type="text" name="txtConAddress" class="boxenabled" tabindex="25" size="79" maxlength="100" value="<?php print $txtConAddress; ?>" />
												</td>
											</tr>
											<tr>
												<td align="left">Country:</td>
												<td align="left">
													<div id="divConCountry">
													<select name="selConCountry" class="boxenabled" tabindex="26" style="width:200px" onchange="storeNameValue(this.selectedIndex, 4);">	
													<option value="0" selected="selected">Unknown</option>												
														<?php
															$sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
															// sql 2005
															
															$que = $mydb->sql_query($sql);									
															if($que){
																while($rst = $mydb->sql_fetchrow($que)){	
																	$CountryID = $rst['id'];
																	$Country = $rst['name'];
																	if($selConCountry == $CountryID) 
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
	
													<select name="selConCity" id="selConCity" class="boxenabled" tabindex="27" style="width:200px" onchange="storeNameValue(this.selectedIndex, 3);">	
														
														<option value="0" selected="selected">Unknown</option>														
													</select>
													
												</td>																					
											</tr>
											<tr>																				
												<td align="left">Khan:</td>
												<td align="left">
													<select name="selConKhan" class="boxenabled" tabindex="28" style="width:200px" onchange="storeNameValue(this.selectedIndex, 2);">
														<option value="0">Unknown</option>																											
													</select>
												</td>
												<td align="left">Sangkat:</td>
												<td align="left">
													<select name="selConSangkat" class="boxenabled" tabindex="29" style="width:200px" onchange="storeNameValue(this.selectedIndex, 1);">
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

						<input type="button" tabindex="31" name="btnNext" value="Next >>" class="button" onClick="ValidateForm();" />						
					</td>
				 </tr>
				</table>
					<input type="hidden" name="pg" id="pg" value="4">
					
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
					
					<input type="hidden" name="txtCusNationality" value="<?php print $txtCusNationality; ?>" />
					<input type="hidden" name="txtCusOccupation" value="<?php print $txtCusOccupation; ?>" />
					<input type="hidden" name="txtCusSangkat" value="<?php print $txtCusSangkat; ?>" />
					<input type="hidden" name="txtCusKhan" value="<?php print $txtCusKhan; ?>" />
					<input type="hidden" name="txtCusCity" value="<?php print $txtCusCity; ?>" />
					<input type="hidden" name="txtCusCountry" value="<?php print $txtCusCountry; ?>" />
					
					<input type="hidden" name="txtDesNationality" value="<?php print $txtDesNationality; ?>" />
					<input type="hidden" name="txtDesOccupation" value="<?php print $txtDesOccupation; ?>" />
					<input type="hidden" name="txtDesDuplicate" value="<?php print $txtDesDuplicate; ?>" />					
					<input type="hidden" name="txtDesSangkat" value="<?php print $txtDesSangkat; ?>" />
					<input type="hidden" name="txtDesKhan" value="<?php print $txtDesKhan; ?>" />
					<input type="hidden" name="txtDesCity" value="<?php print $txtDesCity; ?>" />
					<input type="hidden" name="txtDesCountry" value="<?php print $txtDesCountry; ?>" />
					
					<input type="hidden" name="txtGuaNationality" value="<?php print $txtGuaNationality; ?>" />
					<input type="hidden" name="txtGuaOccupation" value="<?php print $txtGuaOccupation; ?>" />
					<input type="hidden" name="txtGuaSangkat" value="<?php print $txtGuaSangkat; ?>" />
					<input type="hidden" name="txtGuaKhan" value="<?php print $txtGuaKhan; ?>" />
					<input type="hidden" name="txtGuaCity" value="<?php print $txtGuaCity; ?>" />
					<input type="hidden" name="txtGuaCountry" value="<?php print $txtGuaCountry; ?>" />
					
					<input type="hidden" name="txtConNationality" value="<?php print $txtConNationality; ?>" />
					<input type="hidden" name="txtConOccupation" value="<?php print $txtConOccupation; ?>" />
					<input type="hidden" name="txtConSangkat" value="<?php print $txtConSangkat; ?>" />
					<input type="hidden" name="txtConKhan" value="<?php print $txtConKhan; ?>" />
					<input type="hidden" name="txtConCity" value="<?php print $txtConCity; ?>" />
					<input type="hidden" name="txtConCountry" value="<?php print $txtConCountry; ?>" />
					
					<input type="hidden" name="radCustType" value="<?php print $radCustType; ?>" />
					<input type="hidden" name="selBusinessType" value="<?php print $selBusinessType; ?>" />
					<input type="hidden" name="txtBusinessType" value="<?php print $txtBusinessType; ?>" />
			</form>
		</td>
	</tr>
</table>
								