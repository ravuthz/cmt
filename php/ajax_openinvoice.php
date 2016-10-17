<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />



<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	

	$sid1 = $_GET['sid'];
	$sn = $_GET['sn'];
	
	
	
	
//-------------------------------------------------	
//  Summay Report								  |
//-------------------------------------------------

	//Get Count Customer-----------------------------------------------------------
			$sql = "SELECT COUNT(a.AccID) AS TotalCust 
							FROM tblCustProduct a(nolock), tblTarPackage t
							WHERE a.PackageID = t.PackageID ";
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
								and i.BillingCycleID in ( select cycleid from tblSysBillRuncycleinfo )
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
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock), tblSysBillRunCycleInfo inf(nolock) 
						WHERE i.UnpaidAmount > 0 
							AND i.IssueDate is not NULL
							AND i.AccID = a.AccID
							AND i.BillingCycleID = inf.CycleID
							AND a.PackageId = p.PackageID
							and i.BillingCycleID in ( select cycleid from tblSysBillRuncycleinfo )
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
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock), tblSysBillRunCycleInfo inf(nolock) 
						WHERE convert(varchar, inf.BillEndDate, 112) > convert(varchar, dateadd(day, -30, getdate()), 112) 
							AND i.unpaidamount > 0
							AND i.BillingCycleID = inf.CycleID
							AND i.AccID = a.AccID
							AND a.PackageId = p.PackageID
							and i.BillingCycleID in ( select cycleid from tblSysBillRuncycleinfo )
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
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock), tblSysBillRunCycleInfo inf(nolock) 
						WHERE convert(varchar, inf.BillEndDate, 112) <= convert(varchar, dateadd(day, -30, getdate()), 112) 
							AND convert(varchar, inf.BillEndDate, 112) > convert(varchar, dateadd(day, -60, getdate()), 112)  
							AND i.unpaidamount > 0
							AND i.AccID = a.AccID
							AND i.BillingCycleID = inf.CycleID
							AND a.PackageId = p.PackageID
							and i.BillingCycleID in ( select cycleid from tblSysBillRuncycleinfo )
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
					$total60 = $result['count'];	
					$amount60 = $result['sum'];				
				}
			}
	$mydb->sql_freeresult();
	
