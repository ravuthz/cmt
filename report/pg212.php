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
	
		st = audit_tran.st.value;
		et = audit_tran.et.value;
		type = audit_tran.seltype.value;
		op = audit_tran.seloperator.value;

		if((st == "") || (et == "")){
			alert("Please enter from date and to date to view report");
			audit_tran.st.focus();
			return;
		}else{ 
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-audit_tran").innerHTML = loading;
			url = "./php/ajax_audit_tran.php?st="+st+"&et="+et+"&type="+type+"&op="+op;
			getTranDetail(url, "d-audit_tran");
		}
	}
</script>

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table width="687" border="0" align="left" cellpadding="2" cellspacing="0" class="formbg">
				<tr>
					<td width="677" align="left" class="formtitle"><b>&nbsp;AUDIT TRANSACTION REPORT</b></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="audit_tran" onSubmit="showReport(); return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="left" width="686" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td width="14%" align="left">From date:</td>
								<td width="37%" align="left">
									<input type="text" tabindex="1" name="st" class="boxenabled" size="15" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											&nbsp;<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?audit_tran|st', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">											</button> (yyyy-mm-dd)							  </td>
								<td width="12%" align="left">To date:</td>
							    <td width="37%" align="left"><input type="text" tabindex="2" name="et" class="boxenabled" size="15" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											&nbsp;<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?audit_tran|et', '', 'width=200,height=220,top=250,left=350');">
							  <img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">											</button> (yyyy-mm-dd)</td>
							</tr>
							<tr>
								<td align="left">Audit mode:</td>
								<td align="left">
								<select class="boxenabled" name="seltype" style="width:133px;">
									<option value="all">All</option>
                                 	<?php
									$sql = "select TypeID, TypeName from tlkpAuditType;";
									// sql 2005
									
									$que = $mydb->sql_query($sql);									
									if($que){
										while($rst = $mydb->sql_fetchrow($que)){	
											$TypeID = $rst['TypeID'];
											$TypeName = $rst['TypeName'];

											print "<option value='".$TypeID."'>".$TypeName."</option>";
										}
									}
									$mydb->sql_freeresult();
								?>
                                </select>																	</td>
								<td align="left">
									Operator: 								</td>
							    <td align="left">
								<select name="seloperator" class="boxenabled" style="width:133px;">
									<option value="all">All</option>
                                  	<?php
									$sql = "select FullName from tblSecUser;";
									// sql 2005
									
									$que = $mydb->sql_query($sql);									
									if($que){
										while($rst = $mydb->sql_fetchrow($que)){	
											$FullName = $rst['FullName'];
											
											print "<option value='".$FullName."'>".$FullName."</option>";
										}
									}
									$mydb->sql_freeresult();
								?>
                                </select></td>
							</tr>	
							
							<tr>

								<td align="center" colspan="4"><input type="button" name="btnSubmit" value="View report" tabindex="4" onclick="showReport();" /></td>
							</tr>
						</table>
						<input type="hidden" name="pg" value="212" />						
						</form>
					</td>
				</tr>
		  </table>
		</td>
	</tr>		
	<tr><td>&nbsp;</td></tr>				
	<tr><td>
		<div id="d-audit_tran">
		</div>
	</td></tr>
</table>