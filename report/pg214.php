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
		typeid = frmbusiness.selbusiness.value;

		if(typeid == ""){
			alert("Please select service and package to view report");
			frmbusiness.selbusiness.focus();
			return;
		}else{ 
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-business_report").innerHTML = loading;
			url = "./php/ajax_business_report.php?typeid="+typeid;
			getTranDetail(url, "d-business_report");
		}
	}
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table width="278" border="0" align="left" cellpadding="2" cellspacing="0" class="formbg">
				<tr>
					<td width="258" align="left" class="formtitle"><b>&nbsp;BUSINESS TYPE  REPORT</b></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="frmbusiness" onSubmit="showReport(); return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td height="72" align="center">
								<fieldset><legend>Select Business Type</legend>
								
    <table border="0" width="280">
										<tr><td width="45%" align="left"><div style="vertical-align:bottom">&nbsp;Business Type :</div></td>
											<td width="55%" align="left" valign="top">
										<select name="selbusiness" style="width:130; height:10px">
                                 	<?php
									$sql = "select TypeID, [Name] from tlkpCustBusinessType";
									// sql 2005
									$que = $mydb->sql_query($sql);									
									if($que){
										while($rst = $mydb->sql_fetchrow($que)){	
											$TypeID = $rst['TypeID'];
											$Name = $rst['Name'];
											print "<option value='".$TypeID."'>".$Name."</option>";
										}
									}
									$mydb->sql_freeresult();
								?>
                                  <option value='0'>All</option>
                               		 </select><br />
									</td>
									</tr>
									<tr>
										<td height="30" colspan="2" align="center" valign="bottom">
										<input type="submit" name="btnSubmit" value="View report" tabindex="4" onClick="showReport();" />
									  </td>
								  </tr></table>
								  </fieldset>
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
		<div id="d-business_report">
		</div>
	</td></tr>
</table>