<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];			
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Closed accounts from '.formatDate($st, 6).' and '.formatDate($et, 6).'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">Account</th>
								<th align="center">Closed Date</th>	
								<th align="center">NC deposit</th>
								<th align="center">IC deposit</th>																				
								<th align="center">MF deposit</th>
								<th align="center">Balance</th>			
								<th align="center">Out standing</th>																										
							</thead>
							<tbody>';
	
	$sql = "select a.AccID, a.CustID, a.UserName, b.Credit, b.OutStanding, d.NationalDeposit, 
							d.InternationDeposit, d.MonthlyDeposit, h.ChangeDate
					from tblCustProduct a, tblAccountBalance b, tblAccDeposit d, tblAccStatusHistory h
					where a.AccID = b.AccID and a.AccID = d.AccID and a.AccID = h.AccID
							and h.OtherID = 3		
							and convert(varchar, h.ChangeDate, 112) >= '".formatDate($st, 4)."' 
							and convert(varchar, h.ChangeDate, 112) <= '".formatDate($et, 4)."'";		
	
	if($que = $mydb->sql_query($sql)){
		$total1 = 0.00;
		$total2 = 0.00;
		$total3 = 0.00;
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
			$NationalDeposit = $result['NationalDeposit'];
			$InternationDeposit = $result['InternationDeposit'];
			$MonthlyDeposit = $result['MonthlyDeposit'];			
			
			$LinkAccount = "<a href=\"./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91\">".$UserName."</a>";										
			$total1 += floatval($NationalDeposit);											
			$total2 += floatval($InternationDeposit);											
			$total3 += floatval($MonthlyDeposit);											
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
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($NationalDeposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($InternationDeposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($MonthlyDeposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Credit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($OutStanding).'</td>';
			$retOut .= '</tr>';
		}		
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan="3">Total</td>
											<td align="right">'.FormatCurrency($total1).'</td>
											<td align="right">'.FormatCurrency($total2).'</td>
											<td align="right">'.FormatCurrency($total3).'</td>
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