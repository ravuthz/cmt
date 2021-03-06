<?php
	require_once("./common/agent.php");
	require_once("./common/functions.php");	
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>EXTRA REPORT</b>
					</td>
					<td align="right">[<a href="./?pg=247">View report</a>]</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No.</th>
								<th align="center">Code</th>								
								<th align="center">Name</th>
								<th align="center">View/Download</th>
								<th align="center">Submit date</th>	
								<th align="center">Submit by</th>		
								<th align="center">Archieved date</th>							
							</thead>
							<tbody>';
	$sql = "SELECT AutoID, Name, URL, SubmittedDate, SubmittedBy, AchivedDate
					FROM tblReportList
					WHERE IsAchieved = 1
					ORDER BY SubmittedDate DESC"; 				
			
	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$AutoID = $result['AutoID'];					
			$Name = $result['Name'];										
			$URL = $result['URL'];
			$SubmittedDate = $result['SubmittedDate'];
			$SubmittedBy = $result['SubmittedBy'];			
			$AchivedDate = $result['AchivedDate'];			
			
			$download	= "<a href='./extra/download.php?myfile=".$URL."'>Download</a>";
			$view	= "<a href='./extra/".$URL."' target='_blank'>View</a>";						
			$iLoop++;															
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.'" align="center">'.$iLoop.'</td>';	
			$retOut .= '<td class="'.$style.'" align="right">'.$AutoID.'</td>';								
			$retOut .= '<td class="'.$style.'" align="left">'.$Name.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left">'.$download.' | '.$view.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.FormatDate($SubmittedDate, 7).'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$SubmittedBy.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left">'.FormatDate($AchivedDate, 7).'</td>';
			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
		$retOut .= '</table>						
							</td>
						</tr>
					</table>';
     print $retOut;
?>