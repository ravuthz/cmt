<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$month = array(1=>"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	$code=$_GET['code'];
	$serviceid=$_GET['serviceid'];
	$marr = split("-", $code);
	$m = $marr[0];
	$y = $marr[1];
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Cash collection in'.$month[$code].'</b>
					</td>
					<td align="right">'.Date("Y F d H:i:s").'</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid #999999">No.</th>								
								<th align="center" style="border:1px solid #999999">Cycle date</th>
								<th align="center" style="border:1px solid #999999">Subscriber</th>								
								<th align="center" style="border:1px solid #999999">Amount</th>
							</thead>
							<tbody>';
							
	$sql = "SELECT inf.BillEndDate, COUNT(i.CustID) AS Customer, SUM(p.PaymentAmount) AS Amount
					FROM tblCustomerInvoice i(nolock), tblCashPayment p(nolock), tblCustProduct a(nolock), 
								tblTarPackage ta(nolock), tblSysBillRunCycleInfo inf(nolock)
					WHERE i.InvoiceID = p.InvoiceID
								AND i.AccID = a.AccID
								AND i.BillingCycleID = inf.CycleID
								AND a.PackageId = ta.PackageID ";
	if($serviceid == 2)
		$sql .= " AND ta.ServiceID in(2) ";
	if($serviceid == 4)
		$sql .= " AND ta.ServiceID in(4) ";
	if($serviceid == 5)
		$sql .= " AND ta.ServiceID in(1, 3, 8) ";
	$sql .=	" AND convert(varchar, datepart(m, p.PaymentDate)) + '-' + convert(varchar, year(p.PaymentDate)) = '".$code."'
					group by inf.BillEndDate order by inf.BillEndDate desc";
	if($que = $mydb->sql_query($sql)){		
		$n = 0;
		$totalamount = 0;
		$totalcount = 0;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
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
			
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px dotted #999999; border-right:1px dotted #999999">'.$iLoop.'</td>';							
			$retOut .= '<td class="'.$style.'" align="left" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatDate($BillEndDate, 3).'</td>';																								
			$retOut .= '<td class="'.$style.'" align="right"  style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$Customer.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px solid #999999">'.FormatCurrency($Amount).'</td>';

			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>
					<tfood>
						<tr>
							<td align="left" colspan=2 style="border:1px solid #999999">Total</td>
							<td align="right" style="border:1px solid #999999">'.$totalcount.'</td>
							<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalamount).'</td>
						</tr>
					</tfood>																							
								
								</table>						
							</td>
						</tr>
					</table>';
     print $retOut;
?>