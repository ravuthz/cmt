<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$month = array(1=>"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	$code=$_GET['code'];
	$serviceid=$_GET['serviceid'];
	$servicename=$_GET['servicename'];
	$where = $_GET['where'];
	$marr = split("-", $code);
	$m = $marr[0];
	$y = $marr[1];
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Cash collection in '.$code.'<br>
						Service : '.$servicename.'
						</b>
					</td>
					<td align="right">Printed on : '.Date("Y F d H:i:s").'</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No.</th>							
								<th align="center">Cycle Date</th>
								<th align="center">Invoice Type</th>
								<th align="center">Subscriber</th>								
								<th align="center">Amount</th>
							</thead>
							<tbody>';
							
	$sql = "SELECT inf.BillEndDate,i.InvoiceType 'InvoiceTypeID', it.InvoiceType, COUNT(i.CustID) AS Customer, SUM(p.PaymentAmount) AS Amount
					FROM tblCustomerInvoice i(nolock), tblInvoiceType it(nolock), tblCashPayment p(nolock), tblCustProduct a(nolock), 
								tblTarPackage ta(nolock), tblSysBillRunCycleInfo inf(nolock)
					WHERE i.InvoiceID = p.InvoiceID
								AND i.AccID = a.AccID
								and i.InvoiceType = it.InvoiceTypeID
								AND i.BillingCycleID = inf.CycleID
								AND a.PackageId = ta.PackageID ";
								
	$sql .=	" And a.AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID)) ";
								
	if($serviceid == 2)
		$sql .= " AND ta.ServiceID in(2) ";
	if($serviceid == 4)
		$sql .= " AND ta.ServiceID in(4) ";
	if($serviceid == 5)
		$sql .= " AND ta.ServiceID in(1, 3, 8) ";
	$sql .=	" AND left(convert(varchar, p.PaymentDate, 120),7)  = '".$code."'
					group by inf.BillEndDate,i.InvoiceType, it.InvoiceType order by inf.BillEndDate desc, InvoiceTypeID";
					
	if($que = $mydb->sql_query($sql)){		
		$n = 0;
		$totalamount = 0;
		$totalcount = 0;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){	
			$InvoiceType = $result['InvoiceType'];
			$InvoiceTypeID = $result['InvoiceTypeID'];																														
			
			$BillEndDate = $result['BillEndDate'];												
			$Customer = $result['Customer'];
			$Amount = $result['Amount'];		
			$totalamount += floatval($Amount);
			$totalcount += $Customer;		
			$TranStart = "01 ".$month[$M]." ".$Y;		
			$TranEnd = date('d M Y',strtotime('-1 second',strtotime('+1 month',strtotime($M.'/01/'.$Y.' 00:00:00'))));				
			$iLoop++;																	
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$linkCust = "<a href='./report/pg227.php?code=".$code."&invoicetypeid=".$InvoiceTypeID."&serviceid=".$serviceid."&cdate=".FormatDate($BillEndDate, 4)."' target='_blank'>".$Customer."</a>";
			$retOut .= '<td class="'.$style.'" align="center">'.$iLoop.'</td>';	
			
			$retOut .= '<td class="'.$style.'" align="left">'.FormatDate($BillEndDate, 3).'</td>';	
			$retOut .= '<td class="'.$style.'" align="left">'.$InvoiceType.'</td>';																							
			$retOut .= '<td class="'.$style.'" align="right">'.$linkCust.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Amount).'</td>';

			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>
					<tfood>
						<tr>
							<td align="center" colspan=3>Total</td>
							<td align="right">'.$totalcount.'</td>
							<td align="right">'.FormatCurrency($totalamount).'</td>
						</tr>
					</tfood>																							
								
								</table>						
							</td>
						</tr>
					</table>';
     print $retOut;
?>