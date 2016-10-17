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
	function showReport(index){
		st = fdebtperiod.st.value;
		et = fdebtperiod.et.value;
		sid = fdebtperiod.sid1.value;
		if((st == "") || (et == "")){
			alert("Please enter from date and to date to view report");
			fdebtperiod.st.focus();
			return;
		}else{ 
			if(index == 1){
				var loading;
		loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			//	document.getElementById("d-result").innerHTML = loading;
				url = "./php/ajax_riskdebter_period.php?st="+st+"&et="+et+"&sid="+sid+"&mt=" + new Date().getTime();
				window.open(url);
				//getTranDetail(url, "d-result");
			}else{
				url = "./export/riskdebtor_period.php?st="+st+"&et="+et+"&sid="+sid+"&type=csv&mt=" + new Date().getTime();
				window.open(url);
			}
		}
	}
</script>

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="244" align="left" class="formtitle"><b>RISK DEBTOR PERIOD REPORT</b></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="fdebtperiod" onSubmit="showReport(); return false;" action="./">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">From date:</td>
								<td align="left">
									<input type="text" tabindex="3" name="st" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fdebtperiod|st', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
								</td>
							</tr>
							<tr>
								<td align="left">To date:</td>
								<td align="left">
									<input type="text" tabindex="3" name="et" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fdebtperiod|et', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
								</td>
							</tr>
							<tr>
								<td align="left">Service:</td>
								<td align="left">
									<select name="sid1">
										<option value="0" <?php if(intval($sid1) == 0) print "selected";?>>All services</option>
										<option value="2" <?php if(intval($sid1) == 2) print "selected";?>>Telephone</option>
										<option value="4" <?php if(intval($sid1) == 4) print "selected";?>>Leased line</option>
										<option value="5" <?php if(intval($sid1) == 5) print "selected";?>>ISP</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left">
									<input type="button" name="btnSubmit" value="View report" tabindex="4" onClick="showReport(1);" />
									<input type="button" name="download" value="Download" tabindex="5" onClick="showReport(2);" />
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
		<div id="d-result">
		</div>
	</td></tr>
</table>