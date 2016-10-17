
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
	
	function generateFooter($bi1,$bi2)
	{
		$retOutF='</tbody><tfoot>		
							<tr>																							
								
								<td align="center" colspan="5"><b>TOTAL</b></td>
								<td align="right" >'.number_format($bi2,2).'</td>
								<td align="right" >'.number_format($bi1,2).'</td>
								<td align="center" colspan="5" ></td>
							</tr>										
						</tfoot></table>';
		return $retOutF;
		
	}
	
	$retOutH='<br><br><table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#cccccc">
					
						
							<thead>																									
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>																
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">AccID</th>	
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">User Name</th>									
								<th align="center" width="21%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Customer Name</th>
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Customer Type</th>
								<th align="center" width="7%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Bill_1</th>
								<th align="center" width="7%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Bill_2</th>
								<th align="center" width="18%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Payment Date</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Request To</th>
								<th align="center" width="12%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Recommend</th>
								<th align="center" width="6%" style="border-left:1px solid #999999; border-top:1px solid #999999; border-right:1px solid #999999;" nowrape>Done?</th>											
							</thead><tbody>';
	


	//global $mydb;
						$retOut='<form name=frmlpaid >';
						
	$sql = "select * from dbo.fn_latelypaid_1m_isp_request('".$cid."','".$w."') where status<>'Closed' order by ty,exception desc,remark desc ,state,del,pdate,phone,CustName";

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
			$Month1 = $result['Month1'];
			$del= $result['del'];
			
			if (is_null($result['Month2'])==1 || ($result['Month2']==''))
				$Month2=0;
			else
				$Month2 = $result['Month2'];
			
			$Remark = $result['Remark'];
			$Exception = $result['Exception'];
			
			if (is_null($result['Expire'])==0)
				$Expire = FormatDate($result['Expire'], 5);
			else 
				$Expire="";
				
			$in=strval($InvoiceID);
			$Rem="      ";
			
			$roll=$result['isrollback'];
			
			if(intval($State)==1 || $Exception=='VIP' || $Remark=='Active' || intval($del)==1) 
				$rtype = "disabled";
			else 
				$rtype = "";	
					
			if ($Exception=='DELAY' && ($Expire>=$pd))  
			{
				$rtype = "disabled";
				$Rec='Before delay';
			}			
			
			$type=strval($Exception.$Remark);
			
			
			if ($chk!='' && $chk!=$type)			
			{
				$retOut.=generateFooter($b1,$b2);
				$b1=0;
				$b2=0;
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
			$b1+=$Month1;
			$b2+=$Month2;
			
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
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$iLoop.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$AccID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$phone.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$CustName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Exception.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Month2.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Month1.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.formatdate($pdate,7).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">Active</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;"><div id="'.$in.'">'.$Rec.'</div></td>';
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999; border-right:1px solid #999999;">'.$Done.'</td>';
			$retOut .= '</tr>';
			
		}
	}
		$mydb->sql_freeresult();
		$retOut .=generateFooter($b1,$b2);
		

	
	$Print=date('Y-m-d H:i:s');
	$ret = "<table border=0 cellpadding=0 cellspacing=0 width='100%'>
						";

	$ret .= "<tr><td>";
	$ret .= $retOut;
	$ret .= "</td></tr>";
	$ret .= "</table></form>";
	print $ret;	
	
	
?>