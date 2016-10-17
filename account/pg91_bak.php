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
	
	$sql = "select  a.CustID, a.UserName, a.StartBillingDate, a.NextBillingDate, a.SetupDate,
								a.StatusID, p.TarName, s.ServiceID, s.ServiceName, a.SubscriptionName, a.NoBillRun, a.Score, a.BillingEmail, st.StationName,
								b.Credit, b.OutStanding, d.NationalDeposit, d.InternationDeposit, d.MonthlyDeposit, d.UnNationalDeposit,
								d.UnInternationDeposit, d.UnMonthlyDeposit
						from tblCustProduct a, tblTarPackage p, tlkpService s, tlkpStation st, tblAccountBalance b, tblAccDeposit d
						where a.PackageID = p.PackageID
							and p.ServiceID = s.ServiceID
							and a.StationID = st.StationID
							and a.AccID = b.AccID
							and a.AccID = d.AccID
							and a.AccID='".$AccountID."'";

		if($que = $mydb->sql_query($sql)){		
			if($result = $mydb->sql_fetchrow($que)){				
				$CustomerID = $result['CustID'];
				$AccountName = $result['UserName'];
				$StartBillingDate = $result['StartBillingDate'];
				$NextBillingDate = $result['NextBillingDate'];
				$SetupDate = $result['SetupDate'];
				$StatusID = $result['StatusID'];
				$TarName = $result['TarName'];
				$ServiceID = $result['ServiceID'];
				$ServiceName = $result['ServiceName'];
				$SubscriptionName = $result['SubscriptionName'];
				$NoBillRun = $result['NoBillRun'];
				$Score = $result['Score'];
				$StationName = $result['StationName'];
				$Credit = $result['Credit'];
				$OutStanding = $result['OutStanding'];
				$NationalDeposit = $result['NationalDeposit'];
				$InternationDeposit = $result['InternationDeposit'];
				$MonthlyDeposit = $result['MonthlyDeposit'];
				$UnNationalDeposit = $result['UnNationalDeposit'];
				$UnInternationDeposit = $result['UnInternationDeposit'];
				$UnMonthlyDeposit = $result['UnMonthlyDeposit'];
				$BillingEmail = $result['BillingEmail'];

				switch($StatusID){
					case 0: #inactive
						$stbg = $bgUnactivate;
						$stfg = $foreUnactivate;
						$stwd = "Inactive";						
						break;
					case 1: #inactive
						$stbg = $bgActivate;
						$stfg = $foreActivate;
						$stwd = "Active";
						break;
					case 2: #Bar				
						$stbg = $bgLock;
						$stfg = $foreLock;
						$stwd = "Barred";
						break;
					case 3: #Close				
						$stbg = $bgClose;
						$stfg = $foreClose;
						$stwd = "Closed";		
						break;
					case 4: #Close				
						$stbg = $bgClose;
						$stfg = $foreClose;
						$stwd = "Closed";		
						break;
				}
			}
		}
	
?>
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="JavaScript" type="text/javascript">
	function changeImage(imgCode, imgSource){
		//alert(imgSource);
			 if(imgCode == 1)
				document.customer.src = imgSource;
		else if(imgCode ==2)
				document.product.src = imgSource;
		else if(imgCode ==3)
				document.finance.src = imgSource;
		else if(imgCode ==4)
				document.usage.src = imgSource;
		else if(imgCode ==5)
				document.audit.src = imgSource;	
	}
	
	function doPrint(InvoiceID, CustomerID){
		filename = "printinvoice.php?CustomerID="+CustomerID+"&InvoiceID="+InvoiceID;
		var docprint=window.open(filename);
		//window.print();
		//docprint.close();		
		docprint.focus();
	}
</script>
<?php
		
	# =============== Get customer header =====================
		
		$sql = "select c.CustName, sum(d.NationalDeposit) as ncDeposit, sum(d.InternationDeposit) as 'icDeposit',
									sum(d.MonthlyDeposit) as mfDeposit, sum(b.Credit) as Credit, sum(b.Outstanding) as Outstanding 
						from tblCustomer c, tblCustProduct a, tblAccountBalance b, tblAccDeposit d
						where c.CustID = a.CustID and a.AccID = b.AccID and a.AccID = d.AccID and c.CustID=$CustomerID
						group by c.CustName";

		if($que = $mydb->sql_query($sql)){
			if($rst = $mydb->sql_fetchrow($que)){
				$CustName = $rst['CustName'];
				$ncDeposit = $rst['ncDeposit'];
				$icDeposit = $rst['icDeposit'];
				$mfDeposit = $rst['mfDeposit'];
				$Deposit = $ncDeposit + $icDeposit + $mfDeposit;
				$Deposit = FormatCurrency($Deposit);
				$Credit = FormatCurrency($rst['Credit']);
				$Outstanding = FormatCurrency($rst['Outstanding']);
			}
		}
		
		$mydb->sql_freeresult();
						
