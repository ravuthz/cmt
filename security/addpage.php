<?php
	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");
	require_once("./common/class.security.php");
	require_once("./common/functions.php");
/*
	+ ************************************************************************************** +	
	*																																												 *
	* This code is not to be distributed without the written permission of BRC Technology.   *
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
	* 																																											 *
	+ ************************************************************************************** +
*/
		
	if(isset($smt) && (!empty($smt)) && ($smt == "save913") &&  ($pg == 913)){
			
			$now = date("Y-M-d H:i:s"); 
			$txtPageCode = FixQuotes($txtPageCode);
			$txtPageURL = FixQuotes($txtPageURL);
			$txtPageName = FixQuotes($txtPageName);
			$txtComment = FixQuotes($txtComment);
			$id = FixQuotes($id);			
			
			# insert new page
			$sql = "INSERT INTO tblSecPage(PageCode, PageURL, PageName, SubmittedBy, SubmittedDate, Description)
							VALUES('".$txtPageCode."', '".$txtPageURL."', '".$txtPageName."', 
											'".$user['FullName']."', '".$now."', '".$txtComment."')";

			if($mydb->sql_query($sql)){
				# get page id
				$sql = "SELECT PageID FROM tblSecPage where PageCode ='".$txtPageCode."' and SubmittedDate='".$now."'";
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$PageID = $result['PageID'];
						# add user/group to access page
						$arrid = split("\|", $id);
						for ($i=0; $i< sizeof($arrid)-1; $i++){
							$ugid = $arrid[$i];
							$type = substr($ugid, 0, 3);
							if($type == "usr"){
								$uid = substr($ugid, 6, strlen($ugid) - 6);
								$sql = "INSERT INTO tblSecUserPage(UserID, PageID) VALUES('".$uid."', '".$PageID."')";
							}else{
								$gid = substr($ugid, 6, strlen($ugid) - 6);
								$sql = "INSERT INTO tblSecGroupPage(GroupID, PageID) VALUES('".$gid."', '".$PageID."')";
							}
							
							$mydb->sql_query($sql);							
						}
						$retOut = $myinfo->info("Successful add new page and assign to user or group");
					}
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to get new page id.", $error['message']);
				}
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to add new page.", $error['message']);
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
		 		var len = fcreatepage.grant_left.length;
				for (var i = 0; i < len + 1; i++)
					moveItemFromTo(fcreatepage.grant_left, fcreatepage.grant_right)
			}

			if (toList==1)
			 	while (iIndex > -1){
					moveItemFromTo(fcreatepage.grant_left, fcreatepage.grant_right)
					iIndex = fcreatepage.grant_left.selectedIndex
			 	}
      
			if (toList==2)
				 while (iIndex > -1){
						moveItemFromTo(fcreatepage.grant_right, fcreatepage.grant_left)
						iIndex = fcreatepage.grant_right.selectedIndex
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
		 function ValidateForm(){
		 		
				txtPageCode = fcreatepage.txtPageCode;
				txtPageName = fcreatepage.txtPageName;
				if(txtPageCode.value == ""){
					alert("Please enter page code.");
					txtPageCode.focus();
					return;
				}else if(txtPageName.value == ""){
					alert("Please enter page name");
					txtPageName.focus();
					return;
				}
				
				var ID = "";
         	if(fcreatepage.grant_right.length > 0){
			   		for(var i = 0; i < fcreatepage.grant_right.length ; i++){				
							ID = ID + fcreatepage.grant_right(i).value + "|";				   	
					 	}
					}else{
						alert("Please select user to assign to group");
						fcreatepage.grant_right.focus();	
						return;
					}
		 		fcreatepage.id.value = ID;				
				fcreatepage.btnNext.disabled = true;
				fcreatepage.smt.value = "save913";
				fcreatepage.submit();
		 }
</script>

<form name="fcreatepage" method="post" action="./">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="150">
			<?php include("content.php"); ?>
		</td>
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="center" width="100%">
			  <tr>
				 <td align="left" class="formtitle" height="18"><b>ADD NEW PAGE</b></td>
			   </tr>
			  <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">					 					 
					<tr>
						<td align="left">Page code:</td>
					 	<td align="left">
							<input type="text" tabindex="1" name="txtPageCode" class="boxenabled" size="10" maxlength="50" value="" />
								<img src="./images/required.gif" border="0" />
						</td>
					</tr>					 
					<tr>
					 	<td align="left" valign="top">Page URL:</td>
						<td align="left">
							<input type="text" tabindex="2" name="txtPageURL" class="boxenabled" size="71" maxlength="100" value="" />								
						</td>
					</tr>
					<tr>
						<td align="left">Page name:</td>
						<td align="left">
							<input type="text" tabindex="3" name="txtPageName" class="boxenabled" size="67" maxlength="100" value="" />
								<img src="./images/required.gif" border="0" />
						</td>
					</tr>					
					 <tr>
					 	<td align="left" valign="top">Comment:</td>
						<td align="left">
							<textarea name="txtComment" cols="54" rows="6" tabindex="7" class="boxenabled"></textarea>
						</td>
					</tr>
					<tr>
						<td align="left" colspan="2">
							<table border="2" cellpadding="0" cellspacing="3" align="left" class="text">
								<tr>
									<td align="left">Users available</td>
									<td>&nbsp;</td>
									<td align="left">Users in group</td>
								</tr>
								<tr>
									<td nowrap align="left" valign="top">
										<select multiple ondblclick='moveItem(1);' id='grant_left' name='grant_left' style='width:250px;' size=15>       					   										<?php 
												$sql = "SELECT 'usr-->' as 'Type',  UserID as 'ID', FullName as 'Name' FROM tblSecUser WHERE Status = 1
																UNION
																SELECT 'grp-->' as 'Type', GroupID as 'ID', GroupName as 'Name' FROM tblSecGroup
																ORDER BY 1
															";
												if($que = $mydb->sql_query($sql)){
													while($result = $mydb->sql_fetchrow($que)){
														$ID = $result['ID'];
														$Type = $result['Type'];
														$Name = $result['Name'];
														$ugID = $Type.$ID;
														$ugName = $Type.$Name;
														print "<option value='".$ugID."'>".$ugName."</option>";
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
									</select>
								</td>
							 </tr>
						 </table>
						</td>
					</tr>
					 <tr> 				  
						<td align="center" colspan="2">
							<input type="reset" tabindex="8" name="reset" value="Reset" class="button" />
							<input type="button" tabindex="9" name="btnNext" value="Submit" class="button" onClick="ValidateForm();" />						
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
			  <input type="hidden" name="pg" id="pg" value="913" />
				<input type="hidden" name="id" value="" />
				<input type="hidden" name="smt" value="" />
	</form>
<br>&nbsp;
<?php
# Close connection
$mydb->sql_close();
?>

