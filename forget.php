<?php
	session_start();
	include("./common/agent.php");
	require_once("./common/class.security.php");
	include_once("./common/functions.php");
	#start session
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="keywords" content="BRC Technology" />
<meta name="reply-to" content="supports@brc-tech.com" />
<link type="text/css" rel="stylesheet" href="style/mystyle.css" />
<script language="JavaScript" src="javascript/loading.js"></script>
<title>..:: Wise Biller ::..</title>
</head>
<?php
	
	if(isset($submit)){
		print '<script language=javascript>
				var ShowInProcess = new ShowHideProcess("<table width=800 height=460 border=0 cellspacing=0 cellpadding=100><tr><td 	align=center><p>';
		print "<embed src='./images/process.swf'></embed>";			
         

		print '</p></td></tr></table>");
				ShowInProcess.Show();
				</script>';
				
		if(!empty($smt) && isset($smt) && ($smt == "savepass")){
			$username = FixQuotes($username);
			$email = FixQuotes($email);
			$Security = new Security();
			$retOut = $Security->RetrievePassword($username, $email);
			if(is_bool($retOut)){
				$retOut = "your password has been reset and sent to $email.<br><br>
									 <a href='login.php'>Click here to login again</a>
									";
			}
			//// get userid from username and email provided
//			$sql = "SELECT UserID FROM tblSecUser WHERE UserName = '".$username."' AND EmailAddress = '".$email."'"
//			if($que = $mydb->sql_query($sql)){
//				if($result = $mydb->sql_fetchrow($que)){
//					$userid = $result['UserID'];
//						
//				}else{
//					$retOut = $myinfo->error("Failed to get user information from provided information.");	
//				}
//			}else{
//				$error = $mydb->sql_error();
//				$retOut = $myinfo->error("Failed to get user information from provided information.", $error['message']);
//			} 
			
		}			
		echo '<script language=javascript>
						ShowInProcess.Hide();
					</script>';	
	}
?>
<body onLoad="frmAuth.username.focus();">
 <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="75%">
   <tr>
     <td valign="middle">
	   <table border="0" cellpadding="2" cellspacing="0" width="350" height="200" align="center" class="formbg">
		   <tr>
			 <td align="left" class="formtitle" height="18"><b>RETRIEVE NEW PASSWORD</b></td>
		   </tr>
		   <tr>
			 <td valign="top" height="150">
			 	<form name="frmAuth" action="<?php print $PHP_SELF; ?>" method="post">
			   <table border="0" cellpadding="5" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
				 <tr><td height="15" style="border-top:1px solid #334455" colspan="2">&nbsp;</td></tr>
				 <tr>
				   <td align="left" nowrap="nowrap">User name:</td>
				   <td align="left"><input type="text" tabindex="1" name="username" size="35" class="boxenabled"></td>
				 </tr>
				 <tr>
				   <td>Email:</td>
				   <td align="left"><input type="text" tabindex="2" name="email" size="35" class="boxenabled"></td>
				 </tr>
				 <tr> 
				  <td>&nbsp;</td>
				  <td align="left">
						<input type="submit" tabindex="3" name="submit" value="Get password" class="button">					
					</td>
				 </tr>
				 <tr><td height="30%" align="left" colspan="2"><?php print $retOut;?></td></tr>
			   </table>
				 <input type="hidden" name="smt" value="savepass" />
				 </form>
			 </td>
		   </tr>
		   <tr>
			 <td align="left" height="50">
				<p style="padding-left:10px">
					<font size="-2">
					<b>Wise Biller System<br>
					Powered by: <a href="http://www.brc-tech.com" target="_blank"><b>BRC Technology</b></a> © 2006
				</font>
				</p>				
			 </td>
		   </tr>
		 </table>
	 </td>
   </tr>
 </table>
</body>
</html>

