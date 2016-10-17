<?php
	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");
	require_once("./common/class.security.php");
	require_once("./common/functions.php");
/*
	+ ************************************************************************************** +	
	*																																												 *
	* This code is not to be distributed without the written permission of BRC Technology.   *
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
	* 																																											 *
	+ ************************************************************************************** +
*/
	
	$c = FixQuotes($c);
	$q = FixQuotes($q);


?>

<script language="javascript">
	function searchpage(){
		if(fsearchpage.q.value != "")
			fsearchpage.submit();
	}
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="150">
			<?php include("content.php"); ?>
		</td>
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="center" width="100%">			  
				<tr>
				 <td align="left" class="formtitle" height="18"><b>LIST PAGES</b></td>
				 <td align="right" class="formtitle" valign="middle">
				 		<form name="fsearchpage" action="./" method="post">
						<table border="0" cellpadding="0" cellspacing="0" align="right">
							<tr>
								<td>
									<select name="c" class="boxenabled">
										<option value="0" <?php if($c==0) print "selected"; ?>>All</option>
										<option value="1" <?php if($c==1) print "selected"; ?>>Code</option>
										<option value="2" <?php if($c==2) print "selected"; ?>>URL</option>
										<option value="3" <?php if($c==3) print "selected"; ?>>Name</option>
									</select>
								</td>
								<td>
									<input type="text" name="q" class="boxsearch" size="30" value="<?php print $q;?>" />
								</td>
								<td>
									<button name="imgGo" onClick="searchpage();" class="invisibleButtons" style="width:20; height:20">
										<img src="./images/go1.gif" border="0" alt="Search" class="invisibleButtons" width="20" height="20">
									</button>
								</td>
							</tr>
						</table>
						<input type="hidden" name="pg" value="914" />
						</form>												
				 </td>
			  </tr>								
				<tr>
					<td align="left" colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>					
								<th>No.</th>
								<th>Code</th>
								<th>URL</th>
								<th>Name</th>
								<th>Description</th>
								<th>Edit</th>
							</thead>
							<tbody>
							<?php								
								$iLoop = 0;
								$sql = "SELECT PageID, PageCode, PageURL, PageName, Description FROM tblSecPage order by PageCode ";
								if($c == 1)
									$sql .= "WHERE PageCode like '%".$q."%'";
								elseif($c == 2)
									$sql .= "WHERE PageURL like '%".$q."%'";
								elseif($c == 3)
									$sql .= "WHERE PageName like '%".$q."%'";

								if($que = $mydb->sql_query($sql)){				
									while($result = $mydb->sql_fetchrow($que)){
										$PageID = $result['PageID'];
										$PageCode = $result['PageCode'];
										$PageURL = $result['PageURL'];
										$PageName = $result['PageName'];
										$Description = $result['Description'];

										
										$edit = "<a href='./?pid=".$PageID."&pg=915'><img src='./images/Edit.gif' border=0 alt='Edit page' /></a>";
										$iLoop++;
										if(($iLoop % 2) == 0)
											$style = "row1";
										else
											$style = "row2";
										print '<tr>';	
										print '<td class="'.$style.'" align="left">'.$iLoop.'</td>';
										print '<td class="'.$style.'" align="left">'.$PageCode.'</td>';
										print '<td class="'.$style.'" align="left">'.$PageURL.'</td>';									
										print '<td class="'.$style.'" align="left">'.$PageName.'</td>';	
										print '<td class="'.$style.'" align="left">'.$Description.'</td>';		
										print '<td class="'.$style.'" align="left">'.$edit.'</td>';		
										print '</tr>';
									}
								}
								$mydb->sql_freeresult();	
							?>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>


<?php
# Close connection
$mydb->sql_close();
?>
