<?php
	
	$filename = "close_drawer_report";
	
	if ($type == 'csv') {
			$filename  .= '.xls';
			//$mime_type = 'text/comma-separated-values';		
			$mime_type = 'application/vnd.ms-excel';	
	} elseif ($type == 'xls') {
			$filename  .= '.xls';
			$mime_type = 'application/vnd.ms-excel';
	} elseif ($type == 'xml') {
			$filename  .= '.xml';
			$mime_type = 'text/xml';	
	} elseif ($type == 'word') {
			$filename  .= '.doc';
			$mime_type = 'application/vnd.ms-word';		
	} elseif ($type == 'pdf') {
			$filename  .= '.pdf';
			$mime_type = 'application/pdf';
	}
	
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="' . $filename . '"');	
	
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	?>


	<table width="657" border="0" cellpadding="0" cellspacing="5" bordercolor="#999999" style="border-collapse:collapse">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" bordercolor="#999999" style="border-collapse:collapse" align="left" width="100%">
				 				
				<tr>
					<td width="100%" colspan="2">
							<table border="0" bordercolor="#999999" style="border-collapse:collapse" cellpadding="3" cellspacing="0" align="left" width="100%" >																
								
								
							  <?php
									$sql="select DrawerID, Min(PaymentDate) 'StartTransaction',
													 Max(PaymentDate) 'EndTransaction', Cashier
											
											from tblCustCashDrawer cd
											where DrawerID=$DID
											group by DrawerID, Cashier";
									$result=$mydb->sql_query($sql);
									while($row2=$mydb->sql_fetchrow($result)){
										$drawerid=intval($row2["DrawerID"]);
										$cashier=stripslashes($row2["Cashier"]);
										$starttransaction=stripslashes($row2["StartTransaction"]);
										$endtransaction=stripslashes($row2["EndTransaction"]);
										
								
							  ?>
							  							  
							   <?php } ?>
							</table>					  
						</td>
				</tr>			
				<tr>
					<td colspan="2">
						<table width="657" border="0" cellpadding="0" cellspacing="5" bordercolor="#999999" style="border-collapse:collapse">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" bordercolor="#999999" style="border-collapse:collapse"class="formbg" align="left" width="100%">
				<tr>
					<td width="76%" align="left" class="formtitle"><b>Before Close Cash Drawer Report</b></td>
				    <td width="24%" align="right" class="formtitle"></td>
				</tr> 				
				<tr>
					<td colspan="2">
							<table border="0" bordercolor="#999999" style="border-collapse:collapse" cellpadding="3" cellspacing="0" align="left" width="100%" bgcolor="#feeac2">																
								
								
							  <?php
									$sql="select DrawerID, Min(PaymentDate) 'StartTransaction',
													 Max(PaymentDate) 'EndTransaction', Cashier
											
											from tblCustCashDrawer cd
											where DrawerID=$DID
											group by DrawerID, Cashier";
									$result=$mydb->sql_query($sql);
									while($row2=$mydb->sql_fetchrow($result)){
										$drawerid=intval($row2["DrawerID"]);
										$cashier=stripslashes($row2["Cashier"]);
										$starttransaction=stripslashes($row2["StartTransaction"]);
										$endtransaction=stripslashes($row2["EndTransaction"]);
										
								
							  ?>
							  
							  <tr>
								  <td colspan="4"> &nbsp;&nbsp;&nbsp;Drawer: <b><?php echo $drawerid;?></b>, Cashier: <b><?php echo $cashier;?></b></td>
							  </tr>
								<tr>
								  <td colspan="4"> &nbsp;&nbsp;&nbsp;Start Transaction:  <b><?php echo $starttransaction;?></b></td>							      
						     </tr>
								 <tr>
								 	<td colspan="4"> &nbsp;&nbsp;&nbsp;End Transaction: <b><?php echo $endtransaction;?></b></td>									
								 </tr>
							   <?php } ?>
							</table>					  </td>
				</tr>			
								<tr>
					<td colspan="2">
							<table border="0" style="border-collapse:collapse" cellpadding="3" cellspacing="0" align="left" width="100%" bgcolor="#ffffff" bordercolor="">																
								<tr>
									<td></td>
								  <td colspan="4" style="border-bottom:1px solid;">&nbsp;</td>
							  </tr>
							  <tr>
									<td>&nbsp;</td>
									<td colspan="2" style="border-left:1px solid;">&nbsp;&nbsp;::&nbsp;<b>Revenue From System</b></td>
									<td style="border-left:1px solid;">&nbsp;</td>
									<td style="border-left:1px solid; border-right:1px solid">&nbsp;</td>
								</tr>
								<?php 
							  	  $sum1=0.0;
								  $sum2=0.0;
								  $sql="
								  select DrawerID, TransactionModeID, sum(PaymentAmount) 'PaymentAmount', TransactionName
