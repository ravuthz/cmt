<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />



<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	

	$sid1 = $_GET['sid'];
	$sn = $_GET['st'];
	$where = $_GET['w'];
	
	
	
	
// All --------------------------
		$sql = "	Select	count(*) as InvoiceALL, sum(unpaidamount) as UnpaidAll					
					from tblAgingOpenInvoice
					where GrpSID = ".$sid1."
					and iCityID1 in (".$where.")
					and UnpaidAmount > 0
			    ";	
			
		if($que = $mydb->sql_query($sql)){		
				if($result = $mydb->sql_fetchrow()){																															
					$InvoiceALL = $result['InvoiceALL'];	
					$UnpaidAll = $result['UnpaidAll'];			
				}
			}
		$mydb->sql_freeresult();	

//0-30 day----------------------------------------	

		$sql = "	Select	count(*) as count, sum(UnpaidAmount) as sum					
					from tblAgingOpenInvoice
					where GrpSID = ".$sid1."
					and iCityID1 in (".$where.")
					and UnpaidAmount > 0
					and convert(varchar, BillEndDate, 112) > convert(varchar, dateadd(day, -30, getdate()), 112) 
				";

			if($que = $mydb->sql_query($sql)){		
		
				if($result = $mydb->sql_fetchrow()){																															
					$total30 = $result['count'];	
					$amount30 = $result['sum'];			
				}
			}
	$mydb->sql_freeresult();

//30-60 day----------------------------------------	

$sql = "	Select	count(*) as count, sum(unpaidamount) as sum					
			from tblAgingOpenInvoice
			where GrpSID = ".$sid1."
			and iCityID1 in (".$where.")
			and UnpaidAmount > 0
			and convert(varchar, BillEndDate, 112) <= convert(varchar, dateadd(day, -30, getdate()), 112) 
			and convert(varchar, BillEndDate, 112) > convert(varchar, dateadd(day, -60, getdate()), 112) 
		";


			if($que = $mydb->sql_query($sql)){		
		
				if($result = $mydb->sql_fetchrow()){																															
					$total60 = $result['count'];	
					$amount60 = $result['sum'];				
				}
			}
	$mydb->sql_freeresult();
	
//60-90 day----------------------------------------	

$sql = "	Select	count(*) as count, sum(unpaidamount) as sum					
			from tblAgingOpenInvoice
			where GrpSID = ".$sid1."
			and iCityID1 in (".$where.")
			and UnpaidAmount > 0
			and convert(varchar, BillEndDate, 112) <= convert(varchar, dateadd(day, -60, getdate()), 112) 
			and convert(varchar, BillEndDate, 112) > convert(varchar, dateadd(day, -90, getdate()), 112) 
		";


					
			if($que = $mydb->sql_query($sql)){		
		
				if($result = $mydb->sql_fetchrow()){																															
					$total90 = $result['count'];	
					$amount90 = $result['sum'];				
				}
			}
	$mydb->sql_freeresult();
	
	//90-120 day----------------------------------------	
$sql = "	Select	count(*) as count, sum(unpaidamount) as sum					
			from tblAgingOpenInvoice
						where GrpSID = ".$sid1."
						and iCityID1 in (".$where.")
						and UnpaidAmount > 0
			and convert(varchar, BillEndDate, 112) < convert(varchar, dateadd(day, -90, getdate()), 112) 
			and convert(varchar, BillEndDate, 112) >= convert(varchar, dateadd(day, -120, getdate()), 112) 
		";


		
							
			if($que = $mydb->sql_query($sql)){		
		
				if($result = $mydb->sql_fetchrow()){																															
					$total120 = $result['count'];	
					$amount120 = $result['sum'];				
				}
			}
	$mydb->sql_freeresult();
	
	//120 day----------------------------------------

