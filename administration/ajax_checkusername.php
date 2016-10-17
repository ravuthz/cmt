<?php
	require_once("../common/agent.php");
	$username = $_GET['username'];
	if(empty($username) || ($username == ""))
		print "0";
	else{
		$sql = "select * from tblreportlist rl join tblCustProduct cp on rl.name = cp.accid 
				where StatusID <> 4 and rl.UserName like '%".$username."%'";
		$que = $mydb->sql_query($sql);
		if($mydb->sql_numrows()>0)
			print "1";
		else
			print "0";
		$mydb->sql_close();	
	}
		
?>