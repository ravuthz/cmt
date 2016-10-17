<?
	require("dtbp_class.php");
	require("srbp_class.php");
	
	$ServiceId=$_REQUEST["ServiceId"];
	$OrgProvId=$_REQUEST["ProvinceId"];
	$strBillEndDate=$_REQUEST["BillEndDate"];
	
	$dtbp = new dtbp();
	
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
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style></head>

<body>
<div align="center">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportheader">
        <tr>
          <td colspan="2"><span class="reporttitle">INTERNET AND E-MAIL SYSTEM </span></td>
          </tr>
        <tr>
          <td width="13%">Service</td>
          <td width="87%">:&nbsp;<?=$dtbp->GetServiceName($ServiceId);?></td>
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
	<?
			$result = $dtbp->SelectDataFromDb($dtbp->GetAllProvincesQS($OrgProvId));
			while($row=mssql_fetch_array($result))
			{
				$ProvinceId=$row["id"];
	?>
    <tr>
      <td>&nbsp;</td>      
    </tr>
	
    <tr class="provincetitle">
      <td>&nbsp;Province:&nbsp;<?=$row["name"];?></td>
        </tr>
        <tr align="left">
          <td><table width="1200" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#CCCCCC">
            <tr class="serviceheader">
              <td><div style="width:23>">No</div></td>
              <td><div style="width:51>">AccNo</div></td>
              <td><div style="width:68>">Invoce No</div></td>
              <td width="250"><div style="width:250">Customer Name</div></td>
              <td width="100"><div style="width:100">Phone</div></td>
              <td><div style="width:61">Monthly</div></td>
              <td><div style="width:80">Usage</div> </td>
              <td><div style="width:70">Usage Amt</div> </td>			  
              <td><div style="width:64">Mailbox</div></td>
			  <td><div style="width:70">Cedit Allowance</div> </td>
			  <td><div style="width:70">Other</div> </td>
              <td><div style="width:68">Subtotal</div></td>
              <td><div style="width:58">VAT</div></td>
              <td><div style="width:82">Total Amt</div> </td>
			  <td><div style="width:58">Paid</div></td>
              <td><div style="width:82">Unpaid</div> </td>
            </tr>
			<?=$dtbp->GetReportBody($ServiceId, $ProvinceId, $strBillEndDate);?>		
            
          </table></td>
        </tr>
		<?
			}
		?>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
  </td>      
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>
