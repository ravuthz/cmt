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
	function showReport(){
		st = fjob.st.value;
		et = fjob.et.value;
		dep = fjob.department.options[fjob.department.selectedIndex].value;
		sta = fjob.status.options[fjob.status.selectedIndex].value;
		if((st == "") || (et == "")){
			alert("Please enter from date and to date to view report");
			fdebtperiod.st.focus();
			return;
		}else{ 
			
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-result").innerHTML = loading;
			url = "./php/ajax_job.php?st="+st+"&et="+et+"&dep="+dep+"&sta="+sta+"&mt=" + new Date().getTime();
			getTranDetail(url, "d-result");
		}
	}
</script>

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="244" align="left" class="formtitle"><b>ACCOUNT STATUS REPORT</b></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="fjob" onSubmit="return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">From date:</td>
								<td align="left">
									<input type="text" tabindex="3" name="st" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fjob|st', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
								</td>
							</tr>
							<tr>
								<td align="left">To date:</td>
								<td align="left">
									<input type="text" tabindex="3" name="et" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fjob|et', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
								</td>
							</tr>
							<tr>
								<td align="left">Department:</td>
								<td align="left">
									<select name="department">
										<option value="1">Sales</option>
										<option value="2">Switch</option>
										<option value="6">MDF</option>
										<option value="3">Operation</option>
										<option value="5">ISP</option>
										<option value="4">Customer service</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="left">Status:</td>
								<td align="left">
									<select name="status">
										<option value="0">Pending</option>
										<option value="1">Done</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left"><input type="button" name="btnSubmit" value="View report" tabindex="4" onClick="showReport();" /></td>
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
		<div id="d-result">
		</div>
	</td></tr>
</table>