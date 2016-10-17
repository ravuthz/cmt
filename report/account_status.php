<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];
	$serviceid = $_GET['serviceid'];
	
	//$cpe = $_GET['cpe'];	
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>ACCOUNT STATUS REPORT From '.formatDate($st, 6).' and '.formatDate($et, 6).'</b>
					</td>
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
								<th>Sales</th>
								<th>Comment</th>
								<th>SW</th>
								<th>Comment</th>
								<th>MDF</th>
								<th>Comment</th>	
								<th>ISP</th>
								<th>Comment</th>															
								<th>OP</th>
								<th>Comment</th>
								<th>CS</th>																
							</thead>
							<tbody>';
							
$sql = "SELECT j.JobID, j.AccID, j.CurrentStatusID, j.NewStatusID, convert(varchar,j.SubmitDate,120) SubmitDate, j.Incoming, j.Outgoing,
					j.International, j.IncomingLoc, j.IncomingNat, j.OutgoingLoc, j.OutgoingNat, j.Other, j.Comment, j.IsDone, convert(varchar,j.DoneDate,12) DoneDate,
					j.SWComment, j.OpDone, convert(varchar,j.OPDoneDate,120) OPDoneDate, j.OPComment, convert(varchar,j.MDFDoneDate,120) MDFDoneDate, j.MDFComment, convert(varchar,j.ISPDoneDate,120) ISPDoneDate, j.ISPComment, j.Incoming, a.CustID, a.UserName, convert(varchar,j.csDate,120) csDate,ta.tarName,left(a.subscriptionName,30) SubName					 
				FROM tblJobAcctStatus j(nolock), tblCustProduct a(nolock), tblTarPackage ta(nolock) 
				WHERE j.AccID = a.AccID AND a.PackageID = ta.PackageID";
				
			if($serviceid == 2){
				$sql .= " and ta.ServiceID = 2 ";
			}elseif($serviceid == 4){
				$sql .= " and ta.ServiceID = 4 ";
			}elseif($serviceid == 5){	
				$sql .= " and ta.ServiceID in (1, 3, 8) ";
			}else
				$sql .= " and ta.ServiceID in (1, 2, 3, 4, 8) ";
				
$sql .= " AND convert(varchar, j.SubmitDate, 112) BETWEEN '".FormatDate($st, 4)."' AND '".FormatDate($et, 4)."'";							
$sql .= " ORDER BY Case 
when ta.serviceid = 2 then 'aTelephone'
when ta.serviceid = 4 then 'cLeaseLine'
else 'bISP' end
,j.SubmitDate,a.UserName ";

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
											$UserName = $result['UserName'];
											$SaleComment = $result['Comment'];
											$IsDone = $result['IsDone'];
											$DoneDate = $result['DoneDate'];
											$SWComment = $result['SWComment'];
											$ISPDoneDate = $result['ISPDoneDate'];
											$ISPComment = $result['ISPComment'];
											$MDFDoneDate = $result['MDFDoneDate'];
											$MDFComment = $result['MDFComment'];
											$OpDone = $result['OpDone'];
											$OPDoneDate = $result['OPDoneDate'];
											$OPComment = $result['OPComment'];
											$Incoming = $result['Incoming'];
											$csDate = $result['csDate'];
										//	$linkAccount = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
											$linkAccount = "<a href='../?jid=".$JobID."&pg=218'>".$UserName."</a>";
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
												case 4:
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
											$retOut .= '<td class="'.$style.'" align="left">'.$SubName.'</td>';
											$retOut .= '<td class="'.$style.'" align="left">'.$tarName.'</td>';
											$retOut .= '<td align="center" bgcolor="'.$stbg.'">
															<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
														 </td>';
											$retOut .= '<td align="center" bgcolor="'.$nstbg.'">
															<font color="'.$nstfg.'"><b>'.$nstwd.'</b></font>
														 </td>';
											
											
											//sales
											$retOut .= '<td class="'.$style.'" align="left">'.formatDate($SubmitDate, 8).'</td>';
											$retOut .= '<td class="'.$style.'" align="left">'.$SaleComment.'</td>';
											//switch
											$retOut .= '<td class="'.$style.'" align="left">'.formatDate($DoneDate, 8).'</td>';
											$retOut .= '<td class="'.$style.'" align="left">'.$SWComment.'</td>';
											//MDF
											$retOut .= '<td class="'.$style.'" align="left">'.formatDate($MDFDoneDate, 8).'</td>';
											$retOut .= '<td class="'.$style.'" align="left">'.$MDFComment.'</td>';
											//ISP
											$retOut .= '<td class="'.$style.'" align="left">'.formatDate($ISPDoneDate, 8).'</td>';
											$retOut .= '<td class="'.$style.'" align="left">'.$ISPComment.'</td>';
											//OP
											$retOut .= '<td class="'.$style.'" align="left">'.formatDate($OPDoneDate, 8).'</td>';
											$retOut .= '<td class="'.$style.'" align="left">'.$OPComment.'</td>';
											//CS
											$retOut .= '<td class="'.$style.'" align="left">'.formatDate($csDate, 8).'</td>';
											$retOut .= '</tr>';
										}
									}
									$mydb->sql_freeresult();	

		$retOut .= '</tbody>												
				</table>
				</td></tr></table>';
		
	print $retOut;	
?>