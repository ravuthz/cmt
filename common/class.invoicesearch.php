<SCRIPT LANGUAGE="JavaScript">


function Maximize(URL) 
{
//window.open(URL,"Max","resizable=1,toolbar=1,location=1,directories=1,addressbar=1,scrollbars=1,status=1,menubar=1,maximize=1,top=0,left=0,screenX=" + window.screenLeft + ",screenY=" + window.screenTop + ",width=" + screen.availWidth + ",height=" + screen.availHeight);
window.open(URL,null,"fullscreen=yes,resizable=no,location=no,top=0,left=0");
}
</SCRIPT>
<?php 
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
	class invoicesearch{
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
		function InvoiceSearch($scritery, $type){
			//$this->searchType = $sMode;
			$this->critery = $scritery;	
			$this->searchType = $type;	
		}// End function
		
		//
		//	Search by customer
		//
		function search(){
			global $mydb;
			$output = '	<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
										<thead>
											<th>No.</th>
											<th>InvID</th>											
											<th>AccID</th>
											<th>UserName</th>
											<th>Customer Name</th>
											<th>IssueDate</th>
											<th>Total</th>											
											<th>Paid</th>
											<th>Unpaid</th>
											<th>Pay</th>												
										</thead>
										<tbody>							
						';
			$sql = "
							BEGIN TRY		
								Drop Table #invdetail
							END TRY
							BEGIN CATCH
							END CATCH

						
						Select invoiceid, amount into #invdetail from tblCustomerInvoiceDetail where BillItemID = 11 
			
			
			select c.CustID, a.AccID, a.UserName, i.InvoiceID, i.IssueDate, i.DueDate, i.InvoiceAmount, a.SubscriptionName,
								i.Reminder, (i.InvoiceAmount - sum(d.PaymentAmount) + IsNull(ivd.amount,0)) as 'UnpaidAmount',
								 sum(d.PaymentAmount) - IsNull(ivd.amount,0) 'Paid'	 
							from tblCustomer c inner join tblCustProduct a on c.CustID = a.CustID 
								inner join tblCustomerInvoice i on a.AccID = i.AccID
								left join #invdetail ivd on ivd.invoiceid = i.invoiceid
								left join tblCustCashDrawer d on i.InvoiceID = d.InvoiceID
							where (d.IsRollback is NULL or d.IsRollback = 0) ";
			if($this->critery != "*"){
				switch($this->searchType){
								case 1:
									$sql .= " and (i.CustID like '%".$this->critery."%')";
									break;
								case 2:
									$sql .= " and (c.CustName like '%".$this->critery."%')";
									break;
								case 3:
									$sql .= " and (i.AccID like '%".$this->critery."%')";
									break;
								case 4:
									$sql .= " and (i.InvoiceID like '%".$this->critery."%')";
									break;
								case 5:	
									$sql .= " and (a.UserName like '%".$this->critery."%')";
									break;
								case 6:	
									$sql .= " and (a.SubscriptionName like '%".$this->critery."%')";
									break;
							}
			}				
			$sql .=	" group by c.CustID,a.AccID, i.UnpaidAmount, a.UserName,
									i.InvoiceID, i.IssueDate, i.DueDate, i.InvoiceAmount, a.SubscriptionName, i.Reminder, i.OriginalUnpaidAmount, ivd.amount  
								having round((i.InvoiceAmount - sum(d.PaymentAmount) + IsNull(ivd.amount,0)),2) > 0
							union ";
			$sql .=	" select c.CustID, a.AccID, a.UserName, i.InvoiceID, i.IssueDate, i.DueDate, i.InvoiceAmount,a.SubscriptionName, i.Reminder, i.UnpaidAmount , i.InvoiceAmount - i.UnpaidAmount 'Paid'
							from tblCustomerInvoice i inner join tblCustProduct a on i.AccID = a.AccID 
								inner join tblCustomer c on a.CustID = c.CustID
							where i.UnpaidAmount > 0
								and i.InvoiceID not in 
								(select InvoiceID from tblCustCashDrawer where (IsRollback is NULL or IsRollback = 0))";
					
			if($this->critery != "*"){
				switch($this->searchType){
								case 1:
									$sql .= " and (i.CustID like '%".$this->critery."%')";
									break;
								case 2:
									$sql .= " and (c.CustName like '%".$this->critery."%')";
									break;
								case 3:
									$sql .= " and (i.AccID like '%".$this->critery."%')";
									break;
								case 4:
									$sql .= " and (i.InvoiceID like '%".$this->critery."%')";
									break;
								case 5:	
									$sql .= " and (a.UserName like '%".$this->critery."%')";
									break;
								case 6:	
									$sql .= " and (a.SubscriptionName like '%".$this->critery."%')";
									break;
							}
			}
			
			$sql .= " Drop Table #invdetail ";
			
			if($que = $mydb->sql_query($sql)){			
				$iLoop = 1;
				while($result = $mydb->sql_fetchrow($que)){
					$CustID = $result['CustID'];
					$AccID = $result['AccID'];
					$UserName = $result['UserName'];
					$InvoiceID = $result['InvoiceID'];
					$IssueDate = $result['IssueDate'];
					$InvoiceAmount = $result['InvoiceAmount'];
					$SubscriptionName = $result['SubscriptionName'];
					$VATAmount = $result['VATAmount'];
					$UnpaidAmount = $result['UnpaidAmount'];	
					$Paid = $result['Paid'];	

					$pay = "<a HREF='#' onClick='Maximize(\"./?CustomerID=".$CustID."&InvoiceID=".$InvoiceID."&pg=44\");'>Pay</a>";
					//$pay = "<a HREF='javascript:window.open(\"./?CustomerID=".$CustID."&InvoiceID=".$InvoiceID."&pg=44\",\"newwin\");'>Pay</a>";
			
					$AccountName = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
					$linkInvoice = "<a href='./finance/screeninvoice.php?CustomerID=".$CustID."&InvoiceID=".$InvoiceID."' target='_blank'>".$InvoiceID."</a>";
					
					if(($iLoop % 2) == 0)
						$style = "row1";
					else
						$style = "row2";
					$output .= '<tr>';	
					$output .= '<td class="'.$style.'" align="left">'.$iLoop.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$linkInvoice.'</td>';
					$output .= '<td class="'.$style.'" align="left">'.$AccID.'</td>';									
					$output .= '<td class="'.$style.'" align="left">'.$AccountName.'</td>';				
					$output .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';				
					$output .= '<td class="'.$style.'" align="right">'.formatDate($IssueDate, 3).'</td>';	
					$output .= '<td class="'.$style.'" align="right">'.FormatCurrency($InvoiceAmount).'</td>';	
					$output .= '<td class="'.$style.'" align="right">'.FormatCurrency($Paid).'</td>';	
					$output .= '<td class="'.$style.'" align="right">'.FormatCurrency($UnpaidAmount).'</td>';	
					$output .= '<td class="'.$style.'" align="right">'.$pay.'</td>';
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