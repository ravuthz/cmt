<?php


	require_once("../common/agent.php");
	$username = $_GET['username'];
	if(empty($username) || ($username == ""))
		print "0";
	else{
		$sql = "select accid from tblCustProduct where StatusID <> 4 and UserName='$username' and UserName not in (select AccName from tblException)";
		$que = $mydb->sql_query($sql);
		if($mydb->sql_numrows()>0)
		{
			$result = $mydb->sql_fetchrow($que);
			print $result['accid'];
		}
		else
			print "0";
		$mydb->sql_close();	

	}
    
		
?>