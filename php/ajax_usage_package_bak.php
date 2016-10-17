<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");		
	$sid = $_GET['serviceid'];
	$ck = $_GET['ck'];
	$fi = $_GET['fi'];
	//$cpe = $_GET['cpe'];		
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">';				
	
		if($sid == 11)
		{
			$retOut .= '<tr align="center">';			
			$retOut.='<td align="right"><font size=-1>Customer ID :</font></td>';
			$retOut.='<td align="left"><font size=-1><input type="TextBox" name="txtcustID" value=""/></font></td></tr>';
			$retOut .= '<tr align="center">';			
			$retOut.='<td align="right"><font size=-1>Limited Amount :</font></td>';
			$retOut.='<td align="left"><font size=-1><input type="TextBox" name="txtlimit" value=""/></font></td>';
		}
		else{
					$retOut .= "<tr><td colspan=5><a href='javascript:getpackage(1,0)'>Check all</a> | <a href='javascript:getpackage(0,0)'>Uncheck all</a></td></tr>";
					$retOut .= "<tr>";			
					$sql = "SELECT PackageID, TarName 
									FROM tblTarPackage WHERE ";
					if($sid == 2)
						$sql .= "ServiceID = 2 ";
					elseif($sid == 4)
						$sql .= "ServiceID = 4 ";
					elseif($sid == 4)
						$sql .= "ServiceID in(1, 3, 8) ";
					$sql .= "	ORDER BY ServiceID,TarName";								
					
					if($que = $mydb->sql_query($sql)){				
						$iLoop = 0;
						
						while($result = $mydb->sql_fetchrow($que)){																															
							$PackageID = $result['PackageID'];
							$TarName = $result['TarName'];										
							
							if(($iLoop % 4) == 0)											
								$retOut .= "</tr><tr>";																			
							$retOut .= '<td align="left"><input type="checkbox" name="package" value="1" ';
								if(intval($fi) ==1 && ($PackageID==10 || $PackageID==16 || $PackageID==17)) 
									$retOut .= "checked"; 
								else if(intval($ck) ==1 && intval($fi) ==0) $retOut .= "checked"; 
							$retOut .= ' /><font size="-2">'.$TarName;
							$retOut .= '</font><input type="hidden" name="pack" value="'.$PackageID.'" />'.'</td>';						
							$iLoop++;
						}		
					}
					$mydb->sql_freeresult();
		}					
		$retOut .= "</tr>";
		$retOut .= '</table>';
		
	print $retOut;	
?>