<link rel="stylesheet" type="text/css" href="../style/mystyle.css" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$ct=$_GET['ct'];
	$sk=$_GET['sk'];	
	$cid=$_GET['cid'];	
	$where = $_GET['where'];
	$sid1 = $_GET['sid1'];
	$servicename = $_GET['servicename'];
	
	$pa = $_GET['pa'];
	
	if ($pa=='pco'){
		$pa = 'and cp.packageid in (16,17)';
	} else if ($pa =='cmt'){
		$pa = 'and cp.packageid in (9)';
	} else {
		$pa = 'and cp.packageid not in ( 9, 16, 17, 18, 19, 79 )';
	} 
	
	$skid=$_GET['skid'];
	
	if ($skid == 0) {
		$skid = 'and ca.Sangkatid = '.$sk;
	} else {
		$skid = ' ';
	}
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle" width=84%>
						<b>MONTHLY INVOICE ISSUE:</b><br />
						Cycle date: <b>'.$ct.'</b><br />
						Service : <b>'.$servicename.'</b><br />
						Print on: '.date("Y M d H:i:s").'
						
					</td>
					<td align="left">
								
					</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid">No.</th>
								<th align="left" style="border:1px solid">Tele.</th>
								<th align="center" style="border:1px solid">CustomerName</th>
								<th align="center" style="border:1px solid">InvID</th>
								<th align="center" style="border:1px solid">Amount</th>
								<th align="center" style="border:1px solid">Address</th>								
								<th align="center" style="border:1px solid">Sangkat</th>
								<th align="center" style="border:1px solid">Khan</th>
								<th align="center" style="border:1px solid">City</th>						
							</thead>
							<tbody>';
	$sql = "select ca.Sangkatid, ci.invoiceid,ci.accid,cp.username,left(cp.SubscriptionName,16) SubscriptionName,ci.InvoiceAmount,
				ca.Address,
				(select [name] from dbo.tlkpLocation where tlkpLocation.ID = ca.SangkatID) Sangkat,
				(select [name] from dbo.tlkpLocation where tlkpLocation.ID = ca.KhanID) Khan,
				(select Upper(left([name],3)) from dbo.tlkpLocation where tlkpLocation.ID = ca.CityID) City 
			from tblCustomerInvoice ci 
			join tblCustProduct cp on ci.accid = cp.accid 
			join tblCustAddress ca on ca.accid = cp.accid 
			join tblTarPackage tp on tp.packageid = cp.packageid 
			where IsBillingaddress = 1
			and ci.invoiceamount >= 0.01 
			and ci.InvoiceType in (1,2,3)
			and ci.InvoiceID not in ( select invoiceid from tblCashPayment where convert(varchar,paymentdate,112) <= '".$cid."' )
			".$skid." ".$pa; 
if($sid1 == 2){
	$sql .= " and tp.ServiceID = 2 ";
}elseif($sid1 == 4){
	$sql .= " and tp.ServiceID = 4 ";
}elseif($sid1 == 5){	
	$sql .= " and tp.ServiceID in (1, 3, 8) ";
}
	$sql .= " and ci.BillingCycleid in 
				(select cycleid from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112) = '".$cid."')
			Order by cp.username, cp.subscriptionName, City ,Khan ,Sangkat ";
			
	if($que = $mydb->sql_query($sql)){		
		$n = 0;
		$totalamount = 0.00;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$invoiceid = $result['invoiceid'];
			$accid = $result['accid'];
			$username = $result['username'];
			$SubscriptionName = $result['SubscriptionName'];
			$InvoiceAmount = $result['InvoiceAmount'];					
			$Address = $result['Address'];										
			$Sangkat = $result['Sangkat'];
			$Khan = $result['Khan'];
			$City = $result['City'];
						
			//$linkCust = "<a href='../?CustomerID=".$Custid."&pg=10'>".$CustName."</a>";
			//$linkAcc = "<a href='../?CustomerID=".$Custid."&AccountID=".$AccID."&pg=90'>".$UserName."</a>";		
			$totalamount+= floatval($InvoiceAmount);		
			$iLoop++;															
			$n++;
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid; border-top:1px dotted;"><font size="1">'.$n.'</font></td>';																															
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1">'.$username.'</font></td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1">'.$SubscriptionName.'</font></td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1">'.$invoiceid.'</font></td>';	
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted; border-right:1px solid"><font size="1">'.formatCurrency($InvoiceAmount).'</font></td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1">'.$Address.'</font></td>';	
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1">'.$Sangkat.'</font></td>';	
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1">'.$Khan.'</font></td>';	
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1">'.$City.'</font></td>';	
			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																					
								<tr height=20>
										<td colspan="4" align="right" style="border:1px solid">Total:&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td align="right" style="border:1px solid">'.formatCurrency($totalamount).'</td>
										<td colspan="4" align="right" style="border:1px solid">&nbsp;&nbsp;&nbsp;&nbsp;</td>
								</tr>
								</table>						
							</td>
						</tr>
					</table>';
     print $retOut;
?>