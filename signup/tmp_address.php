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

	//
	//	store name of value
	//
	function storeNameValue(index, cat){		
		if(cat == 1){
			fsignupcust2.txtIntSangkat.value = fsignupcust2.selIntSangkat.options[index].text;
		}
		else if(cat == 2){
			fsignupcust2.txtIntKhan.value = fsignupcust2.selIntKhan.options[index].text;
			location(4, fsignupcust2.selIntKhan.options[index].value, "selIntSangkat");
		}
		else if(cat == 3){
			fsignupcust2.txtIntCity.value = fsignupcust2.selIntCity.options[index].text;						
			location(3, fsignupcust2.selIntCity.options[index].value, "selIntKhan");
		}			
		else if(cat == 4){
			fsignupcust2.txtIntCountry.value = fsignupcust2.selIntCountry.options[index].text;			
			location(2, fsignupcust2.selIntCountry.options[index].value, "selIntCity");
		}
		if(cat == 5){
			fsignupcust2.txtBilSangkat.value = fsignupcust2.selBilSangkat.options[index].text;
		}
		else if(cat == 6){
			fsignupcust2.txtBilKhan.value = fsignupcust2.selBilKhan.options[index].text;
			location(4, fsignupcust2.selBilKhan.options[index].value, "selBilSangkat");
		}
		else if(cat == 7){
			fsignupcust2.txtBilCity.value = fsignupcust2.selBilCity.options[index].text;						
			location(3, fsignupcust2.selBilCity.options[index].value, "selBilKhan");
		}			
		else if(cat == 8){
			fsignupcust2.txtBilCountry.value = fsignupcust2.selBilCountry.options[index].text;			
			location(2, fsignupcust2.selBilCountry.options[index].value, "selBilCity");
		}
		else if(cat == 9){
			fsignupcust2.txtBilInvoiceType.value = fsignupcust2.selBilInvoiceType.options[index].text;			
		}
		else if(cat == 10){
			fsignupcust2.txtBilMessenger.value = fsignupcust2.SelMessengerID.options[index].text;			
		}										
	}
	
	//
	//	Set the same
	//
	function setTheSame(){
		if(fsignupcust2.thesame.checked == true){
			fsignupcust2.txtBilAddress.value = fsignupcust2.txtIntAddress.value; 
			
			fsignupcust2.selBilCountry.text = fsignupcust2.selIntCountry.options[fsignupcust2.selIntCountry.selectedIndex].text;
			fsignupcust2.selBilCountry.value = fsignupcust2.selIntCountry.options[fsignupcust2.selIntCountry.selectedIndex].value;
			fsignupcust2.txtBilCountry.value = fsignupcust2.selIntCountry.options[fsignupcust2.selIntCountry.selectedIndex].text;
			
			fsignupcust2.selBilCity[0].text = fsignupcust2.selIntCity.options[fsignupcust2.selIntCity.selectedIndex].text;
			fsignupcust2.selBilCity[0].value = fsignupcust2.selIntCity.options[fsignupcust2.selIntCity.selectedIndex].value;			
			fsignupcust2.selBilCity[0].selected = true;
			fsignupcust2.txtBilCity.value = fsignupcust2.selIntCity.options[fsignupcust2.selIntCity.selectedIndex].text;
			
			fsignupcust2.selBilKhan[0].text = fsignupcust2.selIntKhan.options[fsignupcust2.selIntKhan.selectedIndex].text;
			fsignupcust2.selBilKhan[0].value = fsignupcust2.selIntKhan.options[fsignupcust2.selIntKhan.selectedIndex].value;			
			fsignupcust2.selBilKhan[0].selected = true;
			fsignupcust2.txtBilKhan.value = fsignupcust2.selIntKhan.options[fsignupcust2.selIntKhan.selectedIndex].text;
			
			fsignupcust2.selBilSangkat[0].text = fsignupcust2.selIntSangkat.options[fsignupcust2.selIntSangkat.selectedIndex].text;
			fsignupcust2.selBilSangkat[0].value = fsignupcust2.selIntSangkat.options[fsignupcust2.selIntSangkat.selectedIndex].value;			
			fsignupcust2.selBilSangkat[0].selected = true;
			fsignupcust2.txtBilSangkat.value = fsignupcust2.selIntSangkat.options[fsignupcust2.selIntSangkat.selectedIndex].text;
		}
	}
	
	//
	//	Validate form
	//
	function ValidateForm(){
		if(Trim(fsignupcust2.txtBilAddress.value) == ""){
			alert("Please enter billing address");
			fsignupcust2.txtBilAddress.focus();
			return;
		}else if(fsignupcust2.selBilCountry.options[fsignupcust2.selBilCountry.selectedIndex].text == "Unknown"){
			alert("Pleas select billing country");
			fsignupcust2.selBilCountry.focus();
			return;
		}else if(fsignupcust2.selBilCity.options[fsignupcust2.selBilCity.selectedIndex].text == "Unknown"){
			alert("Pleas select billing city");
			fsignupcust2.selBilCity.focus();
			return;
		}else if(fsignupcust2.selBilKhan.options[fsignupcust2.selBilKhan.selectedIndex].text == "Unknown"){
			alert("Pleas select billing khan");
			fsignupcust2.selBilKhan.focus();
			return;
		}else if(fsignupcust2.selBilSangkat.options[fsignupcust2.selBilSangkat.selectedIndex].text == "Unknown"){
			alert("Pleas select billing sangkat");
			fsignupcust2.selBilSangkat.focus();
			return;
		}else if(fsignupcust2.SelMessengerID.selectedIndex < 1){
			alert("Pleas select messenger");
				fsignupcust2.SelMessengerID.focus();
				return;
		}else if(fsignupcust2.selBilInvoiceType.selectedIndex == 1){
			if(Trim(fsignupcust2.txtBilEmail.value) == ""){
				alert("Pleas enter billing email addresss");
				fsignupcust2.txtBilEmail.focus();
				return;
			}
		}if(Trim(fsignupcust2.txtBilEmail.value) != ""){
			if(!isValidMail(Trim(fsignupcust2.txtBilEmail.value))){
				alert("Invalid billing email address");
				fsignupcust2.txtBilEmail.focus();
				return;
			}
		}		
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
						 	<td style="border-top:1px solid" align="left" bgcolor="#ffffff">
								<b>Address information</b>
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
								Information summary
						 	</td>		 
						</tr>		
					 </table>
			</fieldset>		
		</td>
		<td valign="top" width="600" align="left"> 
			<form name="fsignupcust2" method="post" action="./">
			 <table border="0" cellpadding="0" cellspacing="0" align="left" width="100%">
				<tr>
					<td>
						<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle" height="18"><b>INSTALLATION ADDRESS</b></td>
							</tr>
							<tr>
								<td valign="top">
									<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">								 																
									<tr>
											<td align="left">Address:</td>
											<td align="left" colspan="3">
												<input type="text" name="txtIntAddress" class="boxenabled" tabindex="1" size="79" maxlength="100" value="<?php print $txtIntAddress; ?>" />
											</td>
										</tr>										
										<tr>
											<td align="left">Country:</td>
											<td align="left">
												<select name="selIntCountry" class="boxenabled" tabindex="2" style="width:200px" onChange="storeNameValue(this.selectedIndex, 4);">	
												<option value="0" selected="selected">Unknown</option>												
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
												<select name="selIntCity" class="boxenabled" tabindex="3" style="width:200px" onChange="storeNameValue(this.selectedIndex, 3);">	
													<option value="0">Unknown</option>													
												</select>
											</td>										
											
										</tr>
										<tr>
											<td align="left">Khan:</td>
											<td align="left">
												<select name="selIntKhan" class="boxenabled" tabindex="4" style="width:200px" onChange="storeNameValue(this.selectedIndex, 2);">
													<option value="0">Unknown</option>																										
												</select>
											</td>
											<td align="left">Sangkat:</td>
											<td align="left">
												<select name="selIntSangkat" class="boxenabled" tabindex="5" style="width:200px" onChange="storeNameValue(this.selectedIndex, 1);">
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
						<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle" height="18"><b>BILLING ADDRESS</b> <font color="#000000">[<input type="checkbox" name="thesame" onClick="setTheSame();" tabindex="6">The same as installation address ]</font></td>
							</tr>
							<tr>
								<td valign="top">
									<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">								 																
									<tr>
											<td align="left">Address:</td>
											<td align="left" colspan="3">
												<input type="text" name="txtBilAddress" class="boxenabled" tabindex="7" size="79" maxlength="100" value="<?php print $txtBilAddress; ?>" />
											</td>
										</tr>										
										<tr>
											<td align="left">Country:</td>
											<td align="left">
												<select name="selBilCountry" class="boxenabled" tabindex="8" style="width:200px" onChange="storeNameValue(this.selectedIndex, 8);">	
												<option value="0" selected="selected">Unknown</option>												
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
												<select name="selBilCity" class="boxenabled" tabindex="9" style="width:200px" onChange="storeNameValue(this.selectedIndex, 7);">	
													<option value="0">Unknown</option>													
												</select>
											</td>										
											
										</tr>
										<tr>
											<td align="left">Khan:</td>
											<td align="left">
												<select name="selBilKhan" class="boxenabled" tabindex="10" style="width:200px" onChange="storeNameValue(this.selectedIndex, 6);">
													<option value="0">Unknown</option>																										
												</select>
											</td>
											<td align="left">Sangkat:</td>
											<td align="left">
												<select name="selBilSangkat" class="boxenabled" tabindex="11" style="width:200px" onChange="storeNameValue(this.selectedIndex, 5);">
													<option value="0">Unknown</option>																										
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
																if($selInvoiceType == $InvoiceTypeID) 
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
											<td>
												<select name="SelMessengerID" class="boxenabled" tabindex="13" onChange="storeNameValue(this.selectedIndex, 10);">
													<option value="0">Unknown</option>	
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
											<td colspan="3"><input type="text" name="txtBilEmail" tabindex="14" class="boxenabled" size="77" maxlength="50" value="<?php print $txtBilEmail; ?>" /></td>
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
						<input type="button" tabindex="15" name="btnBack" value="<< Back" class="button" onClick="window.history.back();" />
						<input type="reset" tabindex="16" name="reset" value="Reset" class="button" />
						<input type="button" tabindex="17" name="btnNext" value="Next >>" class="button" onClick="ValidateForm();" />						
					</td>
				 </tr>		   
			 </table>
					<input type="hidden" name="pg" id="pg" value="4">
					<!--
						//
						//	Hidden field
						//
					-->
					<input type="hidden" name="selCustSalutation" value="<?php print $selCustSalutation; ?>" />
					<input type="hidden" name="txtCustomerName" value="<?php print $txtCustomerName; ?>" />
					<input type="hidden" name="txtCustDOB" value="<?php print $txtCustDOB; ?>" />
					<input type="hidden" name="txtCustBusNo" value="<?php print $txtCustBusNo; ?>" />
					<input type="hidden" name="selCustDuplicateID" value="<?php print $selCustDuplicateID; ?>" />
					<input type="hidden" name="CustDuplicate" value="<?php print $CustDuplicate; ?>" />
					<input type="hidden" name="radExemption" value="<?php print $radExemption; ?>" />
					<input type="hidden" name="txtVATNumber" value="<?php print $txtVATNumber; ?>" />
					<input type="hidden" name="selCustNationality" value="<?php print $selCustNationality; ?>" />
					<input type="hidden" name="selCustOccupation" value="<?php print $selCustOccupation; ?>" />
					
					<input type="hidden" name="selDesSalutation" value="<?php print $selDesSalutation; ?>" />
					<input type="hidden" name="txtDesignateName" value="<?php print $txtDesignateName; ?>" />
					<input type="hidden" name="txtDesDOB" value="<?php print $txtDesDOB; ?>" />
					<input type="hidden" name="selDesDuplicateID" value="<?php print $selDesDuplicateID; ?>" />
					<input type="hidden" name="txtDesDuplicate" value="<?php print $txtDesDuplicate; ?>" />
					<input type="hidden" name="DesPhone" value="<?php print $DesPhone; ?>" />
					<input type="hidden" name="DesEmail" value="<?php print $DesEmail; ?>" />
					<input type="hidden" name="selDesNationality" value="<?php print $selDesNationality; ?>" />
					<input type="hidden" name="selDesOccupation" value="<?php print $selDesOccupation; ?>" />
					<input type="hidden" name="txtDesAddress" value="<?php print $txtDesAddress; ?>" />
					<input type="hidden" name="selDesCountry" value="<?php print $selDesCountry; ?>" />
					<input type="hidden" name="selDesCity" value="<?php print $selDesCity; ?>" />
					<input type="hidden" name="selDesKhan" value="<?php print $selDesKhan; ?>" />
					<input type="hidden" name="selDesSangkat" value="<?php print $selDesSangkat; ?>" />
					
					<input type="hidden" name="selGuaSalutation" value="<?php print $selGuaSalutation; ?>" />
					<input type="hidden" name="txtGarrentorName" value="<?php print $txtGarrentorName; ?>" />
					<input type="hidden" name="txtGuaDOB" value="<?php print $txtGuaDOB; ?>" />
					<input type="hidden" name="selGuaDuplicateID" value="<?php print $selGuaDuplicateID; ?>" />
					<input type="hidden" name="txtGuaDuplicate" value="<?php print $txtGuaDuplicate; ?>" />
					<input type="hidden" name="GuaPhone" value="<?php print $GuaPhone; ?>" />
					<input type="hidden" name="GuaEmail" value="<?php print $GuaEmail; ?>" />
					<input type="hidden" name="selGuaNationality" value="<?php print $selGuaNationality; ?>" />
					<input type="hidden" name="selGuaOccupation" value="<?php print $selGuaOccupation; ?>" />
					<input type="hidden" name="txtGuaAddress" value="<?php print $txtGuaAddress; ?>" />
					<input type="hidden" name="selGuaCountry" value="<?php print $selGuaCountry; ?>" />
					<input type="hidden" name="selGuaCity" value="<?php print $selGuaCity; ?>" />
					<input type="hidden" name="selGuaKhan" value="<?php print $selGuaKhan; ?>" />
					<input type="hidden" name="selGuaSangkat" value="<?php print $selGuaSangkat; ?>" />
					
					<input type="hidden" name="selConSalutation" value="<?php print $selConSalutation; ?>" />
					<input type="hidden" name="txtContactName" value="<?php print $txtContactName; ?>" />
					<input type="hidden" name="txtConDOB" value="<?php print $txtConDOB; ?>" />
					<input type="hidden" name="selConDuplicateID" value="<?php print $selConDuplicateID; ?>" />
					<input type="hidden" name="txtConDuplicate" value="<?php print $txtConDuplicate; ?>" />
					<input type="hidden" name="ConPhone" value="<?php print $ConPhone; ?>" />
					<input type="hidden" name="ConEmail" value="<?php print $ConEmail; ?>" />
					<input type="hidden" name="selConNationality" value="<?php print $selConNationality; ?>" />
					<input type="hidden" name="selConOccupation" value="<?php print $selConOccupation; ?>" />
					<input type="hidden" name="txtConAddress" value="<?php print $txtConAddress; ?>" />
					<input type="hidden" name="selConCountry" value="<?php print $selConCountry; ?>" />
					<input type="hidden" name="selConCity" value="<?php print $selConCity; ?>" />
					<input type="hidden" name="selConKhan" value="<?php print $selConKhan; ?>" />
					<input type="hidden" name="selConSangkat" value="<?php print $selConSangkat; ?>" />
					<!-- ================== temporary name ===================-->
					<input type="hidden" name="txtCustNationality" value="<?php print $txtCustNationality; ?>" />
					<input type="hidden" name="txtCustOccupation" value="<?php print $txtCustOccupation; ?>" />
					<input type="hidden" name="txtDesNationality" value="<?php print $txtDesNationality; ?>" />
					<input type="hidden" name="txtDesOccupation" value="<?php print $txtDesOccupation; ?>" />
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
					
					<input type="hidden" name="txtIntSangkat" value="" />
					<input type="hidden" name="txtIntKhan" value="" />
					<input type="hidden" name="txtIntCity" value="" />
					<input type="hidden" name="txtIntCountry" value="" />
					<input type="hidden" name="txtBilSangkat" value="" />
					<input type="hidden" name="txtBilKhan" value="" />
					<input type="hidden" name="txtBilCity" value="" />
					<input type="hidden" name="txtBilCountry" value="" />
					<input type="hidden" name="txtBilInvoiceType" value="" />
					<input type="hidden" name="txtBilMessenger" value="" />
					
				</form>
			</td>
		</tr>
	</table>	
<br>&nbsp;
<?php
# Close connection
$mydb->sql_close();
?>