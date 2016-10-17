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

if(isset($smt) && (!empty($smt)) && ($smt == "save907") &&  ($pg == 907)){
	$txtFullName = FixQuotes($txtFullName);
	$txtUserID = FixQuotes($txtUserID);
	$mode = FixQuotes($mode);
	
	$Security = new Security();
	$retOut = $Security->UpdateUserStatus($txtUserID, $mode);
	if(is_bool($retOut)){
		$audit = new Audit();
		$description = "Suspend user $txtFullName to access to the system".	
		$audit->AddAudit("", "", "Suspend user $txtFullName", $description, $user['FullName'], 1, 14);
		$retOut = $myinfo->info("Successful suspend user $txtFullName to access to the system.");
	}

}
?>

<script language="javascript">
	function Suspend(UserID, FullName, mode){
		if(mode == 0)
			text = "Do you wish to suspend user " + FullName + "?";
		else
			text = "Do you wish to activate user " + FullName + "?";
		if(confirm(text)){
			fsuspenduse.txtUserID.value = UserID;
			fsuspenduse.txtFullName.value = FullName;
			fsuspenduse.smt.value = "save907";
			fsuspenduse.mode.value = mode;
			fsuspenduse.submit();
		}
	}
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="150">
			<?php include("content.php"); ?>
		</td>
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="center" width="100%">
			  <tr>
				 <td align="left" class="formtitle" height="18"><b>LIST USERS</b></td>
			  </tr>
				<tr>
					<td align="left">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>					
								<th>No.</th>
								<th>User name</th>
								<th>Full name</th>
								<th>Status</th>
								<th>Email</th>
								<th>Group name</th>
								<th>Modify</th>
							</thead>
							<tbody>
							<?php								
								$iLoop = 0;
								$sql = "SELECT u.UserID, u.UserName, u.FullName, u.Status, u.EmailAddress, g.GroupName
												FROM tblSecUser u left join tblSecUserGroup ug on u.UserID = ug.UserID left join tblSecGroup g on
															ug.GroupID = g.GroupID";

								if($que = $mydb->sql_query($sql)){				
									while($result = $mydb->sql_fetchrow($que)){
										$pUserID = $result['UserID'];
										$pUserName = $result['UserName'];
										$pFullName = $result['FullName'];
										$Status = $result['Status'];
										$delete = "";
										if($Status){
											$stStatus = "<font color='blue'><b>Active</b></font>";
											$delete = "<a href='#' onClick=\"Suspend('".$pUserID."', '".$pFullName."', 0);\"> <img src='./images/Delete.gif' border=0 alt='Suspend user ".$pFullName."' /></a>&nbsp;&nbsp;";
											$delete .= " <a href='./?UID=".$pUserID."&pg=912'><img src='./images/page2user.ico' border=0 alt='Add page to user' /></a>";
										}else{
											$stStatus = "<font color='red'><b>Suspend</b></font>";
											$delete = "<a href='#' onClick=\"Suspend('".$pUserID."', '".$pFullName."', 1);\"><img src='./images/plus1.gif' border=0 alt='Activate user ".$pFullName."' /></a>";
											
										}
										$pEmailAddress = $result['EmailAddress'];
										$pGroupName = $result['GroupName'];
										$link = "<a href='./?userid=".$pUserID."&pg=909'>".$pFullName."</a>";
										$iLoop++;
										if(($iLoop % 2) == 0)
											$style = "row1";
										else
											$style = "row2";
										print '<tr>';	
										print '<td class="'.$style.'" align="left">'.$iLoop.'</td>';
										print '<td class="'.$style.'" align="left">'.$pUserName.'</td>';
										print '<td class="'.$style.'" align="left">'.$link.'</td>';
										print '<td class="'.$style.'" align="left">'.$stStatus.'</td>';									
										print '<td class="'.$style.'" align="left">'.$pEmailAddress.'</td>';	
										print '<td class="'.$style.'" align="left">'.$pGroupName.'</td>';	
										print '<td class="'.$style.'" align="left">'.$delete.'</td>';	
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
<form name="fsuspenduse" method="post" action="./">
<input type="hidden" name="pg" value="907" />
<input type="hidden" name="txtUserID" value="" />
<input type="hidden" name="txtFullName" value=" /">
<input type="hidden" name="mode" value=" /">
<input type="hidden" name="smt" value="" />
</form>

<?php
# Close connection
$mydb->sql_close();
?>
