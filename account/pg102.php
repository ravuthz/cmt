<?php

	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");	
	require_once("./common/functions.php");
	
?>
<script language="JavaScript" src="./javascript/ajax_location.js"></script>
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
	
	function Validate(){
		Address = feditbilling.txtBilAddress;
		Street = feditbilling.txtBilStreet;
		selBilSangkat = feditbilling.selBilSangkat;
		selBilKhan = feditbilling.selBilKhan;
		selBilCity = feditbilling.selBilCity;
		selBilCountry = feditbilling.selBilCountry;
		note = feditbilling.comment;
		if(Address.value == ""){
			alert("Please enter billing address");
			Address.focus();
			return;
		}else if(selBilCity.options[selBilCity.selectedIndex].text == "Unknown"){
			alert("Please select billing city");
			selBilCity.focus();
			return;
		}else if(selBilCountry.options[selBilCountry.selectedIndex].text == "Unknown"){
			alert("Please select billing country");
			selBilCountry.focus();
			return;
		}else if(selBilKhan.options[selBilKhan.selectedIndex].text == "Unknown"){
			alert("Please select billing khan");
			selBilKhan.focus();
			return;
		}else if(selBilSangkat.options[selBilSangkat.selectedIndex].text == "Unknown"){
			alert("Please select billing sangkat");
			selBilSangkat.focus();
			return;
		}/*else if(note.value == ""){
			alert("Please note for change");
			note.focus();
			return;
		}*/
		feditbilling.btnSubmit.disabled = true;
		feditbilling.submit();
	}	
	
	function storeNameValue(index, cat){		
		if(cat == 2){			
			location(4, feditinstall.selIntKhan.options[index].value, "selIntSangkat");
		}
		else if(cat == 3){			
			location(3, feditinstall.selIntCity.options[index].value, "selIntKhan");
		}
		else if(cat == 4){		
			location(2, feditinstall.selIntCountry.options[index].value, "selIntCity");
		}else if(cat == 6){			
			location(4, feditbilling.selBilKhan.options[index].value, "selBilSangkat");
		}
		else if(cat == 7){			
			location(3, feditbilling.selBilCity.options[index].value, "selBilKhan");
		}
		else if(cat == 8){		
			location(2, feditbilling.selBilCountry.options[index].value, "selBilCity");
		}		
		else if(cat == 10){			
			location(4, feditinstallto.selIntToKhan.options[index].value, "selIntToSangkat");
		}
		else if(cat == 11){			
			location(3, feditinstallto.selIntToCity.options[index].value, "selIntToKhan");
		}
		else if(cat == 12){		
			location(2, feditinstallto.selIntToCountry.options[index].value, "selIntToCity");
		}		
	}
	
	function showhide(div, flag){
		thediv = document.getElementById(div);
		if(flag == 1)
			thediv.style.display = "block";
		else
			thediv.style.display = "none";
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
	
	

	# =============== Save change ===================
	if(!empty($smt) && isset($smt) && ($smt == "save102")){
		$add = FixQuotes($add);

		if($add == 1){
			$AddressID = FixQuotes($AddressID);
			$Address = FixQuotes($txtIntAddress);
			$Sangkat = FixQuotes($selIntSangkat);
			$Street = FixQuotes($txtIntStreet);
			$Khan = FixQuotes($selIntKhan);
			$City = FixQuotes($selIntCity);
			$Country = FixQuotes($selIntCountry);			
			$comment = FixQuotes($comment);
			$title = "Update installation address";
			$IsBillingAddress = 0;
			
			$sql2 = "UPDATE tblTrackAccount SET
						iHouse1 = '".$Address."'
						,iStreet1 = '".$Street."'
						,iSangkatID1 = '".$Sangkat."'
						,iKhanID1 = '".$Khan."'
						,iCityID1 = '".$City."'
						,iCountryID1 = '".$Country."' 
				 where TrackID=".$TrackID;
			
		}elseif($add ==2){
			$AddressID = FixQuotes($AddressID);
			$Address = FixQuotes($txtBilAddress);	
			$Sangkat = FixQuotes($selBilSangkat);
			$Street = FixQuotes($txtBilStreet);
			$Khan = FixQuotes($selBilKhan);
			$City = FixQuotes($selBilCity);
			$Country = FixQuotes($selBilCountry);
			$comment = FixQuotes($comment);
			$title = "Update billing address";
			$IsBillingAddress = 1;
			
			$sql2 = "UPDATE tblTrackAccount SET
						bHouse = '".$Address."'
						,bStreet = '".$Street."'
						,bSangkatID = '".$Sangkat."'
						,bKhanID = '".$Khan."'
						,bCityID = '".$City."'
						,bCountryID = '".$Country."' 
				 where TrackID=".$TrackID;				
		}elseif($add ==3){
			$AddressID = FixQuotes($AddressID);
			$Address = FixQuotes($txtIntToAddress);	
			$Sangkat = FixQuotes($selIntToSangkat);
			$Street = FixQuotes($txtIntToStreet);
			$Khan = FixQuotes($selIntToKhan);
			$City = FixQuotes($selIntToCity);
			$Country = FixQuotes($selIntToCountry);			
			$comment = FixQuotes($comment);
			$title = "Update installation address to";
			$IsBillingAddress = 2;
			
			$sql2 = "UPDATE tblTrackAccount SET
						iHouse2 = '".$Address."'
						,iStreet2 = '".$Street."'
						,iSangkatID2 = '".$Sangkat."'
						,iKhanID2 = '".$Khan."'
						,iCityID2 = '".$City."'
						,iCountryID2 = '".$Country."' 
				 where TrackID=".$TrackID;
		}

		$sql = "UPDATE tblCustAddress SET
						Address = '".$Address.",St. ".$Street."', 
						SangkatID = '".$Sangkat."', 
						KhanID = '".$Khan."', 
						CityID = '".$City."', 
						CountryID = '".$Country."'
						WHERE AccID = ".$AccountID." and IsBillingAddress=".$IsBillingAddress;

		if($mydb->sql_query($sql)){		
			$mydb->sql_query($sql2);
			
			if($IsBillingAddress == 1)
			{
					$sql3 = "UPDATE tblCustProduct SET
						bHouse = '".$Address."'
						,bStreet = '".$Street."'
						,bSangkatID = '".$Sangkat."'
						,bKhanID = '".$Khan."'
						,bCityID = '".$City."'
						,bCountryID = '".$Country."' 
				 			where AccID=".$AccountID;
					$mydb->sql_query($sql3);
			}
			
			$Operator = $user['FullName'];
			$now = date("Y/M/d H:i:s"); 
			$audit = new Audit();
			
			$description = $comment;
			$audit->AddAudit($CustomerID, "", $title, $description, $Operator, 1, 7);							
			
		}
	}	
										
	# =============== Get billing information =====================	
	$sql = "select TrackID,AccID,bHouse,bStreet,bSangkatID,bKhanID,bCityID,bCountryID
			,b1.name bCountryName,b2.name bCityName,b3.name bKhanName,b4.name bSangkatName
			,iHouse1,iStreet1,iSangkatID1,iKhanID1,iCityID1,iCountryID1
			,l1.name lCountryName,l2.name lCityName,l3.name lKhanName,l4.name lSangkatName
			,iHouse2,iStreet2,iSangkatID2,iKhanID2,iCityID2,iCountryID2 
			,lt1.name ltCountryName,lt2.name ltCityName,lt3.name ltKhanName,lt4.name ltSangkatName 
			from tblTrackAccount ad
				left join tlkpLocation l1 on ad.iCountryID1 = l1.id
				left join tlkpLocation l2 on ad.iCityID1 = l2.id
				left join tlkpLocation l3 on ad.iKhanID1 = l3.id
				left join tlkpLocation l4 on ad.iSangkatID1 = l4.id
				left join tlkpLocation lt1 on ad.iCountryID2 = lt1.id
				left join tlkpLocation lt2 on ad.iCityID2 = lt2.id
				left join tlkpLocation lt3 on ad.iKhanID2 = lt3.id
				left join tlkpLocation lt4 on ad.iSangkatID2 = lt4.id
				left join tlkpLocation b1 on ad.bCountryID = b1.id
				left join tlkpLocation b2 on ad.bCityID = b2.id
				left join tlkpLocation b3 on ad.bKhanID = b3.id
				left join tlkpLocation b4 on ad.bSangkatID = b4.id						
				where ad.TrackID =".$TrackID;
				
	if($que = $mydb->sql_query($sql)){
		if($rst = $mydb->sql_fetchrow($que)){
			
			$TrackID = $rst['TrackID'];
			$AccID = $rst['AccID'];
			$bilAddress = $rst['bHouse'];
			$bilStreet = $rst['bStreet'];
			$bilCountryName = $rst['bCountryName'];
			$bilKhanName = $rst['bKhanName'];
			$bilCityName = $rst['bCityName'];
			$bilSangkatName = $rst['bSangkatName'];
			$bilSangkatID = $rst['bSangkatID'];
			$bilKhanID = $rst['bKhanID'];
			$bilCityID = $rst['bCityID'];
			$bilCountryID = $rst['bCountryID'];					
			$BillingAddress = $bilAddress.", St. ".$bilStreet.", ".$bilSangkatName.", ".$bilKhanName.", ".$bilCityName.", ".$bilCountryName;				
			
			$intAddress = $rst['iHouse1'];
			$intStreet = $rst['iStreet1'];
			$intCountryName = $rst['lCountryName'];
			$intKhanName = $rst['lKhanName'];
			$intCityName = $rst['lCityName'];
			$intSangkatName = $rst['lSangkatName'];	
			$intSangkatID = $rst['iSangkatID1'];
			$intKhanID = $rst['iKhanID1'];
			$intCityID = $rst['iCityID1'];
			$intCountryID = $rst['iCountryID1'];				
			$installAddress = $intAddress.", St. ".$intStreet.", ".$intSangkatName.", ".$intKhanName.", ".$intCityName.", ".$intCountryName;				
			
			$inttoAddress = $rst['iHouse2'];
			$inttoStreet = $rst['iStreet2'];
			$inttoCountryName = $rst['ltCountryName'];
			$inttoKhanName = $rst['ltKhanName'];
			$inttoCityName = $rst['ltCityName'];
			$inttoSangkatName = $rst['ltSangkatName'];	
			$inttoSangkatID = $rst['iSangkatID2'];
			$inttoKhanID = $rst['iKhanID2'];
			$inttoCityID = $rst['iCityID2'];
			$inttoCountryID = $rst['iCountryID2'];				
			$installtoAddress = $inttoAddress.", St. ".$inttoStreet.", ".$inttoSangkatName.", ".$inttoKhanName.", ".$inttoCityName.", ".$inttoCountryName;				
			
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
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID;?>&pg=10"><img src="./images/tab/customer.gif" name="customer" border="0" id="customer" onMouseOver="changeImage(1, './images/tab/customer_over.gif');" onMouseOut="changeImage(1, './images/tab/customer.gif');"/></a></td>						
						
						<td align="left" width="85"><img src="./images/tab/product_active.gif" name="product" border="0" id="product" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=41"><img src="./images/tab/finance.gif" name="finance" border="0" id="finance" onMouseOver="changeImage(3, './images/tab/finance_over.gif');" onMouseOut="changeImage(3, './images/tab/finance.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=70"><img src="./images/tab/usage.gif" name="usage" border="0" id="usage" onMouseOver="changeImage(4, './images/tab/usage_over.gif');" onMouseOut="changeImage(4, './images/tab/usage.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=30"><img src="./images/tab/audit.gif" name="audit" border="0" id="audit" onMouseOver="changeImage(5, './images/tab/audit_over.gif');" onMouseOut="changeImage(5, './images/tab/audit.gif');" /></a></td>						
						
						<td align="center" width="*" background="./images/tab_null.gif">&nbsp;</td>		
					</tr>
				</table>
					<!-- end customer table menu -->			
			</td>
		</tr>
		<tr>
			<td height="100%" valign="top">
					<!-- Individual customer main page -->				
					<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="180" rowspan="6">
			<?php include("content.php"); ?>
		</td>
		<td align="left" valign="top">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>INSTALLATION 
					<?php
						if($ServiceID == 4) print " FROM ";
					?>
					INFORMATION
					- <?php print $aSubscriptionName." (".$aUserName.")"; ?></b>
					</td>
					<td align="right">[<a href="javascript:showhide('d-installation', 1);">Edit</a>]</td>
				</tr>
				<tr>
					<td valign="top" colspan="2">
						<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">																										
							<tr>
								<td align="left" valign="top">Address:</td>
								<td align="left" colspan="3"><b><?php print $installAddress; ?></b></td>
							</tr>												
						</table>
					</td>
				</tr>							
			</table>
		</td>
	</tr>	
	<!-- edit billing address -->
	<tr>
		<td align="left">
			<div style="display:none" id="d-installation">
				<form name="feditinstall" action="./" method="post">
					<table border="0" cellpadding="3" cellspacing="0" align="left">
						<tr>
							<td colspan="2" width="50%">
								<fieldset style="width:380px">
									<legend align="center">EDIT INSTALLATION <?php
								if($ServiceID == 4) print " FROM ";
							?> ADDRESS</legend>
										<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%">								 																
											<tr>
													<td align="left">House:</td>
													<td align="left">
														<input type="text" name="txtIntAddress" class="boxenabled" tabindex="1" size="30" maxlength="100" value="<?php print $intAddress; ?>" />
													</td>
                                                    <td align="left">Street:</td>
													<td align="left">
														<input type="text" name="txtIntStreet" class="boxenabled" tabindex="1" size="30" maxlength="100" value="<?php print $intStreet; ?>" />
													</td>
												</tr>										
												<tr>
													<td align="left">Country:</td>
													<td align="left">
														<select name="selIntCountry" class="boxenabled" tabindex="2" style="width:200px" onChange="storeNameValue(this.selectedIndex, 4);">	
														<option value="<?php print $selIntCountry; ?>" selected="selected"><?php print $txtIntCountry;?></option>												
															<?php
																$sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
																// sql 2005
																
																$que = $mydb->sql_query($sql);									
																if($que){
																	while($rst = $mydb->sql_fetchrow($que)){	
																		$CountryID = $rst['id'];
																		$Country = $rst['name'];
																		if($intCountryID == $CountryID) 
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
															<option value="<?php print $intCityID; ?>"><?php print $intCityName; ?></option>													
														</select>
													</td>										
													
												</tr>
												<tr>
													<td align="left">Khan:</td>
													<td align="left">
														<select name="selIntKhan" class="boxenabled" tabindex="4" style="width:200px" onChange="storeNameValue(this.selectedIndex, 2);">
															<option value="<?php print $intKhanID; ?>"><?php print $intKhanName; ?></option>																										
														</select>
													</td>
													<td align="left">Sangkat:</td>
													<td align="left">
														<select name="selIntSangkat" class="boxenabled" tabindex="5" style="width:200px" onChange="storeNameValue(this.selectedIndex, 1);">
															<option value="<?php print $intSangkatID; ?>"><?php print $intSangkatName; ?></option>																										
														</select>
													</td>																					
												</tr>	
												<tr>
													<td align="left" valign="top">Note:</td>
													<td align="left" colspan="3">
														<textarea name="comment" cols="63" rows="3" class="boxenabled"></textarea>
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
					<input type="hidden" name="AddressID" value="<?php print $intAddressID; ?>" />
					<input type="hidden" name="CustomerID" value="<?php print $CustomerID;?>" />
					<input type="hidden" name="AccountID" value="<?php print $AccountID; ?>" />
		            <input type="hidden" name="TrackID" value="<?php print $TrackID; ?>" />
					<input type="hidden" name="add" value="1" />
					<input type="hidden" name="pg" value="102" />
					<input type="hidden" name="smt" value="save102" />
				</form>
			</div>
		</td>
	</tr>	
	<?php if($ServiceID == 4){ 

	?>
	<tr>
		<td align="left" valign="top">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>INSTALLATION TO INFORMATION
					- <?php print $aSubscriptionName." (".$aUserName.")"; ?></b>
					</td>
					<td align="right">[<a href="javascript:showhide('d-installfrom', 1);">Edit</a>]</td>
				</tr>
				<tr>
					<td valign="top" colspan="2">
						<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">																										
							<tr>
								<td align="left" valign="top">Address:</td>
								<td align="left" colspan="3"><b><?php print $installtoAddress; ?></b></td>
							</tr>												
						</table>
					</td>
				</tr>							
			</table>
		</td>
	</tr>
	<tr>
		<td align="left">
			<div style="display:none" id="d-installfrom">
				<form name="feditinstallto" action="./" method="post">
					<table border="0" cellpadding="3" cellspacing="0" align="left">
						<tr>
							<td colspan="2" width="50%">
								<fieldset style="width:380px">
									<legend align="center">EDIT INSTALLATION TO ADDRESS</legend>
										<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%">								 																
											<tr>
													<td align="left">House:</td>
													<td align="left">
														<input type="text" name="txtIntToAddress" class="boxenabled" tabindex="1" size="30" maxlength="100" value="<?php print $inttoAddress; ?>" />
													</td>
                                                    <td align="left">Street:</td>
													<td align="left">
														<input type="text" name="txtIntToStreet" class="boxenabled" tabindex="1" size="30" maxlength="100" value="<?php print $inttoStreet; ?>" />
													</td>
												</tr>										
												<tr>
													<td align="left">Country:</td>
													<td align="left">
														<select name="selIntToCountry" class="boxenabled" tabindex="2" style="width:200px" onChange="storeNameValue(this.selectedIndex, 12);">	
														<option value="0" selected="selected">Unknown</option>												
															<?php
																$sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
																// sql 2005
																
																$que = $mydb->sql_query($sql);									
																if($que){
																	while($rst = $mydb->sql_fetchrow($que)){	
																		$CountryID = $rst['id'];
																		$Country = $rst['name'];
																		if($inttoCountryID == $CountryID) 
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
														<select name="selIntToCity" class="boxenabled" tabindex="3" style="width:200px" onChange="storeNameValue(this.selectedIndex, 11);">	
															<option value="<?php print $inttoCityID; ?>"><?php print $inttoCityName; ?></option>													
														</select>
													</td>										
													
												</tr>
												<tr>
													<td align="left">Khan:</td>
													<td align="left">
														<select name="selIntToKhan" class="boxenabled" tabindex="4" style="width:200px" onChange="storeNameValue(this.selectedIndex, 10);">
															<option value="<?php print $inttoKhanID; ?>"><?php print $inttoKhanName; ?></option>																										
														</select>
													</td>
													<td align="left">Sangkat:</td>
													<td align="left">
														<select name="selIntToSangkat" class="boxenabled" tabindex="5" style="width:200px" onChange="storeNameValue(this.selectedIndex, 1);">
															<option value="<?php print $inttoSangkatID; ?>"><?php print $inttoSangkatName; ?></option>																										
														</select>
													</td>																					
												</tr>	
												<tr>
													<td align="left" valign="top">Note:</td>
													<td align="left" colspan="3">
														<textarea name="comment" cols="63" rows="3" class="boxenabled"></textarea>
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
					<input type="hidden" name="AddressID" value="<?php print $inttoAddressID; ?>" />
					<input type="hidden" name="CustomerID" value="<?php print $CustomerID;?>" />
					<input type="hidden" name="AccountID" value="<?php print $AccountID; ?>" />
                    <input type="hidden" name="TrackID" value="<?php print $TrackID; ?>" />
					<input type="hidden" name="add" value="3" />
					<input type="hidden" name="pg" value="102" />
					<input type="hidden" name="smt" value="save102" />
				</form>
			</div>
		</td>
	</tr>
	<?php }?>	
	<tr>
		<td align="left" valign="top">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>BILLING INFORMATION
					- <?php print $aSubscriptionName." (".$aUserName.")"; ?></b>
					</td>
					<td align="right">[<a href="javascript:showhide('d-billing', 1);">Edit</a>]</td>
				</tr>
				<tr>
					<td valign="top" colspan="2">
						<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">																										
							<tr>
								<td align="left" valign="top">Address:</td>
								<td align="left" colspan="3"><b><?php print $BillingAddress; ?></b></td>
							</tr>												
						</table>
					</td>
				</tr>							
			</table>
		</td>
	</tr>
		<!-- billing information -->	
	<!-- edit billing address -->
	<tr>
		<td align="left">
			<div style="display:none" id="d-billing">
				<form name="feditbilling" action="./" method="post">
					<table border="0" cellpadding="3" cellspacing="0" align="left">
						<tr>
							<td colspan="2" width="50%">
								<fieldset style="width:380px">
									<legend align="center">EDIT BILLING ADDRESS</legend>
										<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%">								 																
											<tr>
													<td align="left">House:</td>
													<td align="left">
														<input type="text" name="txtBilAddress" class="boxenabled" tabindex="1" size="30" maxlength="100" value="<?php print $bilAddress; ?>" />
													</td>
                                                    <td align="left">Street:</td>
													<td align="left">
														<input type="text" name="txtBilStreet" class="boxenabled" tabindex="1" size="30" maxlength="100" value="<?php print $bilStreet; ?>" />
													</td>
												</tr>										
												<tr>
													<td align="left">Country:</td>
													<td align="left">
														<select name="selBilCountry" class="boxenabled" tabindex="2" style="width:200px" onChange="storeNameValue(this.selectedIndex, 8);">						
															<option value="0">Unknown</option>										
															<?php
																$sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
																// sql 2005
																
																$que = $mydb->sql_query($sql);									
																if($que){
																	while($rst = $mydb->sql_fetchrow($que)){	
																		$CountryID = $rst['id'];
																		$Country = $rst['name'];
																		if($bilCountryID == $CountryID) 
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
														<select name="selBilCity" class="boxenabled" tabindex="3" style="width:200px" onChange="storeNameValue(this.selectedIndex, 7);">	
															<option value="<?php print $bilCityID; ?>"><?php print $bilCityName; ?></option>													
														</select>
													</td>										
													
												</tr>
												<tr>
													<td align="left">Khan:</td>
													<td align="left">
														<select name="selBilKhan" class="boxenabled" tabindex="4" style="width:200px" onChange="storeNameValue(this.selectedIndex, 6);">
															<option value="<?php print $bilKhanID; ?>"><?php print $bilKhanName; ?></option>																										
														</select>
													</td>
													<td align="left">Sangkat:</td>
													<td align="left">
														<select name="selBilSangkat" class="boxenabled" tabindex="5" style="width:200px" onChange="storeNameValue(this.selectedIndex, 5);">
															<option value="<?php print $bilSangkatID; ?>"><?php print $bilSangkatName; ?></option>																										
														</select>
													</td>																					
												</tr>	
												<tr>
													<td align="left" valign="top">Note:</td>
													<td align="left" colspan="3">
														<textarea name="comment" cols="63" rows="3" class="boxenabled"></textarea>
													</td>										
												</tr>	
												<tr>
												<td align="center" colspan="4">
													<input type="reset" name="reset" value="Reset" class="button" />&nbsp;
													<input type="button" name="btnSubmit" value="Save" class="button" onClick="Validate();" />
												</td>
											</tr>																																						 		
										 </table>
								</fieldset>
							</td>
						</tr>
					</table>
					<input type="hidden" name="AddressID" value="<?php print $bilAddressID; ?>" />
					<input type="hidden" name="CustomerID" value="<?php print $CustomerID;?>" />
					<input type="hidden" name="AccountID" value="<?php print $AccountID; ?>" />
                    <input type="hidden" name="TrackID" value="<?php print $TrackID; ?>" />
					<input type="hidden" name="add" value="2" />
					<input type="hidden" name="pg" value="102" />
					<input type="hidden" name="smt" value="save102" />
				</form>
			</div>
		</td>
	</tr>					
</table>
<?php
# Close connection
$mydb->sql_close();
?>
