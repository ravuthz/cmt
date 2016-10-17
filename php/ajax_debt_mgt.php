<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />



<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	

	$sid1 = $_GET['sid'];
	$sn = $_GET['st'];
	$where = $_GET['w'];
	
	
	
	
//-------------------------------------------------	
//  Summay Report								  |
//-------------------------------------------------

	//Get Count Customer-----------------------------------------------------------
			$sql = "SELECT COUNT(a.AccID) AS TotalCust 
							FROM tblCustProduct a(nolock), tblTarPackage t
							WHERE a.PackageID = t.PackageID ";
			$sql .=	"and a.AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID)) ";
			
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
			$sql .=	"and a.AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID)) ";
							
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
		$sql .=	"and a.AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID)) ";
							
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
		$sql .=	"and a.AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID)) ";
						
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
		$sql .=	"and a.AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID)) ";					 
							 
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
		$sql .=	"and a.AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID)) ";
							
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
		$sql .=	"and a.AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID)) ";					
							
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
		$sql .=	"and a.AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID)) ";
							
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
		$InvoiceAll = "<a href='../report/pg224.php?sid=".$sid1."&where=".$where."&id=0' target='_blank'>".$InvoiceALL."</a>";	
	else
		$InvoiceAll = 0;
	if(intval($total30) >0)
		$Invoice30 = "<a href='../report/pg224.php?sid=".$sid1."&where=".$where."&id=1' target='_blank'>".$total30."</a>";	
	else
		$Invoice30 = 0;
	if(intval($total60) >0)
		$Invoice60 = "<a href='../report/pg224.php?sid=".$sid1."&where=".$where."&id=2' target='_blank'>".$total60."</a>";	
	else
		$Invoice60 = 0;
	if(intval($total90) >0)
		$Invoice90 = "<a href='../report/pg224.php?sid=".$sid1."&where=".$where."&id=3' target='_blank'>".$total90."</a>";	
	else
		$Invoice90 = 0;
	if(intval($total120) >0)
		$Invoice120 = "<a href='../report/pg224.php?sid=".$sid1."&where=".$where."&id=4' target='_blank'>".$total120."</a>";	
	else
		$Invoice120 = 0;
	if(intval($totalold) >0)
		$Invoice1Old = "<a href='../report/pg224.php?sid=".$sid1."&where=".$where."&id=5' target='_blank'>".$totalold."</a>";	
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
								<th colspan=2 align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">Description</th>								
								
								
								<th colspan=4 align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Total Invoices</th>	
								
								<th colspan=3 align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Invoices Paid</th>	
								
								<th colspan=3 align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999; border-right:1px solid #999999;">Invoices unpaid</th>																																																
								
							</thead>
	
							<thead>																	
								<th align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">No.</th>								
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">BillEndDate</th>
								
								
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Normal</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">VIP</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Total</th>	
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Number</th>	
								
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Normal Paid</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">VIP Paid</th>	
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Total Paid</th>	

								
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Normal Unpaid</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">VIP Unpaid</th>	
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999; border-right:1px solid #999999;">Total Unpaid</th>																																																
								
							</thead>
							
							'	
							
							;
		$sql = "
					Begin Try
	Drop table #Ag
End Try
Begin Catch
End Catch

select	Convert(varchar,BillEndDate,102) BillEndDate,
		IsNull(ex.Remark,'Nor') Remark,
		Count(ci.InvoiceID) TotalInvoice,
		Round(Sum(Round(InvoiceAmount,2)),2) InvoiceAmount,
		Count(ci.InvoiceID) - Count(Case when ci.UnpaidAmount > 0 then 1 end) PaidInvoice,
		Round(Sum(Round(InvoiceAmount,2)),2) - Round(Sum(Round(UnpaidAmount,2)),2) PaidAmount,
		Count(Case when ci.UnpaidAmount > 0 then 1 end) UnpaidInvoice,
		Round(Sum(Round(UnpaidAmount,2)),2) UnpaidAmount
into #Ag
from tblCustomerInvoice ci(nolock)
join tblSysBillRunCycleInfo sb(nolock) on sb.CycleID = ci.billingcycleid
join tblTarPackage tp(nolock) on tp.packageid = sb.packageid
left join (select Distinct AccID,'VIP' Remark from dbo.tblExceptiondetail where Remark = 'Exception') ex on ci.AccID=ex.AccID
where ci.IssueDate is not null
and ci.BillingCycleID in ( select cycleid from tblSysBillRuncycleinfo )

				";
$sql .=	"and ci.AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID)) ";					
							
		if($sid1 == 2){
			$sql .= " and tp.ServiceID = 2 ";
		}elseif($sid1 == 4){
			$sql .= " and tp.ServiceID = 4 ";
		}elseif($sid1 == 5){	
			$sql .= " and tp.ServiceID in (1, 3, 8) ";
		}
							
		$sql .= " group by Convert(varchar,BillEndDate,102),IsNull(ex.Remark,'Nor') ";
		
