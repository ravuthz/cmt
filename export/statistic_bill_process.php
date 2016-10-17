<?php
	$filename = "statistic_bill_process_".date("Y_m_d");		
	$filename  .= '.xls';
	//$mime_type = 'text/comma-separated-values';		
	$mime_type = 'application/vnd.ms-excel';	
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$cycle = $_GET['cycle'];	
	$type = $_GET['type'];		
	$typeT = $_GET['typeT'];	
	$mArr = split("/", $cycle, 2);
	$where = $_GET['w'];
	$m = intval($mArr[0]);
	$y = intval($mArr[1]);		
	
	$month = array(1=>"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	
	$cycleT = $month[$m];
function generateReport($PackageID, $Package, $type, $cycle){
	global $mydb;
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle" colspan=2>
						<b>'.$Package.'</b>
					</td>					
				</tr> 
				<tr>
					<td width=10>&nbsp;</td>
					<td>
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																									
								<th align="center">Call type</th>	
								<th align="center" width="100">Calls</th>	
								<th align="center" width="100">Duration</th>
								<th align="center" width="100">Amount</th>											
							</thead>
							<tbody>';
	
	$sql = "SELECT r.Description, SUM(r.Calls) as 'NoCall', SUM(r.Duration) 'Minute', SUM(r.Amount) 'Money'
					FROM tblReportBillProcess r, tblSysBillRunCycleInfo c
					WHERE r.BillCycleID = c.CycleID
						AND CONVERT(VARCHAR, c.BillEndDate, 112) = '".$cycle."'					
						AND r.PackageID in (".$PackageID.")
					GROUP BY r.Description";	
	// 	AND r.InvoiceType = ".$type." 
	if($que = $mydb->sql_query($sql)){		
		$totalcall = 0;
		$totalminute = 0;
		$totalamount = 0.00;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$TarName = $result['TarName'];										
			$Description = $result['Description'];										
			$NoCall = $result['NoCall'];																
			$Minute = $result['Minute'];											
			$Money = $result['Money'];																							
				
			$totalcall += $NoCall;											
			$totalminute += $Minute;											
			$totalamount += floatval($Money);											
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" width="100">'.$Description.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" width="100">'.$NoCall.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" width="100">'.FormatHour($Minute).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" width="100">'.FormatCurrency($Money).'</td>';
			$retOut .= '</tr>';
		}		
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right">Total</td>											
											<td align="right">'.$totalcall.'</td>
											<td align="right">'.FormatHour($totalminute).'</td>
											<td align="right">'.FormatCurrency($totalamount).'</td>
										</tr>
									</tfoot>												
								</table>						
							</td>
						</tr>
					</table>';
	return $retOut;
}// end function	

	$retfun = "<table border=0 cellpadding=0 cellspacing=0 width='550'>
							<tr>
								<td align=center><b>USAGE STATISTIC OF BILL PROCESSING FOR ".$cycle."</td>
							</tr>
						";
	$sql = "SELECT PackageID, TarName FROM tblTarPackage";
	if($que = $mydb->sql_query($sql)){
		while($result = $mydb->sql_fetchrow($que)){
			$tarid = $result['PackageID'];
			$TarName = $result['TarName'];
				$retfun .= "<tr><td>";
				$retfun .= generateReport($tarid, $TarName, $type, $cycle);
				$retfun .= "</td></tr>";
		}
	}
	$retfun .= "<tr><td><br /></td></tr>";
	$retfun .= "<tr><td>";
		$retfun .= generateReport($where, "GRAND TOTAL", $type, $cycle);
	$retfun .= "</td></tr>";
	$retfun .= "</table>";
	print $retfun;	
?>