from (
	
	select cd.DrawerID, -1 'TransactionModeID' , sum(PaymentAmount) 'PaymentAmount', 'Others Payment' 'TransactionName'
	from tblCustCashDrawer cd
			left join 
			tlkpTransaction t
			on
			cd.TransactionModeID=t.TransactionID
			left join
			tblcustomerinvoice i
			on cd.invoiceid = i.invoiceid
	where cd.DrawerID=$DID	
	and
	isnull(i.invoicetype,0) = 3 
	and
	cd.TransactionModeID in (1,3,4,5,6)
	and isnull(cd.IsRollback,0)=0
	group by cd.DrawerID, cd.TransactionModeID, t.TransactionName
	
	union
	select cd.DrawerID, cd.TransactionModeID, sum(PaymentAmount) 'PaymentAmount', t.TransactionName
	from tblCustCashDrawer cd
			left join 
			tlkpTransaction t
			on
			cd.TransactionModeID=t.TransactionID
			left join
			tblcustomerinvoice i
			on cd.invoiceid = i.invoiceid
	where cd.DrawerID=$DID
	and 
	isnull(i.invoicetype,0) <> 3 
	and
	cd.TransactionModeID in (1,3,4,5,6)
	and isnull(cd.IsRollback,0)=0
	group by cd.DrawerID, cd.TransactionModeID, t.TransactionName
	union
	select $DID 'DrawerID', t.TransactionID, 0 'PaymentAmount', TransactionName
	from tlkpTransaction t
	where t.TransactionID in (1,3,4,5,6)
	union
	select $DID 'DrawerID', -1, 0 'PaymentAmount', 'Others Payment' 'TransactionName'

) d
group by 
DrawerID, TransactionModeID, TransactionName
Union
select cd.DrawerID,'0'  'TransactionModeID', sum(PaymentAmount) 'PaymentAmount', 'Total' 'TransactionName'
from tblCustCashDrawer cd
		left join 
		tlkpTransaction t
		on
		cd.TransactionModeID=t.TransactionID
where cd.DrawerID=$DID
and 
cd.TransactionModeID in (1,3,4,5,6)
and isnull(cd.IsRollback,0)=0
group by cd.DrawerID";
									$result=$mydb->sql_query($sql);
									$i=0;
									while($row=$mydb->sql_fetchrow($result)){
									$i++;										
									if($row['TransactionName']!="Total"){
							  ?>															
								<tr>
								  <td width="6%">&nbsp;</td>
									<td width="6%" style="border-left:1px solid;">&nbsp;</td>
								  <td><?php echo $i.". ".stripslashes($row['TransactionName']);?> : </td>
								  <td style="border-left:1px solid;"  width="130" align="right">
								  <?php
									   	echo FormatCurrency($row['PaymentAmount']);
								   ?>
								   </td>
									 <td style="border-left:1px solid;border-right:1px solid;" width="130" align="right">&nbsp;
									 	
									 </td>  
									 <td width="6%">&nbsp;</td>
								</tr>
								  <?php 
									}else{
									
									?>									
								<tr>
								  <td width="6%">&nbsp;</td>
									<td width="6%" style="border-left:1px solid;">&nbsp;</td>
								  <td><b><?php echo stripslashes($row['TransactionName']);?></b></td>
								  <td style="border-left:1px solid;">&nbsp;</td>  
								  <td width="130" align="right" style="border-left:1px solid; border-right:1px solid"><b><?php
									   	echo FormatCurrency($row['PaymentAmount']);
								   ?></b></td>
								</tr>
									<?php			
									$sum1=doubleval($row['PaymentAmount']);			
									}
								}// end while
									
								?>
		
							  <?php 
								 
							$sql="select DrawerID, TransactionModeID, sum(PaymentAmount) 'PaymentAmount', TransactionName
