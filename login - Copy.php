<?php
	session_start();
	include("./common/agent.php");
	include_once("./common/functions.php");
	#start session
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Pragma" content="no-cache" />

<link rel="stylesheet" type="text/css" href="style/style.css" />
<link rel="stylesheet" type="text/css" href="style/presentationCycle.css" />

<script type='text/javascript' src='javascript/jquery-1.4.1.min.js'></script>
<script type='text/javascript' src='javascript/jquery.cycle.all.min.js'></script>
<script type='text/javascript' src='javascript/presentationCycle.js'></script>
<script type="text/javascript" src="javascript/loading.js"></script>
<script type="text/javascript" language="javascript">
function resize(which, max) {
  var elem = document.getElementById(which);
  if (elem == undefined || elem == null) return false;
  
  elem.width = 600;
  
  elem.height = 200;
  
}
</script>
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
<body onLoad="frmAuth.username.focus();" style="margin:10px auto; padding:0 0 0 0;">
<form name="frmAuth" action="<?php print $PHP_SELF; ?>" method="post">
<ul style="list-style:none; display:inline-table; position:relative; width:940px; margin:0px auto;">
<li style="float:left; width:70%">
	<ul style="display:inline-table; position:relative; list-style:none;">
    	<li>
            <div class="container">
                <div id="presentation_container" class="pc_container">
                    <div class="pc_item">
                        <img src="photoes/6.jpg" alt="slide6" id="img6" onload="resize('img6')" />
                    </div>
                </div>
                
                <script type="text/javascript">
                    // presentationCycle.init();
                </script>
            
            </div>
        </li>
        <li>
        	<span style="font-size:18px; color:#09F"><strong>Why choose Camintel?</strong></span>
        </li>
        <li style="height:10px; margin-top:10px;">
        	<strong>
	        	Because Camintel have more services to serve you
            </strong>
        </li>
        <li>
        	<ul style="padding-top:15px; list-style-position:inside;">
                <li style="padding:5px;">
                	Fixed Phone
                </li>
                <li style="padding:5px;">
                	Internet
                </li>
                <li style="padding:5px;">
                	Lease Line
                </li>
                <li style="padding:5px;">
                	ISDN
                </li>
                <li style="padding:5px;">
                	Easy Phone
                </li>
                <li style="padding:5px;">
                	Access Card
                </li>
                <li style="padding:5px;">
                	Webkey Card 
                </li>
                <li style="padding:5px;">
                	Audio Text Service 
                </li>
            </ul>
        </li>
	</ul>
</li>
<li style="float:left; width:30%">
				<div class="second yregbx">
                        <span class="ct"><span class="cl"></span></span>
                       
                         <h3 align="center">Sign In to Billing System!</h3>
                       
                         <table cellspacing="0" cellpadding="0" summary="form: login information" id="yreglgtb" align="center">
                                <tbody>
                                <tr height="25px">
                                        <td></td>
                                </tr>
                                <tr align="left">
                                        <td><label for="username"><strong>User Name:</strong></label></td></tr>
                                <tr align="left">
                                        <td width="100%"><input type="text" tabindex="1" name="username" size="30" width="250px"><br /><span>(e.g: camintel)</span>
                						</td>
                                </tr>
                                <tr height="10px">
                                        <td></td>
                                </tr>
                                <tr align="left">
                                        <td><label for="passwd"><strong>Password:</strong></label></td></tr>
                                <tr align="left">
                                        <td width="100%"><input type="password" tabindex="2" name="password" size="32" width="250px"></td>
                                </tr>
                                <tr align="center" height="10px">
                                        <td></td>
                                </tr>
                                <tr align="center">
                                        <td><input type="submit" tabindex="3" name="submit" value="Sign in"></td>
                                </tr>
                                <tr height="15px">
                                        <td></td>
                                </tr>
                                <tr>
                                        <td align="center"><a href="forget.php">I can't access my account</a></td>
                                </tr>
                                <tr height="5px">
                                        <td></td>
                                </tr>
                                <tr>
                                	<td>    
										<p id="sigcopys"><span class="kmsibold">Please click <strong>NO Button</strong> in Remember <br>  Password Messagebox , If it appears <br> when you are logging.</span>
    								    </p>                                    
    								</td>
                                </tr>
                            	<tr>
                                  <td><font color="red"><?php print $retOut;?></font></td>   
                            	</tr>
                          		<tr>
                            		<td id="fun">
                            	</td>
                        		</tr>
								</tbody>
                			</table>                         
                            
                        <span class="cb"><span class="cl"></span></span>
                </div>
                <div class="second yregbx">
                        <span class="ct"><span class="cl"></span></span>
                        <div class="yregbxi">
                         <h3 align="center">Note !</h3>
                         </div>
                         <table cellspacing="0" cellpadding="0" summary="form: login information" id="yreglgtb" align="center">
                                <tbody>
                                <tr>
                                	<td>    
										<p id="sigcopys"><span class="kmsibold">During 4 hours, Users have to relog <br>in to Billing System if they want to go <br>on using System.</span>
    								    </p>   
                                        <p id="sigcopys1"><span class="kmsibold">One user can't log in to Camintel Billing<br> System in multi computers at the same<br> time.</span>
    								    </p>                                    
    								</td>
                                </tr>
								</tbody>
                			</table>                         
                            
                        <span class="cb"><span class="cl"></span></span>
                </div>
</li>
</ul>       
				 </form>
</body>
</html>

