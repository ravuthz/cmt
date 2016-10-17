<?php	
	session_start();
	include_once("agent.php");
	include_once("functions.php");
		#	delete expired session time
		$now = date("YmdHis");
		$sqldelete = "DELETE FROM tblSecSession WHERE ExpiredTime <= \"$now\"";

		$mydb->sql_query($sqldelete);
		
		#session control
		
		$sid = $_SESSION["sessionid"];
		
		#	Get user information
		if(isset($sid) && !empty($sid)){
			$sqlinfo = "SELECT s.UserID as userid, s.UserName, s.FullName
									FROM tblSecUser s INNER JOIN tblSecSession ss ON s.UserID = ss.UserID																
									WHERE ss.SessionID = \"$sid\" AND s.Status = 1";

			$queinfo = $mydb->sql_query($sqlinfo);

			if($mydb->sql_numrows($queinfo) > 0){
				$user = $mydb->sql_fetchrow($queinfo);							
			}else{
				redirect("login.php");
			}			
		}else{
				redirect("login.php");
		}
?>