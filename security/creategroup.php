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
		
	if(isset($smt) && (!empty($smt)) && ($smt == "save906") &&  ($pg == 906)){
			
			$now = date("Y/M/d H:i:s"); 			
			$txtGroupName = FixQuotes($txtGroupName);
			$txtComment = FixQuotes($txtComment);
			
			$sql = "INSERT INTO tblSecGroup(GroupName, Description) VALUES('".$txtGroupName."', '".$txtComment."')";

			if($mydb->sql_query($sql)){
				$audit = new Audit();
				$description = "Create new group $txtGroupName".	
				$audit->AddAudit("", "", "Create new group", $description, $user['FullName'], 1, 14);
				$retOut = $myinfo->info("Successful create new group $txtGroupName.");
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to create new group.", $error['message']);
			}										
			
		}											creategroup.php
?>

<script language="javascript">
	
	function ValidateForm(){

		txtGroupName = fcreategroup.txtGroupName;		
		if(Trim(txtGroupName.value) == ""){
			alert("Please enter group name.");
			txtName.focus();
			return;
		}
		fcreategroup.btnNext.disabled = true;
		fcreategroup.submit();
	}
</script>

<br>
<form name="fcreategroup" method="post" action="./">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="150">
			<?php include("content.php"); ?>
		</td>
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="center" width="100%">
			  <tr>
				 <td align="left" class="formtitle" height="18"><b>CREATE NEW GROUP</b></td>
			  </tr>
			  <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">					 					 
					<tr>
						<td align="left" nowrap="nowrap">Group name:</td>
					 	<td align="left">
							<input type="text" tabindex="1" name="txtGroupName" class="boxenabled" size="50" maxlength="50" value="" />
								<img src="./images/required.gif" border="0" />
						</td>
					</tr>					 					
					 <tr>
					 	<td align="left" valign="top">Comment:</td>
						<td align="left">
							<textarea name="txtComment" cols="58" rows="6" tabindex="2" class="boxenabled"></textarea>
						</td>
					</tr>
					 <tr> 				  
					  <td align="center" colspan="2">
							<input type="reset" tabindex="3" name="reset" value="Reset" class="button" />
							<input type="button" tabindex="4" name="btnNext" value="Submit" class="button" onClick="ValidateForm();" />						
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
			  <input type="hidden" name="pg" id="pg" value="906">
				<input type="hidden" name="smt" id="smt" value="save906">
	</form>
<br>&nbsp;
<?php
# Close connection
$mydb->sql_close();
?>
