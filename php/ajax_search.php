<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");

	require_once("../common/class.advancedsearch.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/

$q = $_GET['q'];
$m = $_GET['m'];
$t = $_GET['t'];

$retOut = '<script language="javascript" type="text/javascript" src="./javascript/sorttable.js"></script>

<div id="work">

<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
	<tr>
		<td align="left" class="formtitle"><b>SEARCH RESULT FOR [<font color="#ffffff">'.$q.'</font>]</b></td>
		<td align="right"></td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">
				<tr>
					<td>';
						
							$adSearch = new AdvancedSearch($q, $m, $t);
							
							# ========================= Search Customer ========================
							switch($t){
								case 1:
									$retOut .= $adSearch->searchCustomer();	
									break;
								case 2:
									$retOut .= $adSearch->searchCustomer();	
									break;
								case 3:
									$retOut .= $adSearch->searchAccount();
									break;
								case 4:
									$retOut .= $adSearch->searchAccount();
									break;
								case 5:
									$retOut .= $adSearch->searchAccount();
									break;
								case 6:
									$retOut .= $adSearch->searchCustomer();	
									break;
								case 7:
									$retOut .= $adSearch->searchCustomer();	
									break;
								case 8:
									$retOut .= $adSearch->searchAddress();	
									break;
								case 9:
									$retOut .= $adSearch->searchAddress();	
									break;
								case 10:
									$retOut .= $adSearch->searchvoicedate();	
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