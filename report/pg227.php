<?php
	require_once("../common/agent.php");	
	require_once("../common/functions.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	
?>
<link href="../style/mystyle.css" rel="stylesheet" type="text/css" />
	
<?php
	function CashCollection($pid,$invoicetypeid , $serviceid, $package, $code, $cdate){
	global $mydb, $bgUnactivate, $foreUnactivate, $bgActivate, $foreActivate, $bgLock, $foreLock, $bgClose, $foreClose;

$retOut = '<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						 <b>'.$package.'</b>
						
					</td>
					<td align="right"><!--[<a href="../export/cash_collection.php?code=<?php print $code; ?>&m=<?php print $m; ?>&type=csv">Download</a>]--></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid #999999" width="3%">No</th>
								<th align="center" style="border:1px solid #999999" width="8%">InvoiceType</th>
								<th align="center" style="border:1px solid #999999" width="8%">Invoice</th>
								<th align="center" style="border:1px solid #999999" width="8%">Acc ID</th>
								<th align="center" style="border:1px solid #999999" width="8%">Account</th>
								<th align="center" style="border:1px solid #999999" width="18%">Subscription</th>
								<th align="center" style="border:1px solid #999999" width="4%">Status</th>								
								<th align="center" style="border:1px solid #999999" width="6%">Date</th>
								<th align="center" style="border:1px solid #999999" width="6%">Pay date</th>
								<th align="center" style="border:1px solid #999999" width="9%">Net</th>
								<th align="center" style="border:1px solid #999999" width="9%">VAT</th>
								<th align="center" style="border:1px solid #999999" width="9%">Total</th>
								<th align="center" style="border:1px solid #999999" width="9%">Paid</th>
								<th align="center" style="border:1px solid #999999" width="9%">Unpaid</th>																						
							</thead>
							<tbody>';
								
								
										$code = FixQuotes($code);
										$m = FixQuotes($m);
										$y = FixQuotes($y);

																				
										$sql = "SELECT it.InvoiceType, i.InvoiceType 'InvoiceTypeID', i.InvoiceID, i.IssueDate, i.Duedate, i.InvoiceAmount, i.NetAmount, i.VATAmount, i.UnpaidAmount,
														a.CustID, a.AccID, a.StatusID, a.UserName, a.SubscriptionName, Sum(p.PaymentAmount) 'PaymentAmount',
														p.PaymentDate 
										FROM tblCustomerInvoice i(nolock), tblCashPayment p(nolock), tblCustProduct a(nolock), 
													tblTarPackage ta(nolock), tblSysBillRunCycleInfo inf(nolock), tblInvoiceType it(nolock)
										WHERE i.InvoiceID = p.InvoiceID
													AND i.InvoiceType = it.InvoiceTypeID 
													AND i.AccID = a.AccID
													AND i.BillingCycleID = inf.CycleID
													AND inf.PackageID = ta.PackageID
													AND i.InvoiceType = ".$invoicetypeid."
													AND left(convert(varchar, p.PaymentDate,120),7)  = '".$code."'																		
													AND CONVERT(VARCHAR, inf.BillEndDate, 112) = '".$cdate."' ";																			
									if($pid > 0)
										$sql .= " AND ta.PackageID = $pid";
									else{
										if($serviceid == 2)
											$sql .= " AND ServiceID in(2) ";
										elseif($serviceid == 4)
											$sql .= " AND ServiceID in(4) ";
										elseif($serviceid == 5)
											$sql .= " AND ServiceID in(1, 3, 8) ";
									}
									$sql .= " GROUP BY it.InvoiceType, i.InvoiceType, i.InvoiceID, i.IssueDate, i.Duedate, i.InvoiceAmount, i.NetAmount, i.VATAmount, i.UnpaidAmount,
																			a.CustID, a.AccID, a.StatusID, a.UserName, a.SubscriptionName, p.PaymentDate
														ORDER BY i.InvoiceType desc, a.UserName ";
														
									if($que = $mydb->sql_query($sql)){
										$totalAmount = 0.00;
										$totalVATAmount = 0.00;
										$totalUnpaidAmount = 0.00;
										$totalpaidAmount = 0.00;
										$totalNet = 0.00;
										$iLoop = 0;
										while($result = $mydb->sql_fetchrow()){																
											$InvoiceType = $result['InvoiceType'];
											$InvoiceID = $result['InvoiceID'];
											$TarName = $result['TarName'];																
											$IssueDate = $result['IssueDate'];
											$Duedate = $result['Duedate'];											
											$InvoiceAmount = $result['InvoiceAmount'];																
											$StatusID = intval($result['StatusID']);											
											$NetAmount = $result['NetAmount'];
											$VATAmount = $result['VATAmount'];
											$UnpaidAmount = $result['UnpaidAmount'];
											$PaidAmount = floatval($InvoiceAmount) - floatval($UnpaidAmount);
											$CustID = $result['CustID'];
											$AccID = $result['AccID'];
											$UserName = $result['UserName'];
											$PaymentDate = $result['PaymentDate'];
											$PaymentAmount = $result['PaymentAmount'];
											$SubscriptionName = $result['SubscriptionName'];
											switch($StatusID){
												case 0:
													$stbg = $bgUnactivate;
													$stfg = $foreUnactivate;
													$stwd = "Inactive";
													break;
												case 1:
													$stbg = $bgActivate;
													$stfg = $foreActivate;
													$stwd = "Active";
													break;
												case 2:
													$stbg = $bgLock;
													$stfg = $foreLock;
													$stwd = "Barred";
													break;
												case 3:
													$stbg = $bgClose;
													$stfg = $foreClose;
													$stwd = "Closed";
													break;
												case 4:
													$stbg = $bgClose;
													$stfg = $foreClose;
													$stwd = "Closed";
													break;
											}
											$linkInv = "<a href='../finance/screeninvoice.php?CustomerID=".$CustID."&InvoiceID=".$InvoiceID."' target='_blank'>".$InvoiceID."</a>";
											$linkAcct = "<a href='../?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
											$totalAmount += floatval($InvoiceAmount);
											$totalUnpaidAmount += floatval($UnpaidAmount);
											$totalVATAmount += floatval($VATAmount);
											$totalNet += floatval($NetAmount);
											$totalpaidAmount += floatval($PaymentAmount);
											$iLoop++;															
											
											//if(($iLoop % 2) == 0)
											if(($iLoop % 2) ==0)
												$style = "row1";
											else
												$style = "row2";
											$retOut .= '<tr>';	
											$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px dotted #999999; border-right:1px dotted #999999">'.$iLoop.'</td>';
											$retOut .= '<td class="'.$style.'" align="left" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$InvoiceType.'</td>';																		
											$retOut .= '<td class="'.$style.'" align="left" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$linkInv.'</td>';
											$retOut .= '<td class="'.$style.'" align="left" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$AccID.'</td>';
											$retOut .= '<td class="'.$style.'" align="left" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$linkAcct.'</td>';
											$retOut .= '<td class="'.$style.'" align="left" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.substr($SubscriptionName, 0, 16).'</td>';	
											$retOut .= '<td align="center" bgcolor="'.$stbg.'" style="border-top:1px dotted #999999; border-right:1px dotted #999999">
															<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
														 </td>';													
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.formatDate($IssueDate, 3).'</td>';
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.formatDate($PaymentDate, 3).'</td>';
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($NetAmount).'</td>';
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($VATAmount).'</td>';
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($InvoiceAmount).'</td>';
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($PaymentAmount).'</td>';
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px solid #999999">'.FormatCurrency($UnpaidAmount).'</td>';
											$retOut .= '</tr>';
										}
									}
									$mydb->sql_freeresult();	
								
							$retOut .= '</tbody>
							<tfoot class="sortbottom">
								<tr>
									<td align="right" colspan="9" style="border:1px solid #999999">Total</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalNet).'</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalVATAmount).'</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalAmount).'</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalpaidAmount).'</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalUnpaidAmount).'</td>
								</tr>
							</tfoot>												
						</table>						
					</td>
				</tr>
			</table>
		</td>
	</tr>						
