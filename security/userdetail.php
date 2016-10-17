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
	$sql = "SELECT UserName, FullName, Status, EmailAddress, Telephone, DOB, Description
					FROM tblSecUser WHERE UserID = ".$userid;
	if($que = $mydb->sql_query($sql)){
		if($result = $mydb->sql_fetchrow($que)){
			$pUserName = $result['UserName'];
			$pFullName = $result['FullName'];
			$pStatus = $result['Status'];
			$pEmailAddress = $result['EmailAddress'];
			$pTelephone = $result['Telephone'];
			$pDOB = $result['DOB'];
			$pDescription = $result['Description'];
			if($pStatus)
				$stStatus = "Active";
			else
				$stStatus = "Suspend";
		}
	}else{
		$error = $mydb->sql_error();
		$retOut = $myinfo->error("Failed to get user information.", $error['message']);
	}
?>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="150">
			<?php include("content.php"); ?>
		</td>
		<td valign="top" align="left">
			<table border="0" cellpadding="3" cellspacing="0" align="left" width="100%">
				<tr>
					<td>
						<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle"><b>USER PROFILE DETAIL</b></td>
								<td align="right"></td>
							</tr>
							<tr>
								<td valign="top" colspan="2">
									<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">												
										<tr>
											<td align="left" nowrap="nowrap">User name:</td>
											<td align="left"><b><?php print $pUserName;?></b></td>					
										</tr>
										<tr>					
											<td align="left" nowrap="nowrap">Full name:</td>
											<td align="left" width="80%"><b><?php print $pFullName;?></b></td>																										
										</tr>
										<tr>					
											<td align="left" nowrap="nowrap">Status:</td>
											<td align="left"><b><?php print $stStatus;?></b></td>																										
										</tr>
										<tr>					
											<td align="left" nowrap="nowrap">Date of birth:</td>
											<td align="left"><b><?php print formatDate($pDOB, 6); ?></b></td>
										</tr>												
										<tr>
											<td align="left" nowrap="nowrap">Telephone:</td>
											<td align="left"><b><?php print $pTelephone;?></b></td>					
										</tr>
										<tr>
											<td align="left" nowrap="nowrap">Email:</td>
											<td align="left"><b><?php print $pEmailAddress;?></b></td>					
										</tr>
										<tr>
											<td align="left" nowrap="nowrap" valign="top">Description:</td>
											<td align="left"><b><?php print $pDescription;?></b></td>					
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
</table>
<?php
# Close connection
$mydb->sql_close();
?>