<?php
	require_once("./common/functions.php");

	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	$GroupID = FixQuotes($GroupID);
	if(!empty($smt) && isset($smt) && ($smt == "save910")){
		$id = FixQuotes($id);		 
		 	$arrid = split("\|", $id);	
			$sql = "DELETE FROM tblSecUserGroup WHERE GroupID=".$GroupID;
			$mydb->sql_query($sql);
		 	for ($i=0; $i< sizeof($arrid)-1; $i++){
		 		$uid = $arrid[$i];
				$sql = "INSERT INTO tblSecUserGroup(UserID, GroupID) VALUES($uid, $GroupID)";
				$mydb->sql_query($sql);
		 }
		 $retOut = $myinfo->info("Successful save changed grant user to group");
	}
	
	
	$sql = "SELECT GroupName, Description FROM tblSecGroup WHERE GroupID=".$GroupID;
	if($que = $mydb->sql_query($sql)){
		if($result = $mydb->sql_fetchrow($que)){
			$GroupName = $result['GroupName'];
			$Description = $result['Description'];
		}	
	}
?>
<script language="javascript" type="text/javascript">
// ++++++++++++++++++++++++++++++++++++++++++++++
        // code when user click moving button(>>, ->, <-)
        // ++++++++++++++++++++++++++++++++++++++++++++++
		function moveItem(toList) {
			var iIndex = 0
	
			if (toList==0){
		 		var len = fusergroup.grant_left.length;
				for (var i = 0; i < len + 1; i++)
					moveItemFromTo(fusergroup.grant_left, fusergroup.grant_right)
			}

			if (toList==1)
			 	while (iIndex > -1){
					moveItemFromTo(fusergroup.grant_left, fusergroup.grant_right)
					iIndex = fusergroup.grant_left.selectedIndex
			 	}
      
			if (toList==2)
				 while (iIndex > -1){
						moveItemFromTo(fusergroup.grant_right, fusergroup.grant_left)
						iIndex = fusergroup.grant_right.selectedIndex
				 }
    }
  
		function moveItemFromTo(fromList, toList){
			var iIndex = 0
			var newItem = new Option()
	
			if (fromList.length > 0){
				 if (fromList.selectedIndex > -1)
						iIndex = fromList.selectedIndex
		
				 newItem.text = fromList(iIndex).text
				 newItem.value = fromList(iIndex).value
					 
		 			toList.add(newItem, toList.length)
					fromList.remove(iIndex)
				}
		}
        // ++++++++++++++++++++++++++++++++++++++++++++++

     //-->
		 function saveChange(){
		 		var ID = "";
         	if(fusergroup.grant_right.length > 0){
			   		for(var i = 0; i < fusergroup.grant_right.length ; i++){				
							ID = ID + fusergroup.grant_right(i).value + "|";				   	
					 	}
					}else{
						alert("Please select user to assign to group");
						fusergroup.grant_right.focus();	
						return;
					}
		 		fusergroup.id.value = ID;				
				fusergroup.btnSave.disabled = true;
				fusergroup.smt.value = "save910";
				fusergroup.submit();
		 }
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="150">
			<?php include("content.php"); ?>
		</td>
		<td valign="top" align="left">
			 <form name="fusergroup" method="post" action="./">
			<table border="0" cellpadding="3" cellspacing="0" align="left" width="100%">
				<tr>
					<td>
						<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle"><b>ADD USERS TO GROUP</b></td>
								<td align="right"></td>
							</tr>
							<tr>
								<td valign="top" colspan="2">
									<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">												
										<tr>
											<td align="left" nowrap="nowrap">Group name:</td>
											<td align="left"><b><?php print $GroupName;?></b></td>					
										</tr>
										<tr>					
											<td align="left" nowrap="nowrap">Description:</td>
											<td align="left" width="80%"><b><?php print $Description;?></b></td>																										
										</tr>		
										<tr>
											<td colspan="2">
												<table border="2" cellpadding="0" cellspacing="3" align="left" class="text">
													<tr>
														<td align="left">Users available</td>
														<td>&nbsp;</td>
														<td align="left">Users in group</td>
												 	</tr>
												 	<tr>
														<td nowrap align="left" valign="top">
															<select multiple ondblclick='moveItem(1);' id='grant_left' name='grant_left' style='width:250px;' size=15>       					   						<?php 
																	$sql = "SELECT UserID, FullName 
																					FROM tblSecUser 
																					WHERE Status = 1 and UserID not in 
																						(SELECT UserID from tblSecUserGroup WHERE GROUPID=".$GroupID.")";
																	if($que = $mydb->sql_query($sql)){
																		while($result = $mydb->sql_fetchrow($que)){
																			$pUserID = $result['UserID'];
																			$pFullName = $result['FullName'];
																			print "<option value='".$pUserID."'>".$pFullName."</option>";
																		}
																	}
																?>
															</select>
														</td>
														<td valign='top' align="center">
															<br><br>					
															<button style="width:24;height:23;border:#999999 solid 0;cursor:hand;" onClick="moveItem(0, 'frmadduser.groupLeft', 'frmadduser.groupRight');"><img src='./images/forwardall.gif'></button>
															<br>
															<button style='width:24;height:23;border:#999999 solid 0;cursor:hand;' onClick='moveItem(1, "frmadduser", "groupLeft", "groupRight");'><img src='./images/forward.gif'></button>
															<br>
															<button style='width:24;height:23;border:#999999 solid 0;cursor:hand;' onClick='moveItem(2, "frmadduser", "groupLeft", "groupRight");'><img src='./images/backward.gif'></button>          
														 </td>
														 <td nowrap valign="top" align="right">
														<select multiple ondblclick='moveItem(2);' id='grant_right' name='grant_right' style='width:250px;' size=15>       
															<?php 
																	$sql = "SELECT UserID, FullName 
																					FROM tblSecUser 
																					WHERE UserID in 
																						(SELECT UserID from tblSecUserGroup WHERE GROUPID=".$GroupID.")";
																	if($que = $mydb->sql_query($sql)){
																		while($result = $mydb->sql_fetchrow($que)){
																			$pUserID = $result['UserID'];
																			$pFullName = $result['FullName'];
																			print "<option value='".$pUserID."'>".$pFullName."</option>";
																		}
																	}
																?>
														</select>
													</td>
												 </tr>
											 </table>
											</td>
										</tr>	
										<tr><td align="center" colspan="2">											
											<input type="reset" tabindex="14" name="reset" value="Reset" class="button" />
											<input type="button" tabindex="15" name="btnSave" value="Save" class="button" onClick="saveChange();" />
										</td></tr>
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
			<input type="hidden" name="smt" value="" />
			<input type="hidden" name="pg" value="910" />
			<input type="hidden" name="id" value="" />
			<input type="hidden" name="GroupID" value="<?php print $GroupID; ?>" />
			</form>
		</td>
	</tr>
</table>
<?php
# Close connection
$mydb->sql_close();
?>