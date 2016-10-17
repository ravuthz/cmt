
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
	$where=trim($_GET['where']);
	$chk='';
	$done=0;
	
	function generateFooter($bi1,$bi2,$bi3,$bi4)
	{
		$retOutF='</tbody><tfoot>		
							<tr>																							
								<td align="center" colspan="13"></td>
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
								<th align="center" width="2%" style="border-left:1px solid #999999; border-top:1px solid #999999;">NDD</th>
								<th align="center" width="2%" style="border-left:1px solid #999999; border-top:1px solid #999999;">IDD</th>
								<th align="center" width="2%" style="border-left:1px solid #999999; border-top:1px solid #999999;">AMF</th>
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Total Deposit</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Credit Balance</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Current Usage</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>% Usage</th>
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Closed</th>
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>View</th>
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Com</th>
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Done</th>
							</thead><tbody>';
	


	//global $mydb;
						$retOut='<form name=frmlpaid >';
						
	$sql = "
			BEGIN TRY
				DROP TABLE #billingtmp;					
				DROP TABLE #bkn;
				DROP TABLE #bki;
				DROP TABLE #bkm;
				DROP TABLE #adv;
			END TRY
			BEGIN CATCH
			END CATCH	
					
			select P.accID,sum(IsNull(amount,0)) amount,BillingCycleID into #billingtmp from tblCustProduct P Inner Join tblBillingSummaryTmp B on P.accID=B.accID
			 where P.PackageID in ($where) group by BillingCycleID,P.AccID;
			
			-----------------------------Book National------------------------------------
			select sum(IsNull(PaymentAmount,0)) PaymentAmount,P.accID into #bkn from tblCustProduct P Inner Join tblCustCashDrawer bkn
			on P.accID=bkn.AcctID and bkn.IsSubmitted=0 and (bkn.IsRollBack is Null or bkn.IsRollBack=0) and Bkn.TransactionModeID in (4)
			where P.PackageID in ($where)
			Group by p.accID
			
			-----------------------------Book IDD------------------------------------
			select sum(IsNull(PaymentAmount,0)) PaymentAmount,P.accID into #bki from tblCustProduct P Inner Join
			tblCustCashDrawer bki on P.accID=bki.AcctID and bki.IsSubmitted=0 and (bki.IsRollBack is Null or bki.IsRollBack=0) and Bki.TransactionModeID in (5)
			where P.PackageID in ($where)
			Group by p.accID
			
			-----------------------------Book Advance Monthly Fee------------------------------------
			select sum(IsNull(PaymentAmount,0)) PaymentAmount,P.accID into #bkm from tblCustProduct P Inner Join
			tblCustCashDrawer bkm on P.accID=bkm.AcctID and bkm.IsSubmitted=0 and (bkm.IsRollBack is Null or bkm.IsRollBack=0) and Bkm.TransactionModeID in (6)
			where P.PackageID in ($where)
			Group by p.accID
			
			-----------------------------Advance Payment------------------------------------
			select sum(IsNull(PaymentAmount,0)) PaymentAmount,P.accID into #adv from tblCustProduct P Inner Join
			tblCustCashDrawer adv on P.accID=adv.AcctID and adv.IsSubmitted=0 and	(adv.IsRollBack is Null or adv.IsRollBack=0) and adv.TransactionModeID in (3)
			where P.PackageID in ($where)
			Group by p.accID

			select * from 
			(select top 100 percent *,case when BookDeposit<100 then cast(cast(((CurrentUsage-CreditBalance)*100)/100 as Numeric(30,2)) as varchar(50)) + ' %' else 
			cast(cast(((CurrentUsage-CreditBalance)*100)/BookDeposit as Numeric(30,2)) as varchar(50)) + ' %'
			 end Payment,case when BookDeposit<100 then cast(((CurrentUsage-CreditBalance)*100)/100 as Numeric(30,2)) else 
			cast(((CurrentUsage-CreditBalance)*100)/BookDeposit as Numeric(30,2))
			 end pw from (select TarName Package,P.AccID,Username Phone,SubscriptionName Subscription,IsNull(Telephone,'') Telephone
			,IsNull(NationalDeposit,0)+sum(IsNull(bkn.PaymentAmount,0)) NAT,IsNull(InternationDeposit,0)+sum(IsNull(bki.PaymentAmount,0))
			IDD,IsNull(MonthlyDeposit,0)+sum(IsNull(bkm.PaymentAmount,0)) MON,sum(IsNull(bkn.PaymentAmount,0))+sum(IsNull(bki.PaymentAmount,0)) 
			+sum(IsNull(bkm.PaymentAmount,0)) DemandBook,NationalDeposit+InternationDeposit+MonthlyDeposit+sum(IsNull(bkn.PaymentAmount,0)) 
			+sum(IsNull(bki.PaymentAmount,0))+sum(IsNull(bkm.PaymentAmount,0)) BookDeposit,IsNull(Ab.Credit,0)+sum(IsNull(adv.PaymentAmount,0)) 
			CreditBalance,IsNull(B.Amount,0) CurrentUsage,IsNull(Remark,'') Remark,Sum(IsNull(adv.paymentamount,0)) adv,IsNull(du.Amount,0) 
			AmountClosed,IsNull(du.Amount,0) existing,B.BillingCycleID CycleID,IsNull(du.Status,0) sta,isnull(curbook,0) curbook,cast(isnull(du.swstatus,-1) as int) 
			swstatus ,case when (cast((NationalDeposit+InternationDeposit+MonthlyDeposit+sum(IsNull(bkn.PaymentAmount,0))+sum(IsNull(bki.PaymentAmount,0)) 
			 +sum(IsNull(bkm.PaymentAmount,0))) as numeric(30,2))/100)*$w<=(IsNull(B.Amount,0)-IsNull(Ab.Credit,0)-sum(IsNull(adv.Paymentamount,0))) then
			 'Close' else 'Rec' end state,case when (cast((NationalDeposit+InternationDeposit+MonthlyDeposit+sum(IsNull(bkn.PaymentAmount,0)) 
			 +sum(IsNull(bki.PaymentAmount,0)) +sum(IsNull(bkm.PaymentAmount,0))) as numeric(30,2))/100)*$w<=(IsNull(B.Amount,0)-IsNull(Ab.Credit,0)
			 -sum(IsNull(adv.Paymentamount,0))) then case when cast(isnull(du.swstatus,-1) as int)=2 then -2 else 
			 cast(isnull(du.swstatus,-1) as int) end else cast(isnull(du.swstatus,-1) as int) end sworder,sum(case when isnull(cm.accID,0)<>0 then 1 else 0 end) 
			 viewcount,t.cyclefee from tblCustomer
			 C Inner Join tblCustproduct p on C.CustID=P.CustID Inner Join tblTarPackage T on P.packageID=T.packageID Inner Join #billingtmp B on P.accID=B.accID Left 
			 Join #bkn bkn on p.accID=bkn.accID Left Join #bki bki on p.accID=bki.accID Left Join #bkm bkm on p.accID=bkm.accID	Left Join #adv adv on p.accID=adv.accID 
			 Left Join tblAccDeposit Ad on Ad.AccID=P.accID Left Join tblAccountBalance Ab on P.accID=Ab.accID Left Join (tblException E Inner Join tblExceptionDetail 
			 Ed on E.accID=Ed.accID and Remark='Exception') on p.accID=E.accID Left Join tblSwitchUsageStatus du on p.accID=du.accID and b.BillingCycleID=du.cycleID 
			 left join tblpcocomment cm on p.accID=cm.accID and cm.CycleID=B.BillingCycleID
			 where P.PackageID in ($where) group by B.Amount,
			  Remark,UserName,SubScriptionName,P.accID,C.Telephone,NationalDeposit,InternationDeposit,MonthlyDeposit,TarName,B.BillingCycleID,du.amount,du.status
			  ,du.swstatus,curbook,cm.accID,ab.credit,t.cyclefee having
			   (cast((NationalDeposit+InternationDeposit+MonthlyDeposit+sum(IsNull(bkn.PaymentAmount,0))+sum(IsNull(bki.PaymentAmount,0)) 
			 +sum(IsNull(bkm.PaymentAmount,0))) as numeric(30,2))/100)*$w<=(IsNull(B.Amount,0)-IsNull(Ab.Credit,0)-sum(IsNull(adv.Paymentamount,0)))
			 or du.swstatus in (0,1,2)) V1 Order by Remark asc,sworder,Package,payment desc,PHone ) v2 where pw>=$w
			 Order by Remark asc,sworder,Subscription,Package,payment desc,PHone
			 
			 BEGIN TRY
				DROP TABLE #billingtmp;					
				DROP TABLE #bkn;
				DROP TABLE #bki;
				DROP TABLE #bkm;
				DROP TABLE #adv;
			END TRY
			BEGIN CATCH
			END CATCH
			 
			 ";
		//and p.accID=237145 
		
	$retOut.='<font size="-1"> <b> Customer Type : Normal </b></font>';	
	$retOut.=$retOutH;
	$rtype='';
	if($que = $mydb->sql_query($sql)){				
		
		while($result = $mydb->sql_fetchrow($que)){	
						

			$phone = $result['Phone'];
			$CustName = $result['Subscription'];
			$CurrentUsage = round(($result['CurrentUsage']+$result['cyclefee'])+((($result['CurrentUsage']+$result['cyclefee'])*10)/100),2);
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
			$curbook = $result['curbook'];
			$b=$CreditBalance+$BookDeposit; 
			$state = $result['state'];
			$swstatus = $result['swstatus'];
			$viewcount = $result['viewcount'];
			
		/*	if($BookDeposit<100 || is_null($BookDeposit))
			{
				$Payment=((($CurrentUsage-$CreditBalance)*100)/100);
			}
			{
				$Payment=((($CurrentUsage-$CreditBalance)*100)/$BookDeposit);
			}
			*/
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
			
			if((((intval($swstatus)==0) || (intval($swstatus)==1) || (intval($swstatus)==2 && $state<>'Close')) && ($b<=$curbook) && ($b!=0 || $swstatus<>-1)) || ($state=='Rec') || ($swstatus==0 && $state=='Close'))  
			{
				$rtype="disabled";
			}
			else $rtype="";
			
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			
			if($viewcount>0)
			{
				$st="style=\"font-size:8px;color:#0000FF;font-weight:bold;\"";
			}
			else
			{
				$st="style=\"font-size:8px;color:#000000;\"";
			}
			
			$Done = "<input type=Button Onclick=\"Javascript:doIt(".$AccID.",1,".$AmountClosed.",".$CycleID.",".$existing.",'Add or Update Some Account','".$AccID.'1'."',".$b.");\" ".$rtype." name=\"btnSubmit\" value=\"Done\" style=\"font-size:8px;\" ID='".$AccID.'1'."' >";
			
			$Com = "<input type=Button Onclick=\"Javascript:doPCOcomment(".$AccID.",".$CycleID.");\" name=\"Com\" value=\"Com\" style=\"font-size:8px;\" ID='".$AccID.'2'."' >";
			$View = "<input type=Button Onclick=\"Javascript:viewPCOcomment(".strval($AccID).",".strval($AccID)."5".",".strval($CycleID).",".strval($AccID)."3".",".$viewcount.");\" name=\"View\" value=\"Vw(".$viewcount.")\" ".$st." ID='".$AccID.'3'."' >";	
			
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
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$CreditBalance.'</td>';
			//$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$adv.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$CurrentUsage.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Payment.'</td>';
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$AmountClosed.'</td>';
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$View.'</td>';
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Com.'</td>';
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Done.'</td>';
			$retOut .= '</tr>';
			$retOut .= '<tr><td colspan=17><div id='.strval($AccID).'5'.' style="display:none;"></div></td></tr>';
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
	// print $sql;
	$mydb->sql_close();
?>
