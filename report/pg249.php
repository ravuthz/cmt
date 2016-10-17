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

<form name="fcloseacc" action="./" method="post">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			Are you sure you want to close account <?php print $username; ?> without running demand bill?
		</td>		
	</tr>
	<tr>
		<td align="center">
			<input type="submit" name="btnsubmit" value="Yes" />&nbsp;
			<input type="button" name="btnNo" value="No" />
		</td>
	</tr>		
</table>
</form>
