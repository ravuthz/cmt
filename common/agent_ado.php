<?php

include_once("adodb/adodb-exceptions.inc.php"); 
include_once('adodb/adodb.inc.php');
include_once("configs.php");
include_once("functions.php");
$db=NewADOConnection("mssql");
$db->debug=false;
$db->Connect($DBSERVER,$DBUSERNAME,$DBPASSWORD,$DBNAME);
$db->cursorType = 0;
$db->SetFetchMode(ADODB_FETCH_BOTH);

?>