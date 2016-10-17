<?php
	
	$filename = "cashier_report";		
	$filename  .= '.xls';
	//$mime_type = 'text/comma-separated-values';		
	$mime_type = 'application/vnd.ms-excel';	
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="' . $filename . '"');		

	require_once("../common/agent.php");
	require_once("../common/functions.php");	
				$FromDate = $_GET['st'];	
				$ToDate = $_GET['et'];
				$Package = $_GET['where'];
				
				$sql = '
					BEGIN TRY
						DROP TABLE ReportCashier
						DROP TABLE #Pay
						DROP TABLE #DTB
						DROP TABLE #BD
						DROP TABLE #AP
						DROP TABLE #RD
						DROP TABLE #RB
						DROP TABLE #CN
						DROP TABLE #WB
						DROP TABLE #SB
					END TRY
					BEGIN CATCH
					END CATCH

					SELECT inf.BillEndDate, COUNT(i.InvoiceID) "NoInv", 0.00 "DTB", 0.00 "BD", 0.00 "AP", 
							convert(numeric(8, 2), 0.00) "Paid", 0.00 "RD", 0.00 "RB", 0.00 "CN", 0.00 "WB", 0.00 "SB", 1 "Type"
					INTO ReportCashier
					FROM tblCustomerInvoice i(nolock), tblCashPayment p(nolock), tblSysBillRunCycleInfo inf(nolock), tblCustProduct a(nolock)
					WHERE p.InvoiceID = i.InvoiceID
						AND p.AcctID = a.AccID
						AND i.BillingCycleID = inf.CycleID	 
						AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
						AND a.PackageID IN (' . $Package . ')	   						
					GROUP BY inf.BillEndDate
						;
					SELECT inf.BillEndDate,  
							IsNULL(SUM(p.PaymentAmount), 0.00) "Paid", 1 "Type"
					INTO #Pay
					FROM tblCustomerInvoice i(nolock), tblCashPayment p(nolock), tblSysBillRunCycleInfo inf(nolock), tblCustProduct a(nolock)
					WHERE p.InvoiceID = i.InvoiceID
						AND p.AcctID = a.AccID
						AND i.BillingCycleID = inf.CycleID	 
						AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
						AND a.PackageID IN (' . $Package . ')	   
						AND p.TransactionModeID = 1 
					GROUP BY inf.BillEndDate
					
					UPDATE ReportCashier 
						SET ReportCashier.Paid = #Pay.Paid
						FROM #Pay
						WHERE ReportCashier.[Type] = 1
							AND ReportCashier.BillEndDate = #Pay.BillEndDate
						
					INSERT INTO ReportCashier(BillEndDate, NoInv, DTB, BD, AP, Paid, RD, RB, CN, WB, SB, [Type])
						VALUES("", 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 2)
											
						SELECT IsNULL(SUM(p.PaymentAmount), 0) "DTB"
						INTO #DTB
						FROM tblCashPayment p(nolock), tblCustProduct a(nolock)    
						WHERE p.AcctID = a.AccID
						AND p.TransactionModeID IN(11, 12, 13)	
						AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
						AND a.PackageID IN (' . $Package . ')
						
						UPDATE ReportCashier 
						SET ReportCashier.DTB = #DTB.DTB
						FROM #DTB
						WHERE ReportCashier.[Type] = 2
						
						SELECT IsNULL(SUM(p.PaymentAmount), 0) "BD"
						INTO #BD
						FROM tblCashPayment p(nolock), tblCustProduct a(nolock)
						WHERE p.AcctID = a.AccID
						AND p.TransactionModeID IN(4, 5, 6)	
						AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
						AND a.PackageID IN (' . $Package . ')
						
						UPDATE ReportCashier 
						SET ReportCashier.BD = #BD.BD
						FROM #BD
						WHERE ReportCashier.[Type] = 2
						
						SELECT IsNULL(SUM(p.PaymentAmount), 0) "AP"
						INTO #AP
						FROM tblCashPayment p(nolock), tblCustProduct a(nolock)
						WHERE p.AcctID = a.AccID
						AND p.TransactionModeID IN(3)	
						AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
						AND a.PackageID IN (' . $Package . ')
						
						UPDATE ReportCashier 
						SET ReportCashier.AP = #AP.AP
						FROM #AP
						WHERE ReportCashier.[Type] = 2
						
						SELECT IsNULL(SUM(p.PaymentAmount), 0) "RD"
						INTO #RD
						FROM tblCashPayment p(nolock), tblCustProduct a(nolock)
						WHERE p.AcctID = a.AccID
						AND p.TransactionModeID IN(7, 8, 9)	
						AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
						AND a.PackageID IN (' . $Package . ')
						
						UPDATE ReportCashier 
						SET ReportCashier.RD = #RD.RD
						FROM #RD
						WHERE ReportCashier.[Type] = 2
						
						SELECT IsNULL(SUM(p.PaymentAmount), 0) "RB"
						INTO #RB
						FROM tblCashPayment p(nolock), tblCustProduct a(nolock)
						WHERE p.AcctID = a.AccID
						AND p.TransactionModeID IN(2)	
						AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
						AND a.PackageID IN (' . $Package . ')
						
						UPDATE ReportCashier 
						SET ReportCashier.RB = #RB.RB
						FROM #RB
						WHERE ReportCashier.[Type] = 2
						
						SELECT inf.BillEndDate, IsNULL(SUM(p.PaymentAmount), 0) "CN"
						INTO #CN
						FROM tblCustomerInvoice i(nolock), tblCashPayment p(nolock), tblSysBillRunCycleInfo inf(nolock), tblCustProduct a(nolock)
						WHERE p.InvoiceID = i.InvoiceID
							AND p.AcctID = a.AccID
							AND i.BillingCycleID = inf.CycleID	 
							AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
							AND a.PackageID IN (' . $Package . ')	   
							AND p.TransactionModeID = 14
						GROUP BY inf.BillEndDate
						
						UPDATE ReportCashier 
						SET ReportCashier.CN = #CN.CN
						FROM #CN
						WHERE ReportCashier.[Type] = 1
					 
					SELECT inf.BillEndDate, IsNULL(SUM(p.PaymentAmount), 0) "WB"
						INTO #WB
						FROM tblCustomerInvoice i(nolock), tblCashPayment p(nolock), tblSysBillRunCycleInfo inf(nolock), tblCustProduct a(nolock)
						WHERE p.InvoiceID = i.InvoiceID
							AND p.AcctID = a.AccID
							AND i.BillingCycleID = inf.CycleID	 
							AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
							AND a.PackageID IN (' . $Package . ')	   
							AND p.TransactionModeID = 15
					GROUP BY inf.BillEndDate
						
						UPDATE ReportCashier 
						SET ReportCashier.WB = #WB.WB
						FROM #WB
						WHERE ReportCashier.[Type] = 1
						
						SELECT inf.BillEndDate, IsNULL(SUM(p.PaymentAmount), 0) "SB"
						INTO #SB
						FROM tblCustomerInvoice i(nolock), tblCashPayment p(nolock), tblSysBillRunCycleInfo inf(nolock), tblCustProduct a(nolock)
						WHERE p.InvoiceID = i.InvoiceID
							AND p.AcctID = a.AccID
							AND i.BillingCycleID = inf.CycleID	 
							AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
							AND a.PackageID IN (' . $Package . ')	   
							AND p.TransactionModeID = 10
					GROUP BY inf.BillEndDate
						
						UPDATE ReportCashier 
						SET ReportCashier.SB = #SB.SB
						FROM #SB
						WHERE ReportCashier.[Type] = 1
				
					 SELECT * FROM ReportCashier
						DROP TABLE #Pay
						DROP TABLE #DTB
						DROP TABLE #BD
						DROP TABLE #AP
						DROP TABLE #RD
						DROP TABLE #RB
						DROP TABLE #CN
						DROP TABLE #WB
						DROP TABLE #SB
						
						BEGIN TRY
							DROP TABLE ReportCashier
						END TRY
						BEGIN CATCH
						END CATCH
				';	

			?>
	<table border="0" cellpadding="0" cellspacing="0" align="left" width="100%">
		<tr>
			<td>
				<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
					<tr>
						<td align="left" class="formtitle">
							<b>CASH DRAWER REPORT</b>
						</td>
						<td align="right"></td>
					</tr>
					<tr>
						<td align="left">
							<table border="0" cellpadding="1" cellspacing="0" align="left" class="formbg" width="100%">		
								<tr>
									<td align="left" class="formtitle"><b>Invoices</b></td>
								</tr>
								<tr>
									<td> 
										<table border="0" cellpadding="3" cellspacing="0" align="left" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa" bgColor="#ffffff" width="100%">
											<tr>
												<th align="center" style="border:1px solid">Cycle</th>
												<th align="center" style="border:1px solid">No Inv</th>
												<th align="center" style="border:1px solid">Deposit to balance</th>
												<th align="center" style="border:1px solid">Book deposit</th>
												<th align="center" style="border:1px solid">Book balance</th>
												<th align="center" style="border:1px solid">Fee payment</th>
												<th align="center" style="border:1px solid">Refund deposit</th>
												<th align="center" style="border:1px solid">Refund balance</th>
												<th align="center" style="border:1px solid">Credit note</th>
												<th align="center" style="border:1px solid">Write off</th>
												<th align="center" style="border:1px solid">Settle invoice</th>
												<th align="center" style="border:1px solid">Total</th>
											</tr>
											<?php
												if($que = $mydb->sql_query($sql)){
													$iLoop = 0;
													$totalNoInv = 0;
													$totaldtb = 0.00;
													$totalbd = 0.00;
													$totalap = 0.00;
													$totalpaid = 0.00;
													$totalrd = 0.00;
													$totalrb = 0.00;
													$totalcn = 0.00;
													$totalwb = 0.00;
													$totalsb = 0.00;
													$totalto = 0.00;
													$BillEndDate = $result['BillEndDate'];
													while($result = $mydb->sql_fetchrow($que)){
														$BillEndDate = $result['BillEndDate'];
														$Type = $result['Type'];
														$NoInv = intval($result['NoInv']);
														$DTB = doubleval($result['DTB']);
														$BD = doubleval($result['BD']);
														$AP = doubleval($result['AP']);
														$Paid = doubleval($result['Paid']);
														$RD = doubleval($result['RD']);
														$RB = doubleval($result['RB']);
														$CN = doubleval($result['CN']);
														$WB = doubleval($result['WB']);
														$SB = doubleval($result['SB']);
														$total = $BD + $AP+ $Paid - $RD - $RB - $CN - $WB - $SB;
														
														$totalNoInv += ($NoInv);
														$totaldtb += ($DTB);
														$totalbd += ($BD);
														$totalap += ($AP);
														$totalpaid += ($Paid);
														$totalrd += ($RD);
														$totalrb += ($RB);
														$totalcn += ($CN);
														$totalsb += ($SB);
														$totalwb += ($WB);
														$totalto += ($total);
														$iLoop ++;
														if(($iLoop % 2) == 0)
															$style = "row2";
														else
															$style = "row1";			
														
														print '<tbody>';
														print '<tr>';
														print '<td class="'.$style.'" align="left" style="border-left:1px solid; border-top:1px dotted;">'.FormatDate($BillEndDate, 3).'</td>';	
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.$NoInv.'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($DTB).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($BD).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($AP).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($Paid).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($RD).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($RB).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($CN).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($WB).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($SB).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted; border-right:1px solid">'.FormatCurrency($total).'</td>';	
														print '</tr>';											
													}
														print '</tbody>';
														print '<tfoot>
																		<tr class="sortbottom">
																	';
														print '<td style="border:1px solid" align="left">Total</td>';	
														print '<td style="border:1px solid" align="right">'.$totalNoInv.'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totaldtb).'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totalbd).'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totalap).'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totalpaid).'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totalrd).'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totalrb).'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totalcn).'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totalwb).'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totalsb).'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totalto).'</td>';	
														print '</tr>';
														print '</tfoot>';
												}else{
													$error = $mydb->sql_error();
													print $error['message'];
												}
											?>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<div id="d-1"></div>
			</td>
		</tr>
		<tr>
			<td>
				<div id="d-2"></div>
			</td>
		</tr>
	</table>