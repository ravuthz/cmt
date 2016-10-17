<?php
global $pg;

$bgformat=' bgcolor="#ffffff"; font-weight:bolder';
switch ($pg){
	case 2222: $bg1=$bgformat; break;
	case 2223: $bg2=$bgformat; break;
}
?>
<fieldset>
				<legend align="center">Exception Phone</legend>
					<table border="0" cellpadding="5" cellspacing="0" align="center" width="150" bordercolor="#aaaaaa" bgcolor="#feeac2" id="submenu">			

					 <tr>
						  <td style="border-top:1px solid; text-align:left" <?php echo $bg1?>><a href="./?pg=2222">Exception</a></td>
					  </tr>
					  <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg2?>><a href="./?pg=2223">Exception Detail</a></td>
					  </tr>   
					 </table>
</fieldset>