?>
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bordercolor="#aaaaaa" style="border-collapse:collapse">
		<tr>
			<td valign="top"  height="50">
					<table border="0" cellpadding="4" cellspacing="0" width="100%">
						<tr>
							<td align="left">ID: <b><?php print $CustomerID ?></b></td>						
							<td align="left" colspan="2">Name:<b><?php print $CustName ?></b></td>						
						</tr>
						<tr>
							<td align="left">Deposit: <b><?php print $Deposit; ?></b></td>						
							<td align="left">Balance: <b><?php print $Credit; ?></b></td>						
							<td align="left">Invoice: <b><?php print $Outstanding; ?></b></td>
						</tr>
					</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<!-- Individual customer tab menu -->
				<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" bordercolor="#ffffff" align="center">
					 <tr>
						<td align="left" width="15" background="./images/tab_null.gif">&nbsp;</td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID;?>&pg=10"><img src="./images/tab/customer.gif" name="customer" border="0" id="customer" onMouseOver="changeImage(1, './images/tab/customer_over.gif');" onMouseOut="changeImage(1, './images/tab/customer.gif');"/></a></td>						
						
						<td align="left" width="85"><img src="./images/tab/product_active.gif" name="product" border="0" id="product" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=41"><img src="./images/tab/finance.gif" name="finance" border="0" id="finance" onMouseOver="changeImage(3, './images/tab/finance_over.gif');" onMouseOut="changeImage(3, './images/tab/finance.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=70"><img src="./images/tab/usage.gif" name="usage" border="0" id="usage" onMouseOver="changeImage(4, './images/tab/usage_over.gif');" onMouseOut="changeImage(4, './images/tab/usage.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=30"><img src="./images/tab/audit.gif" name="audit" border="0" id="audit" onMouseOver="changeImage(5, './images/tab/audit_over.gif');" onMouseOut="changeImage(5, './images/tab/audit.gif');" /></a></td>						
						
						<td align="center" width="*" background="./images/tab_null.gif">&nbsp;</td>		
					</tr>
				</table>
					<!-- end customer table menu -->			
			</td>
		</tr>
		<tr>
			<td height="100%" valign="top">
					<!-- Individual customer main page -->				
					<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="180" rowspan="2">
			<?php include("content.php"); ?>
		</td>
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
									 	<td align="left" class="formtitle"><b>PRODUCT DETAIL
										- <?php print $aSubscriptionName." (".$aUserName.")"; ?></b>	
										</td>
										<td align="right">
											<?php 
												if(intval($StatusID) <= 2)
													print '<a href="./?CustomerID='.$CustomerID.'&AccountID='.$AccountID.'&md2&cst='.$StatusID.'&pg=101">Change status</a>';
											?>
											
										</td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">												
												<tr>
													<td align="left" nowrap="nowrap">Account ID:</td>
													<td align="left"><b><?php print $AccountID;?></b></td>
													<td align="left" nowrap="nowrap">Account name:</td>
													<td align="left" ><b><?php print $AccountName; ?></b></td>
												</tr>
												<tr>
													<td align="left">Status:</td>
													<td align="center" bgcolor="<?php print $stbg;?>">
														<font color="<?php print $stfg; ?>"><b><?php print $stwd; ?></b></font>
													</td>
													<td align="left" nowrap="nowrap">Subscription name:</td>
													<td align="left"><b><?php print $SubscriptionName;?></b></td>																										
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">Start bill date:</td>
													<td align="left"><b><?php print formatDate($StartBillingDate, 6);?></b></td>
													<td align="left">Next bill date:</td>
													<td align="left"><b><?php print formatDate($NextBillingDate, 6); ?></b></td>
												</tr>												
												<tr>
													<td align="left" nowrap="nowrap">Package:</td>
													<td align="left"><b><?php print $TarName;?></b></td>
													<td align="left">Service:</td>
													<td align="left"><b><?php print $ServiceName; ?></b></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">No. bill run:</td>
													<td align="left"><b><?php print $NoBillRun;?></b></td>
													<td align="left">Billing Email :</td>
													<td align="left"><b><?php print $BillingEmail; ?></b></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">Station:</td>
													<td align="left"><b><?php print $StationName;?></b></td>
													<td align="left">Setup date:</td>
													<td align="left"><b><?php print formatDate($SetupDate, 6); ?></b></td>
												</tr>	
												<tr>
													<td align="left" nowrap="nowrap">Balance:</td>
													<td align="left"><b><?php print FormatCurrency($Credit);?></b></td>
													<td align="left">Open invoice amount:</td>
													<td align="left"><b><?php print FormatCurrency($OutStanding); ?></b></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">NC deposit:</td>
													<td align="left"><b><?php print FormatCurrency($NationalDeposit);?></b></td>
													<td align="left">Unpaid NC deposit:</td>
													<td align="left"><font color="red"><b><?php print FormatCurrency($UnNationalDeposit); ?></b></font></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">IC deposit:</td>
													<td align="left"><b><?php print FormatCurrency($InternationDeposit);?></b></td>
													<td align="left">Unpaid IC deposit:</td>
													<td align="left"><font color="red"><b><?php print FormatCurrency($UnInternationDeposit); ?></b></font></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">MF deposit:</td>
													<td align="left"><b><?php print FormatCurrency($MonthlyDeposit);?></b></td>
													<td align="left">Unpaid MF deposit:</td>
													<td align="left"><font color="red"><b><?php print FormatCurrency($UnMonthlyDeposit); ?></b></font></td>
												</tr>								
											</table>
										</td>
									</tr>
									<?php
										if($ServiceID == 2){
											$sql = "SELECT Incoming, Outgoing, International, IncomingLoc, IncomingNat, OutgoingLoc, OutgoingNat, Other
														  FROM tblProductStatus WHERE AccID=".$AccountID;
															
											if($que = $mydb->sql_query($sql)){
												$result = $mydb->sql_fetchrow($que);
												$Incoming = $result['Incoming'];								
								if(intval($Incoming) == 1)
									$stIncoming = "<font color=blue><b>Active</b></font>";
								else
									$stIncoming = "<font color=red><b>Barred</b></font>";
								$Outgoing = $result['Outgoing'];
								if(intval($Outgoing) == 1)
									$stOutgoing = "<font color=blue><b>Active</b></font>";
								else
									$stOutgoing = "<font color=red><b>Barred</b></font>";
								$International = $result['International'];
								if(intval($International) == 1)
									$stInternational = "<font color=blue><b>Active</b></font>";
								else
									$stInternational = "<font color=red><b>Barred</b></font>";
									
								$IncomingLoc = $result['IncomingLoc'];
								if(intval($IncomingLoc) == 1)
									$stIncomingLoc = "<font color=blue><b>Active</b></font>";
								else
									$stIncomingLoc = "<font color=red><b>Barred</b></font>";
									
								$IncomingNat = $result['IncomingNat'];
								if(intval($IncomingNat) == 1)
									$stIncomingNat = "<font color=blue><b>Active</b></font>";
								else
									$stIncomingNat = "<font color=red><b>Barred</b></font>";
									
								$OutgoingNat = $result['OutgoingNat'];
								if(intval($OutgoingNat) == 1)
									$stOutgoingNat = "<font color=blue><b>Active</b></font>";
								else
									$stOutgoingNat = "<font color=red><b>Barred</b></font>";
								
								$OutgoingLoc = $result['OutgoingLoc'];
								if(intval($OutgoingLoc) == 1)
									$stOutgoingLoc = "<font color=blue><b>Active</b></font>";
								else
									$stOutgoingLoc = "<font color=red><b>Barred</b></font>";
								
								$Other = $result['Other'];
								if(intval($Other) == 1)
									$stOther = "<font color=blue><b>Active</b></font>";
								else
									$stOther = "<font color=red><b>Barred</b></font>";
													
												print '<tr>
																<td align=left colspan=2>
																	<table border="1" cellpadding="4" cellspacing="0" width = "50%" height="100%" class="formbody" bordercolor="#aaaaaa">
																		<tr>
																			<td align="left">Incoming call</td>
																			<td align="center">'.$stIncoming.'</td>
																		</tr>
																		<tr>
																			<td align="left">Incoming cam loc</td>
																			<td align="center">'.$stIncomingLoc.'</td>
																		</tr>
																		<tr>
																			<td align="left">Incoming cam nat</td>
																			<td align="center">'.$stIncomingNat.'</td>
																		</tr>
																		<tr>
																			<td align="left">Outgoing call</td>
																			<td align="center">'.$stOutgoing.'</td>
																		</tr>
																		<tr>
																			<td align="left">Outgoing cam loc</td>
																			<td align="center">'.$stOutgoingLoc.'</td>
																		</tr>
																		<tr>
																			<td align="left">Outgoing cam nat</td>
																			<td align="center">'.$stOutgoingNat.'</td>
																		</tr>
																		<tr>
																			<td align="left">International call</td>
																			<td align="center">'.$stInternational.'</td>
																		</tr>
																		<tr>
																			<td align="left">Other</td>
																			<td align="center">'.$stOther.'</td>
																		</tr>
																	</table>
																</td>	
															</tr>
															';
											}
										}
									?>														
								</table>						
							</td>
						</tr>	
											
					</table>
				</td>
			</tr>
			
</table>
<?php
# Close connection
$mydb->sql_close();
?>
