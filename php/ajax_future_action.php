<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>ACCOUNT REQUEST REPORT From '.formatDate($st, 6).' and '.formatDate($et, 6).'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
	
	<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th>Phone</th>
								<th width="70">Status</th>
								<th width="70">Request to</th>
								<th>In all</th>
								<th>In loc</th>
								<th>In nat</th>
								<th>Out all</th>
								<th>Out loc</th>
								<th>Out nat</th>
								<th>Int</th>
								<th>Other</th>
								<th>Date</th>	
								<th>Effective</th>																			
							</thead>
							<tbody>';
							
$sql = "SELECT j.JobID, j.AccID, j.CurrentStatusID, j.NewStatusID, j.SubmitDate, j.IsConfirm, j.IsDone, j.EffectiveDate, j.Comment, j.Incoming, j.Outgoing, j.International, j.IncomingLoc, 
j.IncomingNat, j.OutgoingLoc, j.OutgoingNat, j.Other, a.CustID, a.UserName 
		FROM tblJobAcctStatus j, tblCustProduct a WHERE j.AccID = a.AccID
		and convert(varchar, j.EffectiveDate, 112) >= '".formatDate($st, 4)."' 
		and convert(varchar, j.EffectiveDate, 112) <= '".formatDate($et, 4)."'
		ORDER BY j.EffectiveDate";

									if($que = $mydb->sql_query($sql)){
										while($result = $mydb->sql_fetchrow()){																
											$JobID = $result['JobID'];																
											$AccID = $result['AccID'];
											$CurrentStatusID = $result['CurrentStatusID'];
											$NewStatusID = intval($result['NewStatusID']);
//											$StatusID = intval($result['StatusID']);
											$SubmitDate = $result['SubmitDate'];
											$IsConfirm = $result['IsConfirm'];
											$IsDone = $result['IsDone'];																
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
										//	$linkAccount = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
											$linkAccount = "<a href='./?jid=".$JobID."&pg=218'>".$UserName."</a>";
											if ($result['Isconfirm'] == 1){
												$IsConfirm = 'Yes';
											}else{
												$IsConfirm = 'No';
											}
											
											if ($result['IsDone'] == 1){
												$IsDone = 'Yes';
											}else{
												$IsDone = 'No';
											}
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
											
											//$done = "<a href=\"javascript:done(".$JobID.", ".$CustID.", ".$AccID.", '".$UserName."', '".$stwd."', '".$nstwd."', ".$NewStatusID.");\">Done?</a>";
											$iLoop++;															
											if(($iLoop % 2) == 0)
												$style = "row1";
											else
												$style = "row2";
											$retOut .= '<tr>';	
											
											$retOut .= '<td class="'.$style.'" align="left">'.$linkAccount.'</td>';
											$retOut .= '<td align="center" bgcolor="'.$stbg.'">
															<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
														 </td>';
											$retOut .= '<td align="center" bgcolor="'.$nstbg.'">
															<font color="'.$nstfg.'"><b>'.$nstwd.'</b></font>
														 </td>';
											#incoming
											$retOut .= '<td align="center">';
											$retOut .= '<input type="checkbox" name="Incoming" disabled="disabled" class="disabled" value="'.$Incoming.'" ';
											if(intval($Incoming == 1)) 
												$retOut .= 'checked="checked"';
											$retOut .= '></td>';
											$retOut .= '<td align="center">';
											$retOut .= '<input type="checkbox" name="IncomingLoc" disabled="disabled" class="disabled" value="'.$IncomingLoc.'" ';
											if(intval($IncomingLoc == 1)) 
												$retOut .= 'checked="checked"';
											$retOut .= '></td>';
											$retOut .= '<td align="center">';
											$retOut .= '<input type="checkbox" name="IncomingNat" disabled="disabled" class="disabled" value="'.$IncomingNat.'" ';
											if(intval($IncomingNat == 1)) 
												$retOut .= 'checked="checked"';
											$retOut .= '></td>';
											
											#outgoing
											$retOut .= '<td align="center">';
											$retOut .= '<input type="checkbox" name="Outgoing" disabled="disabled" class="disabled" value="'.$Outgoing.'" ';
											if(intval($Outgoing == 1)) $retOut .= 'checked="checked"';
											$retOut .= '></td>';
											$retOut .= '<td align="center">';
											$retOut .= '<input type="checkbox" name="OutgoingLoc" disabled="disabled" class="disabled" value="'.$OutgoingLoc.'" ';
											if(intval($OutgoingLoc == 1)) $retOut .= 'checked="checked"';
											$retOut .= '></td>';
											$retOut .= '<td align="center">';											
											$retOut .= '<input type="checkbox" name="OutgoingNat" disabled="disabled" class="disabled" value="'.$OutgoingNat.'" ';
											if(intval($OutgoingNat == 1)) $retOut .= 'checked="checked"';
											$retOut .= '></td>';
											$retOut .= '<td align="center">';
											
											$retOut .= '<input type="checkbox" name="International" disabled="disabled" class="disabled" value="'.$International.'" ';
											if(intval($International == 1)) $retOut .= 'checked="checked"';
											$retOut .= '></td>';
											$retOut .= '<td align="center">';											
											$retOut .= '<input type="checkbox" name="Other" disabled="disabled" class="disabled" value="'.$Other.'" ';
											if(intval($Other == 1)) $retOut .= 'checked="checked"';
											$retOut .= '></td>';
											
											$retOut .= '<td class="'.$style.'" align="left">'.formatDate($SubmitDate, 3).'</td>';
											$retOut .= '<td class="'.$style.'" align="left">'.formatDate($EffectiveDate, 3).'</td>';											
											
											$retOut .= '</tr>';
										}
									}
									$mydb->sql_freeresult();	

		$retOut .= '</tbody>												
				</table>
				</td></tr></table>';
	print $retOut;	
?>