<?php
	
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
			$accID = $_GET['accID'];
			$CycleID = $_GET['cycleID'];
			$dnow = date('Y-m-d H:i:s',strtotime("-1 hours"));
			$comm = $_GET['comm'];
	
	$sql="Insert into tblpcocomment(AccID,cycleID,Comment,csdate) values(".$accID.",".$CycleID.",'".$comm."','".$dnow."')";
	$mydb->sql_query($sql);

?>

