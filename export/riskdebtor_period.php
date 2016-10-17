<?php
	
	$filename = "financial_risk_period";
	
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
	
	$st=$_GET['st'];
	$et=$_GET['et'];
	$sid1 = $_GET['sid'];
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>&nbsp;Risk Debtor Report from '.FormatDate($st, 3).' and '.FormatDate($et, 3).'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No.</th>					
								<th align="center">Acct ID</th>
								<th align="center">Account</th>
								<th align="center" style="border:1px solid #999999">Sunbscription</th>
								<th align="center">Status</th>
								<th align="center">Deposit</th>								
								<th align="center">Balance</th>
								<th align="center">Outstanding</th>
								<th align="center">Risk debt</th>
							</thead>
							<tbody>';
	$sql = "

				SELECT i.AccID, SUM(i.UnpaidAmount) AS 'OutStanding'
				INTO #Outstanding
				FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage ta(nolock)
				WHERE i.AccID = a.AcciD
					and a.PackageID = ta.PackageID ";
	if($sid1 == 2){
		$sql .= " and ta.ServiceID = 2 ";
	}elseif($sid1 == 4){
		$sql .= " and ta.ServiceID = 4 ";
	}elseif($sid1 == 5){	
		$sql .= " and ta.ServiceID in(1, 3, 8) ";				
	}
	$sql .= " AND	Convert(varchar, IssueDate, 112) between ".FormatDate($st, 4)." AND ".FormatDate($et, 4)."
		GROUP BY i.AccID;								
		SELECT ad.AccID, cp.CustID, cp.UserName, cp.StatusID, cp.SubscriptionName,
			(ad.NationalDeposit + ad.InternationDeposit + ad.MonthlyDeposit) as 'Deposit', 
			ab.Credit, ISNULL(i.OutStanding, 0) AS 'OutStanding'				
		FROM (((tblAccDeposit ad(nolock) 
		INNER JOIN tblaccountbalance ab(nolock) ON ad.AccID = ab.AccID)
		INNER JOIN tblCustProduct cp(nolock) ON ad.AccID = cp.AccID)	
		INNER JOIN #Outstanding i(nolock) ON cp.AccID = i.AccID)				
		ORDER BY 7 DESC;
		DROP TABLE #Outstanding;
			";
							
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
		while($result = $mydb->sql_fetchrow()){																															
			$CustID = $result['CustID'];										
			$AccID = $result['AccID'];
			$UserName = $result['UserName'];
			$SubscriptionName = $result['SubscriptionName'];
			$StatusID = $result['StatusID'];
			$Deposit = floatval($result['Deposit']);			
			$Balance = floatval($result['Credit']);
			$Outstanding = floatval($result['OutStanding']);
			$Risk = floatval(($Deposit + $Balance) - $Outstanding);
			if($Risk <0){
				$cNeg += 1;
				$sNeg += abs($Risk);
				$RiskDebt = "<font color='red'>(".FormatCurrency(abs($Risk)).")</font>";
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
					$stbg = "gray";
					$stfg = "white";
					$stwd = "Inactive";
					break;
				case 1:
					$stbg = "blue";
					$stfg = "white";
					$stwd = "Active";
					break;
				case 2:
					$stbg = "orange";
					$stfg = "white";
					$stwd = "Barred";
					break;
				case 3:
					$stbg = "red";
					$stfg = "white";
					$stwd = "Closed";
					break;
				case 4:
					$stbg = "red";
					$stfg = "white";
					$stwd = "Closed";
					break;
			}
			//$linkAcc = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=90'>".$UserName."</a>";		
			$iLoop++;															
			$n++;
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.'" align="center">'.$n.'</td>';																																														
			$retOut .= '<td class="'.$style.'" align="left">'.$AccID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$UserName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';
			$retOut .= '<td align="center" bgcolor="'.$stbg.'">
										<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
									 </td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Deposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Balance).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Outstanding).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right">'.$RiskDebt.'</td>';
			$retOut .= '</tr>';									
		}		
	}else{
		$error = $mydb->sql_error();
		print $error['message'];
	}
	$mydb->sql_freeresult();
		$GTotal = $sPos - $sNeg;
		if($GTotal >= 0){
			$sRisk = "Sub credit";
			$sGTotal = FormatCurrency($GTotal);
		}
		else{
			$sRisk = "Sub risk";
			$sGTotal = "<font color=red>(".FormatCurrency(abs($GTotal)).")";
		}
		$retOut .= '</tbody>
								<tfoot>
									<tr>
										<td colspan=8 align=left>Sub credit: ('.$cPos.')</td>
										<td align=right>'.FormatCurrency($sPos).'</td>
									</tr>
									<tr>
										<td colspan=8 align=left>Sub risk: ('.$cNeg.')</td>
										<td align=right><font color=red>('.FormatCurrency($sNeg).')</font></td>
									</tr>									
									<tr>
										<td colspan=5 align=left>'.$sRisk.'</td>
										<td align=right>'.FormatCurrency($Dep).'</td>
										<td align=right>'.FormatCurrency($Bal).'</td>
										<td align=right>'.FormatCurrency($OutS).'</td>
										<td align=right>'.$sGTotal.'</td>
									</tr>
								</tfoot>																					
								</ttable>						
							</td>
						</tr>
					</table>';
	print $retOut;	
?>