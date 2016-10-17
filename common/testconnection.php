<?php
$db = new COM("ADODB.Connection");
 // $dsn = "DRIVER={SQL Server}; SERVER=BRC07\SQLExPRESS;UID=sa;PWD=Iammiracle; DATABASE=angkornet_ispbilling";
$dsn = "DRIVER={SQL Server}; SERVER=brc07\sqlexpress;UID=sa;PWD=Iammiracle; DATABASE=wisebiller";
$db->Open($dsn);
$rs = $db->Execute("SELECT CountryID, Country from tlkpCountry order by Country");

while (!$rs->EOF)
{
   
	 echo $rs->Fields[0]->Value."<BR>";
   $rs->MoveNext();
}
?>