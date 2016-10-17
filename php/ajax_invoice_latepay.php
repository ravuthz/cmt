<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$st=$_GET['st'];
	$et=$_GET['et'];
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>ACCOUNT WITH UNPAID INVOICE MORE THAN 2: '.FormatDate($st, 3).' to '.FormatDate($et, 3).'</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>
								<th align="center">Account</th>
								<th align="center">Subscription name</th>
								<th align="center">Status</th>
								<th align="center">Package</th>								
								<th align="center">Total unpaid invoices</th>																						
								<th align="center">No. Inv</th>								
								<th align="center">Suggest</th>								
							</thead>
							<tbody>';
	$sql = "select AccID, IsNULL(Count(InvoiceID), 0) as 'Invoice', Sum(UnpaidAmount) as 'Unpaid'
					into #dummy
					from tblCustomerInvoice
					where UnpaidAmount > 0
						AND InvoiceID not in(select InvoiceID from tblCustCashDrawer where IsRollback is NULL or IsRollback = 0)
						AND convert(varchar, IssueDate, 112) >= '".formatDate($st, 4)."'
						AND convert(varchar, IssueDate, 112) <= '".formatDate($et, 4)."' 
					group by AccID
					union 
					select i.AccID, IsNULL(Count(i.InvoiceID), 0) as 'Invoice', Sum(i.UnpaidAmount) as 'Unpaid'
					from tblCustomerInvoice i, tblCustCashDrawer d
					where i.InvoiceID = d.InvoiceID AND (d.IsRollback is NULL or d.IsRollback = 0)
						AND convert(varchar, i.IssueDate, 112) >= '".formatDate($st, 4)."'
						AND convert(varchar, i.IssueDate, 112) <= '".formatDate($et, 4)."'
					group by i.AccID, i.InvoiceAmount
					having i.InvoiceAmount > sum(d.PaymentAmount)
					
					select a.CustID, a.StatusID, a.AccID, a.UserName, a.SubscriptionName, t.TarName, 
							sum(d.Invoice) as 'No', Sum(d.Unpaid) as 'UnpaidAmount'
					from #dummy d, tblCustProduct a, tblTarPackage t
					where d.AccID = a.AccID and a.PackageID = t.PackageID
					group by a.CustID, a.StatusID, a.AccID, a.UserName, a.SubscriptionName, t.TarName
					having sum(d.Invoice) > 1
					drop table #dummy ";
			
	if($que = $mydb->sql_query($sql)){										
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																
			$CustID = $result['CustID'];																
			$StatusID = $result['StatusID'];
			$AccID = $result['AccID'];											
			$UserName = $result['UserName'];																
			$SubscriptionName =$result['SubscriptionName'];
			$TarName = $result['TarName'];
			$No = $result['No'];
			$UnpaidAmount = $result['UnpaidAmount'];																						
			switch($StatusID){
				case 0:
					$stbg = $bgUnactivate;
					$stfg = $foreUnactivate;
					$stwd = "Inactive";
					break;
				case 1:
					$stbg = $bgActivate;
					$stfg = $foreActivate;
					$stwd = "Active";
					$link = "<a href='./?CustomerID=".$CustID."&AccountID=".AccID."&md=2&cst=1&uname=".$UserName."&pg=249'>Close</a>";													
					break;
				case 2:
					$stbg = $bgLock;
					$stfg = $foreLock;
					$stwd = "Barred";
					break;
				case 3:
					$stbg = $bgClose;
					$stfg = $foreClose;
					$stwd = "Closed";
					break;
				case 4:
					$stbg = $bgClose;
					$stfg = $foreClose;
					$stwd = "Closed";
					break;
			}
			$linkInvoice = "<a href='./?CustomerID=".$CustID."&pg=41'>".$No."</a>";
			$linkAcct = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";											
			$iLoop++;															
			if(($iLoop % 2) == 0)
				$style = "row1";
			else
				$style = "row2";
			$retOut .= '<tr>';	
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';								
			$retOut .= '<td class="'.$style.'" align="left">'.$linkAcct.'</td>';	
			$retOut .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';
			$retOut .= '<td align="center" bgcolor="'.$stbg.'">
									<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
								 </td>';		
			$retOut .= '<td class="'.$style.'" align="left">'.$TarName.'</td>';								
			$retOut .= '<td class="'.$style.'" align="left">'.$UnpaidAmount.'</td>';											
			$retOut .= '<td class="'.$style.'" align="right">'.$linkInvoice.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$link.'</td>';
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