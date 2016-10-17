<!--
	*
	* This code is not to be distributed without the written permission of BRC Technology.
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a> 
	* 	
-->

<?php 
require_once("../common/agent.php");
require_once("../common/functions.php");
header("Content-type:text/xml"); 
print("<?xml version=\"1.0\"?>");

$sql="select * from tlkpTarChargingBand";
$result=$mydb->sql_query($sql);
print("<tree id='0'>");

while($row=$mydb->sql_fetchrow($result)){
	$BandID=intval($row['BandID']);
	$BandName=stripcslashes($row['BandName']);
	$Description=stripslashes($row['Description']);
	if(strlen(trim($Description))!=""){
		 $Description=", Description: ".$Description;
		
	}else{
		$Description="";
	}
	print("<item id='".$BandID."' text='Band Name: ".$BandName."".$Description."' im0='books_close.gif' im1='tombs.gif' im2='tombs.gif'>");			
	$sql2="SELECT * FROM tblTarChargingCode WHERE BandID='$BandID'";
	$result2=$mydb->sql_query($sql2);
	while ($row2=$mydb->sql_fetchrow($result2)) {
		$ChargeID=intval($row2['ChargeID']);
		$AreaCode=stripslashes($row2['AreaCode']);
		$CallType=intval($row2['CallType']);
		$Description=stripslashes($row2['Description']);
		print("<item id='".$ChargeID."' text='Area Code: ".$AreaCode.", Call Type:".$CallType.", Description: ".$Description."' im0='books_close.gif' im1='tombs.gif' im2='tombs.gif' />");
	}
	print("</item>");
}
//         for ($inta=0; $inta<4; $inta++){
//			 		print("<item id='".$url_var."_".$intc."' text='Item ".$url_var."-".$inta."' im0='books_close.gif' im1='tombs.gif' im2='tombs.gif'>");			
//			 	for ($intc=0; $intc<4; $intc++){
//			 		print("<item id='".$url_var."_".$intc."' text='Item ".$url_var."-".$inta."' im0='books_close.gif' im1='tombs.gif' im2='tombs.gif' />");			
//
//			 	}
//			 	echo "</item>";
//         }
print("</tree>");
?> 
