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
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<script language="JavaScript" src="./javascript/trim.js"></script>
<script language="javascript" type="text/javascript">		
	
	function Maximize(URL) 
	{
		window.open(URL,"Max","resizable=1,toolbar=1,location=1,directories=1,addressbar=1,scrollbars=1,status=1,menubar=1,maximize=1,top=0,left=0,width=1100,height=700");
	//window.open(URL,"Max","resizable=yes,location=yes,toolbar=yes,menubar=yes,addressbar=yes,maximize=yes,directories=1,top=0,left=0,width=500,height=660");
	}

	function showResult(){
		q = frmInvSearch.txtSearch.value;
		t = frmInvSearch.dropType.options[frmInvSearch.dropType.selectedIndex].value;

		if(Trim(q) != "") { 
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-InvSearch").innerHTML = loading;
			url = "./php/ajax_invsearch.php?q="+q+"&t="+t+"&mt="+ new Date().getTime();
			getTranDetail(url, "d-InvSearch");
		}
	}
	function setfocus(){
		document.getElementById("txtSearch").focus();
	}
</script>
<body onLoad="setfocus();">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="244" align="left" class="formtitle"><b>&nbsp;Unpaid Invoice Search</b></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="frmInvSearch" onSubmit="showResult(); return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa" bgcolor="#ffffff">	
							<tr>
								<td align="left" valign="top">
									<fieldset>
										<legend align="center">Search</legend>
										<table border="0" cellpadding="3" cellspacing="0" align="left">
											<tr>
												<td align="left">Search: </td>
												<td align="left" colspan="2">
													<input type="text" name="txtSearch" value="<?php print $adSearch; ?>" class="boxsearch" size="40" />												</td>
											</tr>
											<tr>
												<td align="left">In: </td>
												<td align="left">
													<select name="dropType" style="width:200px">
														<option value="1">Customer ID</option>
														<option value="2">Customer Name</option>
														<option value="3">Account ID</option>
														<option value="4">Invoice ID</option>
														<option value="5" selected="selected">Account Name</option>
														<option value="6">SubscriptionName</option>
													</select>												</td>
												<td align="left">
													<button name="imgSearch" onClick="showResult();" class="invisibleButtons" style="width:79; height:22">
														<img src="./images/go.gif" border="0" alt="Search" class="invisibleButtons" width="79" height="22">													</button>												</td>
											</tr>
										</table>
									</fieldset>								</td>
							</tr>																				
						</table>						
						<input type="hidden" name="adMode" value="1" />						
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>		
	<tr><td>&nbsp;</td></tr>				
	<tr><td>
		<div id="d-InvSearch">
		</div>
	</td></tr>
</table>
</body>