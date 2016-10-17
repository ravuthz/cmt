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
?>	

<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<script language="javascript" type="text/javascript">
	
	function getpackage(check){
		service = frmservice.selservice.value;		
		
		if(service == ""){
			alert("Please select Service");
			frmservice.selservice.focus();
			return;
		}else{
			url = "./php/ajax_package.php?serviceid="+service+"&ck="+check+"&mt="+new Date().getTime();;
			getTranDetail(url, "d-package");
		}
	}

	function showReport(index){
		serviceid = frmservice.selservice.value;
		where = 0;
		for(i=0; i< frmservice.package.length; i++){
			if(frmservice.package[i].checked == true)
				where += ", " + frmservice.pack[i].value;
		}
		
		if(frmservice.inactive.checked)
			ina = 1;
		else
			ina = 0;
		if(frmservice.active.checked)
			ac = 1;
		else
			ac = 0;
		if(frmservice.bar.checked)
			ba = 1;
		else
			ba = 0;
		if(frmservice.closed.checked)
			cl = 1;
		else
			cl = 0;
		if((service == "") || (package = "")){
			alert("Please select service and package to view report");
			frmservice.selservice.focus();
			return;
		}else{ 
			if(index == 0){
				var loading;
		loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
				document.getElementById("d-service_report").innerHTML = loading;
				url = "./php/ajax_service_report.php?serviceid="+serviceid+"&where="+where+"&ina="+ina+"&ac="+ac+"&ba="+ba+"&cl="+cl+"&mt="+ new Date().getTime();
				getTranDetail(url, "d-service_report");
			}else{
				url = "./export/service_report.php?serviceid="+serviceid+"&where="+where+"&ina="+ina+"&ac="+ac+"&ba="+ba+"&cl="+cl+"&mt="+ new Date().getTime();
				window.location = url;
			}
		}
	}
</script>

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="244" align="left" class="formtitle"><b>&nbsp;SERVICE REPORT</b></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="frmservice" onSubmit="return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
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
								<td colspan="2" align="left">
									<input type="checkbox" name="inactive" value="1" />Inactive&nbsp;
									<input type="checkbox" name="active" value="1" />Active&nbsp;
									<input type="checkbox" name="bar" value="1" />Barred&nbsp;
									<input type="checkbox" name="closed" value="1" />Closed&nbsp;
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left">
									<input type="button" name="btnSubmit" value="View report" tabindex="4" onClick="showReport(0);" />
									<input type="button" name="btnExport" value="View report" tabindex="5" onClick="showReport(1);" />
								</td>
							</tr>
						</table>						
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>		
	<tr><td>&nbsp;</td></tr>				
	<tr><td>
		<div id="d-service_report">
		</div>
	</td></tr>
</table>
