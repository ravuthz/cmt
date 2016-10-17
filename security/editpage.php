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
	$pid = FixQuotes($pid);	
	$getpage = true;
	if(isset($smt) && (!empty($smt)) && ($smt == "save915") &&  ($pg == 915)){		
		$txtPageCode = FixQuotes($txtPageCode);
		$txtPageURL = FixQuotes($txtPageURL);
		$txtPageName = FixQuotes($txtPageName);
		$txtComment = FixQuotes($txtComment);
		$sql = "UPDATE tblSecPage SET
							PageCode = '".$txtPageCode."',
							PageURL = '".$txtPageURL."',
							PageName = '".$txtPageName."', 
							Description = '".$txtComment."'
						WHERE PageID='".$pid."'";
		if($mydb->sql_query($sql)){
			$retOut = $myinfo->info("Successful save changed page information");
		}else{
			$getpage = false;
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to get page information.", $error['message']);
		}
	}
if($getpage){	
	$sql = "SELECT * FROM tblSecPage WHERE PageId=".$pid;
	if($que = $mydb->sql_query($sql)){
		if($result = $mydb->sql_fetchrow($que)){
			$PageCode = $result['PageCode'];
			$PageURL = $result['PageURL'];
			$PageName = $result['PageName'];
			$Description = $result['Description'];
		}
	}else{
		$error = $mydb->sql_error();
		$retOut = $myinfo->error("Failed to get page information.", $error['message']);
	}
}
?>	
<script language="javascript">
	function ValidateForm(){		 		
		txtPageCode = feditpage.txtPageCode;
		txtPageName = feditpage.txtPageName;
		if(txtPageCode.value == ""){
			alert("Please enter page code.");
			txtPageCode.focus();
			return;
		}else if(txtPageName.value == ""){
			alert("Please enter page name");
			txtPageName.focus();
			return;
		}
		feditpage.btnSave.disabled = true;
		feditpage.smt.value = "save915";
		feditpage.submit();
	}
</script>
<form name="feditpage" method="post" action="./">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="150">
			<?php include("content.php"); ?>
		</td>
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="center" width="100%">
			  <tr>
				 <td align="left" class="formtitle" height="18"><b>EDIT PAGE</b></td>
			   </tr>
			  <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">					 					 
					<tr>
						<td align="left">Page code:</td>
					 	<td align="left">
							<input type="text" tabindex="1" name="txtPageCode" class="boxenabled" size="10" maxlength="50" value="<?php print trim($PageCode);?>" />
								<img src="./images/required.gif" border="0" />
						</td>
					</tr>					 
					<tr>
					 	<td align="left" valign="top">Page URL:</td>
						<td align="left">
							<input type="text" tabindex="2" name="txtPageURL" class="boxenabled" size="71" value="<?php print trim($PageURL);?>" />								
						</td>
					</tr>
					<tr>
						<td align="left">Page name:</td>
						<td align="left">
							<input type="text" tabindex="3" name="txtPageName" class="boxenabled" size="67" value="<?php print trim($PageName);?>" />
								<img src="./images/required.gif" border="0" />
						</td>
					</tr>					
					 <tr>
					 	<td align="left" valign="top">Comment:</td>
						<td align="left">
							<textarea name="txtComment" cols="54" rows="6" tabindex="7" class="boxenabled"><?php print trim($Description);?></textarea>
						</td>
					</tr>					
					 <tr> 				  
						<td align="center" colspan="2">
							<input type="reset" tabindex="8" name="reset" value="Reset" class="button" />
							<input type="button" tabindex="9" name="btnSave" value="Save" class="button" onClick="ValidateForm();" />						
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
			  <input type="hidden" name="pg" id="pg" value="915" />
				<input type="hidden" name="pid" value="<?php print $pid; ?>" />
				<input type="hidden" name="smt" value="" />
	</form>
<br>&nbsp;
<?php
# Close connection
$mydb->sql_close();
?>