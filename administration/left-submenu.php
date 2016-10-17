<?php
global $pg,$subop;
$bg1="";
$bg2="";

$submenuname="Charge Block";
$bgformat=' bgcolor="#ffffff"; font-weight:bolder';
switch ($subop){
	case "1": $bg1=$bgformat;	break;
	case "2": $bg2=$bgformat; break;
}
?>
			<fieldset>
				<legend align="center"><?echo $submenuname;?></legend>
					<table border="0" cellpadding="5" cellspacing="0" align="center" width="100%" bordercolor="#aaaaaa" bgcolor="#feeac2" id="submenu">				
		
					<tr>
						 	<td style="border-top:1px solid" <?php echo $bg1?>>
								<a href="./?pg=3&subop=1">Charge Block</a>
						 	</td>		 
					</tr>
					<tr>
						 	<td style="border-top:1px solid; " <?php echo $bg2?>><a href="./?pg=4&subop=2">Charge Block Detail</a></td>		 
						</tr>
				
						
					 </table>
			</fieldset>