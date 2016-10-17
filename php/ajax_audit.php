<?php

	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];
	$service = $_GET['service'];
	$retOut = '
			<table border="0" cellpadding="1" cellspacing="0" width="100%" class="formbg">
				<tr>
					<td align="left" class="formtitle"><b>Audit Event</b></td>
				</tr>
				<tr>
					<td>
	<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>
								<th align="center">Date</th>
								<th align="center">Context</th>								
								<th align="center">CustID</th>
								<th align="center">Customer Name</th>
								<th align="center">Description</th>																												
							</thead>
							<tbody>';
$sql = "select	convert(varchar,au.AuditDate,120) AuditDate,
				cu.CustID, 
				cu.CustName,
				au.Context, 
				au.Description  
		from dbo.tblAuditTrial au
		join tblcustomer cu on au.CustomerID = cu.custid
		where Operator ='" .$service."'"; 
$sql .= " and au.Context not in ('Add new customer guarranter','Add new customer designate','Add new customer contact','Add new installation address','Add new billing address','Generate new registration invoice','Add product require deposit')";
$sql .= " and convert(varchar, AuditDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4);
$sql .= " order by au.AuditDate"; 					



	if($que = $mydb->sql_query($sql)){				
		
		$iLoop = 0;
		
		while($result = $mydb->sql_fetchrow($que)){																																
			$AuditDate = $result['AuditDate'];
			$CustID = $result['CustID'];
			$CustName = $result['CustName'];
			$Context = $result['Context'];										
			$Description = $result['Description'];
					
					
			$linkAcct = "<a href='../?CustomerID=".$CustID."&pg=90' >".$CustID."</a>";		
			
			
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="left">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.formatDate($AuditDate,8).'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Context.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$linkAcct.'</td>';																									
			$retOut .= '<td class="'.$style.'" align="left">'.$CustName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Description.'</td>';
			$retOut .= '</tr>';
		}		
	}else{
	//	$error = $mydb->sql_error();
	//	$ya= $error['message']; 
	}
	$mydb->sql_freeresult();
	$mydb->sql_close();
		$retOut .= '</tbody>
								<tfoot>
									<tr>
										<td colspan=6 align=right>&nbsp;</td>
									</tr>
								</tfoot>																				
							</table>
						</td>
					</tr>
				</table>';
		print $retOut;
?>