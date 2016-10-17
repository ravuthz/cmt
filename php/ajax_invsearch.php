<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");
	require_once("../common/class.invoicesearch.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/

$text = $_GET['q'];
$type = $_GET['t'];

$retOut = '<script language="javascript" type="text/javascript" src="./javascript/sorttable.js"></script>

<div id="work">
<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
	<tr>
		<td align="left" class="formtitle"><b>SEARCH RESULT FOR [<font color="#ffffff">'.$text.'</font>]</b></td>
		<td align="right"></td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">
				<tr>
					<td>';
						
							$invSearch = new InvoiceSearch($text, $type);
							
							$retOut .= $invSearch->search();
$retOut .=	'</td>
				</tr>
			</table>
		</td>
	</tr>
</table>	
</div>';

print $retOut;
?>