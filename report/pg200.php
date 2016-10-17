<?php
	require_once("./common/agent.php");
	require_once("./common/configs.php");
	require_once("./common/class.audit.php");	
	require_once("./common/functions.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	
	if(($conf == "yes") && ($pg==200)){
		$JID = FixQuotes($JID);
		$CustomerID = FixQuotes($CustomerID);
		$AccountID = FixQuotes($AccountID);
		$cstatus = FixQuotes($cstatus);
		$nstatus = FixQuotes($nstatus);
		$nstatusid = FixQuotes($nstatusid);
		$swcomment = FixQuotes($swcomment);
		$addaudit = false;
		
		#Update account supplement
		//$sql = "UPDATE tblProductStatus SET 
//										tblProductStatus.Incoming = tblJobAcctStatus.Incoming, 
//										tblProductStatus.Outgoing = tblJobAcctStatus.Outgoing, 
//										tblProductStatus.International = tblJobAcctStatus.International
//						FROM tblJobAcctStatus
//						WHERE tblProductStatus.AccID = tblJobAcctStatus.AccID AND tblJobAcctStatus.JobID=$JID";
//		$mydb->sql_query($sql);				
		
		# Get status in job
		$sql = "SELECT Incoming, Outgoing, International, IncomingLoc, IncomingNat, OutgoingLoc, OutgoingNat, Other
						FROM tblJobAcctStatus WHERE JobID = $JID";
		if($que = $mydb->sql_query($sql)){
			if($result = $mydb->sql_fetchrow($que)){
				$incoming = $result['Incoming'];
				$Outgoing = $result['Outgoing'];
				$International = $result['International'];
				$IncomingLoc = $result['IncomingLoc'];
				$IncomingNat = $result['IncomingNat'];
				$OutgoingLoc = $result['OutgoingLoc'];
				$OutgoingNat = $result['OutgoingNat'];
				$Other = $result['Other'];				
			}
		}
		# Update account status
		
		$now = date("Y/M/d H:i:s");
		
		if($nstatusid == 1){
			#Switch activate account ==> activate account supplement status in billing even thought account status does not activate yet.
			$sql = "UPDATE tblProductStatus SET ";
			if(intval($incoming) == 1)
				$sql .= "Incoming = 1, ";
			if(intval($Outgoing) == 1)
				$sql .= "Outgoing = 1, ";
			if(intval($International) == 1)
				$sql .= "International = 1, ";
			if(intval($IncomingLoc) == 1)
				$sql .= "IncomingLoc = 1, ";
			if(intval($IncomingNat) == 1)
				$sql .= "IncomingNat = 1, ";
			if(intval($OutgoingLoc) == 1)
				$sql .= "OutgoingLoc = 1, ";
			if(intval($OutgoingNat) == 1)
				$sql .= "OutgoingNat = 1, ";
			if(intval($Other) == 1)
				$sql .= "Other = 1, "; 
			$sql .= " AccID = $AccountID WHERE AccID = $AccountID ";
			$mydb->sql_query($sql);
			#Activate
			$sql = "UPDATE tblJobAcctStatus SET 
								IsConfirm = 1, 
								ISPDone = 1,	
								ISPDoneDate = '".$now."',
								ISPComment = '".$swcomment."',
								DoneDate = '".$now."',
								SWComment = '".$swcomment."' 
								WHERE JobID = $JID";
			$mydb->sql_query($sql);			
			
			$Description = "Confirm to change account status from $cstatus to $nstatus";
			$addaudit = true;
		}elseif($nstatusid == 2){
			#Bar
			#Switch bar account ==> bar account supplement status in billing.
			$sql = "UPDATE tblProductStatus SET ";
			if(intval($incoming) == 1)
				$sql .= "Incoming = 0, ";
			if(intval($Outgoing) == 1)
				$sql .= "Outgoing = 0, ";
			if(intval($International) == 1)
				$sql .= "International = 0, ";
			if(intval($IncomingLoc) == 1)
				$sql .= "IncomingLoc = 0, ";
			if(intval($IncomingNat) == 1)
				$sql .= "IncomingNat = 0, ";
			if(intval($OutgoingLoc) == 1)
				$sql .= "OutgoingLoc = 0, ";
			if(intval($OutgoingNat) == 1)
				$sql .= "OutgoingNat = 0, ";
			if(intval($Other) == 1)
				$sql .= "Other = 0, "; 
			$sql .= " AccID = $AccountID WHERE AccID = $AccountID";
			$mydb->sql_query($sql);

			$sql = "UPDATE tblJobAcctStatus 
							SET IsConfirm = 1, 
									ISPDone = 1,	
									ISPDoneDate = '".$now."',
									ISPComment = '".$swcomment."',
									IsDone = 0,
									DoneDate = '".$now."',
									SWComment = '".$swcomment."' 
							WHERE JobID = $JID";
			$mydb->sql_query($sql);
			//$sql = "SELECT Count(*) AS 'IsActive'
//							FROM tblProductStatus 
//							WHERE (International = 0 AND Incoming = 0 AND Outgoing = 0 AND IncomingLoc = 0 
//											AND IncomingNat = 0 AND OutgoingLoc = 0 AND OutgoingNat = 0 AND Other = 0) AND AccID = $AccountID ";
//			$que = $mydb->sql_query($sql);
//			$result = $mydb->sql_fetchrow($que);
//			$IsActive = $result['IsActive'];
//
//			if(intval($IsActive) > 0){
//				$sql = "UPDATE tblCustProduct SET StatusID = 2 WHERE AccID = $AccountID AND StatusID = 1";
//				$mydb->sql_query($sql);
//				# Insert into product status history as status changed
//				$sql = "INSERT INTO tblAccStatusHistory(AccID, StatusID, ChangeDate, OtherID, OtherText)
//								VALUES(".$AccountID.", 2, '".$now."', 2, 'Temporary disconnect')";
//				$mydb->sql_query($sql);
//			}			
			$Description = "Already to change account status from $cstatus to $nstatus";
			$addaudit = true;
		}elseif($nstatusid == 3){
			#Close
			$sql = "UPDATE tblProductStatus SET ";
			if(intval($incoming) == 1)
				$sql .= "Incoming = 0, ";
			if(intval($Outgoing) == 1)
				$sql .= "Outgoing = 0, ";
			if(intval($International) == 1)
				$sql .= "International = 0, ";
			if(intval($IncomingLoc) == 1)
				$sql .= "IncomingLoc = 0, ";
			if(intval($IncomingNat) == 1)
				$sql .= "IncomingNat = 0, ";
			if(intval($OutgoingLoc) == 1)
				$sql .= "OutgoingLoc = 0, ";
			if(intval($OutgoingNat) == 1)
				$sql .= "OutgoingNat = 0, ";
			if(intval($Other) == 1)
				$sql .= "Other = 0, "; 
			$sql .= " AccID = $AccountID WHERE AccID = $AccountID";
			$mydb->sql_query($sql);
			
			$sql = "UPDATE tblJobAcctStatus 
							SET IsConfirm = 1,
									ISPDone = 1,	
									ISPDoneDate = '".$now."',
									ISPComment = '".$swcomment."',
									DoneDate = '".$now."',
									SWComment = '".$swcomment."' 
							WHERE JobID = $JID";
			$mydb->sql_query($sql);
			$Description = "Confirm to change account status from $cstatus to $nstatus";
			$addaudit = true;
		}elseif($nstatusid == 5){
			#Unbar
			$sql = "UPDATE tblProductStatus SET ";
			if(intval($incoming) == 1)
				$sql .= "Incoming = 1, ";
			if(intval($Outgoing) == 1)
				$sql .= "Outgoing = 1, ";
			if(intval($International) == 1)
				$sql .= "International = 1, ";
			if(intval($IncomingLoc) == 1)
				$sql .= "IncomingLoc = 1, ";
			if(intval($IncomingNat) == 1)
				$sql .= "IncomingNat = 1, ";
			if(intval($OutgoingLoc) == 1)
				$sql .= "OutgoingLoc = 1, ";
			if(intval($OutgoingNat) == 1)
				$sql .= "OutgoingNat = 1, ";
			if(intval($Other) == 1)
				$sql .= "Other = 1, "; 
			$sql .= " AccID = $AccountID WHERE AccID = $AccountID";
			$mydb->sql_query($sql);

			$sql = "UPDATE tblJobAcctStatus 
							SET IsConfirm = 1, 
							ISPDone = 1,	
							ISPDoneDate = '".$now."',
							ISPComment = '".$swcomment."',
							IsDone = 0, 							
							DoneDate = '".$now."',
							SWComment = '".$swcomment."'
							WHERE JobID = $JID";
			$mydb->sql_query($sql);

			//$sql = "UPDATE tblCustProduct SET StatusID = 1 WHERE AccID = $AccountID and StatusID = 2";
//			$mydb->sql_query($sql);
//			
//			# Insert into product status history as status changed
//			$sql = "INSERT INTO tblAccStatusHistory(AccID, StatusID, ChangeDate, OtherID, OtherText)
//							VALUES(".$AccountID.", 1, '".$now."', 5, 'Temporary disconnect')";
//			$mydb->sql_query($sql);
				
			$Description = "Already to change account status from $cstatus to $nstatus";
			$addaudit = true;
			
		}
		if($addaudit){
				$Audit = new Audit();				
				$Audit->AddAudit($CustomerID, $AccountID, "Confirm pending request", $Description, $user['FullName'], 1, 6);
		}
	}
?>
<script language="javascript" type="text/javascript">
	function done(ID, custid, accid,  Account, cs, ns, nsid){
		if(swcom = prompt("Are you sure that you have done the request for account " + Account + "?\n Please enter your comment.")){
			fdoneit.JID.value = ID;
			fdoneit.conf.value = "yes";			
			fdoneit.CustomerID.value = custid;
			fdoneit.AccountID.value = accid;
			fdoneit.cstatus.value = cs;
			fdoneit.nstatus.value = ns;
			fdoneit.nstatusid.value = nsid;
			fdoneit.swcomment.value = swcom;
			//alert(fdoneit.swcomment.value);
			fdoneit.submit();
		}
	}
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>SWITCH REPORT</b></td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th>Job ID</th>
								<th>Phone</th>
								<th>Status</th>
								<th>Request to</th>
								<th>In all</th>
								<th>In loc</th>
								<th>In nat</th>
								<th>Out all</th>
								<th>Out loc</th>
								<th>Out nat</th>
								<th>Int'</th>
								<th>Other</th>
								<th>Register Date</th>	
								<th>Effective</th>
								<th>Sale Comment</th>
								<th>Done</th>																						
							</thead>
							<tbody>
								<?php
										$sql = "SELECT j.JobID, j.AccID, j.CurrentStatusID, j.NewStatusID, j.SubmitDate, 
															j.EffectiveDate, j.Comment, j.Incoming, j.Outgoing, j.International, j.IncomingLoc,
															j.IncomingNat, j.OutgoingLoc, j.OutgoingNat, j.Other,	a.CustID, a.UserName
														FROM tblJobAcctStatus j, tblCustProduct a, tblTarPackage p
														WHERE j.AccID = a.AccID and a.PackageID = p.PackageID and p.ServiceID = 2 
															and j.IsConfirm = 0 and IsDone = 0 ORDER BY j.EffectiveDate,  j.JobID";

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
													//$stsup = "Activate";
													break;
												case 5:
													$nstbg = $bgActivate;
													$nstfg = $foreActivate;
													$nstwd = "Unbar";
													break;
												default:
													$nstbg = $bgLock;
													$nstfg = $foreLock;
													$nstwd = "Bar";
													break;												
											}
											
											$done = "<a href=\"javascript:done(".$JobID.", ".$CustID.", ".$AccID.", '".$UserName."', '".$stwd."', '".$nstwd."', ".$NewStatusID.");\">Done?</a>";
											$iLoop++;															
											if(($iLoop % 2) == 0)
												$style = "row1";
											else
												$style = "row2";
											print '<tr>';	
											print '<td class="'.$style.'" align="right">'.$JobID.'</td>';
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
											
											print '<td class="'.$style.'" align="left">'.formatDate($SubmitDate, 8).'</td>';
											print '<td class="'.$style.'" align="left">'.formatDate($EffectiveDate, 8).'</td>';
											print '<td class="'.$style.'" align="left">'.$Comment.'</td>';
											print '<td class="'.$style.'" align="center">'.$done.'</td>';
											print '</tr>';
										}
									}
									$mydb->sql_freeresult();	
								?>
							</tbody>												
						</table>
						<form name="fdoneit" method="post" action="./">
							<input type="hidden" name="CustomerID" value="" />
							<input type="hidden" name="AccountID" value="" />
							<input type="hidden" name="conf" value="" />
							<input type="hidden" name="cstatus" value="" />
							<input type="hidden" name="nstatus" value="" />
							<input type="hidden" name="nstatusid" value="" />
							<input type="hidden" name="JID" value="" />													
							<input type="hidden" name="swcomment" value="" />													
							<input type="hidden" name="pg" value="200" />
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>						
</table>
<?php
# Close connection
$mydb->sql_close();
?>