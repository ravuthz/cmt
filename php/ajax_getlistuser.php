<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$gid = $_GET['gid'];	
	$gname = $_GET['gname'];	
			
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>List all users belong to group '.$gname.'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">User name</th>
								<th align="center">Full name</th>
								<th align="center">Status</th>
								<th align="center">Email</th>
								<th align="center">Description</th>																																																				
							</thead>
							<tbody>';
	
	$sql = "SELECT u.UserID, u.UserName, u.FullName, u.Status, u.EmailAddress, u.Description 
					FROM tblSecUser u, tblSecUserGroup  up
					WHERE u.UserID =  up.UserID and up.GroupID = ".$gid;
						
	if($que = $mydb->sql_query($sql)){		
		
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$pUserID = $result['UserID'];										
			$pUserName = $result['UserName'];										
			$pFullName = $result['FullName'];
			$Status = $result['Status'];
			$EmailAddress = $result['EmailAddress'];
			$Description = $result['Description'];																
			$link = "<a href='./?userid=".$pUserID."&pg=909'>".$pFullName."</a>";
			if($Status){
				$stStatus = "<font color='blue'><b>Active</b></font>";
			}else{
				$stStatus = "<font color='red'><b>Suspend</b></font>";
			}											
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$pUserName.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left">'.$link.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$stStatus.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$EmailAddress.'</td>';
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