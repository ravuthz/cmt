<fieldset>
	<legend align="center">Menu</legend>
	
		<!-- ================================ Menu ================================= -->
		<table border="0" cellpadding="5" cellspacing="0" align="center" width="100%" bordercolor="#aaaaaa" bgcolor="#feeac2" id="submenu">			
			<tr><td>&nbsp;</td></tr>			
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==41) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=41">Unpaid invoice</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==42) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=42">Closed invoice</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==43) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=43">Payment transaction</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==52) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=52">Refund transaction</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==53) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=53">Deposit transaction</a>
				</td>		 
			</tr>
			<!--<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==9) print "bgcolor='#ffffff'";?>>
					<a href="./?pg=9&CustomerID=<?php print $CustomerID; ?>">Add extra charge</a>
				</td>		 
			</tr>-->
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==45) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=45">Book deposit</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==48) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=48">Increase balance</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==46) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=46">Refund deposit</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==47) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=47">Refund balance</a>
				</td>		 
			</tr>					
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==55) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=55">Transfer deposit to balance</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==56) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=56">Settle invoice with balance</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==51) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=51">Credit note</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==54) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=54">Write off bad debt</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==49) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=49">Rollback transaction</a>
				</td>		 
			</tr>		
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==2336) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=2336">Deposit Invoice</a>
				</td>		 
			</tr>		
		 </table>
</fieldset>