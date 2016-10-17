<?php
	//Prevent browser caching output
	header('Expires: Mon,26 Jun 1997 05:00:00 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').'GMT');
	header('Cached-Control: no-cache, must-revalidate');
	header('Pragma: no-cached');
	header('Content-Type: text/html');
	include ("./common/sessions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
	*
	* This code is not to be distributed without the written permission of BRC Technology.
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a> 
	* 
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="keywords" content="BRC Technology" />
<meta name="reply-to" content="supports@brc-tech.com" />
<link type="text/css" rel="stylesheet" href="./style/mystyle.css" />
<link type="text/css" rel="stylesheet" href="./style/banner.css" />
<title>..:: Wise Biller ::..</title>
<script language="javascript" src="./javascript/trim.js"></script>
<script language="javascript" src="./javascript/menu.js"></script>
<script language="JavaScript" src="./javascript/loading.js"></script>
<script language="JavaScript" src="./javascript/function.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
</head>
<body>
<script language=javascript>
	var ShowInProcess = new ShowHideProcess("<table width=800 height=460 border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>");
	ShowInProcess.Show();
</script>
<table border="0" cellpadding="0" cellspacing="5" align="center" id="background" width="100%" bordercolor="#999999">
	<tr>
		<td colspan="2">
			<?php
				require_once("./iframe/topbanner.php");
			?>
		</td>
	</tr>
	<tr>
		<td valign="top" style="border:1px solid;" width="180">
			<?php
				include("./iframe/leftmenu.php");
			?>
		</td>
		<td valign="top" width="830" align="center" id="mainarea" style="border:1px solid" bgcolor="#FFFFFF">
			<div id="mainpoint">
			<?php
				if(empty($pg) || is_null($pg)){
					include("home.php");
				}else{
					# if user is allowed to access page
					$sql = "select p.PageURL 
									from tblSecUserPage up, tblSecPage p
									where up.PageID = p.PageID 
										and p.PageCode = '".$pg."' and up.UserID = ".$user['userid']." union
									select p.PageURL
									from tblSecUserGroup ug, tblSecGroupPage gp, tblSecPage p
									where ug.GroupID = gp.GroupID and gp.PageID = p.PageID 
										and p.PageCode = '".$pg."' and ug.UserID = ".$user['userid'];

						if($que = $mydb->sql_query($sql)){
							if($result = $mydb->sql_fetchrow($que)){
								$pageurl = $result['PageURL'];								
								include($pageurl);
							}else{
								include("901.html");
							}
						}else{
							include("901.html");
						}
						$mydb->sql_freeresult();	
					}							
			?>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<div id="copyrights" style="position:relative; top:15px;"><br />
		  &nbsp;
		</div>
			<?php
				//require_once("./include/bottom.php");
			?>
		</td>		
	</tr>
</table>
<script language=javascript>
		ShowInProcess.Hide();
	</script>
</body>
</html>
