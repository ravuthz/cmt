<?php

	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];
	$service = $_GET['service'];
	$retOut = '
			<table border="0" cellpadding="1" cellspacing="0" width="100%" class="formbg">
				<tr>
					<td align="left" class="formtitle"><b>Demand Bill Payment Event</b></td>
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
								<th align="center">CycleDate</th>
								<th align="center">Package</th>
								<th align="center">AccID</th>
								<th align="center">Telephone</th>
								<th align="center">CustomerName</th>
								<th align="center">LOC</th>	
								<th align="center">LDC</th>	
								<th align="center">Mobile</th>
								<th align="center">IDD</th>			
								<th align="center">MF</th>
								<th align="center">Discount</th>
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
				CycleDate,
				TarName,
				Custid,
				Accid,
				Username,
				SubscriptionName,
				[Local],
				LongDistance,
				Mobile,
				International,
				Monthlyfee,
				Discount,
				VAT,
				InvoiceAmount,
				PaymentAmount
			from [vwDemandInvPayment] 
			where invoicetype in (2) and serviceid =" .$service; 
$sql .= " and convert(varchar, paymentdate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4);
$sql .= " and BillEndDate in (select MIN(BillEndDate) from tblSysBillRunCycleInfo where BillProcessed=0)";
//$sql .= " and (PrintFee != 0 or Configurationfee != 0 or CPEfee != 0 or ISDNfee != 0 or SPNfee != 0 or Changelocation != 0 or MonthlyFee != 0 or DemandBill != 0)"; 					
$sql .= " order by paymentdate asc,Cashier asc,PaymentMode asc,CycleDate desc,Username asc,TarName";

	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		$TotalLocal = 0.00;
		$totalLongDistance = 0.00;
		$totalMobile = 0.00;
		$totalInternational = 0.00;
		$totalMonthlyfee = 0.00;
		$totalDiscount = 0.00;
		$totalVAT = 0.00;
		$totalInvoiceAmount = 0.00;
		$totalPaymentAmount = 0.00;	
		while($result = $mydb->sql_fetchrow($que)){																																
			$paymentdate = $result['paymentdate'];
			$Cashier = $result['Cashier'];
			$PaymentMode = $result['PaymentMode'];
			$Invoiceid = $result['Invoiceid'];
			$CycleDate = $result['CycleDate'];
			$TarName = $result['TarName'];										
			$Custid = $result['Custid'];
			$Accid = $result['Accid'];
			$Username = $result['Username'];
			$SubscriptionName = $result['SubscriptionName'];
			$Local = $result['Local'];											
			$LongDistance = $result['LongDistance'];																
			$Mobile = $result['Mobile'];											
			$International = $result['International'];											
			$Monthlyfee = $result['Monthlyfee'];
			$Discount = $result['Discount'];
			$VAT = $result['VAT'];
			$InvoiceAmount = $result['InvoiceAmount'];
			$PaymentAmount =  $result['PaymentAmount'];
			
			
			$linkInv = "<a href='../finance/screeninvoice.php?CustomerID=".$Custid."&InvoiceID=".$Invoiceid."' target='_blank'>".$Invoiceid."</a>";
			$linkAcct = "<a href='../?CustomerID=".$Custid."&AccountID=".$Accid."&pg=91' target='_blank'>".$Username."</a>";
			
			
			$TotalLocal += floatval($Local);
			$totalLongDistance += floatval($LongDistance);
			$totalMobile += floatval($Mobile);
			$totalInternational += floatval($International);
			$totalMonthlyfee += floatval($Monthlyfee);
			$totalDiscount += floatval($Discount);
			$totalVAT += floatval($VAT);
			$totalInvoiceAmount += floatval($InvoiceAmount);
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
			$retOut .= '<td class="'.$style.'" align="left">'.$CycleDate.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';																									
			$retOut .= '<td class="'.$style.'" align="left">'.$Accid.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$linkAcct.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Local).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($LongDistance).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Mobile).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($International).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Monthlyfee).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Discount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($VAT).'</td>';
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
										<td colspan=10 align=right>Total</td>
										<td align="right">'.FormatCurrency($TotalLocal).'</td>
										<td align="right">'.FormatCurrency($totalLongDistance).'</td>
										<td align="right">'.FormatCurrency($totalMobile).'</td>
										<td align="right">'.FormatCurrency($totalInternational).'</td>
										<td align="right">'.FormatCurrency($totalMonthlyfee).'</td>
										<td align="right">'.FormatCurrency($totalDiscount).'</td>
										<td align="right">'.FormatCurrency($totalVAT).'</td>
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