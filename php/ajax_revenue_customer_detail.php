<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<style>
	td, th{
		font-family:"Courier New", Courier, monospace;
		font-size:11px;
	}
</style>
<script language="JavaScript" src="../javascript/ajax_gettransaction.js"></script>
<script language="javascript">
function showReport1(invoiceid){
		
		
			
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='../images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-result").innerHTML = loading;
			url = "./ajax_invoice.php?invoiceid="+invoiceid;
			window.open(url, "", "width=600,height=220,top=250,left=350");
			//getTranDetail(url, "d-result");		
	}
</script>
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$cid = $_GET['cid'];
	$custname = $_GET['custname'];
	$custid = $_GET['custid'];
	$pid = $_GET['pid'];
	$tid = $_GET['tid'];	
	$ct = $_GET['ct'];
	$pt = $_GET['pt'];
	$tt = $_GET['tt'];		
	$sid = $_GET['sid'];
	$sn = $_GET['sn'];		
	$st = $_GET['st'];	
	
		

	$retOut = '
			<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle" colspan=2>	';
	
		$retOut .= ' REVENUE SUMMARY FOR : <b>'.$sn.'</b><br>
		 							CYCLE DATE: <b>'.$ct.' </b><br>
									Customer Name: <b>'.strtoupper($custname).' ('.$custid.')</b> <br>
									Report printed: '.date("Y-m-d H:i:s");				

	$retOut .=	' </td>					
				</tr> 
				<tr>
					<td width=10>&nbsp;</td>
					<td>
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																									
								<th align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>																								
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Type</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Start</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">End</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrap="nowrap">Inv id</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrap="nowrap">Acc id</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrap="nowrap">Account</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrap="nowrap">Subscription</th>									
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Monthly</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Usage</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Other</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Discount</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Net</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">VAT</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>Total</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>Paid</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999; border-right:1px solid #999999;" nowrape>Unpaid</th>											
							</thead>
							<tbody>';
	
	$sql = " SELECT a.AccID, a.UserName, a.SubscriptionName, i.InvoiceID, r.InvoiceTypeID, Sum(r.Duration) 'duration', 
									SUM(r.OtherCharge) 'Other', i.TransStartDate, i.TransEndDate,
									sum(r.MonthlyFee) 'monthly', sum(r.Usage) 'usage', sum(r.Discount) 'discount', sum(r.NetAmount) 'subtotal',
									sum(r.VATAmount) 'vat', sum(r.TotalAmount) 'total', sum(r.PaidAmount) 'paid', sum(r.UnpaidAmount) 'unpaid'
						FROM tblReportRevenueSummary_customer r, tblCustomerInvoice i, tblCustProduct a 
						WHERE r.InvoiceID = i.InvoiceID and
									i.AccID = a.AccID and			
									i.InvoiceAmount > 0 and						
									Convert(varchar, r.BillEndDate, 112) ='".$cid."' AND
									a.CustID =".$custid;

		if($sid > 0){
			if($serid == 2)
				$sql .= "	AND r.ServiceID = 2";
			elseif($serid == 4)
				$sql .= "	AND r.ServiceID = 4";	
			else
				$sql .= "	AND r.ServiceID in(1, 3, 8)";	
		}	
	if(intval($tid) > 0)
		$sql .= "	AND r.InvoiceTypeID = ".$tid;
		
	$sql .= " GROUP BY a.AccID, a.UserName, a.SubscriptionName, i.InvoiceID, r.InvoiceTypeID, i.TransStartDate, i.TransEndDate 
						ORDER BY a.AccID, a.UserName, a.SubscriptionName, i.InvoiceID, r.InvoiceTypeID ";

	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		$TotalInv = 0;
		$TotaOther = 0.00;
		$TotalDuration = 0;
		$totalMonthlyFee = 0.00;		
		$Totalusage = 0.00;
		$TotalDiscount = 0.00;
		$TotalSubtotal = 0.00;
		$TotalVATAmount = 0.00;
		$GrandTotal = 0.00;
		$GrandPaid = 0.00;
		$GrandUnpaid = 0.00;		
		while($result = $mydb->sql_fetchrow($que)){																															
											
			$InvoiceTypeID = $result['InvoiceTypeID'];										
			if(intval($InvoiceTypeID) == 1) 
				$itype = "Cycle";
			elseif(intval($InvoiceTypeID) == 2) 
				$itype = "Demand";
			else 
				$itype = "Other";
			$InvoiceID = $result['InvoiceID'];
			$TransStartDate = $result['TransStartDate'];
			$TransEndDate = $result['TransEndDate'];
			$AccID = $result['AccID'];
			$UserName = $result['UserName'];
			$SubscriptionName = $result['SubscriptionName'];
			$TotalInv += $Inv;																
			$duration = $result['duration'];											
			$TotalDuration += intval($duration);
			$monthly = $result['monthly'];	
			$totalMonthlyFee += floatval($monthly);										
			$usage = $result['usage'];		
			$Totalusage += floatval($usage);	
			
			$Other = $result['Other'];
			$TotaOther += floatval($Other);
			
			$discount = $result['discount'];
			$TotalDiscount += floatval($discount);	
			
			$subtotal = $result['subtotal'];
			$TotalSubtotal += floatval($subtotal);
			
			$vat = $result['vat'];
			$TotalVATAmount += floatval($vat);
			
			$total = $result['total'];
			$GrandTotal += floatval($total);
			
			$paid = $result['paid'];
			$GrandPaid += floatval($paid);
			
			$unpaid = $result['unpaid'];	
			$GrandUnpaid += floatval($unpaid);		
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			$Inv = "<a href=\"javascript:showReport1(".$InvoiceID.");\">".$InvoiceID."</a>";
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;">'.$iLoop.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$itype.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatDate($TransStartDate, 3).'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatDate($TransEndDate, 3).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Inv.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$AccID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$UserName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.substr($SubscriptionName, 0, 18).'</td>';			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($monthly).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($usage).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($Other).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($discount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($subtotal).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($vat).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($total).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($paid).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999;">'.FormatCurrency($unpaid).'</td>';
			$retOut .= '</tr>';
				
		}
	}
		$mydb->sql_freeresult();
		$TotalInv = "<a href='../report/revenue_detail.php?st=".$cid."&it=0&pid=".$pid."&pt=".$pt."&tt=All&sid=".$sid."' target='_blank'>".$TotalInv."</a>";
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan=8 style="border-left:1px solid #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">Total</td>																																	
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($totalMonthlyFee).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($Totalusage).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($TotaOther).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($TotalDiscount).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($TotalSubtotal).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($TotalVATAmount).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($GrandTotal).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($GrandPaid).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999; border-right:1px solid #999999;">'.FormatCurrency($GrandUnpaid).'</td>										
										</tr>
									</tfoot>												
								</table>						
							</td>
						</tr>	
						<tr>
								<td colspan=2 style="padding-left:15px;">
									<div id="d-result"></div>
								</td>	
							</tr>					
					</table>
						
					';
		
	print $retOut;	
	
?>