$sql .= "

Select	BillEndDate,
		Sum(TotalInvoice) TotalInvoice,
		NorInvoiceAmount	= IsNull(Sum(Case When Remark <> 'VIP' then IsNull(InvoiceAmount,0) end),0),
		VipInvoiceAmount	= IsNull(Sum(Case When Remark = 'VIP' then IsNull(InvoiceAmount,0) end),0),
		Sum(InvoiceAmount) InvoiceAmount,
		
		Sum(PaidInvoice) PaidInvoice,
		NorPaidAmount	= IsNull(Sum(Case When Remark <> 'VIP' then IsNull(PaidAmount,0) end),0),
		VipPaidAmount	= IsNull(Sum(Case When Remark = 'VIP' then IsNull(PaidAmount,0) end),0),
		Sum(PaidAmount) PaidAmount,
		
		Sum(UnPaidInvoice) UnPaidInvoice,
		NorUnPaidAmount	= IsNull(Sum(Case When Remark <> 'VIP' then IsNull(UnPaidAmount,0) end),0),
		VipUnPaidAmount	= IsNull(Sum(Case When Remark = 'VIP' then IsNull(UnPaidAmount,0) end),0),
		Sum(UnPaidAmount) UnPaidAmount
		
from #Ag
group by BillEndDate
order by BillEndDate desc

Drop table #Ag


