<?php
/**
	 *	@Project: Wise Biller	
	 *	@File:		audit.php	
	 *	
	 *	@Author: Chea vey	 
	 *
	 */

 
include("configs.php");
include("info.php");
include("mssql.php");

//
// Create information agent
//
if(!isset($myinfo))
	$myinfo = new info_box();


//
// Create database agent
//
#Connect to database persistency
if(!isset($mydb))
$mydb = new sql_db($DBSERVER, $DBUSERNAME, $DBPASSWORD, $DBNAME, true) or die("failed to connect to database");

if(!$mydb->db_connect_id) {	 
  # die("<br><br><center><img src=images/logo.gif><br><br><b>There seems to be a problem with the MySQL server, sorry for the inconvenience.<br><br>We should be back shortly.</center></b>");
 die($myinfo->error("Oop. There seems to be a problem with connection to database."));
}
/*
$sql = "select CustName from tblCustomer";
#$sql = "insert into tblCustomer (custName) values ('vey')";
if($que = $mydb->sql_query($sql)){
	
	$row = $mydb->sql_fetchrow($que);
		print $row['CustName'];
}
	*/								

?>
