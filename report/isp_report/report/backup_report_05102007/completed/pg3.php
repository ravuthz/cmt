<?
	include("sie_class.php");	
	$strBillEndDate=$_REQUEST["BillEndDate"];
	$sie = new sie();		
	$sie->SetCycleDateProperties($strBillEndDate);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<script type="text/css" src="../style/mystyle.css"></script>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
}
-->
</style></head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr class="reporttitle">
        <td colspan="3">Statistic of internet and email </td>
        </tr>
      <tr class="reporttitle">
        <td colspan="3">By comparison with previous month </td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;Service: Dialup</td>
  </tr>
  <tr>
    <td><table width="800" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#CCCCCC">
      <tr class="serviceheader">
        <td rowspan="2" width="30">No</td>
        <td rowspan="2" width="170">Province</td>
        <td colspan="2" width="200"><?=$sie->PreCycleDate;?></td>
        <td colspan="2" width="200"><?=$sie->CurCycleDate;?></td>
        <td colspan="2" width="200">Variance (%) </td>
        </tr>
      <tr class="serviceheader">
        <td width="80">User</td>
        <td width="120">Amount</td>
        <td width="80">User</td>
        <td width="120">Amount</td>
        <td width="80">User</td>
        <td width="120">Amount</td>
      </tr>
	  <?=$sie->GetReportBodyByService(3,$strBillEndDate);?>     	  
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;Service: ADSL </td>
  </tr>
  <tr>
    <td><table width="800" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#CCCCCC">
      <tr class="serviceheader">
        <td rowspan="2" width="30">No</td>
        <td rowspan="2" width="170">Province</td>
        <td colspan="2" width="200"><?=$sie->PreCycleDate;?></td>
        <td colspan="2" width="200"><?=$sie->CurCycleDate;?></td>
        <td colspan="2" width="200">Variance (%) </td>
        </tr>
      <tr class="serviceheader">
        <td width="80">User</td>
        <td width="120">Amount</td>
        <td width="80">User</td>
        <td width="120">Amount</td>
        <td width="80">User</td>
        <td width="120">Amount</td>
      </tr>
      <?=$sie->GetReportBodyByService(1,$strBillEndDate);?>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;Service: ISDN </td>
  </tr>
  <tr>
    <td><table width="800" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#CCCCCC">
      <tr class="serviceheader">
        <td rowspan="2" width="30">No</td>
        <td rowspan="2" width="170">Province</td>
        <td colspan="2" width="200"><?=$sie->PreCycleDate;?></td>
        <td colspan="2" width="200"><?=$sie->CurCycleDate;?></td>
        <td colspan="2" width="200">Variance (%) </td>
        </tr>
      <tr class="serviceheader">
        <td width="80">User</td>
        <td width="120">Amount</td>
        <td width="80">User</td>
        <td width="120">Amount</td>
        <td width="80">User</td>
        <td width="120">Amount</td>
      </tr>
      <?=$sie->GetReportBodyByService(8,$strBillEndDate);?>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;Total</td>
  </tr>
  <tr>
    <td><table width="800" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#CCCCCC">
      <tr class="serviceheader">
        <td rowspan="2" width="30">No</td>
        <td rowspan="2" width="170">Province</td>
        <td colspan="2" width="200"><?=$sie->PreCycleDate;?></td>
        <td colspan="2" width="200"><?=$sie->CurCycleDate;?></td>
        <td colspan="2" width="200">Variance (%) </td>
        </tr>
      <tr class="serviceheader">
        <td width="80">User</td>
        <td width="120">Amount</td>
        <td width="80">User</td>
        <td width="120">Amount</td>
        <td width="80">User</td>
        <td width="120">Amount</td>
      </tr>
      <?=$sie->GetReportBodyByService(-1,$strBillEndDate);?>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
