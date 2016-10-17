
<script language="javascript" type="text/javascript" src="ajax_Billing.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/loading.js"></script>
<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<style>
	td, th{
		font-family:"Courier New", Courier, monospace;
		font-size:11px;
	}
	
</style>

<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$chk='';
	$type='';
	$b1=0;
	$b2=0;
	$b3=0;
	$w=intval($_GET['w']);
	$chk='';
	$done=0;
	
	function generateFooter($bi1,$bi2,$bi3,$bi4)
	{
		$retOutF='</tbody><tfoot>		
							<tr>																							
								<td align="center" colspan="10"><b>TOTAL</b></td>
								<td align="right" >'.number_format($bi1,2).'</td>
								<td align="right" >'.number_format($bi2,2).'</td>
								<td align="right" >'.number_format($bi3,2).'</td>
								<td align="right" >'.number_format($bi4,2).'</td>
							</tr>										
						</tfoot></table>';
		return $retOutF;
		
	}
	
	$retOutH='<br><br><table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#cccccc">
											

							<thead>																									
								<th align="center" width="2%" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>						
								<th align="center" width="6%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Package</th>										
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">AccID</th>	
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Telephone</th>									
								<th align="center" width="15%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Customer Name</th>
								<th align="center" width="8%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Contact</th>
								<th align="center" width="2%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Deposit NAT</th>
								<th align="center" width="2%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Deposit IDD</th>
								<th align="center" width="2%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Advance MF</th>
								<th align="center" width="6%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Total Deposit</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Limit Amount</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Credit Balance</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Current Usage</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>% Usage</th>
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Done</th>
							</thead><tbody>';
	


	//global $mydb;
						$retOut='<form name=frmlpaid >';
						
	$sql = "
			BEGIN TRY
							DROP TABLE #billingtmp;					
			END TRY
			BEGIN CATCH
			END CATCH	
					
			select accID,sum(amount) amount,BillingCycleID into #billingtmp from tblBillingSummaryTmp group by BillingCycleID,AccID;
			
			select *,case when limitamount=0 then '' else cast(cast(((CurrentUsage-CreditBalance)*100)/limitamount as Numeric(30,2)) as varchar(50)) + ' %'
			end Payment from (select TarName Package,P.AccID,Username Phone,SubscriptionName Subscription,IsNull(Telephone,'') Telephone
			,IsNull(NationalDeposit,0)+sum(IsNull(bkn.PaymentAmount,0)) NAT,IsNull(InternationDeposit,0)+sum(IsNull(bki.PaymentAmount,0))
			IDD,IsNull(MonthlyDeposit,0)+sum(IsNull(bkm.PaymentAmount,0)) MON,sum(IsNull(bkn.PaymentAmount,0))+sum(IsNull(bki.PaymentAmount,0)) 
			+sum(IsNull(bkm.PaymentAmount,0)) DemandBook,NationalDeposit+InternationDeposit+MonthlyDeposit+sum(IsNull(bkn.PaymentAmount,0)) 
			+sum(IsNull(bki.PaymentAmount,0))+sum(IsNull(bkm.PaymentAmount,0)) BookDeposit,sum(IsNull(Ab.Credit,0))+sum(IsNull(adv.PaymentAmount,0)) 
			CreditBalance,sum(IsNull(B.Amount,0)) CurrentUsage,IsNull(Remark,'') Remark,Sum(IsNull(adv.paymentamount,0)) adv,IsNull(du.Amount,0) 
			AmountClosed,IsNull(du.Amount,0) existing,B.BillingCycleID CycleID,IsNull(du.Status,0) sta,
			case when (limitamount/100)*$w>(Sum(IsNull(B.Amount,0))-Sum(IsNull(Ab.Credit,0))-sum(IsNull(adv.Paymentamount,0))) 
			then case when du.swstatus=1 then 'Reco' else 'at' end else 'Close' end Done,du.swstatus sstatus,isnull(limitamount,0) limitamount from tblCustomer C Inner 
			Join tblCustproduct p on C.CustID=P.CustID Inner Join tblTarPackage T on P.packageID=T.packageID Left Join #billingtmp B on P.accID=B.accID Inner Join 
			tblCreditLimit cl on p.accID=cl.accID and (isnull(limitamount,0)<>0) Left Join tblCustCashDrawer bkn
			on P.accID=bkn.AcctID and bkn.IsSubmitted=0 and (bkn.IsRollBack is Null or bkn.IsRollBack=0) and Bkn.TransactionModeID in (4) Left Join tblCustCashDrawer 
			bki on P.accID=bki.AcctID and bki.IsSubmitted=0 and (bki.IsRollBack is Null or bki.IsRollBack=0) and Bki.TransactionModeID in (5) Left Join 
			tblCustCashDrawer bkm on P.accID=bkm.AcctID and bkm.IsSubmitted=0 and (bkm.IsRollBack is Null or bkm.IsRollBack=0) and Bkm.TransactionModeID in (6)	Left 			
			 Join tblCustCashDrawer adv on P.accID=adv.AcctID and adv.IsSubmitted=0 and	(adv.IsRollBack is Null or adv.IsRollBack=0) and adv.TransactionModeID in (3) 
			Left Join tblAccDeposit Ad on Ad.AccID=P.accID Left Join tblAccountBalance Ab on P.accID=Ab.accID Left Join (tblException E Inner Join tblExceptionDetail 
			Ed on E.accID=Ed.accID and Remark='Exception') on p.accID=E.accID Left Join tblSwitchUsageStatusCreditLimit du on p.accID=du.accID and 
			b.BillingCycleID=du.cycleID group by Remark,UserName,SubScriptionName,P.accID,C.Telephone,NationalDeposit 
			,InternationDeposit,MonthlyDeposit,TarName,B.BillingCycleID,du.amount,du.status,du.swstatus,limitamount having 
			((limitamount/100)*$w>(Sum(IsNull(B.Amount,0))-Sum(IsNull(Ab.Credit,0))-sum(IsNull(adv.Paymentamount,0))) 
			and du.swstatus=1) or du.swstatus in (0,1,2)
			) V1 
			Order by Remark asc,done desc,sstatus asc,Package,payment desc,PHone; 
			
			BEGIN TRY
				DROP TABLE #billingtmp;					
			END TRY
			BEGIN CATCH
			END CATCH
			";
		
	$retOut.='<font size="-1"> <b> Customer Type : Normal </b></font>';	
	$retOut.=$retOutH;
	$rtype='';
	if($que = $mydb->sql_query($sql)){				
		
		while($result = $mydb->sql_fetchrow($que)){	
						

			$phone = $result['Phone'];
			$CustName = $result['Subscription'];
			$CurrentUsage = $result['CurrentUsage'];
			$BookDeposit = $result['BookDeposit'];
			$AccID = $result['AccID'];
			$adv = $result['adv'];
			$NAT = $result['NAT'];
			$IDD = $result['IDD'];
			$MON = $result['MON'];
			$DemandBook = $result['DemandBook'];
			$Telephone = $result['Telephone'];
			$package = $result['Package'];
			$Payment = $result['Payment'];
			$CreditBalance = $result['CreditBalance'];
			$Remark = $result['Remark'];
			$CycleID = $result['CycleID'];
			$existing = $result['existing'];
			$AmountClosed = $result['AmountClosed'];
			$sta = $result['sta'];
			$Dn = $result['Done'];
			$sstatus = $result['sstatus'];
			$limitamount = $result['limitamount'];
			$b=$BookDeposit+$CreditBalance;
			
			if($done==0 && $Remark=='Exception')
			{
			
				$retOut.=generateFooter($b1,$b2,$b3,$b4);
				$retOut.='<br> <font size="-1"> <b> Customer Type : '.$Remark.' </b></font></br>';
				$retOut.=$retOutH;
				$done=1;
				
				$b1=0;
				$b3=0;
				$b4=0;
				$b2=0;
				$chk=$Remark;
				
			}
			
			$b1+=doubleval($BookDeposit);
			$b3+=doubleval($CurrentUsage);
			$b4+=doubleval($Payment);
			$b2+=doubleval($CreditBalance);
			
			if(($sstatus==1 && $Dn!='Reco') || ($sstatus==2) || ($Dn=='at'))
			{
				$rtype="disabled";
			}
			else $rtype="";
			
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			
			if($Dn=='Reco')
			{
				$st="background-color:#0000FF;color:#FFFFFF;font-weight:bold;";
			}
			else if($rtype=="disabled")
			{
				$st="";
			}
			else $st="background-color:#FF6600;color:#FFFFFF;font-weight:bold;";

			if($Dn=='at') $Dn='Reco';
			
			$Done = "<input style='".$st."' type=Button Onclick=\"Javascript:doIt(".$AccID.",1,".$AmountClosed.",".$CycleID.",".$existing.",'Add or Update Some Account','".$AccID.'1'."','".$Dn."',".$b.");\" ".$rtype." name=\"btnSubmit\" value=\"".$Dn."\" style=\"font-size:8px;\" ID='".$AccID.'1'."' >";
			
			
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$iLoop.'</td>';	
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$package.'</td>';		
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$AccID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$phone.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$CustName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Telephone.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$NAT.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$IDD.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$MON.'</td>';
			//$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$DemandBook.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$BookDeposit.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$limitamount.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$CreditBalance.'</td>';
			//$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$adv.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$CurrentUsage.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Payment.'</td>';
//			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$AmountClosed.'</td>';
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Done.'</td>';
			$retOut .= '</tr>';
			
		}
	}
		$mydb->sql_freeresult();
		$retOut.=generateFooter($b1,$b2,$b3,$b4);
		

	
	$Print=date('Y-m-d H:i:s');
	$ret = "<table border=0 cellpadding=0 cellspacing=0 width='100%'>
						";

	$ret .= "<tr><td>";
	$ret .= $retOut;
	$ret .= "</td></tr>";
	$ret .= "</table></form>";
	print $ret;	
//	print $sql;
//	print "$Done";
?>
