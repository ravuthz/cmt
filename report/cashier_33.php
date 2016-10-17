
			<?php
				require_once("../common/agent.php");
				require_once("../common/functions.php");	
				$FromDate = $_GET['st'];	
				$ToDate = $_GET['et'];
				$cycle = $_GET['cycle'];
				$Package = $_GET['package'];
				$pt = $_GET['pt'];
				$md = $_GET['md'];
				$mn = $_GET['mn'];
				$pn = $_GET['pn'];
				
		$sql = '  SELECT i.InvoiceID, a.AccID, a.UserName, a.SubscriptionName, i.NetAmount, i.VATAmount, 
										i.UnpaidAmount, i.InvoiceAmount, p.PaymentID, p.PaymentDate, p.PaymentAmount, tra.TransactionName
							FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblCashPayment p(nolock), 
										tblSysBillRunCycleInfo inf(nolock), tlkpTransaction tra(nolock)
							WHERE i.InvoiceID = p.InvoiceID
								and i.BillingCycleID = inf.CycleID
								and p.AcctID = a.AccID
								and p.TransactionModeID = tra.TransactionID
								and a.PackageID = "'.$Package.'"
								and CONVERT(VARCHAR, inf.BillEndDate, 112) = "'.$cycle.'"
								and CONVERT(VARCHAR, p.PaymentDate, 112) BETWEEN "' . FormatDate($FromDate, 4) . '" AND "' . FormatDate($ToDate, 4) . '"
								and p.PaymentModelID = "'.$md.'"
							';				
			?>
	<table border="0" cellpadding="0" cellspacing="0" align="left" width="100%">
		<tr>
			<td>
				<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
					<tr>
						<td align="left">
							<table border="0" cellpadding="1" cellspacing="0" align="left" class="formbg" width="100%">		
								<tr>
									<td align="left" class="formtitle"><b>Invoice >> Payment type >> <?php print $mn; ?> >>  <?php print $pn; ?></td>
									<td align="right">
										[<a href="../export/cashier_3.php?st=<?php print $FromDate; ?>&et=<?php print $ToDate; ?>&package=<?php print $Package;?>&pt=<?php print $pt; ?>&cycle=<?php print $cycle; ?>&mn=<?php print $mn;?>&md=<?php print $md;?>&pn=<?php print $pn;?>">Download</a>]
									</td>
								</tr>
								<tr>
									<td colspan="2"> 
										<table border="0" cellpadding="3" cellspacing="0" align="left" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa" bgColor="#ffffff" width="100%">
											<tr>
												<th align="center" style="border:1px solid">No</th>												
												<th align="center" style="border:1px solid">Rec#</th>
												<th align="center" style="border:1px solid">Inv#</th>
												<th align="center" style="border:1px solid">Acc ID</th>
												<th align="center" style="border:1px solid">Account name</th>
												<th align="center" style="border:1px solid">Subscription</th>
												<th align="center" style="border:1px solid">Paid date</th>
												<th align="center" style="border:1px solid">Paid amount</th>
												<th align="center" style="border:1px solid">Net amount</th>
												<th align="center" style="border:1px solid">VAT amount</th>
												<th align="center" style="border:1px solid">Invoice amount</th>
												
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
													$totalNet = 0.00;
													$totalVAT = 0.00;
													$total = 0.00;
													$totalunpaid = 0.00;
													$totalpaid = 0.00;
													

													while($result = $mydb->sql_fetchrow($que)){
														$InvoiceID = $result['InvoiceID'];
														$AccID = $result['AccID'];
														$UserName = $result['UserName'];
														$SubscriptionName = $result['SubscriptionName'];
														$PaymentAmount = doubleval($result['PaymentAmount']);
														$NetAmount = doubleval($result['NetAmount']);
														$VATAmount = doubleval($result['VATAmount']);
														$UnpaidAmount = doubleval($result['UnpaidAmount']);
														$InvoiceAmount = doubleval($result['InvoiceAmount']);
														$PaymentID = $result['PaymentID'];
														$PaymentDate = $result['PaymentDate'];
														$TransactionName = $result['TransactionName'];

														$PaidAmount = $InvoiceAmount - $UnpaidAmount;
														if(doubleval($PaidAmount) <0) $PaidAmount = 0;
														
														$totalNet += ($NetAmount);
														$totalVAT += ($VATAmount);
														$total += ($InvoiceAmount);
											//			$totalunpaid += ($UnpaidAmount);
														$totalpaid += ($PaymentAmount);
														
														$iLoop ++;
														if(($iLoop % 2) == 0)
															$style = "row2";
														else
															$style = "row1";			
														
														print '<tbody>';
														print '<tr>';
														print '<td class="'.$style.'" align="left" style="border-left:1px solid; border-top:1px dotted;">'.$iLoop.'</td>';															
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.$PaymentID.'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.$InvoiceID.'</td>';
														print '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$AccID.'</td>';
														print '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$UserName.'</td>';
														print '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$SubscriptionName.'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatDate($PaymentDate, 3).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($PaymentAmount).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($NetAmount).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($VATAmount).'</td>';
														print '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($InvoiceAmount).'</td>';														
														print '</tr>';											
													}
														print '</tbody>';
														print '<tfoot>
																		<tr class="sortbottom">
																	';
														print '<td style="border:1px solid" align="left" colspan=7>Total</td>';	
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totalpaid).'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totalNet).'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($totalVAT).'</td>';
														print '<td style="border:1px solid" align="right">'.FormatCurrency($total).'</td>';
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
	</table>
