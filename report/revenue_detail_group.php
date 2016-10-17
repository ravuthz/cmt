<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="keywords" content="BRC Technology" />
		<meta name="reply-to" content="cheavey@brc-tech.com" />
		<title>..:: Wise Biller ::..</title>
		<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
		<script language="JavaScript" src="../javascript/ajax_gettransaction.js"></script>		
		<style>
			td{
					font-size:10px;
				}
			th{
				font-size:10px;
			}
		</style>
	</head>
	<body>
		<?php
			require_once("../common/agent.php");
			require_once("../common/functions.php");	
			$sid=$_GET['sid'];
			$st=$_GET['st'];
			$it=$_GET['it'];
			$pid=$_GET['pid'];
			$pt=$_GET['pt'];
			$tt = $_GET['tt'];
			$retOut = '				
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						REVENUE SUMMARY<br />
						Package: '.$pt.'<br />
						Invoice type: '.$tt.'<br />
						Cycle date: '.FormatDate($st, 3).'
					</td>
					<td align="right">						
					</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="2" cellspacing="0" align="center" width="100%" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border-left:1px solid #000000; border-top:1px solid #000000;" width="3%">No.</th>					
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" width="6%">Inv #</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" width="6%">Account</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" width="12%">Subscription</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" width="5%">Date</th>								
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >Monthly</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >Usage</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >Other</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >Discount</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >Net</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >VAT</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >Total</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >Paid</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000; border-right:1px solid #000000;"  >Unpaid</th>								
							</thead>
							<tbody>';
	$sql = "			
						BEGIN TRY
							DROP TABLE #invoice;
							DROP TABLE #tmpDemandBill;
							DROP TABLE #other
							DROP TABLE #charge;
							DROP TABLE #discount;
					END TRY
					BEGIN CATCH
					END CATCH								
						
						SELECT i.InvoiceID, a.AccID, a.UserName, a.SubscriptionName, i.BillingCycleID, i.IssueDate, i.NetAmount, 
								i.VATAmount, i.InvoiceAmount, i.CycleUnpaidAmount 'CycleUnpaidAmount',
								convert(int, 0) 'duration', convert(decimal (8, 2), 0.00) 'MonthlyFee',
								convert(decimal (8, 2), 0.00) 'Charge', convert(decimal (8, 2), 0.00) 'Discount',
								convert(decimal (8, 2), 0.00) 'Other'
					INTO #invoice		
					FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblSysBillRunCycleInfo inf(nolock), tblTarPackage tp(nolock)
					WHERE i.AccID = a.AccID 
						AND i.BillingCycleID = inf.CycleID
						AND a.packageid = tp.packageid
						AND tp.Serviceid = ".$sid."
						AND CONVERT(VARCHAR, inf.BillEndDate, 112) = '".FormatDate($st, 4)."'";
						
					if($it > 0)
						$sql .= "	AND i.InvoiceType = ".$it;	
					//else
