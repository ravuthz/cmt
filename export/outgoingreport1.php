<?php	
	$sm=$_GET['sm'];
	$sy=$_GET['sy'];
	$em=$_GET['em'];
	$ey=$_GET['ey'];
	$pid=$_GET['pid'];
	$did=$_GET['did'];
	
	$start = $sm.$sy;
	$end = $em.$ey;
	
	$month = array(1=>"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	
	$filename = "outgoing_report_monthly".$start."_".$end;		
	$filename  .= '.xls';		
	$mime_type = 'application/vnd.ms-excel';	
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="' . $filename . '"');		
	
	$month = array(1=>"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	
	require_once("../common/agent.php");
	require_once("../common/functions.php");
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Outgoing minutes call report from '.$month[$sm].'/'.$sy.' and '.$month[$sy].'/'.$ey.'</b>
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
					SELECT DatePart(month, CallDate) as 'm', DatePart(year, CallDate) as 'y', tar.TarName, cb.BandName, 
								SUM(RecordCount) 'record', SUM(re.MinuteCall) as 'Minute', SUM(re.Discount) as 'Discount', SUM(re.Amount) as 'Money'
					FROM tblTarPackage tar, tlkptarChargingBand cb, tblReportOutgoingCall re
					WHERE 	re.PackageID = tar.PackageID
							AND re.DistanceBandID = cb.DistanceID
							AND convert(varchar, DatePart(month, CallDate)) + convert(varchar, DatePart(year, CallDate))
								 BETWEEN ".$start." AND ".$end;
	if($pid > 0)
		$sql .= " AND re.PackageID = ".$pid;
	if($did > 0)
		$sql .= " AND cb.DistanceID = ".$did;
		
	$sql .= " GROUP BY DatePart(month, CallDate), DatePart(year, CallDate), tar.TarName, cb.BandName
						ORDER BY DatePart(year, CallDate), DatePart(month, CallDate), tar.TarName, cb.BandName";	
							
	if($que = $mydb->sql_query($sql)){		

		$n = 0;
		$iLoop = 0;
		
		$TotalMinute = 0.00;
		$TotalDiscount = 0.00;
		$TotalAmount = 0.00;
		$TotalNet = 0.00;
		$TotalRecord = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$m = $result['m'];
			$y = $result['y'];
			$PackageName = $result['TarName'];										
			$BandName = $result['BandName'];
			$record =intval( $result['record']);
			$MinuteCall =intval( $result['Minute']);
			$MinuteDiscount = floatval($result['Discount']);
			$Amount = floatval($result['Money']);			
			$NetAmount = $Amount - $MinuteDiscount;
			$TotalMinute += $MinuteCall;
			$TotalDiscount += $MinuteDiscount;
			$TotalAmount += $Amount;	
			$TotalNet += $NetAmount;		
			$TotalRecord += $record;		
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																						
			$retOut .= '<td class="'.$style.'" align="center">'.$iLoop.'</td>';																																														
			$retOut .= '<td class="'.$style.'" align="left">'.$month[$m].'/'.$y.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$PackageName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$BandName.'</td>';			
			$retOut .= '<td class="'.$style.'" align="right">'.$record.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($MinuteCall, "").'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Amount / 100).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($MinuteDiscount / 100).'</td>';	
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
								</table>						
							</td>
						</tr>
					</table>';
	print $retOut;
?>