from (
select cd.DrawerID, cd.TransactionModeID, sum(PaymentAmount) 'PaymentAmount', t.TransactionName
										from tblCustCashDrawer cd
												left join 
												tlkpTransaction t
												on
												cd.TransactionModeID=t.TransactionID
										where cd.DrawerID=$DID
										and 
										cd.TransactionModeID in (2,7,8,9,10,14,15)
										group by cd.DrawerID, cd.TransactionModeID, t.TransactionName
union
select $DID 'DrawerID', t.TransactionID, 0 'PaymentAmount', TransactionName
from tlkpTransaction t
where t.TransactionID in (2,7,8,9,10,14,15)
) d
group by 
DrawerID, TransactionModeID, TransactionName
										union
										select cd.DrawerID, '' TransactionModeID, sum(PaymentAmount) 'PaymentAmount', 'Total' 'TransactionName'
										from tblCustCashDrawer cd
												left join 
												tlkpTransaction t
												on
												cd.TransactionModeID=t.TransactionID
										where cd.DrawerID=$DID
										and 
										cd.TransactionModeID in (2,7,8,9,10,15,14)
										group by cd.DrawerID
										";
									$result=$mydb->sql_query($sql);
									$i=0;
									$n = 0;
									while($row=$mydb->sql_fetchrow($result)){
									 	$n++;
										if($i==0){
										?>
										<tr>
										  <td>&nbsp;</td>
										  <td colspan="2" style="border-left:1px solid;">&nbsp;&nbsp;::&nbsp;<b>Refund from system</b></td>
											<td style="border-left:1px solid;">&nbsp;</td>
											<td style="border-left:1px solid; border-right:1px solid">&nbsp;</td>
									  </tr>
										<?php
										$i++;
										}	
									 if($row["TransactionName"]!="Total"){								
							  ?>							
						
								<tr>
								  <td width="6%">&nbsp;</td>
								  <td width="6%" style="border-left:1px solid;">&nbsp;</td>
								  <td><?php echo $n.". ".stripslashes($row['TransactionName']);?> : </td>
								  <td style="border-left:1px solid;" align="right">
								  <?php
									   	echo FormatCurrency($row['PaymentAmount']);
								   ?>
								   </td>  
									 <td align="right" style="border-left:1px solid; border-right:1px solid;">&nbsp;</td>
								</tr>
								  <?php 
									}else{
									?>
									
								<tr>
								  <td width="6%">&nbsp;</td>
								  <td width="6%" style="border-left:1px solid;">&nbsp;</td>
								  <td><b><?php echo stripslashes($row['TransactionName']);?></b></td>
								  <td style="border-left:1px solid;">&nbsp;</td>  
								  <td style="border-left:1px solid; border-right:1px solid;" align="right"><b>(<?php
									   	echo FormatCurrency($row['PaymentAmount']);
										$sum2=doubleval($row['PaymentAmount']);
								   ?></b>)</td>
								</tr>
									<?php
									}
								}
								?>	
								<?php
									if(($sum1-$sum2) > 0){
										$totalonhand = "<font color=blue><b>".FormatCurrency($sum1-$sum2)."</b></font>";
									}else{
										$totalonhand = "<font color=red>(<b>".FormatCurrency(abs($sum1-$sum2))."</b>)</font>";
									}
								?>								
								<tr>
								  <td>&nbsp;</td>
									<td colspan="2" style="border-left:1px solid;">&nbsp;&nbsp;::&nbsp;<b>Net cash on hand</b></td>
									<td style="border-left:1px solid;">&nbsp;</td> 
								  <td align="right" style="border-left:1px solid; border-right:1px solid;">
										<?php echo $totalonhand; ?>
									</td>
								</tr>

							  <?php 
								 
							$sql="select DrawerID, TransactionModeID, sum(PaymentAmount) 'PaymentAmount', TransactionName
from (
select cd.DrawerID, cd.TransactionModeID, sum(PaymentAmount) 'PaymentAmount', t.TransactionName
										from tblCustCashDrawer cd
												left join 
												tlkpTransaction t
												on
												cd.TransactionModeID=t.TransactionID
										where cd.DrawerID=$DID
										and 
										cd.TransactionModeID in (11,12,13)
										group by cd.DrawerID, cd.TransactionModeID, t.TransactionName
union
select $DID 'DrawerID', t.TransactionID, 0 'PaymentAmount', TransactionName
from tlkpTransaction t
where t.TransactionID in (11,12,13)
) d
group by 
DrawerID, TransactionModeID, TransactionName";
									$result=$mydb->sql_query($sql);
									$i=0;
									$n=0;
									$totalother = 0;
									while($row=$mydb->sql_fetchrow($result)){	
									$totalother += floatval($row['PaymentAmount']);
									$n++;
									if($i==0){
									?>
								<tr>
								  <td>&nbsp;</td>
									<td colspan="2" style="border-left:1px solid;">&nbsp;&nbsp;::&nbsp;<b>Other transaction</b></td>
									<td style="border-left:1px solid;">&nbsp;</td> 
								  <td align="right" style="border-left:1px solid; border-right:1px solid">&nbsp;</td>
								</tr>
									<?php
									$i++;
									}									
							  ?>							
						
								<tr>
								  <td width="6%">&nbsp;</td>
								  <td width="6%" style="border-left:1px solid;">&nbsp;</td>
								  <td><?php echo $n.".".stripslashes($row['TransactionName']);?> : </td>
								  <td align="right" style="border-left:1px solid;">
								  <?php
									   	echo FormatCurrency($row['PaymentAmount']);
								   ?>
								  </td>
									<td style="border-left:1px solid; border-right:1px solid">
									</td>  
								</tr>
								  <?php 
									}									
								?>	
								<tr>
								  <td width="6%">&nbsp;</td>
								  <td width="6%" style="border-left:1px solid;">&nbsp;</td>
								  <td><b>Total</b></td>
								  <td style="border-left:1px solid;">&nbsp;</td>  
								  <td style="border-left:1px solid; border-right:1px solid;" align="right"><b><?php
									   	echo FormatCurrency($totalother);										
								   ?></b></td>
								</tr>
								<tr>
									<td></td>
								  <td colspan="4" style="border-top:1px solid;">&nbsp;</td>
							  </tr>
							</table>					  
						</td>
				</tr>			
			</table>	
		</td>
	</tr>
</table>
					</td>	
				</tr>			
			</table>		</td>
	</tr>
	<tr>
	  <td  colspan="4">&nbsp;</td>
	  </tr>
	<tr>
	  <td  colspan="4">&nbsp;</td>
	  </tr>
	<tr>
	<td  colspan="3" align="right" style="padding-right:50px"><b>Approved By</b></td>
	<td></td>
	</tr>
</table>

