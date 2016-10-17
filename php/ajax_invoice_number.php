<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<style>
	td, th{
		font-family:"Courier New", Courier, monospace;
		font-size:14px;
	}
</style>
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	
	$pt = $_GET['pt'];					
	$cid=$_GET['cid'];	
	$st = $_GET['servicename'];	
		
function generateReport($Phone, $pid, $mn,  $sid1, $cid){
	global $mydb;
	
	$ct=$_GET['ct'];
	$sid1 = $_GET['serviceid'];
	
	
	$pco = "in (16,17)";
	$cmtstaff = "in (9)";
	$internal = "in (18,19,79)";
	
	
	If ($mn <> "GRAND TOTAL" ){	
		$city = "City";
	} else {
		$city = "Messenger";
	}
	
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle" colspan=2>	';
	if($pid > 0)
		$retOut .= 	'	<font size=2>Messenger Name: <b>'.$mn.'&nbsp;&nbsp;[ ID='.$pid.' ]</b><br> 
						Telephone: '.$Phone.'</font>
					';
						
	else
		$retOut .= ' <b>&nbsp;&nbsp;'.$pt.'</b><br> ';											
	$retOut .=	' </td>					
				</tr> 
				<tr>
					<td width=10>&nbsp;</td>
					<td>
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																									
								<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>																
								<th align="center" width="7%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">'.$city.'</th>	
								<th align="center" width="7%" style="border-left:1px dotted #999999; border-top:1px solid #999999;">Khan</th>									
								<th align="center" width="7%" style="border-left:1px dotted #999999; border-top:1px solid #999999;" nowrape>Sangkat</th>
								<th align="center" width="4%" style="border-right:1px solid #999999;border-left:1px dotted #999999; border-top:1px solid #999999;">Invoice Number</th>
							</thead>
							<tbody>';

If ($mn <> "GRAND TOTAL" ){	
	$sql =	"	
				Select City, Khan, Sangkat,Sangkatid, sum(InvoiceNumber) Number from tblMessengerSite where 
			";
if($sid1 == 2){
	$sql .= " ServiceID = 2 ";
}elseif($sid1 == 4){
	$sql .= " ServiceID = 4 ";
}elseif($sid1 == 1){	
	$sql .= " ServiceID in (1, 3, 8) ";
}
	
	$sql .= " and convert(varchar,BillEndDate,112) = '".$cid."'";			
	$sql .= " and MessengerID = ".$pid;
	$sql .= " Group by  City, Khan, Sangkat, Sangkatid";
	$sql .= " Order by  City, Khan, Sangkat";
}else {

	$sql = "	
				Select MessengerID, name 'City', 'All' Khan, 'All' Sangkat, sum(InvoiceNumber) Number from tblMessengerSite where 
			";
if($sid1 == 2){
	$sql .= " ServiceID = 2 ";
}elseif($sid1 == 4){
	$sql .= " ServiceID = 4 ";
}elseif($sid1 == 1){	
	$sql .= " ServiceID in (1, 3, 8) ";
}
	
	$sql .= " and convert(varchar,BillEndDate,112) = '".$cid."'";			
	$sql .= " Group by  MessengerID,name";
	$sql .= " Order by  MessengerID";


}	

	if($que = $mydb->sql_query($sql)){				
		$iLoop = 0;
		$TotalInv = 0;
		
		while($result = $mydb->sql_fetchrow($que)){																															
											
			$City = $result['City'];										
			$Khan = $result['Khan'];															
			$Sangkat = $result['Sangkat'];
			$Sangkatid = $result['Sangkatid'];											
			$MessID = $result['MessengerID'];
			$Number = $result['Number'];										
			

			$TotalInv += $Number;

If ($mn == "GRAND TOTAL" ){
	$skid = "&skid=1";
	$tskid = "&skid=1";
	$CityA = "ALL";
	$link = "ajax_invoice_All_mes_sangkat.php";
} else {
	$CityA = $City;
	$skid = "&skid=0";
	$tskid = "&skid=1";
	$MessID = $pid;
	$link = "ajax_invoice_number_sangkat.php";
}			
			
			$linkToInv = "<a href='../php/".$link."?&city=".$CityA."&kn=".$Khan."&skn=".$Sangkat.$skid."&ms=".$City."&mid=".$MessID."&ct=".$ct."&servicename=".$_GET['servicename']."&sid1=".$sid1."&sk=".$Sangkatid."&cid=".$cid."'>".$Number."</a>";	
			
			
			$iLoop++;																		
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			
			$retOut .= '<tr>';																																													
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px dotted #999999;">'.$iLoop.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$City.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Khan.'</td>';			
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted #999999; border-top:1px dotted #999999;">'.$Sangkat.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted #999999; border-right:1px solid #999999; border-top:1px dotted #999999;">'.$linkToInv.'</td>';
			$retOut .= '</tr>';
				
		}
	}
		$mydb->sql_freeresult();


If ($mn == "GRAND TOTAL" ){
	$MessID = 0;
	$linkall = "ajax_invoice_All_mes_sangkat.php";
}
else {
	$linkall = "ajax_invoice_All_sangkat.php";
}

			$linkToInvAll = "<a href='../php/".$linkall."?&city=All&kn=All&skn=All&ms=".$mn."&mid=".$MessID.$tskid."&ct=".$ct."&servicename=".$_GET['servicename']."&sid1=".$sid1."&sk=".$Sangkatid."&cid=".$cid."'>".$TotalInv."</a>";	
			
		
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="center" colspan=4 style="border-left:1px solid #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;">Total</td>											
											<td align="right" style="border-left:1px solid #999999; border-top:1px dotted #999999; border-bottom:1px solid #999999;border-right:1px solid #999999;">'.$linkToInvAll.'</td>									
										</tr>
									</tfoot>												
								</table>						
							</td>
						</tr>
					</table>';
	return $retOut;
}// end function	

	$retfun = "<table border=0 cellpadding=0 cellspacing=0 width='100%'>
							<tr>
								<td align=left>
									<table width='100%'>
										<tr>
											<td align=left class=formtitle>
												<font size=3><b>Invoices Issued by Messenger Summary</b><br>
												Service type: <b>".$st."</b><br>
												Cycle date: <b>".$ct."</b>&nbsp;</font>
											</td>
											<td align=right valign=bottom class=formtitle>
												Print on: ".date("Y M d H:i:s")."
											</td>
										</tr>
									</table>
								</td>
							
							</tr>
						";
		
	//$retfun .= "<tr><td><br /></td></tr>";
		
		$sql = "	
					select Distinct ms.MessengerID, me.Name 'Messenger', me.Phone from tblMessengerSite ms
					join tlkpMessenger me on me.MessengerID = ms.MessengerID
					where StatusID = 1
					order by MessengerID
					
				";
				
	if($que = $mydb->sql_query($sql)){
		while($result = $mydb->sql_fetchrow($que)){
			$MessengerID = $result['MessengerID'];
			$Messenger = $result['Messenger'];
			$Phone = $result['Phone'];
			$retfun .= "<tr><td>";
			$retfun .= generateReport($Phone, $MessengerID, $Messenger, $sid1, $cid);
			$retfun .= "</td></tr>";
		}
	}
	
	$retfun .= "<tr><td><br /></td></tr>";
	$retfun .= "<tr><td>";
		$retfun .= generateReport('023998584', 1, 'GRAND TOTAL', $sid1, $cid);
	$retfun .= "</td></tr>";
	
	$retfun .= "</table>";
	print $retfun;	
	
?>