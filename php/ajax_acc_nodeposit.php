<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$where = $_GET['where'];		
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Account with no deposit</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">ID</th>
								<th align="center">Account</th>
								<th align="center">Subscription</th>
								<th align="center">Status</th>	
								<th align="center">Package</th>
								<th align="center">Balance</th>
								<th align="center">Open invoice</th>																																												
							</thead>
							<tbody>';
	
	$sql = "SELECT a.CustID, a.AccID, a.UserName, a.SubscriptionName, a.StatusID, p.TarName, bal.Credit, bal.Outstanding
					FROM tblCustProduct a, tblTarPackage p, tblAccountBalance bal
					WHERE a.PackageID = p.PackageID and a.AccID = bal.AccID and p.PackageID in(".$where.") ORDER BY bal.Outstanding";
			 								
	if($que = $mydb->sql_query($sql)){		
		
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$AccID = $result['AccID'];										
			$CustID = $result['CustID'];										
			$UserName = $result['UserName'];																
			$SubscriptionName = $result['SubscriptionName'];																					
			$StatusID = $result['StatusID'];
			$OutStanding = $result['OutStanding'];
			$TarName = $result['TarName'];
			$Credit = $result['Credit'];			
			
			switch($StatusID){
					case 0: #inactive
						$stbg = $bgUnactivate;
						$stfg = $foreUnactivate;
						$stwd = "Inactive";						
						break;
					case 1: #inactive
						$stbg = $bgActivate;
						$stfg = $foreActivate;
						$stwd = "Active";
						break;
					case 2: #Bar				
						$stbg = $bgLock;
						$stfg = $foreLock;
						$stwd = "Barred";
						break;
					case 3: #Close				
						$stbg = $bgClose;
						$stfg = $foreClose;
						$stwd = "Closed";		
						break;
					case 4: #Close				
						$stbg = $bgClose;
						$stfg = $foreClose;
						$stwd = "Closed";		
						break;
				}
				
			$LinkAccount = "<a href=\"./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91\">".$UserName."</a>";																														
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$AccID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$LinkAccount.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';
			$retOut .= '<td align="center" bgcolor="'.$stbg.'">
												<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
											 </td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.FormatCurrency($Credit).'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.FormatCurrency($OutStanding).'</td>';
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