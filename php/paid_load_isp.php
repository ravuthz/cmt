
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
	$cid = $_GET['cid'];
	$where = $_GET['w'];
	$chk='';
	$type='';
	$b1=0;
	$b2=0;
	$b3=0;
	
	function generateFooter($bi1,$bi2,$bi3,$l)
	{
		$retOutF='</tbody><tfoot>		
							<tr>																							
								<td align="center" colspan="6"><b>TOTAL Number of Accounts '.$l.'</b></td>
								<td align="right" >'.$bi3.'</td>
								<td align="right" >'.number_format($bi1,2).'</td>
								<td align="right" >'.number_format($bi2,2).'</td>
								<td align="center" colspan="6" ></td>
							</tr>										
						</tfoot></table>';
		return $retOutF;
		
	}
	
	$retOutH='<br><br><table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#cccccc">
					
						
							<thead>																									
								<th align="center" width="2%" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>																
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">AccID</th>	
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">User Name</th>									
								<th align="center" width="10%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Customer Name</th>
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Customer Type</th>
								<th align="center" width="6%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Deposit</th>
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Num of Inv</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Total Amount</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Total Unpaid</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Status</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Request To</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Delay</th>
								<th align="center" width="9%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Payment Date</th>
								<th align="center" width="14%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Recommend</th>
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999; border-right:1px solid #999999;" nowrape>Done?</th>											
							</thead><tbody>';
	


	//global $mydb;
						$retOut='<form name=frmlpaid >';
						
	/*$sql = "select * from dbo.fn_latelypaid_1m_request_u('".$cid."','".$where."') where status<>'Closed' order by ty,exception desc,remark desc ,state,del,pdate,phone,CustName";
*/

$sql = "select * from dbo.fn_latelypaid_1m_isp_request_u('$cid','$where') order by  remark desc,exception,state,exc desc,del,pdate,phone,CustName";




	if($que = $mydb->sql_query($sql)){				
		
		while($result = $mydb->sql_fetchrow($que)){	
						
			$State = $result['State'];										
			$pdate = $result['pdate'];
			$pd = FormatDate($pdate,5);									
			$phone = $result['Phone'];
			$CustName = $result['CustName'];
			$InvoiceID = $result['InvoiceID'];
			$Status = $result['Status'];
			$Rec = $result['Rec'];
			$AccID = $result['accID'];
			$Payment = $result['Payment'];
			$del= $result['del'];
			$tm = $result['tm'];
			$tu = $result['tu'];
			$numofinv = $result['NumOfInv'];
			$package = $result['package'];
						
			$Remark = $result['Remark'];
			$Exception = $result['Exception'];
			$Exc = $result['Exc'];
			
			if (is_null($result['Expire'])==0)
				$Expire = FormatDate($result['Expire'], 5);
			else 
				$Expire="";
				
			$in=strval($InvoiceID);
			$Rem="      ";
			
			$roll=$result['isrollback'];
			
			if(intval($State)==1 || $Remark=='Active' || $Exception=='VIP') 
				$rtype = "disabled";
			else 
				$rtype = "";	
			
			if ($Exc=='Delay' && (($Expire>=$pd) && (is_null($pd)==0)))  
			{
				$rtype = "disabled";
				$Rec='Before delay';
			}			
			
			$type=strval($Exception.$Remark);
			
			
			if ($chk!='' && $chk!=$type)			
			{
				$retOut.=generateFooter($b1,$b2,$b3,$iLoop);
				$b1=0;
				$b2=0;
				$b3=0;
				$iLoop=0;
			}
			
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			
			
			if ($chk!=$type)	
			{
				$retOut.=$retOutH;
			}
			$chk=strval($Exception.$Remark);																											
			$b1+=$tm;
			$b2+=$tu;
			$b3+=$numofinv;
			
			if ($Exception=='VIP')
			{
				$Rem='Active';
				$Statuschange='Active';
			}
			else
			{
				$Rem=$Remark;
				$Statuschange='Closed';
			}
			
			
			$Done = "<input type=Button Onclick=\"Javascript:doIt(".$InvoiceID.",'".$phone."','".$in.'1'."','".$in."');\" ".$rtype." name=\"btnSubmit\" value=\"Done\" style=\"font-size:8px;\" ID='".$in.'1'."' >";
			

			$taf=$result['t1'];		
			if (intval($taf)==1)
			{
				$Done = "<input type=Button Onclick=\"Javascript:doIt(".$InvoiceID.",'".$phone."','".$in.'1'."','".$in."');\" ".$rtype." name=\"btnSubmit\" value=\"Done\" style=\"font-size:8px;\" ID='".$in.'1'."' >";
			}
			else
			{
				$Done = "<input type=Button Onclick=\"Javascript:doAf(".$InvoiceID.",'".$phone."','".$in.'1'."','".$in."');\" ".$rtype." name=\"btnSubmit\" value=\"Done\" style=\"font-size:8px;\" ID='".$in.'1'."' >";
			}
			
			
			
			
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$iLoop.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$AccID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$phone.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$CustName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Exception.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$package.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$numofinv.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$tm.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$tu.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Status.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">Active</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">  </td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">  '.$pdate.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;"><div id="'.$in.'">'.$Rec.'</div></td>';
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999; border-right:1px solid #999999;">'.$Done.'</td>';
			$retOut .= '</tr>';
			
		}
	}
		$mydb->sql_freeresult();
		$retOut .=generateFooter($b1,$b2,$b3,$iLoop);
		

	
	$Print=date('Y-m-d H:i:s');
	$ret = "<table border=0 cellpadding=0 cellspacing=0 width='100%'>
						";

	$ret .= "<tr><td>";
	$ret .= $retOut;
	$ret .= "</td></tr>";
	$ret .= "</table></form>";
	print $ret;	
	
	
?>