<?php
	$filename = "aging_open_invoice";
	
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

	$sid1 = $_GET['sid'];
//-------------------------------------------------	
//  Summay Report								  |
//-------------------------------------------------

	//Get Count Customer-----------------------------------------------------------
			$sql = "SELECT COUNT(CustID) AS TotalCust 
							FROM tblCustomer c(nolock), tblCustProduct a(nolock), tblTarPackage t
							WHERE c.CustID = a.CustID
								and a.PackageID = t.PackageID ";
			if($sid1 == 2){
				$sql .= " and t.ServiceID = 2 ";
			}elseif($sid1 == 4){
				$sql .= " and t.ServiceID = 4 ";
			}elseif($sid1 == 5){	
				$sql .= " and t.ServiceID in(1, 3, 8) ";
			}					
							
			if($que = $mydb->sql_query($sql)){
				$result = $mydb->sql_fetchrow($que);
				$TotalCust = $result['TotalCust'];
			}
			$mydb->sql_freeresult($que);
	// get total invoice
			$sql = "SELECT
										 COUNT(i.InvoiceID) AS TotalInvoice,
										 SUM(i.InvoiceAmount) AS TotalAmount,
										 SUM(i.NetAmount) AS TotalNetAmount,
										 SUM(i.VATAmount) AS TotalVAT,
										 SUM(i.UnpaidAmount) AS TotalUnpaid										 
							FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock) 
							WHERE i.AccID = a.AccID
								AND a.PackageId = p.PackageID
							";
							
			if($sid1 == 2){
				$sql .= " and p.ServiceID = 2 ";
			}elseif($sid1 == 4){
				$sql .= " and p.ServiceID = 4 ";
			}elseif($sid1 == 5){	
				$sql .= " and p.ServiceID in(1, 3, 8) ";
			}	
			
			if($que = $mydb->sql_query($sql)){		
				if($result = $mydb->sql_fetchrow()){																															
				//	$TotalCust = $result['TotalCust'];
					$TotalInvoice = $result['TotalInvoice'];
					$TotalAmount = $result['TotalAmount'];
					$TotalNetAmount = $result['TotalNetAmount'];
					$TotalVAT = $result['TotalVAT'];
					$TotalUnpaid = $result['TotalUnpaid'];
								
				}
			}
	$mydb->sql_freeresult();

// All --------------------------
		$sql = "SELECT COUNT(i.InvoiceID) AS InvoiceALL, SUM(i.UnpaidAmount) AS UnpaidAll
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock) 
						WHERE i.UnpaidAmount > 0 
							AND i.IssueDate is not NULL
							AND i.AccID = a.AccID
							AND a.PackageId = p.PackageID
							";
		if($sid1 == 2){
				$sql .= " and p.ServiceID = 2 ";
			}elseif($sid1 == 4){
				$sql .= " and p.ServiceID = 4 ";
			}elseif($sid1 == 5){	
				$sql .= " and p.ServiceID in(1, 3, 8) ";
			}		
					
		if($que = $mydb->sql_query($sql)){		
				if($result = $mydb->sql_fetchrow()){																															
					$InvoiceALL = $result['InvoiceALL'];	
					$UnpaidAll = $result['UnpaidAll'];			
				}
			}
		$mydb->sql_freeresult();		
		

//0-30 day----------------------------------------	

		$sql = "SELECT count(*) as count, sum(i.unpaidamount) as sum 
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock) 
						WHERE convert(varchar, i.I.IssueDate, 112) > convert(varchar, dateadd(day, -30, getdate()), 112) 
							AND i.unpaidamount > 0
							AND i.AccID = a.AccID
							AND a.PackageId = p.PackageID
						";
		if($sid1 == 2){
			$sql .= " and p.ServiceID = 2 ";
		}elseif($sid1 == 4){
			$sql .= " and p.ServiceID = 4 ";
		}elseif($sid1 == 5){	
			$sql .= " and p.ServiceID in(1, 3, 8) ";
		}					
			if($que = $mydb->sql_query($sql)){		
		
				if($result = $mydb->sql_fetchrow()){																															
					$total30 = $result['count'];	
					$amount30 = $result['sum'];			
				}
			}
	$mydb->sql_freeresult();

