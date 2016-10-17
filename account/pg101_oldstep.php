<?php
	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");
	require_once("./common/class.invoice.php");	
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
			
?>
<script language="javascript" type="text/javascript" src="./javascript/date.js"></script>
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
	
	function doCommand(cmd){
		dt = document.getElementById("changeon");
		if(cmd ==0)
			dt.style.display = "none";
		else
			dt.style.display = "block";
	}
	function submitForm(){
		if(document.getElementById("changeon").style.display == "block"){
			if(fChangeStatus.txtChangeOn.value == ""){
				alert("Please enter effective date.");
				fChangeStatus.txtChangeOn.focus();
				return; 
			}
		}
		fChangeStatus.btnSubmit.disabled = true;
		fChangeStatus.submit();
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
						
	if(!empty($smt) && isset($smt) && ($smt == "save101")){
		$Audit = new Audit();	
		
		$cmd = FixQuotes($cmd);
		$txtChangeOn = FixQuotes($txtChangeOn);
		$comment = FixQuotes($comment);
		$cst = FixQuotes($cst);
		$AccountID = FixQuotes($AccountID);
		$CustomerID = FixQuotes($CustomerID);
		$supid = FixQuotes($supid);
		$international = FixQuotes($international);
		if((empty($international)) || (is_null($international)) || ($international == "")) $international = 0;
		
		$incoming = FixQuotes($incoming);
		if((empty($incoming)) || (is_null($incoming)) || ($incoming == "")) $incoming = 0;
		
		$incomingloc = FixQuotes($incomingloc);
		if((empty($incomingloc)) || (is_null($incomingloc)) || ($incomingloc == "")) $incomingloc = 0;
		
		$incomingnat = FixQuotes($incomingnat);
		if((empty($incomingnat)) || (is_null($incomingnat)) || ($incomingnat == "")) $incomingnat = 0;
		
		$outgoing = FixQuotes($outgoing);
		if((empty($outgoing)) || (is_null($outgoing)) || ($outgoing == "")) $outgoing = 0;
		
		$outgoingloc = FixQuotes($outgoingloc);
		if((empty($outgoingloc)) || (is_null($outgoingloc)) || ($outgoingloc == "")) $outgoingloc = 0;
		
		$outgoingnat = FixQuotes($outgoingnat);
		if((empty($outgoingnat)) || (is_null($outgoingnat)) || ($outgoingnat == "")) $outgoingnat = 0;
		
		$other = FixQuotes($other);
		if((empty($other)) || (is_null($other)) || ($other == "")) $other = 0;
		
		
		//$today = date("Y-M-d H:i:s",strtotime("-1 hours"));
		//$today = date("Y-m-d h:m:s");
		
		$today = date("Y-m-d h:m:s");
		
		
		
		if(is_null($txtChangeOn) || (empty($txtChangeOn))){
			$txtChangeOn = $today;
		} 

		if(datediff($txtChangeOn, $today) > 0){
			$retOut = $myinfo->warning("Effective date must be greater than today date");
		}else{
			# get serviceid 
			$sql = "SELECT t.ServiceID 
							FROM tblCustProduct a, tblTarPackage t
							WHERE a.PackageID = t.PackageID AND a.AccID = $AccountID";
			if($que = $mydb->sql_query($sql)){
				$result = $mydb->sql_fetchrow($que);
				$ServiceID = $result["ServiceID"];
			}
			$mydb->sql_freeresult($que);
			
			if($ServiceID == 4){
				$IsConfirm = 1;
				$OpDone = 1;
				$DoneDate = $today;
				$OPDoneDate = $today;
			}else{
			}
			
			
			# update panding account
			$sql = "UPDATE tblJobAcctStatus Set 
											IsDone = 1, 
											IsConfirm = 1,
											OpDone = 1 
							WHERE AccID = $AccountID and IsDone = 0";
			if($mydb->sql_query($sql)){
				if($ServiceID == 4){
					$sql = "INSERT INTO tblJobAcctStatus(AccID, CurrentStatusID, NewStatusID, SubmitDate, EffectiveDate, 
														Comment, IsDone, IsConfirm, Incoming, Outgoing, International, IncomingLoc, IncomingNat,
														OutgoingLoc, OutgoingNat, Other, OpDone, DoneDate, OPDoneDate, MDFDone, MDFDoneDate, MDFComment)
									VALUES($AccountID, $cst, $cmd, '".$today."', '".formatDate($txtChangeOn, 8)."', 
														'".$comment."', 0, 1, ".$incoming.", ".$outgoing.", ".$international.",
															".$incomingloc.", ".$incomingnat.", ".$outgoingloc.", ".$outgoingnat.", ".$other.", 1, 
															'".$today."', '".$today."', 1, '".$today."', 'AUTO DONE')";
				}elseif($ServiceID == 2){
					#Telephone
					$sql = "INSERT INTO tblJobAcctStatus(AccID, CurrentStatusID, NewStatusID, SubmitDate, EffectiveDate, 
														Comment, IsDone, IsConfirm, Incoming, Outgoing, International, IncomingLoc, IncomingNat,
														OutgoingLoc, OutgoingNat, Other, OpDone, MDFDone)
									VALUES($AccountID, $cst, $cmd, '".$today."', '".formatDate($txtChangeOn, 8)."', 
														'".$comment."', 0, 0, ".$incoming.", ".$outgoing.", ".$international.",
															".$incomingloc.", ".$incomingnat.", ".$outgoingloc.", ".$outgoingnat.", ".$other.", 0, 0)";
				}else{
					#ISP Billing
					$sql = "INSERT INTO tblJobAcctStatus(AccID, CurrentStatusID, NewStatusID, SubmitDate, EffectiveDate, 
														Comment, IsDone, IsConfirm, Incoming, Outgoing, International, IncomingLoc, IncomingNat,
														OutgoingLoc, OutgoingNat, Other, ISPDone, MDFDone, OpDone)
									VALUES($AccountID, $cst, $cmd, '".$today."', '".formatDate($txtChangeOn, 8)."', 
														'".$comment."', 0, 1, ".$incoming.", ".$outgoing.", ".$international.",
															".$incomingloc.", ".$incomingnat.", ".$outgoingloc.", ".$outgoingnat.", ".$other.", 0, 0, 0)";
				}
				if($que = $mydb->sql_query($sql)){					
					$comment = $sttext." on $txtChangeOn. $comment";
					$Audit->AddAudit($CustomerID, $AccountID, "Change account status", $comment, $user['FullName'], 1, 6);
					$retOut = $myinfo->info("Successfully resquest to adminstrator to change account status.");
				}else{
					$error = $mydb->sql_error();				
					$retOut = $myinfo->error("Failed to change account status.", $error['message'].$sql.$sttext);
				}
			}else{
				$error = $mydb->sql_error();				
				$retOut = $myinfo->error("Failed to update panding account status.", $error['message']);
			}
		}
				
	}
	
	$date = "<div id=\"changeon\" style=\"display:none\" class=\"text\">
							Enter date: <input type=\"text\" name=\"txtChangeOn\" value=\"\" class=\"boxenabled\" onKeyUp=\"DateFormat(this,this.value,event,false,'2')\" onBlur=\"DateFormat(this,this.value,event,true,'2')\" size=\"15\" />
							<a href=\"javascript:void( window.open('./javascript/calendar.html?fChangeStatus|txtChangeOn', '', 'width=200,height=220,top=250,left=350'))\"><img src='./images/b_calendar.png' alt=\"View Calendar\" align=\"middle\" border=\"0\"></a>
							(YYYY-MM-DD HH:MM:SS)
						</div>";	
 //switch($md){
