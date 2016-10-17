<?php
	$st = $st;
	$et = $et;
	
	$filename = "revenue_report_".$st."_".$et;
	$filename  .= '.xls';
	$mime_type = 'application/vnd.ms-excel';	
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$st = FixQuotes($st);
	$et = FixQuotes($et);
?>

			<?php

				require_once("../common/agent.php");
				require_once("../common/functions.php");	
				$st = $_GET['st'];	
				$et = $_GET['et'];
				$service = $_GET['service'];
								
				$retOut = '
			<table border="0" cellpadding="1" cellspacing="0" width="100%" class="formbg">
				<tr>
					<td align="left" class="formtitle"><b>Invoice Billing Events</b></td>
				</tr>
				<tr>
					<td>
	<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">Service</th>
								<th align="center">Package</th>
								<th align="center">Total cycle</th>
								<th align="center">Cycle bill</th>
								<th align="center">Total demand</th>
								<th align="center">Demand fee</th>	
								<th align="center">Reg. fee</th>	
								<th align="center">Con. fee</th>
								<th align="center">CPE fee</th>			
								<th align="center">SPN fee</th>
								<th align="center">ISDN fee</th>
								<th align="center">Ch. location fee</th>
								<th align="center">Other fee</th>
								<th align="center">Total</th>																										
							</thead>
							<tbody>';
