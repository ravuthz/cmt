<?
	$BillEndDate=$_REQUEST["BillEndDate"];
	$ReportNo=$_REQUEST["ReportNo"];	
	
	$ServiceId=$_REQUEST["ServiceId"];
	$OrgProvId=$_REQUEST["ProvinceId"];
		
	
	switch($ReportNo)
	{
		case 1:
			$ReportPath="summary_revenue_by_province.php?BillEndDate=".$BillEndDate;
			break;
		case 2:
			$ReportPath="pg1.php?BillEndDate=".$BillEndDate;
			break;
		case 3:
			$ReportPath="pg2.php?BillEndDate=".$BillEndDate."&ServiceId=".$ServiceId."&ProvinceId=".$OrgProvId;
			break;
		case 4:
			$ReportPath="pg3.php?BillEndDate=".$BillEndDate."&ServiceId=".$ServiceId."&ProvinceId=".$OrgProvId;
			break;
		default:
			$ReportPath="pg3.php?BillEndDate=".$BillEndDate;
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Report Viewer</title>

<script type="text/javascript" src="../javascript/ajax_driver.js"></script>
<script>
	function PackageOnload()
	{		
		process('<?=$ReportPath?>','reportViewer');			
	}
</script>
<link href="../style/mystyle.css" rel="stylesheet" type="text/css" />
</head>

<body onload="PackageOnload();" width="100%">
<!--<div align="center" width="100%">-->
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr align="left">
      <td width="100%"><div align="left" id="reportViewer" width="100%"></div></td>
    </tr>
  </table>  
<!--</div>-->
</body>
</html>
