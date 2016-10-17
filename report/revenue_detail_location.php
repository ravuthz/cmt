<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="keywords" content="BRC Technology" />
		<meta name="reply-to" content="cheavey@brc-tech.com" />
		<title>..:: Wise Biller ::..</title>
		<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
		<script language="JavaScript" src="../javascript/ajax_gettransaction.js"></script>		
		<style>
			td{
					font-size:10px;
				}
			th{
				font-size:10px;
			}
		</style>
	</head>
	<body>
		<?php
			require_once("../common/agent.php");
			require_once("../common/functions.php");	
			$st=$_GET['st'];
			$cid=$_GET['cid'];
			$City=$_GET['city'];
			$sst=$_GET['sst'];
			$style=$_GET['style'];
			$sid=$_GET['sid'];
			
			$retOut = '				
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						REVENUE SUMMARY<br />
						Location: '.$City.'<br />
						Cycle date: '.FormatDate($cid, 3).'<br />
						Service Name: '.$sst.'
					</td>
					<td valign="bottom" align="right" class="formtitle">
					Report printed on : '.date("Y-m-d H:i:s").'						
					</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="2" cellspacing="0" align="center" width="100%" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border-left:1px solid #000000; border-top:1px solid #000000;" width="3%">No.</th>					
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" width="6%">CustID</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" width="6%">AccountID</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" width="12%">SubscriptionName</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" width="5%">TelephoneLine</th>								
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" width="6%">InvoiceID</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >TotalHour</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >ChargeHour</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >MonthlyFee</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >BoxFee</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >UsageFee</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >Discount</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >Other</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >Net</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >VAT</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >Total</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000;" >Paid</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000; border-right:1px solid #000000;"  >Unpaid</th>								
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #000000; border-right:1px solid #000000;"  >Status</th>								
							</thead>
							<tbody>';
	$sql = " select CustID, AccID, SubscriptionName, Telephone, InvoiceID, TotalHour, ChargeHour, 
			MonthlyFee, BoxFee, UsageFee, Discount, OtherCharge, NetAmount, VatAmount,InvoiceAmount, 
			InvoiceAmount - CycleUnpaidAmount 'Paid', CycleUnpaidAmount,ServiceID, ServiceName,InvoiceType
			from  tblAllInvoiceDetail
			where (invoiceamount > 0 or (Invoiceamount=0 and MonthlyFee > 0) or (Invoiceamount=0 and BoxFee > 0))
			and convert(varchar,BillEndDate,112) = '".$cid."'
			and GroupServiceID = ".$sid;

if ($style == "one" || $style == "sub")
	$sql .=	" and City = '".$City."'";

if ($style == "one" || $style == "oneall")	
	$sql .= " and ServiceName = '".$sst."'";			


	$sql .= " Order by ServiceID, InvoiceID desc,SubscriptionName";

	if($que = $mydb->sql_query($sql)){		

		$n = 0;
		$iLoop = 0;
		
		$SumTotalHour = 0.00;
		$SumChargeHour = 0.00;
		$TotalMonthly = 0.00;
		$TotalBoxFee = 0.00;
		$TotalUsage = 0.00;
		$TotalDiscount = 0.00;
		$TotalOther = 0.00;
		$TotalNetAmount = 0.00;
		$TotalVATAmount = 0.00;
		$TotalInvoiceAmount = 0.00;
		$TotalPaid = 0.00;
		$TotalUnpaid = 0.00;
		$TagSID = 0;
		$TagSNAME = "";
		
		while($result = $mydb->sql_fetchrow()){																															
			$InvoiceID = $result['InvoiceID'];
			$CustID = $result['CustID'];
			$AccID = $result['AccID'];
			$SubscriptionName = $result['SubscriptionName'];
			$Telephone = $result['Telephone'];										
			$TotalHour = $result['TotalHour'];
			$ChargeHour = $result['ChargeHour'];
			$MonthlyFee = $result['MonthlyFee'];
			$BoxFee = $result['BoxFee'];
			$UsageFee = $result['UsageFee'];
			$Discount = $result['Discount'];
			$OtherCharge = $result['OtherCharge'];
			$NetAmount = $result['NetAmount'];
			$VatAmount = $result['VatAmount'];
			$InvoiceAmount = $result['InvoiceAmount'];
			$Paid = $result['Paid'];			
			$CycleUnpaidAmount = $result['CycleUnpaidAmount'];			
			$InvoiceType = $result['InvoiceType'];
			$ServiceID = $result['ServiceID'];			
			$ServiceName = $result['ServiceName'];
			
			if(($TagSID == 0 || $TagSNAME == "") || $TagSID != $InvoiceType || $TagSNAME != $ServiceName)
			{
				$Sta = $ServiceName;
				
				if($InvoiceType == 1)
				{
					$Sta .= " Cycle Bill";
				}else if($InvoiceType == 2)
				{
					$Sta .= " Demand Bill";
				}else if($InvoiceType == 3)
				{
					$Sta .= " Other Bill";
				}
				$TagSID = $InvoiceType;
				$TagSNAME = $ServiceName;
			}
			else
			{
				$Sta = "";
			}
			
			$SumTotalHour  += $TotalHour;
			$SumChargeHour += $ChargeHour;
			$TotalMonthly  += $MonthlyFee;
			$TotalBoxFee   += $BoxFee;
			$TotalUsage    += $UsageFee;
			$TotalDiscount += $Discount;
			$TotalOther    += $OtherCharge;
			$TotalNetAmount += $NetAmount;
			$TotalVATAmount += $VatAmount;
			$TotalInvoiceAmount += $InvoiceAmount;
			$TotalPaid     += $Paid;
			$TotalUnpaid   += $CycleUnpaidAmount;
			
			//$InvoiceID = "<a href='javascript:showReport(".$InvoiceID.");'>".$InvoiceID."</a>";	
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																						
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #000000; border-top:1px dotted #999999;">'.$iLoop.'</td>';																																														
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$CustID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$AccID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;" nowrape="nowrape">'.substr($SubscriptionName, 0, 18).'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Telephone.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$InvoiceID.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatSecond($TotalHour).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatSecond($ChargeHour).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($MonthlyFee).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($BoxFee).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($UsageFee).'</td>';			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($Discount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($OtherCharge).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($NetAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($VatAmount).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($InvoiceAmount).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($Paid).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($CycleUnpaidAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #000000;">'.$Sta.'</td>';
			$retOut .= '</tr>';									
		}		
	}else{
		$error = $mydb->sql_error();
		print $error['message'];
	}
	$mydb->sql_freeresult();
		
		$retOut .= '</tbody>
									<tfoot bgcolor="#cccccc">															
									<tr>
										<td colspan=6 align=right style="border-left:1px solid #000000; border-top:1px solid #000000; border-bottom:1px solid #000000;">Total</td>										
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatSecond($SumTotalHour).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatSecond($SumChargeHour).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalMonthly).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalBoxFee).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalUsage).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalDiscount).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalOther).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalNetAmount).'</td>										
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalVATAmount).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalInvoiceAmount).'</td>
										
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000;">'.FormatCurrency($TotalPaid).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000; border-right:1px solid #000000;">'.FormatCurrency($TotalUnpaid).'</td>
										<td align=right style="border-left:1px dotted #999999; border-top:1px solid #000000; border-bottom:1px solid #000000; border-right:1px solid #000000;"></td>
									</tr>
								</tfoot>																																			
							</td>
						</tr>
					</table>
				';
			print $retOut;	
			
		?>
		
	</body>
</html>