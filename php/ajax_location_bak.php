<?php
// we'll generate XML output
header('Content-Type: text/xml');
// generate XML header
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
// create the <response> element
//echo '<response>';
require_once("../common/functions.php");
require_once("../common/agent.php");
$t = $_GET['t'];
$q = $_GET['q'];
$d = $_GET['d'];
	switch ($t){
		case 1: # list all country
			$sql = "SELECT id, name FROM tlkpLocation WHERE type = 1";
			break;
		case 2: # List all city
			$sql = "SELECT id, name FROM tlkpLocation WHERE type = 2 AND country in 
									(SELECT country FROM tlkpLocation WHERE id = ".$q.") ORDER BY name";
			break;
		case 3: # List all Khan
			$sql = "SELECT id, name FROM tlkpLocation WHERE Type = 3 AND province in
									(SELECT province FROM tlkpLocation WHERE id = ".$q.") ORDER BY name";
			break;
		case 4: # List all sangkat
			$sql = "Select id, name from tlkpLocation l where district in 
										(Select district from tlkpLocation where id=".$q." and province=l.province and country=l.country) 
										and type=4 ORDER BY name";
			break;
		case 5: # get credit limit
			if(intval($q) == 6000)
				$sql = "SELECT CredID as 'id', CredName as 'name' FROM tblCreditLimitRules";
			elseif(intval($q) == 6001)
				$sql = "SELECT CredID as 'id', CreditRuleInvoice as 'name' FROM tblCreditLimitRules";
			elseif(intval($q) == 6002)
				$sql = "SELECT CredID as 'id', CredName as 'name' FROM tblCreditRuleUnpaidPeriod";
			break;
		case 6: # get cycle 
			$sql = "select i.CycleID as 'id', i.BillEndDate as 'name'
							from tblSysBillRunCycleInfo i inner join tblCustProduct ac on i.PackageID = ac.PackageId
							where i.BillProcessed = 1 and ac.AccID = ".intval($q)." ORDER BY BillEndDate DESC";
			break;
		case 7: # get AccID by UserName
			$sql = "select case when statusid = 1 then 'Active_' when statusid = 4 then 'Close_' when statusID = 0 then 'InActive_' when statusid = 3 then 'Demand_' end + UserName + '_' +SubscriptionName + '_' + convert(varchar,AccID) 'name', AccID 'id' from tblCustProduct Where UserName like '%".$q."%'";
			break;
	}
	$out = "";

	if($que = $mydb->sql_query($sql)){
		while($result = $mydb->sql_fetchrow($que)){
			$id = $result['id'];
			$name = $result['name'];
			//echo "<script language='JScript'>";
//			echo "var oOption = document.createElement('OPTION');";
//			echo "oOption.text='".$name."'";
//			echo "oOption.value='".$id."'";
//			echo "document.getElementById('".$d."').add(oOption);";
//			echo "<script>";
				$out .= "<option value='".$id."'>".$name."|".$id."</option>";
//			$out .= "<script>";
//			$out .= "var oOption = document.createElement('OPTION');";
//			$out .= "oOption.text='".$name."'";
//			$out .= "oOption.value='".$id."'";
//			$out .= "document.getElementById('".$d."').add(oOption);";
//			$out .= "<script>";
		}
	}
	//echo $out;
?>
<response>
	
		<?php 			
			//$out = xmlEncode($out);
			echo $out;
		?>
	
</response>
