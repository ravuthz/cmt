<?php
	require_once("./common/agent.php");	
	require_once("./common/functions.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	
?>	
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>MESSENGER REPORT</b></td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>
								<th align="center">Name</th>
								<th align="center">Address</th>
								<th align="center">Phone</th>
								<th align="center">Email</th>
								<th align="center">ID/Passport</th>
								<th align="center">DOB</th>
								<th align="center">Status</th>
								<th align="center">Remark</th>
								<th align="center">Edit</th>																				
							</thead>
							<tbody>
								<?php
										$sql = "select MessengerID, Salutation, Name, Address, Phone, Email, Passport, DOB, Remark, statusid
														from tlkpMessenger";

									if($que = $mydb->sql_query($sql)){										
										$iLoop = 0;
										while($result = $mydb->sql_fetchrow()){																
											$MessengerID = $result['MessengerID'];																
											$Salutation = $result['Salutation'];
											$Name = $result['Name'];											
											$Address = $result['Address'];																
											$Phone = $result['Phone'];
											$Email = $result['Email'];
											$Passport = $result['Passport'];
											$DOB = $result['DOB'];
											$Remark = $result['Remark'];
											$statusid = $result['statusid'];
											$MessName = $Salutation."".$Name;			
											$edit = "<a href='./?mid=".$MessengerID."&pg=238'>Edit</a>";		
											if(intval($statusid) == 1)
												$st = "<font color=green>Enable</font>";
											else
												$st = "<font color=red>Disable</font>";																		
											$iLoop++;															
											if(($iLoop % 2) == 0)
												$style = "row1";
											else
												$style = "row2";
											print '<tr>';	
											print '<td class="'.$style.'" align="right">'.$iLoop.'</td>';								
											print '<td class="'.$style.'" align="left">'.$MessName.'</td>';
											print '<td class="'.$style.'" align="left">'.$Address.'</td>';														
											print '<td class="'.$style.'" align="left">'.$Phone.'</td>';								
											print '<td class="'.$style.'" align="left">'.$Email.'</td>';											
											print '<td class="'.$style.'" align="left">'.$Passport.'</td>';
											print '<td class="'.$style.'" align="left">'.FormatDate($DOB, 3).'</td>';
											print '<td class="'.$style.'" align="left">'.$st.'</td>';
											print '<td class="'.$style.'" align="left">'.$Remark.'</td>';
											print '<td class="'.$style.'" align="left">'.$edit.'</td>';
											print '</tr>';
										}
									}
									$mydb->sql_freeresult();	
								?>
							</tbody>																	
						</table>						
					</td>
				</tr>
			</table>
		</td>
	</tr>						
</table>