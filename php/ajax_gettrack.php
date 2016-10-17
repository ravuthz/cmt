<?php
		require_once("../common/agent.php");
		require_once("../common/functions.php");
		$AccountID = $_GET['accID'];
		$CustomerID = $_GET['CustomerID'];
		
		global $bgUnactivate, $foreUnactivate, $bgActivate, $foreActivate, $bgLock, $foreLock, $bgClose, $foreClose;
		$stFuture = "No";
		$AcountUI = "";
		$sql = "select  a.TrackID,a.UserName, a.StartBillingDate, a.NextBillingDate, a.RegDate,
								a.StatusID, p.TarName, s.ServiceID, s.ServiceName,a.SubscriptionName
						from tblTrackAccount a, tblTarPackage p, tlkpService s
						where a.PackageID = p.PackageID
							and p.ServiceID = s.ServiceID
							and a.AccID='".$AccountID."' order by TrackID desc";

		if($que = $mydb->sql_query($sql)){		
			$AcountUI = '<table border="0" cellpadding="2" cellspacing="0" style="background-color:#CCCCCC;" align="left" width="100%">';
			while($result = $mydb->sql_fetchrow($que)){				
				$TrackID = $result['TrackID'];
				$AccountName = $result['UserName'];
				$StartBillingDate = $result['StartBillingDate'];
				$NextBillingDate = $result['NextBillingDate'];
				$SetupDate = $result['RegDate'];
				$StatusID = $result['StatusID'];
				$TarName = $result['TarName'];
				$ServiceID = $result['ServiceID'];
				$ServiceName = $result['ServiceName'];				
				$SubscriptionName = $result['SubscriptionName'];				
				
				switch($StatusID){
					case 0: #inactive
						$stbg = $bgUnactivate;
						$stfg = $foreUnactivate;
						$stwd = "Inactive";
						$bl = "[<a href='./?CustomerID=".$CustomerID."&AccountID=".$AccountID."&md=1&cst=".$StatusID."&pg=101'>Activate</a>]";
						break;
					case 1: #active
						$stbg = $bgActivate;
						$stfg = $foreActivate;
						$stwd = "Active";						
						$bl = "[<a href='./?CustomerID=".$CustomerID."&AccountID=".$AccountID."&md=2&cst=".$StatusID."&pg=101'>Bar</a> | 
										<a href='./?CustomerID=".$CustomerID."&AccountID=".$AccountID."&md=3&cst=".$StatusID."&pg=101'>Close</a>
										]";
						break;
					case 2:	# Bar
						$stbg = $bgLock;
						$stfg = $foreLock;
						$stwd = "Barred";						
						$bl = "[<a href='./?CustomerID=".$CustomerID."&AccountID=".$AccountID."&md=1&cst=".$StatusID."&pg=101'>Unbar</a> | 
										<a href='./?CustomerID=".$CustomerID."&AccountID=".$AccountID."&md=3&cst=".$StatusID."&pg=101'>Close</a>
										]";
						break;
					case 3:	# close
						$stbg = $bgClose;
						$stfg = $foreClose;
						$stwd = "Closed";												
						break;
						
					case 4:	# close
						$stbg = $bgClose;
						$stfg = $foreClose;
						$stwd = "Closed";												
						break;
				}
				$linkAccount = "<a href='./?CustomerID=".$CustomerID."&TrackID=".$TrackID."&AccountID=".$AccountID."&pg=91'>".$AccountName."</a>";
				# Get service interface
				$Service = "UI $ServiceName";
				$formTitleBG = getConfigue("$Service");
				$AcountUI .= '

									<tr>
										<td valign="top" colspan=2>
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formsubbody" bordercolor="#aaaaaa">
												<tr>																									
													<td align="left" width="40%" nowrap="nowrap">'.$linkAccount.'      |   <b>'.$SubscriptionName.'</b></td>
													<td align="center" width="10%" bgColor="'.$stbg.'"><font color="'.$stfg.'"><b>'.$stwd.'</b></font></td>													
													<td align="center" width="100"></td>
													<td align="left" width="33%"><b>'.$TarName.'</b> | TrackID : <b>'.$TrackID.'</b></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap" colspan="4">
														Setup date: <b> '
														.formatDate($SetupDate, 3)														
														.' </b> | Start bill date: <b> '
														.formatDate($StartBillingDate, 3)
														.' </b> | next bill date: <b> '
														.formatDate($NextBillingDate, 3)
														.' </b>
													</td>													
												</tr>
											</table>
										</td>
									</tr>		
									<tr bgcolor="#0099FF">
										<td valign="top" colspan=2>
										</td>
									</tr>
												';

			}			
			$AcountUI .= '			
																
								</table> 
							';												
		}else{
			$error = $mydb->sql_error();
			$AcountUI = $myinfo->error("Failed to get account information.", $error['message']);
		}
		$mydb->sql_freeresult();
		print $AcountUI;
?>
	