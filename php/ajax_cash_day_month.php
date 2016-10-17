<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$st=$_GET['st'];
	$et=$_GET['et'];
	$sid1=$_GET['sid'];
	
	$retOut = '
			<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%" bgColor="white">
							<tr>
								<td>
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>&nbsp;Cash collection from '.FormatDate($st, 3).' and '.FormatDate($et, 3).'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No.</th>					
								<th align="center">Date</th>
								<th align="center" width="130">Revenue</th>
								<th align="center" width="130">Refund</th>								
								<th align="center" width="130">Net Cash</th>
								<th align="center" width="130">Other</th>
							</thead>
							<tbody>';
	$sql = "
					begin try 
						drop table #tmpRevenue
					end try
					begin catch
					end catch
					select convert(varchar, PaymentDate, 112) as 'Date', convert(decimal(10, 2), 0.00) 'Revenue', 
					convert(decimal(10, 2), 0.00) 'Refund', convert(decimal(10, 2), 0) 'Other'
					into #tmpPayment 
					from tblCashPayment p(nolock), tblCustProduct a(nolock), tblTarPackage ta(nolock)
					where p.AcctID = a.AccID
						and a.PackageID = ta.PackageID ";
				if($sid1 == 2){
					$sql .= " and ta.ServiceID = 2 ";
				}elseif($sid1 == 4){
					$sql .= " and ta.ServiceID = 4 ";
				}elseif($sid1 == 5){	
					$sql .= " and ta.ServiceID in(1, 3, 8) ";
				}
					$sql .= "
					and convert(varchar, PaymentDate, 112) between '".FormatDate($st, 4)."' and '".FormatDate($et, 4)."'
					group by convert(varchar, PaymentDate, 112)
					order by convert(varchar, PaymentDate, 112)										
					
					select convert(varchar, p.PaymentDate, 112) as 'Date', SUM(p.PaymentAmount) as 'Revenue'
					into #tmpRevenue 
					from tblCashPayment p(nolock), tlkpTransaction t, tblCustProduct a(nolock), tblTarPackage ta(nolock) 
					where p.TransactionModeID = t.TransactionID
							and p.AcctID = a.AccID
							and a.PackageID = ta.PackageID ";
				if($sid1 == 2){
					$sql .= " and ta.ServiceID = 2 ";
				}elseif($sid1 == 4){
					$sql .= " and ta.ServiceID = 4 ";
				}elseif($sid1 == 5){	
					$sql .= " and ta.ServiceID in(1, 3, 8) ";
				}
					$sql .= "
							and t.TranGroupID = 1	
							and convert(varchar, p.PaymentDate, 112) between '".FormatDate($st, 4)."' and '".FormatDate($et, 4)."'
					group by convert(varchar, p.PaymentDate, 112)
					order by convert(varchar, p.PaymentDate, 112)
					
					update #tmpPayment
					set #tmpPayment.Revenue = #tmpRevenue.Revenue
					from #tmpRevenue 
					where #tmpPayment.Date = #tmpRevenue.Date

					select convert(varchar, p.PaymentDate, 112) as 'Date', SUM(p.PaymentAmount) as 'Refund'
					into #tmpRefund 
					from tblCashPayment p(nolock), tlkpTransaction t(nolock), tblCustProduct a(nolock), tblTarPackage ta(nolock) 
					where p.TransactionModeID = t.TransactionID
							and p.AcctID = a.AccID
							and a.PackageID = ta.Packageid ";
					if($sid1 == 2){
						$sql .= " and ta.ServiceID = 2 ";
					}elseif($sid1 == 4){
						$sql .= " and ta.ServiceID = 4 ";
					}elseif($sid1 == 5){	
						$sql .= " and ta.ServiceID in(1, 3, 8) ";
					}
						$sql .= "
							and t.TranGroupID = 2	
							and convert(varchar, p.PaymentDate, 112) between '".FormatDate($st, 4)."' and '".FormatDate($et, 4)."'
					group by convert(varchar, p.PaymentDate, 112)
					order by convert(varchar, p.PaymentDate, 112)
					
					update #tmpPayment
					set #tmpPayment.Refund = #tmpRefund.Refund
					from #tmpRefund 
					where #tmpPayment.Date = #tmpRefund.Date;					
					
					select convert(varchar, p.PaymentDate, 112) as 'Date', SUM(p.PaymentAmount) as 'Other'
					into #tmpOther 
					from tblCashPayment p(nolock), tlkpTransaction t(nolock), tblCustProduct a(nolock), tblTarPackage ta(nolock) 
					where p.TransactionModeID = t.TransactionID
							and p.AcctID = a.AccID
							and a.PackageID = ta.PackageID ";
				if($sid1 == 2){
					$sql .= " and ta.ServiceID = 2 ";
				}elseif($sid1 == 4){
					$sql .= " and ta.ServiceID = 4 ";
				}elseif($sid1 == 5){	
					$sql .= " and ta.ServiceID in(1, 3, 8) ";
				}
					$sql .= "	and t.TranGroupID = 3	
										and convert(varchar, p.PaymentDate, 112) between '".FormatDate($st, 4)."' and '".FormatDate($et, 4)."'		
								group by convert(varchar, p.PaymentDate, 112)
								order by convert(varchar, p.PaymentDate, 112)
																									
					update #tmpPayment
					set #tmpPayment.Other = #tmpOther.Other
					from #tmpOther
					where #tmpPayment.Date = #tmpOther.Date;
					
					select * from #tmpPayment order by 1
					
					drop table #tmpPayment;
					drop table #tmpRevenue;
					drop table #tmpRefund;
					drop table #tmpOther;
				";
							
	if($que = $mydb->sql_query($sql)){		

		$n = 0;
		$iLoop = 0;
		$totalrevenue = 0;
		$totalrefund = 0;
		$totalnetcash = 0;
		$totalother = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$Date = $result['Date'];
			$ServiceID = $result['ServiceID'];										
			$Revenue = $result['Revenue'];
			$Refund = $result['Refund'];
			$Other = $result['Other'];
			$NetCash = ($Revenue - $Refund);
			
			$totalrevenue += $Revenue;
			$totalrefund += $Refund;
			$totalnetcash += $NetCash;
			$totalother += $Other;									
			$RV = "<a href=\"javascript:showlevel1('d1', 1, '".$Date."', '".$sid1."');\">".FormatCurrency($Revenue)."</a>";
			$RF = "<a href=\"javascript:showlevel1('d1', 2, '".$Date."', ".$sid1."');\">".FormatCurrency($Refund)."</a>";
			$OT = "<a href=\"javascript:showlevel1('d1', 3, '".$Date."', ".$sid1."');\">".FormatCurrency($Other)."</a>";
			$iLoop++;															
			$n++;
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.'" align="center">'.$n.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.FormatDate($Date, 3).'</td>';																																																	
			$retOut .= '<td class="'.$style.'" align="right">'.$RV.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.$RF.'</td>';	
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($NetCash).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right">'.$OT.'</td>';	
			$retOut .= '</tr>';									
		}		
	}else{
		$error = $mydb->sql_error();
		print $error['message'];
	}
	$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan="2">Total</td>
											<td align="right">'.FormatCurrency($totalrevenue).'</td>
											<td align="right">'.FormatCurrency($totalrefund).'</td>
											<td align="right">'.FormatCurrency($totalnetcash).'</td>
											<td align="right">'.FormatCurrency($totalother).'</td>
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
								<div id="d1"></div>
							</td>
						</tr>
					</table>';
	
	$mydb->sql_freeresult();
	
		
	print $retOut;	
?>