<?php
	require_once("../common/agent.php");	
	require_once("../common/functions.php");
	
?>
<link href="../style/mystyle.css" rel="stylesheet" type="text/css" />
	

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>DEBTOR INVOICE REPORT</b><br />
						Print on: <?php print date("Y M d H:i:s"); ?>
						</td>
					<td align="right">
					[<a href="../export/pg224.php?id=<?php print $id;?>&sid=<?php print $sid; ?>&type=csv">Export</a>]	
					</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid #999999">No</th>								
								<th align="center" style="border:1px solid #999999">Invoice</th>
								<th align="center" style="border:1px solid #999999">Acct ID</th>
								<th align="center" style="border:1px solid #999999">Account</th>
								<th align="center" style="border:1px solid #999999">Subscription</th>
								<th align="center" style="border:1px solid #999999">Status</th>								
								<th align="center" style="border:1px solid #999999">Date</th>
								<th align="center" style="border:1px solid #999999">Net</th>
								<th align="center" style="border:1px solid #999999">VAT</th>
								<th align="center" style="border:1px solid #999999">Total</th>
								<th align="center" style="border:1px solid #999999">Paid</th>
								<th align="center" style="border:1px solid #999999">Unpaid</th>																						
							</thead>
							<tbody>
								<?php
										$id = FixQuotes($id);
										
										$sql = " Select	ci.InvoiceID, ci.InvoiceAmount, ci.UnpaidAmount, ci.VATAmount, ci.NetAmount, ci.IssueDate, 
														cp.AccID, cp.UserName, cp.StatusID, cp.SubscriptionName																	
												from tblCustomerInvoice ci
												join (Select * from tblSysBillRunCycleInfo Union Select * from tblDumBillRunCycleInfo) sb on sb.CycleID = ci.BillingCycleID
												join tblCustProduct cp on cp.AccID = ci.AccID
												where (InvoiceType in (2,3) or ( InvoiceType = 1 and ci.InvoiceID in (select InvoiceID from tblLocRev)))
												and sb.PackageID in (select PackageID from tblTarPackage where ServiceID in (select ServiceID from tlkpService where GroupServiceID =".$sid."))
												and TrackID in (select TrackID from tblTrackAccount where iCityID1 in (".$where."))
												and ci.UnpaidAmount > 0 
										
												";
										
										switch($id){
											case 1: # 0 - 30 days
													 $sql .= " and convert(varchar, sb.BillEndDate, 112) > convert(varchar, dateadd(day, -30, getdate()), 112) ";																	
													break;
											case 2: # 30 - 60 days
													$sql .= " and convert(varchar, sb.BillEndDate, 112) <= convert(varchar, dateadd(day, -30, getdate()), 112)
															  and convert(varchar, sb.BillEndDate, 112) > convert(varchar, dateadd(day, -60, getdate()), 112) ";
													break;
											case 3: # 60 - 90 days
													$sql .= " and convert(varchar, sb.BillEndDate, 112) <= convert(varchar, dateadd(day, -60, getdate()), 112)
															  and convert(varchar, sb.BillEndDate, 112) > convert(varchar, dateadd(day, -90, getdate()), 112) ";
													break;
											case 4: # 90 - 120 days
													$sql .= " and convert(varchar, sb.BillEndDate, 112) < convert(varchar, dateadd(day, -90, getdate()), 112)
															  and convert(varchar, sb.BillEndDate, 112) >= convert(varchar, dateadd(day, -120, getdate()), 112) ";
													break;
											case 5: # > 120 days
													$sql .= " and convert(varchar, sb.BillEndDate, 112) < convert(varchar, dateadd(day, -120, getdate()), 112) ";
													break;
										}
										$sql .= " order by cp.UserName";			

									if($que = $mydb->sql_query($sql)){
										$totalAmount = 0.00;
										$totalUnpaidAmount = 0.00;
										$totalVAT = 0.00;
										$totalNet = 0.00;
										$totalPaid = 0.00;
										$iLoop = 0;
										while($result = $mydb->sql_fetchrow()){																
											$VATAmount = $result['VATAmount'];																
											$NetAmount = $result['NetAmount'];
											$AccID = $result['AccID'];											
											$UserName = $result['UserName'];
											$SubscriptionName = $result['SubscriptionName'];																
											$StatusID = intval($result['StatusID']);											
											$InvoiceID = $result['InvoiceID'];
											$IssueDate = $result['IssueDate'];
											$TotalAmount = $result['InvoiceAmount'];
											$UnpaidAmount = $result['UnpaidAmount'];
											$VATAmount = $result['VATAmount'];
											$NetAmount = $result['NetAmount'];
											$PaidAmount = floatval($TotalAmount) - floatval($UnpaidAmount); 
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
											$linkCust = "<a href='../?CustomerID=".$CustID."&pg=10'>".$CustName."</a>";
											$linkAcct = "<a href='../?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91' target='_blank'>".$UserName."</a>";
											$totalAmount += floatval($TotalAmount);
											$totalUnpaidAmount += floatval($UnpaidAmount);
											$totalNet += floatval($NetAmount);
											$totalVAT += floatval($VATAmount);
											$totalPaid += ($PaidAmount);
											$iLoop++;															

											if(($iLoop % 2) == 0)											
												$style = "row1";
											else
												$style = "row2";
											print '<tr>';	
											print '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.$iLoop.'</td>';								
											print '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.$linkInv.'</td>';
											print '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.$AccID.'</td>';
											print '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.$linkAcct.'</td>';
											print '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.substr($SubscriptionName, 0, 30).'</td>';	
											print '<td align="center" bgcolor="'.$stbg.'" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">
															<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
														 </td>';													
											print '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.formatDate($IssueDate, 3).'</td>';
											print '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.FormatCurrency($NetAmount).'</td>';
											print '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.FormatCurrency($VATAmount).'</td>';
											print '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.FormatCurrency($TotalAmount).'</td>';
											print '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999 ">'.FormatCurrency($PaidAmount).'</td>';
											print '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999 ">'.FormatCurrency($UnpaidAmount).'</td>';
											print '</tr>';
										}
									}
									$mydb->sql_freeresult();	
								?>
							</tbody>
							<tfoot class="sortbottom">
								<tr>
									<td align="right" colspan="7" style="border:1px solid #999999">Total</td>
									<td align="right" style="border:1px solid #999999"><?php print FormatCurrency($totalNet); ?></td>
									<td align="right" style="border:1px solid #999999"><?php print FormatCurrency($totalVAT); ?></td>
									<td align="right" style="border:1px solid #999999"><?php print FormatCurrency($totalAmount); ?></td>
									<td align="right" style="border:1px solid #999999"><?php print FormatCurrency($totalPaid); ?></td>
									<td align="right" style="border:1px solid #999999"><?php print FormatCurrency($totalUnpaidAmount); ?></td>
								</tr>
							</tfoot>												
						</table>						
					</td>
				</tr>
			</table>
		</td>
	</tr>						
</table>