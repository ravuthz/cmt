<html>
	<head>
		<title>..:: Wise Biller ::..</title>
		<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
		<script language="JavaScript" src="../javascript/loading.js"></script>
		<script language="JavaScript" src="../javascript/ajax_gettransaction.js"></script>		
		<script language="javascript" type="text/javascript">
			function showReport1(st, et, pt, cycle, package){
									
					var loading;
			loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='../images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			//		document.getElementById("d-invoice").innerHTML = loading;	
					document.getElementById("d-1").innerHTML = loading;																								
																									
					url2 = "./cashier_1.php?st="+st+"&et="+et+"&where="+package+"&cycle="+cycle+"&pt="+pt+"&mt="+ new Date().getTime();
					getTranDetail(url2, "d-1");	
					document.getElementById("d-3").innerHTML = "";
					document.getElementById("d-2").innerHTML = "";
													
			}
			function showReport2(st, et, pt, cycle, package, md, mn){
									
					var loading;
			loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='../images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			//		document.getElementById("d-invoice").innerHTML = loading;	
					//alert("haha");
					document.getElementById("d-2").innerHTML = loading;																								
																									
					url2 = "./cashier_2.php?st="+st+"&et="+et+"&where="+package+"&cycle="+cycle+"&pt="+pt+"&md="+md+"&mn="+mn+"&mt="+ new Date().getTime();
					getTranDetail(url2, "d-2");	
					document.getElementById("d-3").innerHTML = "";								
			}
			function showReport3(st, et, pt, cycle, package, md, mn, pn){
									
					var loading;
			loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='../images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			//		document.getElementById("d-invoice").innerHTML = loading;	
					//alert("haha");
					document.getElementById("d-3").innerHTML = loading;																								
																									
					url2 = "./cashier_3.php?st="+st+"&et="+et+"&package="+package+"&cycle="+cycle+"&pt="+pt+"&md="+md+"&mn="+mn+"&pn="+pn+"&mt="+ new Date().getTime();
					getTranDetail(url2, "d-3");	
													
			}
		</script>	
	</head>
	<body>
			<?php
				require_once("../common/agent.php");
				require_once("../common/functions.php");	
				$FromDate = $_GET['st'];	
				$ToDate = $_GET['et'];
				$Package = $_GET['where'];
				$service = $_GET['service'];
				
				$sql = "select TarName from tblTarPackage where PackageID in (".$Package.") order by TarName";
				if($que = $mydb->sql_query($sql)){
					$Pacakge = "";
					while($result = $mydb->sql_fetchrow($que)){
						$PacakgeName = $result['TarName'];
						$Pacakge .= "[".$PacakgeName."] ";
					}
					
				}
				$mydb->sql_freeresult();
				
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

					SELECT inf.BillEndDate, p.PaymentModelID, COUNT(i.InvoiceID) "NoInv", convert(numeric(8, 2), 0.00) "DTB", 
							convert(numeric(8, 2), 0.00) "BD", convert(numeric(8, 2), 0.00) "AP", convert(numeric(8, 2), 0.00) "Paid",
							convert(numeric(8, 2), 0.00) "RD", convert(numeric(8, 2), 0.00) "RB", convert(numeric(8, 2), 0.00) "CN", 
							convert(numeric(8, 2), 0.00) "WB", convert(numeric(8, 2), 0.00) "SB", 1 "Type"
					INTO ReportCashier
					FROM tblCustomerInvoice i(nolock), tblCashPayment p(nolock), tblSysBillRunCycleInfo inf(nolock), tblCustProduct a(nolock)
					WHERE p.InvoiceID = i.InvoiceID
						AND p.AcctID = a.AccID
						AND i.BillingCycleID = inf.CycleID	 
						AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
						AND a.PackageID IN (' . $Package . ')	   						
					GROUP BY inf.BillEndDate, p.PaymentModelID
						;
					SELECT inf.BillEndDate,  
							IsNULL(SUM(p.PaymentAmount), 0.00) "Paid", 1 "Type", p.PaymentModelID
					INTO #Pay
					FROM tblCustomerInvoice i(nolock), tblCashPayment p(nolock), tblSysBillRunCycleInfo inf(nolock), tblCustProduct a(nolock)
					WHERE p.InvoiceID = i.InvoiceID
						AND p.AcctID = a.AccID
						AND i.BillingCycleID = inf.CycleID	 
						AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
						AND a.PackageID IN (' . $Package . ')	   
						AND p.TransactionModeID = 1 
					GROUP BY inf.BillEndDate, p.PaymentModelID
					
					UPDATE ReportCashier 
						SET ReportCashier.Paid = #Pay.Paid
						FROM #Pay
						WHERE ReportCashier.[Type] = 1 and ReportCashier.PaymentModelID = #Pay.PaymentModelID
							AND ReportCashier.BillEndDate = #Pay.BillEndDate
						
					INSERT INTO ReportCashier(BillEndDate, PaymentModelID, NoInv, DTB, BD, AP, Paid, RD, RB, CN, WB, SB, [Type])
						VALUES("", 1, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 2)
					INSERT INTO ReportCashier(BillEndDate, PaymentModelID, NoInv, DTB, BD, AP, Paid, RD, RB, CN, WB, SB, [Type])
						VALUES("", 2, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 2)
					INSERT INTO ReportCashier(BillEndDate, PaymentModelID, NoInv, DTB, BD, AP, Paid, RD, RB, CN, WB, SB, [Type])
						VALUES("", 3, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 2)
											
						SELECT IsNULL(SUM(p.PaymentAmount), 0) "DTB", p.PaymentModelID
						INTO #DTB
						FROM tblCashPayment p(nolock), tblCustProduct a(nolock)    
						WHERE p.AcctID = a.AccID
							AND p.TransactionModeID IN(11, 12, 13)	
							AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
							AND a.PackageID IN (' . $Package . ')
						GROUP BY p.PaymentModelID
						
						UPDATE ReportCashier 
						SET ReportCashier.DTB = #DTB.DTB
						FROM #DTB
						WHERE ReportCashier.[Type] = 2 and ReportCashier.PaymentModelID = #DTB.PaymentModelID
						
						SELECT IsNULL(SUM(p.PaymentAmount), 0) "BD", p.PaymentModelID
						INTO #BD
						FROM tblCashPayment p(nolock), tblCustProduct a(nolock)
						WHERE p.AcctID = a.AccID
							AND p.TransactionModeID IN(4, 5, 6)	
							AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
							AND a.PackageID IN (' . $Package . ')
						GROUP BY p.PaymentModelID
						
						UPDATE ReportCashier 
						SET ReportCashier.BD = #BD.BD
						FROM #BD
						WHERE ReportCashier.[Type] = 2 and ReportCashier.PaymentModelID = #BD.PaymentModelID
						
						SELECT IsNULL(SUM(p.PaymentAmount), 0) "AP", p.PaymentModelID
						INTO #AP
						FROM tblCashPayment p(nolock), tblCustProduct a(nolock)
						WHERE p.AcctID = a.AccID
							AND p.TransactionModeID IN(3)	
							AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
							AND a.PackageID IN (' . $Package . ')
						GROUP BY p.PaymentModelID
						
						UPDATE ReportCashier 
						SET ReportCashier.AP = #AP.AP
						FROM #AP
						WHERE ReportCashier.[Type] = 2 and ReportCashier.PaymentModelID = #AP.PaymentModelID
						
						SELECT IsNULL(SUM(p.PaymentAmount), 0) "RD", p.PaymentModelID
						INTO #RD
						FROM tblCashPayment p(nolock), tblCustProduct a(nolock)
						WHERE p.AcctID = a.AccID
							AND p.TransactionModeID IN(7, 8, 9)	
							AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
							AND a.PackageID IN (' . $Package . ')
						GROUP BY p.PaymentModelID
						
						UPDATE ReportCashier 
						SET ReportCashier.RD = #RD.RD
						FROM #RD
						WHERE ReportCashier.[Type] = 2 and ReportCashier.PaymentModelID = #RD.PaymentModelID
						
						SELECT IsNULL(SUM(p.PaymentAmount), 0) "RB", p.PaymentModelID
						INTO #RB
						FROM tblCashPayment p(nolock), tblCustProduct a(nolock)
						WHERE p.AcctID = a.AccID
							AND p.TransactionModeID IN(2)	
							AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
							AND a.PackageID IN (' . $Package . ')
						GROUP BY p.PaymentModelID
						
						UPDATE ReportCashier 
						SET ReportCashier.RB = #RB.RB
						FROM #RB
						WHERE ReportCashier.[Type] = 2 and ReportCashier.PaymentModelID = #RB.PaymentModelID
						
						SELECT inf.BillEndDate, IsNULL(SUM(p.PaymentAmount), 0) "CN", p.PaymentModelID
						INTO #CN
						FROM tblCustomerInvoice i(nolock), tblCashPayment p(nolock), tblSysBillRunCycleInfo inf(nolock), tblCustProduct a(nolock)
						WHERE p.InvoiceID = i.InvoiceID
							AND p.AcctID = a.AccID
							AND i.BillingCycleID = inf.CycleID	 
							AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
							AND a.PackageID IN (' . $Package . ')	   
							AND p.TransactionModeID = 14
						GROUP BY inf.BillEndDate, p.PaymentModelID
						
						UPDATE ReportCashier 
						SET ReportCashier.CN = #CN.CN
						FROM #CN
						WHERE ReportCashier.[Type] = 1 and ReportCashier.PaymentModelID = #CN.PaymentModelID
					 
					SELECT inf.BillEndDate, IsNULL(SUM(p.PaymentAmount), 0) "WB", p.PaymentModelID
						INTO #WB
						FROM tblCustomerInvoice i(nolock), tblCashPayment p(nolock), tblSysBillRunCycleInfo inf(nolock), tblCustProduct a(nolock)
						WHERE p.InvoiceID = i.InvoiceID
							AND p.AcctID = a.AccID	
							AND i.BillingCycleID = inf.CycleID	 
							AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
							AND a.PackageID IN (' . $Package . ')	   
							AND p.TransactionModeID = 15
					GROUP BY inf.BillEndDate, p.PaymentModelID
						
						UPDATE ReportCashier 
						SET ReportCashier.WB = #WB.WB
						FROM #WB
						WHERE ReportCashier.[Type] = 1 and ReportCashier.PaymentModelID = #WB.PaymentModelID
						
						SELECT inf.BillEndDate, IsNULL(SUM(p.PaymentAmount), 0) "SB", p.PaymentModelID
						INTO #SB
						FROM tblCustomerInvoice i(nolock), tblCashPayment p(nolock), tblSysBillRunCycleInfo inf(nolock), tblCustProduct a(nolock)
						WHERE p.InvoiceID = i.InvoiceID
							AND p.AcctID = a.AccID
							AND i.BillingCycleID = inf.CycleID	 
							AND CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
							AND a.PackageID IN (' . $Package . ')	   
							AND p.TransactionModeID = 10
					GROUP BY inf.BillEndDate, p.PaymentModelID
						
						UPDATE ReportCashier 
						SET ReportCashier.SB = #SB.SB
						FROM #SB
						WHERE ReportCashier.[Type] = 1 and ReportCashier.PaymentModelID = #SB.PaymentModelID
				
					 SELECT * FROM ReportCashier order by 1 
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
							<b>TOTAL CASH DAILY COLLECTION REPORT</b><br>
							Service: <?php print $service; ?><br>
							Package: <?php print $Pacakge; ?><br>
							From: <?php print $st; ?> to: <?php print $et; ?><br>							
						</td>
						<td align="right" valign="bottom" class="formtitle" nowrap="nowrap">Printed on: <?php print date("Y-m-d H:i:s"); ?></td>
					</tr>
					<tr>
						<td align="left" colspan="2">
							<table border="0" cellpadding="1" cellspacing="0" align="left" class="formbg" width="100%">		
								<tr>
									<td align="left" class="formtitle"><b>Invoices</b></td>
									<td align="right">
										[<a href="../export/cashier.php?st=<?php print $FromDate; ?>&et=<?php print $ToDate; ?>&where=<?php print $Package;?>">Download</a>]
									</td>
								</tr>
								<tr>
									<td colspan="2"> 
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
													$tmpBillDate = $BillEndDate;														
													while($result = $mydb->sql_fetchrow($que)){
														$PaymentModelID = $result['PaymentModelID'];
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
														
														if($BillEndDate != $tmpBillDate){															
														
															$tmpOut = '<tr>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px solid; border-top:1px dotted;">TOTAL</td>';	
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.$tmpNoInv.'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmpdtb).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmpbd).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmpap).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmppaid).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmprd).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmprb).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmpcn).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmpwb).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmpsb).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted; border-right:1px solid">'.$tmplink.'</td>';	
															$tmpOut .= '</tr>';
															$tmpOut .= '<tr><td colspan=12 class="row2">&nbsp;</td></tr>';															
														}
														
														if($total > 0){
															$link = "<a href=\"javascript:showReport1('".$FromDate."', '".$ToDate."', ".$Type.", '".FormatDate($BillEndDate, 4)."', '".$Package."');\">".FormatCurrency($total)."</a>";
														}
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
														
														if($PaymentModelID == 1){
															$paytype = "Cash";
														}
														elseif($PaymentModelID == 2){														
															$paytype = "Cheque";
														}
														else
															$paytype = "Other";
															
														$iLoop ++;
														//if(($iLoop % 2) == 0)
//															$style = "row2";
//														else
															$style = "row1";			
														
														//$tmpBillDate = $BillEndDate;
														
														print '<tbody>';	
														if(($BillEndDate != $tmpBillDate) && ($tmpBillDate != "")){
															print $tmpOut;
															$tmpNoInv = 0;
															$tmpdtb = 0.00;
															$tmpbd = 0.00;
															$tmpap = 0.00;
															$tmppaid = 0.00;
															$tmprd = 0.00;
															$tmprb = 0.00;
															$tmpcn = 0.00;
															$tmpwb = 0.00;
															$tmpsb = 0.00;
															$tmpto = 0.00;
														}													
														print '<tr>';
														print '<td class="'.$style.'" align="left" style="border-left:1px solid; border-top:1px dotted;">'.FormatDate($BillEndDate, 3).'</td>';	
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.$NoInv.'('.$paytype.')</td>';
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
																															
														print '</tfoot>';
														if($BillEndDate == $tmpBillDate){
															$tmpNoInv += intval($NoInv);
															$tmpdtb += $DTB;
															$tmpbd += $BD;
															$tmpap += $AP;
															$tmppaid += $Paid;
															$tmprd += $RD;
															$tmprb += $RB;
															$tmpcn += $CN;
															$tmpwb += $WB;
															$tmpsb += $SB;
															$tmpto += ($BD + $AP+ $Paid - $RD - $RB - $CN - $WB - $SB);
														}else{
															$tmpNoInv = intval($NoInv);
															$tmpdtb = $DTB;
															$tmpbd = $BD;
															$tmpap = $AP;
															$tmppaid = $Paid;
															$tmprd = $RD;
															$tmprb = $RB;
															$tmpcn = $CN;
															$tmpwb = $WB;
															$tmpsb = $SB;
															$tmpto = $BD + $AP+ $Paid - $RD - $RB - $CN - $WB - $SB;															
														}
														if($tmpto > 0){
															$tmplink = "<a href=\"javascript:showReport1('".$FromDate."', '".$ToDate."', ".$Type.", '".FormatDate($BillEndDate, 4)."', '".$Package."');\">".FormatCurrency($tmppaid)."</a>";
														}
														$tmpBillDate = $BillEndDate;					
													}
														$tmpOut = '<tr>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px solid; border-top:1px dotted;">TOTAL</td>';	
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.$tmpNoInv.'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmpdtb).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmpbd).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmpap).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmppaid).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmprd).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmprb).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmpcn).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmpwb).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($tmpsb).'</td>';
															$tmpOut .= '<td align="right" bgColor="#cccccc" style="border-left:1px dotted; border-top:1px dotted; border-right:1px solid">'.$tmplink.'</td>';	
															$tmpOut .= '</tr>';
															$tmpOut .= '<tr><td colspan=12 class="row2">&nbsp;</td></tr>';
															print $tmpOut;
															
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
		<tr>
			<td>
				<div id="d-3"></div>
			</td>
		</tr>
	</table>
						
	</body>
</html>