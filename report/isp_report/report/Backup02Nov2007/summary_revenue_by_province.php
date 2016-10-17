<?
	require_once("srbp_class.php");
	require("xml_handler.php");
	
	$strBillEndDate=$_REQUEST["BillEndDate"];
 	$xml = new XmlHandler();
	$xml->Load("config.xml");	
	$srbp = new srbp();
	
	$srbp->SetPeriod($strBillEndDate);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
}
.style4 {font-family: Arial, Helvetica, sans-serif}
.style5 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight: bold;
}
.style9 {font-size: 14px; font-weight: bold; }
.style10 {font-family: Courier New, Helvetica, sans-serif; font-size: 9px;}
-->
</style>

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2" align="left"><div align="left"><span class="style5">Revenue summary by provinces </span></div></td>
        </tr>
      <tr>
        <td width="11%" align="left"><div align="left"><span class="style4">Service</span></div></td>
        <td width="89%" align="left"><div align="left"><span class="style4">:</span>Internet and e-mail </div></td>
      </tr>
      <tr>
        <td align="left"><div align="left" ><span class="style4">Cycle Date </span></div></td>
        <td align="left"><div align="left"><span class="style4">:</span><?=$srbp->EndBillDate;?></div></td>
      </tr>
      <tr>
        <td align="left"><div align="left"><span class="style4">Printed on</span></div></td>
        <td align="left"><div align="left"><span class="style4">:</span><?=date("d/m/Y")?></div></td>
      </tr>
      <tr>
        <td align="left">&nbsp;</td>
        <td align="left">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="1000" border="1" cellspacing="0" cellpadding="0"  bordercolor="#000000" style=" border-collapse:collapse">
      <tr class="header">
        <td width="3%"><div align="center" class="style9"><span class="style4">No</span></div></td>
        <td width="25%"><div align="center" class="style9"><span class="style4">Province</span></div></td>
        <td width="9%"><div align="center" class="style9"><span class="style4">Users</span></div></td>
        <td width="9%"><div align="center" class="style9"><span class="style4">Total hours </span></div></td>
        <td width="11%"><div align="center" class="style9"><span class="style4">Charging hour </span></div></td>
        <td width="7%"><div align="center" class="style9"><span class="style4">Monthly</span></div></td>
        <td width="6%"><div align="center" class="style9"><span class="style4">Box fee </span></div></td>
        <td width="9%"><div align="center" class="style9"><span class="style4">Usage Fee </span></div></td>
        <td width="9%"><div align="center" class="style9"><span class="style4">Sub total </span></div></td>
        <td width="6%"><div align="center" class="style9"><span class="style4">Vat</span></div></td>
        <td width="6%"><div align="center" class="style9"><span class="style4">Total</span></div></td>
      </tr>
      <?
	  	echo $srbp->getBodyReport($strBillEndDate);
	  ?>      
    </table></td>
  </tr>
  <tr>
        <td colspan="2" align="left"><div align="left">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportfooter">
            <tr>
              <td width="9%">&nbsp;</td>
              <td width="58%">&nbsp;</td>
              <td width="33%">&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr class="reportfooter">
              <td>&nbsp;</td>
              <td>Reported by</td>
              <td>Acknowledged by </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><?=$xml->GetData("ReportedByName");?></td>
              <td class="spreportfooter"><?=$xml->GetData("AcknowledgedByName");?></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>Billing &amp; Treasury Manager </td>
            </tr>
          </table>
        </div></td>
        </tr>
</table>
</body>
</html>
