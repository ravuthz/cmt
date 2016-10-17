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
?>
<script language="javascript" type="text/javascript">
	function done(ID, custid, accid,  Account, cs, ns, nsid){
		if(confirm("Are you sure that you have done the request for account " + Account + "?")){
			fdoneit.JID.value = ID;
			fdoneit.conf.value = "yes";			
			fdoneit.CustomerID.value = custid;
			fdoneit.AccountID.value = accid;
			fdoneit.cstatus.value = cs;
			fdoneit.nstatus.value = ns;
			fdoneit.nstatusid.value = nsid;
			fdoneit.submit();
		}
	}
</script>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js.js"></script>
<script language="javascript" type="text/javascript">
	function showReport(){
		st = future_action.st.value;
		et = future_action.et.value;
		if((st == "") || (et == "")){
			alert("Please enter from date and to date to view report");
			future_action.st.focus();
			return;
		}else{
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-future_action").innerHTML = loading;
			url = "./php/ajax_future_action.php?st="+st+"&et="+et;
			getTranDetail(url, "d-future_action");
		}
	}
</script>

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="320" align="left" class="formtitle"><b>ACCOUNT REQUEST  REPORT</b></td>
					<td width="0" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="future_action" onSubmit="showReport(); return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">From date:</td>
								<td align="left">
									<input type="text" tabindex="3" name="st" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?future_action|st', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">											</button>								</td>
							</tr>
							<tr>
								<td align="left">To date:</td>
								<td align="left">
									<input type="text" tabindex="3" name="et" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?future_action|et', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">											</button>								</td>
							</tr>	
							<tr>
								<td>&nbsp;</td>
								<td align="left"><input type="submit" name="btnSubmit" value="View report" /></td>
							</tr>
						</table>						
						</form>					</td>
				</tr>
			</table>		</td>
	</tr>		
	<tr><td>&nbsp;</td></tr>				
	<tr><td>
		<div id="d-future_action">
		</div>
	</td></tr>
	<tr>
	  <td>
	  	<form name="fdoneit" method="post" action="./">
							<input type="hidden" name="CustomerID" value="" />
							<input type="hidden" name="AccountID" value="" />
							<input type="hidden" name="conf" value="" />
							<input type="hidden" name="cstatus" value="" />
							<input type="hidden" name="nstatus" value="" />
							<input type="hidden" name="nstatusid" value="" />
							<input type="hidden" name="JID" value="" />													
							<input type="hidden" name="pg" value="217" />
		</form>
	  </td>
  </tr>
</table>