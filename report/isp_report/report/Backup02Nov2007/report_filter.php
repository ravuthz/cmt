<?php
	require("conn.php");
	
	
	$cmbBillEndDate=$_REQUEST["cmbBillEndDate"];
	$strLastBillDate=GetLastBillEndDate($conn);
	
	function GetBillEndDate($conn,$strSelected,$strDefault)
	{		
		$strQuery="Select day(BillEndDate),month(BillEndDate),year(BillEndDate) from tblSysBillRunCycleInfo\r\n".
				  "where BillEndDate between (Select dateadd(mm,-4,max(BillEndDate)) from tblSysBillRunCycleInfo where BillProcessed=1) and (Select dateadd(mm,2,max(BillEndDate)) from tblSysBillRunCycleInfo where BillProcessed=1) \r\n".
				  "group by BillEndDate order by BillEndDate";
		
		$result=mssql_query($strQuery,$conn) or die("Error connection string");		
		while ($row = mssql_fetch_row($result)) 
		{			
			$date=date("d-M-Y",mktime(0,0,0,$row[1],$row[0],$row[2]));
			$date_value=date("Ymd",mktime(0,0,0,$row[1],$row[0],$row[2]));
			if(!empty($strSelected))
			{
				if($strSelected==$date)
					echo "<Option value=".$date_value." selected>".$date."</option>";
				else
					echo "<Option value=".$date_value.">".$date."</option>";
			}
			else
			{
				if($strDefault==$date)
					echo "<Option value=".$date_value." selected>".$date."</option>";
				else
					echo "<Option value=".$date_value.">".$date."</option>";
			}
        }
	}
	
	function GetLastBillEndDate($conn)
	{		
		$date="";
		$strQuery=  "Select day(max(BillEndDate)),month(max(BillEndDate)),year(max(BillEndDate)) from tblSysBillRunCycleInfo where BillProcessed=1";					
		
		$result=mssql_query($strQuery,$conn) or die("Error connection string");		
		while ($row = mssql_fetch_row($result)) 
		{						
			$date=date("d-M-Y",mktime(0,0,0,$row[1],$row[0],$row[2]));
        }
		return $date;
	}
?>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.style3 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.style6 {font-size: 10}
.style7 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
	color: #000066;
}
-->
</style>
<script>
	function previewReport()
	{
		var BillEndDate=document.all["cmbBillEndDate"].value;
		var ReportType=document.all["cmbReportNo"].value;
		window.open('./report/isp_report/report/report_viewer.php?ReportNo='+ReportType+'&BillEndDate='+BillEndDate);		
	}
</script>
<table width="100%" border="0">
  <tr>
    <td><div align="center">
      <table width="384" border="1" cellspacing="0" cellpadding="0">
        <tr bgcolor="#FFCC66">
          <td width="380"><span class="style7">Isp Report Filter </span></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="10%">&nbsp;</td>
              <td width="30%">&nbsp;</td>
              <td width="60%">&nbsp;</td>
            </tr>
            <tr>
              <td><span class="style6"></span></td>
              <td><span class="style3">Bill End Date: </span></td>
              <td align="left"><span class="style6" style="font-family: Verdana, Arial, Helvetica, sans-serif">
                <select name="cmbBillEndDate" class="style3" style="width:100">
                  <?=GetBillEndDate($conn,$cmbBillEndDate,$strLastBillDate);?>
                  </select>
              </span></td>
            </tr>
            <tr class="style3">
              <td>&nbsp;</td>
              <td>Report Type:</td>
              <td align="left"><select name="cmbReportNo" class="style3" style="width:200">                  	
					<option value="2">Summary revenue by provinces</option>
					<option value="4">Statistic of internet and email</option>
                  </select></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="3"><div align="center">
                <input type="button" name="btnPreview" value="Preview" onClick="previewReport();" />
              </div></td>
              </tr>
            <tr>
              <td colspan="3">&nbsp;</td>
            </tr>
          </table></td>
        </tr>        
      </table>
    </div></td>
  </tr>
</table>
