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
	$invoiceid = $_GET['invoiceid'];	
		

		

	$retOut .=	'
						<table border="0" cellpadding="3" cellspacing="0" align="left" width="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																									
								<th align="center" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>																
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrap="nowrap">Invoice ID</th>
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Description</th>								
								<th align="center" style="border-left:1px dotted #999999; border-top:1px solid #999999; border-right:1px solid #999999;" nowrape>Amount</th>											
							</thead>
							<tbody>';
	
	$sql = " SELECT * FROM tblCustomerInvoiceDetail WHERE InvoiceID = ".$invoiceid." AND BillItemID <> 5";

	if($que = $mydb->sql_query($sql)){						
		$TotalAmount = 0.00;		
		while($result = $mydb->sql_fetchrow($que)){																															
														
			$InvoiceID = $result['InvoiceID'];
			$Description = $result['Description'];
			$Amount = $result['Amount'];			
			$TotalAmount += $Amount;
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";			
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;">'.$iLoop.'</td>';						
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$InvoiceID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Description.'</td>';						
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-right:1px solid #999999;">'.FormatCurrency($Amount).'</td>';
			$retOut .= '</tr>';
				
		}
	}
		$mydb->sql_freeresult();
		$TotalInv = "<a href='../report/revenue_detail.php?st=".$cid."&it=0&pid=".$pid."&pt=".$pt."&tt=All&sid=".$sid."' target='_blank'>".$TotalInv."</a>";
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan=3 style="border-left:1px solid #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">Total</td>																																												
											<td align="right" style="border-left:1px dotted #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999; border-right:1px solid #999999;">'.FormatCurrency($TotalAmount).'</td>										
										</tr>
									</tfoot>												
								</table>						
							';
	print $retOut;	
	
?>
