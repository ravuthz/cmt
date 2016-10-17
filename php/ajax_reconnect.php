<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];			
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Barred accounts from '.formatDate($st, 6).' and '.formatDate($et, 6).'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">Account</th>
								<th align="center">Date</th>	
								<th align="center">Package</th>	
								<th align="center">Service</th>
								<th align="center">Balance</th>			
								<th align="center">Outstanding</th>																										
							</thead>
							<tbody>';
	
	$sql = "select a.AccID, a.CustID, a.UserName, b.Credit, b.OutStanding, t.TarName, s.ServiceName, h.ChangeDate 							
					from tblCustProduct a, tblAccountBalance b, tblTarPackage t, tlkpService s, tblAccStatusHistory h
					where a.AccID = b.AccID and a.PackageID = t.PackageID and t.ServiceID = s.ServiceID and a.AccID = h.AccID		
							and h.OtherID = 5				
							and convert(varchar, h.ChangeDate, 112) >= '".formatDate($st, 4)."' 
							and convert(varchar, h.ChangeDate, 112) <= '".formatDate($et, 4)."'";	
	
	if($que = $mydb->sql_query($sql)){		
		$total4 = 0.00;
		$total5 = 0.00;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$AccID = $result['AccID'];										
			$CustID = $result['CustID'];										
			$UserName = $result['UserName'];																
			$ChangeDate = $result['ChangeDate'];											
			$Credit = $result['Credit'];											
			$OutStanding = $result['OutStanding'];
			$TarName = $result['TarName'];
			$ServiceName = $result['ServiceName'];
										
			
			$LinkAccount = "<a href=\"./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91\">".$UserName."</a>";																						
			$total4 += floatval($Credit);											
			$total5 += floatval($OutStanding);											
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$LinkAccount.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="right">'.formatDate($ChangeDate, 3).'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$ServiceName.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Credit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($OutStanding).'</td>';
			$retOut .= '</tr>';
		}		
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan="5">Total</td>											
											<td align="right">'.FormatCurrency($total4).'</td>
											<td align="right">'.FormatCurrency($total5).'</td>
										</tr>
									</tfoot>												
								</table>						
							</td>
						</tr>
					</table>';
		
	print $retOut;	
?>