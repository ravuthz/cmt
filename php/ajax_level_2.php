<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$drawerid = $_GET['drawerid'];
	$date = $_GET['date'];
	$tid = $_GET['tid'];
	$div = $_GET['div'];
	$sid = $_GET['sid'];
	if($tid == 1)
		$title = "Payment transaction >> Transaction by package";
	elseif($tid == 2)
		$title = "Refund transaction >> Transaction by package";
	else
		$title = "Transfer transaction >> Transaction by package";
	
	$sql1 = "BEGIN TRY
	DROP TABLE #drawer
END TRY
BEGIN CATCH
END CATCH

Select	Sum(p.PaymentAmount) as 'pAmount', 
		m.PaymentMode,  
		TransactionName = case
								when ts.TransactionID = 1 then 'Fee payment'
								when ts.TransactionID = 3 then 'Advance payment'
								when ts.TransactionID in ( 2,7,8,9 ) then 'Refund'
								when ts.TransactionID in (4,5,6) then 'Book Deposit'
								else TransactionName
							End,
		Invoicetype = case
							when ci.invoicetype = 1 then 'Cycle Bills'
							when ci.invoicetype = 2 then 'Demand Bills'
							when ci.invoicetype = 3 then 'Other Bills'
							else 'Other Receipt'
						End
into #drawer
from tlkpTransaction ts
join tblCustCashDrawer p(nolock) on ts.TransactionID = p.TransactionModeID
join tblcustProduct cp(nolock) on cp.accid = p.acctid
join tblTarPackage t(nolock) on t.packageid = cp.packageid
join tlkpPaymentMode m(nolock) on p.PaymentModelID = m.PaymentID
left join tblCustomerInvoice ci(nolock) on ci.invoiceid = p.invoiceid
where ts.TransactionID not in ( 2,7,8,9 ) and (p.IsRollBack IS NULL or p.IsRollBack = 0) ";
	if($sid == 2){
		$sql1 .= " and t.ServiceID = 2 ";
	}elseif($sid == 4){
		$sql1 .= " and t.ServiceID = 4 ";
	}elseif($sid == 5){	
		$sql1 .= " and t.ServiceID in(1, 3, 8) ";
	}					
	$sql1 .= "	and p.DrawerID = ".$drawerid."
						and convert(varchar, p.PaymentDate, 112) = ".$date."
group by case
				when ci.invoicetype = 1 then 'Cycle Bills'
				when ci.invoicetype = 2 then 'Demand Bills'
				when ci.invoicetype = 3 then 'Other Bills'
				else 'Other Receipt'
			End,
			case
				when ts.TransactionID = 1 then 'Fee payment'
				when ts.TransactionID = 3 then 'Advance payment'
				when ts.TransactionID in ( 2,7,8,9 ) then 'Refund'
				when ts.TransactionID in (4,5,6) then 'Book Deposit'
				else TransactionName
			End,
			m.PaymentMode 

Update #drawer set TransactionName = Invoicetype
where TransactionName = 'Fee payment'
Update #drawer set Invoicetype = 1
where TransactionName = 'Cycle Bills'
Update #drawer set Invoicetype = 2
where TransactionName = 'Demand Bills'
Update #drawer set Invoicetype = 3
where TransactionName = 'Advance payment'
Update #drawer set Invoicetype = 4
where TransactionName = 'Book Deposit'
Update #drawer set Invoicetype = 5
where TransactionName = 'Other Bills'
Update #drawer set Invoicetype = 6--, pAmount = (-1) * pAmount
where TransactionName = 'Refund'
select	TransactionName,
		IsNull([Cash], 0) 'Cash', 
		IsNull([Cheque], 0) 'Cheque' 
from 
(
	select TransactionName,pAmount,PaymentMode,invoicetype from #drawer 
)id
PIVOT (sum(pAmount) FOR PaymentMode IN ([Cash], [Cheque])) AS pvt
Order by InvoiceType
drop table #drawer";
					//echo $sql1;
	$tCashCh = 0;
	$tCash = 0;
	$tCh = 0;
	$ttCaCh = 0;				
	if($que1 = $mydb->sql_query($sql1)){
		$detail = "<div><table><tr><td align='left'><b>Description</b></td><td><b>Cash</b></td><td><b>Cheque</b></td><td><b>Total</b></td></tr>";
		while($result1 = $mydb->sql_fetchrow($que1)){
			$pcash = $result1['Cash'];
			$pCheque = $result1['Cheque'];
			$pTransactionName = $result1['TransactionName'];
			$tCashCh = $pcash + $pCheque;
			$tCash +=  $pcash;
			$tCh += $pCheque;
			$ttCaCh += $tCashCh;
			$detail .="<tr><td align='left'>".$pTransactionName."</td><td>".FormatCurrency($pcash)."</td><td>".FormatCurrency($pCheque)."</td><td>".FormatCurrency($tCashCh)."</td></tr>";
		}
		$detail .= "<tr><td align='left'><b>Total</b></td><td><b>".FormatCurrency($tCash)."</b></td><td><b>".FormatCurrency($tCh)."</b></td><td><b>".FormatCurrency($ttCaCh)."</b></td></tr></table></div>";		
	}
	$mydb->sql_freeresult($que1);
		
	$sql = "select t.PackageID, t.TarName, Count(p.PaymentID) as 'NoTran', Sum(p.PaymentAmount) as 'Amount', p.Cashier
					from tlkpTransaction ts, tblCustCashDrawer p(nolock), tblCustProduct a(nolock), tblTarPackage t(nolock)
					where ts.TransactionID = p.TransactionModeID 
						and p.AcctID = a.AccID 
						and a.PackageID = t.PackageID						
						and (p.IsRollBack IS NULL or p.IsRollBack = 0) ";
	if($sid == 2){
		$sql .= " and t.ServiceID = 2 ";
	}elseif($sid == 4){
		$sql .= " and t.ServiceID = 4 ";
	}elseif($sid == 5){	
		$sql .= " and t.ServiceID in(1, 3, 8) ";
	}					
	$sql .= "	and p.DrawerID = ".$drawerid."
						and convert(varchar, p.PaymentDate, 112) = ".$date."
						and ts.TranGroupID = ".$tid."
					group by t.PackageID, t.TarName, p.Cashier";
	
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
														<th align="center">No of Tran</th>																			
														<th align="center">Amount</th>																												
													</thead>
													<tbody>';
													
													if($que = $mydb->sql_query($sql)){
														$totalAmount = 0.00;
														$totalTran = 0;
														$iLoop = 0;
														while($result = $mydb->sql_fetchrow($que)){
															$PackageID = $result['PackageID'];
															$TarName = $result['TarName'];											
															$Cashier = $result['Cashier'];
															$NoTran = $result['NoTran'];
															$Amount = $result['Amount'];
															$Cash = "<a href=\"javascript:showlevel3('d1-1-".$tid."', ".$tid.", ".$drawerid.", '".$date."', ".$PackageID.", '".$sid."');\">".$Cashier."</a>";
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
																						<td align="right" class="'.$style.'">'.$NoTran.'</td>
																						<td align="right" class="'.$style.'">'.FormatCurrency($Amount).'</td>	
																					</tr>	
																					';																					
														} 
														$retOut .= '</tbody>
																					<tfoot>
																						<tr>
																							<td align="right" colspan="4">Total</td>
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
															<tr>
																<td>
																	<div id="d1-1-'.$tid.'"></div>
																</td>
															</tr>
														</table>
										';
										}
							print $retOut;
										
?>