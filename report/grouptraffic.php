<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$cycle = $_GET['cycle'];
	$loc = 	$_GET['loc'];
	$locn = $_GET['locn'];
	$type = $_GET['type'];		
	$typeT = $_GET['typeT'];
	$where = $_GET['w'];	
	$mArr = split("/", $cycle, 2);
	$m = intval($mArr[0]);
	$y = intval($mArr[1]);			 
	
	$month = array(1=>"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	
	$cycleT = $month[$m];
function generateReport($PackageID, $Package, $type, $cycle, $loc){
	global $mydb;
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle" colspan=2>
						<b>&nbsp;&nbsp;&nbsp;'.$Package.'</b>
					</td>					
				</tr> 
				<tr>
					<td width=10>&nbsp;</td>
					<td>
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																									
								<th align="center">Call type</th>	
								<th align="center" width="200">Calls</th>	
								<th align="center" width="200">Minutes</th>
								<th align="center" width="200">Amount</th>											
							</thead>
							<tbody>';
	
	$sql = "    select 
				Description,
				sum(Calls) NoCall,
				sum(Duration) Minute,
				Sum(amount) Money
				from tblReportBillProcess rb join tblTarPackage tp on rb.packageid = tp.packageid
				where convert(varchar,SubmittedDate,112) = '".$cycle."'	 
				and case
				when rb.packageid in (16,17) then 'PCO'
				when rb.packageid in (18) then 'Internal'
				when rb.packageid in (19) then 'PayPhone'
				else 'Customer'
				end in ( ".$PackageID." )	
				and serviceid = 2 ";
	if ($loc != "All")
	$sql .= "   and CityID = " .$loc;
	
	$sql .= "  group by Description order by Description ";
			
	//AND r.InvoiceType = ".$type." 
	if($que = $mydb->sql_query($sql)){		
		
		$totalcall = 0;
		$totalminute = 0;
		$totalamount = 0.00;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$PackageName = $result['PackageName'];										
			$Description = $result['Description'];										
			$NoCall = $result['NoCall'];																
			$Minute = $result['Minute'];											
			$Money = $result['Money'];																							
				
			$totalcall += $NoCall;											
			$totalminute += $Minute;											
			$totalamount += floatval($Money);											
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left">'.$Description.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.number_format($NoCall).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.number_format($Minute,2).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Money).'</td>';
			$retOut .= '</tr>';
		}		
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right">Total</td>											
											<td align="right">'.number_format($totalcall).'</td>
											<td align="right">'.number_format($totalminute,2).'</td>
											<td align="right">'.FormatCurrency($totalamount).'</td>
										</tr>
									</tfoot>												
								</table>						
							</td>
						</tr>
					</table>';
	return $retOut;
}// end function	

	$retfun = "<table border=0 cellpadding=0 cellspacing=0 width='100%'>
							<tr>
								<td align=center><b>".$locn." USAGE STATISTIC OF BILL PROCESSING FOR ".FormatDate($cycle,5)."</td>
							</tr>
						";
	$sql = "
				select distinct PackageName = case
				when rb.packageid in (16,17) then 'PCO'
				when rb.packageid in (18) then 'Internal'
				when rb.packageid in (19) then 'PayPhone'
				else 'Customer'
				end
				from tblReportBillProcess rb join tblTarPackage tp on rb.packageid = tp.packageid
				where convert(varchar,SubmittedDate,112) = '".$cycle."'	 
				and serviceid = 2
				order by PackageName";
	$ToPackageName = "''";
	if($que = $mydb->sql_query($sql)){
		while($result = $mydb->sql_fetchrow($que)){
			$PackageName = $result['PackageName'];
			$PackageNameS = "'".$result['PackageName']."'";
			$ToPackageName .= ",'".$result['PackageName']."'";
				$retfun .= "<tr><td>";
				$retfun .= generateReport($PackageNameS, $PackageName, $type, $cycle,$loc);
				$retfun .= "</td></tr>";
		}
	}
	$retfun .= "<tr><td><br /></td></tr>";
	$retfun .= "<tr><td>";
		$retfun .= generateReport($ToPackageName, "GRAND TOTAL", $type, $cycle,$loc);
	$retfun .= "</td></tr>";
	$retfun .= "</table>";
	print $retfun;	
?>