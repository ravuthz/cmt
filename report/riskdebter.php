<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>&nbsp;Risk Debtor Report on '.date("Y-M-d H:i:s").'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid #999999">No.</th>					
								<th align="center" style="border:1px solid #999999">Account</th>
								<th align="center" style="border:1px solid #999999">Subscription</th>
								<th align="center" style="border:1px solid #999999">Status</th>
								<th align="center" style="border:1px solid #999999">Deposit</th>								
								<th align="center" style="border:1px solid #999999">Balance</th>
								<th align="center" style="border:1px solid #999999">Outstanding</th>
								<th align="center" style="border:1px solid #999999">Risk debt</th>
							</thead>
							<tbody>';
	$sql = "SELECT ad.AccID, cp.CustID, cp.UserName, cp.StatusID, cp.SubscriptionName, 
						(ad.NationalDeposit + ad.InternationDeposit + ad.MonthlyDeposit) as 'Deposit', ab.Credit, ab.Outstanding,
						(ad.NationalDeposit + ad.InternationDeposit + ad.MonthlyDeposit + ab.Credit) - ab.Outstanding as 'Risk'
					FROM tblAccDeposit ad(nolock),  tblaccountbalance ab(nolock), tblCustProduct cp(nolock), tblTarPackage ta(nolock)
					WHERE ad.AccID = cp.AccID
						AND ab.AccID = cp.AccID
						AND cp.PackageID = ta.PackageID
				";
	if($sid == 2){
		$sql .= " and ta.ServiceID = 2 ";
	}elseif($sid == 4){
		$sql .= " and ta.ServiceID = 4 ";
	}elseif($sid == 5){	
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
			$linkAcc = $UserName;		
			$iLoop++;															
			$n++;
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-bottom:1px dotted #999999">'.$n.'</td>';																																														
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-bottom:1px dotted #999999">'.$linkAcc.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-bottom:1px dotted #999999">'.$SubscriptionName.'</td>';
			$retOut .= '<td align="center" bgcolor="'.$stbg.'" style="border-left:1px dotted #999999; border-bottom:1px dotted #999999">
										<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
									 </td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-bottom:1px dotted #999999">'.FormatCurrency($Deposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-bottom:1px dotted #999999">'.FormatCurrency($Balace).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-bottom:1px dotted #999999">'.FormatCurrency($Outstanding).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-bottom:1px dotted #999999; border-right:1px solid #999999">'.$RiskDebt.'</td>';
			$retOut .= '</tr>';									
		}		
	}
	$mydb->sql_freeresult();
		$GTotal = $sPos - $sNeg;
		if($GTotal >= 0){
			$sRisk = "Sub credit";
			$sGTotal = FormatCurrency($GTotal);
		}
		else{
			$sRisk = "Sub risk";
			$sGTotal = "<font color=red>(".FormatCurrency($GTotal).")";
		}
		$retOut .= '</tbody>
								<tfoot>
									<tr>
										<td colspan=7 align=left style="border:1px solid #999999">Sub credit: ('.$cPos.')</td>
										<td align=right style="border:1px solid #999999">'.FormatCurrency($sPos).'</td>
									</tr>
									<tr>
										<td colspan=7 align=left style="border:1px solid #999999">Sub risk: ('.$cNeg.')</td>
										<td align=right style="border:1px solid #999999"><font color=red>('.FormatCurrency($sNeg).')</font></td>
									</tr>									
									<tr>
										<td colspan=4 align=left style="border:1px solid #999999">'.$sRisk.'</td>
										<td align=right style="border:1px solid #999999">'.FormatCurrency($Dep).'</td>
										<td align=right style="border:1px solid #999999">'.FormatCurrency($Bal).'</td>
										<td align=right style="border:1px solid #999999">'.FormatCurrency($OutS).'</td>
										<td align=right style="border:1px solid #999999">'.$sGTotal.'</td>
									</tr>
								</tfoot>																					
								</table>						
							</td>
						</tr>
					</table>';
	print $retOut;	
?>