$sql = "	Select	count(*) as count, sum(unpaidamount) as sum					
			from tblAgingOpenInvoice
			where GrpSID = ".$sid1."
			and iCityID1 in (".$where.")
			and UnpaidAmount > 0
			and convert(varchar, BillEndDate, 112) < convert(varchar, dateadd(day, -120, getdate()), 112) 
			";


				
			if($que = $mydb->sql_query($sql)){		
				if($result = $mydb->sql_fetchrow()){																															
					$totalold = $result['count'];	
					$amountold = $result['sum'];				
				}
			}
	$mydb->sql_freeresult();
	if(intval($InvoiceALL) >0)
		$InvoiceAll = "<a href='../report/pg274.php?sid=".$sid1."&where=".$where."&id=0' target='_blank'>".number_format($InvoiceALL)."</a>";	
	else
		$InvoiceAll = 0;
	if(intval($total30) >0)
		$Invoice30 = "<a href='../report/pg274.php?sid=".$sid1."&where=".$where."&id=1' target='_blank'>".number_format($total30)."</a>";	
	else
		$Invoice30 = 0;
	if(intval($total60) >0)
		$Invoice60 = "<a href='../report/pg274.php?sid=".$sid1."&where=".$where."&id=2' target='_blank'>".number_format($total60)."</a>";	
	else
		$Invoice60 = 0;
	if(intval($total90) >0)
		$Invoice90 = "<a href='../report/pg274.php?sid=".$sid1."&where=".$where."&id=3' target='_blank'>".number_format($total90)."</a>";	
	else
		$Invoice90 = 0;
	if(intval($total120) >0)
		$Invoice120 = "<a href='../report/pg274.php?sid=".$sid1."&where=".$where."&id=4' target='_blank'>".number_format($total120)."</a>";	
	else
		$Invoice120 = 0;
	if(intval($totalold) >0)
		$Invoice1Old = "<a href='../report/pg274.php?sid=".$sid1."&where=".$where."&id=5' target='_blank'>".number_format($totalold)."</a>";	
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
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Date Of Bill</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Invoice Quantity</th>	
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Total Amount</th>
								
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Paid Amount</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">% Paid</th>
								
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Unpaid Amount</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999; border-right:1px solid #999999;">% Unpaid</th>
							</thead>';
							
		$sql = "Select	CONVERT(varchar,BillEndDate,102) 'Date',

						count(InvoiceID) AS 'Totalinvoice', 
						Round(sum(InvoiceAMount),2) as 'TotalAmount',
						
						count(InvoiceID) - IsNull(Sum(Case when Round(UnpaidAmount,2) > 0 then 1 end),0) 'TotalPaidInvoice',		
						Round(sum(PaidAmount),2) as 'TotalPaidAmount',
						
						IsNull(Sum(Case when Round(UnpaidAmount,2) > 0 then 1 end),0) 'TotalUnpaidInvoice',		
						Round(sum(UnpaidAmount),2) as 'TotalUnpaidAmount' 
					
				from tblAgingOpenInvoice
						where GrpSID = ".$sid1."
						and iCityID1 in (".$where.")
						and IssueDate is not null
				Group by CONVERT(varchar,BillEndDate,102)
				order by Date desc

				";
		
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
			$TotalInvoice =	$result['Totalinvoice'];
			$TotalAmount =	$result['TotalAmount'];
			$totalunpaid =	$result['TotalUnpaidInvoice'];
			$unpaidamount =	$result['TotalUnpaidAmount'];
			$TotalPaid = $result['TotalPaidInvoice'];
			$AmountPaid = $result['TotalPaidAmount'];
			
			$sumpaidamount += $AmountPaid;
			$sumpaidinvoice += $TotalPaid;
			$suminvoice += $TotalInvoice;
			$sumamount += $TotalAmount;
			$sumunpaidinvoice += $totalunpaid;
			$sumunpaidamount += $unpaidamount;
			
			$AmountLink = "<a href='../report/pg273.php?code=".$Date."&sid=".$sid."&where=".$where."&k=a' target='_blank'>".FormatCurrency($TotalAmount)."</a>";
			$AmountPaidLink = "<a href='../report/pg273.php?code=".$Date."&sid=".$sid."&where=".$where."&k=p' target='_blank'>".FormatCurrency($AmountPaid)."</a>";
			
			$pcPaid = number_format(($AmountPaid * 100) / $TotalAmount,2).'%';
					
			$unPaidInvLink = "<a href='../report/pg273.php?code=".$Date."&sid=".$sid."&where=".$where."&k=u' target='_blank'>".FormatCurrency($unpaidamount)."</a>";
			
			$pcUnPaid = number_format(($unpaidamount * 100) / $TotalAmount,2).'%';
			
			$iLoop++;															
			$no++;
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOutMon .= '<tr>';																			
			$retOutMon .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px dotted #999999;">'.$no.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Date.'</td>';																								
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.number_format($TotalInvoice).'</td>';
			
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($TotalAmount).'</td>';
			
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$AmountPaidLink.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$pcPaid.'</td>';
			
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$unPaidInvLink.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999;">'.$pcUnPaid.'</td>';
			$retOutMon .= '</tr>';
		}		
		$retOutMon .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan="2" style="border:1px solid #999999;">Total:&nbsp;</td>
											<td align="right" style="border:1px solid #999999;">'.number_format($suminvoice).'</td>
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumamount).'</td>
											
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumpaidamount).'</td>
											<td align="right" style="border:1px solid #999999;">'.number_format(($sumpaidamount*100)/$sumamount,2).'%</td>
											
											<td align="right" style="border:1px solid #999999;">'.FormatCurrency($sumunpaidamount).'</td>
											<td align="right" style="border:1px solid #999999;">'.number_format(($sumunpaidamount*100)/$sumamount,2).'%</td>
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