//60-90 day----------------------------------------	

		$sql = "SELECT count(*) as count, sum(i.unpaidamount) as sum 
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock), tblSysBillRunCycleInfo inf(nolock)
						WHERE convert(varchar, inf.BillEndDate, 112) <= convert(varchar, dateadd(day, -60, getdate()), 112) 
							AND convert(varchar, inf.BillEndDate, 112) > convert(varchar, dateadd(day, -90, getdate()), 112)
							AND i.unpaidamount > 0
							AND i.BillingCycleID = inf.CycleID
							AND i.AccID = a.AccID
							AND a.PackageId = p.PackageID 
							and i.BillingCycleID in ( select cycleid from tblSysBillRuncycleinfo )";
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
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock), tblSysBillRunCycleInfo inf(nolock)
						WHERE convert(varchar, inf.BillEndDate, 112) < convert(varchar, dateadd(day, -90, getdate()), 112) 
							AND convert(varchar, inf.BillEndDate, 112) >= convert(varchar, dateadd(day, -120, getdate()), 112)
							AND i.unpaidamount > 0
							AND i.BillingCycleID = inf.CycleID
							AND i.AccID = a.AccID
							AND a.PackageId = p.PackageID 
							and i.BillingCycleID in ( select cycleid from tblSysBillRuncycleinfo )";
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
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock), tblSysBillRunCycleInfo inf(nolock)
						WHERE convert(varchar, inf.BillEndDate, 112) < convert(varchar, dateadd(day, -120, getdate()), 112)
							AND i.unpaidamount > 0
							AND i.BillingCycleID = inf.CycleID
							AND i.AccID = a.AccID
							AND a.PackageId = p.PackageID 
							and i.BillingCycleID in ( select cycleid from tblSysBillRuncycleinfo )";
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
	if(intval($InvoiceALL) >0)
		$InvoiceAll = "<a href='../report/pg224.php?sid=".$sid1."&id=0' target='_blank'>".$InvoiceALL."</a>";	
	else
		$InvoiceAll = 0;
	if(intval($total30) >0)
		$Invoice30 = "<a href='../report/pg224.php?sid=".$sid1."&id=1' target='_blank'>".$total30."</a>";	
	else
		$Invoice30 = 0;
	if(intval($total60) >0)
		$Invoice60 = "<a href='../report/pg224.php?sid=".$sid1."&id=2' target='_blank'>".$total60."</a>";	
	else
		$Invoice60 = 0;
	if(intval($total90) >0)
		$Invoice90 = "<a href='../report/pg224.php?sid=".$sid1."&id=3' target='_blank'>".$total90."</a>";	
	else
		$Invoice90 = 0;
	if(intval($total120) >0)
		$Invoice120 = "<a href='../report/pg224.php?sid=".$sid1."&id=4' target='_blank'>".$total120."</a>";	
	else
		$Invoice120 = 0;
	if(intval($totalold) >0)
		$Invoice1Old = "<a href='../report/pg224.php?sid=".$sid1."&id=5' target='_blank'>".$totalold."</a>";	
	else
		$Invoice1Old = 0;
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
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid #999999;">Status</th>
								<th align="center" style="border:1px solid #999999;">Invoice</th>								
								<th align="center" style="border:1px solid #999999;">Amount</th>
							</thead>
							<tbody>
								<tr class="row1">
									<td align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;"> Total Number Of Open Invoices</td>
									<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$InvoiceAll.'</td>
									<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999">'.formatCurrency($UnpaidAll).'</td>
								</tr>
								<tr class="row2">
									<td align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;"> Unpaid Invoices between 0 and 30 days</td>
									<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Invoice30.'</td>
									<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;border-right:1px solid #999999">'.formatcurrency($amount30).'</td>
								</tr>
								<tr class="row1">
									<td align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;"> Unpaid Invoices between 30 and 60 days</td>
									<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Invoice60.'</td>
									<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;border-right:1px solid #999999">'.formatcurrency($amount60).'</td>
								</tr>
								<tr class="row2">
									<td align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;"> Unpaid Invoices between 60 and 90 days</td>
									<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Invoice90.'</td>
									<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999">'.formatcurrency($amount90).'</td>
								</tr>
								<tr class="row1">
									<td align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;"> Unpaid Invoices between 90 and 120 days</td>
									<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Invoice120.'</td>
									<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999">'.formatcurrency($amount120).'</td>
								</tr>
								<tr class="row2">
									<td align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999"> Unpaid Invoices older than 120 days</td>
									<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999">'.$Invoice1Old.'</td>
									<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999; border-bottom:1px solid #999999">'.formatcurrency($amountold).'</td>
								</tr>
							</tbody>																					
							</table>						
							</td>
						</tr></table>';
						
//----------Header Summary------------------------------------------------------


$retOuthead ='<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="310">
				<tr>
					<td align="left" class="formtitle">
						<b>&nbsp;Invoice Summary</b>
					</td>
					<td align="right"></td>
				</tr>
				<tr>
					<td colspan="2">';
