<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<?php
	
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
			$accID = $_GET['accID'];
			$cycleID = $_GET['cycleID'];
	
	// Store history 
	
	$retOut='<table border="1" cellpadding="3" cellspacing="0" align="center" width="60%" height="100%" id="audit3" class="sortable" bordercolor="#cccccc">
							<thead>																									
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #FFFFFF; background-color:#00FFFF;">No</th>						
								<th align="center" width="12%" style="border-left:1px solid #999999; border-top:1px solid #FFFFFF; background-color:#00FFFF;">AccID</th>										
								<th align="center" width="65%" style="border-left:1px solid #999999; border-top:1px solid #FFFFFF; background-color:#00FFFF;">Comment</th>	
								<th align="center" width="20%" style="border-left:1px solid #999999; border-top:1px solid #FFFFFF; background-color:#00FFFF;">Date</th>	
							</thead><tbody>';
	
	$sql="select AccID,Comment,convert(varchar,csdate,120) csdate from tblpcocomment where accID=".intval($accID)." and cycleID=".intval($cycleID)."";

	$loop=0;
	if($que = $mydb->sql_query($sql)){				
		while($result = $mydb->sql_fetchrow($que)){	
			$accID = $result['AccID'];
			$Comment = $result['Comment'];
			$date = $result['csdate'];
			$loop+=1;
			if(($loop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
				
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$loop.'</td>';	
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$accID.'</td>';		
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Comment.'</td>';
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$date.'</td>';
			$retOut .= '</tr>';
		}
	}
	
	
	$retOut .= '</tbody></table>';
	print $retOut;
?>

