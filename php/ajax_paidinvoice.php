<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");
	require("../common/configs.php");
	$cid = $_GET['cid'];
	$acc = $_GET['acc'];
			
	$retOut = '
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa" bgColor="#ffffff">
							<thead>																																
								<th align="center">Inv#</th>
								<th align="center">AccID</th>	
								<th align="center">Username</th>	
								<th align="center">Issueddate</th>
								<th align="center">Paymentdate</th>																				
								<th align="center">Paidwithin</th>
								<th align="center">Reminder</th>
								<th align="center">Amount</th>																												
							</thead>';
	
	$sql = "SELECT a.AccID,a.UserName, i.InvoiceID, i.IssueDate, i.InvoiceAmount, i.Reminder, i.OriginalUnpaidAmount, i.PaymentDate
							FROM tblCustProduct a INNER JOIN tblCustomerInvoice i ON a.AccID = i.AccID
																				LEFT JOIN tblCustCashDrawer d on i.InvoiceID = d.InvoiceID
							WHERE i.CustID = $cid AND (d.IsRollBack IS NULL or d.IsRollBack = 0)																					
							 ";
	if(trim($acc != "*"))
		$sql .= "and a.UserName like '%".$acc."%' ";
	$sql .=	"GROUP BY a.AccID,a.UserName, i.InvoiceID, i.IssueDate, i.InvoiceAmount, i.Reminder, i.OriginalUnpaidAmount, i.PaymentDate  
						HAVING i.InvoiceAmount = IsNULL(SUM(d.PaymentAmount), 0)
					UNION 
					SELECT a.AccID,a.UserName, i.InvoiceID, i.IssueDate, i.InvoiceAmount, i.Reminder, i.OriginalUnpaidAmount, i.PaymentDate
							FROM tblCustProduct a INNER JOIN tblCustomerInvoice i ON a.AccID = i.AccID
							WHERE i.CustID = $cid AND UnpaidAmount = 0 AND i.InvoiceID NOT IN
								(SELECT InvoiceID FROM tblCustCashDrawer WHERE CustID = $cid AND (IsRollback is NULL OR IsRollback = 0)) ";
	if(trim($acc != "*"))
		$sql .= " and a.UserName like '%".$acc."%' ";
	$sql .= " ORDER BY 3 DESC";
	if($que = $mydb->sql_query($sql)){
		$total = 0;
		$iLoop = 0;
		$retOut .= "<tbody>";
		while($result = $mydb->sql_fetchrow()){																															
			$InvoiceID = $result['InvoiceID'];
			$AccID = $result['AccID'];
			$UserName = $result['UserName'];
			$IssueDate = $result['IssueDate'];
			$PaymentDate = $result['PaymentDate'];
			$Reminder = $result['Reminder'];
			$InvoiceAmount = $result['InvoiceAmount'];
			$Paidwithin = datediff($IssueDate, $PaymentDate);										
			$link = "<a href='./?CustomerID=".$CustomerID."&InvoiceID=".$InvoiceID."&pg=3'>Pay</a>";
			$Invoice = "<a href='./finance/screeninvoice.php?CustomerID=".$CustomerID."&InvoiceID=".$InvoiceID."' target='_blank'>".$InvoiceID."</a>";																

			$total += floatval($InvoiceAmount);											
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="left">'.$Invoice.'</td>';		
			$retOut .= '<td class="'.$style.'" align="left">'.$AccID.'</td>';																						
			$retOut .= '<td class="'.$style.'" align="left">'.$UserName.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.formatDate($IssueDate, 1).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.formatDate($PaymentDate, 1).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.$Paidwithin.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.$Reminder.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($InvoiceAmount).'</td>';
			$retOut .= '</tr>';
		}		
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan="7">Total</td>
											<td align="right">'.FormatCurrency($total).'</td>
										</tr>
									</tfoot>';
	}
	$mydb->sql_freeresult();		
	$retOut	.= "</table>";
	//print $sql;
	print $retOut;	
?>
