<?php
	require_once("../common/agent.php");	
	require_once("../common/class.audit.php");
	require_once("../common/class.invoice.array.php");	

?>
<?php
		
		$Audit = new Audit();	
		
		$cmd = FixQuotes($cmd);
		$md = FixQuotes($md);
		$cmd = $md;
		$txtChangeOn = FixQuotes($txtChangeOn);
		$comment = FixQuotes($comment);
		$cst = FixQuotes($cst);
		$AccountID = FixQuotes($AccountID);
		$CustomerID = FixQuotes($CustomerID);
		$supid = FixQuotes($supid);
		$UserName = FixQuotes($UserName);
		$PackageID = FixQuotes($PackageID);
		$SubscriptionName = FixQuotes($sName);

		
		//$today = date("Y-M-d H:i:s",strtotime("-1 hours"));
		//$today = date("Y-m-d h:m:s");
		
		$today = date("Y-m-d h:m:s");
		
		if(is_null($txtChangeOn) || (empty($txtChangeOn))){
			$txtChangeOn = $today;
		} 

		
			# get serviceid 
			$sql = "SELECT Top 1 t.ServiceID 
							FROM tblTrackAccount a, tblTarPackage t
							WHERE a.PackageID = t.PackageID AND a.AccID = $AccountID order by TrackID desc";
							
			if($que = $mydb->sql_query($sql)){
				$result = $mydb->sql_fetchrow($que);
				$ServiceID = $result["ServiceID"];
			}
			$mydb->sql_freeresult($que);
			
			if($ServiceID == 4){
				$IsConfirm = 1;
				$OpDone = 1;
				$DoneDate = $today;
				$OPDoneDate = $today;
			}else{
			}
			
			
			# update panding account
			$sql = "UPDATE tblJobAcctStatus Set 
											IsDone = 1, 
											IsConfirm = 1,
											OpDone = 1 
							WHERE AccID = $AccountID and IsDone = 0";
			$mydb->sql_query($sql);
			
			if($cmd == 0 && $cst == 4) //////////   Reconnect
			{	
				$sql = "delete from tblTrackAccount where statusID=0 and AccID=".$AccountID;
				$mydb->sql_query($sql);
								
				$sql = "INSERT INTO tblTrackAccount(AccID,SubscriptionName,UserName,Password,PackageID,StatusID,RegDate,
							RegBy,Track,bHouse,bStreet,bSangkatID,bKhanID,bCityID,bCountryID,iHouse1,iStreet1,iSangkatID1,iKhanID1,iCityID1,iCountryID1,iHouse2
							,iStreet2,iSangkatID2,iKhanID2,iCityID2,iCountryID2,Context)
						select Top 1 AccID,'".$SubscriptionName."','".$UserName."',Password,".$PackageID.",0,RegDate
						,RegBy,'Rec',bHouse,bStreet,bSangkatID,bKhanID,bCityID,bCountryID,iHouse1,iStreet1,iSangkatID1,iKhanID1,iCityID1,iCountryID1,
						iHouse2,iStreet2,iSangkatID2,iKhanID2,iCityID2,iCountryID2,Context from tblTrackAccount where AccID=".$AccountID." order by TrackID desc";
				
				$mydb->sql_query($sql);
				
				$sql = "Update tblCustProduct set Track='Rec', statusID=0 , NextBillingDate = Null, StartBillingDate = Null, AccountEndDate = Null, UserName= '".$UserName."', SubscriptionName= '".$SubscriptionName."', PackageID = ".$PackageID."
				where AccID=".$AccountID;
				$mydb->sql_query($sql);
			}
			
				if($ServiceID == 4){
					$sql = "INSERT INTO tblJobAcctStatus(AccID, CurrentStatusID, NewStatusID, SubmitDate, EffectiveDate, 
														Comment, IsDone, IsConfirm, Incoming, Outgoing, International, IncomingLoc, IncomingNat,
														OutgoingLoc, OutgoingNat, Other, OpDone, DoneDate, OPDoneDate, MDFDone, MDFDoneDate, MDFComment)
									VALUES($AccountID, $cst, $cmd, '".$today."', '".formatDate($txtChangeOn, 8)."', 
														'Auto by User', 0, 1, 0, 0, 0,
															0, 0, 0, 0, 0, 1, 
															'".$today."', '".$today."', 1, '".$today."', 'AUTO DONE')";
				}elseif($ServiceID == 2){
					$sql = "INSERT INTO tblJobAcctStatus(AccID, CurrentStatusID, NewStatusID, SubmitDate, EffectiveDate, Comment, 
							IsDone, IsConfirm, Incoming, Outgoing, International, IncomingLoc, IncomingNat,OutgoingLoc, 
							OutgoingNat, Other, OpDone, MDFDone, MDFDoneDate, IspDone,  IspDoneDate,OPComment)
							VALUES($AccountID, $cst, $cmd, '".$today."', '".formatDate($txtChangeOn, 8)."','Auto by User', 0, 1, 1
							, 1, 0,	0, 0, 0, 0, 0, 1, 1, '".$today."', 1, '".$today."','Auto by User')";
																												
				}else{
					#ISP Billing
					/*$sql = "INSERT INTO tblJobAcctStatus(AccID, CurrentStatusID, NewStatusID, SubmitDate, EffectiveDate, 
														Comment, IsDone, IsConfirm, Incoming, Outgoing, International, IncomingLoc, IncomingNat,
														OutgoingLoc, OutgoingNat, Other, ISPDone, MDFDone, OpDone)
									VALUES($AccountID, $cst, $cmd, '".$today."', '".formatDate($txtChangeOn, 8)."', 
														'Auto by User', 0, 1, 0, 0, 0,
															0, 0, 0, 0, 0, 0, 0, 0)";*/
															
					$sql = "INSERT INTO tblJobAcctStatus(AccID, CurrentStatusID, NewStatusID, SubmitDate, EffectiveDate, 
														Comment, IsDone, IsConfirm, Incoming, Outgoing, International, IncomingLoc, IncomingNat,
														OutgoingLoc, OutgoingNat, Other, ISPDone, MDFDone, OpDone)
									VALUES($AccountID, $cst, $cmd, '".$today."', '".formatDate($txtChangeOn, 8)."', 
														'Auto by User', 0, 1, 0, 0, 0,
															0, 0, 0, 0, 0, 1, 1, 1)";
				}
				
				if($que = $mydb->sql_query($sql)){					
					$comment = $sttext." on $txtChangeOn. $comment";
					$Audit->AddAudit($CustomerID, $AccountID, "Change account status", $comment, $user['FullName'], 1, 6);
					print "1";
				}else{
					$error = $mydb->sql_error();					
					print "0";
				}

?>
<?php

$mydb->sql_close();
?>
