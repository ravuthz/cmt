<?php

	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$cid = $_GET['cid'];	
	$ct = $_GET['ct'];
	$where = $_GET['where'];
	$retOut = '
			<table border="0" cellpadding="1" cellspacing="0" width="100%" class="formbg">
				<tr>
					<td align="left" class="formtitle"><b>Total Other Invoice Issued</b></td>
				</tr>
				<tr>
					<td>
	<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid;">No</th>								
								<th align="center" style="border:1px solid;">Service</th>
								<th align="center" style="border:1px solid;">Package</th>
								<th align="center" style="border:1px solid;">Total cycle</th>
								<th align="center" style="border:1px solid;">Cycle bill</th>
								<th align="center" style="border:1px solid;">Total demand</th>
								<th align="center" style="border:1px solid;">Demand fee</th>	
								<th align="center" style="border:1px solid;">Register</th>	
								<th align="center" style="border:1px solid;">Install</th>
								<th align="center" style="border:1px solid;">CPE</th>			
								<th align="center" style="border:1px solid;">SPN</th>
								<th align="center" style="border:1px solid;">ISDN</th>
								<th align="center" style="border:1px solid;">Ch.Loc</th>
								<th align="center" style="border:1px solid;">Other</th>
								<th align="center" style="border:1px solid;">Total</th>																										
							</thead>
							<tbody>';
