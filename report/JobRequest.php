<?php

	require_once("./common/agent.php");
	require_once("./common/configs.php");
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
	
	if(($conf == "yes") && ($pg==2226)){
		$JID = FixQuotes($JID);
		$CustomerID = FixQuotes($CustomerID);
		$AccountID = FixQuotes($AccountID);
		$DoneComment = FixQuotes($DoneComment);
		$addaudit = false;
		

		$now = date("Y/M/d H:i:s");
		
			#Activate
			$sql = "UPDATE tblJobRequestStatus SET 
								IsDone = 1,	
								DoneDate = '".$now."',
								DoneComment = '$DoneComment'
								WHERE JobID = $JID";
			$mydb->sql_query($sql);			
			
	}
?>
<script language="javascript" type="text/javascript">
	function done(ID, custid, accid,  Account){
		if(dcom = prompt("Are you sure that you have done the request for account " + Account + "?\n Please enter your comment.")){
			fdoneit.JID.value = ID;
			fdoneit.conf.value = "yes";			
			fdoneit.CustomerID.value = custid;
			fdoneit.AccountID.value = accid;
			fdoneit.DoneComment.value = dcom;
			//alert(fdoneit.swcomment.value);
			fdoneit.submit();
		}
	}
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>CUSTOMER SERVICE REPORT</b></td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th>Job ID</th>
								<th>Phone</th>
								<th>Request to</th>
								<th>Register Date</th>	
								<th>Done</th>																						
							</thead>
							<tbody>
								<?php
										$sql = "SELECT j.JobID, j.AccID,a.subscriptionname, j.RequestComment, j.SubmitDate,	a.CustID, a.UserName
														FROM tblJobRequestStatus j, tblCustProduct a, tblTarPackage p
														WHERE j.AccID = a.AccID and a.PackageID = p.PackageID 
															and (IsDone = 0 or IsDone is Null) ORDER BY j.JobID";

									if($que = $mydb->sql_query($sql)){
										while($result = $mydb->sql_fetchrow()){																
											$JobID = $result['JobID'];																
											$AccID = $result['AccID'];
											$RequestComment = $result['RequestComment'];
											$CustID = $result['CustID'];
											$UserName = $result['UserName'];
											$SubmitDate = $result['SubmitDate'];
											$linkAccount = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
											
											
											$done = "<a href=\"javascript:done(".$JobID.", ".$CustID.", ".$AccID.", '".$UserName."');\">Done?</a>";
											$iLoop++;															
											if(($iLoop % 2) == 0)
												$style = "row1";
											else
												$style = "row2";
											print '<tr>';	
											print '<td class="'.$style.'" align="right">'.$JobID.'</td>';
											print '<td class="'.$style.'" align="left">'.$linkAccount.'</td>';
											
											#outgoing
							
											print '<td class="'.$style.'" align="left">'.$RequestComment.'</td>';
											print '<td class="'.$style.'" align="left">'.formatDate($SubmitDate, 5).'</td>';
//											print '<td class="'.$style.'" align="left">                              </td>';
											print '<td class="'.$style.'" align="center">'.$done.'</td>';
											print '</tr>';
										}
									}
									$mydb->sql_freeresult();	
								?>
							</tbody>												
						</table>
						<form name="fdoneit" method="post">
							<input type="hidden" name="CustomerID" value="" />
							<input type="hidden" name="AccountID" value="" />
							<input type="hidden" name="conf" value="" />
							<input type="hidden" name="JID" value="" />													
							<input type="hidden" name="DoneComment" value="" />													
							<input type="hidden" name="DoneDate" value="" />													
							<input type="hidden" name="pg" value="2226" />
						</form>
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
