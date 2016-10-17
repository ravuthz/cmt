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
	$sql = "SELECT c.Address, c.SangkatID, c.KhanID, c.CityID, c.CountryID, l1.Name as Sangkat, 
								l2.Name as Khan, l3.Name as City, l4.Name as Country
					 FROM tblCustomer c, tlkpLocation l1, tlkpLocation l2, tlkpLocation l3, tlkpLocation l4
					 WHERE c.SangkatID = l1.id and c.KhanID = l2.id and c.CityID = l3.id and c.CountryID = l4.id and c.CustID=$CustomerID";
	if($que = $mydb->sql_query($sql)){
		if($result = $mydb->sql_fetchrow($que)){
			$dbAddress = $result['Address'];
			$dbSangkatID = $result['SangkatID'];
			$dbKhanID = $result['KhanID'];
			$dbCityID = $result['CityID'];
			$dbCountryID = $result['CountryID'];
			$dbSangkat = $result['Sangkat'];
			$dbKhan = $result['Khan'];
			$dbCity = $result['City'];
			$dbCountry = $result['Country'];
		}
	}
?>
<script language="JavaScript" src="./javascript/ajax_location.js"></script>
<script language="javascript">
	function storeNameValue(index, cat){		
		if(cat == 1){
			fsignupcust5.txtIntSangkat.value = fsignupcust5.selIntSangkat.options[index].text;
		}	
		else if(cat == 2){			
			fsignupcust5.txtIntKhan.value = fsignupcust5.selIntKhan.options[index].text;
			location(4, fsignupcust5.selIntKhan.options[index].value, "selIntSangkat");
		}
		else if(cat == 3){
			fsignupcust5.txtIntCity.value = fsignupcust5.selIntCity.options[index].text;						
			location(3, fsignupcust5.selIntCity.options[index].value, "selIntKhan");
		}
		else if(cat == 4){
			fsignupcust5.txtIntCountry.value = fsignupcust5.selIntCountry.options[index].text;			
			location(2, fsignupcust5.selIntCountry.options[index].value, "selIntCity");
		}		
		else if(cat == 5){
			fsignupcust5.txtBilSangkat.value = fsignupcust5.selBilSangkat.options[index].text;
		}
		else if(cat == 6){
			fsignupcust5.txtBilKhan.value = fsignupcust5.selBilKhan.options[index].text;
			location(4, fsignupcust5.selBilKhan.options[index].value, "selBilSangkat");
		}
		else if(cat == 7){
			fsignupcust5.txtBilCity.value = fsignupcust5.selBilCity.options[index].text;						
			location(3, fsignupcust5.selBilCity.options[index].value, "selBilKhan");
		}			
		else if(cat == 8){
			fsignupcust5.txtBilCountry.value = fsignupcust5.selBilCountry.options[index].text;			
			location(2, fsignupcust5.selBilCountry.options[index].value, "selBilCity");
		}			
		else if(cat == 9){
			fsignupcust5.txtLeaSangkat.value = fsignupcust5.selLeaSangkat.options[index].text;
		}
		else if(cat == 10){
			fsignupcust5.txtLeaKhan.value = fsignupcust5.selLeaKhan.options[index].text;
			location(4, fsignupcust5.selLeaKhan.options[index].value, "selLeaSangkat");
		}
		else if(cat == 11){
			fsignupcust5.txtLeaCity.value = fsignupcust5.selLeaCity.options[index].text;						
			location(3, fsignupcust5.selLeaCity.options[index].value, "selLeaKhan");
		}			
		else if(cat == 12){
			fsignupcust5.txtLeaCountry.value = fsignupcust5.selLeaCountry.options[index].text;			
			location(2, fsignupcust5.selLeaCountry.options[index].value, "selLeaCity");
		}		
	}
	
	function setTheSame(index){
		if(fsignupcust5.CustomerID.value == ""){
			txtCustAddress = fsignupcust5.txtCusAddress;
			selCusSangkat = fsignupcust5.selCusSangkat;
			selCusKhan = fsignupcust5.selCusKhan;
			selCusCity = fsignupcust5.selCusCity;
			selCusCountry = fsignupcust5.selCusCountry;
			
			txtCusSangkat = fsignupcust5.txtCusSangkat;
			txtCusKhan = fsignupcust5.txtCusKhan;
			txtCusCity = fsignupcust5.txtCusCity;
			txtCusCountry = fsignupcust5.txtCusCountry;
			txtCusNationality = fsignupcust5.txtCusNationality;
			txtCusOccupation = fsignupcust5.txtCusOccupation;				
		}else{
			txtCustAddress = fsignupcust5.dbAddress;
			selCusSangkat = fsignupcust5.dbSangkatID;
			selCusKhan = fsignupcust5.dbKhanID;
			selCusCity = fsignupcust5.dbCityID;
			selCusCountry = fsignupcust5.dbCountryID;
			
			txtCusSangkat = fsignupcust5.dbSangkat;
			txtCusKhan = fsignupcust5.dbKhan;
			txtCusCity = fsignupcust5.dbCity;
			txtCusCountry = fsignupcust5.dbCountry;
		}
		if(index == 1){
			if(fsignupcust5.thesame1.checked == true){
											
				fsignupcust5.txtIntAddress.value = txtCustAddress.value;
				
				fsignupcust5.selIntSangkat[0].value = selCusSangkat.value;
				fsignupcust5.selIntSangkat[0].text = txtCusSangkat.value;
				fsignupcust5.selIntSangkat[0].selected = "selected";
				
				fsignupcust5.selIntKhan[0].value = selCusKhan.value;
				fsignupcust5.selIntKhan[0].text = txtCusKhan.value;
				fsignupcust5.selIntKhan[0].selected = "selected";
				
				fsignupcust5.selIntCity[0].value = selCusCity.value;
				fsignupcust5.selIntCity[0].text = txtCusCity.value;
				fsignupcust5.selIntCity[0].selected = "selected";
				
				fsignupcust5.selIntCountry[0].value = selCusCountry.value;
				fsignupcust5.selIntCountry[0].text = txtCusCountry.value;
				fsignupcust5.selIntCountry[0].selected = "selected";
				
			}else{
				fsignupcust5.txtIntAddress.value = "";
				
				fsignupcust5.selIntSangkat[0].value = 0;
				fsignupcust5.selIntSangkat[0].text = "Unknown";
				fsignupcust5.selIntSangkat[0].selected = "selected";
				
				fsignupcust5.selIntKhan[0].value = 0;
				fsignupcust5.selIntKhan[0].text = "Unknown";
				fsignupcust5.selIntKhan[0].selected = "selected";
				
				fsignupcust5.selIntCity[0].value = 0;
				fsignupcust5.selIntCity[0].text = "Unknown";
				fsignupcust5.selIntCity[0].selected = "selected";
				
				fsignupcust5.selIntCountry[0].value = 0;
				fsignupcust5.selIntCountry[0].text = "Unknown";
				fsignupcust5.selIntCountry[0].selected = "selected";
			}
		}else{
			if(index == 2){
				if(fsignupcust5.thesame2.checked == true){									
					
					fsignupcust5.txtBilAddress.value = txtCustAddress.value;
					
					fsignupcust5.selBilSangkat[0].value = selCusSangkat.value;
					fsignupcust5.selBilSangkat[0].text = txtCusSangkat.value;
					fsignupcust5.selBilSangkat[0].selected = "selected";
					
					fsignupcust5.selBilKhan[0].value = selCusKhan.value;
					fsignupcust5.selBilKhan[0].text = txtCusKhan.value;
					fsignupcust5.selBilKhan[0].selected = "selected";
					
					fsignupcust5.selBilCity[0].value = selCusCity.value;
					fsignupcust5.selBilCity[0].text = txtCusCity.value;
					fsignupcust5.selBilCity[0].selected = "selected";
					
					fsignupcust5.selBilCountry[0].value = selCusCountry.value;
					fsignupcust5.selBilCountry[0].text = txtCusCountry.value;
					fsignupcust5.selBilCountry[0].selected = "selected";					
				}else{
					fsignupcust5.txtBilAddress.value = "";
					
					fsignupcust5.selBilSangkat[0].value = 0;
					fsignupcust5.selBilSangkat[0].text = "Unknown";
					fsignupcust5.selBilSangkat[0].selected = "selected";
					
					fsignupcust5.selBilKhan[0].value = 0;
					fsignupcust5.selBilKhan[0].text = "Unknown";
					fsignupcust5.selBilKhan[0].selected = "selected";
					
					fsignupcust5.selBilCity[0].value = 0;
					fsignupcust5.selBilCity[0].text = "Uknown";
					fsignupcust5.selBilCity[0].selected = "selected";
					
					fsignupcust5.selBilCountry[0].value = 0;
					fsignupcust5.selBilCountry[0].text = "Unknown";
					fsignupcust5.selBilCountry[0].selected = "selected";
				}
			}else{
				if(index == 3){
					if(fsignupcust5.thesame3.checked == true){									
						
						fsignupcust5.txtLeaAddress.value = txtCustAddress.value;
						
						fsignupcust5.selLeaSangkat[0].value = selCusSangkat.value;
						fsignupcust5.selLeaSangkat[0].text = txtCusSangkat.value;
						fsignupcust5.selLeaSangkat[0].selected = "selected";
						
						fsignupcust5.selLeaKhan[0].value = selCusKhan.value;
						fsignupcust5.selLeaKhan[0].text = txtCusKhan.value;
						fsignupcust5.selLeaKhan[0].selected = "selected";
						
						fsignupcust5.selLeaCity[0].value = selCusCity.value;
						fsignupcust5.selLeaCity[0].text = txtCusCity.value;
						fsignupcust5.selLeaCity[0].selected = "selected";
						
						fsignupcust5.selLeaCountry[0].value = selCusCountry.value;
						fsignupcust5.selLeaCountry[0].text = txtCusCountry.value;
						fsignupcust5.selLeaCountry[0].selected = "selected";
						
					}else{
						fsignupcust5.txtLeaAddress.value = "";
						
						fsignupcust5.selLeaSangkat[0].value = 0;
						fsignupcust5.selLeaSangkat[0].text = "Unknown";
						fsignupcust5.selLeaSangkat[0].selected = "selected";
						
						fsignupcust5.selLeaKhan[0].value = 0;
						fsignupcust5.selLeaKhan[0].text = "Unknown";
						fsignupcust5.selLeaKhan[0].selected = "selected";
						
						fsignupcust5.selLeaCity[0].value = 0;
						fsignupcust5.selLeaCity[0].text = "Unknown";
						fsignupcust5.selLeaCity[0].selected = "selected";
						
						fsignupcust5.selLeaCountry[0].value = 0;
						fsignupcust5.selLeaCountry[0].text = "Unknown";
						fsignupcust5.selLeaCountry[0].selected = "selected";
					}
				}
			}
		}
	}
	
	function ValidateForm(){
			if(Trim(fsignupcust5.txtBilAddress.value) == ""){
				alert("Please enter billing address");
				fsignupcust5.txtBilAddress.focus();
				return;
			}else if(fsignupcust5.selBilCountry.options[fsignupcust5.selBilCountry.selectedIndex].text == "Unknown"){
				alert("Pleas select billing country");
				fsignupcust5.selBilCountry.focus();
				return;
			}else if(fsignupcust5.selBilCity.options[fsignupcust5.selBilCity.selectedIndex].text == "Unknown"){
				alert("Pleas select billing city");
				fsignupcust5.selBilCity.focus();
				return;
			}else if(fsignupcust5.selBilKhan.options[fsignupcust5.selBilKhan.selectedIndex].text == "Unknown"){
				alert("Pleas select billing khan");
				fsignupcust5.selBilKhan.focus();
				return;
			}else if(fsignupcust5.selBilSangkat.options[fsignupcust5.selBilSangkat.selectedIndex].text == "Unknown"){
				alert("Pleas select billing sangkat");
				fsignupcust5.selBilSangkat.focus();
				return;
			}else if(fsignupcust5.SelMessengerID.selectedIndex < 1){
				alert("Pleas select messenger");
					fsignupcust5.SelMessengerID.focus();
					return;
			}else if(fsignupcust5.selBilInvoiceType.options[fsignupcust5.selBilInvoiceType.selectedIndex].value == 1){
				if(Trim(fsignupcust5.txtBilEmail.value) == ""){
					alert("Pleas enter billing email addresss");
					fsignupcust5.txtBilEmail.focus();
					return;
				}
			}if(Trim(fsignupcust5.txtBilEmail.value) != ""){
				if(!isValidMail(Trim(fsignupcust5.txtBilEmail.value))){
					alert("Invalid billing email address");
					fsignupcust5.txtBilEmail.focus();
					return;
				}
			}			
					
			fsignupcust5.txtIntSangkat.value = fsignupcust5.selIntSangkat.options[fsignupcust5.selIntSangkat.selectedIndex].text;
			fsignupcust5.txtIntKhan.value = fsignupcust5.selIntKhan.options[fsignupcust5.selIntKhan.selectedIndex].text;
			fsignupcust5.txtIntCity.value = fsignupcust5.selIntCity.options[fsignupcust5.selIntCity.selectedIndex].text;
			fsignupcust5.txtIntCountry.value = fsignupcust5.selIntCountry.options[fsignupcust5.selIntCountry.selectedIndex].text;
			
			if(fsignupcust5.ServiceID.value == 4){
				fsignupcust5.txtLeaSangkat.value = fsignupcust5.selLeaSangkat.options[fsignupcust5.selLeaSangkat.selectedIndex].text;
				fsignupcust5.txtLeaKhan.value = fsignupcust5.selLeaKhan.options[fsignupcust5.selLeaKhan.selectedIndex].text;
				fsignupcust5.txtLeaCity.value = fsignupcust5.selLeaCity.options[fsignupcust5.selLeaCity.selectedIndex].text;
				fsignupcust5.txtLeaCountry.value = fsignupcust5.selLeaCountry.options[fsignupcust5.selLeaCountry.selectedIndex].text;
			}			
			fsignupcust5.txtBilSangkat.value = fsignupcust5.selBilSangkat.options[fsignupcust5.selBilSangkat.selectedIndex].text;
			fsignupcust5.txtBilKhan.value = fsignupcust5.selBilKhan.options[fsignupcust5.selBilKhan.selectedIndex].text;
			fsignupcust5.txtBilCity.value = fsignupcust5.selBilCity.options[fsignupcust5.selBilCity.selectedIndex].text;
			fsignupcust5.txtBilCountry.value = fsignupcust5.selBilCountry.options[fsignupcust5.selBilCountry.selectedIndex].text;
			
			fsignupcust5.txtBilInvoiceType.value = fsignupcust5.selBilInvoiceType.options[fsignupcust5.selBilInvoiceType.selectedIndex].text;
			fsignupcust5.txtBilMessenger.value = fsignupcust5.SelMessengerID.options[fsignupcust5.SelMessengerID.selectedIndex].text;
			
			fsignupcust5.btnNext.disabled = true;
			fsignupcust5.submit();
		
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
								Address information>
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
						 	<td style="border-top:1px solid" align="left" bgcolor="#ffffff">
								<b>Address information</b>
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
			<form name="fsignupcust5" method="post" action="./">
			 <table border="0" cellpadding="0" cellspacing="0" align="left" width="100%">
				<tr>
					<td>
						<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle" height="18"><b>INSTALLATION ADDRESS <?php if($ServiceID == 1) print "FROM";?></b> <font color="#000000">[<input type="checkbox" name="thesame1" onClick="setTheSame(1);" tabindex="1">The same as customer profile ]</font></td>
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
				<?php if($ServiceID == 4){ #lease line
				?>
				<tr>
					<td>
						<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle" height="18"><b>INSTALLATION ADDRESS TO</b> <font color="#000000">[<input type="checkbox" name="thesame3" onClick="setTheSame(3);" tabindex="1">The same as customer profile ]</font></td>
							</tr>
							<tr>
								<td valign="top">
									<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">								 																
									<tr>
											<td align="left">Address:</td>
											<td align="left" colspan="3">
												<input type="text" name="txtLeaAddress" class="boxenabled" tabindex="1" size="79" maxlength="100" value="<?php print $txtIntAddress; ?>" />
											</td>
										</tr>										
										<tr>
											<td align="left">Country:</td>
											<td align="left">
												<select name="selLeaCountry" class="boxenabled" tabindex="2" style="width:200px" onChange="storeNameValue(this.selectedIndex, 12);">	
												<option value="0" selected="selected">Unknown</option>												
													<?php
														$sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
														// sql 2005
														
														$que = $mydb->sql_query($sql);									
														if($que){
															while($rst = $mydb->sql_fetchrow($que)){	
																$CountryID = $rst['id'];
																$Country = $rst['name'];
																if($selLeaCountry == $CountryID) 
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
												<select name="selLeaCity" class="boxenabled" tabindex="3" style="width:200px" onChange="storeNameValue(this.selectedIndex, 11);">	
													<option value="0">Unknown</option>													
												</select>
											</td>										
											
										</tr>
										<tr>
											<td align="left">Khan:</td>
											<td align="left">
												<select name="selLeaKhan" class="boxenabled" tabindex="4" style="width:200px" onChange="storeNameValue(this.selectedIndex, 10);">
													<option value="0">Unknown</option>																										
												</select>
											</td>
											<td align="left">Sangkat:</td>
											<td align="left">
												<select name="selLeaSangkat" class="boxenabled" tabindex="5" style="width:200px" onChange="storeNameValue(this.selectedIndex, 9);">
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
				<?php }?>
				<tr>
					<td>
						<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle" height="18"><b>BILLING ADDRESS</b> <font color="#000000">[<input type="checkbox" name="thesame2" onClick="setTheSame(2);" tabindex="11">The same as customer profile ]</font></td>
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
												<select name="selBilInvoiceType" class="boxenabled" tabindex="12" style="width:200px;">
													<?php
														$sql = "SELECT InvoiceTypeID, InvoiceType from tlkpCustInvoiceType order by InvoiceType desc";
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
												<select name="SelMessengerID" class="boxenabled" tabindex="13" onChange="storeNameValue(this.selectedIndex, 13);">
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
						<input type="button" tabindex="17" name="btnNext" value="Next >>" class="button" onClick="ValidateForm();" />						
					</td>
				 </tr>		   
			 </table>
					<input type="hidden" name="pg" id="pg" value="6">
					<!--
						//
						//	Hidden field
						//
					-->
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
					<input type="hidden" name="password" value="<?php print $Password; ?>" />
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
					
					<input type="hidden" name="txtIntSangkat" value="<?php print $txtIntSangkat; ?>" />
					<input type="hidden" name="txtIntKhan" value="<?php print $txtIntKhan; ?>" />
					<input type="hidden" name="txtIntCity" value="<?php print $txtIntCity; ?>" />
					<input type="hidden" name="txtIntCountry" value="<?php print $txtIntCountry; ?>" />
					
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
					
					<input type="hidden" name="ext" value="<?php print $ext; ?>" />
					<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
					
					<input type="hidden" name="vatRegistrationFee" value="<?php print $vatRegistrationFee; ?>" />
					<input type="hidden" name="vatConfigurationFee" value="<?php print $vatConfigurationFee; ?>" />
					<input type="hidden" name="vatCPEFee" value="<?php print $vatCPEFee; ?>" />
					<input type="hidden" name="vatISDNFee" value="<?php print $vatISDNFee; ?>" />
					<input type="hidden" name="vatSPNFee" value="<?php print $vatSPNFee; ?>" />
					
					
					<input type="hidden" name="dbAddress" value="<?php print $dbAddress; ?>" />
					<input type="hidden" name="dbSangkatID" value="<?php print $dbSangkatID;?>" />
					<input type="hidden" name="dbKhanID" value="<?php print $dbKhanID; ?>" />
					<input type="hidden" name="dbCityID" value="<?php print $dbCityID; ?>" />
					<input type="hidden" name="dbCountryID" value="<?php print $dbCountryID; ?>" />
					<input type="hidden" name="dbSangkat" value="<?php print $dbSangkat; ?>" />
					<input type="hidden" name="dbKhan" value="<?php print $dbKhan; ?>" />
					<input type="hidden" name="dbCity" value="<?php print $dbCity; ?>" />
					<input type="hidden" name="dbCountry" value="<?php print $dbCountry; ?>" />
					
				</form>
			</td>
		</tr>
	</table>	
<br>&nbsp;
<?php
# Close connection
$mydb->sql_close();
?>