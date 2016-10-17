<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>
<body>


<?php

include ("../../common/configs.php");
include ("../../common/agent.php");

$sql =	"	
			select distinct dateName(yy,BillEndDate) YearProcessed from tblSysBillRunCycleInfo 
			where BillProcessed = 1 
			order by dateName(yy,BillEndDate)
		";
		
		$ShowLinkYear = ' | ';
if($que = $mydb->sql_query($sql)){	
	
		while($result = $mydb->sql_fetchrow()){
		
			$YearProcessed = $result['YearProcessed'];	
			$linkYear = '<a href=./GraphMonthly.php?year='.$YearProcessed.'&sid='.$_GET["sid"].'>'.$YearProcessed.'</a>';
			$ShowLinkYear = $ShowLinkYear . $linkYear . ' | ';
																																																							
		}
	}
$mydb->sql_freeresult();

//Close Connection to database
$mydb->sql_close();


?>



<table align="center" width="100%" height="100%">
	<tr>
		<td align="center">
			<img src="Aging_monthperyear.php?year=<?php print $_GET['year'] ?>&sid=<?php print $_GET['sid'] ?> " />
		</td>
	</tr>
	<tr>
		<td align="center"><?php print $ShowLinkYear ?></td>
	</tr>
</table>
	

</body>
</html>
