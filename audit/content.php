<?php
	switch($pg){
		case 1:
			$bg= "#ffffff";
		case 2:
			
	}
?>
<fieldset>
	<legend align="center">Menu</legend>
	
		<!-- ================================ Menu ================================= -->
		<table border="0" cellpadding="5" cellspacing="0" align="center" width="100%" bordercolor="#aaaaaa" bgcolor="#feeac2" id="submenu">			
			<tr><td bgcolor="#FFFFFF">&nbsp;</td></tr>			
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==30) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID;?>&pg=30">Service</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==31) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID;?>&pg=31">Finance</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==32) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID;?>&pg=32">Reminder</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==33) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID;?>&pg=33">Other</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==34) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID;?>&pg=34">Add comment</a>
				</td>		 
			</tr>		
		 </table>
</fieldset>