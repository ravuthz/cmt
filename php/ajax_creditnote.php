<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];
	$where = $_GET['where'];
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>&nbsp;Credit Note - from '.formatDate($st, 6).' and '.formatDate($et, 6).'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">Customer</th>								
								<th align="center">Account</th>
								<th align="center">Inv #</th>
								<th align="center">Amount</th>	
								<th align="center">Net Amount</th>
								<th align="center">Credit Note</th>
								<th align="center">Date</th>
								<th align="center">Cashier</th>	
								<th align="center">Status</th>
							</thead>
							<tbody>';
	
	$sql = "
			select c.CustID, c.CustName, a.AccID 'AccountId', a.UserName, i.InvoiceID, 	
			i.InvoiceAmount, i.NetAmount, i.IssueDate, 
			p.PaymentAmount, p.PaymentDate, p.Cashier, 1 'posted'
			from tblCustomer c(nolock), tblCustProduct a(nolock), tblCustomerInvoice i(nolock), tblcashPayment p(nolock)
			where c.CustID = p.CustID and p.InvoiceID = i.InvoiceID and a.AccID = p.AcctID and 	
			p.TransactionModeID = 14
					and convert(varchar, p.PaymentDate, 112) >= '".formatDate($st, 4)."' 
					and convert(varchar, p.PaymentDate, 112) <= '".formatDate($et, 4)."'
					and a.PackageID in(".$where.")";
		if($c != 0)
			
	$sql .= " order by d.PaymentDate";		 	
							
	if($que = $mydb->sql_query($sql)){		
		
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$CustID = $result['CustID'];										
			$CustName = $result['CustName'];
			$AccID = $result['AccID'];
			$UserName = $result['UserName'];
			$InvoiceID = $result['InvoiceID'];
			$InvoiceAmount = $result['InvoiceAmount'];
			$NetAmount = $result['NetAmount'];
			$IssueDate = $result['IssueDate'];
			$PaymentAmount = $result['PaymentAmount'];
			$PaymentDate = $result['PaymentDate'];
			$Cashier = $result['Cashier'];
			$Pospted = $result['posted'];			
			$linkCust = "<a href='./?CustomerID=".$CustID."&pg=10'>".$CustName."</a>";
			$linkAcc = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=90'>".$UserName."</a>";		
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.'" align="left">'.$linkCust.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left">'.$linkAcc.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$InvoiceID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">$'.$InvoiceAmount.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">$'.$NetAmount.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">$'.$InvoiceID.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.formatDate($PaymentDate, 3).'</td>';		
			$retOut .= '<td class="'.$style.'" align="left">'.$Cashier.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Pospted.'</td>';
			$retOut .= '</tr>';									
		}		
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																					
								</table>						
							</td>
						</tr>
					</table>';
		
	print $retOut;	
?>