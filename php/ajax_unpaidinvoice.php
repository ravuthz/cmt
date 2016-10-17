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
								<th align="center">IssuedDate</th>																
								<th align="center">Reminder</th>			
								<th align="center">Value</th>
								<th align="center">Unpaid</th>				
								<th align="center">Pay</th>																								
							</thead>';
	
	$sql = "select a.AccID,a.UserName, i.InvoiceID, i.IssueDate, i.DueDate, i.InvoiceAmount, i.Reminder, 
								(i.InvoiceAmount - sum(d.PaymentAmount)) as 'UnpaidAmount' 
					from tblCustProduct a inner join tblCustomerInvoice i on a.AccID = i.AccID
																left join tblCustCashDrawer d on i.InvoiceID = d.InvoiceID 
																		
					where i.CustID = $cid and (d.IsRollback is NULL or d.IsRollback = 0) ";
	if(trim($acc != "*"))
		$sql .= "and a.UserName like '%".$acc."%' ";
	$sql .=	"group by a.AccID,a.UserName, i.UnpaidAmount, i.InvoiceID, i.IssueDate, i.DueDate, i.InvoiceAmount, 
									i.Reminder, i.OriginalUnpaidAmount  
					having (i.InvoiceAmount - sum(d.PaymentAmount)) > 0
					union 
					select a.AccID,a.UserName, i.InvoiceID, i.IssueDate, i.DueDate, i.InvoiceAmount, i.Reminder, i.UnpaidAmount 
					from tblCustProduct a, tblCustomerInvoice i 
					where a.AccID = i.AccID and i.CustID = $cid and i.UnpaidAmount > 0
						and i.InvoiceID not in 
						(select InvoiceID from tblCustCashDrawer where CustID = $cid and (IsRollback is NULL or IsRollback = 0)) ";
	if(trim($acc != "*"))
		$sql .= "and a.UserName like '%".$acc."%' ";
	
	if($que = $mydb->sql_query($sql)){
		$totalAmount = 0.00;
		$totalunpaid = 0.00;
		$iLoop = 0;
		$retOut .= "<tbody>";
		while($result = $mydb->sql_fetchrow()){																															
			$AccID = $result['AccID'];										
			$UserName = $result['UserName'];										
			$InvoiceID = $result['InvoiceID'];										
			$IssueDate = $result['IssueDate'];																
			$DueDate = $result['DueDate'];											
			$InvoiceAmount = $result['InvoiceAmount'];
			$Reminder = $result['Reminder'];
			$UnpaidAmount = $result['UnpaidAmount'];											
			
			$link = "<a href='./?CustomerID=".$CustomerID."&InvoiceID=".$InvoiceID."&pg=44'>Pay</a>";
			$Invoice = "<a href='./finance/screeninvoice.php?CustomerID=".$CustomerID."&InvoiceID=".$InvoiceID."' target='_blank'>".$InvoiceID."</a>";																
			$totalAmount += floatval($InvoiceAmount);
			$totalunpaid += floatval($UnpaidAmount);											
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
			$retOut .= '<td class="'.$style.'" align="right">'.formatDate($DueDate, 1).'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Reminder.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($InvoiceAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($UnpaidAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$link.'</td>';
			$retOut .= '</tr>';
		}		
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan="6">Total</td>
											<td align="right">'.FormatCurrency($totalAmount).'</td>
											<td align="right">'.FormatCurrency($totalunpaid).'</td>
											<td align="right">&nbsp;</td>
										</tr>
									</tfoot>';
	}
	$mydb->sql_freeresult();		
	$retOut	.= "</table>";
	//print $sql;
	print $retOut;	
?>