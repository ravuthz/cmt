<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$where = $_GET['where'];		
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Account with deposit</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>
								<th align="center">Customer</th>
								<th align="center">Account</th>
								<th align="center">Subscription</th>
								<th align="center">Status</th>
								<th align="center">Package</th>
								<th align="center">Service</th>
								<th align="center">NC deposit</th>
								<th align="center">IC deposit</th>
								<th align="center">MF deposit</th>																																												
							</thead>
							<tbody>';
	
	$sql = "select c.CustID, c.CustName, a.AccID, a.UserName, a.SubscriptionName, a.StatusID, t.TarName, 
																			s.ServiceName, d.NationalDeposit, d.InternationDeposit, d.MonthlyDeposit 																	
															from tblCustomer c, tblCustProduct a, tblTarPackage t, tlkpService s, tblAccDeposit d
															where c.CustID = a.CustID and a.PackageID = t.PackageID and t.ServiceID = s.ServiceID	
																	and a.AccID = d.AccID
																	and (d.NationalDeposit > 0 or d.InternationDeposit > 0 or d.MonthlyDeposit > 0)	
																	and t.PackageID in(".$where.")																		
															order by a.AccID, a.StatusID ";
			 								
	if($que = $mydb->sql_query($sql)){		
		
		$iLoop = 0;
		
			while($result = $mydb->sql_fetchrow()){																
				$CustID = $result['CustID'];																
				$CustName = $result['CustName'];
				$AccID = $result['AccID'];											
				$UserName = $result['UserName'];																
				$StatusID = intval($result['StatusID']);
				$TarName = $result['TarName'];
				$SubscriptionName = $result['SubscriptionName'];
				$ServiceName = $result['ServiceName'];
				$NationalDeposit = $result['NationalDeposit'];
				$InternationDeposit = $result['InternationDeposit'];
				$MonthlyDeposit = $result['MonthlyDeposit'];
				
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
				$linkCust = "<a href='./?CustomerID=".$CustID."&pg=10'>".$CustName."</a>";
				$linkAcct = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
				$totalNC += floatval($NationalDeposit);
				$totalIC += floatval($InternationDeposit);
				$totalMF += floatval($MonthlyDeposit);
				$iLoop++;															
				if(($iLoop % 2) == 0)
					$style = "row1";
				else
					$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$linkCust.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$linkAcct.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';
			$retOut .= '<td align="center" bgcolor="'.$stbg.'">
										<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
									 </td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$ServiceName.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($NationalDeposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($InternationDeposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($MonthlyDeposit).'</td>';
			$retOut .= '</tr>';
		}		
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																					
								</table>						
							</td>
						</tr>
					</table>';
		
	print $retOut;	
?>