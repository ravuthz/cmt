<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
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
						Service : '.$servicename.'</b>
					</td>
					<td align="right">Printed on : '.Date("Y F d H:i:s").'</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid #999999">No.</th>
								<th align="center" style="border:1px solid #999999">Cycle Date</th>
								<th align="center" style="border:1px solid #999999">Subscriber</th>								
								<th align="center" style="border:1px solid #999999">Amount</th>
							</thead>
							<tbody>';
							
	$sql = "SELECT inf.BillEndDate, COUNT(i.CustID) AS Customer, SUM(p.PaymentAmount) AS Amount
					FROM tblCustomerInvoice i(nolock)
					join tblCashPayment p(nolock) on i.InvoiceID = p.InvoiceID 
					join tblCustProduct a(nolock) on i.AccID = a.AccID
					join tblSysBillRunCycleInfo inf(nolock) on i.BillingCycleID = inf.CycleID
					join tblTarPackage ta(nolock) on  inf.PackageId = ta.PackageID
			";
	$sql .=	" Where left(convert(varchar,p.paymentdate,120),7) = '".$code."'";		
	
	$sql .=	" And a.AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID)) ";
	
	if($serviceid == 2)
		$sql .= " AND ta.ServiceID in(2) ";
	if($serviceid == 4)
		$sql .= " AND ta.ServiceID in(4) ";
	if($serviceid == 5)
		$sql .= " AND ta.ServiceID in(1, 3, 8) ";
	
	$sql .=	" group by inf.BillEndDate order by inf.BillEndDate desc";
						
	if($que = $mydb->sql_query($sql)){		
		$n = 0;
		$totalamount = 0;
		$totalcount = 0;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){
			
			$BillEndDate = $result['BillEndDate'];												
			$Customer = $result['Customer'];
			$Amount = $result['Amount'];
			$linkCust = "<a href='./cashcollectiondetail.php?code=".$code."&serviceid=".$serviceid."&cdate=".FormatDate($BillEndDate, 4)."&where=".$where."'  target='_blank'>".$Customer."</a>";
					
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
			$retOut .= '<td class="'.$style.'" align="center" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatDate($BillEndDate, 5).'</td>';																								
			$retOut .= '<td class="'.$style.'" align="right"  style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$linkCust.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px solid #999999">'.FormatCurrency($Amount).'</td>';

			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>
					<tfood>
						<tr>
							<td align="center" colspan=2 style="border:1px solid #999999">Total</td>
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