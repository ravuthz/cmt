<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<style>
	td, th{
		font-family:"Courier New", Courier, monospace;
		font-size:10px;
	}
</style>
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$cid = $_GET['cid'];
	$cid2 = $_GET['cid2'];
	$pid = $_GET['pid'];
	$tid = $_GET['tid'];	
	
	$pt = $_GET['pt'];
	$tt = str_replace("0 ,","",$_GET['tt']);		
	$sid = $_GET['sid'];		
	$st = $_GET['st'];	
		
function generateReport($sid, $sn, $tid, $cid2, $cid, $one){
	global $mydb;
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle" colspan=2>	';
					
		$retOut .= ' <b>&nbsp;&nbsp;'.$sn.' data between '.$cid2.' & '.$cid.'</b><br> ';											
	$retOut .=	' </td>					
				</tr> 
				<tr>
					<td width=10>&nbsp;</td>
					<td>
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																									
								<th align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>																
								<th align="center" width=12% style="border-left:1px dotted #999999; border-top:1px solid #999999;">Location</th>	
								<th align="center" width=2% style="border-left:1px dotted #999999; border-top:1px solid #999999;">LINE in<BR />'.$cid2.'</th>
								<th align="center" width=8% style="border-left:1px dotted #999999; border-top:1px solid #999999;">REVENUE in<BR />'.$cid2.'</th>
								<th align="center" width=8% style="border-left:1px dotted #999999; border-top:1px solid #999999;">REVENUE in<BR />'.$cid.'</th>
								<th align="center" width=2% style="border-left:1px dotted #999999; border-top:1px solid #999999;">LINE in<BR />'.$cid.'</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>Revenue Increase</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>Paid for<BR />'.$cid.'</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999; border-right:1px solid #999999;" nowrape>unPaid for<BR />'.$cid.'</th>											
							</thead>
							<tbody>';
	
	$sql = "		Begin Try
						Drop table #tmp".$sid.str_replace(".","", $cid2.$cid.$one)."
					End Try
					Begin catch
					End Catch
					
					select	identity(int,1,1) ID,
							Location,
							Convert(varchar,BillEndDate,102) BillEndDate,
							sum(Lines) Lines,
							Round(SUM(InvoiceAmount),2) InvoiceAmount,
							SUM(PaidAmount) Paid,
							SUM(UnpaidAmount) Unpaid
							into #tmp".$sid.str_replace(".","", $cid2.$cid.$one)."
					from tblCustomerInvoiceRmtSum(nolock) 
					where CityID in (".$tid.")
					And left(Convert(varchar,BillEndDate,102),7) between '".$cid2."' and '".$cid."' "; 
					
if($one<>"0")					
	$sql .= " and  GrpSID=".$sid;

	
	$sql .= " Group by Location,Convert(varchar,BillEndDate,102)
			  Having SUM(InvoiceAmount) > 0
			  Order by Location,BillEndDate 
			  
			  
			 select	ID,Location,
			 		(select Lines from #tmp".$sid.str_replace(".","", $cid2.$cid.$one)." where location = t1.location and left(BillEndDate,7) = '".$cid2."') 'fL',	
					(select InvoiceAmount from #tmp".$sid.str_replace(".","", $cid2.$cid.$one)." where location = t1.location and left(BillEndDate,7)	 = '".$cid2."') 'fR',	
			  		InvoiceAmount,Lines,Paid,Unpaid
		     from #tmp".$sid.str_replace(".","", $cid2.$cid.$one)." t1
			 where left(BillEndDate,7) = '".$cid."'
			 order by InvoiceAmount desc
			 
			 Drop table #tmp".$sid.str_replace(".","", $cid2.$cid.$one)."
			   
			  ";


	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		$TotalfL = 0;
		$TotalfR = 0.00;
		$TotalLine = 0;
		$GrandInvAmt = 0.00;
		$GrandInc = 0.00;
		$GrandPaidAmt = 0.00;
		$GrandUnpaidAmt = 0.00;	
		while($result = $mydb->sql_fetchrow($que)){																															
											
			$EnterDate = $result['EnterDate'];										
			$ServiceName = $result['ServiceName'];										
			$Location = $result['Location'];										
			
			$fL = $result['fL'];
			$TotalfL += $fL;																
			
			$fR = $result['fR'];
			$TotalfR += $fR;																
	
			$Lines = $result['Lines'];
			$TotalLine += $Lines;																
			
			$InvoiceAmount = $result['InvoiceAmount'];
			$GrandInvAmt += floatval($InvoiceAmount);
			
			$Increase = $InvoiceAmount - $fR;
			$GrandInc += floatval($Increase);
			
			$Paid = $result['Paid'];
			$GrandPaidAmt += floatval($Paid);
			
			$Unpaid = $result['Unpaid'];	
			$GrandUnpaidAmt += floatval($Unpaid);		
			
			
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			$Inv = "<a href='../report/revenue_detail_group_loc.php?sid=".$sid."&tid=".$tid."&st=".$cid."&it=".$InvoiceType."&pid=".$pid."&pt=".$pt."&tt=".$tt."&pack=".$pack."' target='_blank'>".$Inv."</a>";
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px dotted #999999;">'.$iLoop.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Location.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.number_format($fL).'</td>';			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($fR).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($InvoiceAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.number_format($Lines).'</td>';			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($Increase).'='.number_format(($Increase*100)/$InvoiceAmount,2).'%</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($Paid).'='.number_format(($Paid*100)/$InvoiceAmount,2).'%</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999;">'.FormatCurrency($Unpaid).'='.number_format(($Unpaid*100)/$InvoiceAmount,2).'%</td>';
			$retOut .= '</tr>';
				
		}
	}
		$mydb->sql_freeresult();
		
		
		
		$TotalInv = "<a href='../report/revenue_detail_group_loc.php?sid=".$sid."&tid=".$tid."&st=".$cid."&it=All&pid=".$pid."&pt=".$pt."&tt=".$tt."&pack=".$pack."' target='_blank'>".$TotalInv."</a>";
		
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="center" colspan=2 style="border-left:1px solid #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">Total</td>										
											<td align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.number_format($TotalfL).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($TotalfR).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($GrandInvAmt).'</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.number_format($TotalLine).'</td>';
											
											
if($GrandInvAmt == 0)
	$GrandInvAmt = 1;	
											
					$retOut .='<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($GrandInc).'='.number_format(($GrandInc*100)/$GrandInvAmt,2).'%</td>
					
					<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($GrandPaidAmt).'='.number_format(($GrandPaidAmt*100)/$GrandInvAmt,2).'%</td>
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999; border-right:1px solid #999999;">'.FormatCurrency($GrandUnpaidAmt).'='.number_format(($GrandUnpaidAmt*100)/$GrandInvAmt,2).'%</td>										
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
								<td align=left>
									<table width='100%'>
										<tr>
											<td align=left>
												<b>TREASURY AND BILLING DEPARTMENT, CAMINTEL <br />
												MONTHLY REVENUE AND DEBT SUMMARY<br>
												By: ".$cid."<br />
												</b>
											</td>
											<td align=right>
												Printed on ".date("Y-m-d H:i:s")."
											</td>
										</tr>
									</table>
								</td>
							
							</tr>
						";
						
$cmd = "select distinct GroupServiceID GrpSID, Description GrpSN from tlkpService
		where GroupServiceID is not null ";

if ($sid <> "0")
	$cmd .= "and GroupServiceID=".$sid ;	
	
$cmd .= " order by GrpSN";				

		if($que1 = $mydb->sql_query($cmd)){
		while($result1 = $mydb->sql_fetchrow($que1)){
			$GrpSID = $result1['GrpSID'];
			$GrpSN = $result1['GrpSN'];
			
			$retfun .= "<tr><td align=center class=formtitle><br><h6>".strtoupper($GrpSN)." statistic of Camintel</h6></td></tr>";	

					$cmd2 = "Select Distinct left(CONVERT(varchar,dateadd(month,-1,BillEndDate),102),7) LastEndDate, left(CONVERT(varchar,BillEndDate,102),7) BillEndDate from tblCustomerInvoiceRmtSum
						where CONVERT(varchar,BillEndDate,102) between '".$cid2."' and '".$cid."'
						order by BillEndDate desc";
						
					if($que2 = $mydb->sql_query($cmd2)){
					while($result2 = $mydb->sql_fetchrow($que2)){	
					
																$LastEndDate = $result2['LastEndDate'];
																$BillEndDate = $result2['BillEndDate'];
																
																
																
																	$retfun .= "<tr><td>";
																	$retfun .= generateReport($GrpSID,$GrpSN,$tid,$LastEndDate,$BillEndDate,"1");
																	$retfun .= "</td></tr>";
																
																
															}										
					}
					$mydb->sql_freeresult();
			
		}
	}
	
	$mydb->sql_freeresult();


$retfun .= "<tr><td align=center class=formtitle><br><h6>TOTAL Statistic of Camintel</h6></td></tr>";

		$retfun .= "<tr><td>";
						$retfun .= generateReport($GrpSID,"Internet + Leased Line + Telephone",$tid,$LastEndDate,substr($cid,0,7),"0");
		$retfun .= "</td></tr>";
		
$retfun .= "<tr><td><br /></td></tr>";			


/*
	if($tid == 0){
			$retfun .= "<tr><td align=center class=formtitle><b>ALL LOCATIONS</b></td></tr>";	
				$sql2 = "select Distinct GrpServiceName , GrpPackage from tblLocRev
						where Convert(varchar,BillEndDate,112) = '".$cid."'
						and GrpServiceName = '".$st."'
						and PackageID in ( ".$where." )
						order by GrpPackage ";	

				if($que2 = $mydb->sql_query($sql2)){
					while($result2 = $mydb->sql_fetchrow($que2)){
						$GrpServiceName = $result2['GrpServiceName'];
						$GrpPackage = $result2['GrpPackage'];
							$retfun .= "<tr><td>";
							$retfun .= generateReport($sid,$CityID,$cid);
							$retfun .= "</td></tr>";
					}
				}
			
				$retfun .= "<tr><td>";
					$retfun .= generateReport($sid,$CityID,$cid);
				$retfun .= "</td></tr>";
	}*/
				
	$retfun .= "</table>";
	
	print $retfun;	
	
?>