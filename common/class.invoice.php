
<?php
	/**
	 *	@Project: Wise Biller	
	 *	@File:		invoice.php	
	 *	
	 *	@Author: Chea vey	 
	 *
	 */
require_once("agent.php");
require_once("functions.php");

	class Invoice{
		var $InvoiceID;
		
		function Invoice(){
			
		}
		
		//
		//	Get invoice id
		//	
		function GetInvoiceID(){
			global $mydb;
			# Get InvoiceID
			$sql = "SELECT NextValue FROM tlkpNext WHERE NextID = 3";
			$que = $mydb->sql_query($sql);
			$result = $mydb->sql_fetchrow();
			$InvoiceID = $result['NextValue'];
			$mydb->sql_freeresult();
			# Update invoice
			$sql = "UPDATE tlkpNext SET NextValue = '".($InvoiceID + 1)."' WHERE NextID = 3";
			$mydb->sql_query($sql);
			return $InvoiceID;
		}
		
		function GetInvoiceDepositID(){
			global $mydb;
			# Get InvoiceID
			$sql = "SELECT NextValue FROM tlkpNext WHERE NextID = 16";
			$que = $mydb->sql_query($sql);
			$result = $mydb->sql_fetchrow();
			$InvoiceID = $result['NextValue'];
			$mydb->sql_freeresult();
			# Update invoice
			$sql = "UPDATE tlkpNext SET NextValue = '".($InvoiceID - 1)."' WHERE NextID = 16";
			$mydb->sql_query($sql);
			return $InvoiceID;
		}
		
		//
		//	Create invoice detail
		//
		function CreateInvoiceDetail($CustomerID, $AccountID, $InvoiceID, $BillItemID, $Amount, $BilledUnit = 0, $IsVAT = 0){
			global $mydb, $myinfo;
			
			# get invoice cycle 
			
			# Get Bill Description
			$sql = "SELECT ItemName, InvoiceSequence FROM tlkpInvoiceItem WHERE ItemID= ".$BillItemID;
			$que = $mydb->sql_query($sql);
			$result = $mydb->sql_fetchrow();
			$Description = $result['ItemName'];
			$InvoiceSequence = $result['InvoiceSequence'];
			//$IsVAT = $result['IsVAT']; 
			$mydb->sql_freeresult();
			//$Amount = floatval(FormatCurrency($Amount, "", 1, 2));
	
	
				# Create invoice detail
				$sql = "INSERT INTO tblCustomerInvoiceDetail(InvoiceID, SequenceNo, BillItemID, Description, 
															BillUnit, Amount, IsVAT, AccID, CustID) 
								VALUES(".$InvoiceID.", ".$InvoiceSequence.", ".$BillItemID.", '".$Description."', 
							".$BilledUnit.", ".number_format($Amount, 2, '.', '').", ".$IsVAT.", ".$AccountID.", ".$CustomerID.")";

			if($mydb->sql_query($sql))
				return true;
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to create customer invoice detail.", $error['message']);
				return $retOut;
			}	
		}
		
		//
		//	Create Invoice
		//
		function CreateInvoice($CustomerID, $AccountID, $InvoiceID, $TransactionStart = "", $TransactionEnd = ""){
			global $mydb, $myinfo;

			$today = date("Y M d H:i:s");

			# Get invoice due date
			$sql = "SELECT ConfigueValue FROM globalconfigs WHERE ConfigueName = 'Invoice Due Date'";
			$que = $mydb->sql_query($sql);
			if($result = $mydb->sql_fetchrow())
				$Due = intval($result['ConfigueValue']);
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to get Invoice due date.", $error['message']);
				return retOut;
			}				
			$mydb->sql_freeresult();
			
			# Get Invoice Cycle ID
			$sql = "SELECT TOP 1 r.CycleID 
							FROM tblSysBillRunCycleInfo r, tblCustProduct a
							WHERE r.PackageID = a.PackageID
								AND r.BillProcessed = 0
								AND a.AccID = $AccountID
							ORDER BY r.CycleID ASC";
			if($que = $mydb->sql_query($sql)){
				$result = $mydb->sql_fetchrow($que);
				$CycleID = $result['CycleID'];
			}
			$mydb->sql_freeresult();
			
			# set invoice deu date
			$InvoiceDeuDate = DateAdd($Due);
			# Get current invoice value (with VAT)
			$sql = "SELECT SUM(Amount) as InvoiceAmount FROM tblCustomerInvoiceDetail WHERE InvoiceID = $InvoiceID";
			$que = $mydb->sql_query($sql);
			if($result = $mydb->sql_fetchrow())
				$InvoiceAmount = $result['InvoiceAmount'];
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to get total invoice amount.", $error['message']);
				return $retOut;
			}
			$mydb->sql_freeresult();
			
			# Get current invoice value (No VAT)
			$sql = "SELECT SUM(Amount) as InvoiceAmountVAT FROM tblCustomerInvoiceDetail WHERE InvoiceID = $InvoiceID AND IsVAT = 1";
			$que = $mydb->sql_query($sql);
			if($result = $mydb->sql_fetchrow())
				$NetAmount = $result['InvoiceAmountVAT'];
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to get total invoice amount with VAT only.", $error['message']);
				return $retOut;
			}
			$mydb->sql_freeresult();
						
			# Check if Customer has VAT 
			$sql = "SELECT IsVATException FROM tblCustomer WHERE CustID = $CustomerID";
			$que = $mydb->sql_query($sql);
			if($result = $mydb->sql_fetchrow())
				$IsVATExemption = $result['IsVATException'];									
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to get customer VAT exemption.", $error['message']);
				return $retOut;
			}
			if(intval($IsVATExemption == 1))
				$VATRate = 0;
			else
				$VATRate = intval(getConfigue('VAT Rate'));
			$mydb->sql_freeresult();
									
			# Calculate invoice value
			if(empty($InvoiceAmount) || ($InvoiceAmount == "") || is_null($InvoiceAmount)) $InvoiceAmount = 0;
			if(empty($NetAmount) || ($NetAmount == "") || is_null($NetAmount)) $NetAmount = 0;
			$VATAmount = doubleval(($NetAmount * $VATRate) / 100); 
			$TotalAmount	= doubleval($InvoiceAmount) + doubleval($VATAmount);
			
			# Get customer balance 
			$sql = "SELECT Credit, Outstanding FROM tblAccountBalance WHERE AccID=$AccountID";
			if($que = $mydb->sql_query($sql)){
				if($result = $mydb->sql_fetchrow()){
					$Credit = $result['Credit'];
					$Outstanding = $result['Outstanding'];
				}
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to get customer balance.", $error['message']);
				return $retOut;
			}
			
			if(!isset($Credit) || is_null($Credit) || empty($Credit) || ($Credit == "")) $Credit = 0;
			if(!isset($Outstanding) || is_null($Outstanding) || empty($Outstanding) || ($Outstanding == "")) $Outstanding = 0;
			$BalanceBroadforward = $Outstanding;
			$mydb->sql_freeresult();
			
			# Calculate invoice with current credit
			if(floatval($Credit) > floatval($TotalAmount)){
				$Credit -= $TotalAmount;
				$UnpaidAmount = 0;				
			}else{
				$UnpaidAmount = $TotalAmount - $Credit;
				$Credit = 0;
			}
			
			#	Update Outstanding
			if($UnpaidAmount > 0)
				$Outstanding += $UnpaidAmount;
			
			#	Create invoice
			$sql = "INSERT INTO tblCustomerInvoice(InvoiceID, CustID, IssueDate, DueDate, InvoiceAmount, VATAmount, UnpaidAmount, BroughtForwardBalance, OriginalUnpaidAmount, NetAmount, AccID, BillingCycleID, TransStartDate, TransEndDate, InvoiceType) 
			VALUES ($InvoiceID, $CustomerID, '".$today."', '".$InvoiceDeuDate."', ".number_format($TotalAmount, 2, '.', '').", ".number_format($VATAmount, 2, '.', '').", ".number_format($UnpaidAmount, 2, '.', '').", ".number_format($BalanceBroadforward, 2, '.', '').", ".number_format($UnpaidAmount, 2, '.', '').", ".number_format($InvoiceAmount, 2, '.', '').", $AccountID, $CycleID, '".$TransactionStart."', '".$TransactionEnd."', 3)";
		
		//print $sql;
			if($mydb->sql_query($sql)){
				#update invoice detail cycle id
				$sql = "UPDATE tblCustomerInvoiceDetail SET BillingCycleID = $CycleID WHERE InvoiceID=$InvoiceID";
				$mydb->sql_query($sql);
		//		print "<br>update balance==> $CustomerID, $AccountID, $Credit, $Outstanding";
				#UPdate balance
				$retOut = $this->UpdateBalance($CustomerID, $AccountID, $Credit, $Outstanding);
				if($retOut)
					return true;
				else
					return $retOut;
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to create customer invoice.", $error['message']);
				return $retOut;
			}
		}	
		
		function CreateDepositDetail($CustomerID, $AccountID, $InvoiceID, $BillItemID, $Amount, $BilledUnit = 0, $IsVAT = 0){
			global $mydb, $myinfo;
			
			# get invoice cycle 
			
			# Get Bill Description
			
			$Description = 'Deposit';
			$InvoiceSequence = 1;
			//$IsVAT = $result['IsVAT']; 
			//$Amount = floatval(FormatCurrency($Amount, "", 1, 2));
	
			
				$sql = "INSERT INTO tblCustomerDepositDetail(InvoiceID,SequenceNo, BillItemID, Description, 
														BillUnit, Amount, IsVAT, AccID, CustID) 
							VALUES($InvoiceID,".$InvoiceSequence.", ".$BillItemID.", '".$Description."', 
							".$BilledUnit.", ".number_format($Amount, 2, '.', '').", ".$IsVAT.", ".$AccountID.", ".$CustomerID.")";

			if($mydb->sql_query($sql))
				return true;
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to create customer invoice detail.", $error['message']);
				return $retOut;
			}	
		}
		
		
		function CreateDeposit($CustomerID, $AccountID, $InvoiceID, $TransactionStart = "", $TransactionEnd = ""){
			global $mydb, $myinfo;

			$today = date("Y M d H:i:s");

			# Get invoice due date
			$sql = "SELECT ConfigueValue FROM globalconfigs WHERE ConfigueName = 'Invoice Due Date'";
			$que = $mydb->sql_query($sql);
			if($result = $mydb->sql_fetchrow())
				$Due = intval($result['ConfigueValue']);
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to get Invoice due date.", $error['message']);
				return retOut;
			}				
			$mydb->sql_freeresult();
			
			# Get Invoice Cycle ID
			$sql = "SELECT TOP 1 r.CycleID 
							FROM tblSysBillRunCycleInfo r, tblCustProduct a
							WHERE r.PackageID = a.PackageID
								AND r.BillProcessed = 0
								AND a.AccID = $AccountID
							ORDER BY r.CycleID ASC";
			if($que = $mydb->sql_query($sql)){
				$result = $mydb->sql_fetchrow($que);
				$CycleID = $result['CycleID'];
			}
			$mydb->sql_freeresult();
			
			# set invoice deu date
			$InvoiceDeuDate = DateAdd($Due);
			# Get current invoice value (with VAT)
			$sql = "SELECT SUM(Amount) as InvoiceAmount FROM tblCustomerDepositDetail WHERE InvoiceID = $InvoiceID";
			$que = $mydb->sql_query($sql);
			if($result = $mydb->sql_fetchrow())
				$InvoiceAmount = $result['InvoiceAmount'];
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to get total invoice amount.", $error['message']);
				return $retOut;
			}
			$mydb->sql_freeresult();
			
			# Get current invoice value (No VAT)
			$sql = "SELECT SUM(Amount) as InvoiceAmountVAT FROM tblCustomerDepositDetail WHERE InvoiceID = $InvoiceID AND IsVAT = 1";
			$que = $mydb->sql_query($sql);
			if($result = $mydb->sql_fetchrow())
				$NetAmount = $result['InvoiceAmountVAT'];
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to get total invoice amount with VAT only.", $error['message']);
				return $retOut;
			}
			$mydb->sql_freeresult();
						
			# Check if Customer has VAT 
		
			$IsVATExemption = 0;									
		
			$VATRate = intval(getConfigue('VAT Rate'));
		
									
			# Calculate invoice value
			if(empty($InvoiceAmount) || ($InvoiceAmount == "") || is_null($InvoiceAmount)) $InvoiceAmount = 0;
			if(empty($NetAmount) || ($NetAmount == "") || is_null($NetAmount)) $NetAmount = 0;
			$VATAmount = doubleval(($NetAmount * $VATRate) / 100); 
			$TotalAmount	= doubleval($InvoiceAmount) + doubleval($VATAmount);
			
			# Get customer balance 
			
			$Credit = 0;
			$Outstanding = 0;
			
			
			$BalanceBroadforward = $Outstanding;
			
			
			#	Create invoice
			$sql = "INSERT INTO tblCustomerDeposit(InvoiceID, CustID, IssueDate, DueDate, InvoiceAmount, VATAmount, UnpaidAmount, BroughtForwardBalance, OriginalUnpaidAmount, NetAmount, AccID, BillingCycleID, TransStartDate, TransEndDate, InvoiceType) 
			VALUES ($InvoiceID, $CustomerID, '".$today."', '".$InvoiceDeuDate."', ".number_format($TotalAmount, 2, '.', '').", ".number_format($VATAmount, 2, '.', '').", ".number_format($UnpaidAmount, 2, '.', '').", ".number_format($BalanceBroadforward, 2, '.', '').", ".number_format($UnpaidAmount, 2, '.', '').", ".number_format($InvoiceAmount, 2, '.', '').", $AccountID, $CycleID, '".$TransactionStart."', '".$TransactionEnd."', 3)";
		
		//print $sql;
			if($mydb->sql_query($sql)){
				#update invoice detail cycle id
				$sql = "UPDATE tblCustomerDepositDetail SET BillingCycleID = $CycleID WHERE InvoiceID=$InvoiceID";
				$mydb->sql_query($sql);
		//		print "<br>update balance==> $CustomerID, $AccountID, $Credit, $Outstanding";
				#UPdate balance
				
				return true;
				
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to create customer invoice.", $error['message']);
				return $retOut;
			}
		}	
		
		
		//
		//	Get unpaid invoice
		//
		function GetUnpaidInvoice($CustomerID){
			global $mydb, $myinfo;
			//$sql = "select a.UserName, i.InvoiceID, i.IssueDate, i.DueDate, i.InvoiceAmount, 
//								i.Reminder,(i.InvoiceAmount - sum(IsNULL(d.PaymentAmount, 0))) as 'UnpaidAmount' 
//							from tblCustProduct a inner join tblCustomerInvoice i on a.AccID = i.AccID
//								left join tblCustCashDrawer d on i.InvoiceID = d.InvoiceID
//							where i.CustID = $CustomerID and (d.IsRollback is NULL or d.IsRollback = 0)
//							group by i.UnpaidAmount, a.UserName,
//									i.InvoiceID, i.IssueDate, i.DueDate, i.InvoiceAmount, i.Reminder, i.OriginalUnpaidAmount  
//							having i.InvoiceAmount > sum(IsNULL(d.PaymentAmount, 0))
//							union 
//							select a.UserName, i.InvoiceID, i.IssueDate, i.DueDate, i.InvoiceAmount, i.Reminder, i.UnpaidAmount 
//							from tblCustomerInvoice i inner join tblCustProduct a on i.AccID = a.AccID 
//							where i.CustID = $CustomerID and i.UnpaidAmount > 0
//								and i.InvoiceID not in 
//								(select InvoiceID from tblCustCashDrawer where CustID = $CustomerID and (IsRollback is NULL or IsRollback = 0))";
			$sql = "	BEGIN TRY
						DROP TABLE #Drawer
						Drop Table #invdetail
						END TRY
						BEGIN CATCH
						END CATCH

						select invoiceid, sum(PaymentAmount) PaymentAmount into #Drawer from tblCustCashDrawer 
						where custid = $CustomerID
						and (IsRollback = 0 or IsRollback is null)
						Group by invoiceid
						
						Select invoiceid, amount into #invdetail from tblCustomerInvoiceDetail where BillItemID = 11 and custid = $CustomerID
			
						select a.AccID, a.UserName, i.InvoiceID, i.IssueDate, i.DueDate, i.InvoiceAmount, 
								i.Reminder,(i.InvoiceAmount - IsNULL(d.PaymentAmount, 0) + IsNull(id.amount,0)) 'UnpaidAmount'
							from tblCustProduct a 
							inner join tblCustomerInvoice i on a.AccID = i.AccID
							left join #invdetail id on id.invoiceid = i.invoiceid
							left join #Drawer d on i.InvoiceID = d.InvoiceID
							where i.CustID = $CustomerID
							and  i.InvoiceAmount <> IsNULL(d.PaymentAmount, 0) + IsNull(id.amount,0)
							and i.UnpaidAmount <> 0
							order by i.invoiceid desc
							
							Drop table #Drawer
							Drop Table #invdetail
							";
								
			if($que = $mydb->sql_query($sql))
				return $que;
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to retrieve unpaid invoice.", $error['message']);
				return $retOut;
			}	
		}
		
		
		function GetDepositInvoice($CustomerID){
			global $mydb, $myinfo;
			$sql = "	BEGIN TRY
						Drop Table #invdetail
						END TRY
						BEGIN CATCH
						END CATCH
						
						Select invoiceid, amount into #invdetail from tblCustomerDepositDetail where BillItemID = 11 and custid = $CustomerID
			
						select a.UserName, i.InvoiceID, i.IssueDate, i.DueDate, i.InvoiceAmount, 
								i.Reminder,i.InvoiceAmount 'UnpaidAmount'
							from tblCustProduct a 
							inner join tblCustomerDeposit i on a.AccID = i.AccID
							left join #invdetail id on id.invoiceid = i.invoiceid
							where i.CustID = $CustomerID
							and i.UnpaidAmount <> 0
							order by i.invoiceid desc
							
							
							Drop Table #invdetail
							";
								
			if($que = $mydb->sql_query($sql))
				return $que;
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to retrieve unpaid invoice.", $error['message']);
				return $retOut;
			}	
		}
		
		//
		//	Get closed invoice
		//
		function GetClosedInvoice($CustomerID){
			global $mydb, $myinfo;
			/*$sql = "SELECT a.UserName, i.InvoiceID, i.IssueDate, i.InvoiceAmount, i.Reminder, i.OriginalUnpaidAmount, i.PaymentDate
							FROM tblCustProduct a INNER JOIN tblCustomerInvoice i ON a.AccID = i.AccID
																				LEFT JOIN tblCustCashDrawer d on i.InvoiceID = d.InvoiceID
							WHERE i.CustID = $CustomerID AND d.IsRollBack <> 1
							GROUP BY a.UserName, i.InvoiceID, i.IssueDate, i.InvoiceAmount, i.Reminder, i.OriginalUnpaidAmount, i.PaymentDate
							HAVING i.InvoiceAmount = IsNULL(SUM(d.PaymentAmount), 0)
							UNION
							SELECT a.UserName, i.InvoiceID, i.IssueDate, i.InvoiceAmount, i.Reminder, i.OriginalUnpaidAmount, i.PaymentDate
							FROM tblCustProduct a INNER JOIN tblCustomerInvoice i ON a.AccID = i.AccID
							WHERE i.CustID = $CustomerID AND UnpaidAmount = 0 AND i.InvoiceID NOT IN
								(SELECT InvoiceID FROM tblCustCashDrawer WHERE CustID = $CustomerID AND (IsRollback is NULL OR IsRollback = 0))																			
						";*/
			$sql = "SELECT a.AccID, a.UserName, i.InvoiceID, i.IssueDate, i.InvoiceAmount, i.Reminder, i.OriginalUnpaidAmount, i.PaymentDate
							FROM tblCustProduct a INNER JOIN tblCustomerInvoice i ON a.AccID = i.AccID
																				LEFT JOIN tblCustCashDrawer d on i.InvoiceID = d.InvoiceID
							WHERE i.CustID = $CustomerID AND (d.IsRollBack IS NULL or d.IsRollBack = 0)
							GROUP BY a.AccID, a.UserName, i.InvoiceID, i.IssueDate, i.InvoiceAmount, i.Reminder, i.OriginalUnpaidAmount, i.PaymentDate  
							HAVING i.InvoiceAmount = IsNULL(SUM(d.PaymentAmount), 0)
							UNION
							SELECT a.AccID, a.UserName, i.InvoiceID, i.IssueDate, i.InvoiceAmount, i.Reminder, i.OriginalUnpaidAmount, i.PaymentDate
							FROM tblCustProduct a INNER JOIN tblCustomerInvoice i ON a.AccID = i.AccID
							WHERE i.CustID = $CustomerID AND UnpaidAmount = 0 AND i.InvoiceID NOT IN
								(SELECT InvoiceID FROM tblCustCashDrawer WHERE CustID = $CustomerID AND (IsRollback is NULL OR IsRollback = 0))																			
							ORDER BY 3 DESC
						";

			if($que = $mydb->sql_query($sql))
				return $que;
			else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to retrieve unpaid invoice.", $error['message']);
				return $retOut;
			}
		}				
		
		//
		//	Update customer balance
		//
		function UpdateBalance($CustomerID, $AccountID, $Balance, $OutStanding){
			global $mydb, $myinfo;
			$sql = "SELECT * FROM tblAccountBalance WHERE AccID = $AccountID";

			if($que = $mydb->sql_query($sql)){
				if($result = $mydb->sql_fetchrow()){
					if($mydb->sql_numrows() > 0){
						$sql = "UPDATE tblAccountBalance 
										SET Credit = $Balance,
												Outstanding = $OutStanding
										WHERE AccID = $AccountID
										";
						if($mydb->sql_query($sql))
							return true;
						else{
							$error = $mydb->sql_error();
							$retOut = $myinfo->error("Failed to update customer balance.", $error['message']);
							return $retOut;
						}
					}
				}else{
					$sql = "INSERT INTO tblAccountBalance(CustID, AccID, Credit, Outstanding) VALUES($CustomerID, $AccountID, '".$Balance."', '".$OutStanding."')";
					if($mydb->sql_query($sql))
						return true;
					else{
						$error = $mydb->sql_error();
						$retOut = $myinfo->error("Failed to create customer balance.", $error['message']);
						return $retOut;
					}
				}			
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to check exist customer balance.", $error['message']);
				return $retOut;
			}
		}
		
		//
		//	Update require deposit
		//
		function URDeposit($CustomerID, $AccountID, $ncDeposit, $icDeposit, $mfDeposit){
			global $mydb, $myinfo;
			# Get current require deposit
			$sql = "SELECT UnNationalDeposit, UnInternationDeposit, UnMonthlyDeposit FROM tblAccDeposit WHERE AccID = $AccountID";
			if($que = $mydb->sql_query($sql)){
				if($mydb->sql_numrows() > 0){
					if($result = $mydb->sql_fetchrow($que)){
						$unDeposit = $result['UnNationalDeposit'];
						$uiDeposit = $result['UnInternationDeposit'];
						$umDeposit = $result['UnMonthlyDeposit'];
						if(is_null($unDeposit)) $unDeposit = 0;
						if(is_null($uiDeposit)) $uiDeposit = 0;
						if(is_null($umDeposit)) $umDeposit = 0;
						$unDeposit += $ncDeposit;
						$uiDeposit += $icDeposit;
						$umDeposit += $mfDeposit;
						#	Update require deposit
						$sql = "UPDATE tblAccDeposit SET 
														UnNationalDeposit = $unDeposit, 
														UnInternationDeposit = $uiDeposit, 
														UnMonthlyDeposit = $umDeposit
										WHERE AccID = $AccountID";
						if($mydb->sql_query($sql))
							return true;
						else{
							$error = $mydb->sql_error();
							$retOut = $myinfo->error("Failed update required deposit.", $error['message'].$sql);
							return $retOut;
						}
					}
				}else{
					$sql = "INSERT INTO tblAccDeposit(CustID, AccID, NationalDeposit, InternationDeposit, 
																						MonthlyDeposit, UnNationalDeposit, UnInternationDeposit, UnMonthlyDeposit)
															VALUES($CustomerID, $AccountID, 0, 0, 0, $ncDeposit, $icDeposit,	$mfDeposit)";
					if($mydb->sql_query($sql))
							return $sql;
					else{
						$error = $mydb->sql_error();
						$retOut = $myinfo->error("Failed update required deposit.", $error['message'].$sql);
						return $retOut;
					}	
				}
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to get current required deposits.", $error['message']);
				return $retOut;
			}
			
		}
					
	}// end class
?>
