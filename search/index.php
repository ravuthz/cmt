<script language="javascript" type="text/javascript">		
	
	function Maximize(URL) 
	{
		//window.open(URL,"Max","resizable=1,toolbar=1,location=1,directories=1,addressbar=1,scrollbars=1,status=1,menubar=1,maximize=1,top=0,left=0,screenX=" + window.screenLeft + ",screenY=" + window.screenTop + ",width=" + screen.availWidth + ",height=" + screen.availHeight);
		window.open(URL,null,"fullscreen=yes,resizable=yes,location=yes,toolbar=yes,menubar=yes,addressbar=no,,top=0,left=0");
	}
</script>
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");

	require_once("../common/search.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/


$retOut = '<script language="javascript" type="text/javascript" src="./javascript/sorttable.js"></script>

<div id="work">

<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
	<tr>
		<td align="left" class="formtitle"><b>SEARCH RESULT FOR [<font color="#ffffff">'.$txtGlobalSearch.'</font>]</b></td>
		<td align="right"></td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">
				<tr>
					<td>';
						
							$mysearch = new search($txtGlobalSearch);
							
							# ========================= Search Customer ========================
							switch($rdaGlobalSearch){
								case 1:
									$retOut .= $mysearch->searchCustomer();	
									break;								
								case 3:
									$retOut .= $mysearch->searchSubscription();
									break;
								case 4:
									$retOut .= $mysearch->searchAccount();
									break;
								case 5:
									$retOut .= $mysearch->searchInvoice();
									break;
								case 6:
									$retOut .= $mysearch->searchPayment();
									break;	
							}														
						
$retOut .=	'</td>
				</tr>
			</table>
		</td>
	</tr>
</table>	
</div>';

print $retOut;
?>