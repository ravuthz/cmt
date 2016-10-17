<?php

require_once("agent.php");
require_once("functions.php");
require_once("class.invoice.array.php");

	class Account{
		var $AccountID;
		var $TrackID;
		
		function Account($AccountID,$TrackID = 0){
			$this->AccountID = $AccountID;
			$this->TrackID = $TrackID;
		}
		
		//
		//	Activate account
		//
		
		function Activate($StartBillingDate = ""){			
			global $mydb, $myinfo, $CustomerID;
			
			# get start bill 
			if($StartBillingDate == "")	$StartBillingDate = date("Ymd H:i:s");
			#Get next bill
			$NextBillingDate = $this->GetNextBill();
			# Get service type
			$ServiceID = $this->GetServiceType();
			# get monthly fee mode			
			$monthlyfee = $this->GetMonthlyMode();			

			# lease line
			if($ServiceID == 4){				
				
				$sql = "select ConfigueValue from globalconfigs where ConfigueName = 'Lease Line Charge Type'";				
				if($que = $mydb->sql_query($sql)){
					$result = $mydb->sql_fetchrow($que);
					$leaseChargeType = $result["ConfigueValue"];
					if(intval($leaseChargeType) == 1){ # prepay ment						
						
						
							$sql = "
							
										Declare @BillEndTel datetime
										Select @BillEndTel = MIN(BillEndDate) from tblSysBillRunCycleInfo where BillProcessed = 0
										
										Select	tp.CycleFee,
												DefaultCycleID = (select CycleID from tblSysBillRunCycleInfo where BillProcessed = 0 and PackageID = 67 and BillEndDate = @BillEndTel), 
												MIN(BillStartDate) BillStartLL, 
												MIN(BillEndDate) BillEndLL, 
												
												StartChargeDate = Convert(datetime,'".$StartBillingDate."'), 
												
												EndChargeDate = DATEADD(dd,-1,MIN(BillStartDate)),
												OneCycleFee = tp.CycleFee / (DATEDIFF(mm,MIN(BillStartDate),MIN(BillEndDate)) + 1),
												UsedMonth = Convert(numeric(15,10), 0.00),
												AmountCharge = Convert(money,0.00)
										Into ___LL		
										from tblCustProduct cp
										join tblSysBillRunCycleInfo sb on cp.PackageID = sb.PackageID
										join tblTarPackage tp on tp.PackageID = sb.PackageID
										where tp.ServiceID in (select ServiceID from tlkpService where GroupServiceID = 4)
										and sb.BillProcessed = 0
										and BillEndDate <> @BillEndTel
										
										and cp.AccID = ".$this->AccountID."
										
										Group by tp.CycleFee
										
										Update ___LL Set	UsedMonth =	Case
										 								When DAY(StartChargeDate) = 1 then DATEDIFF(month,StartChargeDate,BillStartLL)
																		Else (DateDiff(dd,StartChargeDate,dbo.fn_getLastCurrentDate(StartChargeDate)) + 1)/30.00 + DATEDIFF(mm,DateAdd(dd,1,dbo.fn_getLastCurrentDate(StartChargeDate)),BillStartLL)
																	End							
													
										Update ___LL Set AmountCharge = UsedMonth * OneCycleFee
										where StartChargeDate < BillStartLL
										
																	
										Select * from ___LL

										Drop table ___LL							
							";										
							
							
							$sqlx = "
							
										Exec sp_createregistration ".$this->AccountID.",'".$StartBillingDate."'
							";										
							
							
							
							if($que = $mydb->sql_query($sql)){
								if($result = $mydb->sql_fetchrow($que)){
										$reStartBillingDate = $result['StartChargeDate'];
										$reNextBillingDate = $result['EndChargeDate'];
										
										$reNextStartBillingDate = $result['BillStartLL'];
										$reNextNextBillingDate = $result['BillEndLL'];
										
										$cycleFee = $result['AmountCharge'];
										$cycleID = $result['DefaultCycleID'];
										
										
										$StartBillingDate = $reNextStartBillingDate;
										$NextBillingDate = $reNextNextBillingDate;
										 
										$BillItemID = getInvoiceItem("Maintenance Fee");
										if((is_null($BillItemID)) || (empty($BillItemID))){	
											return "No invoice item was set for item [Monthly fee]";
										}
										#	Create invoice
										$invoice = new Invoice();
										$InvoiceID = $invoice->GetInvoiceID();
										
										# Invoice detail
										
										$retOut = $invoice->CreateInvoiceDetail($CustomerID, $this->AccountID, $InvoiceID, $BillItemID, $cycleFee, 0, 1, $this->TrackID);
										if($retOut){					
											# Create invoice
											$retOut = $invoice->CreateInvoice($CustomerID, $this->AccountID, $InvoiceID, $reStartBillingDate, $reNextBillingDate, $this->TrackID);
											
											$sql1 = "Update tblCustomerInvoiceDetail set BillingCycleID=".$cycleID." where InvoiceID=".$InvoiceID;
											$mydb->sql_query($sql1);
											$sql2 = "Update tblCustomerInvoice set BillingCycleID=".$cycleID." where InvoiceID=".$InvoiceID;
											$mydb->sql_query($sql2);
											
									//		print $retOut;
											if($retOut){
											}else{
												return $retOut;
											}
										}else{
											return $retOut;
										}
									
									
									
								}
							}
							
					}else{
						$cycleFee = 0;
					}
				}
				$mydb->sql_freeresult($que);				
			}else{# non leaseline	
				if($monthlyfee == 2){
					return $myinfo->error("Failed to get monthly fee payment mode. Please contact administrator.");
				}elseif($monthlyfee == 1){				
					# Get customer and cycle fee
					$sql = "SELECT a.CustID, t.CycleFee 
									FROM tblCustProduct a, tblTarPackage t 
									WHERE a.PackageID = t.PackageID and a.AccID = ".$this->AccountID;	
					if($que = $mydb->sql_query($sql)){
						if($result = $mydb->sql_fetchrow($que)){
							$CustomerID = $result['CustID'];
							$cycleFee = $result['CycleFee'];
						}
					}
								
					if(is_null($cycleFee) || empty($cycleFee)) $cycleFee = 0;
					#	get monthly charge fee
					$ChargeAmount = $this->ChargeMonthly($cycleFee, $StartBillingDate, $NextBillingDate);			
					//return $cycleFee.",".$StartBillingDate.",".$NextBillingDate;
					# get Bill Item ID
					$BillItemID = getInvoiceItem("Maintenance Fee");
					if((is_null($BillItemID)) || (empty($BillItemID))){	
						return "No invoice item was set for item [Monthly fee]";
					}
					#	Create invoice
					$invoice = new Invoice();
					$InvoiceID = $invoice->GetInvoiceID();
					
					# Invoice detail
					
					$retOut = $invoice->CreateInvoiceDetail($CustomerID, $this->AccountID, $InvoiceID, $BillItemID, $ChargeAmount, 0, 0, $this->TrackID);
					if($retOut){					
						# Create invoice
						$retOut = $invoice->CreateInvoice($CustomerID, $this->AccountID, $InvoiceID, $StartBillingDate, $NextBillingDate, $this->TrackID);
						if($retOut){
						}else{
							return $retOut;
						}
					}else{
						return $retOut;
					}
					//=============================================
				}
				
			}// end monthlyfee
			#	update account
			if($ServiceID == 2)
			{
				$sql = "Delete from tblProductStatus WHERE AccID =".$this->AccountID;	
				$mydb->sql_query($sql);
				
				$sql = "Insert into tblProductStatus(AccID,Incoming,Outgoing,International,IncomingLoc,IncomingNat
						,OutgoingLoc,OutgoingNat,Other)
					     select top 1 AccID,Incoming,Outgoing,International,IncomingLoc,IncomingNat,OutgoingLoc,OutgoingNat,Other
						 from tblJobAcctStatus
							WHERE AccID= ".$this->AccountID;	
				$mydb->sql_query($sql);
				
			}
			$sql = "UPDATE tblCustProduct SET 
									StartBillingDate = '".$StartBillingDate."',
									NextBillingDate = '".$NextBillingDate."',
									MaxTrackID = ".$this->TrackID.",
									StatusID = 1,
									bHouse = (select bHouse from tblTrackAccount where TrackID=".$this->TrackID."),
									bStreet = (select bStreet from tblTrackAccount where TrackID=".$this->TrackID."),
									bCountryID = (select bCountryID from tblTrackAccount where TrackID=".$this->TrackID."),
									bCityID = (select bCityID from tblTrackAccount where TrackID=".$this->TrackID."),
									bSangkatID = (select bSangkatID from tblTrackAccount where TrackID=".$this->TrackID."),
									bKhanID = (select bKhanID from tblTrackAccount where TrackID=".$this->TrackID.")
							WHERE AccID= ".$this->AccountID;

			if($que = $mydb->sql_query($sql)){
				
				$sql = "UPDATE tblTrackAccount SET 
									StartBillingDate = '".$StartBillingDate."',
									NextBillingDate = '".$NextBillingDate."',
									StatusID = 1
							WHERE TrackID= ".$this->TrackID;
				if($que = $mydb->sql_query($sql)){			
					return true;
				}
				else
				{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to update account start / next billing date in tblTrackAccount.", $error['message']);
				}
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to update account start / next billing date in tblCustProduct.", $error['message']);
			}
		}// end function		
		
		
		function Close($EndDate = ""){
			global $mydb, $myinfo;
			if($EndDate == "") $EndDate = date("Ymd H:i:s");
			$sql = "UPDATE tblProductStatus SET
								Incoming = 0,
								Outgoing = 0,
								International = 0
							WHERE AccID = ".$this->AccountID;	
			if($mydb->sql_query($sql)){
				$sql = "UPDATE tblCustProduct SET Track='Close', 
									StatusID = Case 
													when Exists(select * from tblCustProduct cp join tblTarPackage tp on cp.packageid = tp.packageid where serviceid = 4 and accid = ".$this->AccountID.") then 4
										Else 3
									 End,
									AccountEndDate = '".$EndDate."'
								WHERE AccID = ".$this->AccountID;	
				if($mydb->sql_query($sql)){
					$sql = "UPDATE tblTrackAccount SET Track='Close', 
									StatusID = Case 
													when Exists(select * from tblTrackAccount cp join tblTarPackage tp on cp.packageid = tp.packageid where serviceid = 4 and TrackID = ".$this->TrackID.") then 4
										Else 3
									 End,
									AccountEndDate = '".$EndDate."'
								WHERE TrackID = ".$this->TrackID;	
					if($mydb->sql_query($sql)){
							return true;
					}
					else
					{
					
					}
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to close product.", $error['message']);
				}	
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to bar product status.", $error['message']);
			}			
		}		
		
		//
		//	Get monthly fee type
		//	@ Return 
		//			
		//			0: Post monthly fee
		//			1: Advanced monthly fee		
		//			2: Error
		//
		
		function GetMonthlyMode(){
			global $mydb, $myinfo;
			$sql = "SELECT ConfigueValue FROM globalconfigs WHERE ConfigueName = 'Service charge'";
			if($que = $mydb->sql_query($sql)){
				if($result = $mydb->sql_fetchrow($que)){
					return $result['ConfigueValue'];
				}
			}else{
				return "2"; 
			}
		}// end function get monthly mode
		
		//
		//	Get next bill
		//
		function GetNextBill(){
			global $mydb;
			$sql = "select top 1 Convert(varchar, b.BillEndDate, 120) as 'NextBill' 
							from tblSysBillRunCycleInfo b, tblCustProduct a 
							where a.PackageID = b.PackageID and a.AccID = ".$this->AccountID. " and b.BillProcessed = 0 order by BillEndDate";		
			if($que = $mydb->sql_query($sql)){
				if($result = $mydb->sql_fetchrow($que)){
					return $result['NextBill'];
				}
			}
			$mydb->sql_freeresult($que);
		}
		
		//
		// Get Last cycle bill
		//
		function GetLastBill(){
			global $mydb;
			$sql = "select top 1 Convert(varchar, b.BillStartDate, 120) as 'LastBill'
							from tblSysBillRunCycleInfo b, tblCustProduct a
							where a.PackageID = b.PackageID and a.AccID = ".$this->AccountID. " and b.BillProcessed = 0 order by BillEndDate";
			if($que = $mydb->sql_query($sql)){
				if($result = $mydb->sql_fetchrow($que)){
					return $result['LastBill'];
				}
			}
			$mydb->sql_freeresult($que);
		}
		
		//
		//	Get service type
		//
		function GetServiceType(){
			global $mydb;
			$sql = "select t.ServiceID 
							from tblCustProduct a, tblTarPackage t 
							where a.PackageID = t.PackageID and a.AccID = ".$this->AccountID;		
			if($que = $mydb->sql_query($sql)){
				if($result = $mydb->sql_fetchrow($que)){
					return $result['ServiceID'];
				}
			}
			$mydb->sql_freeresult($que);
		}
		
		//
		//	Calcalte charge amount
		//
		
		function ChargeMonthly($MonthlyFee, $StartBill, $EndBill){						
			# day charge
			$dayCharge = datediff($StartBill, $EndBill, "D");									
			# total day in current month
			$month = date("m", strtotime($EndBill));
			$year = date("Y", strtotime($EndBill));
	
			$dayinmonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
			return ($MonthlyFee / $dayinmonth) * $dayCharge;
		}
		
		//
		//	Get Monthly fee
		//
		function GetCicleFee(){
			global $mydb;
			$sql = "SELECT t.CycleFee 
							FROM tblCustProduct a, tblTarPackage t 
							WHERE a.PackageID = t.PackageID and a.AccID = ".$this->AccountID;	
			if($que = $mydb->sql_query($sql)){
				if($result = $mydb->sql_fetchrow($que)){					
					return $result['CycleFee'];
				}
			}
		}
		
	}// end class
?>
