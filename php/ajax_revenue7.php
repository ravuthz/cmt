<?php

	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];
	$service = $_GET['service'];
	$retOut = '
			<table border="0" cellpadding="1" cellspacing="0" width="100%" class="formbg">
				<tr>
					<td align="left" class="formtitle"><b>Other Payment Events</b></td>
				</tr>
				<tr>
					<td>
	<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>
								<th align="center">PayDate</th>	
								<th align="center">Cashier</th>	
								<th align="center">Mode</th>							
								<th align="center">InvID</th>
								<th align="center">AccID</th>
								<th align="center">Telephone</th>
								<th align="center">CustomerName</th>
								<th align="center">ChName</th>	
								<th align="center">PrDetail</th>	
								<th align="center">Install</th>
								<th align="center">CPE</th>			
								<th align="center">SPN</th>
								<th align="center">ChNum</th>
								<th align="center">ChLoc</th>
								<th align="center">Hunt</th>
								<th align="center">Par</th>
								<th align="center">Pol</th>
								<th align="center">Other</th>
								<th align="center">VAT</th>								
								<th align="center">Total</th>
								<th align="center">Paid</th>																										
							</thead>
							<tbody>';
$sql = "	select			
				convert(varchar,paymentdate,111) paymentdate,
				Cashier,
				PaymentMode,
				Invoiceid,
				Custid,
				Accid,
				Username,
				SubscriptionName,
				ChangeName,
				PrintFee,
				Configurationfee,
				CPEfee,
				SPNfee,
				ChangeNumber,
				Changelocation,
				Hunting,
				Parallel,
				[Reverse],
				MonthlyFee 'Other',
				InvoiceAmount,
				VATamount,
				PaymentAmount				
			from vwOtherInvPayment vw
			where invoicetype in (3) and serviceid =" .$service; 
$sql .= " and convert(varchar, paymentdate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4);
$sql .= " and BillEndDate in (select MIN(BillEndDate) from tblSysBillRunCycleInfo where BillProcessed=0)";

//$sql .= " and (PrintFee != 0 or Configurationfee != 0 or CPEfee != 0 or ChangeName != 0 or SPNfee != 0 or Changelocation != 0 or MonthlyFee != 0 or ChangeNumber != 0)"; 					
$sql .= " order by paymentdate asc,Cashier asc,PaymentMode asc, ChangeName desc, PrintFee desc,Configurationfee desc,CPEfee desc,SPNfee desc,ChangeNumber desc,Changelocation desc,Hunting desc,[Reverse] desc,Other desc,Username asc,TarName";

//print $sql;
	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		$totalChangeName = 0.00;
		$totalPF = 0.00;
		$totalCF = 0.00;
		$totalCPE = 0.00;
		$totalSPN = 0.00;
		$totalChangeNumber = 0.00;
		$totalChh = 0.00;
		$totalHunting = 0.00;
		$totalParallel = 0.00;
		$totalReverse = 0.00;
		$totalOTHER = 0.00;
		$totalInvoiceAmount = 0.00;
		$totalVATAmount = 0.00;
		$totalPaymentAmount = 0;		
		while($result = $mydb->sql_fetchrow($que)){																																
			$paymentdate = $result['paymentdate'];
			$Cashier = $result['Cashier'];
			$PaymentMode = $result['PaymentMode'];
			$Invoiceid = $result['Invoiceid'];										
			$Custid = $result['Custid'];
			$Accid = $result['Accid'];
			$Username = $result['Username'];
			$SubscriptionName = $result['SubscriptionName'];
			$ChangeName = $result['ChangeName'];											
			$PF = $result['PrintFee'];																
			$CF = $result['Configurationfee'];											
			$CPE = $result['CPEfee'];											
			$SPNfee = $result['SPNfee'];
			$ChangeNumber = $result['ChangeNumber'];
			$ChAmount = $result['Changelocation'];
			$Hunting = $result['Hunting'];
			$Parallel = $result['Parallel'];
			$Reverse = $result['Reverse'];
			$Other = $result['Other'];
			$VATamount = $result['VATamount'];
			$InvoiceAmount = $result['InvoiceAmount'];
			$PaymentAmount = $result['PaymentAmount'];
			
			
			$linkInv = "<a href='../finance/screeninvoice.php?CustomerID=".$Custid."&InvoiceID=".$Invoiceid."' target='_blank'>".$Invoiceid."</a>";
			$linkAcct = "<a href='../?CustomerID=".$Custid."&AccountID=".$Accid."&pg=91' target='_blank'>".$Username."</a>";
			
			
			
			$totalChangeName += floatval($ChangeName);
			$totalPF += floatval($PF);
			$totalCF += floatval($CF);
			$totalCPE += floatval($CPE);
			$totalSPN += floatval($SPNfee);
			$totalChangeNumber += floatval($ChangeNumber);
			$totalChh += floatval($ChAmount);
			$totalHunting += floatval($Hunting);
			$totalParallel += floatval($Parallel);
			$totalReverse += floatval($Reverse);
			$totalOTHER += floatval($Other);
			$totalInvoiceAmount += floatval($InvoiceAmount);
			$totalVATAmount += floatval($VATamount);
			$totalPaymentAmount += floatval($PaymentAmount);
			
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$paymentdate.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Cashier.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$PaymentMode.'</td>';																									
			$retOut .= '<td class="'.$style.'" align="left">'.$linkInv.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left">'.$Accid.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$linkAcct.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($ChangeName).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($PF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($CF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($CPE).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($SPNfee).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($ChangeNumber).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($ChAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Hunting).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Parallel).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Reverse).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Other).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($VATamount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($InvoiceAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($PaymentAmount).'</td>';
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
										<td colspan=8 align=right>Total</td>
										<td align="right">'.FormatCurrency($totalChangeName).'</td>
										<td align="right">'.FormatCurrency($totalPF).'</td>
										<td align="right">'.FormatCurrency($totalCF).'</td>
										<td align="right">'.FormatCurrency($totalCPE).'</td>
										<td align="right">'.FormatCurrency($totalSPN).'</td>
										<td align="right">'.FormatCurrency($totalChangeNumber).'</td>
										<td align="right">'.FormatCurrency($totalChh).'</td>
										<td align="right">'.FormatCurrency($totalHunting).'</td>
										<td align="right">'.FormatCurrency($totalParallel).'</td>
										<td align="right">'.FormatCurrency($totalReverse).'</td>
										<td align="right">'.FormatCurrency($totalOTHER).'</td>
										<td align="right">'.FormatCurrency($totalVATAmount).'</td>
										<td align="right">'.FormatCurrency($totalInvoiceAmount).'</td>
										<td align="right">'.FormatCurrency($totalPaymentAmount).'</td>
									</tr>
								</tfoot>																				
							</table>
						</td>
					</tr>
				</table>';
		print $retOut;
?>