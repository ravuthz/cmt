<?php
	session_start();
	include("./common/agent.php");
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
				
		if((isset($username)) && (isset($password))){
			$username = FixQuotes($username);
			$password = FixQuotes($password);
			#	Encrypt password
			$password = md5($password);
			#	Get password from database
			$sqlGetPass = "SELECT UserID, password, FullName FROM tblSecUser WHERE UserName=\"$username\" AND status = 1";

			$queGetPass = $mydb->sql_query($sqlGetPass);
			$rstGetPass = $mydb->sql_fetchrow($queGetPass);
			$dbPass = $rstGetPass['password'];
			$UserID = $rstGetPass['UserID'];
			$FullName = $rstGetPass['FullName'];

			if($password == $dbPass){				
				# Get sessionid
				$now = date("Ymdhis");
				$sessionid = uniqid(md5($now));
				$SessionTimeOut = intval(getConfigue("Session time out"));
				//$expired = intval(date("Ymdhis")) + 40000;
				$expired = date("YmdHis", mktime(date("H")+$SessionTimeOut, date("i"), date("s"), date("m"), date("d"), date("Y")));
				# Does not allow one user to login many time in one session
				#	Log out so we delete session data					
				$sqldelete = "DELETE FROM tblSecSession WHERE UserID=\"$UserID\"";
				$mydb->sql_query($sqldelete); 
				#	Register user to database
				$sqlInsert = "INSERT INTO tblSecSession(UserID, SessionID, ExpiredTime) VALUES($UserID, \"$sessionid\", \"$expired\")";	
				if($mydb->sql_query($sqlInsert))				{					
					$_SESSION["sessionid"] = $sessionid;
					$_SESSION["UserID"] = $UserID;
					$_SESSION["FullName"]	= $FullName;
					$_SESSION["Login"] = $username;
					redirect("index.php");		
					//header("Location: $SERVERROOT");
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to register session.", $error['message']);
				}
			}else{				
				$retOut = $myinfo->warning("Invalid username or password.");
			}
		}			
		echo '<script language=javascript>
						ShowInProcess.Hide();
					</script>';	
	}else{
		#	Log out so we delete session data
		$sid = $_SESSION["sessionid"];
		$sqldelete = "DELETE FROM tblSecSession WHERE sessionid=\"$sid\"";
		$mydb->sql_query($sqldelete);
	}
?>
<body onLoad="frmAuth.username.focus();">
 <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="75%">
   <tr>
     <td valign="middle">
	   <table border="0" cellpadding="2" cellspacing="0" width="350" height="200" align="center" class="formbg">
		   <tr>
			 <td align="left" class="formtitle" height="18"><b>LOGIN AUTHENTICATION</b></td>
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
				   <td>Password:</td>
				   <td align="left"><input type="password" tabindex="2" name="password" size="35" class="boxenabled"></td>
				 </tr>
				 <tr> 
				  <td>&nbsp;</td>
				  <td align="left">
						<input type="submit" tabindex="3" name="submit" value="Log in" class="button">
						<a href="forget.php">Forget password</a>
					</td>
				 </tr>
				 <tr><td height="30%" align="left" colspan="2"><font color="red"><?php print $retOut;?></font></td></tr>
			   </table>
				 </form>
			 </td>
		   </tr>
		   <tr>
			 <td align="left" height="50">
				<p style="padding-left:10px">
					<font size="-2">
					
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

