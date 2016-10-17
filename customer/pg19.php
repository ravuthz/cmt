<?php
	require_once("./common/agent.php");
	require_once("./common/class.audit.php");
	require_once("./common/functions.php");
	require_once("./common/helper.php");
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
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/validphone.js"></script>
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
	//
	// Store name value
	//
	function storeNameValue(index, cat){
		if(cat == 17){
			faddcontact.txtSangkat.value = faddcontact.selSangkat.options[index].text;
		}
		else if(cat == 18){
			faddcontact.txtKhan.value = faddcontact.selKhan.options[index].text;
			location(4, faddcontact.selKhan.options[index].value, "selSangkat");
		}
		else if(cat == 19){
			faddcontact.txtCity.value = faddcontact.selCity.options[index].text;
			location(3, faddcontact.selCity.options[index].value, "selKhan");
		}
		else if(cat == 20){
			faddcontact.txtCountry.value = faddcontact.selCountry.options[index].text;
			location(2, faddcontact.selCountry.options[index].value, "selCity");
		}
	}
	//
	//	Validate form
	//
	function ValidateForm(){
		if(Trim(faddcontact.txtGuarratorName.value) == ""){
			alert("Please enter guarrantor name");
			faddcontact.txtGuarratorName.focus();
			return;
		}else if(Trim(faddcontact.txtAddress.value) == ""){
			alert("Please enter customer address");
			faddcontact.txtAddress.focus();
			return;
		}else if(faddcontact.selCountry.options[faddcontact.selCountry.selectedIndex].text == "Unknown"){
			alert("Pleas select customer country");
			faddcontact.selCountry.focus();
			return;
		}else if(faddcontact.selCity.options[faddcontact.selCity.selectedIndex].text == "Unknown"){
			alert("Pleas select customer city");
			faddcontact.selCity.focus();
			return;
		}else if(faddcontact.selKhan.options[faddcontact.selKhan.selectedIndex].text == "Unknown"){
			alert("Pleas select customer khan");
			faddcontact.selKhan.focus();
			return;
		}else if(faddcontact.selSangkat.options[faddcontact.selSangkat.selectedIndex].text == "Unknown"){
			alert("Pleas select customer sangkat");
			faddcontact.selSangkat.focus();
			return;
		}else if(Trim(faddcontact.comment.value) == ""){
			alert("Please enter comment for add new customer guarantor");
			faddcontact.comment.focus();
			return;
		}

		faddcontact.txtCountry.value = faddcontact.selCountry.options[faddcontact.selCountry.selectedIndex].text;
		faddcontact.txtCity.value = faddcontact.selCity.options[faddcontact.selCity.selectedIndex].text;
		faddcontact.txtKhan.value = faddcontact.selKhan.options[faddcontact.selKhan.selectedIndex].text;
		faddcontact.txtSangkat.value = faddcontact.selSangkat.options[faddcontact.selSangkat.selectedIndex].text;
		faddcontact.txtOccupation.value = faddcontact.selOccupation.options[faddcontact.selOccupation.selectedIndex].text;
		faddcontact.txtNationality.value = faddcontact.selNationality.options[faddcontact.selNationality.selectedIndex].text;

		faddcontact.btnSave.disabled = true;
		faddcontact.submit();
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

	//
	//	Save customer information
	//
	if(!empty($smt) && isset($smt) && ($smt == "savepg19")){
		$selSalutation = FixQuotes($selSalutation);
		$txtGuarratorName = FixQuotes($txtGuarratorName);
		$txtDOB = FixQuotes($txtDOB);
		$selDuplicateID = FixQuotes($selDuplicateID);
		$txtDuplicate = FixQuotes($txtDuplicate);
		$selNationality = FixQuotes($selNationality);
		$selOccupation = FixQuotes($selOccupation);
		$txtPhone = FixQuotes($txtPhone);
		$txtEmail = FixQuotes($txtEmail);
		$txtAddress = FixQuotes($txtAddress);
		$selCountry = FixQuotes($selCountry);
		$selCity = FixQuotes($selCity);
		$selKhan = FixQuotes($selKhan);
		$selSangkat = FixQuotes($selSangkat);

		$txtSangkat = FixQuotes($txtSangkat);
		$txtKhan = FixQuotes($txtKhan);
		$txtCity = FixQuotes($txtCity);
		$txtCountry = FixQuotes($txtCountry);
		$txtOccupation = FixQuotes($txtOccupation);
		$txtNationality = FixQuotes($txtNationality);
		$comment = FixQuotes($comment);
		$Operator = $user['FullName'];
		$now = date("Y/M/d H:i:s");
		$audit = new Audit();

		$sql = "INSERT INTO tblCustGuarrantor(CustID, GuarrantorName, GuarrantorNameKhmer, Salutation, DOB, Address, AddressKhmer, KhanID, SangkatID, CityID,
												CountryID, NationalityID, OccupationID, Phone, Email, IdentityMode, IdentityData, SignupDate)
									VALUES($CustomerID, '".$txtGuarratorName."', ".encodeUnicode($txtGuarratorNameKhmer).", '".$selSalutation."', '".$txtDOB."', '".$txtAddress."', ".encodeUnicode($txtAddressKhmer).",
												 $selKhan, $selSangkat, $selSangkat, $selCountry, $selNationality, $selOccupation, '".$txtPhone."',
												 '".$txtEmail."', '".$selDuplicateID."', '".$txtDuplicate."', '".$now."')";
		if($mydb->sql_query($sql)){
			$title = "Add new customer guarrantor";
			//$description = "add new customer guarrantor as: Name: $selSalutation $txtGuarratorName; DOB: $txtDOB; nationality: $txtNationality; occupation: $txtOccupation; phone: $txtPhone; email: $txtEmail; duplicate: $selDuplicateID- $txtDuplicate; address: $txtAddress; sangkat: $txtSangkat; Khan: $txtKhan; City: $txtCity; Country: $txtCountry.";
			$description = $comment;
			$audit->AddAudit($CustomerID, "", $title, $description, $Operator, 1, 7);
			//redirect('./?CustomerID='.$CustomerID.'&pg=10');
			$retOut = $myinfo->info("Successful added new customer guarantor");
		}else{
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to add new customer guarranter.", $error['message']);
		}
	}


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

						<td align="left" width="85"><img src="./images/tab/customer_active.gif" name="customer" border="0" id="customer" /></td>

						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=90"><img src="./images/tab/product.gif" name="product" border="0" id="product" onmouseover="changeImage(2, './images/tab/product_over.gif');" onmouseout="changeImage(2, './images/tab/product.gif');" /></a></td>

						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=41"><img src="./images/tab/finance.gif" name="finance" border="0" id="finance" onmouseover="changeImage(3, './images/tab/finance_over.gif');" onmouseout="changeImage(3, './images/tab/finance.gif');" /></a></td>

						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=70"><img src="./images/tab/usage.gif" name="usage" border="0" id="usage" onmouseover="changeImage(4, './images/tab/usage_over.gif');" onmouseout="changeImage(4, './images/tab/usage.gif');" /></a></td>

						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID;?>&pg=30"><img src="./images/tab/audit.gif" name="audit" border="0" id="audit" onmouseover="changeImage(5, './images/tab/audit_over.gif');" onmouseout="changeImage(5, './images/tab/audit.gif');"/></a></td>

						<td align="center" width="*" background="./images/tab_null.gif">&nbsp;</td>
					</tr>
				</table>
					<!-- end customer table menu -->
			</td>
		</tr>
		<tr>
			<td height="100%" valign="top">
					<!-- Individual customer main page -->
					<table border="0" cellpadding="0" cellspacing="5" width="100%" height="100%" align="left">
						<tr>
							<td width="150" valign="top"><?php include("content.php");?></td>
							<!-- Customer information -->
							<td align="left" valign="top">
								<form name="faddcontact" method="post" action="./">
									<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
										<tr>
											<td align="left" class="formtitle" height="18"><b>Add new customer guarantor</b></td>
										</tr>
										<tr>
											<td valign="top">
												<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
												<tr>
													<td align="left">Salutation:</td>
													<td align="left" width="200" colspan="3">
														<select name="selSalutation" class="boxenabled" tabindex="42">
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
														<input type="text" name="txtGuarratorName" class="boxenabled" tabindex="43" size="82" value="<?php print $txtGuarratorName;?>" />
													</td>
												</tr>
												<tr>
													<td align="left"><span class="khfont">ឈ្មោះ:</span></td>
													<td align="left" colspan="3">
														<input type="text" name="txtGuarratorNameKhmer" class="boxenabled khfont" tabindex="43" size="82" value="<?php print $txtGuarratorNameKhmer;?>" />
													</td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">Date of birth:</td>
													<td align="left"><input type="text" tabindex="44" name="txtDOB" class="boxenabled" size="27" maxlength="30" value="<?php print $txtDOB; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
														<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?faddcontact|txtDOB', '', 'width=200,height=220,top=250,left=350');">
															<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
														</button>
													</td>
													<td align="left">Duplicate:</td>
													<td align="left">
														<select name="selDuplicateID" class="boxenabled" tabindex="45">
															<option value="ID Card" <?php if($selConDuplicateID == "ID Card") print "selected"?>>ID Card</option>
															<option value="Passport" <?php if($selConDuplicateID == "Passport") print "selected"?>>Passport</option>
															<option value="Family book" <?php if($selConDuplicateID == "Miss.") print "selected"?>>Family book</option>
														</select>
														<input type="text" name="txtDuplicate" class="boxenabled" tabindex="46" size="11" value="<?php print $txtGuaDuplicate;?>" />
													</td>
												</tr>
												<tr>
													<td align="left">Nationality:</td>
													<td align="left">
														<select name="selNationality" class="boxenabled" tabindex="47" onChange="storeNameValue(this.selectedIndex, 15);" style="width:200px">
															<option value="38" selected="selected">Cambodia</option>
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
														<select name="selOccupation" class="boxenabled" tabindex="48" onChange="storeNameValue(this.selectedIndex, 16);" style="width:200px">
															<?php
																$sql = "SELECT CareerID, Career from tlkpCareer order by CareerID";
																// sql 2005

																$que = $mydb->sql_query($sql);
																if($que){
																	while($rst = $mydb->sql_fetchrow($que)){
																		$CareerID = $rst['CareerID'];
																		$Career = $rst['Career'];
																		if($selConaOccupation == $CareerID)
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
													<td align="left"><input type="text" name="txtPhone" value="<?php print $txtPhone; ?>" class="boxenabled" tabindex="49" size="29" onKeyUp="ValidatePhone(this);" onBlur="CheckPhone(this);" />
													</td>
													<td align="left">Email:</td>
													<td align="left"><input type="text" name="txtEmail" value=<?php print $txtEmail; ?>"" class="boxenabled" tabindex="50" size="29" /> </td>
												</tr>
												<tr>
													<td align="left">Address:</td>
													<td align="left" colspan="3">
														<input type="text" name="txtAddress" class="boxenabled" tabindex="51" size="83" maxlength="100" value="<?php print $txtAddress; ?>" />
													</td>
												</tr>
												<tr>
													<td align="left"><span class="khfont">អាស័យដ្ឋាន:</span></td>
													<td align="left" colspan="3">
														<input type="text" name="txtAddressKhmer" class="boxenabled khfont" tabindex="51" size="83" maxlength="100" value="<?php print $txtAddressKhmer; ?>" />
													</td>
												</tr>
													<tr>
														<tr>
															<td align="left">Country:</td>
															<td align="left">
																<select name="selCountry" class="boxenabled" tabindex="52" style="width:200px" onChange="storeNameValue(this.selectedIndex, 20);">
																<option value="0" selected="selected">Unknown</option>
																	<?php
																		$sql = "SELECT id, name from tlkpLocation where type = 1 order by name";

																		$que = $mydb->sql_query($sql);
																		if($que){
																			while($rst = $mydb->sql_fetchrow($que)){
																				$CountryID = $rst['id'];
																				$Country = $rst['name'];
																				if($selCountry == $CountryID)
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
																<select name="selCity" class="boxenabled" tabindex="53" style="width:200px" onChange="storeNameValue(this.selectedIndex, 19);">
																	<option value="0">Unknown</option>
																</select>
															</td>
														</tr>
														<td align="left">Khan:</td>
														<td align="left">
															<select name="selKhan" class="boxenabled" tabindex="54" style="width:200px" onChange="storeNameValue(this.selectedIndex, 18);">
																<option value="0">Unknown</option>
															</select>
														</td>
														<td align="left">Sangkat:</td>
														<td align="left">
															<select name="selSangkat" class="boxenabled" tabindex="55" style="width:200px" onChange="storeNameValue(this.selectedIndex, 17);">
																<option value="0">Unknown</option>
															</select>
														</td>
													</tr>
													<tr>
														<td align="left" valign="top">Note:</td>
														<td align="left" colspan="3">
															<textarea name="comment" cols="63" rows="3" class="boxenabled"></textarea>
														</td>
													</tr>
													<tr><td colspan="4">&nbsp;</td></tr>
													<tr><td align="center" colspan="4">
														<input type="reset" tabindex="56" name="reset" value="Reset" class="button" />
														<input type="button" tabindex="57" name="btnSave" value="Save" class="button" onClick="ValidateForm();" />
													</td></tr>
													<?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"4\" align=\"left\">$retOut</td></tr>";
														}
													?>
											 </table>
										 </td>
										 </tr>
									</table>
									<input type="hidden" name="pg" id="pg" value="19" />
									<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
									<input type="hidden" name="txtSangkat" value="" />
									<input type="hidden" name="txtKhan" value="" />
									<input type="hidden" name="txtCity" value="" />
									<input type="hidden" name="txtCountry" value="" />
									<input type="hidden" name="txtOccupation" value="" />
									<input type="hidden" name="txtNationality" value="" />
									<input type="hidden" name="smt" value="savepg19" />
								</form>
							</td>
						</tr>
					</table>
				</td>
			</tr>

</table>
<?php
# Close connection
$mydb->sql_close();
?>
