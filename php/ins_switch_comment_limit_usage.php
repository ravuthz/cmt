<?php
	
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
			$accID = $_GET['accID'];
			$Status = $_GET['Status'];
			$Amount = $_GET['Amount'];
			$CycleID = $_GET['CycleID'];
			$dnow = date('Y-m-d H:i:s',strtotime("-1 hours"));
			$Existing = $_GET['Existing'];
			$Description = $_GET['Description'];
	
	// Store history 
	
	$sql="Insert into tblDebtUsage(AccID,Amount,CycleID,Description,Status,Date) values(".$accID.",1,".$CycleID.",'".$Description."',0,'".$dnow."')";
	$mydb->sql_query($sql);
	
	// 1- request to reconnect
	// 0- request to close
	
	if($Existing==0)	
		$sql="Insert into tblSwitchUsageLimitStatus(AccID,Amount,CycleID,Description,Status,Date,swstatus) values(".$accID.",1,".$CycleID.",'".$Description."',0,'".$dnow."',0)";
	else
		$sql="Update tblSwitchUsageLimitStatus set swstatus=0,Date='".$dnow."' where AccID=".$accID." and CycleID=".$CycleID;
		
	$mydb->sql_query($sql);

?>

