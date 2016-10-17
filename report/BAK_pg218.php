<?php
	require_once("./common/functions.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	
	$JID = FixQuotes($jid);
	$sql = "SELECT j.*, a.CustID, a.UserName FROM tblJobAcctStatus j INNER JOIN tblCustProduct a ON j.AccID = a.AccID WHERE j.JobID=$JID";
	if($que = $mydb->sql_query($sql)){
		if($result = $mydb->sql_fetchrow($que)){
			$AccID = $result['AccID'];
			$CurrentStatusID = $result['CurrentStatusID'];
			$NewStatusID = $result['NewStatusID'];
			$SubmitDate = $result['SubmitDate'];
			$EffectiveDate = $result['EffectiveDate'];
			$Comment = $result['Comment'];
			$IsDone = $result['IsDone'];
			$IsConfirm = $result['IsConfirm'];
			$Incoming = $result['Incoming'];
			$Outgoing = $result['Outgoing'];
			$International = $result['International'];
			$IncomingLoc = $result['IncomingLoc'];
			$IncomingNat = $result['IncomingNat'];
			$OutgoingLoc = $result['OutgoingLoc'];
			$OutgoingNat = $result['OutgoingNat'];
			$Other = $result['Other'];
			$DoneDate = $result['DoneDate'];
			$SWComment = $result['SWComment'];
			$OpDone = $result['OpDone'];
			$OPDoneDate = $result['OPDoneDate'];
			$OPComment = $result['OPComment'];
			$MDFDone = $result['MDFDone'];
			$MDFDoneDate = $result['MDFDoneDate'];
			$MDFComment = $result['MDFComment'];
			$UserName = $result['UserName'];
			$CustID = $result['CustID'];
			
			$LinkAccount = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
			
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
			
			if (intval($IsConfirm) == 1){
				$IsSw = 'Yes';
			}else{
				$IsSw = 'No';
			}
			
			if (intval($IsDone) == 1){
				$IsCS = 'Yes';
			}else{
				$IsCS = 'No';
			}
			
			if (intval($OpDone) == 1){
				$IsOp = 'Yes';
			}else{
				$IsOp = 'No';
			}
			if (intval($MDFDone) == 1){
				$IsMDF = 'Yes';
			}else{
				$IsMDF = 'No';
			}
		}
	}
?>

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="320" align="left" class="formtitle"><b>ACCOUNT REQUEST REPORT</b></td>
					<td width="0" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">						
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa" bgcolor="#eeeeee">							
							<tr>
								<td align="left">Account name:</td>
								<td align="left"><?php print $LinkAccount; ?></td>
							</tr>
							<tr>
								<td align="left">Status:</td>
								<td align="left"><?php print $stwd;?></td>
							</tr>
							<tr>
								<td align="left" valign="top">Requst to:</td>
								<td align="left">
									<?php
											// all incoming call
											print '<input type="checkbox" name="Incoming" disabled="disabled" class="disabled" value="'.$Incoming.'" ';
											if(intval($Incoming == 1)) 
												print 'checked="checked"';
											print '> '.$nstwd.' all incoming call<br />';
											// incoming comintel local
											print '<input type="checkbox" name="IncomingLoc" disabled="disabled" class="disabled" value="'.$IncomingLoc.'" ';
											if(intval($IncomingLoc == 1)) 
												print 'checked="checked"';
											print '> '.$nstwd.' incoming camintel local<br />';
											// incoming comintel nationwide
											print '<input type="checkbox" name="IncomingLoc" disabled="disabled" class="disabled" value="'.$IncomingNat.'" ';
											if(intval($IncomingNat == 1)) 
												print 'checked="checked"';
											print '> '.$nstwd.' incoming camintel local<br />';
											// all outgoing call
											print '<input type="checkbox" name="Outgoing" disabled="disabled" class="disabled" value="'.$Outgoing.'" ';
											if(intval($Outgoing == 1)) 
												print 'checked="checked"';
											print '> '.$nstwd.' all outgoing call<br />';
											// incoming comintel nationwide
											print '<input type="checkbox" name="OutgoingLoc" disabled="disabled" class="disabled" value="'.$OutgoingLoc.'" ';
											if(intval($OutgoingLoc == 1)) 
												print 'checked="checked"';
											print '> '.$nstwd.' outgoing camintel local<br />';
											// incoming comintel nationwide
											print '<input type="checkbox" name="OutgoingNat" disabled="disabled" class="disabled" value="'.$OutgoingNat.'" ';
											if(intval($OutgoingNat == 1)) 
												print 'checked="checked"';
											print '> '.$nstwd.' outgoing camintel nationwide<br />';
											// International call
											print '<input type="checkbox" name="Inter" disabled="disabled" class="disabled" value="'.$International.'" ';
											if(intval($International == 1)) 
												print 'checked="checked"';
											print '> '.$nstwd.' international call<br />';
											// International call
											print '<input type="checkbox" name="Other" disabled="disabled" class="disabled" value="'.$Other.'" ';
											if(intval($Other == 1)) 
												print 'checked="checked"';
											print '> '.$nstwd.' other<br />';
									?>
								</td>
							</tr>
							<tr>
								<td align="left">Request date: </td>
								<td align="left"><?php print FormatDate($SubmitDate, 6); ?></td>
							</tr>
							<tr>
								<td align="left">Effective date: </td>
								<td align="left"><?php print FormatDate($EffectiveDate, 6); ?></td>
							</tr>
							<tr>
								<td align="left" valign="top">Comment: </td>
								<td align="left"><?php print $Comment; ?></td>
							</tr>
							<tr>
								<td colspan="2" bgcolor="#aaaaaa">SWITCH</td>
							</tr>
							<tr>
								<td align="left">Switch done: </td>
								<td align="left"><?php print $IsSw; ?></td>
							</tr>
							<tr>
								<td align="left">Switch done date: </td>
								<td align="left"><?php   print FormatDate($DoneDate, 6); ?></td>
							</tr>
							<tr>
								<td align="left" valign="top">Switch comment: </td>
								<td align="left"><?php print $SWComment; ?></td>
							</tr>
							<tr>
								<td colspan="2" bgcolor="#aaaaaa">MDF</td>
							</tr>
							<tr>
								<td align="left">MDF done: </td>
								<td align="left"><?php print $IsMDF; ?></td>
							</tr>
							<tr>
								<td align="left">MDF done date: </td>
								<td align="left"><?php   print FormatDate($MDFDoneDate, 6); ?></td>
							</tr>
							<tr>
								<td align="left" valign="top">MDF comment: </td>
								<td align="left"><?php print $MDFComment; ?></td>
							</tr>
							<tr>
								<td colspan="2" bgcolor="#aaaaaa">OPERATION</td>
							</tr>
							<tr>
								<td align="left" valign="top">Operation done: </td>
								<td align="left"><?php print $IsOp; ?></td>
							</tr>
							<tr>
								<td align="left" valign="top">Operation done date: </td>
								<td align="left"><?php print FormatDate($OPDoneDate, 6); ?></td>
							</tr>
							<tr>
								<td align="left" valign="top" nowrap="nowrap">Operation comment: </td>
								<td align="left"><?php print $OPComment; ?></td>
							</tr>
							<tr>
								<td colspan="2" bgcolor="#aaaaaa">Customer Service</td>
							</tr>
							<tr>
								<td align="left" valign="top">CS confirm: </td>
								<td align="left"><?php print $IsCS; ?></td>
							</tr>
						</table>						
					</td>
				</tr>
			</table>		
		</td>
	</tr>		
	<tr><td>&nbsp;</td></tr>				
	<tr><td>
		<div id="d-future_action">
		</div>
	</td></tr>
	<tr>
	  <td>
	  	
	  </td>
  </tr>
</table>