";		
		
		
	
	if($que = $mydb->sql_query($sql)){
	
		$sumTotalInvoice = 0;
		$sumNorInvoiceAmount = 0;
		$sumVipInvoiceAmount = 0;
		$sumInvoiceAmount = 0;
		
		$sumPaidInvoice = 0;
		$sumNorPaidAmount = 0;
		$sumVipPaidAmount = 0;
		$sumPaidAmount = 0;
		
		$sumUnPaidInvoice = 0;
		$sumNorUnPaidAmount = 0;
		$sumVipUnPaidAmount = 0;
		$sumUnPaidAmount = 0;
		
		
		
		$iLoop = 0;
		$no = 0;
		$retOut .= "<tbody>";
		while($result = $mydb->sql_fetchrow($que)){	
																												
			$BillEndDate = $result['BillEndDate'];										
			
			$TotalInvoice =	$result['TotalInvoice'];
			$NorInvoiceAmount =	$result['NorInvoiceAmount'];
			$VipInvoiceAmount =	$result['VipInvoiceAmount'];
			$InvoiceAmount =	$result['InvoiceAmount'];
			
			
			$NorPaidAmount	=	$result['NorPaidAmount'];
			if ($NorInvoiceAmount == 0)
				$NorPaidPercent=100;
			else
				$NorPaidPercent	=	round(($NorPaidAmount*100) / $NorInvoiceAmount,2);
			
			$VipPaidAmount =	$result['VipPaidAmount'];
			if ($VipInvoiceAmount == 0)
				$VipPaidPercent = 100;
			else
				$VipPaidPercent	=	round(($VipPaidAmount*100) / $VipInvoiceAmount,2);
				
			$PaidAmount =	$result['PaidAmount'];
			if($InvoiceAmount == 0)
				$PaidInvoice = 100;
			else
				$PaidInvoice =	round(($PaidAmount*100) / $InvoiceAmount,2);
			
			
			
			$NorUnPaidAmount =	$result['NorUnPaidAmount'];
			$NorUnPaidPercent	=	100 - $NorPaidPercent;
			$VipUnPaidAmount =	$result['VipUnPaidAmount'];
			$VipUnPaidPercent	=	100 - $VipPaidPercent;
			$UnPaidAmount =	$result['UnPaidAmount'];
			$UnPaidInvoice =	100 - $PaidInvoice;
			
		
			$sumNorInvoiceAmount += $NorInvoiceAmount;
			$sumVipInvoiceAmount += $VipInvoiceAmount;
			$sumInvoiceAmount += $InvoiceAmount;
			$sumTotalInvoice += $TotalInvoice;

			
			$sumNorPaidAmount += $NorPaidAmount;
			if($sumNorInvoiceAmount == 0)
				$sumNorPaidPercent = 100;
			else
				$sumNorPaidPercent =	round(($sumNorPaidAmount*100) / $sumNorInvoiceAmount,2);
			
			$sumVipPaidAmount += $VipPaidAmount;
			if($sumVipInvoiceAmount == 0)
				$sumVipPaidPercent = 100;
			else
				$sumVipPaidPercent =	round(($sumVipPaidAmount*100) / $sumVipInvoiceAmount,2);

			$sumPaidAmount += $PaidAmount;
			if($sumInvoiceAmount == 0)
				$sumPaidPercent = 100;
			else
				$sumPaidPercent =	round(($sumPaidAmount*100) / $sumInvoiceAmount,2);
			
			
			
			$sumNorUnPaidAmount += $NorUnPaidAmount;
			$SumNorUnPaidPercent	=	100 - $sumNorPaidPercent;
			
			$sumVipUnPaidAmount += $VipUnPaidAmount;
			$SumVipUnPaidPercent	=	100 - $sumVipPaidPercent;
			
			$sumUnPaidAmount += $UnPaidAmount;
			$sumUnPaidPercent	=	100 - $sumPaidPercent;
				
			

			
			$dNorInvoiceAmount = "<a href='./ajax_debt_mgt_detail.php?code=".$BillEndDate."&sid=".$sid."&where=".$where."&k=All&re=Nor' target='_blank'>".FormatCurrency($NorInvoiceAmount)."</a>";
			$dVipInvoiceAmount = "<a href='./ajax_debt_mgt_detail.php?code=".$BillEndDate."&sid=".$sid."&where=".$where."&k=All&re=VIP' target='_blank'>".FormatCurrency($VipInvoiceAmount)."</a>";
			$dInvoiceAmount = "<a href='./ajax_debt_mgt_detail.php?code=".$BillEndDate."&sid=".$sid."&where=".$where."&k=All&re=All' target='_blank'>".FormatCurrency($InvoiceAmount)."</a>";
			
			$dNorPaidAmount = "<a href='./ajax_debt_mgt_detail.php?code=".$BillEndDate."&sid=".$sid."&where=".$where."&k=p&re=Nor' target='_blank'>".FormatCurrency($NorPaidAmount)."</a>";
			$dVipPaidAmount = "<a href='./ajax_debt_mgt_detail.php?code=".$BillEndDate."&sid=".$sid."&where=".$where."&k=p&re=VIP' target='_blank'>".FormatCurrency($VipPaidAmount)."</a>";
			$dPaidAmount = "<a href='./ajax_debt_mgt_detail.php?code=".$BillEndDate."&sid=".$sid."&where=".$where."&k=All&re=All' target='_blank'>".FormatCurrency($PaidAmount)."</a>";
	
	
			$dNorUnPaidAmount = "<a href='./ajax_debt_mgt_detail.php?code=".$BillEndDate."&sid=".$sid."&where=".$where."&k=u&re=Nor' target='_blank'>".FormatCurrency($NorUnPaidAmount)."</a>";
			$dVipUnPaidAmount = "<a href='./ajax_debt_mgt_detail.php?code=".$BillEndDate."&sid=".$sid."&where=".$where."&k=u&re=VIP' target='_blank'>".FormatCurrency($VipUnPaidAmount)."</a>";
			$dUnPaidAmount = "<a href='./ajax_debt_mgt_detail.php?code=".$BillEndDate."&sid=".$sid."&where=".$where."&k=All&re=All' target='_blank'>".FormatCurrency($UnPaidAmount)."</a>";
	
			
			$iLoop++;															
			$no++;
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOutMon .= '<tr>';																			
			$retOutMon .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px dotted #999999;">'.$no.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$BillEndDate.'</td>';
																											
			
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$dNorInvoiceAmount.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$dVipInvoiceAmount.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$dInvoiceAmount.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.number_format($TotalInvoice).'</td>';
	
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$dNorPaidAmount."=".number_format($NorPaidPercent,2)."%".'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$dVipPaidAmount."=".number_format($VipPaidPercent,2)."%".'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$dPaidAmount."=".number_format($PaidInvoice,2)."%".'</td>';
	
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$dNorUnPaidAmount."=".number_format($NorUnPaidPercent,2)."%".'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$dVipUnPaidAmount."=".number_format($VipUnPaidPercent,2)."%".'</td>';		
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999;">'.$dUnPaidAmount."=".number_format($UnPaidInvoice,2)."%".'</td>';
			$retOutMon .= '</tr>';
		}		
		
		$retOutMon .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan="2" style="border:1px solid #999999;">Total:&nbsp;</td>
											
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumNorInvoiceAmount).'</td>
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumVipInvoiceAmount).'</td>
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumInvoiceAmount).'</td>
											<td align="right" style="border:1px solid #999999;">'.number_format($sumTotalInvoice).'</td>

											
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumNorPaidAmount)."<br>=".number_format($sumNorPaidPercent,2)."%".'</td>
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumVipPaidAmount)."<br>=".number_format($sumVipPaidPercent,2)."%".'</td>
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumPaidAmount)."<br>=".number_format($sumPaidPercent,2)."%".'</td>
											
											
											
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumNorUnPaidAmount)."<br>=".number_format($SumNorUnPaidPercent,2)."%".'</td>
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumVipUnPaidAmount)."<br>=".number_format($SumVipUnPaidPercent,2)."%".'</td>
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumUnPaidAmount)."<br>=".number_format($sumUnPaidPercent,2)."%".'%</td>
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
print "<center><h4>CAMINTEL AGING REPORT FOR ".$sn."</h4>
					Print on: ".date("Y-m-d H:i:s")."<br>";			
print $table;
?>
