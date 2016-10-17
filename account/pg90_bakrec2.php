<script language="javascript" src="./javascript/ajax_gettrack.js"></script>
<script language="javascript" src="./javascript/ajax_activateacc.js"></script>
<script language="javascript" src="./javascript/ajax_getAccountInfo.js"></script>
<script language="javascript" type="text/javascript">

	function get_Track(AccID,CustomerID){
		
		if(document.getElementById("div_"+AccID).style.display=="none")
		{
			getTrack("./php/ajax_gettrack.php?&CustomerID="+CustomerID+"&accID="+AccID+"&mt=" + new Date().getTime(),"div_"+AccID);
			document.getElementById("div_"+AccID).style.display = "block";
		}
		else
		{
			document.getElementById("div_"+AccID).style.display = "none";
		}
	}
	
	function doR(act,cID,aID,md,cst,UserName,ServiceID,sName,pID){
		
			if(act=="Reconnect")
			{	
				if(document.getElementById("dr_"+aID).style.display == "none")
				{
					getAcccInfo("./php/ajax_getAccountInfo.php?&CustID="+cID+"&AccountID="+aID+"&UserName="+UserName+"&ServiceID="+ServiceID+"&sName="+sName+"&pID="+pID+"&mt=" + new Date().getTime(),"dr_"+aID);
					document.getElementById("dr_"+aID).style.display = "block";
				}
				else
					document.getElementById("dr_"+aID).style.display = "none";
			}
			else
			{
				if(confirm("Are you sure to complete this "+act+"?"))
				{	
					activateAccount("./account/pgAccountAction.php?&CustomerID="+cID+"&AccountID="+aID+"&md="+md+"&cst="+cst+"&mt=" + new Date().getTime(),aID);
					alert("Your request was successfully sent.........");
					window.location = "./?pg=90&CustomerID="+cID+"&AccountID="+aID;			
				}
			}
	}
	
	function doRe(act,cID,aID,md,cst,UserName,PackageID,SubscriptionName){
		if(confirm("Are you sure to complete this "+act+"?"))
		{
	
			activateAccount("./account/pgAccountAction_re.php?&CustomerID="+cID+"&AccountID="+aID+"&md="+md+"&cst="+cst+"&UserName="+UserName+"&PackageID="+PackageID+"&sName="+SubscriptionName+"&mt=" + new Date().getTime(),UserName);
			alert("Your request was successfully sent.........");
			window.location = "./?pg=90&CustomerID="+cID+"&AccountID="+aID;		
		}
	}
	
