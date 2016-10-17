
<script language="javascript" type="text/javascript" src="ajax_Billing.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/loading.js"></script>
<script language="javascript" type="text/javascript">
	function doIt(invID,ph,Inv,inID){
		if(comm = prompt("Are you sure that you would like to do this "+ph+" ?\n Please enter your comment.")){
			url = "Ins_switch_comment_f.php?invoiceID="+invID+"&phone="+ph+"&remark="+comm+"&mt=" + new Date().getTime();
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
		font-size:12px;
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

	
	function generateFooter($bi1)
	{
		$retOutF='</tbody><tfoot>		
							<tr>																							
								
								<td align="center" colspan="4"><b>TOTAL</b></td>
								<td align="right" >'.number_format($bi1,2).'</td>
								<td align="center" colspan="2" ></td>
							</tr>										
						</tfoot></table>';
		return $retOutF;
		
	}
	
	$retOutH='<br><br><table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
					
						
							<thead>																									
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>							
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Cust ID</th>																
     							<th align="center" width="20%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Customer Name</th>
								<th align="center" width="7%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Type</th>
								<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Bill1</th>
								<th align="center" width="7%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Cycle Date</th>
								<th align="center" width="7%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Remark</th>
								<th align="center" width="14%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Reccomend</th>
								<th align="center" width="6%" style="border-left:1px solid #999999; border-top:1px solid #999999; border-right:1px solid #999999;" nowrape>Done?</th>											
							</thead><tbody>';
	


	//global $mydb;
						$retOut='<form name=frmlpaid >';
						
	$sql = "select * from dbo.fn_latelypaid_1m_ll_f('".$cid."','".$w."') order by remark,transenddate desc,CustName";

	if($que = $mydb->sql_query($sql)){				
		
		while($result = $mydb->sql_fetchrow($que)){	
						
			$State = $result['State'];			
			$billenddate = FormatDate($result['transenddate'],5);							
			$CustName = $result['CustName'];
			$itype=	$result['invoicetype'];		
			$Rec = $result['Rec'];
			$CustID = $result['CustID'];
			$Payment = $result['Payment'];
			$Month1 = $result['Month1'];
			
			$Remark = $result['Remark'];
			
			
			if(intval($State) == 1 ) 
				$rtype = "Disabled";
			else 
				$rtype = "";
				
			$in=strval($CustID);
			$Rem="      ";
			
			$type=strval($Remark);
			
			
			if ($chk!='' && $chk!=$type)			
			{
				$retOut.=generateFooter($b1);
				$b1=0;
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
			$chk=strval($Remark);																											
			$b1+=$Month1;

			$Done = "<input type=Button Onclick=\"Javascript:doIt(".$CustID.",'".$CustName."','".$in.'1'."','".$in."');\" ".$rtype." name=\"btnSubmit\" value=\"Done\" style=\"font-size:10px;\" ID='".$in.'1'."' >";
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$iLoop.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$CustID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$CustName.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$itype.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Month1.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$billenddate.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Remark.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;"><div id="'.$in.'">'.$Rec.'</div></td>';
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999; border-right:1px solid #999999;">'.$Done.'</td>';
			$retOut .= '</tr>';
			
		}
	}
		$mydb->sql_freeresult();
		$retOut .=generateFooter($b1);
		

	

	$ret = "<table border=0 cellpadding=0 cellspacing=0 width='100%'>
							<tr>
								<td align=left>LIST OF LATELY PAID INVOICE<br>
								Expired Date: <b>".$w."</b><br>
								Cycle date: <b>".$cid."</b><br><br>&nbsp;
							</td>
							</tr>
						";

	$ret .= "<tr><td>";
	$ret .= $retOut;
	$ret .= "</td></tr>";
	$ret .= "</table></form>";
	print $ret;	
	
	
?>