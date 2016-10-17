<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	

	$date = $_GET['date'];
	$tid = $_GET['tid'];
	$div = $_GET['div'];
	$sid1 = $_GET['sid'];
	
	$sql = "select d.DrawerID, convert(varchar, d.PaymentDate, 112) as 'Date', d.Cashier, 
								Count(d.PaymentID) as 'NoTran', Sum(d.PaymentAmount) as 'Amount'
					from tblCustCashDrawer d(nolock), tlkpTransaction t, tblCustProduct a(nolock), tblTarPackage ta(nolock)
					where d.TransactionModeID = t.TransactionID
						and d.AcctID = a.AccID
						and a.PackageID = ta.PackageID						
						and convert(varchar, d.PaymentDate, 112) = ".FormatDate($date, 4)." AND t.TranGroupID = ".$tid."
						and (d.IsRollBack = 0 or d.IsRollBack is NULL )";
	if($sid1 == 2){
		$sql .= " and ta.ServiceID = 2 ";
	}elseif($sid1 == 4){
		$sql .= " and ta.ServiceID = 4 ";
	}elseif($sid1 == 5){	
		$sql .= " and ta.ServiceID in(1, 3, 8) ";
	} 
	$sql .= " group by d.DrawerID, convert(varchar, d.PaymentDate, 112), d.Cashier";
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%" bgColor="white">
							<tr>
								<td>
						<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
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
														<th align="center">No of Tran</th>																			
														<th align="center">Amount</th>																												
													</thead>
													<tbody>';
													
													if($que = $mydb->sql_query($sql)){
														$totalAmount = 0.00;
														$totalTran = 0;
														$iLoop = 0;

														while($result = $mydb->sql_fetchrow($que)){
															$DrawerID = $result['DrawerID'];
															$Date = $result['Date'];
															$Cashier = $result['Cashier'];
															$NoTran = $result['NoTran'];
															$Amount = $result['Amount'];
															$Cash = "<a href=\"javascript:showlevel2('d1-".$tid."', ".$tid.", ".$DrawerID.", '".$Date."', '".$sid1."');\">".$Cashier."</a>";
															$totalAmount += floatval($Amount);
															$totalTran += intval($NoTran);
															$iLoop ++;
															if(($iLoop % 2) == 0)											
																$style = "row1";
															else
																$style = "row2";
															$retOut .= '<tr>
																						<td align="left" class="'.$style.'">'.$iLoop.'</td>
																						<td align="left" class="'.$style.'">'.FormatDate($Date, 3).'</td>
																						<td align="left" class="'.$style.'">'.$Cash.'</td>
																						<td align="left" class="'.$style.'">'.$NoTran.$download.'</td>
																						<td align="right" class="'.$style.'">'.FormatCurrency($Amount).'</td>	
																					</tr>	
																					';																					
														} 
														$retOut .= '</tbody>
																					<tfoot>
																						<tr>
																							<td align="right" colspan="3">Total</td>
																							<td align="right">'.$totalTran.'</td>
																							<td align="right">'.FormatCurrency($totalAmount).'</td>
																						</tr>
																					</tfoot>
																				</table>
																			</td>
																		</tr>
																		<tr>
																			<td align="right">'.$detail.'</td>
																		</tr>
																	</table>
																</td>
															</tr>
															</table>
															</td>
															</tr>
															<tr>
																<td>
																	<div id="d1-'.$tid.'"></div>
																</td>
															</tr>
														</table>
										';
										}
							print $retOut;
?>