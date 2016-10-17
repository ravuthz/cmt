<?php
	require_once("./common/agent.php");	
	require_once("./common/functions.php");
	require_once("./common/class.security.php");
	require_once("./common/class.audit.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
		
?>
<script language="JavaScript" src="./javascript/validphone.js"></script>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="javascript">
function showEdit(){
	document.getElementById("d-userprofile").style.display = "block";
}
function checkPass(pass, conpass){
	if(pass == conpass)
		return true;
	else
		return false;
}
function validateForm(){
	username = fedituserprofile.txtUserName;
	password = fedituserprofile.txtPassword;
	txtConfirm = fedituserprofile.txtConfirm;
	txtEmail = fedituserprofile.txtEmail;
	if(Trim(username.value) == ""){
		alert("please enter user name to log in to the system");
		username.focus();
		return;
	}else if(Trim(password.value) != ""){
		if(password.value.length < 6){
			alert("Password must be at least 6 characters length.");
			password.focus();
			return;
		}else if(!checkPass(Trim(password.value), Trim(txtConfirm.value))){
			alert("Password and confirm password must be the same.");
			password.focus();
			return;
		}
	}
	if(Trim(txtEmail.value) != ""){
		if(!isValidMail(Trim(txtEmail.value))){
			alert("Invalid email address.");
			txtEmail.focus();
			return;
		}
	}
	fedituserprofile.btnSubmit.disabled = true;
	fedituserprofile.smt.value = "savecp";
	fedituserprofile.submit();
}
</script>	
<?php
	
	if(isset($smt) && (!empty($smt)) && ($smt == "savecp") &&  ($pg == 905)){
		$txtUserName = FixQuotes($txtUserName);
		$txtPassword = FixQuotes($txtPassword);
		$txtDOB = FixQuotes($txtDOB);
		$txtTelephone = FixQuotes($txtTelephone);
		$txtEmail = FixQuotes($txtEmail);
		$txtDescription = FixQuotes($txtDescription);
		
		$Security = new Security();		
		// edit username / password
		$retOut = $Security->ChageUserNamePass($user['userid'], $txtUserName, $txtPassword);
		if(is_bool($retOut)){
			// edit user information
			$retOut = $Security->UpdateUserProfile($user['userid'], $txtEmail, $txtTelephone, $txtDOB, $txtDescription);
			if(is_bool($retOut)){
				$audit = new Audit();
				$description = "Edit user $txtFullName profile".	
				$audit->AddAudit("", "", "Edit user $txtFullName", $description, $user['FullName'], 1, 14);
				$retOut = $myinfo->info("Successful save changed user $txtFullName.");
			}
		}
	}
	
	$sql = "SELECT UserName, FullName, Status, EmailAddress, Telephone, DOB, Description
					FROM tblSecUser WHERE UserID = ".$user['userid'];
	if($que = $mydb->sql_query($sql)){
		if($result = $mydb->sql_fetchrow($que)){
			$pUserName = $result['UserName'];
			$pFullName = $result['FullName'];
			$pStatus = $result['Status'];
			$pEmailAddress = $result['EmailAddress'];
			$pTelephone = $result['Telephone'];
			$pDOB = $result['DOB'];
			$pDescription = $result['Description'];
			if($pStatus)
				$stStatus = "Active";
			else
				$stStatus = "Suspend";
		}
	}else{
		$error = $mydb->sql_error();
		$retOut = $myinfo->error("Failed to get user information.", $error['message']);
	}
?>
<table border="0" cellpadding="3" cellspacing="0" align="left" width="100%">
	<tr>
		<td>
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>User profile</b></td>
					<td align="right">[<a href="javascript:showEdit();">Edit</a>]</td>
				</tr>
				<tr>
					<td valign="top" colspan="2">
						<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">												
							<tr>
								<td align="left" nowrap="nowrap">User name:</td>
								<td align="left"><b><?php print $pUserName;?></b></td>					
							</tr>
							<tr>					
								<td align="left" nowrap="nowrap">Full name:</td>
								<td align="left" width="80%"><b><?php print $pFullName;?></b></td>																										
							</tr>
							<tr>					
								<td align="left" nowrap="nowrap">Status:</td>
								<td align="left"><b><?php print $stStatus;?></b></td>																										
							</tr>
							<tr>					
								<td align="left" nowrap="nowrap">Date of birth:</td>
								<td align="left"><b><?php print formatDate($pDOB, 6); ?></b></td>
							</tr>												
							<tr>
								<td align="left" nowrap="nowrap">Telephone:</td>
								<td align="left"><b><?php print $pTelephone;?></b></td>					
							</tr>
							<tr>
								<td align="left" nowrap="nowrap">Email:</td>
								<td align="left"><b><?php print $pEmailAddress;?></b></td>					
							</tr>
							<tr>
								<td align="left" nowrap="nowrap" valign="top">Description:</td>
								<td align="left"><b><?php print $pDescription;?></b></td>					
							</tr>												
						</table>
					</td>
				</tr>										
			</table>		
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<?php
		if(isset($retOut) && (!empty($retOut))){
			print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
		}
	?>
	<tr>
		<td align="left">
			<div id="d-userprofile" style="display:none">
			<form name="fedituserprofile" method="post" action="./" />
				<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
					<tr>
						<td align="left" class="formtitle"><b>Edit user profile</b></td>
						<td align="right"></td>
					</tr>
					<tr>
						<td valign="top" colspan="2">
							<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">
								<tr>
									<td align="left">User name:</td>
									<td align="left">
										<input type="text" name="txtUserName" class="boxenabled" value="<?php print trim($pUserName);?>" size="50" />
									</td>
								</tr>
								
								<tr>
									<td align="left">Password:</td>
									<td align="left">
										<input type="password" name="txtPassword" class="boxenabled" value="" size="25" maxlength="20" />
										<font size="-2" color="red">Leave it blank if you do not change password</font>
									</td>
								</tr>
								<tr>
									<td align="left" nowrap="nowrap">Confirm password:</td>
									<td align="left">
										<input type="password" name="txtConfirm" class="boxenabled" value="" size="25" maxlength="20" />
									</td>
								</tr>
								<tr>
									<td align="left" nowrap="nowrap">Date of birth:</td>
									<td align="left"><input type="text" tabindex="4" name="txtDOB" class="boxenabled" size="27" maxlength="30" value="<?php print formatDate($pDOB, 7);?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
										<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fedituserprofile|txtDOB', '', 'width=200,height=220,top=250,left=350');">
											<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
										</button>
									</td>
								</tr>
								<tr>
									<td align="left" nowrap="nowrap">Telephone:</td>
									<td align="left">
										<input type="text" name="txtTelephone" class="boxenabled" value="<?php print trim($pTelephone);?>" size="50" onKeyUp="ValidatePhone(this);" onBlur="CheckPhone(this);"/>
									</td>
								</tr>
								<tr>
									<td align="left" nowrap="nowrap">Email:</td>
									<td align="left">
										<input type="text" name="txtEmail" class="boxenabled" value="<?php print trim($pEmailAddress);?>" size="50" />
									</td>
								</tr>
								<tr>
									<td align="left" nowrap="nowrap" valign="top">Description:</td>
									<td align="left">
										<textarea name="txtDescription" cols="50" rows="5" class="boxenabled"><?php print trim($pDescription); ?></textarea>
									</td>
								</tr>
								<tr>
									<td align="center" colspan="2">
										<input type="reset" name="reset" value="Reset" class="button" />
										<input type="button" name="btnSubmit" value="Save" class="button" onClick="validateForm();" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			<input type="hidden" name="smt" value="" />	
			<input type="hidden" name="pg" value="905" />
			</form>
			</div>
		</td>
	</tr>	
</table>
	
							
<?php
# Close connection
$mydb->sql_close();
?>