<?php
	require_once("./common/agent.php");
	require_once("./common/class.audit.php");	
	require_once("./common/functions.php");	
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	
	if(!empty($smt) && isset($smt) && ($smt == "save900")){		
			$Audit = new Audit();
			$UID = FixQuotes($UID);
			$fullname = FixQuotes($fullname);
			$sql = "INSERT INTO tblDrawer (UserID, Status) VALUES($UID, 0)";
			if($que = $mydb->sql_query($sql)){
				$description = "Open cash drawer for cashier $fullname ";
				$Audit->AddAudit("", "", "Open cash drawer", $description, $user['FullName'], 1, 13);
				$retOut = $myinfo->info("Successfully open cash drawer for cashier $fullname.");
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed open cash drawer for cashier $fullname.", $error['message']);
			}
	}
?>
<script language="javascript" type="text/javascript">
	function submitForm(){
		if(fdraweropen.UID.selectedIndex < 1){
			alert("Please select user to open cash drawer");
			fdraweropen.UID.focus();
			return;
		}else{
			fdraweropen.fullname.value = fdraweropen.UID.options[fdraweropen.UID.selectedIndex].text;
			fdraweropen.submit();
		}
	}
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="50%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>OPEN CASH DRAWER</b></td>
					<td align="right">&nbsp;
						
					</td>
				</tr> 				
				<tr>
					<td colspan="2">
						<form name="fdraweropen" method="post" action="./" onSubmit="return false;">
							<table border="0" cellpadding="3" cellspacing="0" align="left" width="100%" bgcolor="#feeac2">																
								<tr>
									<td align="left">Account:</td>
									<td align="left">
										<select name="UID" class="boxenabled" tabindex="1">	
											<option value="0">Select cashier</option>																			
											<?php
												$sql = "SELECT UserID, FullName 
													FROM tblSecUser 
													WHERE UserID not in (select UserID from tblDrawer where Status = 0)
													and UserID in ( select UserID from tblSecUserGroup where groupid in (1,6) and userID not in (1,6,7))
													Order by FullName";													
												
												$que = $mydb->sql_query($sql);									
												if($que){
													while($rst = $mydb->sql_fetchrow($que)){	
														$UserID = $rst['UserID'];
														$FullName = $rst['FullName'];									
														print "<option value='".$UserID."'>".$FullName."</option>";
													}
												}
												$mydb->sql_freeresult();
											?>
										</select>
									</td>
								</tr>
								
								<tr><td colspan="2">&nbsp;</td></tr>								
								<tr> 				  
								<td>&nbsp;</td>
								<td align="left">
									<input type="submit" tabindex="10" name="btnSubmit" value="Submit" class="button" onClick="submitForm();" />						
								</td>
							 </tr>
							 <?php
									if(isset($retOut) && (!empty($retOut))){
										print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
									}
								?>
							
							</table>
						<input type="hidden" name="fullname" value="" />
						<input type="hidden" name="pg" value="900" />
						<input type="hidden" name="smt" value="save900" />
						</form>	
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