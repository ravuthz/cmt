<link rel="stylesheet" type="text/css" href="../style/mystyle.css" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$skn=$_GET['skn'];
	$kn=$_GET['kn'];
	$city=$_GET['city'];
	$ct=$_GET['ct'];
	$sk=$_GET['sk'];	
	$cid=$_GET['cid'];	
	$sid1 = $_GET['sid1'];
	$servicename = $_GET['servicename'];
	$MessengerName = $_GET['ms'];
	$mid = $_GET['mid'];

	$skid=$_GET['skid'];
	
	if ($skid == 0) {
		$skid = 'and cp.bSangkatid = '.$sk;
	}
	else {
		$skid = "";
	}

	if ( $mid <> 0 ) {
		$mid = 'and cp.MessengerID = '.$mid;
	}
	else {
		$mid = "";
	}



	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle" width=70%>
						<b>MONTHLY INVOICE ISSUE:</b>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<b><font size="4">'.$MessengerName.'</font></b>
						<br />
						Cycle date: <b>'.$ct.'</b><br />
						Service : <b>'.$servicename.'</b><br />
						City &nbsp;:&nbsp; <b>'.$city.'</b>
						&nbsp;&nbsp;&nbsp;Khan&nbsp;:&nbsp; <b>'.$kn.'</b>
						&nbsp;&nbsp;&nbsp;Sangkat&nbsp; : &nbsp; <b><font size="4">'.$skn.'</font></b>
					</td>
					<td valign="bottom" align="right" class="formtitle">
						Print on: '.date("Y M d H:i:s").'	
					</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid">No.</th>
								<th align="left" style="border:1px solid">City</th>
								<th align="left" style="border:1px solid">Khan</th>
								<th align="left" style="border:1px solid" width="12%">Sangkat</th>
								<th align="left" style="border:1px solid">Phone</th>
								<th align="left" style="border:1px solid">Amount</th>
								<th align="center" style="border:1px solid">Customer Name</th>
								<th align="center" style="border:1px solid">House</th>
								<th align="center" style="border:1px solid">Street</th>
								<th align="center" style="border:1px solid" width="15%">Signature</th>														
							</thead>
							<tbody>';
	$sql = "select cp.bSangkatID Sangkatid, ci.invoiceid,ci.accid,cp.username,cp.SubscriptionName,ci.InvoiceAmount,
				
				cp.bHouse House,
			cp.bStreet Street,
				(select [name] from dbo.tlkpLocation where tlkpLocation.ID = cp.bSangkatID) Sangkat,
				(select Upper(left([name],5)) from dbo.tlkpLocation where tlkpLocation.ID = cp.bKhanID) Khan,
				(select Upper(left([name],3)) from dbo.tlkpLocation where tlkpLocation.ID = cp.bCityID) City 
			from tblCustomerInvoice ci 
			join tblCustProduct cp on ci.accid = cp.accid 
			
			join tblTarPackage tp on tp.packageid = cp.packageid 
			where ci.invoiceamount >= 3
			and ci.InvoiceID not in ( select invoiceid from tblCashPayment where convert(varchar,paymentdate,112) <= '".$cid."' )
			".$skid." ".$mid; 
if($sid1 == 2){
	$sql .= " and tp.ServiceID = 2 ";
}elseif($sid1 == 4){
	$sql .= " and tp.ServiceID = 4 ";
}elseif($sid1 == 1){	
	$sql .= " and tp.ServiceID in (1, 3, 8) ";
}
	$sql .= " and ci.BillingCycleid in 
				(select cycleid from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112) = '".$cid."')
			Order by City ,Khan ,Sangkat, cp.subscriptionName, cp.username  ";
			
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
			$House = $result['House'];
			$Street = $result['Street'];												
			$Sangkat = $result['Sangkat'];
			$Khan = $result['Khan'];
			$City = $result['City'];
						
			//$linkCust = "<a href='../?CustomerID=".$Custid."&pg=10'>".$CustName."</a>";
			//$linkAcc = "<a href='../?CustomerID=".$Custid."&AccountID=".$AccID."&pg=90'>".$UserName."</a>";		
			$totalamount += floatval($InvoiceAmount);		
			$iLoop++;															
			$n++;
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid; border-top:1px dotted;"><font size="1">'.$n.'</font></td>';	
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1" >'.$City.'</font></td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1" >'.$Khan.'</font></td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1" >'.$Sangkat.'</font></td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1" >'.$username.'</font></td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1" >'.formatcurrency($InvoiceAmount).'</font></td>';																														
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1">'.$SubscriptionName.'</font></td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1">'.$House.'</font></td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;"><font size="1">'.$Street.'</font></td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;"><font size="1">&nbsp;</font></td>';	

			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																					
								<tr height=20>
										<td colspan="10" align="center" style="border:1px solid">Total Amount = '.FormatCurrency($totalamount).'</td>
								</tr>
								</table>						
							</td>
						</tr>
					</table>';
     print $retOut;
?>