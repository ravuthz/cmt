<script language="javascript">
	function archieve(id, name){
		if(confirm("Do you really want to archieve report " + name + "?")){
			window.location = "./?id="+id+"&ac=1&pg=921";
		}
	}
</script>
<?php
	require_once("./common/agent.php");
	require_once("./common/functions.php");	
	
	if((FixQuotes($ac) == 1) && (FixQuotes($id) > 0)){
		$sql = "UPDATE tblReportList SET IsAchieved = 1 WHERE AutoID=".$id;
		$mydb->sql_query($sql);
	}	
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>EXTRA REPORT</b>
					</td>		
					<td></td>			
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No.</th>
								<th align="center">Code</th>								
								<th align="center">Name</th>
								<th align="center">View/Download</th>
								<th align="center">Submit date</th>	
								<th align="center">Submit by</th>
								<th align="center">Archieved</th>								
							</thead>
							<tbody>';
	$sql = "SELECT AutoID, Name, URL, SubmittedDate, SubmittedBy
					FROM tblReportList
					WHERE IsAchieved = 0 ";
	//if((isset($name)) && (trim($name) != ""))
//		$sql .= " AND Name like '%".$name."%'"; 
	$sql .= " ORDER BY SubmittedDate DESC"; 				
			
	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$AutoID = $result['AutoID'];					
			$Name = $result['Name'];										
			$URL = $result['URL'];
			$SubmittedDate = $result['SubmittedDate'];
			$SubmittedBy = $result['SubmittedBy'];			
			
			$download	= "<a href='./report/extra/download.php?myfile=".$URL."'>Download</a>";
			$view	= "<a href='./report/extra/".$URL."' target='_blank'>View</a>";
			$archieved = "<a href=\"javascript:archieve(".$AutoID.", '".$Name."');\">Archieve</a>";						
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
			$retOut .= '<td class="'.$style.'" align="left">'.$archieved.'</td>';			
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