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
	if((isset($smt)) && (!empty($smt)) && ($smt == "up")){
		$fname = FixQuotes($_POST['filename']);
		$fType = FixQuotes($_POST['fType']);
		$furl = basename( $_FILES['uploadedfile']['name']);		
		$now = date("Y/M/d H:i:s");
		$Operator = $user["FullName"];
		
		#____[directory]
		if ($fType == "1")
			{
				$target_path = "./report/extra/Map/";
			}
		else
			if ($fType == "2")
				{
					$target_path = "./report/extra/Report/";	
				}
			else
				if ($fType == "3")
				{
					$target_path = "./report/extra/Announcement/";	
				}
				else
				{
					$target_path = "./report/extra/Other/";
				}	
				
		$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 
		#_________[Register file]
		$sql = "INSERT INTO tblreportlist(Name, URL, SubmittedDate, SubmittedBy, IsAchieved, ReportTypeID)
						VALUES('".$fname."', '".$furl."', '".$now."', '".$Operator."', 0, '".$fType."')";
		if($mydb->sql_query($sql)){	
			if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
					$retOut = $myinfo->info("The file ".  basename( $_FILES['uploadedfile']['name']). 
					" has been uploaded");
			} else{
					$retOut = $myinfo->error("There was an error uploading the file, please try again!");
			}
		}else{
			$error = $mydb->sql_error();
			$retOut = $myinfo->eorror("Error: Failed to register report in system to be download.", $error['message']);
		}
	}
	
?>	
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<script language="javascript">
	function upload(){
		var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Your file is uploading<br>Please wait a moment...</b></p></td></tr></table>";		
		
		fname = fupload.filename.value;
		furl = fupload.uploadedfile.value;
		if((fname == "") || (furl == "")){
			alert("Please enter report name and browse the file to upload");
		}else{
			fupload.btnSubmit.disabled = true;
			fupload.smt.value = "up";
			fupload.submit();
		}
		
	}
</script>

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="244" align="left" class="formtitle"><b>UPLOAD EXTRA REPORT</b></td>
				</tr> 
				<tr>
					<td>
						<form name="fupload" enctype="multipart/form-data" onSubmit="upload(); return false;" action="./" method="post">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">Report name:</td>
								<td align="left">
									<input type="text" name="filename" size="50" class="boxenabled" />
								</td>
							</tr>
							<tr>
								<td align="left">File:</td>
								<td align="left">
									<input type="file" name="uploadedfile" size="35" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input type="radio" name="fType" value="1" checked="checked" />Map&nbsp;&nbsp;
									<input type="radio" name="fType" value="2" />Report&nbsp;&nbsp;
									<input type="radio" name="fType" value="3" />Announcement&nbsp;&nbsp;
									<input type="radio" name="fType" value="4" />Other&nbsp;&nbsp;
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="center"><input type="button" name="btnSubmit" value="Upload" tabindex="4" onClick="upload();" /></td>
							</tr>
						</table>
						<input type="hidden" name="pg" value="920" />						
						<input type="hidden" name="smt" value="" />
						</form>
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
	<tr><td>&nbsp;</td></tr>					
	<tr><td>
		<div id="d-result">
		</div>
	</td></tr>
</table>