//						$sql .= "	AND i.InvoiceType in(1, 2) ";			
					$sql .= "	AND inf.PackageID ".$pid;
				$sql .= "
					ORDER BY i.InvoiceID;
					
					UPDATE #invoice
					SET #invoice.MonthlyFee = 
					( Select sum(Amount) FROM tblCustomerInvoiceDetail(nolock)
						WHERE	#invoice.InvoiceID = tblCustomerInvoiceDetail.InvoiceID					
								AND tblCustomerInvoiceDetail.BillItemID = 4 
						Group by tblCustomerInvoiceDetail.Invoiceid					
					);
					
					SELECT i.InvoiceID, (i.UnpaidAmount + sum(p.PaymentAmount))'DemandUnpaid'
					INTO #tmpDemandBill
					FROM tblCustomerInvoice i(nolock), tblSysBillRunCycleInfo inf(nolock), tblCashPayment p(nolock), tblTarPackage tp(nolock)
					WHERE i.BillingCycleID = inf.CycleID
						and i.InvoiceID = p.invoiceID
						and inf.Packageid = tp.packageid
						and tp.Serviceid = ".$sid."
						and CONVERT(VARCHAR, p.PaymentDate, 112) > '".FormatDate($st, 4)."'
						and CONVERT(VARCHAR, inf.BillEndDate, 112) = '".FormatDate($st, 4)."' ";
					if($it > 0)
						$sql .= "	AND i.InvoiceType = ".$it;
					$sql .= "	AND inf.PackageID ".$pid;
					
				$sql .= " GROUP BY i.InvoiceID, i.UnpaidAmount
					
					UPDATE #invoice
					SET #invoice.CycleUnpaidAmount = #tmpDemandBill.DemandUnpaid
					FROM #tmpDemandBill(nolock)
					WHERE #invoice.InvoiceID = #tmpDemandBill.InvoiceID;					

																
					SELECT tmp.InvoiceID, convert(decimal (8, 2), Sum(tmp.Amount)) 'Charge', Sum(tmp.BillUnit) 'Duration'
					INTO #charge
					FROM #invoice i, tblCustomerInvoiceDetail tmp(nolock)
					WHERE i.InvoiceID = tmp.InvoiceID
						AND tmp.BillItemID in(1, 2, 3, 21, 30)
					GROUP BY tmp.InvoiceID;
					
					UPDATE #invoice
					SET #invoice.duration = #charge.Duration,
							#invoice.Charge = #charge.Charge
					FROM #charge
					WHERE #invoice.InvoiceID = #charge.InvoiceID;
					
					SELECT tmp.InvoiceID, convert(decimal (8, 2), Sum(tmp.Amount)) 'Discount'
					INTO #discount
					FROM #invoice i, tblCustomerInvoiceDetail tmp(nolock)
					WHERE i.InvoiceID = tmp.InvoiceID
						AND tmp.BillItemID in(12, 13, 9, 16, 17, 18, 19, 20, 22, 28)
					GROUP BY tmp.InvoiceID;
					
					UPDATE #invoice
					SET	#invoice.Discount = #discount.Discount
					FROM #discount
					WHERE #invoice.InvoiceID = #discount.InvoiceID;
					
					SELECT tmp.InvoiceID, convert(decimal (8, 2), Sum(tmp.Amount)) 'Other'
					INTO #other
					FROM #invoice i, tblCustomerInvoiceDetail tmp(nolock)
					WHERE i.InvoiceID = tmp.InvoiceID
						AND tmp.BillItemID in(6, 7, 8, 10, 14, 15, 25, 27, 29, 32)
					GROUP BY tmp.InvoiceID;
					
					UPDATE #invoice
					SET	#invoice.Other = #other.Other
					FROM #other
					WHERE #invoice.InvoiceID = #other.InvoiceID;
					
					SELECT * FROM #invoice ORDER BY InvoiceAmount - CycleUnpaidAmount, CycleUnpaidAmount desc,Discount,UserName;
					
					BEGIN TRY
						DROP TABLE #invoice;
					DROP TABLE #tmpDemandBill;
					DROP TABLE #other
					DROP TABLE #charge;
					DROP TABLE #discount;
					END TRY
					BEGIN CATCH
					END CATCH
					";

	if($que = $mydb->sql_query($sql)){		

		$n = 0;
		$iLoop = 0;
		
		$TotalMonthly = 0.00;
		$TotalUsage = 0.00;
		$TotalDiscount = 0.00;
		$TotalDuration = 0.00;
		$TotalNetAmount = 0.00;
		$TotalVATAmount = 0.00;
		$TotalInvoiceAmount = 0.00;
		$TotalPaidAmount = 0.00;
		$TotalCycleUnpaidAmount = 0.00;
		$TotalOther = 0.00;
		
		while($result = $mydb->sql_fetchrow()){																															
			$InvoiceID = $result['InvoiceID'];
			$Account = $result['UserName'];
			$SubscriptionName = $result['SubscriptionName'];
			$IssueDate = $result['IssueDate'];										
			$NetAmount = $result['NetAmount'];
			$duration = $result['duration'];
			$MonthlyFee = $result['MonthlyFee'];
			$Charge = $result['Charge'];
			$Other = $result['Other'];
			$Discount = $result['Discount'];
			$NetAmount = $result['NetAmount'];
			$VATAmount = $result['VATAmount'];
			$InvoiceAmount = $result['InvoiceAmount'];
			$CycleUnpaidAmount = $result['CycleUnpaidAmount'];			
			$PaidAmount = floatval($InvoiceAmount) - floatval($CycleUnpaidAmount);			
			
			$TotalDuration += $duration;
			$TotalMonthly += $MonthlyFee;
			$TotalUsage += $Charge;
			$TotalDiscount += $Discount;
			$TotalNetAmount += $NetAmount;
			$TotalVATAmount += $VATAmount;
			$TotalInvoiceAmount += $InvoiceAmount;
			$TotalPaidAmount += $PaidAmount;
			$TotalCycleUnpaidAmount += $CycleUnpaidAmount;		
			$TotalOther += $Other;
			
			//$InvoiceID = "<a href='javascript:showReport(".$InvoiceID.");'>".$InvoiceID."</a>";	
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																						
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #000000; border-top:1px dotted #999999;">'.$iLoop.'</td>';																																														
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$InvoiceID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Account.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;" nowrape="nowrape">'.substr($SubscriptionName, 0, 18).'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatDate($IssueDate, 3).'</td>';							
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($MonthlyFee).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($Charge).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($Other).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($Discount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($NetAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($VATAmount).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($InvoiceAmount).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($PaidAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #000000;">'.FormatCurrency($CycleUnpaidAmount).'</td>';
			$retOut .= '</tr>';									
		}		
	}else{
		$error = $mydb->sql_error();
		print $error['message'];
	}
	$mydb->sql_freeresult();
		
		$retOut .= '</tbody>
									<tfoot bgcolor="#cccccc">															
									<tr>
										<td colspan=5 align=right style="border-left:1px solid #000000; border-top:1px solid #000000; border-bottom:1px solid #000000;">Total</td>										
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalMonthly).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalUsage).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalOther).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalDiscount).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalNetAmount).'</td>										
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalVATAmount).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalInvoiceAmount).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalPaidAmount).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000; border-right:1px solid #000000;">'.FormatCurrency($TotalCycleUnpaidAmount).'</td>
									</tr>
								</tfoot>																																			
							</td>
						</tr>
					</table>
				';
			print $retOut;	
		?>
		
	</body>
</html>