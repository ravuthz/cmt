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
	if(isset($smt) && (!empty($smt)) && ($smt == "save800") &&  ($pg == 800)){
		$txtName = FixQuotes($txtName);
		$txtSupplierName = FixQuotes($txtSupplierName);
		$selServiceID = FixQuotes($selServiceID);
		$sql = "INSERT INTO tlkpCPE(CPEName, ServiceID, Active, SupplierName)
									VALUES('".$txtName."', '".$selServiceID."', 1, '".$txtSupplierName."')";
		if($mydb->sql_query($sql)){
			$retOut = $myinfo->info("Successfully add new CPE");
		}else{
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to add new CPE.", $error['message']);
		}
	}
	
?>
<script language="javascript">
	function ValidateForm(){
		if(fCPE.txtName.value == ""){
			alert("Please enter CPE name");
			fCPE.txtName.focus();
			return;
		}else{
			fCPE.btnNext.disabled = true;
			fCPE.smt.value = "save800";
			fCPE.submit();
		}
	}
</script>
<table border="0" align="left" cellpadding="3" cellspacing="5">
	<tr>
		<td>
			<form name="fCPE" method="post" action="./">
			<table border="0" cellpadding="0" cellspacing="5" align="left">
				<tr>		
					<td valign="top" align="left">
						<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="center" width="100%">
							<tr>
							 <td align="left" class="formtitle" height="18"><b>CPE ADMINISTRATION</b></td>
							 </tr>
							<tr>
							 <td valign="top">
								 <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">					 					 
								<tr>
									<td align="left">CPE name:</td>
									<td align="left">
										<input type="text" tabindex="1" name="txtName" class="boxenabled" size="50" maxlength="50" value="<?php print $txtName; ?>" />
											<img src="./images/required.gif" border="0" />
									</td>
								</tr>					 
								<tr>
									<td align="left" valign="top">Supplier name:</td>
									<td align="left">
										<input type="text" tabindex="2" name="txtSupplierName" class="boxenabled" size="50" maxlength="50" value="<?php print $txtSupplierName; ?>" />								
									</td>
								</tr>
								<tr>
									<td align="left">Service type:</td>
									<td>
										<select name="selServiceID" class="boxenabled" tabindex="3" style="width:190px">								
											<?php
												$sql = "SELECT ServiceID, ServiceName from tlkpService order by ServiceName";
												// sql 2005
												
												$que = $mydb->sql_query($sql);									
												if($que){
													while($rst = $mydb->sql_fetchrow($que)){	
														$ServiceID = $rst['ServiceID'];
														$ServiceName = $rst['ServiceName'];
														if($selServiceID == $ServiceID) 
															$sel = "selected";
														else
															$sel = "";
														print "<option value='".$ServiceID."' ".$sel.">".$ServiceName."</option>";
													}
												}
												$mydb->sql_freeresult();
											?>
										</select>
									</td>
								</tr>
								 <tr> 				  
									<td align="center" colspan="2">
										<input type="reset" tabindex="4" name="reset" value="Reset" class="button" />
										<input type="button" tabindex="5" name="btnNext" value="Submit" class="button" onClick="ValidateForm();" />						
									</td>
								 </tr>		
								 <?php
										if(isset($retOut) && (!empty($retOut))){
											print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
										}
									?>			
								 </table>
							 </td>
							 </tr>			   
						 </table>
						</td>
					</tr>
				</table>
				<input type="hidden" name="pg" id="pg" value="800">
				<input type="hidden" name="smt" id="smt" value="">
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="center" width="100%">
				<tr>
				 <td align="left" class="formtitle" height="18"><b>CPE</b></td>
				</tr>
				<tr>
					<td align="left">
						<table border="1" cellpadding="3" width="100%" cellspacing="0" align="center" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>					
								<th>CPE Code.</th>
								<th>CPE name</th>
								<th>Service type</th>
								<th>Status</th>
								<th>Supplier</th>					
								<th>Modify</th>
							</thead>
							<tbody>
							<?php								
								$iLoop = 0;
								// Get cpe information
								$sql = "select c.CPEID, c.CPEName, c.Active, c.SupplierName, s.ServiceName 
												from tlkpCPE c inner join tlkpService s on c.ServiceID = s.ServiceID";
			
								if($que = $mydb->sql_query($sql)){				
									while($result = $mydb->sql_fetchrow($que)){
										$CPEID = $result['CPEID'];
										$CPEName = $result['CPEName'];
										$Active = $result['Active'];
										$SupplierName = $result['SupplierName'];
										$ServiceName = $result['ServiceName'];
										if($Active)
											$status = "<font color = 'green'><b>Enable</b></font>";
										else
											$status = "<font color = 'red'><b>Disable</b></font>";
										$link = "<a href='./?CPEID=".$CPEID."&pg=801'>Edit</a>";
										$iLoop++;
										if(($iLoop % 2) == 0)
											$style = "row1";
										else
											$style = "row2";
										print '<tr>';	
										print '<td class="'.$style.'" align="left">'.$CPEID.'</td>';
										print '<td class="'.$style.'" align="left">'.$CPEName.'</td>';
										print '<td class="'.$style.'" align="left">'.$ServiceName.'</td>';
										print '<td class="'.$style.'" align="left">'.$status.'</td>';									
										print '<td class="'.$style.'" align="left">'.$SupplierName.'</td>';	
										print '<td class="'.$style.'" align="left">'.$link.'</td>';	
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
<?php
# Close connection
$mydb->sql_close();
?>	
