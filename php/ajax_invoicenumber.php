<link rel="stylesheet" type="text/css" href="../style/mystyle.css" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$ct=$_GET['ct'];	
	$cid=$_GET['cid'];	
	$where = $_GET['where'];
	$sid1 = $_GET['serviceid'];
	$servicename = $_GET['servicename'];
	
	$pco = "in(16,17)";
	$cmtstaff = "in(9)";
	$internal = "in(18,19,79)";
	
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>MONTHLY INVOICE ISSUE:</b><br />
						Cycle date: <b>'.$ct.'</b><br />
						Service : <b>'.$servicename.'</b><br />
						
						
					</td>
					<td align="right" class="formtitle" valign="bottom">Print on: '.date("Y M d H:i:s").'</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid">No.</th>
								<th align="center" style="border:1px solid">City</th>								
								<th align="center" style="border:1px solid">Khan</th>
								<th align="center" style="border:1px solid">Sangkat</th>
								<th align="center" style="border:1px solid">Messenger</th>
								<th align="right" style="border:1px solid">Normal</th>
								<th align="right" style="border:1px solid">CmtStaff</th>
								<th align="right" style="border:1px solid">PCO</th>
								<th align="right" style="border:1px solid">Total</th>								
							</thead>
							<tbody>';
	$sql = "		BEGIN TRY
						DROP TABLE #NumInv
					END TRY
					BEGIN CATCH
					END CATCH
	
			select	ca.Sangkatid, Package = Case
					when cp.packageid ".$pco." then 'PCO'
					when cp.packageid ".$cmtstaff." then 'Cmt'
					when cp.packageid ".$internal." then 'Internal'
					Else 'Normal'
					end,
					(select [name] from dbo.tlkpLocation where tlkpLocation.ID = ca.CityID) City,
					(select [name] from dbo.tlkpLocation where tlkpLocation.ID = ca.KhanID) Khan, 
					(select [name] from dbo.tlkpLocation where tlkpLocation.ID = ca.SangkatID) Sangkat,
					count(ci.invoiceid) InvoiceNumber
			into #NumInv from tblCustomerInvoice ci 
			join tblCustProduct cp on ci.accid = cp.accid
			join tblCustAddress ca on ca.accid = cp.accid 
			join tblTarPackage tp on tp.packageid = cp.packageid
			where	IsBillingaddress = 1
					and ci.invoiceamount >= 0.01
					and ci.InvoiceType in (1,2,3)
					and ci.InvoiceID not in ( select invoiceid from tblCashPayment where convert(varchar,paymentdate,112) <= '".$cid."' )
			";
