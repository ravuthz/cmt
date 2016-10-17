<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];
	$type = $_GET['type'];
	$op = $_GET['op'];
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>&nbsp;Audit Report - from '.formatDate($st, 6).' To '.formatDate($et, 6).'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">Date</th>								
								<th align="center">Context</th>
								<th align="center">Description</th>
								<th align="center">Operator</th>
							</thead>
							<tbody>';
	
	$sql = "select a.CustID, a.AccID, a.UserName, ag.GroupName, au.AuditID, au.Context, 	
			au.Description, au.AuditDate, au.Operator
			from tblCustProduct a right join tblAuditTrial au on a.AccID = au.AccountID 
			inner join tlkpAuditGroup ag on au.GroupID = ag.GroupID
where convert(varchar, au.AuditDate, 112) >= '".formatDate($st, 4)."' and convert(varchar, au.AuditDate, 112) <= '".formatDate($et, 4)."'";
	if($type != 'all')
		$sql .= " and ag.TypeID = '".$type."'";
	if($op != 'all')
		$sql .= " and au.Operator = '".$op."'";
							
	if($que = $mydb->sql_query($sql)){
		
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$Description = $result['Description'];										
			$Date = $result['AuditDate'];
			$Context = $result['Context'];
			$Operator = $result['Operator'];
			
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.' width="100" align="left">'.$Date.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left">'.$Context.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Description.'</td>';
			$retOut .= '<td class="'.$style.' width="150" align="left">'.$Operator.'</td>';

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