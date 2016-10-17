<?php
	
/*
	+ ************************************************************************************** +	
	*																																												 *
	* This code is not to be distributed without the written permission of BRC Technology.   *
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
	* 																																											 *
	+ ************************************************************************************** +
*/
?>

<script language="javascript" type="text/javascript">
	function globalSearch(){
		txtSearch = frmGlobalSearch.txtGlobalSearch.value;
		selSearch = frmGlobalSearch.selGlobalSearch.value;		
		if(Trim(txtSearch) != ""){
			//frmGlobalSearch.submit();
			var loading;
loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
		document.getElementById("mainpoint").innerHTML = loading;
		
		url = "./search/?txtGlobalSearch="+txtSearch+"&rdaGlobalSearch="+selSearch+"&mt=" + new Date().getTime();
		getTranDetail(url, "mainpoint");
		
		}		
	}
	function cValue(val){
		frmGlobalSearch.selGlobalSearch.value = val;		
	}
</script>
<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="86" id="topbanner" background="./images/banner_bg.jpg">
	<tr>
 		<td width="230" align="center">
	 		<img src="./images/logo.jpg" alt="LOGO" border="0">				
		</td>
		<td width="200" valign="middle" align="left">
			<font color="#FFFFFF" size="-2">
			<b><?php  print $user["FullName"]; ?><br /></b>
			[<a href="./?pg=905">My profile</a> | <a href="./logout.php">Log out</a>]
			</font>
		</td>
		<td align="right" width="15" valign="middle">
			<img src="./images/frame-left.gif" border="0" />
		</td>		
		<td align="left" background="./images/frame-bg.gif" valign="bottom">
			<form name="frmGlobalSearch" action="./" method="post" onSubmit="globalSearch(); return false;">
			<table border="0" cellpadding="0" cellspacing="0" align="center">
				<tr>
					
					<td nowrap="nowrap" align="right">
						<input type="text" size="64" class="boxsearch" name="txtGlobalSearch" value="<?php print $txtGlobalSearch; ?>">						
					</td>
					<td align="left">
						<button name="imgGo" onClick="globalSearch();" class="invisibleButtons" style="width:79; height:22">
							<img src="./images/go.gif" border="0" alt="Search" class="invisibleButtons" width="79" height="22">
						</button>
					</td>
				</tr>
				<tr>
					<td align="right" colspan="2">
						<input type="radio" name="rdaGlobalSearch" value="1" onclick="cValue(1);" />Customer						
						<input type="radio" name="rdaGlobalSearch" value="3" onclick="cValue(3);" />Subscription
						<input type="radio" name="rdaGlobalSearch" value="4" checked="checked" onclick="cValue(4);" />Account
						<input type="radio" name="rdaGlobalSearch" value="5" onclick="cValue(5);" />Invoice
						<input type="radio" name="rdaGlobalSearch" value="6" onclick="cValue(6);" />Receipt
						<input type="hidden" name="selGlobalSearch" id="selGlobalSearch" value="4" />
						<a href="./?pg=890"><font size="-2">Advanced search</font></a>
					</td>
				</tr>
			</table>
			
			</form>
		</td>
		<td align="right" width="15">
			<img src="./images/frame-right.gif" border="0" />
		</td>
		<td width="10">&nbsp;</td>
 	</tr>
</table>