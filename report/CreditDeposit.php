<script language="javascript" type="text/javascript" src="../javascript/loading.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/date.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/ajax_gettransaction.js"></script>
<script language="javascript" type="text/javascript">	

	function showdetailNDD(Item, CustomerType, ct, servicename, cid){
		var loading;
loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='../images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
		document.getElementById('detail').innerHTML = loading;
			url = "../php/CreditBalanceDetailNDD.php?custy="+CustomerType+"&ct="+ct+"&servicename="+servicename+"&cid=" +cid+"&mt=" + new Date().getTime();
		getTranDetail(url, "detail");
	}
	
	function showdetailIDD(Item, CustomerType, ct, servicename, cid){
		var loading;
loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='../images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
		document.getElementById('detail').innerHTML = loading;
			url = "../php/CreditBalanceDetailIDD.php?custy="+CustomerType+"&ct="+ct+"&servicename="+servicename+"&cid=" +cid+"&mt=" + new Date().getTime();
		getTranDetail(url, "detail");
	}

	function showdetailAMF(Item, CustomerType, ct, servicename, cid){
		var loading;
loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='../images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
		document.getElementById('detail').innerHTML = loading;
			url = "../php/CreditBalanceDetailAMF.php?custy="+CustomerType+"&ct="+ct+"&servicename="+servicename+"&cid=" +cid+"&mt=" + new Date().getTime();
		getTranDetail(url, "detail");
	}
	
	function showdetailCRE(Item, CustomerType, ct, servicename, cid){
		var loading;
loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='../images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
		document.getElementById('detail').innerHTML = loading;
			url = "../php/CreditBalanceDetailCRE.php?custy="+CustomerType+"&ct="+ct+"&servicename="+servicename+"&cid=" +cid+"&mt=" + new Date().getTime();
		getTranDetail(url, "detail");
	}

	function showdetail(Item, CustomerType, ct, servicename, cid){
		var loading;
loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='../images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
		document.getElementById('detail').innerHTML = loading;
		url = "../php/CreditBalanceDetail.php?custy="+CustomerType+"&ct="+ct+"&servicename="+servicename+"&cid=" +cid+"&mt=" + new Date().getTime();
		
		getTranDetail(url, "detail");
	}

</script>


