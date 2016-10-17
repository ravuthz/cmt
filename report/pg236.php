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
	$drawerID = GetDrawerID($user['userid']);
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
	
	function download(did, type, cashier, serviceid){
		
		url = "./export/payment_detail.php?did="+did+"&t="+type+"&cashier="+cashier+"&serviceid="+serviceid;
		//alert(url);
		window.open(url);
	}
</script>

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<form name="f1" method="post" action="./" >
					<td align="left" class="formtitle"><b>Payment transaction</b>
						<select name="sid1" onchange="f1.submit();">
							<option value="0" <?php if(intval($sid1) == 0) print "selected";?>>All services</option>
							<option value="2" <?php if(intval($sid1) == 2) print "selected";?>>Telephone</option>
							<option value="4" <?php if(intval($sid1) == 4) print "selected";?>>Leased line</option>
							<option value="5" <?php if(intval($sid1) == 5) print "selected";?>>ISP</option>
						</select>
						<input type="text" tabindex="3" name="st" class="boxenabled" size="27" maxlength="30" value="<?php print $st; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
						<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?f1|st', '', 'width=200,height=220,top=250,left=350');">
								<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
					  </button>		
							<button name="imgGo" onClick="f1.submit();" class="invisibleButtons">
							<img src="./images/go1.gif" border="0" alt="Search" class="invisibleButtons">
						</button>
						<a href="./administration/cashdrawerreport.php?DID=<?php echo $drawerID?>&close=0" target="_blank">Print Report</a>
					</td>					
					<td align="right">[<a href="#" onclick="download(<?php print $drawerID; ?>, 1, '<?php print $user['FullName']; ?>', f1.sid1.options[f1.sid1.selectedIndex].value)">Download all transaction</a>]</td>
				<input type="hidden" name="pg" value="236" />
					</form>
				</tr> 
				
				<tr>
					<td colspan="2">
						<div id="d1">
							<?php
								$sql = "select d.DrawerID, convert(varchar, d.PaymentDate, 112) as 'Date', d.Cashier, 
																Count(d.PaymentID) as 'NoTran', Sum(d.PaymentAmount) as 'Amount'
												from tblCustCashDrawer d(nolock), tlkpTransaction t(nolock), tblCustProduct a(nolock), tblTarPackage ta(nolock) 
												where d.TransactionModeID = t.TransactionID
													AND d.AcctID = a.AccID
													AND a.PackageID = ta.PackageID
													AND d.DrawerID <> 1
													AND t.TranGroupID = 1
													AND (d.IsRollBack = 0 or d.IsRollBack is NULL)	
													AND d.Cashier = '".$user['FullName']."' ";
								if($sid1 == 2){
									$sql .= " and ta.ServiceID = 2 ";
								}elseif($sid1 == 4){
									$sql .= " and ta.ServiceID = 4 ";
								}elseif($sid1 == 5){	
									$sql .= " and ta.ServiceID in(1, 3, 8) ";
								}
								if((isset($st)) && (!empty($st))){
									$sql .= " and convert(varchar, d.PaymentDate, 112) = ".FormatDate($st, 4)." ";
								}else{
									$sql .= " and d.IsSubmitted = 0 ";
								} 					
								$sql .= " group by d.DrawerID, convert(varchar, d.PaymentDate, 112), d.Cashier";
												
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
																		<td align="left" class="'.$style.'">'.$NoTran.'</td>
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
					<td align="right">[<a href="#" onclick="download(<?php print $drawerID; ?>, 3, '<?php print $user['FullName']; ?>', f1.sid1.options[f1.sid1.selectedIndex].value)">Download all transaction</a>]</td>
				</tr> 
				<tr>
					<td colspan="2">
						<div id="d2">
						<?php
								$sql = "select d.DrawerID, convert(varchar, d.PaymentDate, 112) as 'Date', d.Cashier, 
																Count(d.PaymentID) as 'NoTran', Sum(d.PaymentAmount) as 'Amount'
												from tblCustCashDrawer d, tlkpTransaction t, tblCustProduct a, tblTarPackage ta 
												where d.TransactionModeID = t.TransactionID
													AND d.AcctID = a.AccID
													AND a.PackageID = ta.PackageID
													AND d.DrawerID <> 1													 
													AND t.TranGroupID = 3
													AND (d.IsRollBack = 0 or d.IsRollBack is NULL)	
													AND d.Cashier = '".$user['FullName']."' ";
								if($sid1 == 2){
									$sql .= " and ta.ServiceID = 2 ";
								}elseif($sid1 == 4){
									$sql .= " and ta.ServiceID = 4 ";
								}elseif($sid1 == 5){	
									$sql .= " and ta.ServiceID in(1, 3, 8) ";
								}				
								if((isset($st)) && (!empty($st))){
									$sql .= " and convert(varchar, d.PaymentDate, 112) = ".FormatDate($st, 4)." ";
								}else{
									$sql .= " and d.IsSubmitted = 0 ";
								}	
								$sql .= " group by d.DrawerID, convert(varchar, d.PaymentDate, 112), d.Cashier ";
												
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
											$Cash = "<a href=\"javascript:showlevel2('d2-1', 3, ".$DrawerID.", '".$Date."', '".$sid1."');\">".$Cashier."</a>";
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
																		<td align="left" class="'.$style.'">'.$NoTran.'</td>
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
					<td align="right">[<a href="#" onclick="download(<?php print $drawerID; ?>, 2, '<?php print $user['FullName']; ?>', f1.sid1.options[f1.sid1.selectedIndex].value)">Download all transaction</a>]</td>
				</tr> 
				<tr>
					<td colspan="2">
						<div id="d3">
							<?php
								$sql = "select d.DrawerID, convert(varchar, d.PaymentDate, 112) as 'Date', d.Cashier, 
																Count(d.PaymentID) as 'NoTran', Sum(d.PaymentAmount) as 'Amount'
												from tblCustCashDrawer d, tlkpTransaction t, tblCustProduct a, tblTarPackage ta 
												where d.TransactionModeID = t.TransactionID
													AND d.AcctID = a.AccID
													AND a.PackageID = ta.PackageID
													AND d.DrawerID <> 1													
													AND t.TranGroupID = 2
													AND (d.IsRollBack = 0 or d.IsRollBack is NULL)	
													AND d.Cashier = '".$user['FullName']."' ";
								if($sid1 == 2){
									$sql .= " and ta.ServiceID = 2 ";
								}elseif($sid1 == 4){
									$sql .= " and ta.ServiceID = 4 ";
								}elseif($sid1 == 5){	
									$sql .= " and ta.ServiceID in(1, 3, 8) ";
								}			
								if((isset($st)) && (!empty($st))){
									$sql .= " and convert(varchar, d.PaymentDate, 112) = ".FormatDate($st, 4)." ";
								}else{
									$sql .= " and d.IsSubmitted = 0 ";
								}		
								$sql .= " group by d.DrawerID, convert(varchar, d.PaymentDate, 112), d.Cashier ";
												
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
											$Cash = "<a href=\"javascript:showlevel2('d3-1', 2, ".$DrawerID.", '".$Date."', '".$sid1."');\">".$Cashier."</a>";
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
																		<td align="left" class="'.$style.'">'.$NoTran.'</td>
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