// 	case 1:
//		$title = "ACTIVATE ACCOUNT";
		if($cst == 0){
			$radio = '
									<input type="radio" name="cmd" value="1" onClick="doCommand(0);" tabindex="1" checked="checked"> Activate now<br>
									<input type="radio" name="cmd" value="1" onClick="doCommand(1);" tabindex="2"> Activate on<br>
								';
		}elseif(($cst == 1) || ($cst == 2)){
			$radio = '
									<input type="radio" name="cmd" value="5" onClick="doCommand(0);" tabindex="1" checked="checked"> Unbar now<br>
									<input type="radio" name="cmd" value="5" onClick="doCommand(1);" tabindex="2"> Unbar on<br>	
									<input type="radio" name="cmd" value="2" onClick="doCommand(0);" tabindex="3" checked="checked"> Bar now<br>
									<input type="radio" name="cmd" value="2" onClick="doCommand(1);" tabindex="4"> Bar on<br>	
									<input type="radio" name="cmd" value="3" onClick="doCommand(0);" tabindex="5" checked="checked"> Close now<br>
									<input type="radio" name="cmd" value="3" onClick="doCommand(1);" tabindex="6"> Close on<br>						
							 ';
		}else{
			$disabled = "disabled = 'disabled'";
		}
		//break;
