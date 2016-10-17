<link rel="stylesheet" type="text/css" href="../style/mystyle.css" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$CustomerType=$_GET['custy'];
	$ct=$_GET['ct'];
	$servicename=$_GET['servicename'];
	$cid=$_GET['cid'];
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="60%">
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
								<th align="right" style="border:1px solid">BooIDD</th>
								<th align="right" style="border:1px solid">RefIDD</th>
								<th align="right" style="border:1px solid">TraIDD</th>
								<th align="right" style="border:1px solid">EndIDD</th>
							</thead>
							<tbody>';
	$sql = "	select	CustID,
						AccID,
						SubscriptionName 'name',
	  					UserName,
						
						BookIDD,
						RefIDD,
						IDDtoCr,
						EndIDD
						
				from tblAccCreditDeposit
				where ServiceType = '".$servicename."' 
				and convert(varchar,BillEndDate,112) = '".$cid."'
				and CustomerType = '".$CustomerType."'
				and BookIDD + RefIDD + IDDtoCr + EndIDD <> 0 
				Order by BookIDD desc, RefIDD desc, IDDtoCr desc, EndIDD desc
						";
			
	if($que = $mydb->sql_query($sql)){		
		$n = 0;

		$toBookIDD = 0;
		$toRefIDD = 0;
		$toIDDtoCr = 0;
		$toEndIDD= 0;

		
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){		
																															
			$CustID = $result['CustID'];
			$AccID = $result['AccID'];
			$name = $result['name'];
			$UserName = $result['UserName'];

			$BookIDD = $result['BookIDD'];					
			$RefIDD = $result['RefIDD'];										
			$IDDtoCr = $result['IDDtoCr'];
			$EndIDD = $result['EndIDD'];

$linkAcct = "<a href='../?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91' target = _blank>".$UserName."</a>";

		$toBookIDD += $BookIDD;
		$toRefIDD += $RefIDD;
		$toIDDtoCr += $IDDtoCr;
		$toEndIDD += $EndIDD;

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
			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($BookIDD).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($RefIDD).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($IDDtoCr).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted;border-right:1px solid; border-top:1px dotted;"><b>'.FormatCurrency($EndIDD).'</b></td>';
			

			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																					
								<tr height=20>
										<td colspan="4" align="center" style="border:1px solid">Total :</td>

										<td align="right" style="border:1px solid">'.FormatCurrency($toBookIDD).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toRefIDD).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toIDDtoCr).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($toEndIDD).'</td>

								</tr>
								</table>						
							</td>
						</tr>
					</table>';
     print $retOut;
?>