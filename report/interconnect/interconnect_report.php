<?	
	require("local_report_class.php");
			
	
	$LocReport= new LocReport();			
?>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<link href="style.css" rel="stylesheet" type="text/css">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr class="report_header">
    <td><div align="center">INTERCONNECT REPORT </div></td>
  </tr>
  <tr class="under_header">
    <td><div align="center">For period: 07/01/2007 to 07/31/2007 </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#999999" class="report_dscr_header">
      <tr>
        <td rowspan="4">Providers</td>
        <td colspan="6">Incoming Calls </td>
        <td colspan="8">Outgoing Calls </td>
        </tr>
      <tr>
        <td colspan="2">CC08</td>
        <td colspan="2">DRX(MD110)</td>
        <td colspan="2" rowspan="2">Total</td>
        <td colspan="4">CC08</td>
        <td colspan="2">DRX(MD110)</td>
        <td colspan="2" rowspan="2">Total</td>
        </tr>
      <tr>
        <td colspan="2">ALL</td>
        <td colspan="2">ALL</td>
        <td colspan="2">CC08(Acc Onlty) </td>
        <td colspan="2">CC08(Exclude Access) </td>
        <td colspan="2">ALL</td>
        </tr>
      <tr>
        <td>Call</td>
        <td>Minute</td>
        <td>Call</td>
        <td>Minute</td>
        <td>Call</td>
        <td>Minute</td>
        <td>Call</td>
        <td>Minute</td>
        <td>Call</td>
        <td>Minute</td>
        <td>Call</td>
        <td>Minute</td>
        <td>Call</td>
        <td>Minute</td>
      </tr>
	  <?
	  	echo $LocReport->GetReportBody();
	  ?>
      <!--<tr class="report_detail">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>-->
    </table></td>
  </tr>
</table>