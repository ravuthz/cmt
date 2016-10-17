<?php
	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");
	require_once("./common/functions.php");
/*
	+ ************************************************************************************** +	
	*																																												 *
	* This code is not to be distributed without the written permission of BRC Technology.   *
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
	* 																																											 *
	+ ************************************************************************************** +
*/
		
	if(isset($smt) && (!empty($smt)) && ($pg == 21)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
			$sql = "INSERT INTO tlkpMessenger (Salutation, Name, Address, Country, Phone, Fax, Email, Passport, DOB, Remark)
									VALUES('".$selSalutation."', '".$txtName."', '".$txtAddress."',".$selCountry.", '".$txtPhone."', '".$txtFax."', '".$txtEmail."', '".$txtPassport."', '".$txtDOB."', '".$txtComment."')";
		if($mydb->sql_query($sql)){
			$Operator = $user['FullName'];
			$now = date("Y/M/d H:i:s"); 
			$audit = new Audit();
			
			$title = "Add new messenger";
			$description = "add new messenger: Name: $selSalutation $txtName; DOB: $txtDOB; phone: $txtPhone; fax: $txtFax; email: $txtEmail; duplicate: $txtPassport; address: $txtAddress, $selCountry.";

			$audit->AddAudit("", "", $title, $description, $Operator, 1, 1);
			$retOut = $myinfo->info("Successfully sign up new messenger.");
		}else{			
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to sign up new messenger", $error['message']);
		}
	}
?>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/validphone.js"></script>
<script language="javascript">
	function ValidateForm(){

		mName = fsignupmessenger.txtName;
		mAddress = fsignupmessenger.txtAddress;
		mPassport = fsignupmessenger.txtPassport;
		mPhone = fsignupmessenger.txtPhone;
		mFax = fsignupmessenger.txtFax;
		mEmail = fsignupmessenger.txtEmail;
		if(Trim(mName.value) == ""){
			alert("Please enter messenger name.");
			mName.focus();
			return;
		}else if(Trim(mAddress.value) == ""){
			alert("Please enter messenger address.");
			mAddress.focus();
			return;
		}else if(Trim(mEmail.value) != ""){
			if(!isValidMail(Trim(mEmail.value))){
				alert("Invalid email address.");
				mEmail.focus();
				return;
			}
		}
		fsignupmessenger.btnNext.disabled = true;
		fsignupmessenger.submit();
	}
</script>

<br>
<form name="fsignupmessenger" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="center" width="550">
			   <tr>
				 <td align="left" class="formtitle" height="18"><b>SIGN UP NEW MESSENGER</b></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td align="left">Salutation:</td>
						<td align="left" width="200" colspan="3">
							<select name="selSalutation" class="boxenabled" tabindex="1">
								<option value="Mr." <?php if($selSalutation == "Mr.") print "selected"?>>Mr.</option>
								<option value="Mrs." <?php if($selSalutation == "Mrs.") print "selected"?>>Mrs.</option>
								<option value="Miss." <?php if($selSalutation == "Miss.") print "selected"?>>Miss.</option>
								<option value="Dr." <?php if($selSalutation == "Dr.") print "selected"?>>Dr.</option>
							</select></td>
					</tr>	
					<tr>
						<td align="left">Name:</td>
					 	<td align="left" colspan="3"><input type="text" tabindex="2" name="txtName" class="boxenabled" size="76" maxlength="50" value="<?php print($txtName);?>" /><img src="./images/required.gif" border="0" /></td>
					</tr>					 
					<tr>
					 	<td align="left" valign="top">Address:</td>
						<td align="left" colspan="3"><textarea name="txtAddress" cols="58" rows="2" tabindex="3" class="boxenabled"><?php print($txtAddress);?></textarea><img src="./images/required.gif" border="0" /></td>
					</tr>
					<tr>
						<td align="left">Country:</td>
						<td align="left" colspan="3">
							<select name="selCountry" class="boxenabled" tabindex="3">
								<option value="38" selected="selected">Cambodia</option>	
								<?php
									$sql = "SELECT CountryID, Country from tlkpCountry order by Country";
									// sql 2005
									
									$que = $mydb->sql_query($sql);									
									if($que){
										while($rst = $mydb->sql_fetchrow($que)){	
											$CountryID = $rst['CountryID'];
											$Country = $rst['Country'];
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
					</tr>	
					<tr>						 	
						<td align="left">ID/Passport:</td>
						<td align="left"><input type="text" tabindex="4" name="txtPassport" class="boxenabled" size="30" maxlength="20" value="<?php print $txtPassport; ?>" /></td>
						<td align="left" nowrap="nowrap">Date of birth:</td>
						<td align="left"><input type="text" tabindex="5" name="txtDOB" class="boxenabled" size="12" maxlength="30" value="<?php print $txtDOB; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
							<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fsignupmessenger|txtDOB', '', 'width=200,height=220,top=250,left=350');">
								<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
							</button>(YYYY-MM-DD)
						</td>
					</tr>
					<tr>
					 	<td align="left">Phone:</td>
						<td align="left"><input type="text" name="txtPhone" value="<?php print $txtPhone; ?>" class="boxenabled" tabindex="49" size="30" onKeyUp="ValidatePhone(this);" onBlur="CheckPhone(this);" /></td>
						<td align="left">Fax:</td>
						<td align="left"><input type="text" name="txtFax" value="<?php print $txtFax; ?>" class="boxenabled" tabindex="49" size="27" onKeyUp="ValidatePhone(this);" onBlur="CheckPhone(this);" /></td>
					 </tr>
					 <tr>
					 	<td align="left">Email:</td>
						<td align="left" colspan="4"><input type="text" tabindex="8" name="txtEmail" class="boxenabled" size="30" maxlength="30" value="<?php print $txtEmail; ?>" /></td>
						
					 </tr>
					 <tr>
					 	<td align="left" valign="top">Comment:</td>
						<td align="left" colspan="3"><textarea name="txtComment" cols="58" rows="6" tabindex="9" class="boxenabled"><?php print($txtComment);?></textarea></td>
					</tr>
					 <tr> 				  
					  <td align="center" colspan="4">
							<input type="reset" tabindex="10" name="reset" value="Reset" class="button" />
							<input type="button" tabindex="11" name="btnNext" value="Submit" class="button" onClick="ValidateForm();" />						
						</td>
					 </tr>		
					 <?php
							if(isset($retOut) && (!empty($retOut))){
								print "<tr><td colspan=\"4\" align=\"left\">$retOut</td></tr>";
							}
						?>			
				   </table>
				 </td>
			   </tr>			   
			 </table>
			  <input type="hidden" name="pg" id="pg" value="21">
				<input type="hidden" name="smt" id="smt" value="yes">
	</form>
<br>&nbsp;
<?php
# Close connection
$mydb->sql_close();
?>
