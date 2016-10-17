<?
	require("srbp_class.php");
	
	$strBillEndDate=$_REQUEST["BillEndDate"];
	
	$srbp = new srbp();	
	
	$srbp->SetPeriod($strBillEndDate)
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
.style5 {	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight: bold;	
}
-->
</style></head>

<body>
<div align="center">
  <table width="1000" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportheader">
        <tr>
          <td colspan="2"><span class="style5">Summary revenue by provinces</span></td>
          </tr>
        <tr>
          <td width="14%">Service</td>
          <td width="86%">:&nbsp;Internet & Email Service</td>
        </tr>
        <tr>
          <td>Bill Cycle Date </td>
          <td>:&nbsp;<?=$srbp->EndBillDate;?></td>
        </tr>
        <tr>
          <td>Printed On </td>
          <td>:&nbsp;<?=date("d/m/Y")?></td>
        </tr>
      </table></td>      
    </tr>
    <tr>
      <td>&nbsp;</td>      
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <?
			$result = $srbp->SelectDataFromDb($srbp->GetAllProvincesQS());
			while($row=mssql_fetch_array($result))
			{
				$ProvinceId=$row["id"];
				$strAllProvince.=$ProvinceId.",";
		?>
        <tr class="provincetitle">
          <td>&nbsp;<?=$row["name"]?></td>
        </tr>
        <tr>
          <td><table width="100%" border="1" cellspacing="0" cellpadding="0"  bordercolor="#CCCCCC" style=" border-collapse:collapse">
      <tr class="serviceheader">
        <td width="3%"><div align="center" class="style9"><span class="style4">No</span></div></td>
        <td width="13%"><div align="center" class="style9"><span class="style4">Service Type</span></div></td>
        <td width="9%"><div align="center" class="style9"><span class="style4">Users</span></div></td>
        <td width="9%"><div align="center" class="style9"><span class="style4">Total hours </span></div></td>
        <td width="11%"><div align="center" class="style9"><span class="style4">Charging hour </span></div></td>
        <td width="7%"><div align="center" class="style9"><span class="style4">Monthly</span></div></td>
        <td width="6%"><div align="center" class="style9"><span class="style4">Box fee </span></div></td>
        <td width="9%"><div align="center" class="style9"><span class="style4">Usage Fee </span></div></td>
		<td width="9%"><div align="center" class="style9"><span class="style4">Credit Allowance </span></div></td>
		<td width="9%"><div align="center" class="style9"><span class="style4">Other</span></div></td>
        <td width="9%"><div align="center" class="style9"><span class="style4">Sub total </span></div></td>
        <td width="6%"><div align="center" class="style9"><span class="style4">Vat</span></div></td>
        <td width="6%"><div align="center" class="style9"><span class="style4">Total</span></div></td>
		<td width="6%"><div align="center" class="style9"><span class="style4">Paid</span></div></td>
        <td width="6%"><div align="center" class="style9"><span class="style4">Unpaid</span></div></td>
      </tr>      
      <?
	  	echo $srbp->getBodyReportPgOne($ProvinceId,$strBillEndDate);
	  ?>      
    </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
		<?
			}
			$intLen=strlen($strAllProvince);
			$strAllProvince=$strAllProvince!=""?substr($strAllProvince,0,$intLen-1):"";
		?>
      </table></td>      
    </tr>
    <tr>
      <td align="left">&nbsp;Total</td>
    </tr>
    <tr>
      <td><table width="100%" border="1" cellspacing="0" cellpadding="0"  bordercolor="#CCCCCC" style=" border-collapse:collapse">
      <tr class="serviceheader">
        <td width="3%"><div align="center" class="style9"><span class="style4">No</span></div></td>
        <td width="13%"><div align="center" class="style9"><span class="style4">Service Type</span></div></td>
        <td width="9%"><div align="center" class="style9"><span class="style4">Users</span></div></td>
        <td width="9%"><div align="center" class="style9"><span class="style4">Total hours </span></div></td>
        <td width="11%"><div align="center" class="style9"><span class="style4">Charging hour </span></div></td>
        <td width="7%"><div align="center" class="style9"><span class="style4">Monthly</span></div></td>
        <td width="6%"><div align="center" class="style9"><span class="style4">Box fee </span></div></td>
        <td width="9%"><div align="center" class="style9"><span class="style4">Usage Fee </span></div></td>
		<td width="9%"><div align="center" class="style9"><span class="style4">Credit Allowance </span></div></td>
		<td width="9%"><div align="center" class="style9"><span class="style4">Other</span></div></td>
        <td width="9%"><div align="center" class="style9"><span class="style4">Sub total </span></div></td>
        <td width="6%"><div align="center" class="style9"><span class="style4">Vat</span></div></td>
        <td width="6%"><div align="center" class="style9"><span class="style4">Total</span></div></td>
		<td width="6%"><div align="center" class="style9"><span class="style4">Paid</span></div></td>
        <td width="6%"><div align="center" class="style9"><span class="style4">Unpaid</span></div></td>
      </tr>      
      <?
	  	if($intLen>0)
		{
	  		echo $srbp->getBodyReportPgOne($strAllProvince,$strBillEndDate);
		}
	  ?>      
    </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>
