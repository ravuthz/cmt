<?php
// we'll generate XML output
header('Content-Type: text/xml');
// generate XML header
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
// create the <response> element
//echo '<response>';
require_once("../common/functions.php");
require_once("../common/agent.php");

		if($choice=="credit"){
			if($id==6000){
				$sql="select CredID,CredName from tblCreditLimitRules where status=1";
			}elseif($id==6001){
				$sql="select CredID,CreditRuleInvoice from tblCreditRuleInvoice where status=1";
			}elseif($id==6002){
				$sql="select CredID,CredName from tblCreditRuleUnpaidPeriod where status=1";
			}			
		}elseif($choice=="discount")
		{
			if($id==5010){
				$sql="select DiscDestID, Description from tblEventDiscDestSpecific where isactive='1' order by Description";
			}elseif($id==5011){
				$sql="select DiscFreeAllowID, Description from tblEventDiscFreeCallAllowance where isactive='1'  order by Description";
			}elseif($id==5012){
				$sql="select DiscAllowID, Description from tblEventDiscCallAllowance where isactive='1'  order by Description";
			}			
			
		}else{
			$sql="select ttb.TimeID, ttb.TimeBandName
											from tblTarTimeBand ttb
												inner join tblTarPackage ttp
												on ttb.PackageID=ttp.PackageID
											where ttb.status=1 and ttp.status=1 and ttb.PackageID='$id'";
		}
		$result=$mydb->sql_query($sql);
		
		echo '<options>';
		while ($row=$mydb->sql_fetchrow($result)) {
			
			echo '<option value="'.$row[0].'">'.$row[1].'</option>';
			
		}
		echo  '</options>';
		
?>

