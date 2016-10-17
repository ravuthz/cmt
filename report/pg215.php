 <?php
	require_once("./common/agent.php");
	require_once("./common/configs.php");
	require_once("./common/class.account.php");	
	require_once("./common/functions.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
		
	if($conf == "yes"){
		$JID = FixQuotes($JID);
		$opcomment = FixQuotes($opcomment);
		$now = date("Y/M/d H:i:s");
		
		$sql = "UPDATE tblJobAcctStatus SET
							OpDone = 1,
							OPDoneDate = '".$now."',
							OPComment = '".$opcomment."'
						WHERE JobID = $JID
						";
		$mydb->sql_query($sql);
	}
?>
<script language="javascript" type="text/javascript">
	function done(ID, Account){
		if(opcom = prompt("Are you sure that you have done the request for account " + Account + "?\nPlease enter your comment.", "Enter comment")){
			fdoneit.JID.value = ID;
			fdoneit.conf.value = "yes";						
			fdoneit.opcomment.value = opcom;
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
					<td align="left" class="formtitle"><b>ACCOUNT REQUEST PENDING REPORT</b></td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th>Phone</th>
								<th>Customer</th>
								<th>Package</th>
								<th>Status</th>
								<th>Request to</th>
								<th>Register Date</th>
								<th>Sale Comment</th>
								<th>SW comment</th>
								<th>MDF comment</th>
								<th>Done</th>																											
							</thead>
							<tbody>
								<?php
										$sql = "SELECT j.JobID, j.AccID, j.CurrentStatusID, j.NewStatusID, j.SubmitDate, j.Comment 'SaleComment',
															j.SWComment, j.ISPComment, j.MDFComment, j.Incoming, j.Outgoing, j.International, j.IncomingLoc,
															j.IncomingNat, j.OutgoingLoc, j.OutgoingNat, j.Other,	a.CustID, a.UserName,a.subscriptionName 'SubName', tp.tarName
														FROM tblJobAcctStatus j, tblCustProduct a, tblTarPackage tp
														WHERE j.AccID = a.AccID and a.packageid=tp.packageid and j.IsConfirm = 1 and MDFDone = 1 and j.OpDone = 0 and j.IsDone = 0 
														ORDER BY j.EffectiveDate,  j.JobID";

									if($que = $mydb->sql_query($sql)){
										while($result = $mydb->sql_fetchrow()){																
											$JobID = $result['JobID'];																
											$AccID = $result['AccID'];
											$SubName = $result['SubName'];
											$tarName = $result['tarName'];
											$CurrentStatusID = $result['CurrentStatusID'];
											$NewStatusID = intval($result['NewStatusID']);
//											$StatusID = intval($result['StatusID']);
											$SubmitDate = $result['SubmitDate'];																
											$SaleComment = $result['SaleComment'];
											$SWComment = $result['SWComment'];
											$ISPComment = $result['ISPComment'];
											if(is_null($SWComment) || ($SWComment == ""))
												$Comment = $ISPComment;
											else
												$Comment = $SWComment;
											$MDFComment = $result['MDFComment'];
											$CustID = $result['CustID'];
											$UserName = $result['UserName'];
											$Incoming = $result['Incoming'];
											$Outgoing = $result['Outgoing'];
											$International = $result['International'];											

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
													break;
												default:
													$nstbg = $bgLock;
													$nstfg = $foreLock;
													$nstwd = "Bar";
													break;												
											}											
											$iLoop++;															
											if(($iLoop % 2) == 0)
												$style = "row1";
											else
												$style = "row2";
												
											$done = "<a href=\"javascript:done(".$JobID.", '".$UserName."');\">Done?</a>";
											$linkAccount = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
												
											print '<tr>';	
											//print '<td class="'.$style.'" align="right">'.$JobID.'</td>';
											print '<td class="'.$style.'" align="left">'.$linkAccount.'</td>';
											print '<td class="'.$style.'" align="left">'.$SubName.'</td>';
											print '<td class="'.$style.'" align="left">'.$tarName.'</td>';
											print '<td align="center" bgcolor="'.$stbg.'">
															<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
														 </td>';
											print '<td align="center" bgcolor="'.$nstbg.'">
															<font color="'.$nstfg.'"><b>'.$nstwd.'</b></font>
														 </td>';
											
											print '<td class="'.$style.'" align="left">'.formatDate($SubmitDate, 8).'</td>';
											print '<td class="'.$style.'" align="left">'.$SaleComment.'</td>';
											print '<td class="'.$style.'" align="left">'.$Comment.'</td>';
											print '<td class="'.$style.'" align="left">'.$MDFComment.'</td>';
											print '<td class="'.$style.'" align="left">'.$done.'</td>';
											print '</tr>';
										}
									}
									$mydb->sql_freeresult();	
								?>
							</tbody>												
						</table>	
						<form name="fdoneit" method="post" action="./">				
							<input type="hidden" name="conf" value="" />
							<input type="hidden" name="JID" value="" />
							<input type="hidden" name="pg" value="215" />									
							<input type="hidden" name="opcomment" value="" />									
						</form>					
					</td>
				</tr>
			</table>
		</td>
	</tr>						
</table>