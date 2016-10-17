<!--
	*
	* This code is not to be distributed without the written permission of BRC Technology.
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a> 
	* 
-->
<?php
	
   // error_reporting(E_ALL);
	require_once("./common/agent.php");	
	//require_once("function.php");
	require_once("./common/functions.php");
	$gl_action=Array(1=>"Active",2=>"Bar",3=>"Close");
	$gl_action_sub=Array(1=>"Income",2=>"Outgoing(Loc+Int)",3=>"Outgoing(Int)");
	   
	function CheckExistsGateCode($GateCode,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "Select * FROM tlkpTarGateWay WHERE GateCode='".stripcslashes(trim($GateCode))."' and status='1' ";
		if($NotOwnSelf==true){
			$sql.=" and GateID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	function CheckExistOverChargeBlock($BlockName,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "Select * FROM tblIspOverChargeBlock WHERE OverChargeBlockName='".stripcslashes(trim($BlockName))."' and status='1' ";
		if($NotOwnSelf==true){
			$sql.=" and OverChargeBlockID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	
	function CheckExistingCreditRuleLimit($RuleName,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "Select * FROM tblCreditLimitRules WHERE CredName='".stripcslashes(trim($RuleName))."' and status='1' ";
		if($NotOwnSelf==true){
			$sql.=" and CredID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	function CheckExistingCreditRuleInvoice($RuleName,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "Select * FROM tblCreditRuleInvoice WHERE CreditRuleInvoice='".stripcslashes(trim($RuleName))."' and status='1' ";
		if($NotOwnSelf==true){
			$sql.=" and CredID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	function CheckExistTariffDiscount($PackageID,$DiscountID,$DiscType,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "Select * FROM trelTarDisc WHERE PackageID='".$PackageID."' and DiscID='".$DiscType."' and DiscType='".$DiscType."' and EndDate IS NULL ";
		if($NotOwnSelf==true){
			$sql.=" and TarID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
		function CheckExistMinuteBaseTariffDiscount($PackageID,$DiscountID,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "Select * FROM trelCycleDiscMinuteBase WHERE PackageID='".$PackageID."' and DiscID='".$DiscType."' ";
		if($NotOwnSelf==true){
			$sql.=" and TarDiscID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	
	function CheckExistCreditRuleTariff($PackageID,$CredID,$CredType,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "Select * FROM trelTarCredit WHERE PackageID='".$PackageID."' and CredID='".$CredID."' and CredType='".$CredType."' ";
		if($NotOwnSelf==true){
			$sql.=" and ID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	
	function CheckExistsRecuringCharge($RecRate,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "Select * FROM tblRecuringCharges WHERE RecRate='".stripcslashes(trim($RecRate))."' ";
		if($NotOwnSelf==true){
			$sql.=" and RecChargeID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	
	function CheckFnfDiscount($RecChargeID,$Rate,$IsPercentage,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "SELECT * FROM tblEventDiscFnF WHERE RecChargeID='$RecChargeID' and".	
			    "DiscRate='$Rate' and IsPercentage='$IsPercentage' and ".
				"IsActivate='1' ";
		if($NotOwnSelf==true){
			$sql.=" and DiscFnFID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	
	function CheckDestinationDiscount($RecChargeID,$txtDescription,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "SELECT * FROM tblEventDiscDestSpecific WHERE RecChargeID='$RecChargeID' and ".	
			    "Description='$txtDescription' and ".
				"IsActive='1' ";
				//print $sql;
		if($NotOwnSelf==true){
			$sql.=" and DiscDestID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	
	
	function CheckFreeCallDiscount($RecChargeID,$txtDescription,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "SELECT * FROM tblEventDiscFreeCallAllowance WHERE RecChargeID='$RecChargeID' and ".	
			    "Description='$txtDescription' and ".
				"IsActive='1' ";
				//print $sql;
		if($NotOwnSelf==true){
			$sql.=" and DiscFreeAllowID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	function CheckCallAllowDiscount($RecChargeID,$txtDescription,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "SELECT * FROM tblEventDiscCallAllowance WHERE RecChargeID='$RecChargeID' and ".	
			    "Description='$txtDescription' and ".
				"IsActive='1' ";
				//print $sql;
		if($NotOwnSelf==true){
			$sql.=" and DiscAllowID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	
	function CheckCallMinuteDiscountDiscount($txtDescription,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "select * from tblCycleDiscMinuteBase WHERE ".	
			    "Description='$txtDescription' and ".
				"IsActive='1' ";
				//print $sql;
		if($NotOwnSelf==true){
			$sql.=" and DiscID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	
	function CheckExistsWeekendDay($DayNo,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "Select * FROM tlkpTarWeekendDays WHERE DayNo='".intval($DayNo)."'";
		if($NotOwnSelf==true){
			$sql.=" and WeekendDayID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	function CheckExistsSpecialDay($SpecialDay,$LastEffectDay,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		
		$sql = "Select * FROM tlkpTarSpecialDay WHERE SpecialDay='".stripcslashes(trim($SpecialDay))."' ".
			   "AND ToDay='".stripcslashes(trim($LastEffectDay))."' and status='1'";
		if($NotOwnSelf==true){
			$sql.="  AND SpecialDayID!='".$ID."'";
		}
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}

	
	
	
	function CheckBandNameExisting($BandName,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		$sql = "Select * FROM tlkpTarChargingBand WHERE BandName='".stripcslashes(trim($BandName))."' and status='1' ";
		if($NotOwnSelf==true){
			$sql.=" AND DistanceID!='".$ID."'";
		}
		//echo $sql;
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
function CheckExistsBlockName($BlockName,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		$sql = "Select * FROM tlkpTarChargeBlock WHERE BlockName='".stripcslashes(trim($BlockName))."' and status='1'";
		if($NotOwnSelf==true){
			$sql.=" AND BlockID!='".$ID."'";
		}
		//echo $sql;
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	function CheckExistingBillingCycle($Name,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		$sql = "Select * FROM tlkpBillingCycle WHERE Name='".stripcslashes(trim($Name))."' and status='1' ";
		if($NotOwnSelf==true){
			$sql.=" AND CycleID!='".$ID."'";
		}
		//echo $sql;
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	
		function CheckExistingPackage($Name,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		$sql = "Select * FROM tblTarPackage WHERE TarName='".stripcslashes(trim($Name))."' and status='1' ";
		if($NotOwnSelf==true){
			$sql.=" AND PackageID!='".$ID."'";
		}
		//echo $sql;
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	function CheckExistingTimeBand($TimeBandName,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		$sql = "Select * FROM tblTarTimeBand WHERE TimeBandName='".stripcslashes(trim($TimeBandName))."' and status='1' ";
		if($NotOwnSelf==true){
			$sql.=" AND TimeID!='".$ID."'";
		}
		//echo $sql;
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	
	function CheckExistingTariff($BandID,$GateID,$TimeID,$SubServiceID,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		$sql = "Select * FROM tblTariff WHERE BandID='$BandID' AND GateID='$GateID' AND TimeID='$TimeID'  AND SubService='$SubServiceID' AND (EndDate is null or EndDate > GetDate()) ";
		if($NotOwnSelf==true){
			$sql.=" AND TarID!='".$ID."'";
		}
		//echo $sql;
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	
	function CheckExistingIspTariff($cmbPackageID, $TimeID, $OverChargeBlockID, $NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		$sql = "Select * FROM tblIspTariff WHERE PackageID='$PackageID' AND TimeID = '$TimeID' AND OverChargeBlock = '$OverChargeBlock' AND (EndDate is null or EndDate > GetDate()) ";
		if($NotOwnSelf==true){
			$sql.=" AND TarID!='".$ID."'";
		}
		
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}
		return $test;
	}
	
	function CheckAreaCodeExisting($AreaCode,$CountryCode,$ServiceType,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		$len=strlen($AreaCode);
		$mycode=$AreaCode;
		$mycondition=" AreaCode='".$AreaCode."' ";
//		for($i=1;$i<=$len;$i++){
//			$mycode=substr($AreaCode,0,$i);
//			$mycondition.= " AreaCode='".$mycode."' OR";
//		}
//		if(strlen($mycondition)>0){
//			$mycondition=substr($mycondition,0,strlen($mycondition)-2);
//		}
//	
			$sql="select * from tblTarAreaCode
					inner join tlkpTarChargingBand cb
					on tblTarAreaCode.bandid=cb.distanceid
					 WHERE ($mycondition) 
				  and countrycode='".trim($CountryCode)."' 
				  and servicetype='$ServiceType'
				  and cb.Status='1' ";
			//print $sql;
			if($NotOwnSelf==true){
				$sql.=" AND AreaCodeID!='".$ID."'";
			}
			$query= $mydb->sql_query($sql);
			$numrow=intval($mydb->sql_numrows($query));
			if($numrow>0){
				$test=true;	
			}
		
		return $test;
	}
	function CheckAreaCodeExisting2($bandid){
		global $mydb,$myinfo;
		$test=false;

		
			$sql="select *
					from 
					(select * from tblTarAreaCode where bandid='$bandid'
					) base1, 
					(select ac.AreaCodeID, ac.AreaCode, ac.CountryCode, ac.ServiceType, ac.Description,ac.BandID
					from tlkpTarChargingBand cb
						left join tblTarAreaCode ac
						on cb.DistanceID=ac.BandID
					where cb.Status='1' and
					cb.DistanceID!='$bandid' and 
					isnull(ac.BandID,0)!=0) base2
					where base1.AreaCode=base2.AreaCode and base1.CountryCode=base2.CountryCode
						and base1.ServiceType=base2.ServiceType";
			//print $sql;
			$query= $mydb->sql_query($sql);
			$numrow=intval($mydb->sql_numrows($query));
			if($numrow>0){
				$test=true;	
			}
		
		return $test;
	}
	
	function GetAreaCode($bandid){
		global $mydb,$myinfo,$pg;
		$blockdetailoutput="<div id='band".$bandid."' style='display:none;'>";
		
		$sql="select ttcb.DistanceID as 'cbBandID', ttcb.BandName BandName, 
	   		 ttcb.Description Description, ttac.AreaCodeID, ttac.AreaCode, 
	   		 ttac.CountryCode, ttct.CallType, ttac.Description 'ccDescription'
			 from tlkpTarChargingBand ttcb, tblTarAreaCode ttac, tlkpTarCallType ttct  
			 WHERE ttcb.DistanceID=ttac.BandID and ttac.ServiceType=ttct.CallID and ttcb.DistanceID='$bandid'";

		
		$result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1'  bordercolor='#999999' style='border-collapse:collapse'   cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";
		$blockdetailoutput.="<th>Area Code</th>";
		$blockdetailoutput.="<th>Country Code</th>";
		$blockdetailoutput.="<th>Service Type</th>";
	    $blockdetailoutput.="<th>Description</th>";
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
			$blockdetailoutput.="<td align='left'>".stripslashes($row['AreaCode'])."</td>";
			$blockdetailoutput.="<td  align='left'>".stripslashes($row['CountryCode'])."&nbsp;</td>";
			$blockdetailoutput.="<td  align='left'>".stripslashes($row['CallType'])."&nbsp;</td>";
			$blockdetailoutput.="<td  align='left'>".stripslashes($row['ccDescription'])."&nbsp;</td>";
			$blockdetailoutput.="<td  align='center'><a href=\"./?pg=1006&amp;op=edit&amp;areacodeid=".$row['AreaCodeID']."\"><img src=\"./images/Edit.gif\" border=\"0\"></a></td>
													</td>";
			$blockdetailoutput.="</tr>";
			//		$rowcount++;
		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		$blockdetailoutput.="</div>";
		return $blockdetailoutput;
		
	}
	
	function HasAreaCode($bandid){
		global $mydb,$myinfo;
		$blresult=false;
		$sql="Select * from tblTarAreaCode WHERE BandID='".$bandid."'";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
		return $blresult;
	}
	
	function HasTariffDetail($packageid){
		global $mydb,$myinfo;
		$blresult=false;
		$sql="select *
		from (tblTariff tt  
	  	left join (tblTarTimeBand tttb   
					left join tblTarPackage ttp  
					on tttb.PackageID=ttp.PackageID)  
				on tt.TimeID=tttb.TimeID)  
	  	where tttb.PackageID='".$packageid."' 
 		order by ttp.PackageID";
 
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
		return $blresult;
	}
	
	function CheckExistsBlockDetail($BlockID,$FromDuration,$ToDuration,$UnitType,$NotOwnSelf=false,$ID=0){
		global $mydb,$myinfo;
		$test=false;
		$sql = "Select * FROM tblTarChargeBlockDetail ".
			   "WHERE BlockID='".intval($BlockID)."' AND FromDuration='".$FromDuration."' AND ToDuration='".$ToDuration."' AND Unit='".$UnitType."'";
		if($NotOwnSelf==true){
			$sql.=" AND BlockDetailID!='".$ID."'";
		}
		//echo $sql;
		$query= $mydb->sql_query($sql);
		$numrow=intval($mydb->sql_numrows($query));
		if($numrow>0){
			$test=true;	
		}

		return $test;
	}
	
	function GetChargeBlockDetail($blockid){
		global $mydb,$myinfo;
		$blockdetailoutput="<div id='block".$blockid."' style='display:none;'>";
		
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
		$blockdetailoutput.="</div>";
		return $blockdetailoutput;
	}
	
	function GetTimeBandDetail($blockid){
		global $mydb,$myinfo;
		$blockdetailoutput="<div id='block".$blockid."' style='display:none;'>";
		
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
		$blockdetailoutput.="</div>";
		return $blockdetailoutput;
	}
	
	function GetTariffDetail($packageid){
		global $mydb,$myinfo;
		$blockdetailoutput="<div id='block".$packageid."' style='display:none;'>";
		
		$sql="select tt.TarID, tt.Rate, tt.EffectiveDate, 
tt.Description 'tDescription', ttp.TarName,  
ttcb.BandName, ttgw.GateCode, tttb.TimeBandName,  
'' 'ServiceName', 
case 
when (tt.EndDate is null or tt.EndDate >= GetDate()) then 1  
else  
0 
end 'Status',tttb.TimeID,tttb.PackageID 
from (tblTariff tt  
	  left join (tblTarTimeBand tttb   
					left join tblTarPackage ttp  
					on tttb.PackageID=ttp.PackageID)  
				on tt.TimeID=tttb.TimeID)  
	  left join tlkpTarGateWay ttgw  
			    on tt.GateID=ttgw.GateID  
	  left join tlkpTarChargingBand ttcb  
		        on tt.DistanceID=ttcb.DistanceID 
where tttb.PackageID='".$packageid."' 
 order by ttp.PackageID, tt.DistanceID desc";
		//echo $sql;
			$result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead><th>ID</th>
													
													<th nowrap='nowrap'>Distance</th>
													<th>Gate Way</th>
													<th nowrap='nowrap'>Time Band</th>
													
													<th>Rate(Cent)</th>
													<th nowrap='nowrap'>Effective Date</th>
													<th>Description</th>
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
			$blockdetailoutput.="<tr  bgcolor='#ffffff' >";
			$blockdetailoutput.="<td align='center'>".$row['TarID']."</td>";
				$blockdetailoutput.="<td align='center'>".$row['BandName']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['gatecode']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['TimeBandName']."</td>";
		
			$blockdetailoutput.="<td align='center'>".$row['Rate']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['EffectiveDate']."</td>";
			$blockdetailoutput.="<td align='center'>".$row['tDescription']."</td>";
			$blockdetailoutput.="<td  align='center'><a href=\"./?pg=1012&amp;op=edit&amp;tarid=".$row['TarID']."\"><img src=\"./images/Edit.gif\" border=\"0\"></a></td>
												";
			$blockdetailoutput.="</tr>";

		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		$blockdetailoutput.="</div>";
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
			  WHERE tedds.DiscDestID='$desdiscid' order by ttcb.BandName";
			 
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
			$blockdetailoutput.="<td align='left'>".stripslashes($row['BandName'])."</td>";
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
	
	function GetOverChargeBlockDetail($hid){
		global $mydb,$myinfo;
		$blockdetailoutput="<div id='block".$hid."' style='display:none;'>";
		
		$sql="select * 
			  from tblIspOverChargeBlockDetail
			  WHERE OverChargeBlockID='$hid'";
			 
		//echo $sql;
	  $result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";

		$blockdetailoutput.="<th>From Duration</th>";
		$blockdetailoutput.="<th>To Duration</th>";
		$blockdetailoutput.="<th>Rate</th>";
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
			$blockdetailoutput.="<td  align='center'>".intval($row['FromDuration'])."</td>";
			$blockdetailoutput.="<td  align='center'>".intval($row['ToDuration'])."</td>";
			$blockdetailoutput.="<td  align='center'>".doubleval($row['Rate'])."</td>";
			$blockdetailoutput.="</tr>";
		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		$blockdetailoutput.="</div>";
		return $blockdetailoutput;
	}
	
	
	function GetCreditRuleLimitDetail($hid){
		global $mydb,$myinfo,$gl_action,$gl_action_sub;
		$blockdetailoutput="<div id='block".$hid."' style='display:none;'>";
		
		$sql="select * 
			  from tlkpCreditLimitRules
			  WHERE CredID='$hid'";
			 
		//echo $sql;
	  $result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";

		$blockdetailoutput.="<th>Over From %</th>";
		$blockdetailoutput.="<th>Over To %</th>";
		$blockdetailoutput.="<th>Action</th>";
		$blockdetailoutput.="<th>Suplementary</th>";
		$blockdetailoutput.="<th>Description</th>";
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
			$blockdetailoutput.="<td  align='center'>".intval($row['OverFrom'])."</td>";
			$blockdetailoutput.="<td  align='center'>".intval($row['OverTo'])."</td>";
						$blockdetailoutput.="<td  align='center'>".stripslashes($gl_action[$row['Action']])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($gl_action_sub[$row['Suplementary']])."</td>";
			
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['Description'])."</td>";
			$blockdetailoutput.="</tr>";
		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		$blockdetailoutput.="</div>";
		return $blockdetailoutput;
	}
	function GetCreditCreditRulePeriodicDetail($hid){
		global $mydb,$myinfo,$gl_action,$gl_action_sub;
		$blockdetailoutput="<div id='block".$hid."' style='display:none;'>";
		
		$sql="select * 
			  from tlkpCreditRuleUnpaidPeriod
			  WHERE CredID='$hid'";
			 
		//echo $sql;
	  $result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";

		$blockdetailoutput.="<th>From Day</th>";
		$blockdetailoutput.="<th>To Day</th>";
			$blockdetailoutput.="<th>Threshold</th>";
		$blockdetailoutput.="<th>Action</th>";
		$blockdetailoutput.="<th>Suplementary</th>";
		$blockdetailoutput.="<th>Description</th>";
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
			$blockdetailoutput.="<td  align='center'>".intval($row['FromDay'])."</td>";
			$blockdetailoutput.="<td  align='center'>".intval($row['ToDay'])."</td>";
			$blockdetailoutput.="<td  align='center'>".intval($row['Threshold'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($gl_action[$row['Action']])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($gl_action_sub[$row['Suplementary']])."</td>";
						$blockdetailoutput.="<td  align='center'>".stripslashes($row['Description'])."</td>";
			$blockdetailoutput.="</tr>";
		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		$blockdetailoutput.="</div>";
		return $blockdetailoutput;
	}
	
	function GetCreditRuleInvoiceDetail($hid){
		global $mydb,$myinfo,$gl_action,$gl_action_sub;
		$blockdetailoutput="<div id='block".$hid."' style='display:none;'>";
		
		$sql="select * 
			  from tlkpCreditRuleInvoice
			  WHERE CredID='$hid'";
			 
		//echo $sql;
	  $result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";

		$blockdetailoutput.="<th>From Invoice</th>";
		$blockdetailoutput.="<th>To Invoice</th>";
		$blockdetailoutput.="<th>Action</th>";
		$blockdetailoutput.="<th>Suplementary</th>";
		$blockdetailoutput.="<th>Description</th>";
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
			$blockdetailoutput.="<td  align='center'>".intval($row['FromInvoice'])."</td>";
			$blockdetailoutput.="<td  align='center'>".intval($row['ToInvoice'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($gl_action[$row['Action']])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($gl_action_sub[$row['Suplementary']])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['Description'])."</td>";
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
			  WHERE tedfca.DiscFreeAllowID='$hid' order by ttcb.BandName;";
			 
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
			$blockdetailoutput.="<td align='left'>".stripslashes($row['BandName'])."</td>";
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
		function GetCallAllowDiscountDetail($hid){
		global $mydb,$myinfo;
		
		$blockdetailoutput="<div id='dest".$hid."' style='display:none;'>";
		
		$sql="select * from tlkpEventDiscCallAllowanceDuration    
			  WHERE DiscAllowID='$hid' ";
			 
	//	echo $sql;
	  $result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";
		$blockdetailoutput.="<th>Description</th>";
		$blockdetailoutput.="<th>From Duration</th>";
		$blockdetailoutput.="<th>To Duration</th>";
		$blockdetailoutput.="<th>Rate</th>";
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
			$yesno=(intval($row['IsPercentage'])==0?"No":"Yes");
			$blockdetailoutput.="<tr  bgcolor='#ffffff' >";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['Description'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['FromDur'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['ToDur'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['DiscRate'])."</td>";
			$blockdetailoutput.="<td  align='center'>".$yesno."</td>";
			$blockdetailoutput.="</tr>";
		
		}
		$blockdetailoutput.="</tbody>";
		$blockdetailoutput.="</table>";
		
		///-------------
		$blockdetailoutput.="<br>";
		$sql="select ttcb.BandName, tttb.TimeBandName, ttdt.DayType 'DayType',tttb.FromTime, tttb.ToTime
				from (tlkpEventDiscCallAllowance tedfca
						left join tblTarTimeBand tttb
						on tedfca.TimeID=tttb.TimeID)
						left join tlkpTarDayType ttdt
						on tttb.DayType=ttdt.DayTypeID
						left join tlkpTarChargingBand ttcb
						on tedfca.DistanceID=ttcb.DistanceID  
			  WHERE tedfca.DiscAllowID='$hid' order by ttcb.BandName;";
			 
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
			$blockdetailoutput.="<td align='left'>".stripslashes($row['BandName'])."</td>";
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
	function GetCallMinuteBaseDiscountDetail($hid){
		global $mydb,$myinfo;
		
		$blockdetailoutput="<div id='block".$hid."' style='display:none;'>";
		
		$sql="select * from dbo.tblCycleDiscMinuteBaseSub    
			  WHERE DiscID='$hid'";
			 
	//	echo $sql;
	  $result=$mydb->sql_query($sql);
		$blockdetailoutput.="<table border='1' bordercolor='#999999' style='border-collapse:collapse'  cellpadding='0' cellspacing='1' width='100%'>";
		$blockdetailoutput.="<thead>";
		$blockdetailoutput.="<th>Minimum Duration (Minute)</th>";
		$blockdetailoutput.="<th>Discount Amount($)</th>";
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
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['MinDuration'])."</td>";
			$blockdetailoutput.="<td  align='center'>".stripslashes($row['DiscAmount'])."</td>";
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
		  $arrmonth=array(1=>"Jan",
    		   2=>"Feb",
    		   3=>"Mar",
    		   4=>"Apr",
    		   5=>"May",
    		   6=>"Jun",
    		   7=>"Jul",
    		   8=>"Aug",
    		   9=>"Sept",
    		   10=>"Oct",
    		   11=>"Nov",
    		   12=>"Dec");
		$sql="select tbc.CycleID bcCycleID, tbc.[Name]  , tbd.BillingDateID, tbd.[Day], tbd.[Month] ".
			 "FROM tlkpBillingCycle tbc, tlkpBillingDate tbd ".
			 "WHERE tbc.CycleID=tbd.CycleID AND tbc.CycleID='".$cycleid."'";
		
		$result=$mydb->sql_query($sql);
		
		$rowcount=0;
		$rowclass="";
		while($row=$mydb->sql_fetchrow($result)){
			$dayvalue="";
			if(trim($row['Month'])=="" || $row["Month"]==0){
				$blockdetailoutput.=stripslashes($row['Day']). " and ";
			}else{
				$blockdetailoutput.=$arrmonth[intval($row['Month'])]."-".stripslashes($row['Day']). " and ";
			}
				//	$rowcount++;
		}
		if(strlen($blockdetailoutput)>0){
			$blockdetailoutput=substr($blockdetailoutput,0,strlen($blockdetailoutput)-4);
		}
		return $blockdetailoutput;
	}
	
	function HasBillingDate($cycleid){
		
		global $mydb,$myinfo;
		$blresult=false;
		$sql="Select * from tlkpBillingDate WHERE CycleID='".$cycleid."'";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
		return $blresult;
	}
	
	function HasChargeBlockDetail($blockid){
		global $mydb,$myinfo;
		$blresult=false;
		$sql="Select * from tblTarChargeBlockDetail WHERE BlockID='".$blockid."'";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
		return $blresult;
	}
	function HasTimeBandDetail($blockid){
		global $mydb,$myinfo;
		$blresult=false;
		$sql="Select * from tblTarTimeBand WHERE PackageID='".$blockid."'";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
		return $blresult;
	}
	function HasDiscDestDetail($discdestid){
		global $mydb,$myinfo;
		$blresult=false;
		$sql="Select * from tlkpEventDiscDestSpecific WHERE DiscDestID='".$discdestid."'";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
		return $blresult;
	}
	function HasOverChargeBlockDetail($hid){
		global $mydb,$myinfo;
		$blresult=false;
		$sql="Select * from tblIspOverChargeBlockDetail WHERE OverChargeBlockID='".$hid."'";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
		return $blresult;
	}
		function HasCreditRulePeriod($hid){
		global $mydb,$myinfo;
		$blresult=false;
		$sql="Select * from tlkpCreditRuleUnpaidPeriod WHERE CredID='".$hid."'";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
		return $blresult;
	}
	function HasCreditRuleInvoiceDetail($hid){
		global $mydb,$myinfo;
		$blresult=false;
		$sql="Select * from tlkpCreditRuleInvoice WHERE CredID='".$hid."'";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
		return $blresult;
	}
	function HasCreditRuleLimitDetail($hid){
		global $mydb,$myinfo;
		$blresult=false;
		$sql="Select * from tlkpCreditLimitRules WHERE CredID='".$hid."'";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
		return $blresult;
	}
	function HasDiscFreeCallDetail($hid){
		global $mydb,$myinfo;
		$blresult=false;
		$blresult2=false;
		$sql="Select * from tlkpEventDiscFreeCallAllowanceDuration WHERE DiscFreeAllowID='".$hid."';";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
	
		$sql="Select * from tlkpEventDiscFreeCallAllowance WHERE DiscFreeAllowID='".$hid."';";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult2=true;
		}
		else{
			$blresult2=false;
		}
		//echo $sql;
		return ($blresult && $blresult2);
	}
	function HasDiscCallAllowDetail($hid){
		global $mydb,$myinfo;
		$blresult=false;
		$blresult2=false;
		$sql="Select * from tlkpEventDiscCallAllowanceDuration WHERE DiscAllowID='".$hid."';";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
	
		$sql="Select * from tlkpEventDiscCallAllowance WHERE DiscAllowID='".$hid."';";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult2=true;
		}
		else{
			$blresult2=false;
		}
		//echo $sql;
		return ($blresult && $blresult2);
	}
	
	function HasDiscCallMinuteBaseDetail($hid){
		global $mydb,$myinfo;
		$blresult=false;
		$blresult2=false;
		$sql="select * from dbo.tblCycleDiscMinuteBaseSub where  DiscID='".$hid."';";
		$result=$mydb->sql_query($sql);
		$numrow=$mydb->sql_numrows($result);
		if($numrow>0){
			$blresult=true;
		}
		else{
			$blresult=false;
		}
	
		
		//echo $sql;
		return ($blresult);
	}
	
	function GetNextID($NextName){
		global $mydb;
		$sql="update tlkpNext set NextValue=(isnull(NextValue,0)+1) where NextName='".$NextName."';
		select isnull(NextValue,0) 'NextValue' from tlkpNext where NextName='".$NextName."'";
		$result=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($result)){
				return intval($row["NextValue"]);
			}
		return false;
	}
?>