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
	$tt = str_replace("0 ,","",$_GET['tt']);		
	$sid = $_GET['sid'];		
	$st = $_GET['st'];	
		
function generateReport($cid, $pid, $tid, $ct, $pt, $tt, $sname, $pack, $sid){
	global $mydb;
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle" colspan=2>	';
		$retOut .= ' <b>&nbsp;&nbsp;'.$pt.'</b><br> ';											
	$retOut .=	' </td>					
				</tr> 
				<tr>
					<td width=10>&nbsp;</td>
					<td>
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																									
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>																
								<th align="center" width="9%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Type</th>	
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
	
	$sql = " SELECT InvoiceType, Count(*) 'Inv', Sum(Duration) 'duration', SUM(OtherCharge) 'Other',
									sum(MonthlyFee) 'monthly', sum(Usage) 'usage', sum(Discount) 'discount', sum(NetAmount) 'subtotal',
									sum(VATAmount) 'vat', sum(InvoiceAmount) 'total', sum(PaidAmount) 'paid', sum(UnpaidAmount) 'unpaid'
						FROM tblLocRev
						WHERE Convert(varchar, BillEndDate, 112) ='".$cid."'";

		$sql .= "	AND GroupServiceID = ".$sid;
		$sql .= " AND PackageID in ( ".$pack." )";
		
	if ($pt <> "GRAND TOTAL")	
		$sql .= "	AND GrpPackage = '".$pid."'";
	if ($tt <> "All")	
		$sql .= "and cityid in ( ".$tid." )";
		
	$sql .= " GROUP BY InvoiceType 
						ORDER BY InvoiceType ";

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
											
			$InvoiceType = $result['InvoiceType'];										
			$itype = $result['InvoiceType'];										
			
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
			$Inv = "<a href='../report/revenue_detail_group_loc.php?sid=".$sid."&st=".$cid."&it=".$InvoiceType."&pid=".$pid."&pt=".$pt."&tt=".$tt."&tid=".$tid."&pack=".$pack."' target='_blank'>".$Inv."</a>";
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
		
		$TotalInv = "<a href='../report/revenue_detail_group_loc.php?sid=".$sid."&st=".$cid."&it=All&pid=".$pid."&pt=".$pt."&tt=".$tt."&tid=".$tid."&pack=".$pack."' target='_blank'>".$TotalInv."</a>";
		
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
												Location: <b>".$tt."</b><br>
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
						
			$retfun .= "<tr><td align=center class=formtitle><b>".$tt."</b></td></tr>";	
				$sql2 = "select Distinct GrpServiceName , GrpPackage from tblLocRev
						where Convert(varchar,BillEndDate,112) = '".$cid."'
						and GroupServiceID = '".$sid."'
						and PackageID in ( ".$where." ) ";
			if($tid <> 0)
				$sql2 .= "and CityID=".$tid;
					
				$sql2 .=" order by GrpPackage ";	

				if($que2 = $mydb->sql_query($sql2)){
					while($result2 = $mydb->sql_fetchrow($que2)){
						$GrpServiceName = $result2['GrpServiceName'];
						$GrpPackage = $result2['GrpPackage'];
							$retfun .= "<tr><td>";
							$retfun .= generateReport($cid, $GrpPackage, $tid, $ct, $GrpPackage, $tt, $GrpServiceName, $where, $sid);
							$retfun .= "</td></tr>";
					}
				}
	$retfun .= "<tr><td>";
	$retfun .= generateReport($cid, "GRAND TOTAL", $tid, $ct, "GRAND TOTAL", $tt, $GrpServiceName, $where, $sid);
	$retfun .= "</td></tr>";
	$retfun .= "</table>";
	
	print $retfun;	
	
?>