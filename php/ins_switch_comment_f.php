<?php

	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
			$phone = $_GET['phone'];
			$InvoiceID = $_GET['invoiceID'];
			$remark = $_GET['remark'];
			$ac = $_GET['ai'];
			$dnow = $_GET['dp'];

						
	$sql="Insert into tblDebtRemark_f values($InvoiceID,'$phone','$remark',1)";
	$mydb->sql_query($sql);
	
	$sql="update tblCustProduct set statusID=4,AccountEndDate=getdate(),Track='Close' where accID=$ac";
	$mydb->sql_query($sql);


	$sql="update tblTrackAccount set statusID=4,AccountEndDate=getdate(),Track='Close' where accID=$ac";
	$mydb->sql_query($sql);
	
	
?>