</table> ';
return $retOut;

	}
	$retfun = "<table border=0 cellpadding=0 cellspacing=0 width='100%'>
							<tr>
								<td align=left>CASH COLLECTION<br>
									PAYMENT: <b>".$code."</b><br>
									CYCLE DATE: <b>".substr($cdate, 4, 2)."-".substr($cdate, 0, 4)."</b><br>
									PRINT ON: <b>".date("d M Y H:i:s")."</b><br><br>&nbsp;
								</td>
							</tr>
						";
		$sql = "SELECT PackageID, TarName, ServiceID 	
						FROM tblTarPackage(nolock) WHERE 1=1 ";
		if($serviceid == 2)
			$sql .= " AND ServiceID in(2) ";
		elseif($serviceid == 4)
			$sql .= " AND ServiceID in(4) ";
		elseif($serviceid == 5)
			$sql .= " AND ServiceID in(1, 3, 8) ";		
	$sql .= " ORDER BY 2 ";	

	//if($que = $mydb->sql_query($sql)){
//		while($result = $mydb->sql_fetchrow($que)){
//			$ServiceID = $result['ServiceID'];
//			$tarid = $result['PackageID'];
//			$TarName = $result['TarName'];
//				$retfun .= "<tr><td>";
//				$retfun .= CashCollection($tarid, $serviceid, "Package: <b>".$TarName."</b>", $code, $cdate);
//				$retfun .= "</td></tr>";
//		}
//	}
	$retfun .= "<tr><td><br /></td></tr>";
	$retfun .= "<tr><td>";
		$retfun .= CashCollection(0, $invoicetypeid, $serviceid, "<b></b>", $code, $cdate);
	$retfun .= "</td></tr>";
	$retfun .= "</table>";
	print $retfun;
	
?>