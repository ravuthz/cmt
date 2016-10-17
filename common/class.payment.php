<!--
	*
	* This code is not to be distributed without the written permission of BRC Technology.
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a> 
	* 
-->
<?php
	/**
	 *	@Project: Wise Biller	
	 *	@File:		invoice.php	
	 *	
	 *	@Author: Chea vey	 
	 *
	 */
require_once("agent.php");
	class Payment{
		
		function Payment(){			
		}
		
		//
		//	Get invoice id
		//	
		function GetReceiptID(){
			global $mydb;
			# Get Receipt
			$sql = "SELECT NextValue FROM tlkpNext WHERE NextID = 4";
			$que = $mydb->sql_query($sql);
			$result = $mydb->sql_fetchrow();
			$ReceiptID = $result['NextValue'];
			$mydb->sql_freeresult();
			# Update Receipt
			$sql = "UPDATE tlkpNext SET NextValue = '".($ReceiptID + 1)."' WHERE NextID = 4";
			$mydb->sql_query($sql);
			return $ReceiptID;
		}
		
		//
		//	Get cash drawer
		//
		function GetDrawerID($uerid){
			global $mydb;
			$sql = "SELECT DrawerID FROM tblDrawer WHERE UserID = $uerid and Status = 0";
			if($que = $mydb->sql_query($sql)){
				if($mydb->sql_numrows() > 0){
					if($result = $mydb->sql_fetchrow($que)){
						return $result['DrawerID'];
					}else{
						return 0;
					}
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}
		
		//
		//	Create fee payment receipt
		//	Transaction mode: 1 ==> fee payment; 2==> Refund credit; 3: Deposit payment; 4: Refund deposit; 5: Transfer credit to invoice; 6: Transfer deposit to credit; 7: Increase credit; 8: Credit note receipt; 9: Write off bad debt
		//
		function CreatePaymentReceipt($DrawerID, $CustomerID, $AccountID, $InvoiceID, $ReceiptID, $Amount, $PaymentModelID, $TransactionModeID, $Operator, $GroupReceiptID, $Comment = "", $CheckFrom = "", $CheckNumber = "", $IssuedDate = ""){
			global $mydb, $myinfo;
			
			$today = date("Y-M-d H:i:s",strtotime("-1 hours"));			
			//$today = date("Y-M-d H:i:s");			
			# Create invoice detail
			$sql = "INSERT INTO tblCustCashDrawer(DrawerID, PaymentID, CustID, InvoiceID, PaymentDate, PaymentAmount, Description, Cashier, PaymentModelID, TransactionModeID, IsSubmitted, IsActive, CheckFrom, CheckNumber, IssuedDate, AcctID, GroupPaymentID) 
							VALUES(".$DrawerID.", ".$ReceiptID.", ".$CustomerID.", ".$InvoiceID.", '".$today."', ".$Amount.", '".$Comment."', '".$Operator."', ".$PaymentModelID.", ".$TransactionModeID.", 0, 1, '".$CheckFrom."', '".$CheckNumber."', '".$IssuedDate."', ".$AccountID.", ".$GroupReceiptID.")";
			if($mydb->sql_query($sql)){
				# Update payment date in tblCustomerInvoice
				$sql = "UPDATE tblCustomerInvoice SET PaymentDate='".$today."' WHERE InvoiceID=".$InvoiceID;
				if($mydb->sql_query($sql))
					return true;
				else{ 
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to update invoice payment.", $error['message']);
					return $retOut;
				}
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to create customer customer payment receipt.", $error['message']);
				return $retOut;
			}	
		}
						
		//
		//	Get unpaid invoice
		//
		function GetPaymentHistory($CustomerID){
			global $mydb, $myinfo;
			$sql = "select d.AcctID,d.PaymentID, d.PaymentAmount, convert(varchar, d.PaymentDate, 120) 'paiddate', 
							d.Cashier, d.InvoiceID, m.PaymentMode, s.TransactionName, i.IssueDate, ac.UserName
					from tlkpPaymentMode m(nolock), tlkpTransaction s(nolock),	tblCustProduct ac(nolock), 
							tblCustomerInvoice i(nolock), tblCustCashDrawer d(nolock)
					where d.PaymentModelID = m.PaymentID and d.TransactionModeID = s.TransactionID 
						and d.InvoiceID = i.InvoiceID 		
						and d.AcctID = ac.AccID						
						and (d.IsRollback is NULL or d.IsRollback = 0) and d.TransactionModeID in(1, 10, 14, 15,39) and d.CustID = $CustomerID
					union
					select d.AcctID,d.PaymentID, d.PaymentAmount, convert(varchar, d.PaymentDate, 120) 'paiddate', 
							d.Cashier, 0 'InvoiceID', m.PaymentMode, s.TransactionName, 0 'IssueDate', ac.UserName
					from tlkpPaymentMode m(nolock), tlkpTransaction s(nolock), tblCustProduct ac(nolock), 
							tblCustCashDrawer d(nolock) 
					where d.PaymentModelID = m.PaymentID and d.TransactionModeID = s.TransactionID 
						and d.AcctID = ac.AccID
						and (d.IsRollback is NULL or d.IsRollback = 0) and d.TransactionModeID in(3) and d.CustID = $CustomerID
								
					order by IssueDate desc
							";													
			if($que = $mydb->sql_query($sql))
				return $que;
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to retrieve paymnt history.", $error['message']);
				return $retOut;
			}	
		}
		
		//
		//	Get refund invoice
		//
		function GetRefundHistory($CustomerID){
			global $mydb, $myinfo;
			$sql = "select d.PaymentID, d.PaymentAmount, convert(varchar, d.PaymentDate, 120) 'paiddate', d.Cashier, d.InvoiceID, 
									m.PaymentMode, s.TransactionName, ac.UserName
							from tblCustCashDrawer d(nolock), tlkpPaymentMode m(nolock), tlkpTransaction s, tblCustProduct ac(nolock) 
							where d.PaymentModelID = m.PaymentID 
										and d.TransactionModeID = s.TransactionID 
										and d.AcctID = ac.AccID
										and (d.IsRollback is NULL or d.IsRollback = 0) and d.TransactionModeID in(2, 7, 8, 9,39) and ac.CustID = $CustomerID
							order by d.PaymentID desc
							";
			if($que = $mydb->sql_query($sql))
				return $que;
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to retrieve paymnt history.", $error['message']);
				return $retOut;
			}	
		}
		
		//
		//	Get deposit
		//
		function GetDepositHistory($CustomerID){
			global $mydb, $myinfo;
			$sql = "select d.PaymentID, d.PaymentAmount, convert(varchar, d.PaymentDate, 120) 'paiddate', 
									d.Cashier, d.InvoiceID, m.PaymentMode, s.TransactionName, ac.UserName
							from tblCustCashDrawer d(nolock), tlkpPaymentMode m(nolock), tlkpTransaction s(nolock), tblCustproduct ac(nolock) 
							where d.PaymentModelID = m.PaymentID 
										and d.TransactionModeID = s.TransactionID 
										and d.AcctID = ac.AccID
										and (d.IsRollback is NULL or d.IsRollback = 0) and d.TransactionModeID in(4, 5, 6, 7, 8, 9, 11, 12, 13, 39) and ac.CustID = $CustomerID
							";
			if($que = $mydb->sql_query($sql))
				return $que;
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to retrieve paymnt history.", $error['message']);
				return $retOut;
			}	
		}														
		
					
	}// end class
?>
