<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$drawerid = $_GET['drawerid'];
	$date = $_GET['date'];
	$tid = $_GET['tid'];
	$div = $_GET['div'];
	$packageid = $_GET['packageid'];
	$sid = $_GET['sid'];
	if($tid == 1)
		$title = "Payment transaction >> Transaction by package >> Transaction by paid as";
	elseif($tid == 2)
		$title = "Refund transaction >> Transaction by package >> Transaction by paid as";
	else
		$title = "Transfer transaction >> Transaction by package >> Transaction by paid as";
		
	$sql = "select Count(p.PaymentID) as 'NoTran', Sum(p.PaymentAmount) as 'Amount', p.Cashier, ts.TransactionName, m.PaymentMode,
						p.PaymentModelID, p.TransactionModeID, ta.TarName
					from tlkpTransaction ts, tblCustCashDrawer p(nolock), tblCustProduct a(nolock), tblTarPackage ta(nolock), tlkpPaymentMode m						
					where ts.TransactionID = p.TransactionModeID 
						and p.AcctID = a.AccID 
						and a.PackageID = ta.PackageID
						and p.PaymentModelID = m.PaymentID ";
	if($sid == 2){
		$sql .= " and ta.ServiceID = 2 ";
	}elseif($sid == 4){
		$sql .= " and ta.ServiceID = 4 ";
	}elseif($sid == 5){	
		$sql .= " and ta.ServiceID in(1, 3, 8) ";
	}					
	$sql .=	"	and p.DrawerID = ".$drawerid."
						and (p.IsRollBack IS NULL or p.IsRollBack = 0)
						and convert(varchar, p.PaymentDate, 112) = ".$date."
						and ts.TranGroupID = ".$tid."
						and ta.PackageID = ".$packageid."
					group by p.Cashier, ts.TransactionName, m.PaymentMode,	p.PaymentModelID, p.TransactionModeID, ta.TarName";
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
							<tr>
								<td>
									<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
										<tr>
											<td align="left" class="formtitle"><b>'.$title.'</b></td>
											<td align="right">[<a href="#" onClick="hide(\''.$div.'\');">Hide</a>]</td>
										</tr> 
										<tr>
											<td colspan="2">
												<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
													<thead>																	
														<th align="center">No</th>								
														<th align="center">Date</th>
														<th align="center">Cashier</th>
														<th align="center">Package</th>
														<th align="center">Transaction</th>													
														<th align="center">Paid as</th>
														<th align="center">No of Tran</th>																																																								
														<th align="center">Amount</th>																												
													</thead>
													<tbody>';
													
													if($que = $mydb->sql_query($sql)){
														$totalAmount = 0.00;
														$totalTran = 0;
														$iLoop = 0;
														while($result = $mydb->sql_fetchrow($que)){
															$PaymentMode = $result['PaymentMode'];
															$TransactionName = $result['TransactionName'];											
															$PaymentModelID = $result['PaymentModelID'];											
															$TransactionModeID = $result['TransactionModeID'];											
															$Cashier = $result['Cashier'];
															$TarName = $result['TarName'];
															$NoTran = $result['NoTran'];
															$Amount = $result['Amount'];
															$Cash = "<a href=\"javascript:showlevel4('d1-1-1-".$tid."', ".$tid.", ".$drawerid.", '".$date."', ".$packageid.", ".$TransactionModeID.", ".$PaymentModelID.", '".$sid."');\">".$Cashier."</a>";
															$totalAmount += floatval($Amount);
															$totalTran += intval($NoTran);
															$iLoop ++;
															if(($iLoop % 2) == 0)											
																$style = "row1";
															else
																$style = "row2";
															$retOut .= '<tr>
																						<td align="left" class="'.$style.'">'.$iLoop.'</td>
																						<td align="left" class="'.$style.'">'.FormatDate($date, 3).'</td>
																						<td align="left" class="'.$style.'">'.$Cash.'</td>
																						<td align="left" class="'.$style.'">'.$TarName.'</td>
																						<td align="left" class="'.$style.'">'.$TransactionName.'</td>																						
																						<td align="left" class="'.$style.'">'.$PaymentMode.'</td>
																						<td align="right" class="'.$style.'">'.$NoTran.'</td>																																																																		
																						<td align="right" class="'.$style.'">'.FormatCurrency($Amount).'</td>	
																					</tr>	
																					';																					
														} 
														$retOut .= '</tbody>
																					<tfoot>
																						<tr>
																							<td align="right" colspan="6">Total</td>
																							<td align="right">'.$totalTran.'</td>
																							<td align="right">'.FormatCurrency($totalAmount).'</td>
																						</tr>
																					</tfoot>
																				</table>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
															<tr>
																<td>
																	<div id="d1-1-1-'.$tid.'"></div>
																</td>
															</tr>
														</table>
													';
										}
							print $retOut;
										
?>