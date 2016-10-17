<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
		
/*
	$sql = "create proc sp_dropm
		as
		delete from __drop1
		insert into __drop1 select distinct * from dbo.fn_latelypaid_1m_request_u('2010-08-31','2010-09-22') order by  remark desc,exception,state,exc desc,del,pdate,phone,CustName
		";
*/
	$sql = "exec sp_dropm
		";

	$que = $mydb->sql_query($sql);				
	
?>