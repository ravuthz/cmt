<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="Billing_sub.js"></script>
<script language="javascript" type="text/javascript" src="ajax_Billing.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/loading.js"></script>

<style>
	td, th{
		font-family:"Courier New", Courier, monospace;
		font-size:11px;
	}
</style>

<?php
	
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$cid = $_GET['cid'];
	
	$b1=0;
	$b2=0;
	$b3=0;
	
	function generateFooter($bi1,$bi2,$bi3)
	{
		$retOutF='</tbody><tfoot>		
							<tr>																							
								<td align="center" colspan="3"><b>TOTAL</b></td>
								<td align="right" >'.number_format($bi1,2).'</td>
								<td align="right" >'.$bi2.'</td>
								<td align="right" >'.number_format($bi3,2).'</td>
								<td align="center"></td>
							</tr>										
						</tfoot></table>';
		return $retOutF;
		
	}
	
	$retOutH='<br><br><table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#cccccc">
					
						
								<thead>																									
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>																
								<th align="center" width="20%" style="border-left:1px solid #999999; border-top:1px solid #999999;">City Name</th>	
								<th align="center" width="20%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Package Name</th>									
								<th align="center" width="10%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Monthly Charge</th>
								<th align="center" width="10%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Number</th>
								<th align="center" width="10%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Total</th>
								<th align="center" width="10%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Full Monthly</th>
							</thead><tbody>';
	

	$retOut=$retOutH;
	//global $mydb;
						$retOut.='<form name=frmlpaid >';
						
		
	$sql = "exec sp_countmonth '$cid'";

	if($que = $mydb->sql_query($sql)){				
		
		while($result = $mydb->sql_fetchrow($que)){	
						
			$City = $result['City'];										
			$TarName = $result['TarName'];
			$Amount=$result['Amount'];
			$ct = $result['ct'];
			$total = $result['total'];
			$Type = $result['Type'];
			
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			$b1+=$Amount;
			$b2+=$ct;
			$b3+=$total;
			
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$iLoop.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$City.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$TarName.'</td>';			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Amount.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$ct.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$total.'</td>';
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Type.'</td>';
			$retOut .= '</tr>';
			
		}
	}
		$mydb->sql_freeresult();
		$retOut .=generateFooter($b1,$b2,$b3);
		

	
	$Print=date('Y-m-d H:i:s');
	$ret = "<table border=0 cellpadding=0 cellspacing=0 width='100%'>
							<tr>
								<td align=left>Count Monthly<br>
								Cycle date: <b>".$cid."</b><br>
								Printed on: <b>".$Print."</b><br><br>&nbsp;
							</td>
							</tr>
						";

	$ret .= "<tr><td>";
	$ret .= $retOut;
	$ret .= "</td></tr>";
	$ret .= "</table></form>";

	print $ret;	
?>