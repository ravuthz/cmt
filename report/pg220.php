<?php
	require_once("./common/functions.php");
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
		output = friskdebtor.output.value;
		sid = friskdebtor.sid1.value;
		if(output == 1){
			var loading;
			loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
					document.getElementById("d-riskdebtor").innerHTML = loading;
					url = "./php/ajax_riskdebter.php?sid="+sid+"&mt="+ new Date().getTime();
					getTranDetail(url, "d-riskdebtor");
		}else if(output == 2){
			url = "./report/riskdebter.php?sid="+sid+"&mt="+ new Date().getTime();
			window.open(url);
		}else{
			url = "./export/riskdebtor.php?sid="+sid+"&type=csv";
			window.open(url);
		}
	}
	function changeMode(index){
		friskdebtor.output.value = index;
	}
</script>
<body>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">	
	<tr>
		<td>
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="320" align="left" class="formtitle"><b>RISK DEBTOR REPORT</b></td>					
				</tr>
				<tr>
					<td>
						<form name="friskdebtor" onSubmit="showReport(); return false;">				
							<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa" bgcolor="white">	
								<tr>
									<td align="left">
										<input type="radio" name="viewmode" onClick="changeMode(1);">Submit <br />
										<input type="radio" name="viewmode" onClick="changeMode(2);">Preview <br />
										<input type="radio" name="viewmode" onClick="changeMode(3);" checked="checked">Download
									</td>
								</tr>
								<tr>
								<td align="left">Service:
								
									<select name="sid1">
										<option value="0" <?php if(intval($sid1) == 0) print "selected";?>>All services</option>
										<option value="2" <?php if(intval($sid1) == 2) print "selected";?>>Telephone</option>
										<option value="4" <?php if(intval($sid1) == 4) print "selected";?>>Leased line</option>
										<option value="5" <?php if(intval($sid1) == 5) print "selected";?>>ISP</option>
									</select>
								</td>
							</tr>
								<tr>
									<td align="left">
										<input type="button" name="report" value="View report" class="button" onClick="showReport();">
									</td>
								</tr>
							</table>
						<input type="hidden" name="output" value="2" />
						</form>
					</td>
				</tr>
			</table>			
		</td>
	</tr>		
	<tr><td>
		<div id="d-riskdebtor">
		</div>
	</td></tr>
</table>
</body>