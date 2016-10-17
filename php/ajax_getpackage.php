<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$serviceID = $_GET['serviceid'];	

	if($serviceID == 0){
		$retOut = "<select name='selpackage'>";
		$retOut .= "<option value='0'>All</option>";
		$retOut .= "</select>";	
	}else{
		$sql = "Select PackageID, TarName from tblTarPackage where serviceID=".$serviceID;
			
		if($que = $mydb->sql_query($sql)){
		
			$retOut = "<select name='selpackage'>";
			$retOut .= "<option value='0'>All</option>";
			while($result = $mydb->sql_fetchrow()){																															
				$PackageID = $result['PackageID'];										
				$TarName = $result['TarName'];
	
				$retOut .= "<option value=".$PackageID.">".$TarName."</option>";								
			}			
			$retOut .= "</select>";	
		}else{
				$retOut = "<select name='selpackage'>";
				$retOut .= "<option value='0'>All</option>";
				$retOut .= "</select>";	
		}
	}
	$mydb->sql_freeresult();
	print $retOut;	
?>