</script>
<?php
	require_once("./common/agent.php");	
	require_once("./common/functions.php");

	function Account($AccountID, $SubscriptionName, $ServiceID, $UserName, $pID){
		global $mydb, $myinfo;
		global $bgUnactivate, $foreUnactivate, $bgActivate, $foreActivate, $bgLock, $foreLock, $bgClose, $foreClose;
		$stFuture = "No";
		$sqlstatus = "SELECT NewStatusID, EffectiveDate FROM tblJobAcctStatus WHERE AccID = $AccountID and IsDone = 0";

		if($questatus = $mydb->sql_query($sqlstatus)){
			if($resultstatus = $mydb->sql_fetchrow($questatus)){
				$nwestatus = $resultstatus['NewStatusID'];
				$EffectiveDate = $resultstatus['EffectiveDate'];
				switch($nwestatus){
					case 0:
						$stnewstatus = " inactived ";
						break;
					case 1:
						$stnewstatus = " activated ";
						break;
					case 2:
						$stnewstatus = " barred ";
						break;
					case 3:
						$stnewstatus = " closed ";
						break;
					case 4:
						$stnewstatus = " closed ";
						break;	
				} 
				$stFuture = "Will be $stnewstatus on ".formatDate($EffectiveDate, 6);
			}
		}
		
		$mydb->sql_freeresult($questatus);
		
		$AcountUI = "";
		$sql = "select  a.CustID, a.UserName, a.StartBillingDate, a.NextBillingDate, a.SetupDate,
								a.StatusID, p.TarName, s.ServiceID, s.ServiceName,a.BillingEmail,a.VATnumber,a.IsVatException
						from tblCustProduct a, tblTarPackage p, tlkpService s
						where a.PackageID = p.PackageID
							and p.ServiceID = s.ServiceID
							and a.AccID='".$AccountID."'";

		if($que = $mydb->sql_query($sql)){		
			if($result = $mydb->sql_fetchrow($que)){				
				$CustomerID = $result['CustID'];
				$AccountName = $result['UserName'];
				$StartBillingDate = $result['StartBillingDate'];
				$NextBillingDate = $result['NextBillingDate'];
				$BillingEmail = $result['BillingEmail'];
				$VATnumber = $result['VATnumber'];
				$IsVatException = $result['IsVatException'];
				$SetupDate = $result['SetupDate'];
				$StatusID = $result['StatusID'];
				$TarName = $result['TarName'];
				$ServiceID = $result['ServiceID'];
				$ServiceName = $result['ServiceName'];				
				$VATc = ($IsVatException == 0 ? "Yes" : "No");
				
				switch($StatusID){
					case 0: #inactive
						$stbg = $bgUnactivate;
						$stfg = $foreUnactivate;
						$stwd = "Inactive";
						$bl = "[<a href='#' onclick='javascript:doR(\"Activate\",".$CustomerID.",".$AccountID.",1,".$StatusID.",\"".$AccountName."\",".$ServiceID.",\"".$SubscriptionName."\",".$pID.");' >Activate</a>]";
						break;
					case 1: #active
						$stbg = $bgActivate;
						$stfg = $foreActivate;
						$stwd = "Active";						
						$bl = "[<a href='#' onclick='javascript:doR(\"Close\",".$CustomerID.",".$AccountID.",3,".$StatusID.",\"".$AccountName."\",".$ServiceID.",\"".$SubscriptionName."\",".$pID.");' >Close</a>]";
						break;
					case 2:	# Bar
						$stbg = $bgLock;
						$stfg = $foreLock;
						$stwd = "Barred";						
						$bl = "[<a href='#' onclick='javascript:doR(\"Activate\",".$CustomerID.",".$AccountID.",1,".$StatusID.",\"".$AccountName."\",".$ServiceID.",\"".$SubscriptionName."\",".$pID.");' >Activate</a>]";
						break;
					case 3:	# close
						$stbg = $bgClose;
						$stfg = $foreClose;
						$stwd = "Closed";												
						break;
						
					case 4:	# close
						$stbg = $bgClose;
						$stfg = $foreClose;
						$stwd = "Closed";
						$bl = "[<a href='#' onclick='javascript:doR(\"Reconnect\",".$CustomerID.",".$AccountID.",0,".$StatusID.",\"".$AccountName."\",".$ServiceID.",\"".$SubscriptionName."\",".$pID.");' >Rec & Change</a>]";												
						break;
				}
				//$linkAccount = "<a href='#' onclick='get_Track(".$AccountID.");'>".$AccountName."</a>";
				$linkAccount = "<a href='#' onclick='get_Track(".$AccountID.",".$CustomerID.");'>".$AccountName."</a>";
				# Get service interface
				$Service = "UI $ServiceName";
				$formTitleBG = getConfigue("$Service");
				
				$AcountUI= '
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
									 	<td align="left" class="formtitle" bgColor="'.$formTitleBG.'">Service type: <b>'.$ServiceName.'</b></td>
										<td align="right" class="formtitle" bgColor="'.$formTitleBG.'">Subscription Name: <b>'.$SubscriptionName.' [ AccID:'.$AccountID.']</b></td>										
									</tr>';
				
				//// =========================== Reconnect or Change ======================================
				
				
				$AcountUI.='
							                           
							<tr>
                            	<td colspan="2">
                                    <div id="dr_'.$AccountID.'" style="display:none;">
								  </div>
                                </td>
                            </tr>
							
						';
				
				
				
				//// =========================== Reconnect or Change ======================================
									
				
				
				
				
									
									
				$AcountUI.='					
									<tr>
										<td valign="top" colspan=2>
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">
												<tr>																									
													<td align="left" width="40%" nowrap="nowrap">'.$linkAccount.'      |   <b>'.$SubscriptionName.'</b></td>
													<td align="center" width="10%" bgColor="'.$stbg.'"><font color="'.$stfg.'"><b>'.$stwd.'</b></font></td>													
													<td align="center" width="100">'.$bl.'</td>
													<td align="left" width="33%"><b>'.$TarName.'</b></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap" colspan="4">
														Billing Email: <b> '
														.$BillingEmail														
														.' </b> | VAT number: <b> '
														.$VATnumber
														.' </b> | VAT Charge: <b> '
														.$VATc
														.' </b>
													</td>													
												</tr>
												<tr>
													<td align="left" nowrap="nowrap" colspan="4">
														Start bill date: <b> '
														.formatDate($StartBillingDate, 3)
														.' </b> | next bill date: <b> '
														.formatDate($NextBillingDate, 3)
														.' </b>
													</td>													
												</tr>
												';

						$sql = "select a.TrackID, a.UserName, a.StartBillingDate, a.NextBillingDate, a.Regby,a.Track, 
												a.StatusID, p.TarName,a.AccountEndDate
										from tblTrackAccount a, tblTarPackage p
										where a.PackageID = p.PackageID
											and a.AccID='".$AccountID."'";
				
						if($que = $mydb->sql_query($sql)){		
							if($result = $mydb->sql_fetchrow($que)){				
								$AccountName = $result['UserName'];
								$TrackID = $result['TrackID'];
								$StartBillingDate = $result['StartBillingDate'];
								$NextBillingDate = $result['NextBillingDate'];
								$Regby = $result['Regby'];
								$Track = $result['Track'];
							}
						}
						
						$AcountUI .= '			<tr>													
													<td align="left" colspan="4"><font color=red><b>Last action ===>> Track : '.$TrackID.' | Condition : '.$Track.' | Done by : '.$Regby.' </b></font></td>
												</tr>	
											</table>
										</td>
									</tr>
									<tr>
									 	<td align="left" colspan="2"><div id="div_'.$AccountID.'" style="display:none;"></div></td>			
									</tr>
								</table>
							';												
			}			
		}else{
			$error = $mydb->sql_error();
			$AcountUI = $myinfo->error("Failed to get account information.", $error['message']);
		}
		$mydb->sql_freeresult();
		return $AcountUI;
	}
