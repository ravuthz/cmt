<?php

	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];
	$service = $_GET['service'];
	$retOut = '
			<table border="0" cellpadding="1" cellspacing="0" width="100%" class="formbg">
				<tr>
					<td align="left" class="formtitle"><b>Total Issued New Other Invoices</b></td>
				</tr>
				<tr>
					<td>
	<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">InvID</th>
								<th align="center">Package</th>
								<th align="center">IssueDate</th>
								<th align="center">AccID</th>
								<th align="center">Telephone</th>
								<th align="center">CustomerName</th>
								<th align="center">Cycle</th>
								<th align="center">Demand</th>	
								<th align="center">Register</th>	
								<th align="center">Install</th>
								<th align="center">CPE</th>			
								<th align="center">SPN</th>
								<th align="center">ISDN</th>
								<th align="center">Ch.Loc</th>
								<th align="center">Other</th>
								<th align="center">Total</th>																										
							</thead>
							<tbody>';
$sql = "	select	Invoiceid,
				convert(varchar,issuedate,111) issuedate,
				TarName,
				Accid,
				Username,
				SubscriptionName,
				Cycle = Case when invoicetype = 1 then invoiceamount else 0 end,
				Demand = Case when invoicetype = 2 then invoiceamount else 0 end,
				Registrationfee,
				Configurationfee,
				CPEfee,
				ISDNfee,
				SPNfee,
				Changelocation,
				0 'Other'
			from vwOtherRevenue 
			where invoicetype in (1,2,3) and invoiceamount != 0 and serviceid =" .$service; 
$sql .= "and convert(varchar, IssueDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4); 					
$sql .= " order by issuedate asc,Cycle asc, Demand asc, Registrationfee desc,Configurationfee desc,CPEfee desc,ISDNfee desc,SPNfee desc,Changelocation desc,Username asc,TarName";


	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		$totalCycle = 0.00;
		$totalDemand = 0.00;
		$totalRF = 0.00;
		$totalCF = 0.00;
		$totalCPE = 0.00;
		$totalSPN = 0.00;
		$totalISDN = 0.00;
		$totalChh = 0.00;
		$totalOTHER = 0.00;
		$totalTOTAL = 0.00;
		$TOTALNUMBER = 0;		
		while($result = $mydb->sql_fetchrow($que)){																																
			$TarName = $result['TarName'];
			$Invoiceid = $result['Invoiceid'];
			$issuedate = $result['issuedate'];										
			$Accid = $result['Accid'];
			$Username = $result['Username'];
			$SubscriptionName = $result['SubscriptionName'];
			$Cycle = $result['Cycle'];
			$Demand = $result['Demand'];
			$RF = $result['Registrationfee'];																
			$CF = $result['Configurationfee'];											
			$CPE = $result['CPEfee'];											
			$ISDNfee = $result['ISDNfee'];
			$SPNfee = $result['SPNfee'];
			$ChAmount = $result['Changelocation'];
			$Other = $result['Other'];
			$TOTAL =  floatval($Cycle) + floatval($Demand) +floatval($RF) + floatval($CF) + floatval($CPE) + floatval($ISDNfee) + floatval($SPNfee) + floatval($ChAmount);
			$totalCycle += floatval($Cycle);
			$totalDemand += floatval($Demand);
			$totalRF += floatval($RF);
			$totalCF += floatval($CF);
			$totalCPE += floatval($CPE);
			$totalSPN += floatval($SPNfee);
			$totalISDN += floatval($ISDNfee);
			$totalTOTAL += floatval($TOTAL);
			$totalOTHER += floatval($Other);
			$totalChh += floatval($ChAmount);
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Invoiceid.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';																									
			$retOut .= '<td class="'.$style.'" align="left">'.$issuedate.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Accid.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Username.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Cycle).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Demand).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($RF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($CF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($CPE).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($SPNfee).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($ISDNfee).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($ChAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Other).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($TOTAL).'</td>';
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
										<td colspan=7 align=right>Total</td>
										<td align="right">'.FormatCurrency($totalCycle).'</td>
										<td align="right">'.FormatCurrency($totalDemand).'</td>
										<td align="right">'.FormatCurrency($totalRF).'</td>
										<td align="right">'.FormatCurrency($totalCF).'</td>
										<td align="right">'.FormatCurrency($totalCPE).'</td>
										<td align="right">'.FormatCurrency($totalSPN).'</td>
										<td align="right">'.FormatCurrency($totalISDN).'</td>
										<td align="right">'.FormatCurrency($totalChh).'</td>
										<td align="right">'.FormatCurrency($totalOTHER).'</td>
										<td align="right">'.FormatCurrency($totalTOTAL).'</td>
									</tr>
								</tfoot>																				
							</table>
						</td>
					</tr>
				</table>';
		print $retOut;
?>