<link rel="stylesheet" type="text/css" href="../style/mystyle.css" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$ct=$_GET['ct'];	
	$cid=$_GET['cid'];	
	$servicename = $_GET['sname'];
	

	
	
	
	
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Monthly Pre-Payment and Deposit Report:</b><br />
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
								<th align="center" style="border:1px solid">Type</th>								
								<th align="center" style="border:1px solid">BooNDD</th>
								<th align="center" style="border:1px solid">RefNDD</th>
								<th align="right" style="border:1px solid">TraNDD</th>
								<th align="center" style="border:1px solid">EndNDD</th>
								<th align="right" style="border:1px solid">BooIDD</th>
								<th align="right" style="border:1px solid">RefIDD</th>
								<th align="right" style="border:1px solid">TraIDD</th>
								<th align="right" style="border:1px solid">EndIDD</th>
								<th align="right" style="border:1px solid">BooAMF</th>
								<th align="right" style="border:1px solid">RefAMF</th>
								<th align="right" style="border:1px solid">TraAMF</th>
								<th align="right" style="border:1px solid">EndAMF</th>
								<th align="right" style="border:1px solid">IncCRE</th>
								<th align="right" style="border:1px solid">OverINV</th>
								<th align="right" style="border:1px solid">RefCRE</th>
								<th align="right" style="border:1px solid">Settle</th>
								<th align="right" style="border:1px solid">BegCRE</th>
								<th align="right" style="border:1px solid">UsedCRE</th>
								<th align="right" style="border:1px solid">EndCRE</th>
								<th align="right" style="border:1px solid">CreNote</th>
								<th align="right" style="border:1px solid">WriteOff</th>								
							</thead>
							<tbody>';
	$sql = "	select	CustomerType,
	  
						sum(BookNDD) 'BookNDD',
						sum(RefNDD) 'RefNDD',
						sum(NDDtoCr) 'NDDtoCr',
						sum(EndNDD) 'EndNDD',

						sum(BookIDD) 'BookIDD',
						sum(RefIDD) 'RefIDD',
						sum(IDDtoCr) 'IDDtoCr',
						sum(EndIDD) 'EndIDD',

						sum(BookMF) 'BookMF',
						sum(RefMF) 'RefMF',
						sum(MFtoCr) 'MFtoCr',
						sum(EndMF) 'EndMF',

						sum(IncreaseCr) 'BookCr',
						sum(OverINV) 'OverINV',
						sum(RefCr) 'RefCr',
						sum(Settlement) 'Settlement',
						sum(LastCRE) 'BegCRE',
						sum(UsedCRE) 'UsedCRE',
						sum(EndCredit) 'EndCr',

						sum(CrNote) 'CrNote',
						sum(Writeoff) 'Writeoff'
						
				from tblAccCreditDeposit
				where ServiceType = '".$servicename."' 
				and convert(varchar,BillEndDate,112) = '".$cid."'
				and abs(BookNDD) + abs(RefNDD) + abs(NDDtoCr) + abs(EndNDD) + abs(BookIDD) + abs(RefIDD) + abs(IDDtoCr) + abs(EndIDD) + abs(BookMF) + abs(RefMF) + abs(MFtoCr) + abs(EndMF) + abs(IncreaseCr) + abs(OverINV) + abs(RefCr) + abs(Settlement) + abs(LastCRE) + abs(UsedCRE) + abs(EndCredit) + abs(CrNote) + abs(Writeoff) <> 0 
				group by CustomerType
				
			";

	if($que = $mydb->sql_query($sql)){		
		$n = 0;
		
		$toBookNDD = 0;
		$toRefNDD = 0;
		$toNDDtoCr = 0;
		$toEndNDD = 0;

		$toBookIDD = 0;
		$toRefIDD = 0;
		$toIDDtoCr = 0;
		$toEndIDD= 0;

		$toBookMF = 0;
		$toRefMF = 0;
		$toMFtoCr = 0;
		$toEndMF = 0;

		$toBookCr = 0;
		$toOverINV = 0;
		$toRefCr = 0;
		$toSettlement = 0;
		$toBegCRE = 0;
		$toUsedCRE = 0;
		$toEndCr= 0;

		$toCrNote = 0;
		$toWriteoff = 0;
		
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$CustomerType = $result['CustomerType'];
			$BookNDD = $result['BookNDD'];					
			$RefNDD = $result['RefNDD'];										
			$NDDtoCr = $result['NDDtoCr'];
			$EndNDD = $result['EndNDD'];

			$BookIDD = $result['BookIDD'];					
			$RefIDD = $result['RefIDD'];										
			$IDDtoCr = $result['IDDtoCr'];
			$EndIDD = $result['EndIDD'];

			$BookMF = $result['BookMF'];					
			$RefMF = $result['RefMF'];										
			$MFtoCr = $result['MFtoCr'];
			$EndMF = $result['EndMF'];

			$BookCr = $result['BookCr'];					
			$OverINV = $result['OverINV'];					
			$RefCr = $result['RefCr'];										
			$Settlement = $result['Settlement'];
			$BegCRE = $result['BegCRE'];
			$UsedCRE = $result['UsedCRE'];
			$EndCr = $result['EndCr'];

			$CrNote = $result['CrNote'];
			$Writeoff = $result['Writeoff'];
				
	$linkCust = "<a href=\"javascript:showdetail('All','".$CustomerType."', '".$ct."', '".$servicename."', '".$cid."');\">".$CustomerType."</a>";
	$linkEndNDD = "<a href=\"javascript:showdetailNDD('NDD','".$CustomerType."', '".$ct."', '".$servicename."', '".$cid."');\">".$EndNDD."</a>";
	$linkEndIDD = "<a href=\"javascript:showdetailIDD('IDD','".$CustomerType."', '".$ct."', '".$servicename."', '".$cid."');\">".$EndIDD."</a>";
	$linkEndAMF = "<a href=\"javascript:showdetailAMF('AMF','".$CustomerType."', '".$ct."', '".$servicename."', '".$cid."');\">".$EndMF."</a>";
	$linkEndCRE = "<a href=\"javascript:showdetailCRE('AMF','".$CustomerType."', '".$ct."', '".$servicename."', '".$cid."');\">".$EndCr."</a>";
		
		$toBookNDD += $BookNDD;
		$toRefNDD += $RefNDD;
		$toNDDtoCr += $NDDtoCr;
		$toEndNDD += $EndNDD;

		$toBookIDD += $BookIDD;
		$toRefIDD += $RefIDD;
		$toIDDtoCr += $IDDtoCr;
		$toEndIDD += $EndIDD;

		$toBookMF += $BookMF;
		$toRefMF += $RefMF;
		$toMFtoCr += $MFtoCr;
		$toEndMF += $EndMF;

		$toBookCr += $BookCr;
		$toOverINV += $OverINV;
		$toRefCr += $RefCr;
		$toSettlement += $Settlement;
		$toBegCRE += $BegCRE;
		$toUsedCRE += $UsedCRE;
		$toEndCr += $EndCr;

		$toCrNote += $CrNote;
		$toWriteoff += $Writeoff;
					
			$iLoop++;															
			$n++;
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid; border-top:1px dotted;">'.$n.'</td>';	
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$linkCust.'</td>';								
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($BookNDD).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($RefNDD).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($NDDtoCr).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;"><b>'.$linkEndNDD.'</b></td>';
			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($BookIDD).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($RefIDD).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($IDDtoCr).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;"><b>'.$linkEndIDD.'</b></td>';
			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($BookMF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($RefMF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($MFtoCr).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;"><b>'.$linkEndAMF.'</b></td>';
			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($BookCr).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($OverINV).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($RefCr).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($Settlement).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($BegCRE).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($UsedCRE).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;"><b>'.$linkEndCRE.'</b></td>';
			
			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($CrNote).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($Writeoff).'</td>';
			
			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
	
		$retOut .= '</tbody>																					
								<tr height=20>
										<td colspan="2" align="center" style="border:1px solid">Total :</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toBookNDD).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toRefNDD).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toNDDtoCr).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toEndNDD).'</td>

										<td align="right" style="border:1px solid">'.FormatCurrency($toBookIDD).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toRefIDD).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toIDDtoCr).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toEndIDD).'</td>

										<td align="right" style="border:1px solid">'.FormatCurrency($toBookMF).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toRefMF).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toMFtoCr).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toEndMF).'</td>

										<td align="right" style="border:1px solid">'.FormatCurrency($toBookCr).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toOverINV).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toRefCr).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toSettlement).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toBegCRE).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toUsedCRE).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toEndCr).'</td>


										<td align="right" style="border:1px solid">'.FormatCurrency($CrNote).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($Writeoff).'</td>
								</tr>
								</table>						
							</td>
						</tr>
						
						<tr>
							<td colspan=2>
								<div id="detail"></div>
							</td>
						</tr>
					</table>';
     print $retOut;
?>