$sql = "				
					select t.TarName, t.PackageID, s.ServiceName, 0 as 'Invoice', 0 'InvoiceAmount', 0 'Demand',
						0 'DemandAmount', 0 'RF', 0 'CF', 0 'CPE', 0 'SPN', 0 'ISDN', 0 'ChAmount',  0 'Other' 
					INTO #tmpDummy
					from tblCustomerInvoice i, tblCustProduct a, tblTarPackage t, tlkpService s
					where i.AccID = a.AccID
						and a.PackageID = t.PackageID
						and t.ServiceID = s.ServiceID
						and convert(varchar, IssueDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."
						";
		if(intval($service) > 0)
			$sql .= " and s.ServiceID = ".$service;
			$sql .= "	group by t.TarName, t.PackageID, s.ServiceName
					
					order by s.ServiceName, t.TarName;					
					
					select a.PackageID, Sum(i.NetAmount) as 'InvoiceAmount', Count(i.InvoiceID) as 'Invoice'
					INTO #tmpBill
					from tblCustomerInvoice i, tblCustProduct a
					where i.AccID = a.AccID						
						and convert(varchar, IssueDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."						
						and i.InvoiceType = 1
					group by a.PackageID;
					
					UPDATE #tmpDummy
					SET #tmpDummy.InvoiceAmount = #tmpBill.InvoiceAmount,
							#tmpDummy.Invoice = #tmpBill.Invoice
					FROM #tmpDummy INNER JOIN #tmpBill ON #tmpDummy.PackageID = #tmpBill.PackageID;
																																			
					select a.PackageID, Sum(i.NetAmount) as 'DemandAmount', Count(i.InvoiceID) as 'Demand'
					INTO #tmpDemand
					from tblCustomerInvoice i, tblCustProduct a
					where i.AccID = a.AccID					
						and convert(varchar, IssueDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."						
						and i.InvoiceType = 2
					group by a.PackageID;
					
					UPDATE #tmpDummy
					SET #tmpDummy.DemandAmount = #tmpDemand.DemandAmount,
							#tmpDummy.Demand = #tmpDemand.Demand
					FROM #tmpDummy INNER JOIN #tmpDemand ON #tmpDummy.PackageID = #tmpDemand.PackageID;
																																			
					select a.PackageID, Sum(id.Amount) as 'RFAmount'
					INTO #tmpRF
					from tblCustomerInvoiceDetail id, tblCustomerInvoice i, tblCustProduct a
					where id.InvoiceID = i.InvoiceID 
						and i.AccID = a.AccID
						and convert(varchar, IssueDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."
						and id.BillItemID = 6	
					group by a.PackageID;
					
					UPDATE #tmpDummy
					SET #tmpDummy.RF = #tmpRF.RFAmount
					FROM #tmpDummy INNER JOIN #tmpRF ON #tmpDummy.PackageID = #tmpRF.PackageID;
					
					select a.PackageID, Sum(id.Amount) as 'CFAmount'
					INTO #tmpCF 
					from tblCustomerInvoiceDetail id, tblCustomerInvoice i, tblCustProduct a
					where id.InvoiceID = i.InvoiceID 	
						and i.AccID = a.AccID
						and convert(varchar, IssueDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."
						and id.BillItemID = 7	
					group by a.PackageID;
					
					UPDATE #tmpDummy
					SET #tmpDummy.CF = #tmpCF.CFAmount
					FROM #tmpDummy INNER JOIN #tmpCF ON #tmpDummy.PackageID = #tmpCF.PackageID;
					
					select a.PackageID, Sum(id.Amount) as 'CPEAmount' 
					INTO #tmpCPE
					from tblCustomerInvoiceDetail id, tblCustomerInvoice i, tblCustProduct a
					where id.InvoiceID = i.InvoiceID 
						and i.AccID = a.AccID	
						and convert(varchar, i.IssueDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."
						and id.BillItemID = 8	
					group by a.PackageID;					
					
					UPDATE #tmpDummy
					SET #tmpDummy.CPE = #tmpCPE.CPEAmount
					FROM #tmpDummy INNER JOIN #tmpCPE ON #tmpDummy.PackageID = #tmpCPE.PackageID;
					
					select a.PackageID, Sum(id.Amount) as 'SPNAmount' 
					INTO #tmpSPN
					from tblCustomerInvoiceDetail id, tblCustomerInvoice i, tblCustProduct a
					where id.InvoiceID = i.InvoiceID 	
						and i.AccID = a.AccID
						and convert(varchar, IssueDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."
						and id.BillItemID = 15	
					group by a.PackageID
					
					
					UPDATE #tmpDummy
					SET #tmpDummy.SPN = #tmpSPN.SPNAmount
					FROM #tmpDummy INNER JOIN #tmpSPN ON #tmpDummy.PackageID = #tmpSPN.PackageID;
					
					select a.PackageID, Sum(id.Amount) as 'ISDNAmount' 
					INTO #tmpISDN
					from tblCustomerInvoiceDetail id, tblCustomerInvoice i, tblCustProduct a
					where id.InvoiceID = i.InvoiceID 	
						and i.AccID = a.AccID
						and convert(varchar, IssueDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."
						and id.BillItemID = 14	
					group by a.PackageID;
					
					UPDATE #tmpDummy
					SET #tmpDummy.ISDN = #tmpISDN.ISDNAmount
					FROM #tmpDummy INNER JOIN #tmpISDN ON #tmpDummy.PackageID = #tmpISDN.PackageID;
					
					select a.PackageID, Sum(id.Amount) as 'ChAmount' 
					INTO #tmpCh
					from tblCustomerInvoiceDetail id, tblCustomerInvoice i, tblCustProduct a
					where id.InvoiceID = i.InvoiceID 	
						and i.AccID = a.AccID
						and convert(varchar, IssueDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."
						and id.BillItemID = 23	
					group by a.PackageID;
					
					UPDATE #tmpDummy
					SET #tmpDummy.ChAmount = #tmpCh.ChAmount
					FROM #tmpDummy INNER JOIN #tmpCh ON #tmpDummy.PackageID = #tmpCh.PackageID;
					
					select a.PackageID, Sum(id.Amount) as 'OtherAmount' 
					INTO #tmpOther
					from tblCustomerInvoiceDetail id, tblCustomerInvoice i, tlkpInvoiceItem t, tblCustProduct a
					where id.InvoiceID = i.InvoiceID
						and i.AccID = a.AccID
						and id.BillItemID = t.ItemID 	
						and convert(varchar, IssueDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."
						and t.ItemGroupID = 2
						and id.BillItemID not in(	6, 7, 8, 14, 15)
					group by a.PackageID;					
					
					UPDATE #tmpDummy
					SET #tmpDummy.Other = #tmpOther.OtherAmount
					FROM #tmpDummy INNER JOIN #tmpOther ON #tmpDummy.PackageID = #tmpOther.PackageID;
					
					SELECT * FROM #tmpDummy ORDER BY ServiceName, TarName;
					
					DROP TABLE #tmpDummy;
					DROP TABLE #tmpDemand;
					DROP TABLE #tmpBill;
					DROP TABLE #tmpRF;
					DROP TABLE #tmpCF;
					DROP TABLE #tmpCPE;
					DROP TABLE #tmpISDN;
					DROP TABLE #tmpCh;
					DROP TABLE #tmpSPN;
					DROP TABLE #tmpOther;
					
		";	
	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		$NoDemand = 0.00;
		$totalDemand = 0.00;
		$totalBILL = 0.00;
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
			$ServiceName = $result['ServiceName'];
			$InvoiceAmount = $result['InvoiceAmount'];										
			$Invoice = $result['Invoice'];
			$Demand = $result['Demand'];
			$DemandAmount = $result['DemandAmount'];										
			$RF = $result['RF'];																
			$CF = $result['CF'];											
			$CPE = $result['CPE'];											
			$SPN = $result['CPD'];
			$ISDN = $result['ISDN'];
			$ChAmount = $result['ChAmount'];
			$Other = $result['Other'];
			$TOTAL = floatval($InvoiceAmount) + floatval($DemandAmount) + floatval($RF) + floatval($CF) + floatval($CPE) + floatval($SPN) + floatval($ISDN) + floatval($Other);
			$TOTALNUMBER += intval($Invoice);
			$totalBILL += $InvoiceAmount;
			$totalRF += floatval($RF);
			$totalCF += floatval($CF);
			$totalCPE += floatval($CPE);
			$totalSPN += floatval($SPN);
			$totalISDN += floatval($ISDN);
			$totalTOTAL += floatval($TOTAL);
			$totalOTHER += floatval($Other);
			$totalChh += floatval($ChAmount);
			$NoDemand += intval($Demand);
			$totalDemand += floatval($DemandAmount);
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$ServiceName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';																									
			$retOut .= '<td class="'.$style.'" align="right">'.$Invoice.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($InvoiceAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.$Demand.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($DemandAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($RF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($CF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($CPE).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($SPN).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($ISDN).'</td>';
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
										<td colspan=3 align=right>Total</td>
										<td align="right">'.$TOTALNUMBER.'</td>
										<td align="right">'.FormatCurrency($totalBILL).'</td>
										<td align="right">'.$NoDemand.'</td>
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
