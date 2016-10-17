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
    <td width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
    <td width="100%"><table width="100%" border="1" cellspacing="0" cellpadding="0"  bordercolor="#000000" style=" border-collapse:collapse">
      <thead>
        <th width="3%">No</th>
        <th width="25%">Province</th>
        <th width="9%">Users</th>
        <th width="9%">Total hours</th>
        <th width="11%">Charging hour</th>
        <th width="7%">Monthly</th>
        <th width="6%">Box fee</th>
        <th width="9%">Usage Fee</th>
        <th width="9%">Sub total</th>
        <th width="6%">Vat</th>
        <th width="6%">Total</th>
      </thead>
	  <tbody>
      <?
	  	echo $srbp->getBodyReport($strBillEndDate);
	  ?> 
	  </tbody>     
    </table></td>
  </tr>
  <tr>
        <td width="100%" colspan="2" align="left"><div align="left">
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