?>
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript" src="./javascript/ajax_checkusername_re.js"></script>
<script language="javascript" src="./javascript/ajax_signup_getserviceID.js"></script>
<script language="JavaScript" type="text/javascript">


	function ValidAccountAcc(retDiv,AccID,ServiceID){
		
		phonepre = frmActive.elements["SelPhonePreset_"+AccID].options[frmActive.elements["SelPhonePreset_"+AccID].selectedIndex].value;
		phonenum = frmActive.elements["UserName_"+AccID].value;
		
		if(ServiceID==2)
		{
			username = phonepre + phonenum;
			if(username.length < 9){
				document.getElementById("Recon_"+AccID).disabled = true;
				document.getElementById("dUserName_"+AccID).innerHTML=" Invalied account name.";
				document.getElementById("dUserName_"+AccID).style.display = "inline";
				return false;
			}else{
				for (i = 0; i < username.length; i++){
					var c = username.charAt(i);
						if ((c < "0") || (c > "9")){
							document.getElementById("Recon_"+AccID).disabled = true;
							document.getElementById("dUserName_"+AccID).innerHTML=" Invalied account name.";
							document.getElementById("dUserName_"+AccID).style.display = "inline";
							return false;
						}
				}
				document.getElementById("Recon_"+AccID).disabled = false;
			}
		}
		else
		{
			username = phonenum;
			if(username.length == 0){
				document.getElementById("Recon_"+AccID).disabled = true;
				document.getElementById("dUserName_"+AccID).innerHTML=" Invalid account name.";
				document.getElementById("dUserName_"+AccID).style.display = "inline";
				return false;
			}else{
				document.getElementById("Recon_"+AccID).disabled = false;
			}
		}
		
		
		if(frmActive.elements["PackageID_"+AccID].selectedIndex < 1)
		{
				alert("Please select package..."); 
				frmActive.elements["PackageID_"+AccID].focus();
				return false;
		}
		
		suff = "";
		if(document.getElementById("sidd_"+AccID).value==1)
		{
			suff="@adsl";
		}
		else if(document.getElementById("sidd_"+AccID).value==3)
		{
			suff="@dialup";
		}
		else if(document.getElementById("sidd_"+AccID).value==8)
		{
			suff="@isdn";
		}
		else if(document.getElementById("sidd_"+AccID).value==4)
		{
			suff="@leaseline";
		}
		
		url = "./php/ajax_checkusername.php?username=" + username.toString().replace(suff,"")+suff +"&mt=" + new Date().getTime();
		checkUserNameAcc(url, retDiv, AccID);
	}
	
	function fn_getvalue(AccID)
	{
		if(frmActive.elements["PackageID_"+AccID].selectedIndex < 1)
		{
				alert("Please select package..."); 
				frmActive.elements["PackageID_"+AccID].focus();
				return false;
		}
		PackageID=frmActive.elements["PackageID_"+AccID].options[frmActive.elements["PackageID_"+AccID].selectedIndex].value;
		url2 = "./php/ajax_getServerID.php?PackageID=" + PackageID +"&mt=" + new Date().getTime();
		getServiceID(url2, document.getElementById("sidd_"+AccID));
		return true;
	}
	
	function validateForm(AccID,act,cID,aID,md,cst)
	{
		if(Trim(frmActive.elements["SubscriptionName_"+AccID].value) ==""){
			alert("Please enter subscription fee");
			frmActive.elements["SubscriptionName_"+AccID].focus();
			return;
		}else if(frmActive.elements["PackageID_"+AccID].selectedIndex < 1)
		{
				alert("Please select package..."); 
				frmActive.elements["PackageID_"+AccID].focus();
				return false;
		}else if(Trim(frmActive.elements["UserName_"+AccID].value) == ""){
			alert("Please enter account name");
			frmActive.elements["UserName_"+AccID].focus();
			return;
		}
		
		suff = "";
		preu = "";
		if(document.getElementById("sidd_"+AccID).value==1)
		{
			suff="@adsl";
		}
		else if(document.getElementById("sidd_"+AccID).value==3)
		{
			suff="@dialup";
		}
		else if(document.getElementById("sidd_"+AccID).value==8)
		{
			suff="@isdn";
		}
		else if(document.getElementById("sidd_"+AccID).value==4)
		{
			suff="@leaseline";
		}
		else if(document.getElementById("sidd_"+AccID).value==2)
		{
			preu=frmActive.elements["SelPhonePreset_"+AccID].options[frmActive.elements["SelPhonePreset_"+AccID].selectedIndex].value;
		}
		

		PackageID=frmActive.elements["PackageID_"+AccID].options[frmActive.elements["PackageID_"+AccID].selectedIndex].value;
		doRe(act,cID,aID,md,cst,preu+frmActive.elements["UserName_"+AccID].value.toString().replace(suff,"")+suff,PackageID,frmActive.elements["SubscriptionName_"+AccID].value);
	}
	
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
		
		// Count inactive
		$sql = "SELECT COUNT(*) 'inactive' FROM tblCustProduct WHERE StatusID = 0 AND CustID = $CustomerID";
		if($que = $mydb->sql_query($sql)){
			$result = $mydb->sql_fetchrow($que);
			$inactive = intval($result['inactive']);
		} $mydb->sql_freeresult($que);
		// Count active
		$sql = "SELECT COUNT(*) 'active' FROM tblCustProduct WHERE StatusID = 1 AND CustID = $CustomerID";
		if($que = $mydb->sql_query($sql)){
			$result = $mydb->sql_fetchrow($que);
			$active = intval($result['active']);
		} $mydb->sql_freeresult($que);
		// Count bar
		$sql = "SELECT COUNT(*) 'bar' FROM tblCustProduct WHERE StatusID = 2 AND CustID = $CustomerID";
		if($que = $mydb->sql_query($sql)){
			$result = $mydb->sql_fetchrow($que);
			$bar = intval($result['bar']);
		} $mydb->sql_freeresult($que);
		// Count close
		$sql = "SELECT COUNT(*) 'close' FROM tblCustProduct WHERE StatusID in(3, 4) AND CustID = $CustomerID";
		if($que = $mydb->sql_query($sql)){
			$result = $mydb->sql_fetchrow($que);
			$close = intval($result['close']);
		} $mydb->sql_freeresult($que);
						
