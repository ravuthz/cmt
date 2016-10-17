<?php

	require_once("./common/configs.php");
	$conn=mssql_connect($DBSERVER,$DBUSERNAME,$DBPASSWORD) or die ('Not connected : ' . mssql_error());
	mssql_select_db('wisebiller_detail') or die('Database not found : ' .mssql_error());
	
	$ServerName="billingserver";	
	
?>