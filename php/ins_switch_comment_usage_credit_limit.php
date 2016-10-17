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
			$curbook = $_GET['curbook'];
	
	// Store history 
	
	$sql="Insert into tblDebtUsage(AccID,Amount,CycleID,Description,Status,Date) values(".$accID.",1,".$CycleID.",'".$Description."',0,'".$dnow."')";
	$mydb->sql_query($sql);
	
	// 1- request to reconnect
	// 0- request to close
	
	if($Existing==0)	
		$sql="Insert into tblSwitchUsageStatusCreditLimit(AccID,Amount,CycleID,Description,Status,Date,swstatus,curbook) values(".$accID.",1,".$CycleID.",'".$Description."',0,'".$dnow."',0,".$curbook.")";
	else
		$sql="Update tblSwitchUsageStatusCreditLimit set swstatus=0,Date='".$dnow."',curbook=".$curbook." where AccID=".$accID." and CycleID=".$CycleID;
		
	$mydb->sql_query($sql);

?>

