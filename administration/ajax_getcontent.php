<?php
	
 //error_reporting(E_ALL);
 	if($contentname=="gettimeband"){
	header('Content-Type: text/xml');
	// generate XML header
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
	
	require_once("../common/agent.php");
	
	global $mydb;
	
		$sql="select ttb.TimeID, ttb.PackageID, ttb.DayType, ttb.FromTime, 
										ttb.ToTime, ttb.TimeBandName,ttb.Status  
											from tblTarTimeBand ttb
												inner join tblTarPackage ttp
												on ttb.PackageID=ttp.PackageID
											where ttb.status=1 and ttp.status=1 and ttb.PackageID='$id'";
		$result=$mydb->sql_query($sql);
		
		echo '<options>';
		while ($row=$mydb->sql_fetchrow($result)) {
			echo '<option>'.$row["TimeID"]."-".$row["TimeBandName"].'</option>';
		}
		echo  '</options>';
 	}else{
	
	require_once("../common/agent.php");

	function GetAreaCode($bandid,$orderby,$ordertype){
		global $mydb,$myinfo,$pg;
		
		$sql="select ttcb.DistanceID as 'cbBandID', ttcb.BandName BandName, 
	   		 ttcb.Description Description, ttac.AreaCodeID, ttac.AreaCode, 
	   		 ttac.CountryCode, ttct.CallID, ttct.CallType, ttac.Description 'ccDescription'
			 from tlkpTarChargingBand ttcb, tblTarAreaCode ttac, tlkpTarCallType ttct  
			 WHERE ttcb.DistanceID=ttac.BandID and ttac.ServiceType=ttct.CallID and ttcb.DistanceID='$bandid'";
		if(strlen($orderby)>1){
			$sql.=" order by $orderby";
			$sql.=" $ordertype";
		}
		
		$ordertype=($ordertype=="asc")?"desc":"asc";		
		
		$result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1'  bordercolor='#999999' style='border-collapse:collapse'   cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";
		$blockdetailoutput.="<th><a href='#' onClick='getContent(".$bandid.",\"AreaCodeID\",\"$ordertype\");'\">ID</a></th>";
		$blockdetailoutput.="<th><a href='#' onClick='getContent(".$bandid.",\"AreaCode\",\"$ordertype\");'\">Area Code</a></th>";
		$blockdetailoutput.="<th><a href='#' onClick='getContent(".$bandid.",\"CountryCode\",\"$ordertype\");'\">Country Code</a></th>";
		$blockdetailoutput.="<th><a href='#' onClick='getContent(".$bandid.",\"CallType\",\"$ordertype\");'\">Service Type</a></th>";
	    $blockdetailoutput.="<th><a href='#' onClick='getContent(".$bandid.",\"ccDescription\",\"$ordertype\");'\">Description</a></th>";
	    $blockdetailoutput.="<th>Edit</th>";
		$blockdetailoutput.="</thead>";
		$blockdetailoutput.="<tbody>";
		$rowcount=0;
		$rowclass="";
		while($row=$mydb->sql_fetchrow($result)){
			if($rowcount%2==0){
				$rowclass="row1";
			}else{
				$rowclass="row2";
			}
			$blockdetailoutput.="<tr bgcolor='#ffffff' >";
			$blockdetailoutput.="<td align='center'>".stripslashes($row['AreaCodeID'])."</td>";
			$blockdetailoutput.="<td align='center'>".stripslashes($row['AreaCode'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['CountryCode'])."&nbsp;</td>";
			$blockdetailoutput.="<td  align='left'>".stripslashes($row['CallID'])."-".stripslashes($row['CallType'])."&nbsp;</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['ccDescription'])."&nbsp;</td>";
			$blockdetailoutput.="<td  align='center'><a href=\"./?pg=1006&amp;op=edit&amp;areacodeid=".$row['AreaCodeID']."\"><img src=\"./images/Edit.gif\" border=\"0\"></a><a href=\"javascript:ActionConfirmationDeleteAreaCode(".intval($row['AreaCodeID']).",'".$row['AreaCode']."')\"><img src=\"./images/delete.gif\" border=\"0\"></a></td>";
			$blockdetailoutput.="</tr>";
			//		$rowcount++;
		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		//blockdetailoutput.="</div>";
		return $blockdetailoutput;
		//return $sql;
	}
	
	
		function GetExceptionDetail($accid,$orderby,$ordertype){
		global $mydb,$myinfo,$pg;
		
		$sql="select AccID 'AccID', Remark 'Type', 
	   		 Dedate 'Request Date', Expire 'Expire Date',isnull(cycleid,0) cycleid,(select convert(char(10),Max(BillEndDate),120) from 
			 tblSysBillRuncycleInfo where cycleID=Ed.cycleID) 'Bill Date'
			 from tblExceptionDetail Ed WHERE accid=$accid";
			 
		if(strlen($orderby)>1){
			$sql.=" order by $orderby";
			$sql.=" $ordertype";
		}
		
		$ordertype=($ordertype=="asc")?"desc":"asc";		
		
		$result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1'  bordercolor='#999999' style='border-collapse:collapse'   cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";
		$blockdetailoutput.="<th><a href='#' onClick='getContent(".$accid.",\"AccID\",\"$ordertype\");'\">AccID</a></th>";
		$blockdetailoutput.="<th><a href='#' onClick='getContent(".$accid.",\"Remark\",\"$ordertype\");'\">Type</a></th>";
		$blockdetailoutput.="<th><a href='#' onClick='getContent(".$accid.",\"Dedate\",\"$ordertype\");'\">Request Date</a></th>";
		$blockdetailoutput.="<th><a href='#' onClick='getContent(".$accid.",\"Expire\",\"$ordertype\");'\">Expire Date</a></th>";
	    $blockdetailoutput.="<th><a href='#' onClick='getContent(".$accid.",\"cycleID\",\"$ordertype\");'\">Bill Date</a></th>";
	    $blockdetailoutput.="<th>Edit</th>";
		$blockdetailoutput.="</thead>";
		$blockdetailoutput.="<tbody>";
		$rowcount=0;
		$rowclass="";
		while($row=$mydb->sql_fetchrow($result)){
			if($rowcount%2==0){
				$rowclass="row1";
			}else{
				$rowclass="row2";
			}
			$blockdetailoutput.="<tr bgcolor='#ffffff' >";
			$blockdetailoutput.="<td align='center'>".stripslashes($row['AccID'])."</td>";
			$blockdetailoutput.="<td align='center'>".stripslashes($row['Type'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['Request Date'])."&nbsp;</td>";
			$blockdetailoutput.="<td  align='left'>".stripslashes($row['Expire Date'])."&nbsp;</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['Bill Date'])."&nbsp;</td>";
			$blockdetailoutput.="<td  align='center'><a href=\"./?pg=2223&amp;op=edit&amp;cycleid=".$row['cycleid']."&amp;accid=".$row['AccID']."\"><img src=\"./images/Edit.gif\" border=\"0\"></a><a href=\"javascript:ActionConfirmationDeleteExceptionDetail(".intval($row['AccID']).",".$row['cycleid'].",'".$row['Type']."')\"><img src=\"./images/delete.gif\" border=\"0\"></a></td>";
			$blockdetailoutput.="</tr>";
			//		$rowcount++;
		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		//blockdetailoutput.="</div>";
		return $blockdetailoutput;
		//return $sql;
	}

	
	
	function GetPackageInfo($serviceid){
	global $mydb;
$blockdetailoutput="	<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\" id=\"1\" class=\"\" bordercolor=\"#aaaaaa\"   bgcolor=\"#EFEFEF\"  style=\"border-collapse:collapse\" >
												<tr>
											  <th width=\"29\">No.</th>
													<th width=\"149\">Name</th>
													<th width=\"45\">Thresh</th>
											
													<th width=\"30\">Dep Amt</th>
													<th width=\"76\">Service</th>
													<th width=\"36\">Cycle</th>
												
													<th width=\"29\">Reg Fee </th>
													<th width=\"46\">Config Fee </th>
													<th width=\"30\">CPE Fee </th>
													<th width=\"40\">Cycle Fee</th>
													<th width=\"39\">ISDN Fee </th>
													<th width=\"52\">Special Number </th>
													<th width=\"33\">Edit</th>
												</tr> ";
										
												$sqlcon ="";
												if(isset($serviceid) && $serviceid!=0){
													$sqlcon=" and ttp.serviceid=$serviceid ";
												}
													
												$sql2="select ttp.PackageID, ttp.TarName, ttp.Threshold, ttp.DepositAmount, ".
												 	  "ts.ServiceName, tbc.[Name] CycleName, ttp.Status,ttp.RegistrationFee,ttp.ConfigurationFee,ttp.CycleFee,ttp.CPEFee,ttp.ISDNFee,ttp.SpecialNumber ".
													  "FROM tblTarPackage ttp, tlkpService ts, tlkpBillingCycle tbc ".
													  "WHERE ttp.ServiceID=ts.ServiceID AND ttp.CycleID=tbc.CycleID $sqlcon ORDER BY ttp.Status Desc";
												$query2=$mydb->sql_query($sql2);
												$rowcount=0;
												$rowclass="";
												$rowdeactiveclass="";
												while($row=$mydb->sql_fetchrow($query2))												
												{
												    if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													if($row['Status']==0){
														$rowdeactiveclass=" style='background-color:#CCCCCC'";
														$blockdetailoutput.='<tr $rowdeactiveclass>
														<td " align="center"><font color="#aaaaaa">'.$row['PackageID'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['TarName'].'</font></td>
													  <td  align="left"><font color="#aaaaaa">'.$row['Threshold'].'</font></td>
													  <td  align="left"><font color="#aaaaaa">'.$row['DepositAmount'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['ServiceName'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['CycleName'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['RegistrationFee'].'</font></td>
													<td  align="left"><font color="#aaaaaa">'.$row['ConfigurationFee'].'</font></td>
													<td  align="left"><font color="#aaaaaa">'.$row['CPEFee'].'</font></td>	
													<td  align="left"><font color="#aaaaaa">'.$row['CycleFee'].'</font></td>
													<td  align="left"><font color="#aaaaaa">'.$row['ISDNFee'].'</font></td>
													<td  align="left"><font color="#aaaaaa">'.$row['SpecialNumber'].'</font></td>';
													$blockdetailoutput.='<td align="center"><a href="javascript:ActionConfirmationActivate('.$row['PackageID'].',\''.$row['TarName'].'\')"  text="Activate"><img src="./images/icon/admin.gif" border="0"></a></td>';
													$blockdetailoutput.='</tr>';
													}else{
														
													$blockdetailoutput.='<tr>
														<td class="'.$rowclass.'" align="center">'.$row['PackageID'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['TarName'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['Threshold'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['DepositAmount'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['ServiceName'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['CycleName'].'</td>
														<td  class="'.$rowclass.'" align="left">'.$row['RegistrationFee'].'</td>
													<td  class="'.$rowclass.'" align="left">'.$row['ConfigurationFee'].'</td>
													<td  class="'.$rowclass.'" align="left">'.$row['CPEFee'].'</td>	
													<td  class="'.$rowclass.'" align="left">'.$row['CycleFee'].'</td>
													<td  class="'.$rowclass.'" align="left">'.$row['ISDNFee'].'</td>
													<td  class="'.$rowclass.'" align="left">'.$row['SpecialNumber'].'</td>
													<td class="'.$rowclass.'" align="right"><a href="./?pg=1011&amp;op=edit&amp;packageid='.$row['PackageID'].'&amp;sService='.$serviceid.'"><img src="./images/Edit.gif" border="0"></a>&nbsp;<a href="javascript:ActionConfirmation('.$row['PackageID'].',\''.$row['TarName'].'\')"><img src="./images/Delete.gif" border="0"></a></td>
													</tr>';
													}
													
													$rowcount++;
												 }
											
						$blockdetailoutput.= "  </table>	";
						return $blockdetailoutput;
	}
	function GetChargeBlockDetail($blockid){

		global $mydb,$myinfo;
	//	$blockdetailoutput="<div id='block".$blockid."' style='display:none;'>";
		
		$sql="select ttcb.BlockID 'ttcbBlockID', ttcb.BlockName 'BlockName', ".
	   		 "ttcbd.BlockDetailID, ttcbd.FromDuration, ttcbd.ToDuration, ttcbd.Unit ".
		     "FROM tlkpTarChargeBlock ttcb, tblTarChargeBlockDetail ttcbd ".
		     "WHERE ttcb.BlockID=ttcbd.BlockID AND ttcb.BlockID='$blockid' ".
			 "ORDER BY 1,3 ";
		//echo $sql;
			$result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";
		$blockdetailoutput.="<th>From Duration</th>";
		$blockdetailoutput.="<th>To Duration</th>";
		$blockdetailoutput.="<th>Unit</th>";
		//$blockdetailoutput.="<th>Edit</th>";
		$blockdetailoutput.="</thead>";
		$blockdetailoutput.="<tbody>";
		$rowcount=0;
		$rowclass="";
		while($row=$mydb->sql_fetchrow($result)){
			if($rowcount%2==0){
				$rowclass="row1";
			}else{
				$rowclass="row2";
			}
			$blockdetailoutput.="<tr  bgcolor='#ffffff' >";
			$blockdetailoutput.="<td align='center'>".floatval($row['FromDuration'])."</td>";
			$blockdetailoutput.="<td  align='center'>".floatval($row['ToDuration'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['Unit'])."</td>";
			$blockdetailoutput.="</tr>";

		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
	//	$blockdetailoutput.="</div>";
		return $blockdetailoutput;
	}
	
	function GetTimeBandDetail($blockid){
		global $mydb,$myinfo;
		//$blockdetailoutput="<div id='block".$blockid."' style='display:none;'>";
		
		$sql2="select tttb.TimeID,tttb.TimeBandName, tttb.FromTime, tttb.ToTime, ".
													  "ttdt.DayType, tttb.Status, ttp.TarName ".
													  "FROM tblTarTimeBand tttb, tlkpTarDayType ttdt, tblTarPackage ttp ".
													  "WHERE tttb.DayType=ttdt.DayTypeID and tttb.PackageID=ttp.PackageID ".
													  "and tttb.PackageID='".$blockid."' ORDER BY Status DESC";

		//echo $sql2;
			$result=$mydb->sql_query($sql2);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";
		$blockdetailoutput.="<th>ID</th>";
		$blockdetailoutput.="<th>Time Band</th>";
		//$blockdetailoutput.="<th>Package </th>";
		$blockdetailoutput.="<th>Day Type </th>";
		$blockdetailoutput.="<th>From Time</th>";
		$blockdetailoutput.="<th>To Time</th>";
		$blockdetailoutput.="<th>Edit</th>";
		//$blockdetailoutput.="<th>Edit</th>";
		$blockdetailoutput.="</thead>";
		$blockdetailoutput.="<tbody>";
		$rowcount=0;
		$rowclass="";
		while($row=$mydb->sql_fetchrow($result)){
			if($rowcount%2==0){
				$rowclass="row1";
			}else{
				$rowclass="row2";
			}
			$blockdetailoutput.="<tr  bgcolor='#ffffff' >";
			$blockdetailoutput.="<td align='center'>".$row['TimeID']."</td>";

			$blockdetailoutput.="<td align='center'>".$row['TimeBandName']."</td>";
			//$blockdetailoutput.="<td align='center'>".$row['TarName']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['DayType']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['FromTime']."</td>";
			$blockdetailoutput.="<td  align='center'>".$row['ToTime']."</td>";
			$blockdetailoutput.="<td  align='center'><a href=\"./?pg=1007&amp;op=edit&amp;timeid=".$row['TimeID']."\"><img src=\"./images/Edit.gif\" border=\"0\"></a>&nbsp;<a href=\"javascript:ActionConfirmation('".$row['TimeID']."','".$row['TimeBandName']."')\"><img src=\"./images/Delete.gif\" border=\"0\"></a>									
														</td>";
			$blockdetailoutput.="</tr>";

		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
	//	$blockdetailoutput.="</div>";
		return $blockdetailoutput;
	}
		
	function GetTimeBandDetailIsp($blockid){
		global $mydb,$myinfo;
		//$blockdetailoutput="<div id='block".$blockid."' style='display:none;'>";
		
		$sql2="select tttb.TimeID,tttb.TimeBandName, tttb.FromTime, tttb.ToTime, ".
													  "ttdt.DayType, tttb.Status, ttp.TarName ".
													  "FROM tblTarTimeBand tttb, tlkpTarDayType ttdt, tblTarPackage ttp ".
													  "WHERE tttb.DayType=ttdt.DayTypeID and tttb.PackageID=ttp.PackageID ".
													  "and tttb.PackageID='".$blockid."' ORDER BY Status DESC";

		//echo $sql2;
			$result=$mydb->sql_query($sql2);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";
		$blockdetailoutput.="<th>ID</th>";
		$blockdetailoutput.="<th>Time Band</th>";
		//$blockdetailoutput.="<th>Package </th>";
		$blockdetailoutput.="<th>Day Type </th>";
		$blockdetailoutput.="<th>From Time</th>";
		$blockdetailoutput.="<th>To Time</th>";
		$blockdetailoutput.="<th>Edit</th>";
		//$blockdetailoutput.="<th>Edit</th>";
		$blockdetailoutput.="</thead>";
		$blockdetailoutput.="<tbody>";
		$rowcount=0;
		$rowclass="";
		while($row=$mydb->sql_fetchrow($result)){
			if($rowcount%2==0){
				$rowclass="row1";
			}else{
				$rowclass="row2";
			}
			$blockdetailoutput.="<tr  bgcolor='#ffffff' >";
			$blockdetailoutput.="<td align='center'>".$row['TimeID']."</td>";

			$blockdetailoutput.="<td align='center'>".$row['TimeBandName']."</td>";
			//$blockdetailoutput.="<td align='center'>".$row['TarName']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['DayType']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['FromTime']."</td>";
			$blockdetailoutput.="<td  align='center'>".$row['ToTime']."</td>";
			$blockdetailoutput.="<td  align='center'><a href=\"./?pg=1025&amp;op=edit&amp;timeid=".$row['TimeID']."\"><img src=\"./images/Edit.gif\" border=\"0\"></a>&nbsp;<a href=\"javascript:ActionConfirmation('".$row['TimeID']."','".$row['TimeBandName']."')\"><img src=\"./images/Delete.gif\" border=\"0\"></a>									
														</td>";
			$blockdetailoutput.="</tr>";

		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
	//	$blockdetailoutput.="</div>";
		return $blockdetailoutput;
	}
	function GetTariffDetail($packageid,$orderby,$ordertype){
		
		global $mydb,$myinfo;
		//$blockdetailoutput="<div id='block".$packageid."' style='display:none;'>";
		
		$sql="select tt.TarID, tt.Rate, tt.EffectiveDate, 
				tt.Description 'tDescription', ttp.TarName,  
				ttcb.BandName, ttgw.GateCode, tttb.TimeBandName,  
				'' 'ServiceName', 
				case 
				when (tt.EndDate is null or tt.EndDate >= GetDate()) then 1  
				else  
				0 
				end 'Effective',tttb.TimeID,tttb.PackageID, cb.BlockName
				from (tblTariff tt  
					  left join (tblTarTimeBand tttb   
									left join tblTarPackage ttp  
									on tttb.PackageID=ttp.PackageID)  
								on tt.TimeID=tttb.TimeID)  
					  left join tlkpTarGateWay ttgw  
								on tt.GateID=ttgw.GateID  
					  left join tlkpTarChargingBand ttcb  
								on tt.DistanceID=ttcb.DistanceID 
					  left join tlkpTarChargeBlock cb
								on tt.BlockID=cb.BlockID
				where tttb.PackageID='$packageid' and ttcb.Status=
				'1'  ";
			if(strlen($orderby)>1){
			$sql.=" order by $orderby";
			$sql.=" $ordertype";
			}else{
				$sql.=" order by Effective desc, ttp.PackageID, tt.DistanceID desc";
				//$ordertype="asc";
			}
			
		$ordertype=($ordertype=="asc")?"desc":"asc";		
		
				// order by Effective desc, ttp.PackageID, tt.DistanceID desc";
		
		$result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead><th><a href='#' onClick='getContent(".$packageid.",\"TarID\",\"$ordertype\");'\">ID</a> </th>
													
													<th nowrap='nowrap'><a href='#' onClick='getContent(".$packageid.",\"BandName\",\"$ordertype\");'\">Distance</a> </th>
													<th><a href='#' onClick='getContent(".$packageid.",\"GateCode\",\"$ordertype\");'\">Gate Way</a> </th>
													<th nowrap='nowrap'><a href='#' onClick='getContent(".$packageid.",\"TimeBandName\",\"$ordertype\");'\">Time Band</a> </th>																						<th nowrap='nowrap'><a href='#' onClick='getContent(".$packageid.",\"BlockName\",\"$ordertype\");'\">Round Up </a> </th>
													
													<th><a href='#' onClick='getContent(".$packageid.",\"Rate\",\"$ordertype\");'\">Rate(Cent)</a></th>
													<th nowrap='nowrap'><a href='#' onClick='getContent(".$packageid.",\"Effective\",\"$ordertype\");'\">Effective Date</a></th>
												
													<th>Edit</th>												
												</tr>";
												
		$blockdetailoutput.="</thead>";
		$blockdetailoutput.="<tbody>";
		$rowcount=0;
		$rowclass="";
		while($row=$mydb->sql_fetchrow($result)){
			if($rowcount%2==0){
				$rowclass="row1";
			}else{
				$rowclass="row2";
			}
			if(intval($row["Effective"])==1){
			$blockdetailoutput.="<tr  bgcolor='#ffffff' >";
			$blockdetailoutput.="<td align='center'>".$row['TarID']."</td>";
			$blockdetailoutput.="<td align='left'>".$row['BandName']."</td>";
			$blockdetailoutput.="<td align='left'>".$row['GateCode']."</td>";
			$blockdetailoutput.="<td align='left'>".$row['TimeBandName']."</td>";			
			$blockdetailoutput.="<td align='left'>".$row['BlockName']."</td>";
		
			$blockdetailoutput.="<td align='left'>".$row['Rate']."</td>";
			$blockdetailoutput.="<td align='left'>".$row['EffectiveDate']."</td>";
		
			$blockdetailoutput.="<td  align='left'><a href=\"./?pg=1012&amp;op=edit&amp;tarid=".$row['TarID']."\"><img src=\"./images/Edit.gif\" border=\"0\"></a>&nbsp;<a href='javascript:ActionConfirmation(".$row['TarID'].",\"\")'><img src=\"./images/delete.gif\" border=\"0\"></a></td>
												";
			$blockdetailoutput.="</tr>";
			}else{
				
			$blockdetailoutput.="<tr  bgcolor='#aaaaaa' >";
			$blockdetailoutput.="<td align='center'>".$row['TarID']."</td>";
			$blockdetailoutput.="<td align='left'>".$row['BandName']."</td>";
			$blockdetailoutput.="<td align='left'>".$row['GateCode']."</td>";
			$blockdetailoutput.="<td align='left'>".$row['TimeBandName']."</td>";
			$blockdetailoutput.="<td align='left'>".$row['BlockName']."</td>";
			$blockdetailoutput.="<td align='left'>".$row['Rate']."</td>";
			$blockdetailoutput.="<td align='left'>".$row['EffectiveDate']."</td>";
			$blockdetailoutput.="<td  align='left'><a href='javascript:ActionConfirmationActivate(".$row['TarID'].",\"\")'><img src='./images/icon/admin.gif' border='0'></a></td>";							
			$blockdetailoutput.="</tr>";
			}

		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		//$blockdetailoutput.="</div>";
		return $blockdetailoutput;
	}
	function GetIspTariffDetail($packageid){
		
		global $mydb,$myinfo;
		//$blockdetailoutput="<div id='block".$packageid."' style='display:none;'>";
		
		$sql="select it.TarID,it.FreeUsage,it.Description,it.EffectiveDate ,
				Case when it.EndDate < GetDate() or it.EffectiveDate > GetDate() then
					0
				else 
					1
				end Effective
				,
				it.EndDate, ocb.OverChargeBlockName 
				from tblIspTariff it 
					left join tblTarPackage tp
					on it.PackageID=tp.PackageID
					left join tblIspOverChargeBlock ocb
					on it.OverChargeBlockID=ocb.OverChargeBlockID
				where tp.status='1' and tp.PackageID='".$packageid."'";
		//$blockdetailoutput=$sql;
		//print $sql;
			$result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead><th>ID</th>
																								
													<th>Over Charge Block</th>
													<th>Free Usage</th>
													<th>Description</th>
													<th>Effective Date</th>
													<th>End Date</th>
													<th>Edit</th>												
												</tr>";
												
		$blockdetailoutput.="</thead>";
		$blockdetailoutput.="<tbody>";
		$rowcount=0;
		$rowclass="";
		while($row=$mydb->sql_fetchrow($result)){
			if($rowcount%2==0){
				$rowclass="row1";
			}else{
				$rowclass="row2";
			}
			if(intval($row["Effective"])==1){
			$blockdetailoutput.="<tr  bgcolor='#ffffff' >";
			$blockdetailoutput.="<td align='center'>".$row['TarID']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['OverChargeBlockName']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['FreeUsage']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['Description']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['EffectiveDate']."</td>";	
			$blockdetailoutput.="<td align='center'>".$row['EndDate']."</td>";
			$blockdetailoutput.="<td  align='center'><a href=\"./?pg=1022&amp;op=edit&amp;tarid=".$row['TarID']."\"><img src=\"./images/Edit.gif\" border=\"0\"></a></td>";
			$blockdetailoutput.="</tr>";
			}else{
				
			$blockdetailoutput.="<tr  bgcolor='#aaaaaa' >";
						$blockdetailoutput.="<td align='center'>".$row['TarID']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['OverChargeBlockName']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['FreeUsage']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['Description']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['EffectiveDate']."</td>";	
			$blockdetailoutput.="<td align='center'>".$row['EndDate']."</td>";	$blockdetailoutput.="<td  align='center'></td>";
			$blockdetailoutput.="</tr>";
			}

		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		//$blockdetailoutput.="</div>";
		return $blockdetailoutput;
	}
	
	function GetDestDiscountDetail($desdiscid){
		global $mydb,$myinfo;
		$blockdetailoutput="<div id='dest".$desdiscid."' style='display:none;'>";
		
		$sql="select * 
			  from tlkpEventDiscDestSpecific tedds 
						  left join tlkpTarChargingBand ttcb 
							on tedds.DistanceBandID=ttcb.DistanceID 
						  left join tblTarTimeBand tttb 
							on tedds.TimeID=tttb.TimeID  
			  WHERE tedds.DiscDestID='$desdiscid' ";
			 
		//echo $sql;
	  $result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";
		$blockdetailoutput.="<th>Distance Band</th>";
		$blockdetailoutput.="<th>TimeBand</th>";
		$blockdetailoutput.="<th>From Duration</th>";
		$blockdetailoutput.="<th>To Duration</th>";
		$blockdetailoutput.="<th>Discount Rate</th>";
		$blockdetailoutput.="<th>Is %</th>";
		$blockdetailoutput.="</thead>";
		$blockdetailoutput.="<tbody>";
		$rowcount=0;
		$rowclass="";
		while($row=$mydb->sql_fetchrow($result)){
			if($rowcount%2==0){
				$rowclass="row1";
			}else{
				$rowclass="row2";
			}
			$blockdetailoutput.="<tr  bgcolor='#ffffff' >";
			$blockdetailoutput.="<td align='center'>".stripslashes($row['BandName'])."</td>";
			$blockdetailoutput.="<td  align='center'>".$row['TimeBandName']."</td>";
			$blockdetailoutput.="<td  align='center'>".intval($row['FromDur'])."</td>";
			$blockdetailoutput.="<td  align='center'>".intval($row['ToDur'])."</td>";
			$blockdetailoutput.="<td  align='center'>".doubleval($row['DiscRate'])."</td>";
			$blockdetailoutput.="<td  align='center'>".(intval($row['IsPercentage'])==1?"Yes":"No")."</td>";
			$blockdetailoutput.="</tr>";
		
		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		$blockdetailoutput.="</div>";
		return $blockdetailoutput;
	}
	
	function GetFreeCallDiscountDetail($hid){
		global $mydb,$myinfo;
		
		$blockdetailoutput="<div id='dest".$hid."' style='display:none;'>";
		
		$sql="select * from tlkpEventDiscFreeCallAllowanceDuration   
			  WHERE DiscFreeAllowID='$hid' ";
			 
	//	echo $sql;
	  $result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";
		$blockdetailoutput.="<th>Description</th>";
		$blockdetailoutput.="<th>From Duration</th>";
		$blockdetailoutput.="<th>To Duration</th>";
		$blockdetailoutput.="</thead>";
		$blockdetailoutput.="<tbody>";
		$rowcount=0;
		$rowclass="";
		while($row=$mydb->sql_fetchrow($result)){
			if($rowcount%2==0){
				$rowclass="row1";
			}else{
				$rowclass="row2";
			}
			$blockdetailoutput.="<tr  bgcolor='#ffffff' >";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['Description'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['FromDur'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['ToDur'])."</td>";
			$blockdetailoutput.="</tr>";
		
		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		
		///-------------
		$blockdetailoutput.="<br>";
		$sql="select ttcb.BandName, tttb.TimeBandName, ttdt.DayType 'DayType',tttb.FromTime, tttb.ToTime
				from (tlkpEventDiscFreeCallAllowance tedfca
						left join tblTarTimeBand tttb
						on tedfca.TimeID=tttb.TimeID)
						left join tlkpTarDayType ttdt
						on tttb.DayType=ttdt.DayTypeID
						left join tlkpTarChargingBand ttcb
						on tedfca.DistanceID=ttcb.DistanceID  
			  WHERE tedfca.DiscFreeAllowID='$hid';";
			 
		//echo $sql;
	  $result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";
		$blockdetailoutput.="<th>Distance Band</th>";
		$blockdetailoutput.="<th>TimeBand</th>";
		$blockdetailoutput.="<th>From Time</th>";
		$blockdetailoutput.="<th>To Time</th>";
		$blockdetailoutput.="<th>Day Type</th>";
		$blockdetailoutput.="</thead>";
		$blockdetailoutput.="<tbody>";
		$rowcount=0;
		$rowclass="";
		while($row=$mydb->sql_fetchrow($result)){
			if($rowcount%2==0){
				$rowclass="row1";
			}else{
				$rowclass="row2";
			}
			$blockdetailoutput.="<tr  bgcolor='#ffffff' >";
			$blockdetailoutput.="<td align='center'>".stripslashes($row['BandName'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['TimeBandName'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['FromTime'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['ToTime'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['DayType'])."</td>";
		
			$blockdetailoutput.="</tr>";
		
		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		
		
		
		
		$blockdetailoutput.="</div>";
		return $blockdetailoutput;
	}
	
	function GetBillingDate($cycleid){
		global $mydb,$myinfo;
		$blockdetailoutput="";
		
		$sql="select tbc.CycleID bcCycleID, tbc.[Name]  , tbd.BillingDateID, tbd.[Day] ".
			 "FROM tlkpBillingCycle tbc, tlkpBillingDate tbd ".
			 "WHERE tbc.CycleID=tbd.CycleID AND tbc.CycleID='".$cycleid."'";
		
		$result=$mydb->sql_query($sql);
		
		$rowcount=0;
		$rowclass="";
		while($row=$mydb->sql_fetchrow($result)){
			
			$blockdetailoutput.=stripslashes($row['Day']). " and ";
		
				//	$rowcount++;
		}
		if(strlen($blockdetailoutput)>0){
			$blockdetailoutput=substr($blockdetailoutput,0,strlen($blockdetailoutput)-4);
		}
		return $blockdetailoutput;
	}
	
	function GetTimeBandXmlInfo($packageid){
		global $mydb,$myinfo;
		$sql="select ttb.TimeID, ttb.PackageID, ttb.DayType, ttb.FromTime, 
										ttb.ToTime, ttb.TimeBandName,ttb.Status  
											from tblTarTimeBand ttb
												inner join tblTarPackage ttp
												on ttb.PackageID=ttp.PackageID
											where ttb.status=1 and ttp.status=1 and ttb.PackageID='$packageid'";
		$result=$mydb->sql_query($sql);
		
		echo '<options>';
		while ($row=$mydb->sql_fetchrow($result)) {
		echo '<option>'.$row["TimeID"]."-".$row["TimeBandName"].'</option>';
		}
		echo  '</options>';
	
	}
	
	
	switch($contentname){
		
		case "chargeblock": echo GetChargeBlockDetail($id);break;
		case "areacode": echo GetAreaCode($id, $orderby,$ordertype);break;
		case "timeband": echo GetTimeBandDetail($id);break;
		case "timebandisp": echo GetTimeBandDetailIsp($id);break;
		case "tariff": echo GetTariffDetail($id, $orderby,$ordertype);break;
		case "isptariff": echo GetIspTariffDetail($id);break;	
		case "package": echo GetPackageInfo($id);break;
		case "exception": echo GetExceptionDetail($id, $orderby,$ordertype);break;
	}
 	}
?>