//30-60 day----------------------------------------	

		$sql = "SELECT count(*) as count, sum(i.unpaidamount) as sum 
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock) 
						WHERE convert(varchar, i.IssueDate, 112) <= convert(varchar, dateadd(day, -30, getdate()), 112) 
							AND convert(varchar, i.IssueDate, 112) > convert(varchar, dateadd(day, -60, getdate()), 112)  
							AND i.unpaidamount > 0
							AND i.AccID = a.AccID
							AND a.PackageId = p.PackageID ";
			if($sid1 == 2){
				$sql .= " and p.ServiceID = 2 ";
			}elseif($sid1 == 4){
				$sql .= " and p.ServiceID = 4 ";
			}elseif($sid1 == 5){	
				$sql .= " and p.ServiceID in(1, 3, 8) ";
			}				
			if($que = $mydb->sql_query($sql)){		
		
				if($result = $mydb->sql_fetchrow()){																															
					$total60 = $result['count'];	
					$amount60 = $result['sum'];				
				}
			}
	$mydb->sql_freeresult();
	
//60-90 day----------------------------------------	

		$sql = "SELECT count(*) as count, sum(i.unpaidamount) as sum 
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock)
						WHERE convert(varchar, i.IssueDate, 112) <= convert(varchar, dateadd(day, -60, getdate()), 112) 
							AND convert(varchar, i.IssueDate, 112) > convert(varchar, dateadd(day, -90, getdate()), 112)
							AND i.unpaidamount > 0
							AND i.AccID = a.AccID
							AND a.PackageId = p.PackageID ";
			if($sid1 == 2){
				$sql .= " and p.ServiceID = 2 ";
			}elseif($sid1 == 4){
				$sql .= " and p.ServiceID = 4 ";
			}elseif($sid1 == 5){	
				$sql .= " and p.ServiceID in(1, 3, 8) ";
			}					
			if($que = $mydb->sql_query($sql)){		
		
				if($result = $mydb->sql_fetchrow()){																															
					$total90 = $result['count'];	
					$amount90 = $result['sum'];				
				}
			}
	$mydb->sql_freeresult();
	
	//90-120 day----------------------------------------	
	
		$sql = "SELECT count(*) as count, sum(i.unpaidamount) as sum 
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock)
						WHERE convert(varchar, i.IssueDate, 112) < convert(varchar, dateadd(day, -90, getdate()), 112) 
							AND convert(varchar, i.IssueDate, 112) >= convert(varchar, dateadd(day, -120, getdate()), 112)
							AND i.unpaidamount > 0
							AND i.AccID = a.AccID
							AND a.PackageId = p.PackageID ";
		if($sid1 == 2){
			$sql .= " and p.ServiceID = 2 ";
		}elseif($sid1 == 4){
			$sql .= " and p.ServiceID = 4 ";
		}elseif($sid1 == 5){	
			$sql .= " and p.ServiceID in(1, 3, 8) ";
		}
							
			if($que = $mydb->sql_query($sql)){		
		
				if($result = $mydb->sql_fetchrow()){																															
					$total120 = $result['count'];	
					$amount120 = $result['sum'];				
				}
			}
	$mydb->sql_freeresult();
	
	//90-120 day----------------------------------------
		
		$sql = "SELECT count(*) as count, sum(i.unpaidamount) as sum 
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock)
						WHERE convert(varchar, IssueDate, 112) < convert(varchar, dateadd(day, -120, getdate()), 112)
							AND i.unpaidamount > 0
							AND i.AccID = a.AccID
							AND a.PackageId = p.PackageID ";
		if($sid1 == 2){
			$sql .= " and p.ServiceID = 2 ";
		}elseif($sid1 == 4){
			$sql .= " and p.ServiceID = 4 ";
		}elseif($sid1 == 5){	
			$sql .= " and p.ServiceID in(1, 3, 8) ";
		}					
			if($que = $mydb->sql_query($sql)){		
				if($result = $mydb->sql_fetchrow()){																															
					$totalold = $result['count'];	
					$amountold = $result['sum'];				
				}
			}
	$mydb->sql_freeresult();
	
	$InvoiceAll = $InvoiceALL;	
	$Invoice30 = $total30;	
	$Invoice60 = $total60;	
	$Invoice90 = $total90;	
	$Invoice120 = $total120;	
	$Invoice1Old = $totalold;	
	//--Debtor Invoice----------------------------------------------
	
	$retOutDeb = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="500">
				<tr>
					<td align="left" class="formtitle">
						<b>&nbsp;Debtor Invoiced</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">Status</th>
								<th align="center">Invoice</th>								
								<th align="center">Amount</th>
							</thead>
							<tbody>
								<tr class="row1"><td align="left"> Total Number Of Open Invoices</td><td align="right">'.$InvoiceAll.'</td><td align="right">'.formatCurrency($UnpaidAll).'</td></tr>
								<tr class="row2"><td align="left"> Unpaid Invoices between 0 and 30 days</td><td align="right">'.$Invoice30.'</td><td align="right">'.formatcurrency($amount30).'</td></tr>
								<tr class="row1"><td align="left"> Unpaid Invoices between 30 and 60 days</td><td align="right">'.$Invoice60.'</td><td align="right">'.formatcurrency($amount60).'</td></tr>
								<tr class="row2"><td align="left"> Unpaid Invoices between 60 and 90 days</td><td align="right">'.$Invoice90.'</td><td align="right">'.formatcurrency($amount90).'</td></tr>
								<tr class="row1"><td align="left"> Unpaid Invoices between 90 and 120 days</td><td align="right">'.$Invoice120.'</td><td align="right">'.formatcurrency($amount120).'</td></tr>
								<tr class="row2"><td align="left"> Unpaid Invoices older than 120 days</td><td align="right">'.$Invoice1Old.'</td><td align="right">'.formatcurrency($amountold).'</td></tr>
							</tbody>																					
							</table>						
							</td>
						</tr></table>';
						
