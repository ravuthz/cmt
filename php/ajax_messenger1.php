<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");		
	$mid = $_GET['mid'];
	$div = $_GET['div'];
	$mnane = $_GET['mname'];
	$sid = $_GET['sid'];
	//$cpe = $_GET['cpe'];		
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>'.$mnane.'</b></td>
					<td align="right">[<a href="#" onClick="hide(\''.$div.'\');">Hide</a>]</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">Account</th>
								<th align="center">Subscription</th>
								<th align="center">Status</th>	
								<th align="center">Package</th>
								<th align="center">Service</th>																																											
							</thead>
							<tbody>';
	
	$sql = "
						select a.AccID, a.UserName, a.CustID, a.SubscriptionName, a.StatusID, t.TarName, s.ServiceName
						from tblCustProduct a, tblCustAddress ca, tblTarPackage t, tlkpService s
						where a.AccID = ca.AccID
							and a.PackageID = t.PackageID
							and t.ServiceID = s.ServiceID
							and a.MessengerID = ".$mid."
							and ca.SangkatID = ".$sid."
							and ca.IsBillingAddress = 1							
				";		 	
							
	if($que = $mydb->sql_query($sql)){		
		
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$AccID = $result['AccID'];										
			$CustID = $result['CustID'];										
			$UserName = $result['UserName'];																
			$SubscriptionName = $result['SubscriptionName'];																				
			$StatusID = $result['StatusID'];
			$TarName = $result['TarName'];
			$ServiceName = $result['ServiceName'];
			
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
				}
				
			$LinkAccount = "<a href=\"./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91\" target = '_blank'>".$UserName."</a>";																						
			$LinkAccount1 = "<a href=\"./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91\" target = '_blank'>".$SubscriptionName."</a>";									
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$LinkAccount.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left">'.$LinkAccount1.'</td>';
			$retOut .= '<td align="center" bgcolor="'.$stbg.'">
												<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
											 </td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$ServiceName.'</td>';
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