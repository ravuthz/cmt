<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$cid = $_GET['cid'];	
	$aid = $_GET['aid'];
	$st = $_GET['st'];
	$et = $_GET['et'];
	$tel = $_GET['tel'];
	$loc = $_GET['loc'];
	$idd = $_GET['idd'];
	$ldd = $_GET['ldd'];
	$mob = $_GET['mob'];
	$unk = $_GET['unk'];
	
	if(datediff($st, $et) > 31){
		print "<font color='red'>The gaps from start time and end time must be less than 31days</font>";
	}elseif(datediff($st, $et) < 0){
		print "<font color='red'>The gaps from start time and end time must be less than 31day</font>".datediff($et, $st);
	}else{
		$st .= " 00:00:00";
		$et .= " 23:59:59";
		print '<table border="1" cellpadding="3" cellspacing="0" width="100%" id="2" class="sortable" bordercolor="#aaaaaa">
							<thead>
								<th>No.</th>
								<th>Called time</th>
								<th>Called type</th>
								<th>Dial number</th>
								<th>Destination</th>													
								<th>Duration</th>													
								<th>Amount</th>													
							</thead>
							<tbody>';
						$sql = "SELECT f.ConnectedNumber, Convert(varchar, dbo.PickToTime(f.CallEndTime), 120) as 'EndTime', f.RndDuration, f.Amount,
													t.TypeShortcut,  a.Description
										FROM tblcallsbillable f, tlkpTarCallType t, tblTarAreaCode a																				
										WHERE f.ServiceType = t.CallID	AND f.AreaCodeID = a.AreaCodeID
											AND CallerNumber ='".$tel."' 
											AND Convert(varchar, dbo.PickToTime(CallEndTime),112) BETWEEN '".FormatDate($st, 4)."' AND '".FormatDate($et, 4)."'";				
					$sql .= " AND f.ServiceType IN(-1 ";								
					if($loc == 1)
						$sql .= ", 0, 1, 14 ";
					if($idd == 1)
						$sql .= ", 4 ";
					if($ldd == 1)
						$sql .= ", 3 ";
					if($mob == 1)
						$sql .= ", 2 ";
					if($unk == 1)
						$sql .= ", 5 ";							
			$sql .= ") ORDER BY f.CallEndTime, f.ConnectedNumber, f.amount desc";
		$intLoop = 1;
		$totalMin = 0;
		$totalAmout = 0.00;
		if($que = $mydb->sql_query($sql)){
			while($result = $mydb->sql_fetchrow($que)){
				$ConnectedNumber = $result['ConnectedNumber'];
				$CallEndTime = $result['EndTime'];
				$Duration = $result['RndDuration'];
				$totalMin += intval($Duration);
				$Amount = $result['Amount'];
				$totalAmout += floatval($Amount);
				$TypeShortcut = $result['TypeShortcut'];
				$Description = $result['Description'];
				if(($intLoop % 2) == 0)
					$style = "row1";
				else
				$style = "row2";
				
				print "<tr>";	
				print '<td class="'.$style.'" align="right">'.$intLoop.'</td>';
				print '<td class="'.$style.'" align="left">'.formatDate($CallEndTime, 7).'</td>';
				print '<td class="'.$style.'" align="left">'.$TypeShortcut.'</td>';
				print '<td class="'.$style.'" align="left">'.$ConnectedNumber.'</td>';
				print '<td class="'.$style.'" align="left">'.$Description.'</td>';
				print '<td class="'.$style.'" align="left">'.FormatHour($Duration).'</td>';
				print '<td class="'.$style.'" align="left">'.FormatCurrency($Amount, "$", 3, 4).'</td>';	
				print "</tr>";
				
				$intLoop ++;
			}				
		}		
		print '</tbody>';
		print "<tfoot>";	
		
		print '<td align="right" colspan=5><b>Total</b></td>';				
		print '<td align="left"><b>'.FormatHour($totalMin).'</b></td>';
		print '<td align="left"><b>'.FormatCurrency($totalAmout, "$", 3).'</b</td>';	

		print "</tfoot>";
		print '</table>';
		
		$mydb->sql_freeresult();					
	}
	
	
		
?>