<?php
	
	$cid = $_GET['cid'];
	$aid = $_GET['aid'];
	$yid = $_GET['yid'];
	
	$filename = "usage_".$cid;
	if(intval($aid) != 0)
		$filename .= '_'.$aid;
	else
		$filename .= '_all_accounts';
	if ($type == 'csv') {
			$filename  .= '.xls';
			//$mime_type = 'text/comma-separated-values';		
			$mime_type = 'application/vnd.ms-excel';		
	} elseif ($type == 'xls') {
			$filename  .= '.xls';
			$mime_type = 'application/vnd.ms-excel';
	} elseif ($type == 'xml') {
			$filename  .= '.xml';
			$mime_type = 'text/xml';	
	} elseif ($type == 'word') {
			$filename  .= '.doc';
			$mime_type = 'application/vnd.ms-word';		
	} elseif ($type == 'pdf') {
			$filename  .= '.pdf';
			$mime_type = 'application/pdf';
	}
	
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="' . $filename . '"');		
	require_once("../common/agent.php");
	require_once("../common/functions.php");
	function Usage($AccountID, $AccountName, $CycleID){
		global $mydb, $myinfo;
		$out = "Telephone: ".$AccountName."\r\n";
		$out .= "No. \t";
		$out .= "Call Time \t";
		$out .= "Call From \t";
		$out .= "Call To \t";
		$out .= "Duration \t";
		$out .= "Amount \t";		
				
		if(intval($CycleID) > 0){
			$sql = "SELECT CallerNumber, ConnectedNumber, CallEndTime, Duration, Amount 
							FROM getBillableCalls ('".$AccountName."', NULL, NULL, NULL, NULL, $CycleID)
							ORDER BY CallEndTime";
		}else{
			$sql = "SELECT CallerNumber, ConnectedNumber, CallEndTime, Duration, Amount 
							FROM getBillableCalls ('".$AccountName."', NULL, NULL, NULL, NULL, NULL)
							ORDER BY CallEndTime";
		}

		$intLoop = 1;
		if($que = $mydb->sql_query($sql)){
			while($result = $mydb->sql_fetchrow($que)){
				$CallerNumber = $result['CallerNumber'];
				$ConnectedNumber = $result['ConnectedNumber'];
				$CallEndTime = $result['CallEndTime'];
				$Duration = $result['Duration'];
				$Amount = $result['Amount'];
				$out .= "\r\n";
				$out .= $intLoop."\t";
				$out .= formatDate($CallEndTime, 7)."\t";
				$out .= $CallerNumber."\t";
				$out .= $ConnectedNumber."\t";
				$out .= FormatHour($Duration)."\t";
				$out .= FormatCurrency($Amount, "$", 3)."\t";
				
				$intLoop ++;
			}
		}										
		return $out;				 
	}
?>	
	
		<?php
			//if(intval($yid) > 0){
				$sql = "SELECT AccID, UserName FROM tblCustProduct WHERE CustID=$cid ";
				if(intval($aid) != 0)
					$sql .= "AND AccID=".$aid;
	
				if($que1 = $mydb->sql_query($sql)){
					while($result = $mydb->sql_fetchrow($que1)){
						$AccID = $result['AccID'];
						$UserName = $result['UserName'];
											
						print Usage($AccID, $UserName, $yid);
						print "\r\n\r\n";
					}
				}
			//}else{
//					$sql = "SELECT ac.AccID, ac.UserName, MIN(info.CycleID) as 'Cycle' 
//									FROM tblCustProduct ac inner join tblSysBillRunCycleInfo info on info.PackageID = ac.PackageID
//									WHERE info.BillProcessed = 1 and ac.CustID=$cid ";
//					if(intval($aid) != 0)
//						$sql .= "AND AccID=".$aid;
//					$sql .= "Group BY ac.AccID, ac.UserName";
//					if($que1 = $mydb->sql_query($sql)){
//						while($result = $mydb->sql_fetchrow($que1)){
//							$AccID = $result['AccID'];
//							$UserName = $result['UserName'];
//							$yid = $result['Cycle'];					
//							print Usage($AccID, $UserName, $yid)."\r\n\n";
//						}
//					}
//				}	
			
			$mydb->sql_freeresult();
						
		die;
		exit;
		?>			
			