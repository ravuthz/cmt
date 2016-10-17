<?php
	$sql = "SELECT a.StatusID, t.ServiceID, a.UserName, a.SubscriptionName , a.BillingEmail
					FROM tblCustProduct a, tblTarPackage t 
					WHERE a.PackageID = t.PackageID and a.AccID = $AccountID";

	if($que = $mydb->sql_query($sql)){
		if($result = $mydb->sql_fetchrow($que)){
			$aStatusID = $result['StatusID'];
			$aUserName = $result['UserName'];
			$aSubscriptionName = $result['SubscriptionName'];
			$aBillingEmail = $result['BillingEmail'];
			$ServiceID = $result['ServiceID'];

			if((intval($aStatusID) == 0) || (intval($aStatusID) == 4))
				 $disable = true;
			else
				$disable = false;
		}else{
			$disable = true;
		}
	}else{
		$disable = true;
	}

?>
<fieldset>
	<legend align="center">Menu</legend>
	
		<!-- ================================ Menu ================================= -->
		<table border="0" cellpadding="5" cellspacing="0" align="center" width="100%" bordercolor="#aaaaaa" bgcolor="#feeac2" id="submenu">			
			<tr><td align="left">&nbsp;</td></tr>	
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==195) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=195&TrackID=<?php print $TrackID; ?>">Application Form</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid">
					<!--<a href="./?CustomerID=<?php print $CustomerID.$aStatusID; ?>&pg=4&ext=ap">Add more products</a>-->
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid">
					<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=90&TrackID=<?php print $TrackID; ?>">Products summary</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==91) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=91&TrackID=<?php print $TrackID; ?>">Product detail</a>
				</td>		 
			</tr>	
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==301) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=301&TrackID=<?php print $TrackID; ?>">Change VAT</a>
				</td>		 
			</tr>	
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==94) print "bgcolor='#ffffff'";?>>
					<?php
						
							print '<a href="./?CustomerID='.$CustomerID.'&AccountID='.$AccountID.'&pg=94&TrackID='.$TrackID.'">Add extra charge</a>';		
					?>
					
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==95) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=95&TrackID=<?php print $TrackID; ?>">Add recurring charge</a>
				</td>		 
			</tr>
			<!--<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==94) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=94">Change account name</a>
				</td>		 
			</tr>-->
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==197) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=197&TrackID=<?php print $TrackID; ?>">View password</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==93) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=93&TrackID=<?php print $TrackID; ?>">Change password</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==92) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=92&TrackID=<?php print $TrackID; ?>">Change name or mail</a>
				</td>		 
			</tr>
						
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==96) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=96&TrackID=<?php print $TrackID; ?>">One time credit</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==97) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=97&TrackID=<?php print $TrackID; ?>">Recurring credit</a>
				</td>		 
			</tr>
			<!--<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==104) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=104">Percentage credit</a>
				</td>		 
			</tr>-->
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==98) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=98&TrackID=<?php print $TrackID; ?>">Credit summary</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==99) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=99&TrackID=<?php print $TrackID; ?>">Recurring charge summary</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==105) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=105&TrackID=<?php print $TrackID; ?>">Status history</a>
				</td>		 
			</tr>
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==102) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=102&TrackID=<?php print $TrackID; ?>">Installation address</a>
				</td>		 
			</tr>	
			<tr>
				<td align="left" style="border-top:1px solid" <?php if($pg==103) print "bgcolor='#ffffff'";?>>
					<a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=103&TrackID=<?php print $TrackID; ?>">Credit rule management</a>
				</td>		 
			</tr>	
			<tr>
			  <td align="left" style="border-top:1px solid" <?php if($pg==198) print "bgcolor='#ffffff'";?>><a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=198&TrackID=<?php print $TrackID; ?>">Additional Mail Box </a></td>
		  </tr>		
		  <tr>
			  <td align="left" style="border-top:1px solid" <?php if($pg==199) print "bgcolor='#ffffff'";?>><a href="./?CustomerID=<?php print $CustomerID; ?>&AccountID=<?php print $AccountID; ?>&pg=199&TrackID=<?php print $TrackID; ?>">Account Mail Box </a></td>
		  </tr>										
		 </table>
</fieldset>