<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");		
	
	$ck = $_GET['ck'];
	//$cpe = $_GET['cpe'];		
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">';				
	$retOut .= "<tr><td colspan=5><a href='javascript:getcity(1)'>Check all</a> | <a href='javascript:getcity(0)'>Uncheck all</a></td></tr>";
	$retOut .= "<tr>";			
	
	$sql = "select ID LocID, Name LocName  from tlkpLocation where type = 2 order by Name ";
								
	
	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		
		while($result = $mydb->sql_fetchrow($que)){																															
			$LocID = $result['LocID'];
			$LocName = $result['LocName'];										
			
			if(($iLoop % 4) == 0)											
				$retOut .= "</tr><tr>";																			
			$retOut .= '<td align="left"><input type="checkbox" name="LocName" value="1" ';
				if(intval($ck) ==1) $retOut .= "checked"; 
			$retOut .= ' /><font size="-2">'.$LocName;
			$retOut .= '</font><input type="hidden" name="lid" value="'.$LocID.'" /><input type="hidden" name="lname" value="'.$LocName.'" />'.'</td>';						
			$iLoop++;
		}		
	}
	$mydb->sql_freeresult();		
		$retOut .= "</tr>";
		$retOut .= '</table>';
		
	print $retOut;	
?>