<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");
	$cid = $_GET['cid'];
	$acc = $_GET['acc'];
			
	$retOut = '
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa" bgColor="#ffffff">
							<thead>																																
								<th align="center">Rec#</th>
								<th align="center">Inv#</th>
								<th align="center">AccID</th>	
								<th align="center">Username</th>	
								<th align="center" nowrap="nowrap">IssuedDate</th>
								<th align="center">Amount</th>																				
								<th align="center">Mode</th>
								<th align="center">Cashier</th>
								<th align="center">PaidDate</th>																												
							</thead>';
	
	$sql = "select d.AcctID, d.PaymentID, d.PaymentAmount, convert(varchar, d.PaymentDate, 120) 'paiddate', 
							d.Cashier, d.InvoiceID, m.PaymentMode, s.TransactionName, i.IssueDate, a.UserName
					from tblCustCashDrawer d, tblCustProduct a(nolock), tlkpPaymentMode m, tlkpTransaction s, tblCustomerInvoice i 
					where d.PaymentModelID = m.PaymentID and d.TransactionModeID = s.TransactionID 
						and d.InvoiceID = i.InvoiceID 
						and d.AcctID = a.AccID
						and (d.IsRollback is NULL or d.IsRollback = 0) 
						and d.TransactionModeID in(1, 10, 14, 15) 
						and d.CustID = $cid
					";
						
	if(trim($acc != "*"))
		$sql .= "and a.UserName like '%".$acc."%' ";
	$sql .= " union select d.AcctID, d.PaymentID, d.PaymentAmount, convert(varchar, d.PaymentDate, 120) 'paiddate', 
							d.Cashier, 0 'InvoiceID', m.PaymentMode, s.TransactionName, NULL 'IssueDate', a.UserName
					from tblCustCashDrawer d, tblCustProduct a(nolock), tlkpPaymentMode m, tlkpTransaction s
					where d.PaymentModelID = m.PaymentID and d.TransactionModeID = s.TransactionID 						
						and d.AcctID = a.AccID
						and (d.IsRollback is NULL or d.IsRollback = 0) 
						and d.TransactionModeID in(3) 
						and d.CustID = $cid";
	if(trim($acc != "*"))
		$sql .= "and a.UserName like '%".$acc."%' ";						
	$sql .=	"order by 2 desc";
	
	
	if($que = $mydb->sql_query($sql)){
		$Total = 0;
		$iLoop = 0;
		$retOut .= "<tbody>";
		while($result = $mydb->sql_fetchrow()){																															
			$InvoiceID = $result['InvoiceID'];
			$AccID = $result['AcctID'];
			$PaymentID = $result['PaymentID'];
			$PaymentAmount = $result['PaymentAmount'];
			$PaymentDate = $result['paiddate'];
			$IssueDate = $result['IssueDate'];
			$Cashier = $result['Cashier'];
			$PaymentMode = $result['PaymentMode'];
			$UserName = $result['UserName'];
			$TransactionName = $result['TransactionName'];
			$Invoice = "" ;
			if($InvoiceID != 0)
			$Invoice = "<a href='./finance/screeninvoice.php?CustomerID=".$CustomerID."&InvoiceID=".$InvoiceID."' target='_blank'>".$InvoiceID."</a>";
			$Receipt = "<a href='./finance/receipt.php?CustomerID=".$CustomerID."&PaymentID=".$PaymentID."' target='_blank'>".$PaymentID."</a>";										

			$Total += floatval($PaymentAmount);											
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="left">'.$Receipt.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left">'.$Invoice.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$AccID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$UserName.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.formatDate($IssueDate, 1).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($PaymentAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$PaymentMode.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Cashier.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.formatDate($PaymentDate, 7).'</td>';
			$retOut .= '</tr>';
		}		
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan="5">Total</td>
											<td align="right">'.FormatCurrency($Total).'</td>
											<td align="right" colspan="3">&nbsp;</td>
										</tr>
										
									</tfoot>';
	}
	$mydb->sql_freeresult();		
	$retOut	.= "</table>";
	//print $sql;
	print $retOut;	
?>
