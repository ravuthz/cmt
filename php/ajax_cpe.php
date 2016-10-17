<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];
	//$cpe = $_GET['cpe'];		
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>CPE used report from '.formatDate($st, 6).' and '.formatDate($et, 6).'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">Account</th>
								<th align="center">Status</th>	
								<th align="center">Package</th>	
								<th align="center">CPE</th>
								<th align="center">Serial number</th>			
								<th align="center">Used date</th>																										
							</thead>
							<tbody>';
	
	$sql = "select a.AccID, a.CustID, a.UserName, a.StatusID, t.TarName, c.CPEName, c.SerialNumber, u.UsedDate
					from tblCustProduct a, tblTarPackage t, tlkpCPE c, tblCPEUsed u
					where t.PackageID = a.PackageID and a.AccID = u.AccID and u.CPEID = c.CPEID				
							and convert(varchar, u.UsedDate, 112) >= '".formatDate($st, 4)."' 
							and convert(varchar, u.UsedDate, 112) <= '".formatDate($et, 4)."'";	
	//if($cpe != 0)
//		$sql .= " and u.CPEID = ".$cpe;
	
	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$AccID = $result['AccID'];										
			$CustID = $result['CustID'];										
			$UserName = $result['UserName'];																
			$StatusID = $result['StatusID'];											
			$TarName = $result['TarName'];											
			$CPEName = $result['CPEName'];
			$SerialNumber = $result['SerialNumber'];
			$UsedDate = $result['UsedDate'];
					
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
			
			$LinkAccount = "<a href=\"./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91\">".$UserName."</a>";																																			
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$LinkAccount.'</td>';						
			$retOut .= '<td align="center" bgcolor="'.$stbg.'">
										<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
									 </td>';																		
			$retOut .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$CPEName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$SerialNumber.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatDate($UsedDate, 3).'</td>';
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