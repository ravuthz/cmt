<?php
	require_once("./common/agent.php");	
	require_once("./common/functions.php");
	require_once("./common/class.audit.php");	
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
		
	$mid = FixQuotes($mid);
	if(!empty($smt) && isset($smt) && ($smt == "save238")){
		$selSalutation = FixQuotes($selSalutation);
		$txtName = FixQuotes($txtName);
		$txtAddress = FixQuotes($txtAddress);
		$selCountry = FixQuotes($selCountry);
		$txtPassport = FixQuotes($txtPassport);
		$txtDOB = FixQuotes($txtDOB);
		$txtPhone = FixQuotes($txtPhone);
		$txtFax = FixQuotes($txtFax);
		$txtEmail = FixQuotes($txtEmail);
		$statusid = FixQuotes($statusid);
		$txtComment = FixQuotes($txtComment);
		
		$sql = "UPDATE tlkpMessenger SET 
							Salutation = '".$selSalutation."',
							Name = '".$txtName."',
							Address = '".$txtAddress."',
							Country = '".$selCountry."',
							Phone = '".$txtPhone."',
							Fax = '".$txtFax."',
							Email = '".$txtEmail."',
							Passport = '".$txtPassport."',
							DOB = '".$txtDOB."',
							Remark = '".$txtComment."',
							statusid = '".$statusid."'
						WHERE MessengerID = $mid
						";
		if($mydb->sql_query($sql)){
			$Operator = $user['FullName'];
			$now = date("Y/M/d H:i:s"); 
			$audit = new Audit();
			
			$title = "Edit messenger";
			$description = "Edit messenger id: $mid to; Name: $selSalutation $txtName; DOB: $txtDOB; phone: $txtPhone; fax: $txtFax; email: $txtEmail; duplicate: $txtPassport; address: $txtAddress, $selCountry.";

			$audit->AddAudit("", "", $title, $description, $Operator, 1, 1);
			redirect('./?pg=228');
		}
	}
	
	$sql = "SELECT * FROM tlkpMessenger WHERE MessengerID = $mid";
	if($que = $mydb->sql_query($sql)){
		if($result = $mydb->sql_fetchrow($que)){
			$Salutation = $result['Salutation'];
			$Name = $result['Name'];
			$Address = $result['Address'];
			$dbCountry = $result['Country'];
			$Phone = $result['Phone'];
			$Fax = $result['Fax'];
			$Email = $result['Email'];
			$Passport = $result['Passport'];
			$DOB = $result['DOB'];
			$Remark = $result['Remark'];
			$statusid = $result['statusid'];
		}
	}
?>	
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/validphone.js"></script>
<script language="javascript">
	function ValidateForm(){

		mName = feditmessenger.txtName;
		mAddress = feditmessenger.txtAddress;
		mPassport = feditmessenger.txtPassport;
		mPhone = feditmessenger.txtPhone;
		mFax = feditmessenger.txtFax;
		mEmail = feditmessenger.txtEmail;
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
		
		feditmessenger.smt.value= "save238";
		feditmessenger.btnNext.disabled = true;
		feditmessenger.submit();
	}
</script>
<form name="feditmessenger" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="center" width="550">
	 <tr>
	 <td align="left" class="formtitle" height="18"><b>EDIT MESSENGER [<?php print $Name; ?>]</b></td>
	 </tr>
	 <tr>
	 <td valign="top">
		 <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
		 
		 <tr>
			<td align="left">Salutation:</td>
			<td align="left" width="200" colspan="3">
				<select name="selSalutation" class="boxenabled" tabindex="1">
					<option value="Mr." <?php if($Salutation == "Mr.") print "selected"?>>Mr.</option>
					<option value="Mrs." <?php if($Salutation == "Mrs.") print "selected"?>>Mrs.</option>
					<option value="Miss." <?php if($Salutation == "Miss.") print "selected"?>>Miss.</option>
					<option value="Dr." <?php if($Salutation == "Dr.") print "selected"?>>Dr.</option>
				</select></td>
		</tr>	
		<tr>
			<td align="left">Name:</td>
			<td align="left" colspan="3"><input type="text" tabindex="2" name="txtName" class="boxenabled" size="76" maxlength="50" value="<?php print($Name);?>" /><img src="./images/required.gif" border="0" /></td>
		</tr>					 
		<tr>
			<td align="left" valign="top">Address:</td>
			<td align="left" colspan="3"><textarea name="txtAddress" cols="58" rows="2" tabindex="3" class="boxenabled"><?php print($Address);?></textarea><img src="./images/required.gif" border="0" /></td>
		</tr>
		<tr>
			<td align="left">Country:</td>
			<td align="left" colspan="3">
				<select name="selCountry" class="boxenabled" tabindex="3">					
					<?php
						$sql = "SELECT CountryID, Country from tlkpCountry order by Country";
						// sql 2005
						
						$que = $mydb->sql_query($sql);									
						if($que){
							while($rst = $mydb->sql_fetchrow($que)){	
								$CountryID = $rst['CountryID'];
								$Country = $rst['Country'];
								if($dbCountry == $CountryID) 
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
			<td align="left"><input type="text" tabindex="4" name="txtPassport" class="boxenabled" size="30" maxlength="20" value="<?php print $Passport; ?>" /></td>
			<td align="left" nowrap="nowrap">Date of birth:</td>
			<td align="left"><input type="text" tabindex="5" name="txtDOB" class="boxenabled" size="12" maxlength="30" value="<?php print formatDate($DOB, 5); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
				<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?feditmessenger|txtDOB', '', 'width=200,height=220,top=250,left=350');">
					<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
				</button>(YYYY-MM-DD)
			</td>
		</tr>
		<tr>
			<td align="left">Phone:</td>
			<td align="left"><input type="text" name="txtPhone" value="<?php print $Phone; ?>" class="boxenabled" tabindex="49" size="30" onKeyUp="ValidatePhone(this);" onBlur="CheckPhone(this);" /></td>
			<td align="left">Fax:</td>
			<td align="left"><input type="text" name="txtFax" value="<?php print $Fax; ?>" class="boxenabled" tabindex="49" size="27" onKeyUp="ValidatePhone(this);" onBlur="CheckPhone(this);" /></td>
		 </tr>
		 <tr>
			<td align="left">Email:</td>
			<td align="left" colspan="4"><input type="text" tabindex="8" name="txtEmail" class="boxenabled" size="30" maxlength="30" value="<?php print $Email; ?>" /></td>
			
		 </tr>
		 <tr>
		 	<td align="left">Status:</td>
			<td align="left">
				<select name="statusid">
					<option value="1" <?php if(intval($statusid) == 1) print "selected"; ?>>Enable</option>
					<option value="0" <?php if(intval($statusid) == 0) print "selected"; ?>>Disable</option>
				</select>
			</td>
		 </tr>
		 <tr>
			<td align="left" valign="top">Comment:</td>
			<td align="left" colspan="3"><textarea name="txtComment" cols="58" rows="6" tabindex="9" class="boxenabled"><?php print($Remark);?></textarea></td>
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
 	<input type="hidden" name="mid" value="<?php print $mid; ?>" />
	<input type="hidden" name="pg" id="pg" value="238" />
	<input type="hidden" name="smt" id="smt" value="" />
</form>
<br>&nbsp;
<?php
# Close connection
$mydb->sql_close();
?>