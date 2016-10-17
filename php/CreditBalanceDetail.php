<link rel="stylesheet" type="text/css" href="../style/mystyle.css" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$CustomerType=$_GET['custy'];
	$ct=$_GET['ct'];
	$servicename=$_GET['servicename'];
	$cid=$_GET['cid'];
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle" width=70%>
						<b>Monthly Pre-Payment and Deposit Report :</b><br />
						Cycle date: <b>'.$ct.'</b><br />
						Service : <b>'.$servicename.'</b><br />
						Customer Type : <b>'.$CustomerType.'</b>
					</td>
					<td valign="bottom" align="right" class="formtitle">
						Print on: '.date("Y M d H:i:s").'	
					</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid">No.</th>
								<th align="center" style="border:1px solid">AccID</th>
								<th align="center" style="border:1px solid">Name</th>
								<th align="center" style="border:1px solid">UserName</th>																									
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
	$sql = "	select	CustID,
						AccID,
						left(SubscriptionName,7) 'name',
	  					UserName,
						BookNDD,
						RefNDD,
						NDDtoCr,
						EndNDD,

						BookIDD,
						RefIDD,
						IDDtoCr,
						EndIDD,

						BookMF,
						RefMF,
						MFtoCr,
						EndMF,

						IncreaseCr 'BookCr',
						OverINV,
						RefCr,
						Settlement,
						LastCRE,
						UsedCRE,
						EndCredit,

						CrNote,
						Writeoff
						
				from tblAccCreditDeposit
				where ServiceType = '".$servicename."' 
				and convert(varchar,BillEndDate,112) = '".$cid."'
				and CustomerType = '".$CustomerType."'
				and BookNDD + RefNDD + NDDtoCr + EndNDD + BookIDD + RefIDD + IDDtoCr + EndIDD + BookMF + RefMF + MFtoCr + EndMF + IncreaseCr + OverINV + RefCr + Settlement + LastCRE + UsedCRE + EndCredit + CrNote + Writeoff <> 0 
				Order by 
						EndMF desc,
						BookCr desc,
						OverINV desc,
						RefCr desc,
						Settlement desc,
						UsedCRE desc,
						EndCredit desc,
						CrNote desc,
						Writeoff desc
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
																															
			$CustID = $result['CustID'];
			$AccID = $result['AccID'];
			$name = $result['name'];
			$UserName = $result['UserName'];
			
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
			$BegCRE = $result['LastCRE'];
			$UsedCRE = $result['UsedCRE'];
			$EndCr = $result['EndCredit'];

			$CrNote = $result['CrNote'];
			$Writeoff = $result['Writeoff'];


$linkAcct = "<a href='../?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91' target = _blank>".$UserName."</a>";


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
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$AccID.'</td>';								
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$name.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$linkAcct.'</td>';																
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($BookNDD).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($RefNDD).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($NDDtoCr).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;"><b>'.FormatCurrency($EndNDD).'</b></td>';
			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($BookIDD).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($RefIDD).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($IDDtoCr).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;"><b>'.FormatCurrency($EndIDD).'</b></td>';
			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($BookMF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($RefMF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($MFtoCr).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;"><b>'.FormatCurrency($EndMF).'</b></td>';
			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($BookCr).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($OverINV).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($RefCr).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($Settlement).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($BegCRE).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($UsedCRE).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;"><b>'.FormatCurrency($EndCr).'</b></td>';
			
			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($CrNote).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($Writeoff).'</td>';

			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																					
								<tr height=20>
										<td colspan="4" align="center" style="border:1px solid">Total :</td>
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
					</table>';
     print $retOut;
?>