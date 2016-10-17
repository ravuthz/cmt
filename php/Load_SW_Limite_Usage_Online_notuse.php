
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
	$where=$_GET['where'];
	$chk='';
	$done=0;
	$lamount = $_GET['lamount'];
	
	function generateFooter($bi2,$bi3,$bi4)
	{
		$retOutF='</tbody><tfoot>		
							<tr>																							
								<td align="center" colspan="6"><b>TOTAL</b></td>
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
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Credit Balance</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Current Usage</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>% Usage</th>
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Done</th>
							</thead><tbody>';
	


	//global $mydb;
						$retOut='<form name=frmlpaid >';
						
	$sql = "select *,cast(cast((CurrentUsage-CreditBalance) as Numeric(30,2)) as varchar(50))  Payment
			 from (select TarName Package,P.AccID,Username Phone,SubscriptionName Subscription,IsNull(Telephone,'') Telephone
			,sum(IsNull(Ab.Credit,0))+sum(IsNull(adv.PaymentAmount,0)) CreditBalance,sum(B.Amount) CurrentUsage,IsNull(Remark,'') 		
			Remark,Sum(IsNull(adv.paymentamount,0)) adv,IsNull(ul.Amount,0) 
			AmountClosed,IsNull(ul.Amount,0) existing,B.BillingCycleID CycleID,IsNull(ul.Status,0) sta,
			case when (cast(($lamount/$w)*100 as Numeric(30,2))>(Sum(IsNull(B.Amount,0))-Sum(IsNull(Ab.Credit,0))) and ul.swstatus=1) then 'Rec' else 'Close' end Done	
			from tblCustomer C Inner Join tblCustproduct p on C.CustID=P.CustID Inner Join tblTarPackage T on P.packageID=T.packageID Inner Join 
			tblBillingSummaryTmp B on P.accID=B.accID Left Join tblCustCashDrawer adv on P.accID=adv.AcctID and adv.IsSubmitted=0 and (adv.IsRollBack is Null or 
			adv.IsRollBack=0) and adv.TransactionModeID in (3) Left Join tblAccDeposit Ad on Ad.AccID=P.accID Left Join tblAccountBalance Ab on P.accID=Ab.accID Left 
			Join (tblException E Inner Join tblExceptionDetail Ed on E.accID=Ed.accID and Remark='Exception') on p.accID=E.accID Left Join [tblSwitchUsageLimitStatus] 
			ul on p.accID=ul.accID and B.BillingCycleID=ul.CycleID where p.custID in ($where) group by 
			Remark,UserName,SubScriptionName,P.accID,C.Telephone,TarName,ul.swstatus,ul.amount,B.BillingCycleID,ul.status
			having (cast(($lamount/$w)*100 as Numeric(30,2))>(Sum(IsNull(B.Amount,0))-Sum(IsNull(Ab.Credit,0))) and ul.swstatus=1) or ul.swstatus=0) V1 Order by 
			Remark asc,Package,PHone ";
		
	$retOut.='<font size="-1"> <b> Customer Type : Normal </b></font>';	
	$retOut.=$retOutH;
	$rtype='';
	if($que = $mydb->sql_query($sql)){				
		
		while($result = $mydb->sql_fetchrow($que)){	
						

			$phone = $result['Phone'];
			$CustName = $result['Subscription'];
			$CurrentUsage = $result['CurrentUsage'];
			$AccID = $result['AccID'];
			$adv = $result['adv'];
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
			
			if($done==0 && $Remark=='Exception')
			{
			
				$retOut.=generateFooter($b2,$b3,$b4);
				$retOut.='<br> <font size="-1"> <b> Customer Type : '.$Remark.' </b></font></br>';
				$retOut.=$retOutH;
				$done=1;
				
			
				$b3=0;
				$b4=0;
				$b2=0;
				$chk=$Remark;
				
			}
			
			$b3+=doubleval($CurrentUsage);
			$b4+=doubleval($Payment);
			$b2+=doubleval($CreditBalance);
			
			/*if($sta!=$existing)
			{
				$rtype="disabled";
			}
			else $rtype="";*/
			
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			
			
			$Done = "<input type=Button Onclick=\"Javascript:doIt(".$AccID.",1,".$AmountClosed.",".$CycleID.",".$existing.",'Add or Update Some Account','".$AccID.'1'."','".$Dn."','".$Dn."');\" ".$rtype." name=\"btnSubmit\" value=\"".$Dn."\" style=\"font-size:8px;\" ID='".$AccID.'1'."' >";
			
			
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$iLoop.'</td>';	
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$package.'</td>';		
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$AccID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$phone.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$CustName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Telephone.'</td>';
			//$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$DemandBook.'</td>';
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
		$retOut.=generateFooter($b2,$b3,$b4);
		

	
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