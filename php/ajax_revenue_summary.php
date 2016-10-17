<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<style>
	td, th{
		font-family:"Courier New", Courier, monospace;
		font-size:13px;
	}
</style>
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$cid = $_GET['cid'];
	$where = $_GET['w'];
	$pid = $_GET['pid'];
	$tid = $_GET['tid'];	
	$ct = $_GET['ct'];
	$pt = $_GET['pt'];
	$tt = $_GET['tt'];		
	$sid = $_GET['sid'];		
	$st = $_GET['st'];	
		
function generateReport($cid, $pid, $tid, $ct, $pt, $tt, $sid){
	global $mydb;
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle" colspan=2>	';
	if($pid > 0)
		$retOut .= ' Package type: <b>'.$pt.' [ ID: '.$pid.' ]</b><br> ';				
	else
		$retOut .= ' <b>'.$pt.'</b><br> ';											
	$retOut .=	' </td>					
				</tr> 
				<tr>
					<td width=10>&nbsp;</td>
					<td>
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																									
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>																
								<th align="center" width="9%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Inv type</th>	
								<th align="center" width="4%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Inv</th>									
								<th align="center" width="9%" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>Monthly</th>
								<th align="center" width="10%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Usage</th>
								<th align="center" width="9%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Other</th>
								<th align="center" width="10%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Discount</th>
								<th align="center" width="10%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Net</th>
								<th align="center" width="9%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">VAT</th>
								<th align="center" width="10%" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>Total</th>
								<th align="center" width="8%" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>Paid</th>
								<th align="center" width="12%" style="border-left:1px dotted #999999; border-top:1px solid #999999; border-right:1px solid #999999;" nowrape>Unpaid</th>											
							</thead>
							<tbody>';
	
	$sql = " SELECT r.InvoiceTypeID, sum(r.TotalInvoice) 'Inv', Sum(r.Duration) 'duration', SUM(r.OtherCharge) 'Other',
									sum(r.MonthlyFee) 'monthly', sum(r.Usage) 'usage', sum(r.Discount) 'discount', sum(r.NetAmount) 'subtotal',
									sum(r.VATAmount) 'vat', sum(r.TotalAmount) 'total', sum(r.PaidAmount) 'paid', sum(r.UnpaidAmount) 'unpaid'
						FROM tblReportRevenueSummary r, tblTarPackage p
						WHERE r.PackageID = p.PackageID and
									Convert(varchar, BillEndDate, 112) ='".$cid."'									
									";

	//	$sql .= "	AND r.ServiceID = ".$sid;
		$sql .= "	AND r.PackageID IN( ".$pid.")";
	if(intval($tid) > 0)
		$sql .= "	AND r.InvoiceTypeID = ".$tid;
		
	$sql .= " GROUP BY r.InvoiceTypeID 
						ORDER BY r.InvoiceTypeID ";

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
			$Inv = $result['Inv'];
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
			$Inv = "<a href='../report/revenue_detail.php?st=".$cid."&it=".$InvoiceTypeID."&pid=".$pid."&pt=".$pt."&tt=".$itype."' target='_blank'>".$Inv."</a>";
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;">'.$iLoop.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$itype.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Inv.'</td>';			
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
											<td align="right" colspan=2 style="border-left:1px solid #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">Total</td>											
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.$TotalInv.'</td>											
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
					</table>';
	return $retOut;
}// end function	

	$retfun = "<table border=0 cellpadding=0 cellspacing=0 width='100%'>
							<tr>
								<td align=left>
									<table width='100%'>
										<tr>
											<td align=left>
												REVENUE SUMMARY<br>
												Service type: <b>".$st."</b><br>
												Invoice type: <b>".$tt."</b><br>
												Cycle date: <b>".$ct."</b><br><br>&nbsp;
											</td>
											<td align=right>
												<img src=../src/Graphic/monthly_revenue.php?sid=".$sid." />
											</td>
										</tr>
									</table>
								</td>
							
							</tr>
						";
		$sql = "SELECT PackageID, TarName, ServiceID 	
						FROM tblTarPackage WHERE 1=1 ";
	//if(intval($sid) >0)
//		$sql .= "	AND ServiceID = ".$sid;	
		$sql .= "	AND PackageID in (".$where;
	$sql .= ") ORDER BY 2 ";	

	if($que = $mydb->sql_query($sql)){
		while($result = $mydb->sql_fetchrow($que)){
			$ServiceID = $result['ServiceID'];
			$tarid = $result['PackageID'];
			$TarName = $result['TarName'];
				$retfun .= "<tr><td>";
			//	print "$cid, $tarid, $tid, $ct, $TarName, $tt, $ServiceID<br><br>";
			//	$retfun .= generateReport($cid, $tarid, $tid, $ct, $TarName, $tt, $ServiceID);
				$retfun .= generateReport($cid, $tarid, $tid, $ct, $TarName, $tt, $ServiceID);
				$retfun .= "</td></tr>";
		}
	}
	$retfun .= "<tr><td><br /></td></tr>";
	$retfun .= "<tr><td>";
		$retfun .= generateReport($cid, $where, $tid, $ct, "GRAND TOTAL", $tt, $ServiceID);
	$retfun .= "</td></tr>";
	$retfun .= "</table>";
	print $retfun;	
	
?>