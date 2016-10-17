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
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<script language="javascript">
	function showList(id, gid, gname){
		var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-grouplist").innerHTML = loading;
			if(id == 1) // get user
				url = "./php/ajax_getlistuser.php?gid="+gid+"&gname="+gname;
			else
				url = "./php/ajax_getlistpage.php?gid="+gid+"&gname="+gname;
			getTranDetail(url, "d-grouplist");
	}
	
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="150" rowspan="2">
			<?php include("content.php"); ?>
		</td>
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="center" width="100%">
			  <tr>
				 <td align="left" class="formtitle" height="18"><b>LIST GROUPS</b></td>
			  </tr>
				<tr>
					<td align="left">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>					
								<th>No.</th>
								<th>Group name</th>
								<th>Description</th>
								<th>No. users</th>
								<th>Configs</th>								
							</thead>
							<tbody>
							<?php								
								$iLoop = 0;
								$sql = "SELECT g.GroupID, g.GroupName, g.Description, Count(ug.UserID) as 'NoUser'
												FROM tblSecGroup g left join tblSecUserGroup ug on g.GroupID = ug.GroupID
												GROUP BY g.GroupID, g.GroupName, g.Description";

								if($que = $mydb->sql_query($sql)){				
									while($result = $mydb->sql_fetchrow($que)){
										$pGroupID = $result['GroupID'];
										$pGroupName = $result['GroupName'];
										$Desccription = $result['Desccription'];
										$NoUser = $result['NoUser'];
										$delete = "";
										if($NoUser > 0){											
											$NoUser = "<a href=\"javascript:showList(1, '".$pGroupID."', '".$pGroupName."');\">".$NoUser."</a>";
										}else{											
											$NoUser = "0";
										}
										//$NoPage = $result['NoPage'];
//										if($NoPage > 0){											
//											$NoPage = "<a href=\"javascript:showList(2, '".$pGroupID."', '".$pGroupName."');\">".$NoPage."</a>";
//										}else{											
//											$NoPage = "0";
//										}
										$con = "<a href='./?GroupID=".$pGroupID."&pg=910'><img src='./images/user2group.ico' border=0 alt='Add users to group'></a>  <a href='./?GroupID=".$pGroupID."&pg=911'><img src='./images/page2group.ico' border=0 alt='Add pages to group'></a>";
										
										$iLoop++;
										if(($iLoop % 2) == 0)
											$style = "row1";
										else
											$style = "row2";
										print '<tr>';	
										print '<td class="'.$style.'" align="left">'.$iLoop.'</td>';
										print '<td class="'.$style.'" align="left">'.$pGroupName.'</td>';
										print '<td class="'.$style.'" align="left">'.$Desccription.'</td>';
										print '<td class="'.$style.'" align="left">'.$NoUser.'</td>';										
										print '<td class="'.$style.'" align="left">'.$con.'</td>';		
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
	<tr>
					<td align="left">
						<div id="d-grouplist">
						
						</div>
					</td>
				</tr>
</table>


<?php
# Close connection
$mydb->sql_close();
?>
