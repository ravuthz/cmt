<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
			$phone = $_GET['phone'];
			$InvoiceID = $_GET['invoiceID'];
			$remark = $_GET['remark'];
		
	$sql="Insert into tblDebtRemark_af values(".$InvoiceID.",'".$phone."','".$remark."',1)";
	$affected=$mydb->sql_query($sql);
	print $remark;
	//print $sql;
	//print mysql_affected_rows();
	
?>