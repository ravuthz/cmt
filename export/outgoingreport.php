<?php	
	$st = $_GET['st'];
	$et = $_GET['et'];
	$pid = $_GET['pid'];
	$did = $_GET['did'];
	
	$filename = "outgoing_report_".$st."_".$et;		
	$filename  .= '.xls';		
	$mime_type = 'application/vnd.ms-excel';	
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="' . $filename . '"');		
	
	require_once("../common/agent.php");
	require_once("../common/functions.php");
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Outgoing minutes call report from '.FormatDate($st, 3).' and '.FormatDate($et, 3).'</b>
					</td>
					<td align="right">						
					</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No.</th>					
								<th align="center">Call Date</th>
								<th align="center">Package name</th>
								<th align="center">Band name</th>
								<th align="center">Calls</th>
								<th align="center">Minute calls</th>
								<th align="center">Amount</th>
								<th align="center">Discount</th>								
								<th align="center">Net amount</th>
							</thead>
							<tbody>';
	$sql = "
					SELECT Convert(varchar, CallDate, 112) as 'CallDate', tar.TarName, cb.BandName, re.MinuteCall,  
							re.RecordCount, re.Discount, re.Amount
					FROM tblTarPackage tar, tlkptarChargingBand cb, tblReportOutgoingCall re
					WHERE 	re.PackageID = tar.PackageID
							AND re.DistanceBandID = cb.DistanceID
							AND Convert(varchar, CallDate, 112) BETWEEN '".FormatDate($st, 4)."' AND '".FormatDate($et, 4)."'";
	if($pid > 0)
		$sql .= " AND re.PackageID = ".$pid;
	if($did > 0)
		$sql .= " AND cb.DistanceID = ".$did;
		
	$sql .= " ORDER BY Convert(varchar, CallDate, 112), tar.TarName, cb.BandName";	
							
	if($que = $mydb->sql_query($sql)){		

		$n = 0;
		$iLoop = 0;
		
		$TotalMinute = 0.00;
		$TotalDiscount = 0.00;
		$TotalAmount = 0.00;
		while($result = $mydb->sql_fetchrow()){																															
			$CallDate = $result['CallDate'];
			$PackageName = $result['TarName'];										
			$BandName = $result['BandName'];
			$RecordCount =intval( $result['RecordCount']);
			$MinuteCall =intval( $result['MinuteCall']);
			$Discount = intval($result['Discount']);
			$Amount = floatval($result['Amount']);
			$NetAmount = $Amount - $Discount;			
			
			$TotalMinute += $MinuteCall;
			$TotalDiscount += $Discount;
			$TotalAmount += $Amount;
			$TotalNet += $NetAmount;
			$TotalRecord += $RecordCount;			
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																						
			$retOut .= '<td class="'.$style.'" align="center">'.$iLoop.'</td>';																																														
			$retOut .= '<td class="'.$style.'" align="left">'.FormatDate($CallDate, 3).'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$PackageName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$BandName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.number_format($RecordCount, 0).'</td>';						
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($MinuteCall, "").'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Amount / 100).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Discount / 100).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($NetAmount / 100).'</td>';
			$retOut .= '</tr>';									
		}		
	}else{
		$error = $mydb->sql_error();
		print $error['message'];
	}
	$mydb->sql_freeresult();
		
		$retOut .= '</tbody>
								<tfoot>																
									<tr>
										<td colspan=4 align=right>Total</td>
										<td align=right>'.$TotalRecord.'</td>
										<td align=right>'.FormatCurrency($TotalMinute, "").'</td>										
										<td align=right>'.FormatCurrency($TotalAmount / 100).'</td>
										<td align=right>'.FormatCurrency($TotalDiscount / 100).'</td>
										<td align=right>'.FormatCurrency($TotalNet / 100).'</td>
									</tr>
								</tfoot>																					
								</ttable>						
							</td>
						</tr>
					</table>';
	print $retOut;
?>