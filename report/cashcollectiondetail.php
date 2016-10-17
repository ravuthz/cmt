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

	function CashCollection($pid, $serviceid, $package, $code, $cdate,$where){
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
								<th align="center" style="border:1px solid #999999" width="8%">InvType</th>
								<th align="center" style="border:1px solid #999999" width="8%">InvoiceID</th>
								<th align="center" style="border:1px solid #999999" width="8%">AccID</th>
								<th align="center" style="border:1px solid #999999" width="8%">UserName</th>
								<th align="center" style="border:1px solid #999999" width="18%">Subscription</th>
								<th align="center" style="border:1px solid #999999" width="4%">Status</th>								
								<th align="center" style="border:1px solid #999999" width="6%">InvDate</th>
								<th align="center" style="border:1px solid #999999" width="6%">PayDate</th>
								<th align="center" style="border:1px solid #999999" width="9%">NetAmt</th>
								<th align="center" style="border:1px solid #999999" width="9%">VAT</th>
								<th align="center" style="border:1px solid #999999" width="9%">InvAmt</th>
								<th align="center" style="border:1px solid #999999" width="9%">Credit</th>
								<th align="center" style="border:1px solid #999999" width="9%">AmtDue</th>
								<th align="center" style="border:1px solid #999999" width="9%">aPaid</th>
								<th align="center" style="border:1px solid #999999" width="9%">bUnpaid</th>
								<th align="center" style="border:1px solid #999999" width="9%">cPaid</th>
								<th align="center" style="border:1px solid #999999" width="9%">Balance</th>																						
							</thead>
							<tbody>';
								
								
										$code = FixQuotes($code);
										$m = FixQuotes($m);
										$y = FixQuotes($y);

																				
										$sql = "SELECT it.InvoiceType, ci.InvoiceType 'InvoiceTypeID', ci.InvoiceID, ci.IssueDate, ci.NetAmount, ci.VATamount, ci.InvoiceAmount,  ci.UnpaidAmount, cp.AccID, cp.StatusID, cp.UserName, cp.SubscriptionName, ca.PaymentDate, ci.OriginalUnpaidAmount, 
										IsNull((select sum(PaymentAmount) from tblCashPayment where left(convert(varchar,PaymentDate,112),6)  <= ".str_replace("-","",$code)." and InvoiceID = ci.InvoiceID),0) TotalPaid,
										Sum(ca.PaymentAmount) 'CurPaid'
										FROM tblCustomerInvoice ci(nolock)
										join tblInvoiceType it(nolock) on it.InvoiceTypeID = ci.InvoiceType
										join tblCashPayment ca(nolock) on ca.InvoiceID = ci.InvoiceID 
										join tblTrackAccount cp(nolock) on cp.TrackID = ci.TrackID 
										join tblSysBillRunCycleInfo sb(nolock) on sb.CycleID = ci.BillingCycleID
										join tblTarPackage tp(nolock) on tp.PackageID = sb.PackageID
										WHERE  left(convert(varchar,ca.paymentdate,120),7) = '".$code."'																		
													AND CONVERT(VARCHAR, sb.BillEndDate, 112) = '".$cdate."' 
												and iCityID1 in (".$where.")	
												
													";																			
									if($pid > 0)
										$sql .= " AND tp.PackageID = $pid";
									else{
										if($serviceid == 2)
											$sql .= " AND ServiceID in(2) ";
										elseif($serviceid == 4)
											$sql .= " AND ServiceID in(4) ";
										elseif($serviceid == 5)
											$sql .= " AND ServiceID in(1, 3, 8) ";
									}
									$sql .= " GROUP BY it.InvoiceType, ci.InvoiceType, ci.InvoiceID, ci.IssueDate,ci.NetAmount, ci.VATamount, ci.InvoiceAmount,  ci.UnpaidAmount, cp.AccID, cp.StatusID, cp.UserName, cp.SubscriptionName, ca.PaymentDate, ci.OriginalUnpaidAmount
														ORDER BY ci.InvoiceType desc, cp.UserName ";
													
									if($que = $mydb->sql_query($sql)){
										
										$totalNetAmt = 0.00;
										$totalVATamt = 0.00;
										$totalInvAmt = 0.00;
										$totalCredit = 0.00;
										$totalAmtDue = 0.00;
										$totalaPaid = 0.00;
										$totalbUnpaid = 0.00;
										$totalcPaid = 0.00;
										$totalBalance = 0.00;
										
										$iLoop = 0;
										while($result = $mydb->sql_fetchrow()){																
											$InvoiceType = $result['InvoiceType'];
											$InvoiceID = $result['InvoiceID'];
											$TarName = $result['TarName'];																
											$IssueDate = $result['IssueDate'];
											$PaymentDate = $result['PaymentDate'];
											$StatusID = intval($result['StatusID']);											
											$NetAmt = $result['NetAmount'];																
											$VATamt = $result['VATamount'];																
											$InvAmt = $result['InvoiceAmount'];																
											$AmtDue = $result['OriginalUnpaidAmount'];												
											$Credit = $InvAmt - $AmtDue;
											$cPaid = $result['CurPaid'];
											$aPaid = $result['TotalPaid'] - $cPaid;
											$bUnpaid = $AmtDue - $aPaid;
											$Balance = $cPaid - $bUnpaid;
											
											$CustID = $result['CustID'];
											$AccID = $result['AccID'];
											$UserName = $result['UserName'];
											
											
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
											
											$totalNetAmt += floatval($NetAmt);
											$totalVATamt += floatval($VATamt);
											$totalInvAmt += floatval($InvAmt);
											$totalCredit += floatval($Credit);
											$totalAmtDue += floatval($AmtDue);
											$totalaPaid += floatval($aPaid);
											$totalbUnpaid += floatval($bUnpaid);
											$totalcPaid += floatval($cPaid);
											$totalBalance += floatval($Balance);
											
											
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
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($NetAmt).'</td>';
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($VATamt).'</td>';											
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($InvAmt).'</td>';
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($Credit).'</td>';
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($AmtDue).'</td>';
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($aPaid).'</td>';

											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($bUnpaid).'</td>';
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($cPaid).'</td>';
											$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px solid #999999">'.FormatCurrency($Balance).'</td>';
											$retOut .= '</tr>';
										}
									}
									$mydb->sql_freeresult();	
								
							$retOut .= '</tbody>
							<tfoot class="sortbottom">
								<tr>
									<td align="center" colspan="9" style="border:1px solid #999999">Total</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalNetAmt).'</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalVATamt).'</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalInvAmt).'</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalCredit).'</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalAmtDue).'</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalaPaid).'</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalbUnpaid).'</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalcPaid).'</td>
									<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalBalance).'</td>
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
						FROM tblTarPackage(nolock) WHERE 1=1
						
						
						";
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
		$retfun .= CashCollection(0, $serviceid, "<b></b>", $code, $cdate, $where);
	$retfun .= "</td></tr>";
	$retfun .= "</table>";
	print $retfun;
?>