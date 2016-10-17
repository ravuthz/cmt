<?php
	require_once("./common/agent.php");	
	require_once("./common/functions.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	// Get drawer

?>	
<script language="javascript" type="text/javascript" src="./javascript/loading.js"></script>
<script language="javascript" type="text/javascript" src="./javascript/date.js"></script>
<script language="javascript" type="text/javascript" src="./javascript/ajax_gettransaction.js"></script>
<script language="javascript" type="text/javascript">	
				
	function showlevel2(div, tid, drawerid, date, sid){
		var loading;
loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
		document.getElementById(div).innerHTML = loading;
		url = "./php/ajax_level_2.php?div="+div+"&tid="+tid+"&drawerid="+drawerid+"&date="+date+"&sid="+sid+"&mt=" + new Date().getTime();

		getTranDetail(url, div);
	}
	
	function showlevel3(div, tid, drawerid, date, packageid, sid){
		var loading;
loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
		document.getElementById(div).innerHTML = loading;
		url = "./php/ajax_level_3.php?div="+div+"&tid="+tid+"&drawerid="+drawerid+"&date="+date+"&packageid="+packageid+"&sid="+sid+"&mt=" + new Date().getTime();
		getTranDetail(url, div);
	}
	
	function showlevel4(div, tid, drawerid, date, packageid, trid, pid, sid){
		var loading;
loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
	document.getElementById(div).innerHTML = loading;
	url = "./php/ajax_level_4.php?div="+div+"&tid="+tid+"&drawerid="+drawerid+"&date="+date+"&packageid="+packageid+"&trid="+trid+"&pid="+pid+"&sid="+sid+"&mt=" + new Date().getTime();
		getTranDetail(url, div);
	}

	function hide(div){
		document.getElementById(div).innerHTML = "";
	}
</script>

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<form name="f1" method="post" action="./">
					<td align="left" class="formtitle"><b>Payment transaction</b>
						<select name="sid1" onchange="f1.submit();">
							<option value="0" <?php if(intval($sid1) == 0) print "selected";?>>All services</option>
							<option value="2" <?php if(intval($sid1) == 2) print "selected";?>>Telephone</option>
							<option value="4" <?php if(intval($sid1) == 4) print "selected";?>>Leased line</option>
							<option value="5" <?php if(intval($sid1) == 5) print "selected";?>>ISP</option>
						</select>
					</td>
					<td align="right">
						
							<input type="text" tabindex="3" name="st1" class="boxenabled" size="27" maxlength="30" value="<?php print $st1; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
							<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?f1|st', '', 'width=200,height=220,top=250,left=350');">
								<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
							</button>		
							<button name="imgGo" onClick="f1.submit();" class="invisibleButtons">
							<img src="./images/go1.gif" border="0" alt="Search" class="invisibleButtons">
						</button>				
					</td>
					
					<input type="hidden" name="pg" value="206" />
					</form>
				</tr> 
				<tr>
					<td colspan="2">
						<div id="d1">
							<?php								
									$sql = "select d.DrawerID, convert(varchar, d.PaymentDate, 112) as 'Date', d.Cashier, 
																Count(d.PaymentID) as 'NoTran', Sum(d.PaymentAmount) as 'Amount'
													from tblCustCashDrawer d(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock), tlkpTransaction t
													where d.AcctID = a.AccID
													and d.DrawerID <> 1
														and a.PackageID = p.PackageID
														and d.TransactionModeID = t.TransactionID
														and t.TranGroupID = 1
														and (d.IsRollBack = 0 or d.IsRollBack is NULL) ";
								if((isset($st1)) && (!empty($st1))){
									$sql .= " and convert(varchar, d.PaymentDate, 112) = ".FormatDate($st1, 4)." ";
								}else{
									$sql .= " and d.IsSubmitted = 0 ";
								} 
								//if((isset($sid1)) && (!empty($sid1))){
								if($sid1 == 2){
									$sql .= " and p.ServiceID = 2 ";
								}elseif($sid1 == 4){
									$sql .= " and p.ServiceID = 4 ";
								}elseif($sid1 == 5){	
									$sql .= " and p.ServiceID in(1, 3, 8) ";
								}	
								$sql .= "	group by d.DrawerID, convert(varchar, d.PaymentDate, 112), d.Cashier";

				$retOut = '<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
										<thead>																	
											<th align="center">No</th>								
											<th align="center">Date</th>
											<th align="center">Cashier</th>									
											<th align="center">No of Tran</th>																			
											<th align="center">Amount</th>																												
										</thead>
										<tbody>';
									if($que = $mydb->sql_query($sql)){
										$totalAmount = 0.00;
										$iLoop = 0;
										while($result = $mydb->sql_fetchrow($que)){
											$DrawerID = $result['DrawerID'];
											$Date = $result['Date'];
											$Cashier = $result['Cashier'];
											$NoTran = $result['NoTran'];
											$Amount = $result['Amount'];
											$ServiceID = $result['ServiceID'];
											$Cash = "<a href=\"javascript:showlevel2('d1-1', 1, ".$DrawerID.", '".$Date."', '".$sid1."');\">".$Cashier."</a>";
											$download = ' [<a href="./export/payment_detail.php?did='.$DrawerID.'&cashier='.$Cashier.'&t=1&serviceid='.$sid1.'&type=csv">Download detail</a>]';
											$totalAmount += floatval($Amount);
											$iLoop ++;
											if(($iLoop % 2) == 0)											
												$style = "row1";
											else
												$style = "row2";
											$retOut .= '<tr>
																		<td align="left" class="'.$style.'">'.$iLoop.'</td>
																		<td align="left" class="'.$style.'">'.FormatDate($Date, 3).'</td>
																		<td align="left" class="'.$style.'">'.$Cash.'</td>
																		<td align="left" class="'.$style.'">'.$NoTran.$download.'</td>
																		<td align="right" class="'.$style.'">'.FormatCurrency($Amount).'</td>	
																	</tr>	
																	';																					
										} 
										$retOut .= '</tbody>
																	<tfoot>
																		<tr>
																			<td align="right" colspan="4">Total</td>
																			<td align="right">'.FormatCurrency($totalAmount).'</td>
																		</tr>
																	</tfoot>
																	';
									}
								$retOut .= '</table>';
								
							print $retOut;
							?>
						</div>
					</td>
				</tr>
				<tr><td align="right">
				<div id="tot">
				<?php 
				$sql1 = "	BEGIN TRY DROP TABLE #drawer END TRY BEGIN CATCH END CATCH 
							BEGIN TRY DROP TABLE #p END TRY BEGIN CATCH END CATCH 
							BEGIN TRY DROP TABLE #i END TRY BEGIN CATCH END CATCH 


select	InvoiceID,
		m.PaymentMode, 
		TransactionName = case 
								when ts.TransactionID = 1 then 'Fee payment' 
								when ts.TransactionID = 3 then 'Advance payment' 
								when ts.TransactionID in ( 2,7,8,9 ) then 'Refund' 
								when ts.TransactionID in (4,5,6) 
								then 'Book Deposit' 
								else TransactionName 
							End,
		Sum(p.PaymentAmount) as 'pAmount'						
Into #p								
from tblCustCashDrawer p(nolock) 
join tlkpTransaction ts(nolock) on ts.TransactionID = p.TransactionModeID
join tlkpPaymentMode m(nolock) on p.PaymentModelID = m.PaymentID
where p.DrawerID <> 1 
and ts.TransactionID not in ( 2,7,8,9 ) 
and (p.IsRollBack IS NULL or p.IsRollBack = 0) ";

if($sid1 == 2){
									$sql1 .= " and t.ServiceID = 2 ";
								}elseif($sid1 == 4){
									$sql1 .= " and t.ServiceID = 4 ";
								}elseif($sid1 == 5){	
									$sql1 .= " and t.ServiceID in(1, 3, 8) ";
								}	



if((isset($st1)) && (!empty($st1))){
	$sql1 .= " and convert(varchar, p.PaymentDate, 112) = '".FormatDate($st1, 4)."'";
}	
else{
	$sql1 .= " and p.IsSubmitted = 0 ";
} 

	$sql1 .= "	Group by m.PaymentMode, InvoiceID,
						case 
								when ts.TransactionID = 1 then 'Fee payment' 
								when ts.TransactionID = 3 then 'Advance payment' 
								when ts.TransactionID in ( 2,7,8,9 ) then 'Refund' 
								when ts.TransactionID in (4,5,6) 
								then 'Book Deposit' 
								else TransactionName 
						End
				
select InvoiceID, it.InvoiceTypeName Invoicetype
into #i
from tblCustomerInvoice ci
join tlkpInvoiceType it on ci.InvoiceType = it.InvoiceType
where InvoiceID in (select InvoiceID from #p)
								
select PaymentMode, TransactionName, IsNull(Invoicetype,'Other Receipt') Invoicetype, Sum(pAmount) pAmount
into #drawer 
from #p 
left join #i on #p.InvoiceID = #i.InvoiceID
Group by PaymentMode, TransactionName, Invoicetype


Update #drawer set TransactionName = Invoicetype where TransactionName = 'Fee payment'
Update #drawer set Invoicetype = 1 where TransactionName = 'Cycle Bills'
Update #drawer set Invoicetype = 2 where TransactionName = 'Demand Bills'
Update #drawer set Invoicetype = 3 where TransactionName = 'Advance payment'
Update #drawer set Invoicetype = 4 where TransactionName = 'Book Deposit'
Update #drawer set Invoicetype = 5 where TransactionName = 'Other Bills'
Update #drawer set Invoicetype = 6 where TransactionName = 'Refund'


select	TransactionName,
		IsNull([Cash], 0) 'Cash', 
		IsNull([Cheque], 0) 'Cheque' 
from 
(
	select TransactionName,pAmount,PaymentMode,invoicetype from #drawer 
)id
PIVOT (sum(pAmount) FOR PaymentMode IN ([Cash], [Cheque])) AS pvt
Order by InvoiceType

Drop table #p
Drop table #i
Drop table #drawer

";

				
	$tCashCh = 0;
	$tCash = 0;
	$tCh = 0;
	$ttCaCh = 0;				
	if($que1 = $mydb->sql_query($sql1)){
		$detail = "<div><table><tr><td align='left'><b>Description</b></td><td><b>Cash</b></td><td><b>Cheque</b></td><td><b>Total</b></td></tr>";
		while($result1 = $mydb->sql_fetchrow($que1)){
			$pcash = $result1['Cash'];
			$pCheque = $result1['Cheque'];
			$pTransactionName = $result1['TransactionName'];
			$tCashCh = $pcash + $pCheque;
			$tCash +=  $pcash;
			$tCh += $pCheque;
			$ttCaCh += $tCashCh;
			$detail .="<tr><td align='left'>".$pTransactionName."</td><td>".FormatCurrency($pcash)."</td><td>".FormatCurrency($pCheque)."</td><td>".FormatCurrency($tCashCh)."</td></tr>";
		}
		$detail .= "<tr><td align='left'><b>Total</b></td><td><b>".FormatCurrency($tCash)."</b></td><td><b>".FormatCurrency($tCh)."</b></td><td><b>".FormatCurrency($ttCaCh)."</b></td></tr></table></div>";		
	}
	$mydb->sql_freeresult($que1);
				
print $detail;
				?>
				</div>				
				</td>
				<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
		<div id="d1-1">
		</div>
 		</td>
 	</tr>
	<tr><td>&nbsp;</td></tr>						
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>Transfer transaction</b></td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<div id="d2">
						<?php
								$sql = "select d.DrawerID, convert(varchar, d.PaymentDate, 112) as 'Date', d.Cashier, 
																Count(d.PaymentID) as 'NoTran', Sum(d.PaymentAmount) as 'Amount', p.ServiceID
													from tblCustCashDrawer d, tblCustProduct a, tblTarPackage p, tlkpTransaction t
													where d.AcctID = a.AccID
														and a.PackageID = p.PackageID
														and d.TransactionModeID = t.TransactionID
														and d.DrawerID <> 1
														and d.IsSubmitted = 0 AND t.TranGroupID = 2
														and (d.IsRollBack = 0 or d.IsRollBack is NULL) ";
								if((isset($st1)) && (!empty($st1))){
									$sql .= " and convert(varchar, d.PaymentDate, 112) = ".FormatDate($st1, 4)." ";
								} 
								//if((isset($sid1)) && (!empty($sid1))){
								if($sid1 == 2){
									$sql .= " and p.ServiceID = 2 ";
								}elseif($sid1 == 4){
									$sql .= " and p.ServiceID = 4 ";
								}elseif($sid1 == 5){	
									$sql .= " and p.ServiceID in(1, 3, 8) ";
								}	
								$sql .= "	group by d.DrawerID, convert(varchar, d.PaymentDate, 112), d.Cashier, p.ServiceID";
												
				$retOut = '<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
										<thead>																	
											<th align="center">No</th>								
											<th align="center">Date</th>
											<th align="center">Cashier</th>									
											<th align="center">No of Tran</th>																			
											<th align="center">Amount</th>																												
										</thead>
										<tbody>';
									if($que = $mydb->sql_query($sql)){
										$totalAmount = 0.00;
										$iLoop = 0;
										while($result = $mydb->sql_fetchrow($que)){
											$DrawerID = $result['DrawerID'];
											$Date = $result['Date'];
											$Cashier = $result['Cashier'];
											$NoTran = $result['NoTran'];
											$Amount = $result['Amount'];
											$Cash = "<a href=\"javascript:showlevel2('d2-1', 2, ".$DrawerID.", '".$Date."');\">".$Cashier."</a>";
											$download = ' [<a href="./export/payment_detail.php?did='.$DrawerID.'&cashier='.$Cashier.'&t=2&serviceid='.$sid1.'&type=csv">Download detail</a>]';
											$totalAmount += floatval($Amount);
											$iLoop ++;
											if(($iLoop % 2) == 0)											
												$style = "row1";
											else
												$style = "row2";
											$retOut .= '<tr>
																		<td align="left" class="'.$style.'">'.$iLoop.'</td>
																		<td align="left" class="'.$style.'">'.FormatDate($Date, 3).'</td>
																		<td align="left" class="'.$style.'">'.$Cash.'</td>
																		<td align="left" class="'.$style.'">'.$NoTran.$download.'</td>
																		<td align="right" class="'.$style.'">'.FormatCurrency($Amount).'</td>	
																	</tr>	
																	';																					
										} 
										$retOut .= '</tbody>
																	<tfoot>
																		<tr>
																			<td align="right" colspan="4">Total</td>
																			<td align="right">'.FormatCurrency($totalAmount).'</td>
																		</tr>
																	</tfoot>
																	';
									}
								$retOut .= '</table>';
								
							print $retOut;
							
							?>
					</div>					
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
	<br />
		<div id="d2-1">
	</div>
 		</td>
 	</tr>
	<tr><td>&nbsp;</td></tr>						
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>Refund transaction</b></td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<div id="d3">
							<?php
								$sql = "select d.DrawerID, convert(varchar, d.PaymentDate, 112) as 'Date', d.Cashier, 
																Count(d.PaymentID) as 'NoTran', Sum(d.PaymentAmount) as 'Amount', p.ServiceID
													from tblCustCashDrawer d, tblCustProduct a, tblTarPackage p, tlkpTransaction t
													where d.AcctID = a.AccID
														and a.PackageID = p.PackageID
														and d.DrawerID <> 1
														and d.TransactionModeID = t.TransactionID
														and d.IsSubmitted = 0 AND t.TranGroupID = 3
														and (d.IsRollBack = 0 or d.IsRollBack is NULL) ";
								if((isset($st1)) && (!empty($st1))){
									$sql .= " and convert(varchar, d.PaymentDate, 112) = ".FormatDate($st1, 4)." ";
								} 
								//if((isset($sid1)) && (!empty($sid1))){
								if($sid1 == 2){
									$sql .= " and p.ServiceID = 2 ";
								}elseif($sid1 == 4){
									$sql .= " and p.ServiceID = 4 ";
								}elseif($sid1 == 5){	
									$sql .= " and p.ServiceID in(1, 3, 8) ";
								}	
								$sql .= "	group by d.DrawerID, convert(varchar, d.PaymentDate, 112), d.Cashier, p.ServiceID";
												
				$retOut = '<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
										<thead>																	
											<th align="center">No</th>								
											<th align="center">Date</th>
											<th align="center">Cashier</th>									
											<th align="center">No of Tran</th>																			
											<th align="center">Amount</th>																												
										</thead>
										<tbody>';
									if($que = $mydb->sql_query($sql)){
										$totalAmount = 0.00;
										$iLoop = 0;
										while($result = $mydb->sql_fetchrow($que)){
											$DrawerID = $result['DrawerID'];
											$Date = $result['Date'];
											$Cashier = $result['Cashier'];
											$NoTran = $result['NoTran'];
											$Amount = $result['Amount'];
											$ServiceID = $result['ServiceID'];
											$Cash = "<a href=\"javascript:showlevel2('d2-1', 2, ".$DrawerID.", '".$Date."', ".$ServiceID.");\">".$Cashier."</a>";
											$download = ' [<a href="./export/payment_detail.php?did='.$DrawerID.'&cashier='.$Cashier.'&t=2&serviceid='.$sid1.'&type=csv">Download detail</a>]';
											$totalAmount += floatval($Amount);
											$iLoop ++;
											if(($iLoop % 2) == 0)											
												$style = "row1";
											else
												$style = "row2";
											$retOut .= '<tr>
																		<td align="left" class="'.$style.'">'.$iLoop.'</td>
																		<td align="left" class="'.$style.'">'.FormatDate($Date, 3).'</td>
																		<td align="left" class="'.$style.'">'.$Cash.'</td>
																		<td align="left" class="'.$style.'">'.$NoTran.$download.'</td>
																		<td align="right" class="'.$style.'">'.FormatCurrency($Amount).'</td>	
																	</tr>	
																	';																					
										} 
										$retOut .= '</tbody>
																	<tfoot>
																		<tr>
																			<td align="right" colspan="4">Total</td>
																			<td align="right">'.FormatCurrency($totalAmount).'</td>
																		</tr>
																	</tfoot>
																	';
									}
								$retOut .= '</table>';
								
							print $retOut;
							?>
					</div>					
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
	<br />
		<div id="d3-1">
	</div>
 		</td>
 	</tr>
</table>