<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<style>
	td, th{
		font-family:"Courier New", Courier, monospace;
		font-size:13px;
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
		
function generateReport($sid, $sn, $tid, $cid,$cid2, $cin){
	global $mydb;
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle" colspan=2>	';
		$retOut .= ' <b>&nbsp;&nbsp;'.$sn.' in '.$cin.'</b><br> ';											
	$retOut .=	' </td>					
				</tr> 
				<tr>
					<td width=10>&nbsp;</td>
					<td>
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																									
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>																
								<th align="center" width="9%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Cycle</th>	
								<th align="center" width="4%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Lines</th>									
								<th align="center" width="10%" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>MonthlyRevenue</th>
								<th align="center" width="10%" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>RevenueIncrease</th>
								<th align="center" width="8%" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>PaidAmount</th>
								<th align="center" width="12%" style="border-left:1px dotted #999999; border-top:1px solid #999999; border-right:1px solid #999999;" nowrape>UnpaidAmount</th>											
							</thead>
							<tbody>';
	
	$sql = "		Begin Try
						Drop table #tmp".$sid.$tid."
					End Try
					Begin catch
					End Catch
					
					select	identity(int,1,1) ID,
							GrpSN ServiceName,
							Convert(varchar,BillEndDate,102) BillEndDate,
							sum(Lines) Lines,
							Round(SUM(InvoiceAmount),2) InvoiceAmount,
							SUM(PaidAmount) Paid,
							SUM(UnpaidAmount) Unpaid
							into #tmp".$sid.$tid."
					from tblCustomerInvoiceRmtSum(nolock) 
					where GrpSID=".$sid."
					And Convert(varchar,BillEndDate,102) between '".$cid2."' and '".$cid."' ";

if ($tid <> 'All_Branches')
   $sql .= "			And CityID in (".$tid.") "; 


	
	$sql .= " Group by GrpSN,Convert(varchar,BillEndDate,102)
			  Having SUM(InvoiceAmount) > 0
			  Order by ServiceName,BillEndDate desc 
			  
			  
			  Select t1.*, Round(t1.InvoiceAmount - t2.InvoiceAmount,2) Increase
			  
			  from #tmp".$sid.$tid." t1 
			  left join #tmp".$sid.$tid." t2 on t1.ID = t2.ID - 1
			  
			  Drop table #tmp".$sid.$tid."
			   
			  ";

	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		$TotalLine = 0;
		$GrandInvAmt = 0.00;
		$GrandInc = 0.00;
		$GrandPaidAmt = 0.00;
		$GrandUnpaidAmt = 0.00;		
		while($result = $mydb->sql_fetchrow($que)){																															
											
			$EnterDate = $result['EnterDate'];										
			$ServiceName = $result['ServiceName'];										
			$Location = $result['Location'];										
			$BillEndDate = $result['BillEndDate'];										
			
			$Lines = $result['Lines'];
			$TotalLine += $Lines;																
			
			$InvoiceAmount = $result['InvoiceAmount'];
			$GrandInvAmt += floatval($InvoiceAmount);
			
			$Increase = $result['Increase'];
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
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$BillEndDate.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.number_format($Lines).'</td>';			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.FormatCurrency($InvoiceAmount).'</td>';
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
											<td align="center" colspan=3 style="border-left:1px solid #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">Total</td>																						
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">'.FormatCurrency($GrandInvAmt).'</td>';
											
											
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
												From ".$cid2." To ".$cid."<br />
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
			
			$retfun .= "<tr><td align=center class=formtitle><br><b>".$GrpSN."</b></td></tr>";	

					$cmd2 = "Select Name Location, ID CityID from tlkpLocation 
						where type= 2
						and ID in (Select distinct CityID from tblCustomerInvoiceRmtSum where GrpSID = ".$GrpSID.")
						and ID in (".$tid.")";
						
					if($que2 = $mydb->sql_query($cmd2)){
					while($result2 = $mydb->sql_fetchrow($que2)){	
					
																$Location = $result2['Location'];
																$CityID = $result2['CityID'];
																
																if ($GrpSID <> "4")
																{
																	$retfun .= "<tr><td>";
																	$retfun .= generateReport($GrpSID,$GrpSN,$CityID,$cid,$cid2,$Location);
																	$retfun .= "</td></tr>";
																}
																
															}										
					}
					$mydb->sql_freeresult();
					

		$retfun .= "<tr><td>";
		$retfun .= generateReport($GrpSID,$GrpSN,'All_Branches',$cid,$cid2,'All_Branches');
		$retfun .= "</td></tr>";
		$retfun .= "<tr><td><br /></td></tr>";			
					
					
		}
	}
	
	$mydb->sql_freeresult();
	
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