//----------Header Summary------------------------------------------------------


$retOuthead ='<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="310">
				<tr>
					<td colspan="2">';
$retOuthead .= '<table border="1" cellpadding="3" cellspacing="0" align="left" width="100%" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa">
				<tr class="row2"><td align="left" width="160"> Customer Number</td><td align="right">'.$TotalCust.'</td></tr>
				<tr class="row2"><td align="left"> Invoice Issued Number</td><td align="right">'.$TotalInvoice.'</td></tr>
				<tr class="row2"><td align="left"> Net Value</td><td align="right">'.FormatCurrency($TotalNetAmount).'</td></tr>
				<tr class="row2"><td align="left"> VAT Charges</td><td align="right">'.FormatCurrency($TotalVAT).'</td></tr>
				<tr class="row2"><td align="left"> Total Invoice Value</td><td align="right">'.FormatCurrency($TotalAmount).'</td></tr>
				<tr class="row2"><td align="left"> Unpaid Value</td><td align="right">'.FormatCurrency($TotalUnpaid).'</td></tr>
				</table>';		
$retOuthead .='</td></tr></table>';
//-----By Month------------------------------------------------------------------------
	
	$retOutMon ='<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>&nbsp;Billing By Month</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
	<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa" bgColor="#ffffff">
							<thead>																	
								<th align="center">No.</th>								
								<th align="center">Date</th>
								<th align="center">Invoice Qty</th>	
								<th align="center">Amount</th>
								<th align="center">Invoice Paid Qty</th>	
								<th align="center">Amount</th>
								<th align="center">Invoice Unpaid Qty</th>
								<th align="center">Amount</th>																																																
							</thead>';
		$sql = "SELECT convert(varchar, datepart(m, i.IssueDate)) + '-' + convert(varchar, year(i.IssueDate)) 'Date',
										count(i.Invoiceid) AS 'totalinvoice', sum(i.InvoiceAMount) as 'totalamount'
						INTO #tmpInvoice
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock)
						WHERE i.IssueDate is not null 
							AND i.AccID = a.AccID
							AND a.PackageId = p.PackageID ";
		if($sid1 == 2){
			$sql .= " and p.ServiceID = 2 ";
		}elseif($sid1 == 4){
			$sql .= " and p.ServiceID = 4 ";
		}elseif($sid1 == 5){	
			$sql .= " and p.ServiceID in(1, 3, 8) ";
		}					
		$sql .= " group by datepart(m, IssueDate), year(IssueDate)
							select d.Date, d.TotalInvoice, d.TotalAmount, Count(i.InvoiceID) as 'totalunpaid', sum(i.UnpaidAmount) as 'unpaidamount'
							from #tmpInvoice d left join tblCustomerInvoice i on
								d.Date = convert(varchar, datepart(m, i.IssueDate)) + '-' + convert(varchar, year(i.IssueDate)) 
							and i.UnpaidAMount > 0
							group by d.Date, d.TotalInvoice, d.TotalAmount, year(i.IssueDate), datepart(m, i.IssueDate)
							order by year(i.IssueDate) desc, datepart(m, i.IssueDate) desc
							drop table #tmpInvoice";
	
	if($que = $mydb->sql_query($sql)){
	
		$suminvoice = 0;
		$sumamount = 0;
		$sumpaidinvoice = 0;
		$sumpaidamount = 0;
		$sumunpaidinvoice = 0;
		$sumunpaidamount = 0;
		
		$iLoop = 0;
		$no = 0;
		$retOut .= "<tbody>";
		while($result = $mydb->sql_fetchrow()){
																																	
			$Date = $result['Date'];										
			$TotalInvoice =	$result['TotalInvoice'];
			$TotalAmount =	$result['TotalAmount'];
			$totalunpaid =	$result['totalunpaid'];
			$unpaidamount =	$result['unpaidamount'];
			$TotalPaid = intval($TotalInvoice) - intval($totalunpaid);
			$AmountPaid = floatval($TotalAmount) - floatval($unpaidamount);
			
			$sumpaidamount += $AmountPaid;
			$sumpaidinvoice += $TotalPaid;
			$suminvoice += $TotalInvoice;
			$sumamount += $TotalAmount;
			$sumunpaidinvoice += $totalunpaid;
			$sumunpaidamount += $unpaidamount;
			//$ShowDate = "<a href='./report/pg225.php?code=".$Date."' target='_blank'>".$Date."</a>";
			$iLoop++;															
			$no++;
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOutMon .= '<tr>';																			
			$retOutMon .= '<td class="'.$style.'" align="center">'.$no.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="left">'.$Date.'</td>';																								
			$retOutMon .= '<td class="'.$style.'" align="right">'.$TotalInvoice.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right">'.FormatCurrency($TotalAmount).'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right">'.$TotalPaid.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right">'.FormatCurrency($AmountPaid).'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right">'.$totalunpaid.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right">'.FormatCurrency($unpaidamount).'</td>';
			$retOutMon .= '</tr>';
		}		
		$retOutMon .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan="2" height="20">Total:&nbsp;</td>
											<td align="right"><u>'.$suminvoice.'</u></td>
											<td align="right"><u>'.FormatCurrency($sumamount).'</u></td>
											<td align="right"><u>'.$sumpaidinvoice.'</u></td>
											<td align="right"><u>'.FormatCurrency($sumpaidamount).'</u></td>
											<td align="right"><u>'.$sumunpaidinvoice.'</u></td>
											<td align="right"><u>'.FormatCurrency($sumunpaidamount).'</u></td>
										</tr>
									</tfoot>';
		}
	$mydb->sql_freeresult();		
	$retOutMon	.= "</table></td></tr></table>";

	$table = '<table width="99%" border="0" cellspacing="0" cellpadding="0">
				<tr><td>'.$retOuthead.'</td></tr>
				<tr><td height="15"></td></tr>
				<tr><td >'.$retOutDeb.'</td></tr>
				<tr><td height="15"></td></tr>
				<tr><td>'.$retOutMon.'</td></tr>
			  </table>';
print $table;
?>
