<?php
	require_once("./common/agent.php");	
			
	if(isset($smt) && (!empty($smt)) && ($pg == 261)){
	
			$sql =	"Insert Into tlkpMessengerSangkat 
					 Values (".$selIntMessenger.",".$selIntCountry.",".$selIntCity.",".$selIntKhan.",".$selIntSangkat.")";
			
		if($mydb->sql_query($sql)){
			$mes = $myinfo->info("Successfully sign up new Sangkat to Messenger.");
		}else{			
			$error = $mydb->sql_error();
			$mes = $myinfo->error("Failed to sign up new messenger", $error['message']);
		}
	}
	
	
	$sql = "SELECT c.Address, c.SangkatID, c.KhanID, c.CityID, c.CountryID, l1.Name as Sangkat, 
								l2.Name as Khan, l3.Name as City, l4.Name as Country
					 FROM tblCustomer c, tlkpLocation l1, tlkpLocation l2, tlkpLocation l3, tlkpLocation l4
					 WHERE c.SangkatID = l1.id and c.KhanID = l2.id and c.CityID = l3.id and c.CountryID = l4.id
					 and l1.id not in ( select SangkatID from dbo.tlkpMessengerSangkat )
					 ";
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
<script language="JavaScript" src="./javascript/ajax_location_messenger.js"></script>
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
	}
	
	
	function ValidateForm(){
			fsignupcust5.txtIntSangkat.value = fsignupcust5.selIntSangkat.options[fsignupcust5.selIntSangkat.selectedIndex].text;
			fsignupcust5.txtIntKhan.value = fsignupcust5.selIntKhan.options[fsignupcust5.selIntKhan.selectedIndex].text;
			fsignupcust5.txtIntCity.value = fsignupcust5.selIntCity.options[fsignupcust5.selIntCity.selectedIndex].text;
			fsignupcust5.txtIntCountry.value = fsignupcust5.selIntCountry.options[fsignupcust5.selIntCountry.selectedIndex].text;
			fsignupcust5.txtIntMessenger.value = fsignupcust5.selIntMessenger.options[fsignupcust5.selIntMessenger.selectedIndex].text;
			
			
			fsignupcust5.btnNext.disabled = true;
			fsignupcust5.submit();
		
	}
</script>
<br>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="100%">
	<tr>
		<td valign="top" width="100%" align="left"> 
			<form name="fsignupcust5" method="post" action="./">
			 <table border="0" cellpadding="0" cellspacing="0" align="left" width="100%">
				<tr>
					<td>
						<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle" height="18"><b>Messenger by Sangkat</b></td>
							</tr>
							<tr>
								<td valign="top">
									<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">								 																
									<tr>
											<td align="left">Messenger:</td>
											<td align="left" colspan="3">
												<select name="selIntMessenger" class="boxenabled" tabindex="2" style="width:200px">													
													<?php
														$sql = "select MessengerID as 'id', Salutation + ' ' + [Name] as 'name' from dbo.tlkpMessenger where statusid = 1 order by name";
														// sql 2005
														
														$que = $mydb->sql_query($sql);									
														if($que){
															while($rst = $mydb->sql_fetchrow($que)){	
																$MessengerID = $rst['id'];
																$Messenger = $rst['name'];
																if($selIntMessenger == $MessengerID) 
																	$sel = "selected";
																else
																	$sel = "";
																print "<option value='".$MessengerID."' ".$sel.">".$Messenger."</option>";
															}
														}
														$mydb->sql_freeresult();
													?>
												</select>
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
				
				<tr><td>&nbsp;</td></tr>
					<tr> 				  
						<td align="center">						
							<input type="button" tabindex="17" name="btnNext" value="Submit" class="button" onClick="ValidateForm();" />						
						</td>
					 </tr>
					 <?php
							if(isset($mes) && (!empty($mes))){
								print "<tr><td colspan=\"6\" align=\"center\">$mes</td></tr>";
							}
						?>		
					 
					 <tr><td>&nbsp;</td></tr>
					 <tr><td>
					<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>
																							
								<th align="center" style="border:1px solid">No.</th>
								<th align="left" style="border:1px solid">Messenger</th>
								<th align="center" style="border:1px solid">Sangkat</th>
								<th align="center" style="border:1px solid">Khan</th>
								<th align="center" style="border:1px solid">City</th>
								<th align="center" style="border:1px solid">Country</th>														
							</thead>
							<tbody>
<?php $sql = "	select ms.MessengerID, Salutation + ' ' + [Name] MessengerName, Phone,
				(select [Name] from  tlkpLocation where tlkpLocation.id = ms.SangkatID) Sangkat, 
				(select [Name] from  tlkpLocation where tlkpLocation.id = ms.KhanID) Khan,
				(select [Name] from  tlkpLocation where tlkpLocation.id = ms.CityID) City,
				(select [Name] from  tlkpLocation where tlkpLocation.id = ms.CountryID) Country
				from tlkpMessenger me
				join tlkpMessengerSangkat ms on me.MessengerID = ms.MessengerID
				order by MessengerName,Country,City,Khan, Sangkat";
			
	if($que = $mydb->sql_query($sql)){		
		$n = 0;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$MessengerName = $result['MessengerName'];
			$Phone = $result['Phone'];
			$Sangkat = $result['Sangkat'];
			$Khan = $result['Khan'];
			$City = $result['City'];					
			$Country = $result['Country'];										
					
			$iLoop++;															
			$n++;
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid; border-top:1px dotted;">'.$n.'</td>';	
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$MessengerName.'</td>';																														
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$Sangkat.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$Khan.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$City.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted;border-right:1px solid; border-top:1px dotted;">'.$Country.'</td>';


			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																					
								<tr height=20>
										<td colspan="6" align="right" style="border:1px solid">&nbsp;&nbsp;&nbsp;&nbsp;</td>
								</tr>
								</table>';
	 print $retOut;
?>											
										</td></tr>
					 		   
			 </table>
			 
			 
					<input type="hidden" name="pg" id="pg" value="261">
					<input type="hidden" name="smt" id="smt" value="yes">
					<input type="hidden" name="txtIntSangkat" value="<?php print $txtIntSangkat; ?>" />
					<input type="hidden" name="txtIntKhan" value="<?php print $txtIntKhan; ?>" />
					<input type="hidden" name="txtIntCity" value="<?php print $txtIntCity; ?>" />
					<input type="hidden" name="txtIntCountry" value="<?php print $txtIntCountry; ?>" />
					<input type="hidden" name="txtIntMessenger" value="<?php print $txtIntMessenger; ?>" />
					
					
		  </form>
			</td>
		</tr>
	</table>	
<br>&nbsp;
<?php
# Close connection
$mydb->sql_close();
?>