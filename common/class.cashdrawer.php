<?php

	/*
	 *	@Project: Wise Biller	
	 *	@File:	  class.cashdrawer.php	
	 *	@Author: Thon Nicolas	 
	 */
	 
include_once("agent_ado.php");


class CashDrawer{
	 // Start Class
	var $db;
	var $drawerid;
	/*   public enum TransactionMode
			{
				FeeAmount=1,
				RefundCredit=2,
				IncreaseCredit=3,
				BookNCDeposit=4,
				BookICDeposit=5,
				BookMFDeposit=6,
				RefundNCDeposit=7,
				RefundICDeposit=8,
				RefundMFDeposit=9,
				SettleInvoiceWithCredit=10,
				TransferNCDepositToCredit=11,
				TransferICDepositToCredit=12,
				TransferMFDepositToCredit=13,
				CreditNoteInvoice=14,
				WriteOffBadDebt=15
	
			}
	*/
	
	
	function CashDrawer($db,$drawerid){
		$this->db=$db;
		$this->drawerid=$drawerid;
	}
	
	//Update Cash Drawer
	function UpdatePayment($PaymentID,$db){
	
	//Update Cash drawer
		$sql="UPDATE tblCustCashDrawer SET IsActive='0', ProcessDate=GETDATE() WHERE PaymentID=$PaymentID; ";
		//print $PaymentID;
		
		$db->_Execute($sql);
	
	 $sql2="INSERT INTO tblCashPayment (PaymentID, DrawerID,CustID,InvoiceID,PaymentAmount, PaymentDate, Description, 
									  Cashier, PaymentModelID, TransactionModeID,IsSubmitted, IsActive, CheckFrom, 
									  CheckNumber, IssuedDate,AcctID,ProcessDate) 
	SELECT PaymentID, DrawerID,CustID,InvoiceID,PaymentAmount, PaymentDate, Description, 
									  Cashier, PaymentModelID, TransactionModeID,IsSubmitted, 0 'IsActive', CheckFrom, 
									  CheckNumber, IssuedDate,AcctID,ProcessDate
	FROM tblCustCashDrawer
	WHERE PaymentID=$PaymentID;";
		//print $sql2;
		$db->_Execute($sql2);	
	}
	
	function GetCashDrawerPayment($DrawerID, $db){
		$sql="SELECT * FROM tblCustCashDrawer WHERE IsActive='1' AND IsSubmitted='1' AND DrawerID=$DrawerID";
		$result=$db->_Execute($sql);
		return $result;
	}
	
	function GetInvoiceByID($InvoiceID,$db){
		$sql="select invoiceid, custid, issuedate, duedate, convert(money, invoiceamount,2) 'invoiceamount',
			vatamount, convert(money, unpaidamount,2) 'unpaidamount',
			originalunpaidamount,
			netamount, AccID
		 from tblCustomerInvoice WHERE InvoiceID=$InvoiceID";		
		$result=$db->_Execute($sql);
		return $result;
	}
	
	function UpdateInvoiceUnpaidAmount($InvoiceID, $UnpaidAmount,$db){
		$UnpaidAmount = doubleval($UnpaidAmount);
		$InvoiceID = intval($InvoiceID);
		$sql="Update tblCustomerInvoice SET UnpaidAmount=convert(money,$UnpaidAmount,2) WHERE InvoiceID=$InvoiceID";
		//print $sql; 
		$db->_Execute($sql);
	}
	
	function GetAccountBalance($AccID,$db){
		$sql="select CustID, AccID, Deposit,convert(money, Credit,2) Credit, convert(money,Outstanding,2) Outstanding, UnpaidDeposit from tblAccountBalance where AccID='$AccID'";
		//print $sql;
		return $db->_Execute($sql);
	}
	
	function UpdateAccountBalance($AccID,$Credit,$Outstanding,$db){
		$sql="Update tblAccountBalance SET Credit=$Credit, Outstanding=$Outstanding
			  WHERE AccID=$AccID";
			 // print "<p>".$sql."</p>";
			  $db->_Execute($sql);
	}
	
	function GetAccountDeposit($AccID,$db){
		$sql="select * from tblAccDeposit where AccID=$AccID";
		return $db->_Execute($sql);	
	}
	
	function UpdateAccountDeposit($AccID,$NationalDeposit,$InternationDeposit,$MonthlyDeposit,$UnNationalDeposit,$UnInternationDeposit,$UnMonthlyDeposit,$db){
									
		$sql="Update tblAccDeposit SET NationalDeposit='$NationalDeposit', 
									  InternationDeposit='$InternationDeposit', 
									  MonthlyDeposit='$MonthlyDeposit', UnNationalDeposit='$UnNationalDeposit', 
									  UnInternationDeposit='$UnInternationDeposit', 
									  UnMonthlyDeposit='$UnMonthlyDeposit' 
									  WHERE AccID=$AccID";
		//print($sql);
		$db->_Execute($sql);
	}
	
	function Process(){
		//cash drawer
		$cashdrawer=$this->GetCashDrawerPayment($this->drawerid,$this->db);
		
		while($cashdrawerpayment=$cashdrawer->FetchRow()){
					//	print "<pre> Cash Drawer \n";
					//	print_r($cashdrawerpayment);
					//	print "</pre>";
			$intTransactionMode=intval($cashdrawerpayment["TransactionModeID"]);
			$blRollback=intval($cashdrawerpayment["IsRollback"]);
			$dbCashDrawerPaymentAmount=doubleval($cashdrawerpayment["PaymentAmount"]);
			$intAccIDDrawer=intval($cashdrawerpayment["AcctID"]);
			$intPaymentID=intval($cashdrawerpayment["PaymentID"]);
			$intInvoiceID=intval($cashdrawerpayment["InvoiceID"]);
			
			if($blRollback==0){
				//Fee Payment
				//print $intTransactionMode;
				
				if($intTransactionMode==1){
				//print  'test';
					
					$intInvoiceID=intval($cashdrawerpayment["InvoiceID"]);
					$result=$this->GetInvoiceByID($intInvoiceID,$this->db);	
					
					//print_r($result);
					while($invoice=$result->FetchRow()){
						//print "test3";
						//print "<pre> Invoice \n";
						//print_r($invoice);
					//	print "</pre>";
						$dbInvoiceUnpaidAmount=doubleval($invoice["unpaidamount"]);
						//$intInvoiceID=intval($invoice["InvoiceID"]);
						$intAccIDDrawer=intval($invoice["AccID"]);
						$dbCredit=0.0;
						$dbLeftOver=0.0;
						
						if($dbInvoiceUnpaidAmount >= $dbCashDrawerPaymentAmount){
							// Invoice Unpaid Amount
						//	print "<p> Unpaid: ".$dbInvoiceUnpaidAmount. ", InvoiceAmou: ". $dbCashDrawerPaymentAmount. "</p>";
						//	print "<p> ". ($dbInvoiceUnpaidAmount-$dbCashDrawerPaymentAmount) . "</p>";
							
							$this->UpdateInvoiceUnpaidAmount($intInvoiceID,$dbInvoiceUnpaidAmount-$dbCashDrawerPaymentAmount,$this->db);				
							$dbLeftOver=0.0;
						}
						else{
							$this->UpdateInvoiceUnpaidAmount($intInvoiceID,0.0, $this->db);
						}
						
					
						$accountbalance=$this->GetAccountBalance($intAccIDDrawer,$this->db);
						while($accbalancerec=$accountbalance->FetchRow()){
							$outstanding=doubleval($accbalancerec["Outstanding"]);
							$credit=doubleval($accbalancerec["Credit"]);
							if($dbInvoiceUnpaidAmount >= $dbCashDrawerPaymentAmount){
								$outstanding=$outstanding - $dbCashDrawerPaymentAmount;
								$dbLeftOver=0.0;
								//$dbCashDrawerPaymentAmount=0;
							}else{
								$dbLeftOver=$dbCashDrawerPaymentAmount-$dbInvoiceUnpaidAmount;
								
								if($outstanding > $dbInvoiceUnpaidAmount){
									$outstanding = $outstanding - $dbInvoiceUnpaidAmount;									
								}else{
									$outstanding=0.0;
								}
								
							}
							$credit=$credit + $dbLeftOver;
							$this->UpdateAccountBalance($intAccIDDrawer, $credit, $outstanding,$this->db);
						}
						$IsActive=0;	
					
						$this->UpdatePayment($intPaymentID,$this->db);
					}
				}
				
				
				//Refund Credit
				else if($intTransactionMode == 2){
					$accountbalance=$this->GetAccountBalance($intAccIDDrawer,$this->db);
					while($accbalancerec=$accountbalance->FetchRow()){
						$oustanding=doubleval($accbalancerec["Outstanding"]);
						$credit=doubleval($accbalancerec["Credit"]);
						if($credit >= $dbCashDrawerPaymentAmount){
							$credit=$credit-$dbCashDrawerPaymentAmount;
						}
						$this->UpdateAccountBalance($intAccIDDrawer,$credit,$oustanding,$this->db);
						$this->UpdatePayment($intPaymentID,$this->db);
					}	
					
				}
				//Increase credit
				else if($intTransactionMode == 3){
					$accountbalance=$this->GetAccountBalance($intAccIDDrawer,$this->db);
					while($accbalancerec=$accountbalance->FetchRow()){
						$oustanding=doubleval($accbalancerec["Outstanding"]);
						$credit=doubleval($accbalancerec["Credit"]);
						//if($credit >= $dbCashDrawerPaymentAmount){
						$credit=$credit + $dbCashDrawerPaymentAmount;
						//}
						$this->UpdateAccountBalance($intAccIDDrawer,$credit,$oustanding,$this->db);
					}	
					$this->UpdatePayment(intval($cashdrawerpayment["PaymentID"]),$this->db);
				}
				
				
			
				//Book Deposit 
				else if($intTransactionMode == 4 || $intTransactionMode == 5 || $intTransactionMode==6){
					$dbNCDepositEx=0.0;
					$dbICDepositEx=0.0;
					$dbMFDepositEx=0.0;
					$dbUnNCDepositEx=0.0;
					$dbUnICDepositEx=0.0;
					$dbUnMFDepositEx=0.0;
					
					$deposit=$this->GetAccountDeposit($intAccIDDrawer,$this->db);
					
					
					while($depositrow=$deposit->FetchRow()){
						$dbNCDeposit=doubleval($depositrow["NationalDeposit"]);
						$dbICDeposit=doubleval($depositrow["InternationDeposit"]);
						$dbMFDeposit=doubleval($depositrow["MonthlyDeposit"]);
						$dbUnNCDeposit=doubleval($depositrow["UnNationalDeposit"]);
						$dbUnICDeposit=doubleval($depositrow["UnInternationDeposit"]);
						$dbUnMFDeposit=doubleval($depositrow["UnMonthlyDeposit"]);
						
						switch($intTransactionMode){
							case 4: $dbNCDepositEx=$dbCashDrawerPaymentAmount; break;
							case 5: $dbICDepositEx=$dbCashDrawerPaymentAmount; break;
							case 6: $dbMFDepositEx=$dbCashDrawerPaymentAmount; break;
							default: break;
						}
									
						$dbUnNCDeposit=$dbUnNCDeposit-$dbNCDepositEx;
						$dbUnICDeposit=$dbUnICDeposit-$dbICDepositEx;
						$dbUnMFDeposit=$dbUnMFDeposit-$dbMFDepositEx;
						$dbNCDeposit=$dbNCDeposit+$dbNCDepositEx;
						$dbICDeposit=$dbICDeposit+$dbICDepositEx;
						$dbMFDeposit=$dbMFDeposit+$dbMFDepositEx;
						
						if($dbUnNCDeposit <0){
							$dbUnNCDeposit=0;
						}
						if($dbUnICDeposit <0){
							$dbUnICDeposit=0;
						}
						if($dbUnMFDeposit <0){
							$dbUnMFDeposit=0;
						}
				
						
						$this->UpdateAccountDeposit($intAccIDDrawer,
													$dbNCDeposit,
													$dbICDeposit,
													$dbMFDeposit,
													$dbUnNCDeposit,
													$dbUnICDeposit,
													$dbUnMFDeposit,
													$this->db);
													
						
						
						$this->UpdatePayment($intPaymentID,$this->db);
						
					}
				}
				
				
				//Refund deposit
				else if($intTransactionMode == 7 || $intTransactionMode == 8 || $intTransactionMode == 9){
					$dbNCDepositEx=0.0;
					$dbICDepositEx=0.0;
					$dbMFDepositEx=0.0;
					$dbUnNCDepositEx=0.0;
					$dbUnICDepositEx=0.0;
					$dbUnMFDepositEx=0.0;
					$deposit=$this->GetAccountDeposit($intAccIDDrawer,$this->db);
					while($depositrow=$deposit->FetchRow()){
						$dbNCDeposit=doubleval($depositrow["NationalDeposit"]);
						$dbICDeposit=doubleval($depositrow["InternationDeposit"]);
						$dbMFDeposit=doubleval($depositrow["MonthlyDeposit"]);
						$dbUnNCDeposit=doubleval($depositrow["UnNationalDeposit"]);
						$dbUnICDeposit=doubleval($depositrow["UnInternationDeposit"]);
						$dbUnMFDeposit=doubleval($depositrow["UnMonthlyDeposit"]);
						
						switch($intTransactionMode){
							case 7: $dbNCDepositEx=$dbCashDrawerPaymentAmount; break;
							case 8: $dbICDepositEx=$dbCashDrawerPaymentAmount; break;
							case 9: $dbMFDepositEx=$dbCashDrawerPaymentAmount; break;
							default: break;
						}
						
						$dbNCDeposit=$dbNCDeposit-$dbNCDepositEx;
						$dbICDeposit=$dbICDeposit-$dbICDepositEx;
						$dbMFDeposit=$dbMFDeposit-$dbMFDepositEx;
						
						$this->UpdateAccountDeposit($intAccIDDrawer,
													$dbNCDeposit,
													$dbICDeposit,
													$dbMFDeposit,
													$dbUnNCDeposit,
													$dbUnICDeposit,
													$dbUnMFDeposit,
													$this->db);
						$this->UpdatePayment($intPaymentID,$this->db);
					}
				}
				//Settle Invoice with credit
				else if($intTransactionMode == 10){
					$intInvoiceID=intval($cashdrawerpayment["InvoiceID"]);
					$accountbalance=$this->GetAccountBalance($intAccIDDrawer,$this->db);
					while($accbalancerec=$accountbalance->FetchRow()){
						$outstanding=doubleval($accbalancerec["Outstanding"]);
						$credit=doubleval($accbalancerec["Credit"]);
						
						$credit = $credit - $dbCashDrawerPaymentAmount;
						
						$outstanding = $outstanding - $dbCashDrawerPaymentAmount;
						
						$this->UpdateAccountBalance($intAccIDDrawer,$credit,$outstanding,$this->db);
												
						$result=$this->GetInvoiceByID($intInvoiceID,$this->db);	
						
						while($invoice=$result->FetchRow()){
							
							$dbInvoiceUnpaidAmount=doubleval($invoice["unpaidamount"]);
							
							$this->UpdateInvoiceUnpaidAmount($intInvoiceID,$dbInvoiceUnpaidAmount-$dbCashDrawerPaymentAmount, $this->db);		
									
							$dbLeftOver=0.0;
							
							
						}	
						$this->UpdatePayment($intPaymentID,$this->db);
					}	
				}
				
				//Transfer Deposit to Credit
				else if($intTransactionMode == 11 || $intTransactionMode ==12 || $intTransactionMode==13){
					$dbNCDepositEx=0.0;
					$dbICDepositEx=0.0;
					$dbMFDepositEx=0.0;
					$dbUnNCDepositEx=0.0;
					$dbUnICDepositEx=0.0;
					$dbUnMFDepositEx=0.0;
					$deposit=$this->GetAccountDeposit($intAccIDDrawer,$this->db);
					while($depositrow=$deposit->FetchRow()){
						$dbNCDeposit=doubleval($depositrow["NationalDeposit"]);
						$dbICDeposit=doubleval($depositrow["InternationDeposit"]);
						$dbMFDeposit=doubleval($depositrow["MonthlyDeposit"]);
						$dbUnNCDeposit=doubleval($depositrow["UnNationalDeposit"]);
						$dbUnICDeposit=doubleval($depositrow["UnInternationDeposit"]);
						$dbUnMFDeposit=doubleval($depositrow["UnMonthlyDeposit"]);
						
						switch($intTransactionMode){
							case 11: $dbNCDepositEx=$dbCashDrawerPaymentAmount; break;
							case 12: $dbICDepositEx=$dbCashDrawerPaymentAmount; break;
							case 13: $dbMFDepositEx=$dbCashDrawerPaymentAmount; break;
							default: break;
						}
									
						$dbNCDeposit=$dbNCDeposit-$dbNCDepositEx;
						$dbICDeposit=$dbICDeposit-$dbICDepositEx;
						$dbMFDeposit=$dbMFDeposit-$dbMFDepositEx;
						
						$this->UpdateAccountDeposit($intAccIDDrawer,
													$dbNCDeposit,
													$dbICDeposit,
													$dbMFDeposit,
													$dbUnNCDeposit,
													$dbUnICDeposit,
													$dbUnMFDeposit,
													$this->db);
													
						$accountbalance=$this->GetAccountBalance($intAccIDDrawer,$this->db);
						while($accbalancerec=$accountbalance->FetchRow()){
							$oustanding=doubleval($accbalancerec["Outstanding"]);
							$credit=doubleval($accbalancerec["Credit"]);
							//if($credit >= $dbCashDrawerPaymentAmount){
							$credit=$credit+$dbCashDrawerPaymentAmount;
							//}
							$this->UpdateAccountBalance($intAccIDDrawer,$credit,$oustanding,$this->db);
						}	
						$this->UpdatePayment($intPaymentID,$this->db);
					}
				}
				
				//Transfer Credit Note
				else if($intTransactionMode == 14 || $intTransactionMode ==15){
						$intInvoiceID=intval($cashdrawerpayment["InvoiceID"]);
						$result=$this->GetInvoiceByID($intInvoiceID,$this->db);	
						//print_r($result);
						while($invoice=$result->FetchRow()){
							//print "test3";
							$dbInvoiceUnpaidAmount=doubleval($invoice["unpaidamount"]);
							$this->UpdateInvoiceUnpaidAmount($intInvoiceID,$dbInvoiceUnpaidAmount-$dbCashDrawerPaymentAmount,$this->db);				
							$accountbalance=$this->GetAccountBalance($intAccIDDrawer,$this->db);
							while($accbalancerec=$accountbalance->FetchRow()){
								$outstanding=doubleval($accbalancerec["Outstanding"]);
								$credit=doubleval($accbalancerec["Credit"]);
								if($outstanding >= $dbCashDrawerPaymentAmount){
									$outstanding = $outstanding - $dbCashDrawerPaymentAmount;
								}
								$this->UpdateAccountBalance($intAccIDDrawer,$credit,$outstanding,$this->db);
							}
							
							$this->UpdatePayment($intPaymentID,$this->db);
						}	
				}
				
			}// Is If Rollback
		}// End while loop of cash drawer payment
	}
}// End Cash Drawer Class


?>