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
<script language="javascript" type="text/javascript">
		

	function showReport(){
		mid = frmmessenger.selMessenger.value;		
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-report").innerHTML = loading;
			url = "./php/ajax_messenger.php?mid="+mid+"&mt="+ new Date().getTime();
			getTranDetail(url, "d-report");
		
	}
	function showlevel2(div, mid, sid, mname){
		mid = frmmessenger.selMessenger.value;		
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById(div).innerHTML = loading;
			url = "./php/ajax_messenger1.php?div="+div+"&mid="+mid+"&sid="+sid+"&mname="+mname+"&mt="+ new Date().getTime();
			getTranDetail(url, div);
		
	}
	
	function hide(div){
		document.getElementById(div).innerHTML = "";
	}
</script>
<body>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="244" align="left" class="formtitle"><b>MESSENGER REPORT</b></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="frmmessenger" onSubmit="showReport(); return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td width="35%" align="left">Messenger:</td>
								<td width="65%" align="left">
										<select name="selMessenger">
											<option value='0'>All</option>
											<?php
									$sql = "select MessengerID, Salutation, Name from tlkpMessenger;";
									// sql 2005
									
									$que = $mydb->sql_query($sql);									
									if($que){
										while($rst = $mydb->sql_fetchrow($que)){	
											$MessengerID = $rst['MessengerID'];
											$Salutation = $rst['Salutation'];
											$Name = $rst['Name'];
											$Mname = $Salutation." ".$Name;
											
											print "<option value='".$MessengerID."'>".$Mname."</option>";
										}
									}
									$mydb->sql_freeresult();
								?>
								
										</select>
							  </td>
							</tr>															
							<tr>
								<td>&nbsp;</td>
								<td align="left"><input type="button" name="btnSubmit" value="View report" tabindex="4" onClick="showReport(); return false;" /></td>
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
		<div id="d-report">
		</div>
	</td></tr>
</table>
</body>