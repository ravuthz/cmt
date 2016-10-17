<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$tid = $_GET['tid'];	

	if(intval($tid) == 0){
		$retOut = "<select name='cid'>";
		$retOut .= "<option value='0'>All</option>";
		$retOut .= "</select>";	
	}else{
			if(intval($tid) == 6000)
				$sql = "SELECT CredID as 'id', CredName as 'name' FROM tblCreditLimitRules";
			elseif(intval($tid) == 6001)
				$sql = "SELECT CredID as 'id', CreditRuleInvoice as 'name' FROM tblCreditLimitRules";
			elseif(intval($tid) == 6002)
				$sql = "SELECT CredID as 'id', CredName as 'name' FROM tblCreditRuleUnpaidPeriod";
			if($que = $mydb->sql_query($sql)){			
				$retOut = "<select name='cid'>";				
				while($result = $mydb->sql_fetchrow()){																															
					$id = $result['id'];										
					$name = $result['name'];		
					$retOut .= "<option value=".$id.">".$name."</option>";								
				}
				$retOut .= "<option value='0' selected>All</option>";
				$retOut .= "</select>";	
			}else{
					$retOut = "<select name='cid' style='width:130'>";
					$retOut .= "<option value='0'>All</option>";
					$retOut .= "</select>";	
			}
	}
	$mydb->sql_freeresult();
	print $retOut;	
?>