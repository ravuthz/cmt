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
<script language="JavaScript" src="./javascript/ajax_getcreditrule.js"></script>
<script language="javascript" type="text/javascript">
function getcredit(){
			tid = facccredit.tid.options[facccredit.tid.selectedIndex].value;			
			url = "./php/ajax_getcreditrule.php?tid="+tid;
			getTranDetail(url, "d-creditrule");		
	}

	function showReport(){
		tid = facccredit.tid.options[facccredit.tid.selectedIndex].value;
		cid = facccredit.cid.options[facccredit.cid.selectedIndex].value;
		
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-result").innerHTML = loading;
			url = "./php/ajax_acc_credit.php?tid="+tid+"&cid="+cid;
			getTranDetail(url, "d-result");
			
	}	
	
</script>
<body>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="244" align="left" class="formtitle"><b>CREDIT RULE MANAGEMENT REPORT</b></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="facccredit" onSubmit="showReport(); return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td width="35%" align="left"> credit type:</td>
								<td width="65%" align="left">
										<select name="tid" style="width:130" onChange="getcredit();">
											<option value='0' selected="selected">All</option>
											<?php
												$sql = "select CredTypeID, CredType from tblSysCreditMgtTypeCode;";												
												$que = $mydb->sql_query($sql);									
												if($que){
													while($rst = $mydb->sql_fetchrow($que)){	
														$CredTypeID = $rst['CredTypeID'];
														$CredType = $rst['CredType'];
														
														print "<option value='".$CredTypeID."'>".$CredType."</option>";
													}
												}
												$mydb->sql_freeresult();
											?>											
										</select>
							  </td>
							</tr>
							<tr>
								<td align="left"> Credit rule:</td>
								<td align="left">
									<div id="d-creditrule">
										<select name="cid">
											<option value="0">All</option>
										</select>
									</div>
								</td>
							</tr>	
							
							<tr>
								<td>&nbsp;</td>
								<td align="left"><input type="submit" name="btnSubmit" value="View report" tabindex="4" onClick="showReport();" /></td>
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
</body>