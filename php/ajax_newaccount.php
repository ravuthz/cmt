<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];
	$c = $_GET['c'];				
	$ina = $_GET['ina'];
	$ac = $_GET['ac'];
	$ba = $_GET['ba'];
	$ba = $_GET['ba'];
	$where = $_GET['where'];
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>New accounts from '.formatDate($st, 6).' and '.formatDate($et, 6).'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">Account</th>
								<th align="center">Subscription</th>
								<th align="center">Status</th>	
								<th align="center">Sign up</th>	
								<th align="center">Package</th>
								<th align="center">Service</th>
								<th align="center">Salesman</th>																																												
							</thead>
							<tbody>';
	
	$sql = "select a.AccID, a.CustID, a.UserName, a.StatusID, a.SubscriptionName, a.SetupDate, t.TarName, s.ServiceName, 
						sale.Salutation, sale.Name						
					from tblCustProduct a(nolock), tblTarPackage t(nolock), tlkpService s(nolock), tlkpSalesMan sale, tblAccStatusHistory his
					where a.PackageID = t.PackageID and t.ServiceID = s.ServiceID 
							and a.SalePersonID = sale.SalesmanID						
							and a.AccID = his.AccID
							and convert(varchar, his.ChangeDate, 112) >= '".formatDate($st, 4)."' 
							and convert(varchar, his.ChangeDate, 112) <= '".formatDate($et, 4)."' ";
		$sql .= " and t.PackageID IN(".$where.") ";
		if($c != 0)
			$sql .= " and a.SalePersonID = ".$c;
		$sql .= " and his.StatusID in(9 ";
		if($ina != 0)
			$sql .= ", 0 ";
		if($ac != 0)
			$sql .= ", 1 ";
		if($ba != 0)
			$sql .= ", 2 ";
		if($cl != 0)
			$sql .= ", 3, 4 ";	
		$sql .= ")";
	$sql .= " order by a.SetupDate";		 	
							
	if($que = $mydb->sql_query($sql)){		
		
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$AccID = $result['AccID'];										
			$CustID = $result['CustID'];										
			$UserName = $result['UserName'];																
			$SubscriptionName = $result['SubscriptionName'];											
			$SetupDate = $result['SetupDate'];											
			$StatusID = $result['StatusID'];
			$OutStanding = $result['OutStanding'];
			$TarName = $result['TarName'];
			$ServiceName = $result['ServiceName'];
			$Salutation = $result['Salutation'];
			$Name = $result['Name'];											
			$salesman = $Salutation." ".$Name;
			
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
			$LinkAccount1 = "<a href=\"./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91\">".$SubscriptionName."</a>";									
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
			$retOut .= '<td class="'.$style.'" align="right">'.formatDate($SetupDate, 3).'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$ServiceName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$salesman.'</td>';
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