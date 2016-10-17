<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$drawerid = $_GET['drawerid'];
	$date = $_GET['date'];
	$tid = $_GET['tid'];
	$div = $_GET['div'];
	$packageid = $_GET['packageid'];
	$trid = $_GET['trid'];
	$pid = $_GET['pid'];
	$sid = $_GET['sid'];
	if($tid == 1)
		$title = "Payment transaction >> Transaction by package >> Transaction by paid as >> Payment detail";
	elseif($tid == 2)
		$title = "Refund transaction >> Transaction by package >> Transaction by paid as >> Payment detail";
	else
		$title = "Transfer transaction >> Transaction by package >> Transaction by paid as >> Payment detail";
		
	$sql = "select p.PaymentID, p.AcctID, p.PaymentDate, p.CustID, p.Description, p.Cashier, p.PaymentAmount,
						ts.TransactionName, m.PaymentMode, a.UserName
					from tlkpTransaction ts, tblCustCashDrawer p(nolock), tblCustProduct a(nolock), tblTarPackage ta(nolock), tlkpPaymentMode m						
					where ts.TransactionID = p.TransactionModeID 
						and p.AcctID = a.AccID 
						and a.PackageID = ta.PackageID
						and p.PaymentModelID = m.PaymentID
						and (p.IsRollBack IS NULL or p.IsRollBack = 0) ";
	if($sid == 2){
		$sql .= " and ta.ServiceID = 2 ";
	}elseif($sid == 4){
		$sql .= " and ta.ServiceID = 4 ";
	}elseif($sid == 5){	
		$sql .= " and ta.ServiceID in(1, 3, 8) ";
	}
	$sql.= "	and p.DrawerID = ".$drawerid."
						and p.TransactionModeID = ".$trid."
						and p.PaymentModelID = ".$pid."
						and convert(varchar, p.PaymentDate, 112) = ".$date."
						and ts.TranGroupID = ".$tid."
						and a.PackageID = ".$packageid."
					";
	
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
														<th align="center">Payment id</th>
														<th align="center">Date</th>
														<th align="center">Account</th>																											
														<th align="center">Transaction</th>	
														<th align="center">Paid as</th>
														<th align="center">Cashier</th>
														<th align="center">Description</th>																																									
														<th align="center">Amount</th>																												
													</thead>
													<tbody>';
													
													if($que = $mydb->sql_query($sql)){
														$totalAmount = 0.00;
														$iLoop = 0;
														while($result = $mydb->sql_fetchrow($que)){
															$PaymentID = $result['PaymentID'];
															$AcctID = $result['AcctID'];											
															$PaymentDate = $result['PaymentDate'];											
															$CustID = $result['CustID'];											
															$Cashier = $result['Cashier'];
															$Description = $result['Description'];
															$TransactionName = $result['TransactionName'];
															$PaymentMode = $result['PaymentMode'];
															$UserName = $result['UserName'];
															$Amount = $result['PaymentAmount'];															
															$linkAccount = "<a href='./?CustomerID=".$CustID."&AccountID=".$AcctID."&pg=91'>".$UserName."</a>";
															$totalAmount += floatval($Amount);
															$iLoop ++;
															if(($iLoop % 2) == 0)											
																$style = "row1";
															else
																$style = "row2";
															$retOut .= '<tr>
																						<td align="left" class="'.$style.'">'.$iLoop.'</td>
																						<td align="left" class="'.$style.'">'.$PaymentID.'</td>
																						<td align="left" class="'.$style.'">'.FormatDate($PaymentDate, 7).'</td>
																						<td align="left" class="'.$style.'">'.$linkAccount.'</td>
																						<td align="left" class="'.$style.'">'.$TransactionName.'</td>																						
																						<td align="left" class="'.$style.'">'.$PaymentMode.'</td>
																						<td align="left" class="'.$style.'">'.$Cashier.'</td>
																						<td align="left" class="'.$style.'">'.$Description.'</td>																																												
																						<td align="right" class="'.$style.'">'.FormatCurrency($Amount).'</td>	
																					</tr>	
																					';																					
														} 
														$retOut .= '</tbody>
																					<tfoot>
																						<tr>
																							<td align="right" colspan="8">Total ('.$iLoop.')</td>
																							<td align="right">'.FormatCurrency($totalAmount).'</td>
																						</tr>
																					</tfoot>
																				</table>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>															
														</table>
													';
										}
							print $retOut;
										
?>