<?php
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	
	/**
	 *	@Project: Wise Biller	
	 *	@File:		class.account.php	
	 *	
	 *	@Author: Chea vey	 
	 *
	 */
require_once("agent.php");
require_once("functions.php");
require_once("class.invoice.php");

	class Account{
		var $AccountID;
		
		function Account($AccountID){
			$this->AccountID = $AccountID;
		}
		
		//
		//	Activate account
		//
		
		function Activate($StartBillingDate = ""){			
			global $mydb, $myinfo;
			
			# get start bill 
			if($StartBillingDate == "")	$StartBillingDate = date("Ymd H:i:s");
			#Get next bill
			$NextBillingDate = $this->GetNextBill();
			# get monthly fee mode
			$monthlyfee = $this->GetMonthlyMode();
				
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
				$BillItemID = getInvoiceItem("Monthly fee");
				if((is_null($BillItemID)) || (empty($BillItemID))){	
					return "No invoice item was set for item [Monthly fee]";
				}
				#	Create invoice
				$invoice = new Invoice();
				$InvoiceID = $invoice->GetInvoiceID();
				
				# Invoice detail
				
				$retOut = $invoice->CreateInvoiceDetail($CustomerID, $this->AccountID, $InvoiceID, $BillItemID, $ChargeAmount);
				if($retOut){					
					# Create invoice
					$retOut = $invoice->CreateInvoice($CustomerID, $this->AccountID, $InvoiceID, $StartBillingDate, $NextBillingDate);
					if($retOut){
					}else{
						return $retOut;
					}
				}else{
					return $retOut;
				}
				
			}// end monthlyfee
			#	update account
			$sql = "UPDATE tblCustProduct SET 
									StartBillingDate = '".$StartBillingDate."',
									NextBillingDate = '".$NextBillingDate."',
									StatusID = 1
							WHERE AccID= ".$this->AccountID;

			if($que = $mydb->sql_query($sql)){
				return true;
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to update account start / next billing date.", $error['message']);
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
				$sql = "UPDATE tblCustProduct SET 
									StatusID = 3,
									AccountEndDate = '".$EndDate."'
								WHERE AccID = ".$this->AccountID;	
				if($mydb->sql_query($sql)){
					return true;
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
			$sql = "select top 1 b.BillEndDate 
							from tblSysBillRunCycleInfo b, tblCustProduct a 
							where a.PackageID = b.PackageID and a.AccID = ".$this->AccountID. " and b.BillProcessed = 0 order by BillEndDate";		
			if($que = $mydb->sql_query($sql)){
				if($result = $mydb->sql_fetchrow($que)){
					return $result['BillEndDate'];
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
		
		
	}// end class
?>
