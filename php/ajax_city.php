<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");		
	$sid = $_GET['serviceid'];
	$ck = $_GET['ck'];
	//$cpe = $_GET['cpe'];		
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">';				
	$retOut .= "<tr><td colspan=5><a href='javascript:getpackage(1)'>Check all</a> | <a href='javascript:getpackage(0)'>Uncheck all</a></td></tr>";
	$retOut .= "<tr>";			
	
	$sql = "SELECT id, name FROM tlkpLocation WHERE type = 2 ORDER BY name";
								
	
	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		
		while($result = $mydb->sql_fetchrow($que)){																															
			$id = $result['id'];
			$name = $result['name'];										
			
			if(($iLoop % 4) == 0)											
				$retOut .= "</tr><tr>";																			
			$retOut .= '<td align="left"><input type="checkbox" name="package" value="1" ';
				if(intval($ck) ==1) $retOut .= "checked"; 
			$retOut .= ' /><font size="-2">'.$name;
			$retOut .= '</font><input type="hidden" name="pack" value="'.$id.'" />'.'</td>';						
			$iLoop++;
		}		
	}
	$mydb->sql_freeresult();		
		$retOut .= "</tr>";
		$retOut .= '</table>';
		
	print $retOut;	
?>