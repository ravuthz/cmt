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
	$pid = $_GET['pid'];
	$tid = $_GET['tid'];	
	$ct = $_GET['ct'];
	$pt = $_GET['pt'];
	$tt = $_GET['tt'];		
	$sid = $_GET['sid'];		
	$st = $_GET['st'];	
		
function generateReport($cid, $sid, $st, $City, $tid){
	global $mydb;
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle" colspan=2>	';
	
		$retOut .= ' <b>'.$City.'</b><br> ';											
	$retOut .=	' </td>					
				</tr> 
				<tr>
					<td width=10>&nbsp;</td>
					<td>
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																									
								<th align="center" width="2%" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>																
								<th align="center" width="8%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">ServiceType</th>	
								<th align="center" width="4%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Users</th>									
								<th align="center" width="8%" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>TotalHours</th>
								<th align="center" width="8%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">ChargeHour</th>
								<th align="center" width="8%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Monthly</th>
								<th align="center" width="6%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">BoxFee</th>
								<th align="center" width="7%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">UsageFee</th>
								<th align="center" width="5%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Discount</th>
								<th align="center" width="7%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Other</th>		
								<th align="center" width="8%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Net</th>
								<th align="center" width="7%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">VAT</th>
								<th align="center" width="8%" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>Total</th>
								<th align="center" width="6%" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>Paid</th>
								<th align="center" width="8%" style="border-left:1px dotted #999999; border-top:1px solid #999999; border-right:1px solid #999999;" nowrape>Unpaid</th>											
							</thead>
							<tbody>';

	$sql = "	Select	ServiceName, 
						Count(*) 'TotalInvoice',
						sum(TotalHour) 'TotalHour',
						sum(ChargeHour) ChargeHour,
						sum(MonthlyFee) MonthlyFee,
						sum(BoxFee) BoxFee,
						sum(UsageFee) UsageFee,
						sum(Discount) Discount,
						sum(OtherCharge) OtherCharge,
						Sum(NetAmount) NetAmount,
						Sum(VatAmount) VATAmount,
						Sum(InvoiceAmount) TotalAmount,
						Sum(InvoiceAmount - CycleUnPaidAmount) 'PaidAmount',
						Sum(CycleUnPaidAmount) 'UnpaidAmount'
				from tblAllInvoiceDetail
				where ( invoiceamount > 0 or (invoiceamount = 0 and MonthlyFee > 0) or (invoiceamount = 0 and BoxFee > 0) )
				and convert(varchar,BillEndDate,112) = '".$cid."'
				and GroupServiceID = ".$sid;
				
			 If ($tid != 0)
			 {
				$sql .=  " and InvoiceType = ".$tid." ";
			 }

			If ($City != "Grand Total")
			 {
				$sql .=  " and City = '".$City."'";
				$stylebody = "one";
				$stylefoot = "sub";	
			 }
			 else
			 {
			 	$stylebody = "oneall";
				$stylefoot = "suball";	
			 }
				
	$sql .= "	group by ServiceName
				order by ServiceName
			";

	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		$SumTotalInv = 0;
		$SumTotalHour = 0;
		$SumChargeHour = 0;
		$SumMonthlyFee	= 0.00;	
		$SumBoxFee	= 0.00;
		$SumUsageFee = 0.00;
		$SumDiscount = 0.00;	
		
		$SumOtherCharge = 0.00;
		$TotalNetAmount = 0.00;
		$TotalVATAmount = 0.00;
		$GrandTotal = 0.00;
		$GrandPaid = 0.00;
		$GrandUnpaid = 0.00;	
			
		while($result = $mydb->sql_fetchrow($que)){																															
											
			$ServiceName	=	$result['ServiceName'];
												
			$TotalInvoice	= $result['TotalInvoice'];
			$SumTotalInv   += $TotalInvoice;
																			
			$TotalHour		= $result['TotalHour'];	
			$SumTotalHour  += $TotalHour;
			
			$ChargeHour		= $result['ChargeHour']; 										
			$SumChargeHour += $ChargeHour;
			
			$MonthlyFee = $result['MonthlyFee'];
			$SumMonthlyFee		+= floatval($MonthlyFee);
			
			$BoxFee = $result['BoxFee'];		
			$SumBoxFee		+= floatval($BoxFee);
			
			$UsageFee = $result['UsageFee'];		
			$SumUsageFee		+= floatval($UsageFee);
			
			$Discount = $result['Discount'];		
			$SumDiscount		+= floatval($Discount);	
			
			$OtherCharge = $result['OtherCharge'];
			$SumOtherCharge += floatval($OtherCharge);	
			
			$NetAmount = $result['NetAmount'];
			$TotalNetAmount += floatval($NetAmount);
			
			$VATAmount = $result['VATAmount'];
			$TotalVATAmount += floatval($VATAmount);
			
			$TotalAmount = $result['TotalAmount'];
			$GrandTotal += floatval($TotalAmount);
			
			$PaidAmount = $result['PaidAmount'];
			$GrandPaid += floatval($PaidAmount);
			
			$UnpaidAmount = $result['UnpaidAmount'];	
			$GrandUnpaid += floatval($UnpaidAmount);	
			
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			$LinkInv = "<a href='../report/revenue_detail_location.php?style=".$stylebody."&cid=".$cid."&st=".$st."&city=".$City."&sst=".$ServiceName."&sid=".$sid."' target='_blank'>".number_format($TotalInvoice)."</a>";
			
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;">'.$iLoop.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$ServiceName.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$LinkInv.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatSecond($TotalHour).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatSecond($ChargeHour).'</td>';			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($MonthlyFee).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($BoxFee).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($UsageFee).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($Discount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($OtherCharge).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($NetAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($VATAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($TotalAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($PaidAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999;">'.FormatCurrency($UnpaidAmount).'</td>';
			$retOut .= '</tr>';
			
		}
	}
		$mydb->sql_freeresult();
		$SumLinkInv = "<a href='../report/revenue_detail_location.php?style=".$stylefoot."&cid=".$cid."&st=".$st."&city=".$City."&sid=".$sid."&sst=All' target='_blank'>".number_format($SumTotalInv)."</a>";
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan=2 style="border-left:1px solid #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">Total</td>											
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.$SumLinkInv.'</td>	
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatSecond($SumTotalHour).'</td>	
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatSecond($SumChargeHour).'</td>											
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($SumMonthlyFee).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($SumBoxFee).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($SumUsageFee).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($SumDiscount).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($SumOtherCharge).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($TotalNetAmount).'</td>
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
											<td align='left' class='formtitle'>
												REVENUE SUMMARY<br>
												Service type: <b>".$st."</b><br>
												Invoice type: <b>".$tt."</b><br>
												Cycle date: <b>".$ct."</b><br>&nbsp;
											</td>
											<td  valign='bottom' align='right' class='formtitle'>
												Report printed on : ".date("Y-m-d")."<br>&nbsp;
											</td>
										</tr>
									</table>
								</td>
							
							</tr>
						";
		$sql = "select distinct City from tblAllInvoiceDetail 
				where convert(varchar,BillEndDate,112) = '".$cid."' and GroupService = '".$st."'
				order by City";

	if($que = $mydb->sql_query($sql)){
		while($result = $mydb->sql_fetchrow($que)){
			$City = $result['City'];
				$retfun .= "<tr><td>";
				$retfun .= generateReport($cid,$sid, $st, $City, $tid);
				$retfun .= "</td></tr>";
		}
	}
	$retfun .= "<tr><td><br /></td></tr>";
	$retfun .= "<tr><td>";
		$retfun .= $retfun .= generateReport($cid, $sid, $st, "Grand Total", $tid);
	$retfun .= "</td></tr>";
	$retfun .= "</table>";
	print $retfun;	
	
?>