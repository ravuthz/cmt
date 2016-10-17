<script language="javascript" type="text/javascript">		
	
	function Maximize(URL) 
	{
		//window.open(URL,"Max","resizable=1,toolbar=1,location=1,directories=1,addressbar=1,scrollbars=1,status=1,menubar=1,maximize=1,top=0,left=0,screenX=" + window.screenLeft + ",screenY=" + window.screenTop + ",width=" + screen.availWidth + ",height=" + screen.availHeight);
		window.open(URL,null,"fullscreen=yes,resizable=yes,location=yes,toolbar=yes,menubar=yes,addressbar=no,,top=0,left=0");
	}
</script>
<?php 

	require("configs.php");

	
 /*
	*
	* This code is not to be distributed without the written permission of BRC Technology.
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a> 
	* 
	* Class for searchig.
	*
	*	Created by: Chea vey
	* Created on: 2006 December 28
	*
  */
	
	class search{
		// connection to database
		var $mydb;
		// information
		var $myinfo;
		// Search customer:1; messenger:2; salesman:3; account:4; invoice:5; receipt:6
		var $searchType;
		// where clause (using like and or operation)
		var $critery;
		
		//
		// Constructor
		//
		function search($scritery){
			//$this->searchType = $sMode;
			$this->critery = $scritery;		
		}// End function
		
		//
		//	Search by customer
		//
		function searchCustomer(){
			global $mydb;
			$output = '	<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
										<thead>
											<th>No.</th>
											<th>CustID.</th>
											<th>Name</th>											
											<th>Registered</th>
											<th>BillingEmail</th>											
											<th>Type</th>
										</thead>
										<tbody>							
						';
						
			$sql = "SELECT cus.CustID, cus.CustName, cus.RegisteredDate, cus.BillingEmail, ct.CustTypeName								 
							FROM tblCustomer cus, tlkpCustType ct
							WHERE (cus.CustTypeID = ct.CustTypeID)	";
			if($this->critery != "*")
				$sql .= " and ((cus.CustID like '%".$this->critery."%') or (cus.CustName like '%".$this->critery."%'))";
			
			if($que = $mydb->sql_query($sql)){			
				$iLoop = 1;
				while($result = $mydb->sql_fetchrow($que)){
					$CustID = $result['CustID'];
					$CustName = $result['CustName'];
					$CustTypeName = $result['CustTypeName'];
					$RegisteredDate = $result['RegisteredDate'];
					$BillingEmail = $result['BillingEmail'];					
					$CustomerName = "<a href='./?CustomerID=".$CustID."&pg=10'>".$CustName."</a>";
					if(($iLoop % 2) == 0)
						$style = "row1";
					else
						$style = "row2";
					$output .= '<tr>';	
					$output .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$CustID.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$CustomerName.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.formatDate($RegisteredDate, 3).'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$BillingEmail.'</td>';				
					$output .= '<td class="'.$style.'" align="left">'.$CustTypeName.'</td>';	
					$output .= '</tr>';
					$iLoop += 1;
				}// end while
				$output .= '</tbody>';
				$output .= '</table>';
			}// end if que
			return $output;
		}// end function	
						
		//
		//	Search by messenger
		//
		function searchMessenger(){
			$output = '<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>
								<th align="center">Name</th>
								<th align="center">Address</th>
								<th align="center">Phone</th>
								<th align="center">Email</th>
								<th align="center">ID/Passport</th>
								<th align="center">DOB</th>
								<th align="center">Status</th>
								<th align="center">Remark</th>
								<th align="center">Edit</th>																				
							</thead>
							<tbody>';
								
								$sql = "select SalesmanID, Salutation, Name, Address, Phone, Email, Passport, DOB, Remark, statusid
												from tlkpSalesman
												where ";
								if($this->critery != "*")
									$sql .= "Name like '%".$this->critery."%' order by Name";
									if($que = $mydb->sql_query($sql)){										
										$iLoop = 0;
										while($result = $mydb->sql_fetchrow()){																
											$SalesmanID = $result['SalesmanID'];																
											$Salutation = $result['Salutation'];
											$Name = $result['Name'];											
											$Address = $result['Address'];																
											$Phone = $result['Phone'];
											$Email = $result['Email'];
											$Passport = $result['Passport'];
											$DOB = $result['DOB'];
											$Remark = $result['Remark'];
											$statusid = $result['statusid'];
											$SaleName = $Salutation."".$Name;	
											$edit = "<a href='./?saleid=".$SalesmanID."&pg=237'>Edit</a>";
											if(intval($statusid) == 1)
												$st = "<font color=green>Enable</font>";
											else
												$st = "<font color=red>Disable</font>";	
											$iLoop++;															
											if(($iLoop % 2) == 0)
												$style = "row1";
											else
												$style = "row2";
											print '<tr>';	
											print '<td class="'.$style.'" align="right">'.$iLoop.'</td>';								
											print '<td class="'.$style.'" align="left">'.$SaleName.'</td>';
											print '<td class="'.$style.'" align="left">'.$Address.'</td>';														
											print '<td class="'.$style.'" align="left">'.$Phone.'</td>';								
											print '<td class="'.$style.'" align="left">'.$Email.'</td>';											
											print '<td class="'.$style.'" align="left">'.$Passport.'</td>';
											print '<td class="'.$style.'" align="left">'.FormatDate($DOB, 3).'</td>';
											print '<td class="'.$style.'" align="left">'.$st.'</td>';
											print '<td class="'.$style.'" align="left">'.$Remark.'</td>';
											print '</tr>';
										}
									}
									$mydb->sql_freeresult();	
					$output .= '			
							</tbody>																	
						</table>';
		}// end function
		
		//
		//	Search by customer
		//
		function searchAccount(){
			global $mydb;
			$output = '	<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
										<thead>
											<th>No.</th>
											<th>Acc id</th>
											<th>Name</th>
											<th>Subscription</th>											
											<th>Status</th>
											<th>Package</th>											
											<th>Service</th>
											<th>NoBillRun</th>
											<th>Score</th>
										</thead>
										<tbody>							
						';
			$sql = "select	cu.CustID,
							cp.AccID,
							--cu.Telephone 'UserName',
							replace(replace(replace(Telephone,'-',''),'(',''),')','') + '@Internet' 'UserName',
							cp.StatusID,
							NoBillRun,
							Score,
							SubscriptionName,
							tp.TarName,
							ServiceName = Case
											when tp.ServiceID = 2 then 'Telephone'
											when tp.ServiceID = 4 then 'Lease Line'
											when tp.ServiceID in (1,3,8) then 'Internet'
										end
					from tblCustomer cu 
					join tblCustProduct cp on cu.CustID = cp.CustID 
					join tblTarPackage tp on tp.PackageID = cp.Packageid			
					";		
			if($this->critery != "*")
				$sql .= " where ((cp.AccID like '%".$this->critery."%') or (replace(replace(replace(Telephone,'-',''),'(',''),')','') like '%".$this->critery."%'))";
							
			$sql .= " Union
			
					SELECT a.CustID, a.AccID, a.UserName, a.StatusID, a.NoBillRun, a.Score, a.SubscriptionName, t.TarName, s.ServiceName
							FROM tblCustProduct a, tblTarPackage t, tlkpService s
							WHERE (a.PackageID = t.PackageID and t.ServiceID = s.ServiceID)	";
			if($this->critery != "*")
				$sql .= " and ((a.AccID like '%".$this->critery."%') or (a.UserName like '%".$this->critery."%'))";
				
				$sql .= " order by StatusID, AccID";
			if($que = $mydb->sql_query($sql)){			
				$iLoop = 1;
				while($result = $mydb->sql_fetchrow($que)){
					$CustID = $result['CustID'];
					$AccID = $result['AccID'];
					$UserName = $result['UserName'];
					$SubscriptionName = $result['SubscriptionName'];
					$StatusID = intval($result['StatusID']);
					$NoBillRun = $result['NoBillRun'];
					$Score = $result['Score'];
					$TarName = $result['TarName'];
					$ServiceName = $result['ServiceName'];					
					$AccountName = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
					
					switch($StatusID){
						case 0:
							$stbg = "gray";
							$stfg = "white";
							$stwd = "Inactive";
							break;
						case 1:
							$stbg = "blue";
							$stfg = "white";
							$stwd = "Active";
							break;
						case 2:
							$stbg = "orange";
							$stfg = "white";
							$stwd = "Barred";
							break;
						case 3:
							$stbg = "red";
							$stfg = "white";
							$stwd = "Closed";
							break;
						case 4:
							$stbg = "red";
							$stfg = "white";
							$stwd = "Closed";
							break;
					}
					if(($iLoop % 2) == 0)
						$style = "row1";
					else
						$style = "row2";
					$output .= '<tr>';	
					$output .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$AccID.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$AccountName.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';
					$output .= '<td align="center" bgcolor="'.$stbg.'">
												<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
											 </td>';
					$output .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';				
					$output .= '<td class="'.$style.'" align="left">'.$ServiceName.'</td>';	
					$output .= '<td class="'.$style.'" align="left">'.$NoBillRun.'</td>';	
					$output .= '<td class="'.$style.'" align="left">'.$Score.'</td>';	
					$output .= '</tr>';
					$iLoop += 1;
				}// end while
				$output .= '</tbody>';
				$output .= '</table>';
			}// end if que

			return $output;
		}// end function
		
		//__________________________________SEARCH SUBSCRIPTION NAME
		function searchSubscription(){
			global $mydb;
			$output = '	<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
										<thead>
											<th>No.</th>
											<th>Acc id</th>
											<th>Name</th>											
											<th>Subscription</th>											
											<th>Status</th>
											<th>Package</th>											
											<th>Service</th>
											<th>No bill run</th>
											<th>Score</th>
										</thead>
										<tbody>							
						';
						
			$sql = "SELECT a.CustID, a.AccID, a.UserName, a.StatusID, a.NoBillRun, a.Score, a.SubscriptionName, t.TarName, s.ServiceName
							FROM tblCustProduct a, tblTarPackage t, tlkpService s
							WHERE (a.PackageID = t.PackageID and t.ServiceID = s.ServiceID)	";
			if($this->critery != "*")
				$sql .= " and a.SubscriptionName like '%".$this->critery."%'";
			if($que = $mydb->sql_query($sql)){			
				$iLoop = 1;
				while($result = $mydb->sql_fetchrow($que)){
					$CustID = $result['CustID'];
					$AccID = $result['AccID'];
					$UserName = $result['UserName'];
					$StatusID = intval($result['StatusID']);
					$NoBillRun = $result['NoBillRun'];
					$Score = $result['Score'];
					$TarName = $result['TarName'];
					$ServiceName = $result['ServiceName'];
					$SubscriptionName = $result['SubscriptionName'];					
					$AccountName = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
					
					switch($StatusID){
						case 0:
							$stbg = "gray";
							$stfg = "white";
							$stwd = "Inactive";
							break;
						case 1:
							$stbg = "blue";
							$stfg = "white";
							$stwd = "Active";
							break;
						case 2:
							$stbg = "orange";
							$stfg = "white";
							$stwd = "Barred";
							break;
						case 3:
							$stbg = "red";
							$stfg = "white";
							$stwd = "Closed";
							break;
						case 4:
							$stbg = "red";
							$stfg = "white";
							$stwd = "Closed";
							break;
					}
					if(($iLoop % 2) == 0)
						$style = "row1";
					else
						$style = "row2";
					$output .= '<tr>';	
					$output .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$AccID.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$AccountName.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';
					$output .= '<td align="center" bgcolor="'.$stbg.'">
												<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
											 </td>';
					$output .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';				
					$output .= '<td class="'.$style.'" align="left">'.$ServiceName.'</td>';	
					$output .= '<td class="'.$style.'" align="left">'.$NoBillRun.'</td>';	
					$output .= '<td class="'.$style.'" align="left">'.$Score.'</td>';	
					$output .= '</tr>';
					$iLoop += 1;
				}// end while
				$output .= '</tbody>';
				$output .= '</table>';
			}// end if que

			return $output;
		}// end function
		
		//
		//	Search by invoice
		//
		function searchInvoice(){
			global $mydb,$WebInvoiceRoot;
			$output = '	<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
										<thead>
											<th>No.</th>
											<th>Inv id</th>											
											<th>Acc id</th>
											<th>Account</th>
											<th>Subscription</th>
											<th>Issue date</th>
											<th>Total amount</th>											
											<th>VAT</th>
											<th>Unpaid</th>
											<th>Pay</th>												
										</thead>
										<tbody>							
						';
						
			$sql = "SELECT i.InvoiceID, i.IssueDate, i.InvoiceAmount, i.VATAmount, i.UnpaidAmount, i.AccID, 
								a.CustID, a.UserName, a.SubscriptionName 
							FROM tblCustomerInvoice i, tblCustProduct a
							WHERE (i.AccID = a.AccID)	";
			if($this->critery != "*")
				$sql .= " and (i.InvoiceID like '%".$this->critery."%')";
			if($que = $mydb->sql_query($sql)){			
				$iLoop = 1;
				while($result = $mydb->sql_fetchrow($que)){
					$CustID = $result['CustID'];
					$AccID = $result['AccID'];
					$UserName = $result['UserName'];
					$SubscriptionName = $result['SubscriptionName'];
					$InvoiceID = $result['InvoiceID'];
					$IssueDate = $result['IssueDate'];
					$InvoiceAmount = $result['InvoiceAmount'];
					$VATAmount = $result['VATAmount'];
					$UnpaidAmount = $result['UnpaidAmount'];	
					if(floatval($UnpaidAmount) > 0)
						$pay = "<a href='./?CustomerID=".$CustID."&InvoiceID=".$InvoiceID."&pg=44'>Pay</a>";
					else
						$pay = "<font color='#999999'>Pay</font>";				
					$AccountName = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
					$linkInvoice = "<a href='./finance/screeninvoice.php??CustomerID=".$CustID."&InvoiceID=".$InvoiceID."' target='_blank'>".$InvoiceID."</a>";
					
					if(($iLoop % 2) == 0)
						$style = "row1";
					else
						$style = "row2";
					$output .= '<tr>';	
					$output .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$linkInvoice.'</td>';					
					$output .= '<td class="'.$style.'" align="left">'.$AccID.'</td>';				
					$output .= '<td class="'.$style.'" align="left">'.$AccountName.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';				
					$output .= '<td class="'.$style.'" align="left">'.formatDate($IssueDate, 3).'</td>';	
					$output .= '<td class="'.$style.'" align="right">'.FormatCurrency($InvoiceAmount).'</td>';	
					$output .= '<td class="'.$style.'" align="right">'.FormatCurrency($VATAmount).'</td>';	
					$output .= '<td class="'.$style.'" align="right">'.FormatCurrency($UnpaidAmount).'</td>';	
					$output .= '<td class="'.$style.'" align="left">'.$pay.'</td>';
					$output .= '</tr>';
					$iLoop += 1;
				}// end while
				$output .= '</tbody>';
				$output .= '</table>';
			}// end if que

			return $output;
		}// end function
		
		//
		//	Search by payment
		//
		function searchPayment(){
			global $mydb;
			$output = '	<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
										<thead>
											<th>No.</th>
											<th>Rec id</th>
											<th>Inv id</th>											
											<th>Account</th>
											<th>Payment date</th>
											<th>Transaction</th>											
											<th>Pay as</th>
											<th>Cashier</th>
											<th>Description</th>
											<th>Amount</th>												
										</thead>
										<tbody>							
						';
						
			$sql = "select a.AccID, a.UserName, a.CustID, dr.InvoiceID, dr.PaymentAmount, dr.Description, 
									dr.Cashier, dr.PaymentID, tr.TransactionName, pm.PaymentMode, dr.paymentDate
							from tblCustProduct a, tblCustCashDrawer dr, tlkpTransaction tr, tlkpPaymentMode pm
							where a.AccID = dr.AcctID
									and dr.PaymentModelID = pm.PaymentID
									and dr.TransactionModeID = tr.TransactionID	
									and (dr.IsRollBack IS NULL or dr.IsRollBack = 0)
									";
			if($this->critery != "*")
				$sql .= " and dr.PaymentID like '%".$this->critery."%'";
			if($que = $mydb->sql_query($sql)){			
				$iLoop = 1;
				while($result = $mydb->sql_fetchrow($que)){
					$AccID = $result['AccID'];
					$UserName = $result['UserName'];
					$CustID = $result['CustID'];
					$InvoiceID = $result['InvoiceID'];
					$PaymentAmount = $result['PaymentAmount'];
					$Description = $result['Description'];
					$Cashier = $result['Cashier'];
					$PaymentID = $result['PaymentID'];	
					$TransactionName = $result['TransactionName'];	
					$PaymentMode = $result['PaymentMode'];	
					$paymentDate = $result['paymentDate'];	
														
					$AccountName = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
					$linkInvoice = "<a href='./finance/screeninvoice.php?CustomerID=".$CustID."&InvoiceID=".$InvoiceID."' target='_blank'>".$InvoiceID."</a>";
					$linkReceipt = "<a href='./finance/receipt.php?CustomerID=".$CustID."&PaymentID=".$PaymentID."' target='_blank'>".$PaymentID."</a>";
					if(($iLoop % 2) == 0)
						$style = "row1";
					else
						$style = "row2";
					$output .= '<tr>';	
					$output .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$linkReceipt.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$linkInvoice.'</td>';					
					$output .= '<td class="'.$style.'" align="left">'.$AccountName.'</td>';				
					$output .= '<td class="'.$style.'" align="left">'.formatDate($paymentDate, 3).'</td>';	
					$output .= '<td class="'.$style.'" align="left">'.$TransactionName.'</td>';	
					$output .= '<td class="'.$style.'" align="left">'.$PaymentMode.'</td>';	
					$output .= '<td class="'.$style.'" align="left">'.$Cashier.'</td>';	
					$output .= '<td class="'.$style.'" align="left">'.$Description.'</td>';	
					$output .= '<td class="'.$style.'" align="right">'.FormatCurrency($PaymentAmount).'</td>';	
					$output .= '</tr>';
					$iLoop += 1;
				}// end while
				$output .= '</tbody>';
				$output .= '</table>';
			}// end if que

			return $output;
		}// end function
		
	}// End class
	
	
	
?>	