<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$tid = $_GET['tid'];
	$cid = $_GET['cid'];	
		
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>CUSTOMER REPORT BY CATEGORY</b>
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
								<th align="center">NC Deposit</th>
								<th align="center">IC Deposit</th>			
								<th align="center">MF Deposit</th>
								<th align="center">Balance</th>
								<th align="center">Outstanding</th>																						
							</thead>
							<tbody>';
	
	$sql = "select a.CustID, a.AccID, a.UserName, a.StatusID, t.TarName, b.Credit,
							d.NationalDeposit, d.InternationDeposit, d.MonthlyDeposit, Sum(i.UnpaidAmount) as 'Invoice'
					from tblCustProduct a, tblAccountBalance b, tblAccDeposit d, tblCustomerInvoice i, 
								tblTarPackage t, tblcustproductcredit cre 					
					where a.AccID = b.AccID and a.AccID = d.AccID and a.AccId = i.AccID and a.AccID = cre.AccID and a.PackageID = t.PackageID ";
	if(intval($tid) > 0)														 
		$sql .= " and cre.CredType=".$tid;
	if(intval($cid) > 0)
		$sql .= " and cre.CredID=".$cid;
	$sql .= " group by a.CustID, a.AccID, a.UserName, a.StatusID, t.TarName, b.Credit,
										 d.NationalDeposit, d.InternationDeposit, d.MonthlyDeposit ";	
	//if($cpe != 0)
//		$sql .= " and u.CPEID = ".$cpe;
	
	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																																								
			$CustID = $result['CustID'];										
			$AccID = $result['AccID'];																
			$UserName = $result['UserName'];											
			$StatusID = $result['StatusID'];											
			$TarName = $result['TarName'];
			$NationalDeposit = $result['NationalDeposit'];
			$InternationDeposit = $result['InternationDeposit'];
			$MonthlyDeposit = $result['MonthlyDeposit'];
			$Invoice = $result['Invoice'];
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
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($NationalDeposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($InternationDeposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($MonthlyDeposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Credit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Invoice).'</td>';
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