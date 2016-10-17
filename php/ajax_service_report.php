<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$serviceid = $_GET['serviceid'];	
	$where = $_GET['where'];
	$ina = $_GET['ina'];
	$ac = $_GET['ac'];
	$ba = $_GET['ba'];
	$cl = $_GET['cl'];

	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">			
						<b>Service report </b>
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
								<th align="center">NC Desposit</th>
								<th align="center">IC Deposit</th>
								<th align="center">MF Deposit</th>
								<th align="center">Balance</th>		
								<th align="center">Outstanding</th>			
							</thead>
							<tbody>';
	$sql = "select a.CustID, a.AccID, a.UserName, a.StatusID, d.NationalDeposit, d.InternationDeposit, 
									d.MonthlyDeposit, b.Credit, b.Outstanding 
					from tblCustProduct a(nolock), tblAccDeposit d, tblTarPackage t, tblAccountBalance b(nolock) 
					where a.AccID = d.AccID and a.AccID = b.AccID and a.PackageID = t.PackageID";
	
		$sql .= " and t.PackageID in (".$where.") ";
	$sql .= " and a.StatusID in(9 ";
		if($ina != 0)
			$sql .= ", 0 ";
		if($ac != 0)
			$sql .= ", 1 ";
		if($ba != 0)
			$sql .= ", 2 ";
		if($cl != 0)
			$sql .= ", 3, 4 ";	
		$sql .= ")";
							
	if($que = $mydb->sql_query($sql)){
		$sumNc;		
		$sumMc;
		$sumIc;
		$sumMonFee;
		$sumBalace;
		$sumInvoice;
		$count = 0;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){
			
			$CustID = $result['CustID'];
			$AccID = $result['AccID'];																						
			$UserName = $result['UserName'];								
			$NcDeposit = $result['NationalDeposit'];
			$IcDeposit = $result['InternationDeposit'];
			$McDeposit = $result['MonthlyDeposit'];
			$MonthlyFee = $result['CycleFee'];
			$Balance = $result['Credit'];
			$Invoice = $result['Outstanding'];
			$StatusID = $result['StatusID'];
			$sumNc = $sumNc+$NcDeposit;
			$sumMc = $sumMc+$McDeposit;
			$sumIc = $sumIc+$IcDeposit;
			$sumMonFee = $sumMonFee+$MonthlyFee;
			$sumBalace = $sumBalace+$Balance;
			$sumInvoice = $sumInvoice+$Invoice;
			$count++;
						
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
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.' width="100" align="left">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.' width="100" align="left">'.$UserName.'</td>';																								
			$retOut .= '<td align="center" bgcolor="'.$stbg.'">
										<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
									 </td>';
			$retOut .= '<td class="'.$style.' width="100" align="right">'.FormatCurrency($NcDeposit).'</td>';
			$retOut .= '<td class="'.$style.' width="100" align="right">'.FormatCurrency($IcDeposit).'</td>';
			$retOut .= '<td class="'.$style.' width="100" align="right">'.FormatCurrency($McDeposit).'</td>';
			$retOut .= '<td class="'.$style.' width="100" align="right">'.FormatCurrency($Balance).'</td>';
			$retOut .= '<td class="'.$style.' width="100" align="right">'.FormatCurrency($Invoice).'</td>';
			
			$retOut .= '</tr>';									
		}	
			
			$retOut .= '<tr>';
			$retOut .= '<td colspan="3" align="Right">Total:</th>	
						<td align="right">'.FormatCurrency($sumNc).'</td>
						<td align="right">'.FormatCurrency($sumIc).'</td>
						<td align="right">'.FormatCurrency($sumMc).'</td>
						<td align="right">'.FormatCurrency($sumBalace).'</td>		
						<td align="right">'.FormatCurrency($sumInvoice).'</td>';	
			$retOut .= '</tr>';			
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																					
								</table>						
							</td>
						</tr>
					</table>';	
	print $retOut;	
?>
