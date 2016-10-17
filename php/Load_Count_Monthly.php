
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
								<th align="center" width="2%" style="border-left:1px solid #999999; border-top:1px solid #999999;">City</th>						
								<th align="center" width="6%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Package</th>										
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Amount</th>	
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Count</th>									
								<th align="center" width="15%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Total</th>
								<th align="center" width="8%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Full</th>
								<th align="center" width="2%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Invoice Type</th>
							</thead><tbody>';

	//global $mydb;
						$retOut='<form name=frmlpaid >';
						
	$sql = "
			 where amount>0 Order by city;
			 ";
		//and p.accID=237145 
		
	//$retOut.='<font size="-1"> <b> Customer Type : Normal </b></font>';	
	$retOut.=$retOutH;
	$rtype='';
	if($que = $mydb->sql_query($sql)){				
		
		while($result = $mydb->sql_fetchrow($que)){	
						

			$City = $result['City'];
			$Tarname = $result['Tarname'];
			$amount = $result['amount'];
			$ct = $result['ct'];
			$total = $result['total'];
			$type = $result['type'];
			$invoicetype = $result['invoicetype'];
			
			
			
			$b1+=doubleval($BookDeposit);
			$b3+=doubleval($CurrentUsage);
			$b4+=doubleval($Payment);
			$b2+=doubleval($CreditBalance);
			
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			
			
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$iLoop.'</td>';	
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$City.'</td>';		
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Tarname.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$amount.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$ct.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$total.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$type.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$invoicetype.'</td>';
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
//	print $sql;
	$mydb->sql_close();
?>
