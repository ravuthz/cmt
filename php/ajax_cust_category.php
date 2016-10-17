<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$tid = $_GET['tid'];
	$oid = $_GET['oid'];
	$where = $_GET['where'];	
		
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>CUSTOMER REPORT BY CATEGORY</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">Customer</th>
								<th align="center">No of Acc</th>									
								<th align="center">Telephone</th>
								<th align="center">Email</th>
								<th align="center">Occupation</th>			
								<th align="center">Sign up</th>																											
							</thead>
							<tbody>';
	
	$sql = "select c.CustID, c.CustName, c.RegisteredDate, c.Telephone, c.BillingEmail, o.Career, count(a.AccID) as NoAccount
					from tblCustomer c(nolock) inner join tlkpcareer o(nolock) on c.OccupationID = o.CareerID	 													 
	 													 left join tblCustProduct a(nolock) on c.CustID = a.CustID where a.PackageID in (".$where.") ";
	if(intval($tid) >= 0)														 
		$sql .= " and c.Category=".$tid;
	if(intval($oid) >0)
		$sql .= " and c.OccupationID=".$oid;
	$sql .= " group by c.CustID, c.CustName, c.RegisteredDate, c.Telephone, c.BillingEmail, o.Career order by c.CustName";	
	//if($cpe != 0)
//		$sql .= " and u.CPEID = ".$cpe;
	
	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																																								
			$CustID = $result['CustID'];										
			$CustName = $result['CustName'];																
			$RegisteredDate = $result['RegisteredDate'];											
			$Telephone = $result['Telephone'];											
			$BillingEmail = $result['BillingEmail'];
			$Career = $result['Career'];
			$NoAccount = $result['NoAccount'];													
			
			$LinkAccount = "<a href=\"./?CustomerID=".$CustID."&pg=90\">".$NoAccount."</a>";	
			$LinkCustomer = "<a href=\"./?CustomerID=".$CustID."&pg=10\">".$CustName."</a>";																																			
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$LinkCustomer.'</td>';																										
			$retOut .= '<td class="'.$style.'" align="left">'.$LinkAccount.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Telephone.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$BillingEmail.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Career.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatDate($RegisteredDate, 3).'</td>';
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