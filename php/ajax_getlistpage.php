<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$gid = $_GET['gid'];	
	$gname = $_GET['gname'];	
			
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>List all pages belong to group '.$gname.'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">Page code</th>
								<th align="center">Page name</th>
								<th align="center">Description</th>																																																				
							</thead>
							<tbody>';
	
	$sql = "SELECT p.PageCode, p.PageName, p.Description 
					FROM tblSecPage p, tblSecGroupPage  gp
					WHERE p.PageID =  gp.PageID and gp.GroupID = ".$gid;
							
	if($que = $mydb->sql_query($sql)){		
		
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$PageCode = $result['PageCode'];										
			$PageName = $result['PageName'];										
			$Description = $result['Description'];																
														
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$PageCode.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left">'.$PageName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Description.'</td>';
			$retOut .= '</tr>';
		}		
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																					
								</table>						
							</td>
						</tr>
					</table>';
		
	print $retOut;	
?>