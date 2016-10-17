<?php
	require_once("../common/agent.php");
	$PackageID = $_GET['PackageID'];
	if(empty($PackageID) || ($PackageID == ""))
		print "";
	else{
		$sql = "select ServiceID from tblTarPackage where PackageID=".$PackageID."";
		$que = $mydb->sql_query($sql);
		if($que)
		{
			$result = $mydb->sql_fetchrow($que);
			print $result['ServiceID'];
		}
		else
			print "";
		$mydb->sql_close();	
	}
		
?>