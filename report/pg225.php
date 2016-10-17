<?php
	require_once("../common/agent.php");	
	require_once("../common/functions.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	$month = array(1=>"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	$k = $_GET['k'];
	$sid = FixQuotes($sid);
	$code = FixQuotes($code);
	$m = split("-", $code);
?>
<link href="../style/mystyle.css" rel="stylesheet" type="text/css" />

<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Invoice status on cycle: <?php print $code; ?></b><br />
						Print on: <?php print date("Y M d H:i:s"); ?>
					</td>
					<td align="right">
					[<a href="../export/pg225.php?sid=<?php print $sid;?>&code=<?php print $code; ?>&type=csv">Export</a>]	
					</td>
				</tr> 
				<tr>
					<td colspan="2">
	<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa" bgColor="#ffffff">
							<thead>																	
								<th align="center" style="border:1px solid #999999">No</th>
								<th align="center" style="border:1px solid #999999">VIP</th>
								<th align="center" style="border:1px solid #999999">Package</th>								
								<th align="center" style="border:1px solid #999999">InvoiceID</th>
								<th align="center" style="border:1px solid #999999">AccID</th>
								<th align="center" style="border:1px solid #999999">UserName</th>
								<th align="center" style="border:1px solid #999999">SubscriptionName</th>
								<th align="center" style="border:1px solid #999999">Status</th>								
								<th align="center" style="border:1px solid #999999">IssueDate</th>
								<th align="center" style="border:1px solid #999999">Net</th>
								<th align="center" style="border:1px solid #999999">VAT</th>
								<th align="center" style="border:1px solid #999999">Total</th>
								<th align="center" style="border:1px solid #999999">Paid</th>
								<th align="center" style="border:1px solid #999999">Unpaid</th>																																																
							</thead>
<?php		
		
		$sql = "SELECT ci.InvoiceID, Convert(varchar,ci.IssueDate,102) IssueDate, ci.InvoiceAmount, ci.NetAmount, ci.VATAmount, ci.UnpaidAmount, cp.CustID, cp.AccID,
		 								cp.UserName, left(cp.SubscriptionName,15) SubscriptionName, cp.StatusID,IsNull(ex.Remark,'CUS') Remark,left(tp.TarName,7) TarName
						FROM tblCustomerInvoice ci(nolock) 
						join tblCustProduct cp(nolock) on ci.AccID = cp.AccID
						join (Select * from tblSysBillRunCycleInfo Union Select * from tblDumBillRunCycleInfo) sb on sb.CycleID = ci.BillingCycleID
						join tblTarPackage tp(nolock) on tp.PackageID = sb.PackageID
						left join (select Distinct AccID,'VIP' Remark from dbo.tblExceptiondetail where Remark = 'Exception') ex on cp.AccID=ex.AccID
						WHERE (InvoiceType in (2,3) or ( InvoiceType = 1 and ci.InvoiceID in (select InvoiceID from tblLocRev)))
						and tp.ServiceID in (select ServiceID from tlkpService where GroupServiceID = ".$sid.")
						and convert(varchar, sb.BillEndDate, 102) = '".$code."'
						and ci.TrackID in (select TrackID from tblTrackAccount where iCityID1 in (".$where."))		
						";
		if($k == "p")
			$sql .= "and Round(ci.UnpaidAmount,2) <> Round(ci.InvoiceAmount,2)";
		else if($k == "u")
			$sql .= "and Round(ci.UnpaidAmount,2) > 0 ";

		$sql .= " ORDER BY ex.Remark,ci.UnpaidAmount desc ";


	$totalinvoice = 0.00;
	$totalnetamount= 0.00;
	$totalvat = 0.00;
	$totalunpaid = 0.00;
	$totalpaid = 0.00;

	if($que = $mydb->sql_query($sql)){			
		print "<tbody>";
		while($result = $mydb->sql_fetchrow()){
																																	
			$InvoiceID = $result['InvoiceID'];										
			$IssueDate =	$result['IssueDate'];
			$InvoiceAmount =	$result['InvoiceAmount'];
			$NetAmount =	$result['NetAmount'];
			$VATAmount =	$result['VATAmount'];
			$UnpaidAmount =	$result['UnpaidAmount'];
			$PaidAmount = floatval($InvoiceAmount) - floatval($UnpaidAmount);
			$CustID =	$result['CustID'];
			$AccID =	$result['AccID'];
			$UserName =	$result['UserName'];
			$SubscriptionName =	$result['SubscriptionName'];
			$StatusID = $result['StatusID'];
			$TarName = $result['TarName'];
			$Remark = $result['Remark'];
			$totalinvoice += floatval($InvoiceAmount);
			$totalnetamount += floatval($NetAmount);
			$totalvat += floatval($VATAmount);
			$totalunpaid += floatval($UnpaidAmount);
			$totalpaid += floatval($PaidAmount);
			$linkInv = "<a href='../finance/screeninvoice.php?CustomerID=".$CustID."&InvoiceID=".$InvoiceID."' target='_blank'>".$InvoiceID."</a>";
			$linkAcct = "<a href='../?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91' target='_blank'>".$UserName."</a>";
			switch($StatusID){
				case 0:
					$stbg = $bgUnactivate;
					$stfg = $foreUnactivate;
					$stwd = "Inactive";
					break;
				case 1:
					$stbg = $bgActivate;
					$stfg = $foreActivate;
					$stwd = "Active";
					break;
				case 2:
					$stbg = $bgLock;
					$stfg = $foreLock;
					$stwd = "Barred";
					break;
				case 3:
					$stbg = $bgClose;
					$stfg = $foreClose;
					$stwd = "Closed";
					break;
				case 4:
					$stbg = $bgClose;
					$stfg = $foreClose;
					$stwd = "Closed";
					break;
			}								
			$iLoop++;															
			$no++;
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOutMon .= '<tr>';																			
			$retOutMon .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px dotted #999999; ">'.$no.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.$Remark.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.$TarName.'</td>';	
			$retOutMon .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.$linkInv.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.$AccID.'</td>';																								
			$retOutMon .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.$linkAcct.'</td>';
			$retOutMon .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.substr($SubscriptionName, 0, 30).'</td>';
			$retOutMon .= '<td align="center" bgcolor="'.$stbg.'" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">
											<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
										 </td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.$IssueDate.'</td>';			
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.FormatCurrency($NetAmount).'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.FormatCurrency($VATAmount).'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; ">'.FormatCurrency($InvoiceAmount).'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999">'.FormatCurrency($PaidAmount).'</td>';
			$retOutMon .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999">'.FormatCurrency($UnpaidAmount).'</td>';
			$retOutMon .= '</tr>';
		}		
		$retOutMon .= '</tbody>
									  <tfoot>
											<tr>
												<td align=left colspan=9 style="border:1px solid #999999">Total</td>												
												<td align=right style="border:1px solid #999999">'.FormatCurrency($totalnetamount).'</td>
												<td align=right style="border:1px solid #999999">'.FormatCurrency($totalvat).'</td>
												<td align=right style="border:1px solid #999999">'.FormatCurrency($totalinvoice).'</td>
												<td align=right style="border:1px solid #999999">'.FormatCurrency($totalpaid).'</td>	
												<td align=right style="border:1px solid #999999">'.FormatCurrency($totalunpaid).'</td>	
											</tr>
										</tfoot>
									';
		
		}
	$mydb->sql_freeresult();		
	print $retOutMon;
?>	
</table>
