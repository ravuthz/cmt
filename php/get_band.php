<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");		
	$ck = $_GET['ck'];
	//$cpe = $_GET['cpe'];		
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">';				
	$retOut .= "<tr><td colspan=5><a href='javascript:getband(1)'>Check all</a> | <a href='javascript:getband(0)'>Uncheck all</a></td></tr>";
	$retOut .= "<tr>";			
	
	$sql = "SELECT DistanceID, BandName FROM tlkptarChargingBand ORDER BY BandName";								
	
	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		
		while($result = $mydb->sql_fetchrow($que)){																															
			$BandID = $result['DistanceID'];
			$BandName = $result['BandName'];										
			
			if(($iLoop % 4) == 0)											
				$retOut .= "</tr><tr>";																			
			$retOut .= '<td align="left"><input type="checkbox" name="bandid" value="1" ';
				if(intval($ck) ==1) $retOut .= "checked"; 
			$retOut .= ' /><font size="-2">'.$BandName;
			$retOut .= '</font><input type="hidden" name="band" value="'.$BandID.'" />'.'</td>';						
			$iLoop++;
		}		
	}
	$mydb->sql_freeresult();		
		$retOut .= "</tr>";
		$retOut .= '</table>';
		
	print $retOut;	
?>