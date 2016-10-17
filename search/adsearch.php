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

	function showResult(){
		q = fAdSearch.adSearch.value;
		m = fAdSearch.adMode.value;
		t = fAdSearch.adType.options[fAdSearch.adType.selectedIndex].value;

		if(Trim(q) != "") { 
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-adSearch").innerHTML = loading;
			url = "./php/ajax_search.php?q="+q+"&t="+t+"&m="+m;
			getTranDetail(url, "d-adSearch");
		}
	}
</script>
<body>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="244" align="left" class="formtitle"><b>&nbsp;Advanced search</b></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="fAdSearch" onSubmit="showResult(); return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa" bgcolor="#ffffff">	
							<tr>
								<td align="left" valign="top">
									<fieldset>
										<legend align="center">Search mode</legend>
										<table border="0" cellpadding="3" cellspacing="0" align="left">
											<tr>
												<td align="left">
												<input type="radio" name="adsMode" value="1" checked="checked" onClick="fAdSearch.adMode.value='1';" /> 
													Fuzzy search
												</td>
											</tr>
											<tr>
												<td align="left">
												<input type="radio" name="adsMode" value="2" onClick="fAdSearch.adMode.value='2';" /> 
													Exact search
												</td>
											</tr>
										</table>
									</fieldset>
								</td>
								<td align="left" valign="top">
									<fieldset>
										<legend align="center">Search</legend>
										<table border="0" cellpadding="3" cellspacing="0" align="left">
											<tr>
												<td align="left">Search: </td>
												<td align="left" colspan="2">
													<input type="text" name="adSearch" value="<?php print $adSearch; ?>" class="boxsearch" size="40" />
												</td>
											</tr>
											<tr>
												<td align="left">In: </td>
												<td align="left">
													<select name="adType" style="width:200px">
														<option value="1">Customer ID</option>
														<option value="2">Customer Name</option>
														<option value="3">Account ID</option>
														<option value="4">UserName</option>
														<option value="5" selected="selected">Subscription Name</option>
														<option value="6">Billing Email</option>
														<option value="7">Contact Telephone</option>
														<option value="8">Installation Address</option>
														<option value="9">Billing Address</option>
													</select>
												</td>
												<td align="left">
													<button name="imgSearch" onClick="showResult();" class="invisibleButtons" style="width:79; height:22">
														<img src="./images/go.gif" border="0" alt="Search" class="invisibleButtons" width="79" height="22">
													</button>
												</td>
											</tr>
										</table>
									</fieldset>
								</td>
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
		<div id="d-adSearch">
		</div>
	</td></tr>
</table>
</body>