if($sid1 == 2){
	$sql .= " and tp.ServiceID = 2 ";
}elseif($sid1 == 4){
	$sql .= " and tp.ServiceID = 4 ";
}elseif($sid1 == 5){	
	$sql .= " and tp.ServiceID in (1, 3, 8) ";
}
	$sql .= " and ci.BillingCycleid in 
				(select cycleid from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112) = '".$cid."')
				group by ca.CityID,ca.KhanID,ca.Sangkatid,Case
					when cp.packageid ".$pco." then 'PCO'
					when cp.packageid ".$cmtstaff." then 'Cmt'
					when cp.packageid ".$internal." then 'Internal'
					Else 'Normal'
					end
			";
	
	$sql .= "	select City,Khan,Sangkat,Messenger,Sangkatid, 
					IsNull(Normal,0) 'Normal', 
					IsNull(Cmt,0) 'CmtStaff', 
					IsNull(PCO,0) 'PCO'
					
				From
					(	
						select nu.City,nu.Khan,nu.Sangkat,nu.Sangkatid,nu.Package, nu.InvoiceNumber, me.name 'Messenger' from #NumInv nu
						left join tlkpMessengerSangkat ms on nu.Sangkatid = ms.Sangkatid 
						left join tlkpMessenger me on ms.MessengerID = me.MessengerID
					) id
				PIVOT (sum(InvoiceNumber) For Package in(Normal,Cmt,PCO)) as pvt
				order by  City desc,Khan,Sangkat,Messenger
				drop table #NumInv
				
			";
			
	if($que = $mydb->sql_query($sql)){		
		$n = 0;
		$totalnormal = 0;
		$totalcmt = 0;
		$totalpco = 0;
		$totalSinv = 0;
		$totalAll = 0;
		
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$City = $result['City'];
			$Sangkatid = $result['Sangkatid'];					
			$Khan = $result['Khan'];										
			$Sangkat = $result['Sangkat'];
			$Messenger = $result['Messenger'];
			$Normal = $result['Normal'];
			$CmtStaff = $result['CmtStaff'];
			$PCO = $result['PCO'];			
			
	$linkNormal = "<a href='../php/ajax_invoicenumber_sangkat.php?&city=".$City."&kn=".$Khan."&skn=".$Sangkat."&skid=0&pa=normal&ct=".$ct."&servicename=".$servicename."&sid1=".$sid1."&sk=".$Sangkatid."&cid=".$cid."'>".$Normal."</a>";		
			
	$linkcmt = "<a href='../php/ajax_invoicenumber_sangkat.php?&city=".$City."&kn=".$Khan."&skn=".$Sangkat."&skid=0&pa=cmt&ct=".$ct."&servicename=".$servicename."&sid1=".$sid1."&sk=".$Sangkatid."&cid=".$cid."'>".$CmtStaff."</a>";
	
	$linkpco = "<a href='../php/ajax_invoicenumber_sangkat.php?&city=".$City."&kn=".$Khan."&skn=".$Sangkat."&skid=0&pa=pco&ct=".$ct."&servicename=".$servicename."&sid1=".$sid1."&sk=".$Sangkatid."&cid=".$cid."'>".$PCO."</a>";
				
			$totalSinv = $Normal + $CmtStaff + $PCO;
			$totalAll += $totalSinv;
			
			$totalnormal+= $Normal;
			$totalcmt+= $CmtStaff;
			$totalpco+= $PCO;
					
			$iLoop++;															
			$n++;
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid; border-top:1px dotted;">'.$n.'</td>';	
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$City.'</td>';								
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$Khan.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$Sangkat.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$Messenger.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.$linkNormal.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.$linkcmt.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.$linkpco.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.$totalSinv.'</td>';
			
			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
	
	
			
	$linkNormalall = "<a href='../php/ajax_invoicenumber_sangkat_all.php?&skid=1&pa=normal&ct=".$ct."&servicename=".$servicename."&sid1=".$sid1."&sk=".$Sangkatid."&cid=".$cid."'>".$totalnormal."</a>";		
			
	$linkcmtall = "<a href='../php/ajax_invoicenumber_sangkat_all.php?&skid=1&pa=cmt&ct=".$ct."&servicename=".$servicename."&sid1=".$sid1."&sk=".$Sangkatid."&cid=".$cid."'>".$totalcmt."</a>";
	
	$linkpcoall = "<a href='../php/ajax_invoicenumber_sangkat_all.php?&skid=1&pa=pco&ct=".$ct."&servicename=".$servicename."&sid1=".$sid1."&sk=".$Sangkatid."&cid=".$cid."'>".$totalpco."</a>";
	
		$retOut .= '</tbody>																					
								<tr height=20>
										<td colspan="5" align="right" style="border:1px solid">Total:&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td align="right" style="border:1px solid">'.$linkNormalall.'</td>
										<td align="right" style="border:1px solid">'.$linkcmtall.'</td>
										<td align="right" style="border:1px solid">'.$linkpcoall.'</td>
										<td align="right" style="border:1px solid">'.$totalAll.'</td>
								</tr>
								</table>						
							</td>
						</tr>
					</table>';
     print $retOut;
?>