$sql = "				
					select t.TarName, t.PackageID, s.ServiceName, 0 'Invoice', convert(decimal(8, 2), 0.00) 'InvoiceAmount', 0 'Demand',
						convert(decimal(8, 2), 0.00) 'DemandAmount', convert(decimal(8, 2), 0.00) 'RF', convert(decimal(8, 2), 0.00) 'CF', 
						convert(decimal(8, 2), 0.00) 'CPE', convert(decimal(8, 2), 0.00) 'SPN', convert(decimal(8, 2), 0.00) 'ISDN', 
						convert(decimal(8, 2), 0.00) 'ChAmount',  convert(decimal(8, 2), 0.00) 'Other' 
					INTO #tmpDummy
					from tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage t(nolock), 
							tlkpService s(nolock), tblSysBillRunCycleInfo inf(nolock)
					where i.AccID = a.AccID
						and i.BillingCycleID = inf.CycleID
						and inf.PackageID = t.PackageID
						and t.ServiceID = s.ServiceID
						and convert(varchar, inf.BillEndDate, 112) = '".$cid."'
				";	
			$sql .= " and t.PackageID IN (".$where.") ";
			$sql .= "	group by t.TarName, t.PackageID, s.ServiceName
					order by s.ServiceName, t.TarName;					
					
					select a.PackageID, Sum(i.NetAmount) as 'InvoiceAmount', Count(i.InvoiceID) as 'Invoice'
					INTO #tmpBill
					from tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblSysBillRunCycleInfo inf(nolock)
					where i.AccID = a.AccID						
						and i.BillingCycleID = inf.CycleID
						and convert(varchar, inf.BillEndDate, 112) = '".$cid."'						
						and i.InvoiceType = 1
					group by a.PackageID;
					
					UPDATE #tmpDummy
					SET #tmpDummy.InvoiceAmount = #tmpBill.InvoiceAmount,
							#tmpDummy.Invoice = #tmpBill.Invoice
					FROM #tmpDummy INNER JOIN #tmpBill ON #tmpDummy.PackageID = #tmpBill.PackageID;
																																			
					select a.PackageID, Sum(i.NetAmount) as 'DemandAmount', Count(i.InvoiceID) as 'Demand'
					INTO #tmpDemand
					from tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblSysBillRunCycleInfo inf(nolock)
					where i.AccID = a.AccID	
						and i.BillingCycleID = inf.CycleID				
						and convert(varchar, inf.BillEndDate, 112) ='".$cid."'						
						and i.InvoiceType = 2
					group by a.PackageID;
					
					UPDATE #tmpDummy
					SET #tmpDummy.DemandAmount = #tmpDemand.DemandAmount,
							#tmpDummy.Demand = #tmpDemand.Demand
					FROM #tmpDummy INNER JOIN #tmpDemand ON #tmpDummy.PackageID = #tmpDemand.PackageID;
																																			
					select a.PackageID, Sum(id.Amount) as 'RFAmount'
					INTO #tmpRF
					from tblCustomerInvoiceDetail id(nolock), tblCustomerInvoice i(nolock), tblCustProduct a(nolock), 
							tblSysBillRunCycleInfo inf(nolock)
					where id.InvoiceID = i.InvoiceID 
						and i.BillingCycleID = inf.CycleID
						and i.AccID = a.AccID
						and convert(varchar, inf.BillEndDate, 112) = '".$cid."'
						and id.BillItemID = 6	
					group by a.PackageID;
					
					UPDATE #tmpDummy
					SET #tmpDummy.RF = #tmpRF.RFAmount
					FROM #tmpDummy INNER JOIN #tmpRF ON #tmpDummy.PackageID = #tmpRF.PackageID;
					
					select a.PackageID, Sum(id.Amount) as 'CFAmount'
					INTO #tmpCF 
					from tblCustomerInvoiceDetail id(nolock), tblCustomerInvoice i(nolock), tblCustProduct a(nolock),
								tblSysBillRunCycleInfo inf(nolock)
					where id.InvoiceID = i.InvoiceID 	
						and i.BillingCycleID = inf.CycleID
						and i.AccID = a.AccID
						and convert(varchar, inf.BillEndDate, 112) = '".$cid."'
						and id.BillItemID = 7	
					group by a.PackageID;
					
					UPDATE #tmpDummy
					SET #tmpDummy.CF = #tmpCF.CFAmount
					FROM #tmpDummy INNER JOIN #tmpCF ON #tmpDummy.PackageID = #tmpCF.PackageID;
					
					select a.PackageID, Sum(id.Amount) as 'CPEAmount' 
					INTO #tmpCPE
					from tblCustomerInvoiceDetail id(nolock), tblCustomerInvoice i(nolock), tblCustProduct a(nolock),
							tblSysBillRunCycleInfo inf(nolock)
					where id.InvoiceID = i.InvoiceID 
						and i.AccID = a.AccID	
						and i.BillingCycleID = inf.CycleID
						and convert(varchar, inf.BillEndDate, 112) = '".$cid."'
						and id.BillItemID = 8	
					group by a.PackageID;					
					
					UPDATE #tmpDummy
					SET #tmpDummy.CPE = #tmpCPE.CPEAmount
					FROM #tmpDummy INNER JOIN #tmpCPE ON #tmpDummy.PackageID = #tmpCPE.PackageID;
					
					select a.PackageID, Sum(id.Amount) as 'SPNAmount' 
					INTO #tmpSPN
					from tblCustomerInvoiceDetail id(nolock), tblCustomerInvoice i(nolock), tblCustProduct a(nolock),
							tblSysBillRunCycleInfo inf(nolock)
					where id.InvoiceID = i.InvoiceID 	
						and i.BillingCycleID = inf.CycleID
						and i.AccID = a.AccID
						and convert(varchar, inf.BillEndDate, 112) = '".$cid."'
						and id.BillItemID = 15	
					group by a.PackageID
					
					
					UPDATE #tmpDummy
					SET #tmpDummy.SPN = #tmpSPN.SPNAmount
					FROM #tmpDummy INNER JOIN #tmpSPN ON #tmpDummy.PackageID = #tmpSPN.PackageID;
					
					select a.PackageID, Sum(id.Amount) as 'ISDNAmount' 
					INTO #tmpISDN
					from tblCustomerInvoiceDetail id(nolock), tblCustomerInvoice i(nolock), tblCustProduct a(nolock),
							tblSysBillRunCycleInfo inf(nolock)
					where id.InvoiceID = i.InvoiceID 	
						and i.BillingCycleID = inf.CycleID
						and i.AccID = a.AccID
						and convert(varchar, inf.BillEndDate, 112) = '".$cid."'
						and id.BillItemID = 14	
					group by a.PackageID;
					
					UPDATE #tmpDummy
					SET #tmpDummy.ISDN = #tmpISDN.ISDNAmount
					FROM #tmpDummy INNER JOIN #tmpISDN ON #tmpDummy.PackageID = #tmpISDN.PackageID;
					
					select a.PackageID, Sum(id.Amount) as 'ChAmount' 
					INTO #tmpCh
					from tblCustomerInvoiceDetail id(nolock), tblCustomerInvoice i(nolock), tblCustProduct a(nolock),
							tblSysBillRunCycleInfo inf(nolock)
					where id.InvoiceID = i.InvoiceID 	
						and i.BillingCycleID = inf.CycleID
						and i.AccID = a.AccID
						and convert(varchar, inf.BillEndDate, 112) = '".$cid."'
						and id.BillItemID = 23	
					group by a.PackageID;
					
					UPDATE #tmpDummy
					SET #tmpDummy.ChAmount = #tmpCh.ChAmount
					FROM #tmpDummy INNER JOIN #tmpCh ON #tmpDummy.PackageID = #tmpCh.PackageID;
					
					select a.PackageID, Sum(id.Amount) as 'OtherAmount' 
					INTO #tmpOther
					from tblCustomerInvoiceDetail id(nolock), tblCustomerInvoice i(nolock), tlkpInvoiceItem t(nolock), tblCustProduct a(nolock),
							tblSysBillRunCycleInfo inf(nolock)
					where id.InvoiceID = i.InvoiceID
						and i.AccID = a.AccID
						and i.BillingCycleID = inf.CycleID
						and id.BillItemID = t.ItemID 	
						and convert(varchar, inf.BillEndDate, 112) = '".$cid."'
						and t.ItemGroupID = 2
						and id.BillItemID not in(	6, 7, 8, 14, 15)
					group by a.PackageID;					
					
					UPDATE #tmpDummy
					SET #tmpDummy.Other = #tmpOther.OtherAmount
					FROM #tmpDummy INNER JOIN #tmpOther ON #tmpDummy.PackageID = #tmpOther.PackageID;
					
					SELECT * FROM #tmpDummy where InvoiceAmount != 0 or Invoice!=0 or Demand != 0 or DemandAmount!=0 or RF!=0 or CF!= 0 or CPE!=0 ORDER BY ServiceName, TarName;
					
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
			$SPN = $result['SPN'];
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
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid; border-top:1px dotted">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted">'.$ServiceName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted">'.$TarName.'</td>';																									
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted">'.$Invoice.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted">'.FormatCurrency($InvoiceAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted">'.$Demand.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted">'.FormatCurrency($DemandAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted">'.FormatCurrency($RF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted">'.FormatCurrency($CF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted">'.FormatCurrency($CPE).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted">'.FormatCurrency($SPN).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted">'.FormatCurrency($ISDN).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted">'.FormatCurrency($ChAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted">'.FormatCurrency($Other).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid; border-top:1px dotted; border-right:1px solid;">'.FormatCurrency($TOTAL).'</td>';
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
										<td colspan=3 align=right style="border:1px solid;">Total</td>
										<td align="right" style="border:1px solid;">'.$TOTALNUMBER.'</td>
										<td align="right" style="border:1px solid;">'.FormatCurrency($totalBILL).'</td>
										<td align="right" style="border:1px solid;">'.$NoDemand.'</td>
										<td align="right" style="border:1px solid;">'.FormatCurrency($totalDemand).'</td>
										<td align="right" style="border:1px solid;">'.FormatCurrency($totalRF).'</td>
										<td align="right" style="border:1px solid;">'.FormatCurrency($totalCF).'</td>
										<td align="right" style="border:1px solid;">'.FormatCurrency($totalCPE).'</td>
										<td align="right" style="border:1px solid;">'.FormatCurrency($totalSPN).'</td>
										<td align="right" style="border:1px solid;">'.FormatCurrency($totalISDN).'</td>
										<td align="right" style="border:1px solid;">'.FormatCurrency($totalChh).'</td>
										<td align="right" style="border:1px solid;">'.FormatCurrency($totalOTHER).'</td>
										<td align="right" style="border:1px solid;">'.FormatCurrency($totalTOTAL).'</td>
									</tr>
								</tfoot>																				
							</table>
						</td>
					</tr>
				</table>';
		print $retOut;
?>