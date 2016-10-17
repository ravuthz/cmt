<?php
	
	$filename = "financial_risk";
	
	if ($type == 'csv') {
			$filename  .= '.xls';
			//$mime_type = 'text/comma-separated-values';		
			$mime_type = 'application/vnd.ms-excel';	
	} elseif ($type == 'xls') {
			$filename  .= '.xls';
			$mime_type = 'application/vnd.ms-excel';
	} elseif ($type == 'xml') {
			$filename  .= '.xml';
			$mime_type = 'text/xml';	
	} elseif ($type == 'word') {
			$filename  .= '.doc';
			$mime_type = 'application/vnd.ms-word';
	} elseif ($type == 'pdf') {
			$filename  .= '.pdf';
			$mime_type = 'application/pdf';
	}
	
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="' . $filename . '"');		
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
?>	
<table border="0" cellpadding="3" cellspacing="0" width="100%" align="left">
	<tr>
		<td align="left">
			Print "Risk Debtor Report on: <?php print date("Y-M-d H:i:s"); ?>
		</td>
	</tr>
	<tr>
		<td align="left">
			<table border="1" cellpadding="3" cellspacing="0" width="100%" align="left">
				<thead>
					<tr>
						<th>No</th>
						<th>Account</th>
						<th>Subscription</th>
						<th>Status</th>
						<th>Deposit</th>
						<th>Balance</th>
						<th>Outstanding</th>
						<th>Risk debt</th>
					</tr>
				</thead>			
	
<?		
	$sid1 = $_GET['sid'];
	$sql = "SELECT ad.AccID, cp.CustID, cp.UserName, cp.StatusID, cp.SubscriptionName,
				(ad.NationalDeposit + ad.InternationDeposit + ad.MonthlyDeposit) as 'Deposit', ab.Credit, ab.Outstanding,
				(ad.NationalDeposit + ad.InternationDeposit + ad.MonthlyDeposit + ab.Credit) - ab.Outstanding as 'Risk'
			FROM tblAccDeposit ad, tblAccountBalance ab(nolock), tblCustProduct cp(nolock), tblTarPackage ta(nolock)
			WHERE ad.AccID = cp.AccID
				AND cp.AccID = ab.AccID
				AND cp.PackageID = ta.PackageID ";
	if($sid1 == 2){
		$sql .= " and ta.ServiceID = 2 ";
	}elseif($sid1 == 4){
		$sql .= " and ta.ServiceID = 4 ";
	}elseif($sid1 == 5){	
		$sql .= " and ta.ServiceID in(1, 3, 8) ";
	}			
		$sql .= "	ORDER BY ab.Outstanding DESC;";
							
	if($que = $mydb->sql_query($sql)){		
		$n = 0;
		$iLoop = 0;
		$cPos = 0;
		$cNeg = 0;
		$sPos = 0.00;
		$sNeg = 0.00;
		$Dep = 0.00;
		$Bal = 0.00;
		$OutS = 0.00;
		$GTotal = 0.00;
		print "<tbody>";
		while($result = $mydb->sql_fetchrow()){																															
			$CustID = $result['CustID'];										
			$AccID = $result['AccID'];
			$UserName = $result['UserName'];
			$SubscriptionName = $result['SubscriptionName'];
			$StatusID = $result['StatusID'];
			$Deposit = floatval($result['Deposit']);			
			$Balance = floatval($result['Credit']);
			$Outstanding = floatval($result['Outstanding']);
			$Risk = floatval($result['Risk']);
			if($Risk <0){
				$cNeg += 1;
				$sNeg += abs($Risk);
				$RiskDebt = "(".FormatCurrency(abs($Risk)).")";
			}else{
				$cPos += 1;
				$sPos += $Risk;
				$RiskDebt = FormatCurrency($Risk); 
			}
			$Dep += $Deposit;
			$Bal += $Balance;
			$OutS += $Outstanding;
			switch($StatusID){
				case 0:					
					$stwd = "Inactive";
					break;
				case 1:				
					$stwd = "Active";
					break;
				case 2:					
					$stwd = "Barred";
					break;
				case 3:					
					$stwd = "Closed";
					break;
			}			
			$n++;
			print "<tr>";
			print "<td align='left'>".$n."</td>";
			print "<td align='left'>".$UserName."</td>";
			print "<td align='left'>".$SubscriptionName."</td>";
			print "<td align='left'>".$stwd."</td>";			
			print "<td align='right'>".FormatCurrency($Deposit)."</td>";
			print "<td align='right'>".FormatCurrency($Balance)."</td>";
			print "<td align='right'>".FormatCurrency($Outstanding)."</td>";
			print "<td align='right'>".$RiskDebt."</td>";
			print "</tr>";		
		}		
	}
		print "</tbody>";
	$mydb->sql_freeresult();
		$GTotal = $sPos - $sNeg;
		if($GTotal >= 0){
			$sRisk = "Sub credit";
			$sGTotal = FormatCurrency($GTotal);
		}
		else{
			$sRisk = "Sub risk";
			$sGTotal = "(".FormatCurrency(abs($GTotal)).")";
		}
		print "<tfoot>";
		print "<tr>";
		print "<td align='left' colspan='7'>Total credit: (".$cPos.")</td>";
		print "<td align='right'>";
		print FormatCurrency($sPos);
		print "</td>";	
		print "</tr>";
		
		print "<tr>";
		print "<td align='left' colspan='7'>Total risk: (".$cNeg.")</td>";
		print "<td align='right'>";
		print FormatCurrency($sNeg);
		print "</td>";	
		print "</tr>";
		
		print "<tr>";
		print "<td colspan='4'>";
		print $sRisk;
		print "</td>";	
		print "<td align='right'>";
		print FormatCurrency($Dep);
		print "</td>";
		print "<td align='right'>";
		print FormatCurrency($Bal);
		print "</td>";
		print "<td align='right'>";
		print FormatCurrency($OutS);
		print "</td>";
		print "<td align='right'>";
		print $sGTotal;
		print "</td>";
		print "</tr>";
		print "</tfoot>";				
?>
			</table>
		</td>
	</tr>
</table>