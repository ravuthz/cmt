<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");		
	$mid = $_GET['mid'];
	//$cpe = $_GET['cpe'];		
	
	$retOut = '
		<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
		<tr>
			<td>
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Messenger reprot </b>
					</td>
					<td align="right">[<a href="./export/messenger.phps?mid='.$mid.'&type=csv">Download</a>]</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>								
								<th align="center">Messenger</th>
								<th align="center">Sangkat</th>
								<th align="center">Khan</th>	
								<th align="center">City</th>	
								<th align="center">Country</th>
								<th align="center">No. Acc</th>																																			
							</thead>
							<tbody>';
	
	$sql = "
					select ca.SangkatID, l1.Name 'Sangkat', l2.Name 'Khan', l3.Name 'City', 
						l4.Name 'Country', count(cp.AccID) as 'Amount', m.Salutation, m.Name
					from tblCustAddress ca, tblCustProduct cp, tlkpLocation l1, 
						tlkpLocation l2, tlkpLocation l3, tlkpLocation l4, tlkpMessenger m
					where ca.SangkatID = l1.id
						and ca.KhanID = l2.id
						and ca.CityID = l3.id
						and ca.CountryID = l4.id
						and ca.AccID = cp.AccID
						and ca.IsBillingAddress = 1
						and cp.MessengerID = m.MessengerID
				";
	if(intval($mid) >0)
		$sql .= "	and cp.MessengerID = ".$mid;
	$sql .= "group by ca.SangkatID, l1.Name, l2.Name, l3.Name, l4.Name, m.Salutation, m.Name
					order by l4.Name, l3.Name, l2.Name, l1.Name					
				";
	
	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		
		while($result = $mydb->sql_fetchrow($que)){																															
			$SangkatID = $result['SangkatID'];
			$Sangkat = $result['Sangkat'];										
			$Khan = $result['Khan'];										
			$City = $result['City'];																
			$Country = $result['Country'];											
			$Amount = $result['Amount'];											
			$Salutation = $result['Salutation'];
			$Name = $result['Name'];			
			$iLoop++;															
			$MName = $Salutation." ".$Name;
			$stAmount = "<a href=\"javascript:showlevel2('d1', ".$mid.", ".$SangkatID.", '".$MName."');\">".$Amount."</a>";
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$MName.'</td>';																									
			$retOut .= '<td class="'.$style.'" align="left">'.$Sangkat.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Khan.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$City.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Country.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.$stAmount.'</td>';
			$retOut .= '</tr>';
		}		
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																									
								</table>						
							</td>
						</tr>
					</table>
				</td>
			</tr>	
			<tr>
				<td>
					<div id="d1">
					</div>
				</td>
			</tr>
		</table>';
		
	print $retOut;	
?>