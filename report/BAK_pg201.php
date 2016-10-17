 <?php
	require_once("./common/agent.php");
	require_once("./common/configs.php");		
	require_once("./common/functions.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	
	if(($conf == "yes") && ($pg==201)){
		$JID = FixQuotes($JID);
		$AccountID = FixQuotes($AccountID);
		$now = date("Y/M/d H:i:s");
		
		$sql = "UPDATE tblJobAcctStatus 
						SET IsDone = 1,
								csDate = '".$now."' 
						WHERE JobID = $JID";
		$mydb->sql_query($sql);
		
		$sql = "SELECT Count(*) AS 'IsActive'
							FROM tblProductStatus 
							WHERE (International = 0 AND Incoming = 0 AND Outgoing = 0 AND IncomingLoc = 0 
											AND IncomingNat = 0 AND OutgoingLoc = 0 AND OutgoingNat = 0 AND Other = 0) AND AccID = $AccountID ";

		$que = $mydb->sql_query($sql);
		$result = $mydb->sql_fetchrow($que);
		$IsActive = $result['IsActive'];
		if(intval($IsActive) > 0){
			$sql = "UPDATE tblCustProduct SET StatusID = 2 WHERE AccID = $AccountID AND StatusID = 1";
				$mydb->sql_query($sql);
				# Insert into product status history as status changed
				$sql = "INSERT INTO tblAccStatusHistory(AccID, StatusID, ChangeDate, OtherID, OtherText)
								VALUES(".$AccountID.", 2, '".$now."', 2, 'Temporary disconnect')";
				$mydb->sql_query($sql);
		}else{
			$sql = "UPDATE tblCustProduct SET StatusID = 1 WHERE AccID = $AccountID AND StatusID = 2";
				$mydb->sql_query($sql);
				# Insert into product status history as status changed
				$sql = "INSERT INTO tblAccStatusHistory(AccID, StatusID, ChangeDate, OtherID, OtherText)
								VALUES(".$AccountID.", 1, '".$now."', 5, 'Reconnect')";
				$mydb->sql_query($sql);
		}
	}
?>
<script language="javascript" type="text/javascript">
	function done(ID, AccID, Account, st){
		if(confirm("Are you sure that you to " + st + " account " + Account + "?")){
			fdoneit.JID.value = ID;
			fdoneit.AccountID.value = AccID;
			fdoneit.conf.value = "yes";						
			
			fdoneit.submit();
		}
	}
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>ACCOUNT REQUEST PENDING REPORT</b></td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<!--<th>Job ID</th>-->
								<th>Phone</th>
								<th width="70">Status</th>
								<th width="70">Request to</th>
								<th>In all</th>
								<th>In loc</th>
								<th>In nat</th>
								<th>Out all</th>
								<th>Out loc</th>
								<th>Out nat</th>
								<th>Int'</th>
								<th>Other</th>
								<th>ISP Comment</th>
								<th>OP Comment</th>
								<th>Done</th>																						
							</thead>
							<tbody>
								<?php
										$sql = "SELECT j.JobID, j.AccID, j.CurrentStatusID, j.NewStatusID, j.SubmitDate, 
															j.EffectiveDate, j.Comment, j.Incoming, j.Outgoing, j.International, j.IncomingLoc,
															j.IncomingNat, j.OutgoingLoc, j.OutgoingNat, j.Other, j.ISPComment, j.OPComment, a.CustID, a.UserName
														FROM tblJobAcctStatus j, tblCustProduct a
														WHERE j.AccID = a.AccID and ((j.IsConfirm = 1 and j.OpDone = 1) or (j.ISPDone = 1)) and j.IsDone = 0 
														ORDER BY j.EffectiveDate,  j.JobID";

									if($que = $mydb->sql_query($sql)){
										while($result = $mydb->sql_fetchrow()){
											$JobID = $result['JobID'];				
											$AccID = $result['AccID'];
											$CurrentStatusID = $result['CurrentStatusID'];
											$NewStatusID = intval($result['NewStatusID']);
//											$StatusID = intval($result['StatusID']);
											$SubmitDate = $result['SubmitDate'];																
											$EffectiveDate = $result['EffectiveDate'];
											$Comment = $result['Comment'];
											$CustID = $result['CustID'];
											$UserName = $result['UserName'];
											$Incoming = $result['Incoming'];
											$Outgoing = $result['Outgoing'];
											$IncomingLoc = $result['IncomingLoc'];
											$IncomingNat = $result['IncomingNat'];
											$OutgoingLoc = $result['OutgoingLoc'];
											$OutgoingNat = $result['OutgoingNat'];
											$Other = $result['Other'];
											$International = $result['International'];
											$ISPComment = $result['ISPComment'];
											$OPComment = $result['OPComment'];
											$linkAccount = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";

											switch($CurrentStatusID){
												case 0:
													$stbg = $bgUnactivate;
													$stfg = $foreUnactivate;
													$stwd = "Inactive";
													break;
												case 1:
													$stbg = $bgActivate;
													$stfg = $foreActivate;
													$stwd = "Active";
													break;
												case 2:
													$stbg = $bgLock;
													$stfg = $foreLock;
													$stwd = "Barred";
													break;
												case 3:
													$stbg = $bgClose;
													$stfg = $foreClose;
													$stwd = "Closed";
													break;
											}
											switch($NewStatusID){
												
												case 1:
													$nstbg = $bgActivate;
													$nstfg = $foreActivate;
													$nstwd = "Activate";
													$stsup = "Activate";
													
													$done = "<a href=\"./?JID=".$JobID."&CustomerID=".$CustID."&AccountID=".$AccID."&UserName=".$UserName."&cstatus=".$stwd."&nstatus=".$nstwd."&pg=216\">Done?</a>";
													break;
												case 2:
													$nstbg = $bgLock;
													$nstfg = $foreLock;
													$nstwd = "Bar";
													$stsup = "Bar";
													$done = "<a href='#' onClick=\"done(".$JobID.", ".$AccID.", '".$UserName."', 'bar');\">Done?</a>";
													break;
												case 3:
													$nstbg = $bgClose;
													$nstfg = $foreClose;
													$nstwd = "Close";
													$stsup = "Close";
													
													$done = "<a href=\"./?JID=".$JobID."&CustomerID=".$CustID."&AccountID=".$AccID."&UserName=".$UserName."&cstatus=".$stwd."&nstatus=".$nstwd."&pg=216\">Done?</a>";
													break;
												case 5:
													$nstbg = $bgActivate;
													$nstfg = $foreActivate;
													$nstwd = "Unbar";
													$stsup = "Unbar";
													$done = "<a href='#' onClick=\"done(".$JobID.", ".$AccID.", '".$UserName."', 'unbar');\">Done?</a>";
													break;
												default:
													$nstbg = $bgClose;
													$nstfg = $foreClose;
													$nstwd = "Close";
													
													$done = "<a href=\"./?JID=".$JobID."&CustomerID=".$CustID."&AccountID=".$AccID."&UserName=".$UserName."&cstatus=".$stwd."&nstatus=".$nstwd."&pg=216\">Done?</a>";
													break;												
											}
											 
											
											$iLoop++;															
											if(($iLoop % 2) == 0)
												$style = "row1";
											else
												$style = "row2";
											print '<tr>';	
											//print '<td class="'.$style.'" align="right">'.$JobID.'</td>';
											print '<td class="'.$style.'" align="left">'.$linkAccount.'</td>';
											print '<td align="center" bgcolor="'.$stbg.'">
															<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
														 </td>';
											print '<td align="center" bgcolor="'.$nstbg.'">
															<font color="'.$nstfg.'"><b>'.$nstwd.'</b></font>
														 </td>';
											#incoming
											print '<td align="center">';
											print '<input type="checkbox" name="Incoming" disabled="disabled" class="disabled" value="'.$Incoming.'" ';
											if(intval($Incoming == 1)) 
												print 'checked="checked"';
											print '></td>';
											print '<td align="center">';
											print '<input type="checkbox" name="IncomingLoc" disabled="disabled" class="disabled" value="'.$IncomingLoc.'" ';
											if(intval($IncomingLoc == 1)) 
												print 'checked="checked"';
											print '></td>';
											print '<td align="center">';
											print '<input type="checkbox" name="IncomingNat" disabled="disabled" class="disabled" value="'.$IncomingNat.'" ';
											if(intval($IncomingNat == 1)) 
												print 'checked="checked"';
											print '></td>';
											
											#outgoing
											print '<td align="center">';
											print '<input type="checkbox" name="Outgoing" disabled="disabled" class="disabled" value="'.$Outgoing.'" ';
											if(intval($Outgoing == 1)) print 'checked="checked"';
											print '></td>';
											print '<td align="center">';
											print '<input type="checkbox" name="OutgoingLoc" disabled="disabled" class="disabled" value="'.$OutgoingLoc.'" ';
											if(intval($OutgoingLoc == 1)) print 'checked="checked"';
											print '></td>';
											print '<td align="center">';											
											print '<input type="checkbox" name="OutgoingNat" disabled="disabled" class="disabled" value="'.$OutgoingNat.'" ';
											if(intval($OutgoingNat == 1)) print 'checked="checked"';
											print '></td>';
											print '<td align="center">';
											
											print '<input type="checkbox" name="International" disabled="disabled" class="disabled" value="'.$International.'" ';
											if(intval($International == 1)) print 'checked="checked"';
											print '></td>';
											print '<td align="center">';											
											print '<input type="checkbox" name="Other" disabled="disabled" class="disabled" value="'.$Other.'" ';
											if(intval($Other == 1)) print 'checked="checked"';
											print '></td>';
											print '<td class="'.$style.'" align="left">'.$ISPComment.'</td>';
											print '<td class="'.$style.'" align="left">'.$OPComment.'</td>';
											print '<td class="'.$style.'" align="center">'.$done.'</td>';
											print '</tr>';
										}
									}
									$mydb->sql_freeresult();	
								?>
							</tbody>												
						</table>					
					</td>
				</tr>
				<form name="fdoneit" method="post" action="./">					
					<input type="hidden" name="conf" value="" />			
					<input type="hidden" name="AccountID" value="" />			
					<input type="hidden" name="JID" value="" />																											
					<input type="hidden" name="pg" value="201" />
				</form>
			</table>
		</td>
	</tr>						
</table>
<?php
# Close connection
$mydb->sql_close();
?>