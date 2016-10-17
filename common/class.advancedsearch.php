<?php 
 /*
	*
	* This code is not to be distributed without the written permission of BRC Technology.
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a> 
	* 	
  */
	
	class AdvancedSearch{
		// connection to database
		var $mydb;
		// information
		var $myinfo;
		// Search type: 1: customerid, 2: customername; 3: accountid; 4: accountname; 5: subscription; 6: billingemail; 7: telephone.
		var $type;
		// where clause (keyword to search)
		var $question;
		// mode; search mode if exact search or fuzzy search
		var $mode;
		
		//
		// Constructor
		//
		//	$q = question to search for
		//	$m = mode (1: fuzzy search; 2: exact search)
		//	$t = type: 1: customerid, 2: customername; 3: accountid; 4: accountname; 5: subscription; 6: billingemail; 7: telephone;
		//						 8: installation address; 9: billing address
		function AdvancedSearch($q, $m, $t){			
			$this->question = trim($q);		
			$this->type = trim($t);		
			$this->mode = trim($m);
			
		}// End function
		
		//
		//	Search by customer
		//
		function searchCustomer(){
			global $mydb;
			$output = '	<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
										<thead>
											<th>No.</th>
											<th>Name</th>											
											<th>Registered</th>
											<th>Telephone</th>
											<th>Email</th>											
											<th>Type</th>
											<th>Products</th>
										</thead>
										<tbody>							
						';
						
			$sql = "SELECT cus.CustID, cus.CustName, cus.RegisteredDate, cus.BillingEmail, con.Phone,
										 ct.CustTypeName, count(a.AccID) as 'Products'								 
							FROM tlkpCustType ct inner join tblCustomer cus on ct.CustTypeID = cus.CustTypeID 
																	 left join tblCustProduct a on cus.CustID = a.CustID 
																	 left join tblCustContact con on cus.CustID = con.CustID ";																	 
			# not search all
			
				# search customer id
				if($this->type == 1){
					if($this->question != "*"){
						# fuzzy search
						if($this->mode == 1){
							$sql .= "WHERE cus.CustID like '%".$this->question."%'";
						}else{
							$sql .= "WHERE cus.CustID =".$this->question;
						}
					}
				#search customer name	
				}elseif($this->type == 2){
					if($this->question != "*"){
						# fuzzy search
						if($this->mode == 1){
							$sql .= "WHERE cus.CustName like '%".$this->question."%'";
						}else{
							$sql .= "WHERE cus.CustName ='".$this->question."'";
						}
					}
				# search billing email
				}elseif($this->type == 6){
					if($this->question != "*"){
						# fuzzy search
						if($this->mode == 1){
							$sql .= "WHERE cus.BillingEmail like '%".$this->question."%'";
						}else{
							$sql .= "WHERE cus.BillingEmail ='".$this->question."'";
						}
					}
				# search telephone
				}elseif($this->type == 7){
					if($this->question != "*"){
						# fuzzy search
						if($this->mode == 1){
							$sql .= "WHERE con.Phone like '%".$this->question."%'";
						}else{
							$sql .= "WHERE con.Phone ='".$this->question."'";
						}
					}				
			}
				$sql .= " GROUP BY cus.CustID, cus.CustName, cus.RegisteredDate, cus.BillingEmail, con.Phone,
										 ct.CustTypeName";
			
			
			if($que = $mydb->sql_query($sql)){			
				$iLoop = 1;
				while($result = $mydb->sql_fetchrow($que)){
					$CustID = $result['CustID'];
					$CustName = $result['CustName'];
					$CustTypeName = $result['CustTypeName'];
					$RegisteredDate = $result['RegisteredDate'];
					$BillingEmail = $result['BillingEmail'];
					$Telephone = $result['Phone'];
					$Products = $result['Products'];
					$CustomerName = "<a href='./?CustomerID=".$CustID."&pg=10'>".$CustName."</a>";
					$Account = "<a href='./?CustomerID=".$CustID."&pg=90'>".$Products."</a>";
					if(($iLoop % 2) == 0)
						$style = "row1";
					else
						$style = "row2";
					$output .= '<tr>';	
					$output .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$CustomerName.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.formatDate($RegisteredDate, 3).'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$Telephone.'</td>';				
					$output .= '<td class="'.$style.'" align="left">'.$BillingEmail.'</td>';				
					$output .= '<td class="'.$style.'" align="left">'.$CustTypeName.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$Account.'</td>';	
					$output .= '</tr>';
					$iLoop += 1;
				}// end while
				$output .= '</tbody>';
				$output .= '</table>';
			}// end if que
			//return $sql;
			return $output;
		}// end function	
						
		
		//
		//	Search by account
		//
		function searchAccount(){
			global $mydb;
			$output = '	<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
										<thead>
											<th>No.</th>
											<th>Account</th>
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
			
				if($this->type == 3){
					# fuzzy search
					if($this->critery != "*"){
						if($this->mode == 1){
							$sql .= "AND a.AccID like '%".$this->question."%'";
						}else{
							$sql .= "AND a.AccID =".$this->question;
						}
					}
				}elseif($this->type == 4){
					# fuzzy search
					if($this->critery != "*"){
						if($this->mode == 1){
							$sql .= "AND a.UserName like '%".$this->question."%'";
						}else{
							$sql .= "AND a.UserName ='".$this->question."'";
						}
					}
				}
				elseif($this->type == 5){
					if($this->critery != "*"){
						# fuzzy search
						if($this->mode == 1){
							$sql .= "AND a.SubscriptionName like '%".$this->question."%'";
						}else{
							$sql .= "AND a.SubscriptionName ='".$this->question."'";
						}
					}
				}
				
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
					}
					if(($iLoop % 2) == 0)
						$style = "row1";
					else
						$style = "row2";
					$output .= '<tr>';	
					$output .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
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
			//	return $sql;
			return $output;
		}// end function
		
		//
		//	Search by address
		//
		function searchAddress(){
			global $mydb;
			$output = '	<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
										<thead>
											<th>No.</th>
											<th>Account</th>
											<th>Subscription</th>											
											<th>Status</th>
											<th>Address</th>											
											<th>Sangkat</th>
											<th>Khan</th>											
											<th>City</th>
											<th>Country</th>
										</thead>
										<tbody>							
						';
						
			$sql = "SELECT a.CustID, a.AccID, a.UserName, a.SubscriptionName, a.StatusID, ad.Address, 
											l1.Name as 'Sangkat', l2.Name as 'Khan', l3.Name as 'City', l4.Name as 'Country'
							FROM tblCustProduct a, tblCustAddress ad, tlkpLocation l1, tlkpLocation l2, tlkpLocation l3, tlkpLocation l4
							WHERE a.AccID = ad.AccID and ad.SangkatID = l1.id and ad.KhanID = l2.id and ad.CityID = l3.id and ad.CountryID = l4.id ";
			
				if($this->type == 8){
					$sql .= "AND IsBillingAddress = 0 ";
					//if($this->critery != "*"){
						# fuzzy search
						if($this->mode == 1){
							$sql .= "AND ad.Address like '%".$this->question."%'";
						}else{
							$sql .= "AND ad.Address =".$this->question;
						}
				//	}
				}elseif($this->type == 9){
					$sql .= "AND IsBillingAddress = 1 ";
					if($this->critery != "*"){
						# fuzzy search
						if($this->mode == 1){
							$sql .= "AND ad.Address like '%".$this->question."%'";
						}else{
							$sql .= "AND ad.Address =".$this->question;
						}
					}
				}								
			
				
			if($que = $mydb->sql_query($sql)){			
				$iLoop = 1;
				while($result = $mydb->sql_fetchrow($que)){
					$CustID = $result['CustID'];
					$AccID = $result['AccID'];
					$UserName = $result['UserName'];
					$StatusID = intval($result['StatusID']);
					$Address = $result['Address'];
					$Sangkat = $result['Sangkat'];
					$Khan = $result['Khan'];
					$City = $result['City'];					
					$Country = $result['Country'];					
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
					}
					if(($iLoop % 2) == 0)
						$style = "row1";
					else
						$style = "row2";
					$output .= '<tr>';	
					$output .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$AccountName.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';
					$output .= '<td align="center" bgcolor="'.$stbg.'">
												<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
											 </td>';
					$output .= '<td class="'.$style.'" align="left">'.$Address.'</td>';				
					$output .= '<td class="'.$style.'" align="left">'.$Sangkat.'</td>';	
					$output .= '<td class="'.$style.'" align="left">'.$Khan.'</td>';	
					$output .= '<td class="'.$style.'" align="left">'.$City.'</td>';	
					$output .= '<td class="'.$style.'" align="left">'.$Country.'</td>';	
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
			global $mydb;
			$output = '	<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
										<thead>
											<th>No.</th>
											<th>Invoice id</th>											
											<th>Account</th>
											<th>Due date</th>
											<th>Total amount</th>											
											<th>VAT amount</th>
											<th>Unpaid amount</th>
												
										</thead>
										<tbody>							
						';
						
			$sql = "SELECT i.InvoiceID, i.DueDate, i.InvoiceAmount, i.VATAmount, i.UnpaidAmount, i.AccID, a.CustID, a.UserName 
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
					$InvoiceID = $result['InvoiceID'];
					$DueDate = $result['DueDate'];
					$InvoiceAmount = $result['InvoiceAmount'];
					$VATAmount = $result['VATAmount'];
					$UnpaidAmount = $result['UnpaidAmount'];					
					$AccountName = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
					$linkInvoice = "<a href='./finance/screeninvoice.php?CustomerID=".$CustID."&InvoiceID=".$InvoiceID."' target='_blank'>".$InvoiceID."</a>";
					
					if(($iLoop % 2) == 0)
						$style = "row1";
					else
						$style = "row2";
					$output .= '<tr>';	
					$output .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$linkInvoice.'</td>';					
					$output .= '<td class="'.$style.'" align="left">'.$AccountName.'</td>';				
					$output .= '<td class="'.$style.'" align="left">'.formatDate($DueDate, 3).'</td>';	
					$output .= '<td class="'.$style.'" align="right">'.FormatCurrency($InvoiceAmount).'</td>';	
					$output .= '<td class="'.$style.'" align="right">'.FormatCurrency($VATAmount).'</td>';	
					$output .= '<td class="'.$style.'" align="right">'.FormatCurrency($UnpaidAmount).'</td>';	
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