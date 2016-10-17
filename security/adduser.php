<?php
	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");
	require_once("./common/class.security.php");
	require_once("./common/functions.php");
/*
	+ ************************************************************************************** +	
	*																																												 *
	* This code is not to be distributed without the written permission of BRC Technology.   *
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
	* 																																											 *
	+ ************************************************************************************** +
*/
		
	if(isset($smt) && (!empty($smt)) && ($smt == "save903") &&  ($pg == 903)){
			
			$now = date("Y/M/d H:i:s"); 
			$txtName = FixQuotes($txtName);
			$txtFullName = FixQuotes($txtFullName);
			$txtPass = FixQuotes($txtPass);
			$txtDOB = FixQuotes($txtDOB);
			$txtPhone = FixQuotes($txtPhone);
			$txtEmail = FixQuotes($txtEmail);
			$txtComment = FixQuotes($txtComment);
			# if username exist
			//$sql = "SELECT * FROM tblSecuser WHERE UserName = '".$txtName."' or FullName = '".$txtFullName."'";
//			if($que = $mydb->sql_query($sql)){
//				if($mydb->sql_numrows() > 0){					
//					$retOut = $myinfo->error("User name or full name is already exist in database.", $error['message']);
//				}else{
//					$txtPass = md5($txtPass);
//					$sql = "INSERT INTO tblSecuser(UserName, Password, FullName, Status, EmailAddress, 
//															Telephone, DOB, Description, CreatedDate)
//													VALUES('".$txtName."', '".$txtPass."', '".$txtFullName."', 1, '".$txtEmail."',
//																 '".$txtPhone."', '".$txtDOB."', '".$txtComment."', '".$now."')"; 
//					if($mydb->sql_query($sql)){
//						$audit = new Audit();
//						$description = "Create new user $txtFullName to access to the system".	
//						$audit->AddAudit("", "", "Create user $txtFullName", $description, $user['FullName'], 1, 14);
//						$retOut = $myinfo->info("Create new user $txtFullName to access to the system.");
//					}else{
//						$error = $mydb->sql_error();
//						$retOut = $myinfo->error("Failed to create user $txtFullName.", $error['message']);
//					}
//				}
//			}else{
//				$error = $mydb->sql_error();
//				$retOut = $myinfo->error("Failed to check user in database.", $error['message']);
//			}	
			$Security = new Security();
			$retOut = $Security->CreateUser($txtName, $txtPass, $txtFullName, $txtEmail, $txtPhone, $txtDOB, $txtComment);
			if(is_bool($retOut)){
				$audit = new Audit();
				$description = "Create new user $txtFullName to access to the system".	
				$audit->AddAudit("", "", "Create user $txtFullName", $description, $user['FullName'], 1, 14);
				$retOut = $myinfo->info("Create new user $txtFullName to access to the system.");
			}
		}											
?>
<script language="JavaScript" src="./javascript/validphone.js"></script>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="javascript">
	function checkPass(pass, conpass){
		if(pass == conpass)
			return true;
		else
			return false;
	}
	function ValidateForm(){

		txtName = fcreateuser.txtName;
		txtFullName = fcreateuser.txtFullName;
		txtPass = fcreateuser.txtPass;
		txtConPass = fcreateuser.txtConPass;
		txtEmail = fcreateuser.txtEmail;
		
		if(Trim(txtName.value) == ""){
			alert("Please enter user name.");
			txtName.focus();
			return;
		}else if(Trim(txtFullName.value) == ""){
			alert("Please enter user full name.");
			txtFullName.focus();
			return;
		}else if(txtPass.value == ""){
			alert("Please enter password.");
			txtPass.focus();
			return;
		}else if(txtPass.value.length < 6){
			alert("Password must be at least 6 characters length.");
			txtPass.focus();
			return;
		}else if(!checkPass(txtPass.value, txtConPass.value)){
			alert("Password and confirm password must be the same.");
			txtPass.focus();
			return;
		}else if(Trim(txtEmail.value) != ""){
			if(!isValidMail(Trim(txtEmail.value))){
				alert("Invalid email address.");
				txtEmail.focus();
				return;
			}
		}
		fcreateuser.btnNext.disabled = true;
		fcreateuser.submit();
	}
</script>

<form name="fcreateuser" method="post" action="./">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="150">
			<?php include("content.php"); ?>
		</td>
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="center" width="100%">
			  <tr>
				 <td align="left" class="formtitle" height="18"><b>ADD NEW USER</b></td>
			   </tr>
			  <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">					 					 
					<tr>
						<td align="left">User name:</td>
					 	<td align="left">
							<input type="text" tabindex="1" name="txtName" class="boxenabled" size="50" maxlength="50" value="" />
								<img src="./images/required.gif" border="0" />
						</td>
					</tr>					 
					<tr>
					 	<td align="left" valign="top">Full name:</td>
						<td align="left">
							<input type="text" tabindex="2" name="txtFullName" class="boxenabled" size="50" maxlength="50" value="" />
								<img src="./images/required.gif" border="0" />
						</td>
					</tr>
					<tr>
						<td align="left">Password:</td>
						<td align="left">
							<input type="password" tabindex="3" name="txtPass" class="boxenabled" size="50" maxlength="20" value="" />
								<img src="./images/required.gif" border="0" />
						</td>
					</tr>
					<tr>
						<td align="left" nowrap="nowrap">Confirm password:</td>
						<td align="left">
							<input type="password" tabindex="3" name="txtConPass" class="boxenabled" size="50" maxlength="20" value="" />
								<img src="./images/required.gif" border="0" />
						</td>
					</tr>
					<tr>						 	
						
						<td align="left" nowrap="nowrap">Date of birth:</td>
						<td align="left"><input type="text" tabindex="4" name="txtDOB" class="boxenabled" size="27" maxlength="30" value="" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
							<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fcreateuser|txtDOB', '', 'width=200,height=220,top=250,left=350');">
								<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
							</button>
						</td>
					</tr>
					<tr>
					 	<td align="left">Phone:</td>
						<td align="left"><input type="text" name="txtPhone" value="" class="boxenabled" tabindex="5" size="30" onKeyUp="ValidatePhone(this);" onBlur="CheckPhone(this);" /></td>						
					 </tr>
					 <tr>
					 	<td align="left">Email:</td>
						<td align="left">
							<input type="text" tabindex="6" name="txtEmail" class="boxenabled" size="50" maxlength="30" value="" /></td>
						
					 </tr>
					 <tr>
					 	<td align="left" valign="top">Comment:</td>
						<td align="left">
							<textarea name="txtComment" cols="58" rows="6" tabindex="7" class="boxenabled"></textarea>
						</td>
					</tr>
					 <tr> 				  
					  <td align="center" colspan="2">
							<input type="reset" tabindex="8" name="reset" value="Reset" class="button" />
							<input type="button" tabindex="9" name="btnNext" value="Submit" class="button" onClick="ValidateForm();" />						
						</td>
					 </tr>		
					 <?php
							if(isset($retOut) && (!empty($retOut))){
								print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
							}
						?>			
				   </table>
				 </td>
			   </tr>			   
			 </table>
			</td>
		</tr>
	</table>
			  <input type="hidden" name="pg" id="pg" value="903">
				<input type="hidden" name="smt" id="smt" value="save903">
	</form>
<br>&nbsp;
<?php
# Close connection
$mydb->sql_close();
?>
