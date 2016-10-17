<?php
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
?>
<fieldset>
	<legend align="center">Menu</legend>
	
		<!-- ================================ Menu ================================= -->
		<table border="0" cellpadding="5" cellspacing="0" align="left" width="100%" bordercolor="#aaaaaa" bgcolor="#feeac2" id="submenu">			
			<tr><td>&nbsp;</td></tr>			
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==11) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=10">Customer info</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==12) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=12">Edit customer</a>
				</td>		 
			</tr>
			<!--<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==13) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=13">Edit billing info</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==14) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=14">Edit address</a>
				</td>		 
			</tr>-->
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==18) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=18">Add new contact</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==19) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=19">Add new guarantor</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==20) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=20">Add new designate</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==23) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=23">Change customer type</a>
				</td>		 
			</tr>
			<!--<tr>
				<td style="border-top:1px solid" <?php if($pg==21) print "bgcolor='#ffffff'";?>>
					<a href="./?pg=8&CustomerID=<?php print $CustomerID; ?>">Consolidate</a>
				</td>		 
			</tr>-->
					
		 </table>
</fieldset>