?>
<form name="frmActive">
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
						<td align="left"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=1&ext=ap">Add more products</a></td>
						<td align="right">
							<font color=gray>Inactive:</font> <b><?php print $inactive; ?></b>
							<font color=blue>Active:</font> <b><?php print $active; ?></b>
							<font color=orange>Barred:</font> <b><?php print $bar; ?></b>
							<font color=red>Closed:</font> <b><?php print $close; ?></b> 
						</td>
					</tr>
					<tr><td colspan="2">					 
					</td></tr>
					<tr>		
						<td valign="top" align="left" colspan="2">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">				
                            <tr><td>&nbsp;</td></tr>				
										<?php
                                            $sql = "SELECT AccID, SubscriptionName, UserName, ServiceID, t.packageID FROM tblCustProduct c
													inner Join tblTarPackage t on c.packageID=t.packageID
											WHERE CustID=$CustomerID ORDER BY Statusid,AccID DESC";
        
                                            if($que1 = $mydb->sql_query($sql)){
                                                while($result = $mydb->sql_fetchrow($que1)){
                                                    $AccID = $result['AccID'];
                                                    $SubscriptionName = $result['SubscriptionName'];
													$UserName = $result['UserName'];
													$ServiceID = $result['ServiceID'];
													$packageID = $result['packageID'];
                                                    if(!is_null($AccID))						
                                                    print "
                                                                <tr><td>".Account($AccID, $SubscriptionName,$ServiceID, $UserName, $packageID)."</td></tr>
                                                                <tr><td>&nbsp;</td></tr>
                                                        ";
                                                }
                                            }
                                            $mydb->sql_freeresult();
                                        ?>			
								
								</table>						
							</td>
						</tr>
					</table>
				</td>
			</tr>
            <tr>
            	<td>
                	<div id="re" style="display:none;"></div>
                </td>
            </tr>
</table>
			<input type="hidden" id="crec" value="" />
            </form>
<?php
# Close connection
$mydb->sql_close();
?>