$retOuthead .= '<table border="0" cellpadding="3" cellspacing="0" align="left" width="100%" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa">
				<tr class="row2">
					<td align="left" width="160" style="border-left:1px solid #999999; border-top:1px solid #999999;"> Account Number</td>
					<td align="right" style="border-left:1px dotted #999999; border-top:1px solid #999999; border-right:1px solid #999999">'.$TotalCust.'</td>
				</tr>
				<tr class="row2">
					<td align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;"> Invoice Issued Number</td>
					<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999">'.$TotalInvoice.'</td>
				</tr>
				<tr class="row2">
					<td align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;"> Net Value</td>
					<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999">'.FormatCurrency($TotalNetAmount).'
				</td>
					</tr>
				<tr class="row2">
					<td align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;"> VAT Charges</td>
					<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999">'.FormatCurrency($TotalVAT).'</td>
				</tr>
				<tr class="row2">
					<td align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;"> Total Invoice Value</td>
					<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999">'.FormatCurrency($TotalAmount).'</td>
				</tr>
				<tr class="row2">
					<td align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;"> Paid Value</td>
					<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999">'.FormatCurrency(floatval($TotalAmount) - floatval($TotalUnpaid)).'</td></tr>
				<tr class="row2">
					<td align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;"> Unpaid Value</td>
					<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999; border-bottom:1px solid #999999;">'.FormatCurrency($TotalUnpaid).'</td>
				</tr>
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
	<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa" bgColor="#ffffff">
							<thead>																	
								<th align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">No.</th>								
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Date</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Inv Qty</th>	
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Amount</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Inv paid Qty</th>	
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Amount</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Inv unpaid Qty</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999; border-right:1px solid #999999;">Amount</th>																																																
							</thead>';
		$sql = "
						SELECT convert(varchar, datepart(m, inf.BillEndDate)) + '-' + convert(varchar, year(inf.BillEndDate)) 'Date',
										count(i.Invoiceid) AS 'totalinvoice', sum(i.InvoiceAMount) as 'totalamount'
						INTO #tmpInvoice
						FROM tblCustomerInvoice i(nolock), tblTarPackage p(nolock), tblSysBillRunCycleInfo inf(nolock)
						WHERE i.IssueDate is not null 
							AND i.BillingCycleID = inf.CycleID							
							AND inf.PackageId = p.PackageID 
							and i.BillingCycleID in ( select cycleid from tblSysBillRuncycleinfo )";
		if($sid1 == 2){
			$sql .= " and p.ServiceID = 2 ";
		}elseif($sid1 == 4){
			$sql .= " and p.ServiceID = 4 ";
		}elseif($sid1 == 5){	
			$sql .= " and p.ServiceID in(1, 3, 8) ";
		}					
		$sql .= " group by datepart(m,  inf.BillEndDate), year(inf.BillEndDate)
							select d.Date, d.TotalInvoice, d.TotalAmount, Count(i.InvoiceID) as 'totalunpaid', 
										sum(i.UnpaidAmount) as 'unpaidamount'
							from #tmpInvoice d, tblCustomerInvoice i(nolock), 
										tblTarPackage p(nolock), tblSysBillRunCycleInfo inf(nolock)
							where d.Date = convert(varchar, datepart(m, inf.BillEndDate)) + '-' + convert(varchar, year(inf.BillEndDate)) 								
								and i.BillingCycleID = inf.CycleID
								and inf.PackageID = p.PackageID ";
		if($sid1 == 2){
			$sql .= " and p.ServiceID = 2 ";
		}elseif($sid1 == 4){
			$sql .= " and p.ServiceID = 4 ";
		}elseif($sid1 == 5){	
			$sql .= " and p.ServiceID in(1, 3, 8) ";
		}						
								
							$sql .= "	and i.UnpaidAMount > 0
							group by d.Date, d.TotalInvoice, d.TotalAmount, year(inf.BillEndDate), datepart(m, inf.BillEndDate)
							order by year(inf.BillEndDate) desc, datepart(m, inf.BillEndDate) desc
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
		while($result = $mydb->sql_fetchrow($que)){																											
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
			if(strlen($Date) < 7)
				$strDate = "0".$Date;
			else
				$strDate = $Date;
			$ShowDate = "<a href='../report/pg225.php?code=".$Date."&sid=".$sid."' target='_blank'>".$strDate."</a>";
			$iLoop++;															
			$no++;
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOutMon .= '<tr>';																			
			$retOutMon .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px dotted #999999;">'.$no.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$ShowDate.'</td>';																								
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$TotalInvoice.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($TotalAmount).'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$TotalPaid.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($AmountPaid).'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$totalunpaid.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999;">'.FormatCurrency($unpaidamount).'</td>';
			$retOutMon .= '</tr>';
		}		
		$retOutMon .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan="2" style="border:1px solid #999999;">Total:&nbsp;</td>
											<td align="right" style="border:1px solid #999999;">'.$suminvoice.'</td>
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumamount).'</td>
											<td align="right" style="border:1px solid #999999;">'.$sumpaidinvoice.'</td>
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumpaidamount).'</td>
											<td align="right" style="border:1px solid #999999;">'.$sumunpaidinvoice.'</td>
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumunpaidamount).'</td>
										</tr>
									</tfoot>';
		}else{
			$error = $mydb->sql_error();
			print $error['message'];
		}
	$mydb->sql_freeresult();		
	$retOutMon	.= "</table></td></tr></table>";

	$table = '<table width="99%" border="0" cellspacing="0" cellpadding="0">
				<tr><td>'.$retOuthead.'</td></tr>
				<tr>
					<td height="15">
					</td>
				</tr>
				<tr><td >'.$retOutDeb.'
					<a href=../src/Graphic/GraphMonthly.php?year='.date("Y").'&sid='.$sid1.'>
						<img src=../src/graphic/aging.php?sid='.$sid1.' border=0 align=top>
					</a>
					</td>
				</tr>
				<tr><td height="15"></td></tr>
				<tr><td>'.$retOutMon.'</td></tr>
			  </table>';
print "<center><h4>ONLINE AGING REPORT FOR ".$sn."</h4>
					Print on: ".date("Y-m-d H:i:s")."<br>";			
print $table;
?>
