
<script language="javascript" type="text/javascript" src="ajax_Billing.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/loading.js"></script>
<script language="javascript" type="text/javascript">

	function doIt(invID,ph,Inv,inID,accID,dnow){
		dd= new Date().getFullYear( ) + "/" + new Date().getMonth() + "/" + new Date().getDay() + " " + new Date().getHours()+ ":" + new Date().getMinutes()+ ":" + new Date().getSeconds() ;
		if(comm = prompt("Are you sure that you would like to do this "+ph+" ?\n Please enter your comment.","disconn. on " + dd)){
			url = "Ins_switch_comment_f.php?invoiceID="+invID+"&phone="+ph+"&remark="+comm+"&ai="+accID+"&dp="+dnow+"&mt=" + new Date().getTime();
			di="";
			DoAction(url);
			document.getElementById(inID).innerHTML=comm;
			document.getElementById(Inv).disabled=true;
		}
	}
	
</script>
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
	
	function generateFooter($bi1,$bi2,$bi3)
	{
		$retOutF='</tbody><tfoot>		
							<tr>																							
								<td align="center" colspan="6"><b>TOTAL</b></td>
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
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Phone</th>									
								<th align="center" width="8%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Cust Name</th>
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Type</th>
								<th align="center" width="10%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Package</th>
								<th align="center" width="2%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Inv</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Amount</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Unpaid</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Status</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Request To</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Delay</th>
								<th align="center" width="7%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Payment Date</th>
								<th align="center" width="10%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Recommend</th>
								<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999; border-right:1px solid #999999;" nowrape>Done?</th>											
							</thead><tbody>';
	


	//global $mydb;
						$retOut='<form name=frmlpaid >';
						
	//$sql = "select * from dbo.fn_latelypaid_1m_f('$cid','$where') order by remark desc,exception,state,del ,phone,CustName";
	
	$sql = "exec spDebt2monts '$cid','ISP'";
	
	if($que = $mydb->sql_query($sql)){				
		
		while($result = $mydb->sql_fetchrow($que)){	
						
			$State = $result['State'];										
			
			$del = $result['del'];
			$InvoiceID = $result['InvoiceID'];
			$tm = $result['tm'];
			$tu = $result['tu'];
			$phone = $result['Phone'];
			$CustName = $result['CustName'];			
			$Status = $result['Status'];
			$Rec = $result['Rec'];
			$AccID = $result['accID'];
			$Payment = $result['Payment'];
			$numofinv = $result['NumOfInv'];
			$package = $result['package'];
			$pdate = $result['paymentdate'];
			
			$Remark = $result['Remark'];
			$Exception = $result['Exception'];
			
			if(intval($State) == 1 || $Exception=='VIP' || $Remark=='Active') 
				$rtype = "Disabled";
			else 
				$rtype = "";
				
			if (is_null($result['Expire'])==0)
				$Expire = FormatDate($result['Expire'], 5);
			else 
				$Expire="";
				
			$in=strval($InvoiceID);
			$Rem="      ";
			
			$type=strval($Exception.$Remark);
			
			
			if ($chk!='' && $chk!=$type)			
			{
				$retOut.=generateFooter($b1,$b2,$b3);
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
				$Rem='Active';
			else
				$Rem=$Remark;
			
			
			if(intval($del) == 1) 
				$rtype = "Disabled";
				
			$dnow=date('Y-m-d');			
			$Done = "<input type=Button Onclick=\"Javascript:doIt(".$InvoiceID.",'".$phone."','".$in.'1'."','".$in."',".intval($AccID).",'".$dnow."');\" ".$rtype." name=\"btnSubmit\" value=\"Done\" style=\"font-size:8px;\" ID='".$in.'1'."' >";
						
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$iLoop.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$AccID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$phone.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$CustName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Exception.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$package.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$numofinv.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$tm.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$tu.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Status.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Rem.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">  </td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">  '.$pdate.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;"><div id="'.$in.'">'.$Rec.'</div></td>';
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999; border-right:1px solid #999999;">'.$Done.'</td>';
			$retOut .= '</tr>';
			
		}
	}
		$mydb->sql_freeresult();
		$retOut .=generateFooter($b1,$b2,$b3);
		

	
	$Print=date('Y-m-d H:i:s',strtotime("-1 hours"));
	if (is_null($w)==0)
	{
		$w=$cid;
	}


	$ret = "<table border=0 cellpadding=0 cellspacing=0 width='100%'>
							<tr>
								<td align=left>LIST OF LATELY UNPAID INVOICE<br>
								SERVICE     : <b>INTERNET & EMAIL</b><br>
								Expired Date: <b>".$w."</b><br>
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