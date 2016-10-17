<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	require_once("../common/configs.php");	
	$cid = $_GET['cid'];	
	$aid = $_GET['aid'];
	$yid = $_GET['yid'];
	
	//http://billingserver:8080/WebInvoice/CallDetail.aspx?InvoiceID=&SearchOption=-1,1,2,21&AccountNo=2311127&BillEndDate=20070831&InvType=1&ServiceId=2
	
	function BuildUsage($cid, $AccountID, $AccountName, $CycleID){		
		global $mydb, $myinfo, $WebQuickBillRoot;	
			if(intval($CycleID) == 0){		
			
				#____ get current cycle
				$sql = "SELECT MaxTrackID 
								FROM tblCustProduct
								WHERE AccID = $AccountID
									";
				$que = $mydb->sql_query($sql);
				$result = $mydb->sql_fetchrow($que);
				$MaxTrackID = $result['MaxTrackID'];
			
			
				#____ get current cycle
				$sql = "SELECT TOP 1 c.CycleID,c.BillEndDate 
								FROM tblSysBillRunCycleInfo c, tblCustProduct a
								WHERE c.PackageID = a.PackageID
									AND a.AccID = $AccountID
									AND c.BillProcessed = 0
									ORDER BY BillEndDate ASC";
				$que = $mydb->sql_query($sql);
				$result = $mydb->sql_fetchrow($que);
				$CycleID = $result['CycleID'];
				$strBillEndDate=$result['BillEndDate'];
				
				$timestamp = strtotime($strBillEndDate);		
				$strBillEndDate = date("Y.m.d",$timestamp);
		
				$mydb->sql_freeresult($que);
				
				$sql = "SELECT Description, Sum(BillUnit) as 'Duration', Sum(Amount) as 'Money'
								FROM tblBillingSummaryTmp 
								WHERE AccID = $AccountID AND BillingCycleID = $CycleID  
								GROUP BY Description
								/* UNION 
								SELECT Description, Sum(BillUnit) as 'Duration', Sum(Amount) as 'Money'
								FROM tblCustomerInvoiceDetail
								WHERE AccID = $AccountID AND BillingCycleID = $CycleID
								GROUP BY Description */";
			}else{
				$sql = "SELECT Description, Sum(BillUnit) as 'Duration', Sum(Amount) as 'Money'
								FROM tblcustomerinvoicedetail 
								WHERE AccID = $AccountID AND BillingCycleID = $CycleID 
								GROUP BY Description";
			}
			$CallType = "\'Mobile\',\'LDC\',\'LOC\',\'IDD\'";
			if($que = $mydb->sql_query($sql)){
				$UsageUI = '
							<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
								<tr>
									<td align="left" class="formtitle">Account name: <b>'.$AccountName.'</b></td>
									<td align="right" class="formtitle">[
										<!--<a href = "#" onclick="window.open(\''.$WebQuickBillRoot.'PrintingDetailbyAccID.aspx?TrackID='.$MaxTrackID.'&BillEndDate='.$strBillEndDate.'&CallType='.$CallType.'&InvType=1&ServiceId=2\');">Detail</a>-->]</td>										
								</tr>
								<tr>
									<td valign="top" colspan=2>
										<table border="1" cellpadding="3" cellspacing="0" width="100%" id="2" class="sortable" bordercolor="#aaaaaa">
											<thead>
												<th>No.</th>
												<th>Description</th>
												<td>Duration</td>
												<th>Amount</th>													
											</thead>
											<tbody>												
									';
					$iLoop = 0;			
					$totalDuration = 0;
					$totalAmount = 0.00;	
					while($result = $mydb->sql_fetchrow($que)){
						$Description = $result['Description'];
						$Duration = $result['Duration'];
						$totalDuration += intval($Duration);
						$Money = $result['Money'];
						$totalAmount += floatval($Money);
						$iLoop ++;
						if(($iLoop % 2) == 0)
							$style = "row1";
						else
							$style = "row2";
						$UsageUI .= '<tr>';	
						$UsageUI .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
						$UsageUI .= '<td class="'.$style.'" align="left">'.$Description.'</td>';
						$UsageUI .= '<td class="'.$style.'" align="right">'.FormatHour($Duration).'</td>';
						$UsageUI .= '<td class="'.$style.'" align="right">'.FormatCurrency($Money).'</td>';	
						$UsageUI .= '</tr>';	
					}				
				$UsageUI .= '		</tbody>	
												<tfoot>
													<tr>
														<td align="right" colspan=2><b>Total</b></td>
														<td align="right"><b>'.FormatHour($totalDuration).'</b></td>
														<td align="right"><b>'.FormatCurrency($totalAmount).'</b></td>
													</tr>
												</tfoot>
											</table>
										</td>
									</tr>
								</table>';
			}else{
				$error = $mydb->sql_error();
				$UsageUI = $myinfo->error("Failed to gat account usage information", $error['message']);
			}		
		return $UsageUI;
	}
	
	//if(intval($yid) == 0){ 
//		$sql = "SELECT AccID, UserName 
//						FROM tblCustProduct
//						WHERE CustID=$cid ";					
//		
//		if(!empty($aid))
//			$sql .= "AND AccID=".$aid; 		
//
//		if($que1 = $mydb->sql_query($sql)){
//			while($result = $mydb->sql_fetchrow($que1)){
//				$AccID = $result['AccID'];
//				$UserName = $result['UserName'];
//				if(!is_null($AccID))						
//				print "<table border='0' cellpadding='0' cellspacing='0' width='100%'>																
//								<tr><td>".BuildUsage($cid, $AccID, $UserName, $yid)."</td></tr>
//								<tr><td>&nbsp;</td></tr>
//							</table>
//					";
//			}
//		}
//	}else{
		$sql = "SELECT AccID, UserName FROM tblCustProduct WHERE CustID=$cid ";
		if(intval($aid) != 0)
			$sql .= "AND AccID=".$aid;

		if($que1 = $mydb->sql_query($sql)){
			while($result = $mydb->sql_fetchrow($que1)){
				$AccID = $result['AccID'];
				$UserName = $result['UserName'];
									
				print "<table border='0' cellpadding='0' cellspacing='0' width='100%'>																
									<tr><td>".BuildUsage($cid, $AccID, $UserName, $yid)."</td></tr>
									<tr><td>&nbsp;</td></tr>
								</table>
					";
			}	
		//}
	}
	$mydb->sql_freeresult();
		
?>
