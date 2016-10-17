<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="keywords" content="BRC Technology" />
		<meta name="reply-to" content="cheavey@brc-tech.com" />
		<title>..:: Wise Biller ::..</title>
		<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />		
	</head>
	<body>
		<?php
			require_once("../common/agent.php");
			require_once("../common/functions.php");	
			
			$st=$_GET['st'];
			$et=$_GET['et'];
			$pwhere=$_GET['pwhere'];
			$bwhere=$_GET['bwhere'];
			
			
			$retOut = '
				<table border=0 cellpadding=3 cellspacing=0 width="100%">
					<tr>
						<td align=left>
							<table border=1 cellpadding=3 cellspacing=0 borderColor="#999999" style="border-collapse:collapse" width="300">
								<tr>
									<td align="left"><b>Report:</b></td>
									<td align="left">Outgoing minute report</td>
								</tr>
								<tr>
									<td align="left"><b>Start date:</b></td>
									<td align="left">'.FormatDate($st, 6).'</td>
								</tr>
								<tr>
									<td align="left"><b>End date:</b></td>
									<td align="left">'.FormatDate($et, 6).'</td>
								</tr>
								<tr>
									<td align="left"><b>Print on:</b></td>
									<td align="left">'.date("d m Y H:i:s").'</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Outgoing minutes call report from '.FormatDate($st, 3).' and '.FormatDate($et, 3).'</b>
					</td>
					<td align="right">						
					</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid #999999;">No.</th>					
								<th align="center" style="border:1px solid #999999;">Call Date</th>
								<th align="center" style="border:1px solid #999999;">Package name</th>
								<th align="center" style="border:1px solid #999999;">Band name</th>
								<th align="center" style="border:1px solid #999999;">Calls</th>
								<th align="center" style="border:1px solid #999999;">Minute calls</th>
								<th align="center" style="border:1px solid #999999;">Amount</th>
								<th align="center" style="border:1px solid #999999;">Discount</th>								
								<th align="center" style="border:1px solid #999999;">Net amount</th>
							</thead>
							<tbody>';
	$sql = "
					SELECT Convert(varchar, CallDate, 112) as 'CallDate', tar.TarName, cb.BandName, re.MinuteCall,  
							re.RecordCount, re.Discount, re.Amount
					FROM tblTarPackage tar, tlkptarChargingBand cb, tblReportOutgoingCall re
					WHERE 	re.PackageID = tar.PackageID
							AND re.DistanceBandID = cb.DistanceID
							AND Convert(varchar, CallDate, 112) BETWEEN '".FormatDate($st, 4)."' AND '".FormatDate($et, 4)."'";
							
	$sql .= " AND re.PackageID in (".$pwhere.") ";
	$sql .= " AND cb.DistanceID in (".$bwhere.") ";
		
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
										<td colspan=4 align=right style="border:1px solid #999999;">Total</td>
										<td align=right style="border:1px solid #999999;">'.$TotalRecord.'</td>
										<td align=right style="border:1px solid #999999;">'.FormatCurrency($TotalMinute, "").'</td>										
										<td align=right style="border:1px solid #999999;">'.FormatCurrency($TotalAmount / 100).'</td>
										<td align=right style="border:1px solid #999999;">'.FormatCurrency($TotalDiscount / 100).'</td>
										<td align=right style="border:1px solid #999999;">'.FormatCurrency($TotalNet / 100).'</td>
									</tr>
								</tfoot>																					
								</ttable>						
							</td>
						</tr>
					</table>
						</td>
					</tr>
				</table>';
			print $retOut;	
		?>
	</body>
</html>