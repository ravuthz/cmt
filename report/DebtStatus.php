<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="keywords" content="BRC Technology" />
		<meta name="reply-to" content="cheavey@brc-tech.com" />
		<title>..:: Wise Biller ::..</title>
		<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
		<script language="JavaScript" src="../javascript/ajax_gettransaction.js"></script>		
		<style>
			td{
					font-size:10px;
				}
			th{
				font-size:10px;
			}
		</style>
	</head>
	<body>
		<?php
			require_once("../common/agent.php");
			require_once("../common/functions.php");	
			
			$frm=$_GET['frm'];
			$tom=$_GET['tom'];
			$ua=$_GET['ua'];
			$nu=$_GET['nu'];
			$cu=str_replace("\\","",$_GET['cu']);
			$serviceid=$_GET['serviceid'];
			$servicename=$_GET['servicename'];
			$where = $_GET['where'];
			
			$retOut = '				
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>
						Service : '.$servicename.'<br>
						Follow up debt '.$frm.' to '.$tom.'<br>
						Debt Amount  >= '.FormatCurrency($ua).'
						</b>
					</td>
					<td align="right" class="formtitle">Printed on : '.Date("Y F d H:i:s").'</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="2" cellspacing="0" align="center" width="100%" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid #999999">No.</th>
								<th align="center" style="border:1px solid #999999">Type</th>
								<th align="center" style="border:1px solid #999999">Status</th>
								<th align="center" style="border:1px solid #999999">SubscriptionName</th>
								<th align="center" style="border:1px solid #999999">AccID</th>
								<th align="center" style="border:1px solid #999999">UserName</th>
								<th align="center" style="border:1px solid #999999">Package</th>
								<th align="center" style="border:1px solid #999999">LastDate</th>
								<th align="center" style="border:1px solid #999999">Deposit</th>
								<th align="center" style="border:1px solid #999999">INV</th>
								<th align="center" style="border:1px solid #999999">PreUnpaid</th>								
								<th align="center" style="border:1px solid #999999">LastUnpaid</th>
								<th align="center" style="border:1px solid #999999">TotalUnpaid</th>							
							</thead>
							<tbody>';
							
	$sql =	"
	
				Begin Try
					Drop table #Last
				End Try
				Begin catch
				End catch
				
				Begin Try
					Drop table #Pre
				End Try
				Begin catch
				End catch
				
			";						
							
	$sql .= "Select AccID,InvoiceID, Convert(varchar,BillEndDate,102) BillEndDate, UnpaidAmount LastUnpaid
			Into #Last
			from tblCustomerInvoice ci
			join tblSysBillRunCycleInfo sb on sb.CycleID = ci.BillingCycleID
			where InvoiceID in ( 
									Select Max(InvoiceID) from tblCustomerInvoice 
									Where UnpaidAmount > 0 
									And BillingCycleID in (select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,102) between '".$frm."' and '".$tom."') 
									And AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID))
									group by AccID 
							   ) 
			";

	$sql .=	"	Select AccID, Sum(UnpaidAmount) PreUnpaid
				Into #Pre
				from tblCustomerInvoice
				where UnpaidAmount > 0
				and InvoiceID not in (select InvoiceID from #Last)
				and BillingCycleID in (select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,102) between '".$frm."' and '".$tom."') 
				And AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID))
				Group by AccID ";
			
	$sql .= " 	Select	IsNull(ex.Remark,'CUS') Remark, 
				#Last.BillEndDate, 
				ci.AccID,
				ci.CustID, 
				cp.UserName, 
				cp.SubscriptionName, 
				cp.StatusID,
				Count(*) InvNum, 
				IsNull(PreUnpaid,0) PreInv, 
				IsNull(LastUnpaid,0) LastUnpaid, 
				sum(ci.UnpaidAmount) TotalUnpaid, 
				tp.TarName ,
				ad.NationalDeposit + ad.InternationDeposit + ad.MonthlyDeposit Deposit
		From tblCustomerInvoice ci
		join tblCustProduct cp on cp.AccID = ci.AccID 
		join tblAccDeposit ad on ad.AccID = cp.AccID
		join tblSysBillRuncycleInfo sb on sb.CycleID = ci.BillingCycleID
		join tblTarPackage tp on tp.PackageID = sb.PackageID 
		join tlkpService se on se.ServiceID = tp.ServiceID 
		left join (select Distinct AccID,'VIP' Remark from dbo.tblExceptiondetail where Remark = 'Exception') ex on ci.AccID=ex.AccID 
		left join #Last on #Last.AccID = ci.AccID
		left join #Pre on #Pre.AccID = ci.AccID
		where ci.UnpaidAmount > 0 
		and IsNull(ex.Remark,'CUS') in (".$cu.")
		and convert(varchar,sb.BillEndDate,102) between '".$frm."' and '".$tom."'
		and se.Description='".$servicename."'
		And ci.AccID in (select AccID from tblCustAddress Where AddressID in ( select Max(AddressID) from tblCustAddress where IsBillingAddress = 0 and CityID in (".$where.") group by AccID))
		group by ex.Remark, #Last.BillEndDate, ci.AccID, ci.CustID, cp.UserName, cp.SubscriptionName,cp.StatusID, PreUnpaid, LastUnpaid, tp.TarName,ad.NationalDeposit, ad.InternationDeposit, ad.MonthlyDeposit
		having Count(*) >= ".$nu." and sum(ci.UnpaidAmount) >= ".$ua."
		order by ex.Remark, cp.StatusID, cp.SubscriptionName, TotalUnpaid desc
		Drop table #Last
		Drop table #Pre	";


	if($que = $mydb->sql_query($sql)){		

		$n = 0;
		$toalDeposit = 0;
		$toalINV = 0;
		$totalPre = 0;
		$totalLast = 0;
		$AllUnpaid = 0;
		$iLoop = 0;
		
		while($result = $mydb->sql_fetchrow()){																															
			$Remark = $result['Remark'];												
			$BillEndDate = $result['BillEndDate'];
			$AccID = $result['AccID'];
			$CustID = $result['CustID'];
			$StatusID = $result['StatusID'];
			$UserName = $result['UserName'];												
			$SubscriptionName = $result['SubscriptionName'];
			$TarName = $result['TarName'];												
			$Deposit = $result['Deposit'];
			$InvNum = $result['InvNum'];
			$PreInv = $result['PreInv'];												
			$LastUnpaid = $result['LastUnpaid'];
			$TotalUnpaid = $result['TotalUnpaid'];
			
			$toalDeposit += floatval($Deposit);		
			$toalINV += floatval($InvNum);
			$totalPre += floatval($PreInv);
			$totalLast += floatval($LastUnpaid);
			$AllUnpaid += floatval($TotalUnpaid);
			
			$linkUnpaidInv = "<a href='../?CustomerID=".$CustID."&AccountID=".$AccID."&pg=41' target='_blank'><u>".$InvNum."</u></a>";
			
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
			
			//$InvoiceID = "<a href='javascript:showReport(".$InvoiceID.");'>".$InvoiceID."</a>";	
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																						
			
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px dotted #999999; border-right:1px dotted #999999">'.$iLoop.'</td>';				
			$retOut .= '<td class="'.$style.'" align="center" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$Remark.'</td>';
			$retOut .= '<td align="center" bgcolor="'.$stbg.'" style="border-left:1px dotted #999999; border-top:1px dotted #999999; "><font color="'.$stfg.'"><b>'.$stwd.'</b></font></td>';
			$retOut .= '<td class="'.$style.'" align="left"  style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$SubscriptionName.'</td>';																																																
			$retOut .= '<td class="'.$style.'" align="left"  style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$AccID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$UserName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left"  style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$TarName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left"  style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$BillEndDate.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($Deposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right"  style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.$linkUnpaidInv.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($PreInv).'</td>';																								
			$retOut .= '<td class="'.$style.'" align="right"  style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($LastUnpaid).'</td>';
			$retOut .= '<td class="'.$style.'" align="right"  style="border-top:1px dotted #999999; border-right:1px dotted #999999">'.FormatCurrency($TotalUnpaid).'</td>';
			
			$retOut .= '</tr>';									
		}		
	}else{
		$error = $mydb->sql_error();
		print $error['message'];
	}
	$mydb->sql_freeresult();
		
		$retOut .= '</tbody>
									<tfoot bgcolor="#cccccc">															
									<tr>
										<td align="center" colspan=8 style="border:1px solid #999999">Total</td>
										<td align="right" style="border:1px solid #999999">'.FormatCurrency($toalDeposit).'</td>
										<td align="right" style="border:1px solid #999999">'.$toalINV.'</td>
										<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalPre).'</td>
										<td align="right" style="border:1px solid #999999">'.FormatCurrency($totalLast).'</td>
										<td align="right" style="border:1px solid #999999">'.FormatCurrency($AllUnpaid).'</td>
									</tr>
								</tfoot>																																			
							</td>
						</tr>
					</table>
				';
			print $retOut;	
		?>
		
	</body>
</html>