//	case 2:		
//		$title = "BAR ACCOUNT";
//		$radio = '
//								<input type="radio" name="cmd" value="2" onClick="doCommand(0);" tabindex="1" checked="checked"> Bar now<br>
//								<input type="radio" name="cmd" value="2" onClick="doCommand(1);" tabindex="2"> Bar on<br>								
//						 ';
//		break;
//	case 3:		
//		$title = "CLOSE ACCOUNT";
//		$radio = '
//								<input type="radio" name="cmd" value="3" onClick="doCommand(0);" tabindex="1" checked="checked"> Close now<br>
//								<input type="radio" name="cmd" value="3" onClick="doCommand(1);" tabindex="2"> Close on<br>								
//						 ';
//		break;	
// }
 $radio .= $date;				
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
							<td valign="top" width="180">
								<?php include("content.php"); ?>
							</td>
							<td valign="top" align="left">
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="60%">
									<tr>
										<td align="left" class="formtitle"><b><?php print $title; ?>
										- <?php print $aSubscriptionName." (".$aUserName.")"; ?></b>
										</td>
										<td align="right">&nbsp;
											
										</td>
									</tr> 				
									<tr>
										<td colspan="2">
											<form name="fChangeStatus" method="post" action="./" onSubmit="return false;">
												<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" bgcolor="#feeac2">
													<tr>
														<td align="left" nowrap="nowrap" valign="top">
															<fieldset>
																<legend align="center">Status</legend>
															<?php 
																print $radio;
															?>
															</fieldset>
														</td>
														<td align="left" valign="top" nowrap="nowrap">
															<fieldset>
																<legend align="center">Supplementary</legend>
																	<input type="checkbox" name="incoming" value="1" />Incoming ALL<br />
																	<input type="checkbox" name="incomingloc" value="1" />Incoming Camintel LOC<br />
																	<input type="checkbox" name="incomingnat" value="1" />Incoming Camintel NAT<br />
																	<input type="checkbox" name="outgoing" value="1" />Outgoing ALL<br />
																	<input type="checkbox" name="outgoingloc" value="1" />Outgoing Camintel LOC<br />
																	<input type="checkbox" name="outgoingnat" value="1" />Outgoing Camintel NAT<br />
																	<input type="checkbox" name="international" value="1" />International call	</br>																
																	<input type="checkbox" name="other" value="1" />Other
															</fieldset>
														</td>									
													</tr>
													<tr>
														<td align="left" valign="top" colspan="2" nowrap="nowrap">Comment:
															<textarea name="comment" cols="35" rows="5" class="boxenabled" tabindex="4"></textarea>
														</td>
													</tr>																
													<tr><td colspan="2">&nbsp;</td></tr>								
													<tr> 				  
													<td align="center" colspan="2">
														<input type="reset" tabindex="8" name="reset" value="Reset" class="button" />
														<input type="submit" tabindex="9" name="btnSubmit" value="Submit" class="button" onClick="submitForm();" />						
													</td>
												 </tr>
												 <?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
														}
													?>
												
												</table>
											<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
											<input type="hidden" name="AccountID" value="<?php print $AccountID; ?>" />
											<input type="hidden" name="md" value="<?php print $md; ?>" />
											<input type="hidden" name="cst" value="<?php print $cst; ?>" />
											<input type="hidden" name="pg" value="101" />
											<input type="hidden" name="smt" value="save101" />
											</form>	
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
