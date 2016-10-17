<?php

	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
			$phone = $_GET['phone'];
			$InvoiceID = $_GET['invoiceID'];
			$remark = $_GET['remark'];
			$accID = $_GET['accid'];
			$dnow = $_GET['dp'];
						
	$sql="Insert into tblDebtRemark_re_f values($InvoiceID,'$phone','$remark',1)";
	$mydb->sql_query($sql);
	$sql="update tblCustProduct set statusID=1,startbillingdate='$dnow' where accID=$accID";
	$mydb->sql_query($sql);
	
	//print $sql;
	//print mysql_affected_rows();
	
?>