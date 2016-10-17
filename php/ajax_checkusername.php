<?php
	require_once("../common/agent.php");
	$username = $_GET['username'];
	if(empty($username) || ($username == ""))
		print "0";
	else{
		$sql = "select * from tblCustProduct where StatusID not in (4) and ltrim(rtrim(UserName))=ltrim(rtrim('".$username."'))";
		$que = $mydb->sql_query($sql);
		if($mydb->sql_numrows()>0)
			print "1";
		else
			print "0";
		$mydb->sql_close();	
	}
		
?>