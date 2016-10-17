
<script language="javascript" type="text/javascript" src="ajax_Billing.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/loading.js"></script>
<?php
	
	function generateFooter2($bi1,$bi2)
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

	function showSecond($cid,$where){	
			global $mydb;	
			$chk='';
			$type='';
			$b1=0;
			$b2=0;
			$retOutH='<b>REQUEST RECONNECT AFTER DUE DATE<br><table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#cccccc">
							
								
									<thead>																									
										<th align="center" width="3%" style="border-left:1px solid #999999; border-top:1px solid #999999;">No</th>																
										<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">AccID</th>	
										<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">User Name</th>									
										<th align="center" width="22%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Customer Name</th>
										<th align="center" width="4%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Customer Type</th>
										<th align="center" width="7%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Bill_1</th>
										<th align="center" width="7%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Bill_2</th>
										<th align="center" width="7%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Status</th>
										<th align="center" width="5%" style="border-left:1px solid #999999; border-top:1px solid #999999;" nowrape>Request To</th>
										<th align="center" width="12%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Delay</th>
										<th align="center" width="12%" style="border-left:1px solid #999999; border-top:1px solid #999999;">Recommend</th>
										<th align="center" width="6%" style="border-left:1px solid #999999; border-top:1px solid #999999; border-right:1px solid #999999;" nowrape>Done?</th>											
									</thead><tbody>';
			
		
		
			
								$retOut='';
								
				
			$sql = "select * from dbo.fn_latelypaid_1m_isp_af_delay_f('".$cid."','".$where."') where status<>'Closed' order by remark,status,state,del,expire,phone,custname";
		
			if($que = $mydb->sql_query($sql)){				
				
				while($result = $mydb->sql_fetchrow($que)){	
								
					$State = $result['State'];										
					$pdate = $result['pdate'];
					$pd = FormatDate($pdate,5);											
					$del=$result['del'];
					$phone = $result['Phone'];
					$CustName = $result['CustName'];
					$InvoiceID = $result['InvoiceID'];
					$Status = $result['Status'];
					$Rec = $result['Rec'];
					$AccID = $result['accID'];
					$Payment = $result['Payment'];
					$Month1 = $result['Month1'];
					
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
					
					$type=strval($Exception.$Remark);
					
					if(intval($State)==1 ||  ($del==1) || $Remark=='Not Drop') 
						$rtype = "disabled";
					else 
						$rtype = "";	
					
					if ($Exception=='DELAY' && ($Expire>=$pd))  
					{
						$rtype = "disabled";
						//$Rec='Before delay';
					}			
			
					
					if ($chk!='' && $chk!=$type)			
					{
						$retOut.=generateFooter2($b1,$b2);
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
					
					if ($Month1<=5 and intval($Month2)==0)
					{
						$Remark='Active';
					}
					
					
					$Done = "<input type=Button Onclick=\"Javascript:doAf(".$InvoiceID.",'".$phone."','".strval($InvoiceID).'1'."','".strval($in)."');\" ".$rtype." name=\"btnSubmit\" value=\"Done\" style=\"font-size:8px;\" ID='".strval($in).'1'."' >";
				
		
				
					$retOut .= '<tr>';																																													
					$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$iLoop.'</td>';			
					$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$AccID.'</td>';
					$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$phone.'</td>';			
					$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$CustName.'</td>';
					$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">Normal</td>';
					$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Month2.'</td>';
					$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Month1.'</td>';
					$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Status.'</td>';
					$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">Active</td>';
					$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px solid #999999; border-top:1px solid #999999;">'.$Expire.'</td>';
					$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px solid #999999; border-top:1px solid #999999;"><div id="'.$in.'">'.$Rec.'</div></td>';
					$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid #999999; border-top:1px solid #999999; border-right:1px solid #999999;">'.$Done.'</td>';
					$retOut .= '</tr>';
					
				}
			}
				$mydb->sql_freeresult();
				if ($chk!='')
					$retOut .=generateFooter2($b1,$b2);
				
			$ret = "<table border=0 cellpadding=0 cellspacing=0 width='100%'>
								";
		
			$ret .= "<tr><td>";
			$ret .= $retOut;
			$ret .= "</td></tr>";
			$ret .= "</table>";
			return $ret;
	}

?>