<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");		
	$cid = $_GET['cid'];
	$pid = $_GET['pid'];
	$tid = $_GET['tid'];	
	$ct = $_GET['ct'];
	$pt = $_GET['pt'];
	$tt = $_GET['tt'];		
	
	$retOut = '
		<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
		<tr>
			<td>
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>'.strtoupper($tt).' REPORT FOR ['.strtoupper($pt).'] for '.$ct.'</b>
					</td>					
				</tr> 
				<tr>
					<td>
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">Package</th>
								<th align="center">Invoice</th>
								<th align="center">Issue date</th>	
								<th align="center">Telephone</th>	
								<th align="center">Subscription name</th>
								<th align="center">Account</th>
								<th align="center">Duration</th>
								<th align="center">Monthly fee</th>
								<th align="center">charge</th>
								<th align="center">Discount</th>
								<th align="center">Sub total</th>
								<th align="center">VAT</th>
								<th align="center">Total amount</th>																																			
							</thead>
							<tbody>';
	
	$sql = " SELECT TarName, InvoiceID, IssueDate, UserName, SubscriptionName, AccID, Duration, Charge, Discount,
										MonthlyFee, Subtotal, VATAmount, TotalAmount
						FROM tblReportBillDetail
						WHERE InvoiceType = ".$tid." AND
									Convert(varchar, CycleDate, 112) ='".$cid."'
									
					
				";
	if(intval($pid) >0)
		$sql .= "	AND PackageID = ".$pid;
	$sql .= " ORDER BY TarName, UserName";
	
	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		$totalMonthlyFee = 0.00;
		$TotalDuration = 0;
		$TotalCharge = 0.00;
		$TotalDiscount = 0.00;
		$TotalSubtotal = 0.00;
		$TotalVATAmount = 0.00;
		$GrandTotal = 0.00;		
		while($result = $mydb->sql_fetchrow($que)){																															
			$TarName = $result['TarName'];
			$InvoiceID = $result['InvoiceID'];										
			$IssueDate = $result['IssueDate'];										
			$UserName = $result['UserName'];																
			$SubscriptionName = $result['SubscriptionName'];											
			$AccID = $result['AccID'];											
			$Duration = $result['Duration'];
			$TotalDuration += intval($Duration);
			$MonthlyFee = $result['MonthlyFee'];
			$totalMonthlyFee += floatval($MonthlyFee);
			$Charge = $result['Charge'];
			$TotalCharge += floatval($Charge);
			$Discount = $result['Discount'];
			$TotalDiscount += floatval($Discount);
			$Subtotal = $result['Subtotal'];
			$TotalSubtotal += floatval($Subtotal);
			$VATAmount = $result['VATAmount'];
			$TotalVATAmount += floatval($VATAmount);
			$TotalAmount = $result['TotalAmount'];	
			$GrandTotal += floatval($TotalAmount);		
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';																									
			$retOut .= '<td class="'.$style.'" align="left">'.$InvoiceID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.FormatDate($IssueDate, 2).'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$UserName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$AccID.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatHour($Duration).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($MonthlyFee).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Charge).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Discount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Subtotal).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($VATAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($TotalAmount).'</td>';
			$retOut .= '</tr>';
		}		
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>	
								<tfoot>
									<tr>
										<td align="right" colspan="7">Total</td>
										<td align="right">'.FormatHour($TotalDuration).'</td>
										<td align="right">'.FormatCurrency($totalMonthlyFee).'</td>
										<td align="right">'.FormatCurrency($TotalCharge).'</td>
										<td align="right">'.FormatCurrency($TotalDiscount).'</td>
										<td align="right">'.FormatCurrency($TotalSubtotal).'</td>
										<td align="right">'.FormatCurrency($TotalVATAmount).'</td>
										<td align="right">'.FormatCurrency($GrandTotal).'</td>
									</tr>
								</tfoot>																								
								</table>						
							</td>
						</tr>
					</table>
				</td>
			</tr>				
		</table>';
		//<td align="right">'.FormatCurrency(($TotalSubtotal + abs($TotalDiscount)) - $TotalCharge).'</td>
	print $retOut;	
?>