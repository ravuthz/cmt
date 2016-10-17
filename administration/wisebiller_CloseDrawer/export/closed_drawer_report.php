<?php
	$filename = "close_drawer_report";
	
	if ($type == 'csv') {
			$filename  .= '.xls';
			//$mime_type = 'text/comma-separated-values';		
			$mime_type = 'application/vnd.ms-excel';	
	} elseif ($type == 'xls') {
			$filename  .= '.xls';
			$mime_type = 'application/vnd.ms-excel';
	} elseif ($type == 'xml') {
			$filename  .= '.xml';
			$mime_type = 'text/xml';	
	} elseif ($type == 'word') {
			$filename  .= '.doc';
			$mime_type = 'application/vnd.ms-word';		
	} elseif ($type == 'pdf') {
			$filename  .= '.pdf';
			$mime_type = 'application/pdf';
	}
	
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	
	require_once("../common/agent.php");
	require_once("../common/functions.php");
	
	$st = FixQuotes($st);
	$et = FixQuotes($et);
	
	$retOut = '<table border="0" cellpadding="3" cellspacing="0" class="formbg" align="center" width="100%">
				<tr>
					<td width="76%" align="left" class="formtitle"><b>Close Cash Drawer Report</b></td>
				    <td width="24%" align="right" class="formtitle"></td>
				</tr>
				<tr>
					<td colspan="2">
						<table border="0" bordercolor="#999999" bgColor="#ffffff" style="border-collapse:collapse" cellpadding="10" cellspacing="0" align="left" width="100%">
							<tr>
								<td align="left">
									Transaction start: <b>'.FormatDate($st, 3).'</b><br>
									Transaction end:&nbsp; <b>'.FormatDate($et, 3).'</b>
								</td>
							</tr>	
							<tr>
								<td>
									<table border=0 cellpadding=3 cellspacing=0 bordercolor="#999999" style="border-collapse:collapse" width="100%">
										<tr>
											<th align="center" style="border:1px solid">Description</th>
											<th align="center" style="border:1px solid">Summary</th>
											<th align="center" style="border:1px solid">Total</th>
										</tr>										
								';
	
	//-------------------------------------------------	
	//  Get fee payment detail								  
	//-------------------------------------------------
	$retOut .= '<tr>
								<td align=left style="border-left:1px solid; border-top:1px solid; padding-left:10px;"><b>:: Revenue From System</b></td>
								<td	style="border-left:1px solid; border-top:1px solid;" width=150>&nbsp;</td>
								<td	style="border-left:1px solid; border-top:1px solid; border-right:1px solid;" width=150>&nbsp;</td>
							</tr>';
	$totalRevenue = 0.00;
	$sql = "select t.TransactionName, sum(p.PaymentAmount) as 'Amount' 
					from tblCustCashDrawer p, tlkpTransaction t
					where p.TransactionModeID = t.TransactionID
						and t.TranGroupID = 1
						and convert(varchar, PaymentDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."
					group by t.TransactionName, t.TransactionID
					order by t.TransactionID";
	if($que = $mydb->sql_query($sql)){
		while($result = $mydb->sql_fetchrow($que)){
			$TranName = $result['TransactionName'];
			$Amount = $result['Amount'];
			$totalRevenue += floatval($Amount);
			$retOut .= '<tr>
										<td align="left" style="border-left:1px solid; padding-left:50px;">'.$TranName.'</td>
										<td align="right" style="border-left:1px solid;">'.FormatCurrency($Amount).'</td>
										<td align="right" style="border-left:1px solid; border-right:1px solid">&nbsp;</td>
									</tr>
			';
		}		
	}
	$retOut .= '<tr>
										<td align="left" style="border-left:1px solid; padding-left:50px;"><b>Total Revenue</b></td>
										<td align="right" style="border-left:1px solid;">&nbsp;</td>
										<td align="right" style="border-left:1px solid; border-right:1px solid"><b>'.FormatCurrency($totalRevenue).'</b></td>
									</tr>
			';
	
	$mydb->sql_freeresult($que);
	
	//-------------------------------------------------	
	//  Get refund detail								  
	//-------------------------------------------------
	$retOut .= '<tr>
								<td align=left style="border-left:1px solid; padding-left:10px;"><b>:: Refund From System</b></td>
								<td	style="border-left:1px solid; " width=150>&nbsp;</td>
								<td	style="border-left:1px solid; border-right:1px solid;" width=150>&nbsp;</td>
							</tr>';
	$totalRefund = 0.00;
	$sql = "select t.TransactionName, sum(p.PaymentAmount) as 'Amount' 
					from tblCustCashDrawer p, tlkpTransaction t
					where p.TransactionModeID = t.TransactionID
						and t.TranGroupID = 2
						and convert(varchar, PaymentDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."
					group by t.TransactionName, t.TransactionID
					order by t.TransactionID";
	if($que = $mydb->sql_query($sql)){
		while($result = $mydb->sql_fetchrow($que)){
			$TranName = $result['TransactionName'];
			$Amount = $result['Amount'];
			$totalRefund += floatval($Amount);
			$retOut .= '<tr>
										<td align="left" style="border-left:1px solid; padding-left:50px;">'.$TranName.'</td>
										<td align="right" style="border-left:1px solid;">('.FormatCurrency($Amount).')</td>
										<td align="right" style="border-left:1px solid; border-right:1px solid">&nbsp;</td>
									</tr>
			';
		}		
	}
	$retOut .= '<tr>
										<td align="left" style="border-left:1px solid; padding-left:50px;"><b>Total Refund</b></td>
										<td align="right" style="border-left:1px solid;">&nbsp;</td>
										<td align="right" style="border-left:1px solid; border-right:1px solid"><b>('.FormatCurrency($totalRefund).')</b></td>
									</tr>
			';
	
	$mydb->sql_freeresult($que);
	
	//-------------------------------------------------	
	//  Net cash on hand								  
	//-------------------------------------------------
	$netCash = $totalRevenue - $totalRefund;
	if($netCash < 0)
		$stNetCash = "<font color=red><b>(".FormatCurrency(abs($netCash)).")</b></font>";
	else
		$stNetCash = "<font color=blue><b>".FormatCurrency($netCash)."</b></font>";
	$retOut .= '<tr>
								<td align="left" style="border-left:1px solid; padding-left:10px;"><b>:: Net Cash on Hand</b></td>
								<td align="right" style="border-left:1px solid;">&nbsp;</td>
								<td align="right" style="border-left:1px solid; border-right:1px solid"><b>'.$stNetCash.'</b></td>
							</tr>
							';
							
	//-------------------------------------------------	
	//  Get fee payment detail								  
	//-------------------------------------------------
	$retOut .= '<tr>
								<td align=left style="border-left:1px solid; padding-left:10px;"><b>:: Other transaction</b></td>
								<td	style="border-left:1px solid; " width=150>&nbsp;</td>
								<td	style="border-left:1px solid; border-right:1px solid;" width=150>&nbsp;</td>
							</tr>';
	$totalOther = 0.00;
	$sql = "select t.TransactionName, sum(p.PaymentAmount) as 'Amount' 
					from tblCustCashDrawer p, tlkpTransaction t
					where p.TransactionModeID = t.TransactionID
						and t.TranGroupID = 3
						and convert(varchar, PaymentDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4)."
					group by t.TransactionName, t.TransactionID
					order by t.TransactionID";
	if($que = $mydb->sql_query($sql)){
		while($result = $mydb->sql_fetchrow($que)){
			$TranName = $result['TransactionName'];
			$Amount = $result['Amount'];
			$totalOther += floatval($Amount);
			$retOut .= '<tr>
										<td align="left" padding-left:50px;">'.$TranName.'</td>
										<td align="right">'.FormatCurrency($Amount).'</td>
										<td align="right" style="border-right:1px solid">&nbsp;</td>
									</tr>
			';
		}		
	}
	$retOut .= '<tr>
										<td align="left" style="border-left:1px solid; padding-left:50px;"><b>Total Other transaction</b></td>
										<td align="right" style="border-left:1px solid;">&nbsp;</td>
										<td align="right" style="border-left:1px solid; border-right:1px solid"><b>'.FormatCurrency($totalOther).'</b></td>
									</tr>
			';
	
	$mydb->sql_freeresult($que);						
	
	$retOut .= '		
										<tr>
											<td colspan=3 style="border-top:1px solid;">&nbsp;</td>
										</tr>	
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
		
		print $retOut;
	
?>