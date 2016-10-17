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
	
?>	
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<script language="javascript">
function getpackage(check){
		service = faccnodeposit.selservice.value;		
		
		if(service == ""){
			alert("Please select Service");
			faccnodeposit.selservice.focus();
			return;
		}else{
			url = "./php/ajax_package.php?serviceid="+service+"&ck="+check+"&mt="+new Date().getTime();;
			getTranDetail(url, "d-package");
		}
	}
	function viewreport(index){
		where = "0 ";
		for(i=0; i< faccnodeposit.package.length; i++){
			if(faccnodeposit.package[i].checked == true)
				where += ", " + faccnodeposit.pack[i].value;
		}
		if(index ==1){
			loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-result").innerHTML = loading;
			
			url = "./php/ajax_acc_deposit.php?where="+where+"&mt="+new Date().getTime();
			getTranDetail(url, "d-result");
		}else{
			url = "./export/acc_deposit.php?where="+where+"&mt="+new Date().getTime();
			window.location = url;
		}
	}
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" >
				<tr>
					<td align="left" class="formtitle"><b>ACCOUNT WITH DEPOSIT REPORT</b></td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="faccnodeposit">
						<table border="0" cellpadding="3" cellspacing="0" align="left" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left" nowrap="nowrap">Service:</td>
								<td align="left" width="90%">
										<select name="selservice" style="width:200px" onChange="getpackage(1);">
											<option value='0'>Select service</option>
											<option value="2">Telephone</option>
											<option value="4">LeaseLine</option>
											<option value="5">ISP</option>
										</select>
							  </td>
							</tr>
							<tr>								
								<td align="left" colspan="2">
									<div id="d-package">
									</div>
								</td>
							</tr>					
							<tr>
								<td align="left" colspan="2">
									<input type="button" name="tbnPreview" value="Preview" onclick="viewreport(1)" />
									<input type="button" name="tbnExport" value="Export" onclick="viewreport(2)" />
								</td>
							</tr>						
						</table>		
						</form>				
					</td>
				</tr>				
			</table>
		</td>
	</tr>
	<tr><td>
		<div id="d-result">
		</div>
	</td></tr>						
</table>