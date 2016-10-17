<?php
	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");	
	require_once("./common/class.account.array.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	if(($conf == "yes") && ($pg==216)){
		$JID = FixQuotes($JID);
		$CustomerID = FixQuotes($CustomerID);
		$AccountID = FixQuotes($AccountID);
		$cstatus = FixQuotes($cstatus);
		$nstatus = FixQuotes($nstatus);					 
		$txtStartBillDate = FixQuotes($txtStartBillDate);		
		$hh = FixQuotes($hh);
		$mm = FixQuotes($mm);
		$ss = FixQuotes($ss);
		$txtStartBillDate .= " ".$hh.":".$mm.":".$ss;
		$now = date("Y/M/d H:i:s");
		
		$sql = "SELECT Top 1 a.TrackID
							FROM tblTrackAccount a
							WHERE a.AccID = $AccountID order by TrackID desc";
							
		if($que = $mydb->sql_query($sql)){
			$result = $mydb->sql_fetchrow($que);
			$TrackID = $result["TrackID"];
		}
		
		$account = new Account($AccountID,$TrackID);	
		
		
		if($nstatus == "Activate"){	
			$retOut = $account->Activate($txtStartBillDate);
			# Insert into product status history as status changed
			$sql = "INSERT INTO tblAccStatusHistory(AccID, StatusID, ChangeDate, OtherID, OtherText)
							VALUES(".$AccountID.", 1, '".$txtStartBillDate."', 1, 'New connect')";
			$mydb->sql_query($sql);
		}elseif(($nstatus == "Bar") || ($nstatus == "Close")){
			$retOut = $account->Close($txtStartBillDate);
			# Insert into product status history as status changed
			$sql = "INSERT INTO tblAccStatusHistory(AccID, StatusID, ChangeDate, OtherID, OtherText)
							VALUES(".$AccountID.", 3, '".$txtStartBillDate."', 3, 'Disconnect')";
			$mydb->sql_query($sql);
		}elseif($nstatus == "Reconnect"){
/*			$sql = "INSERT INTO tblTrackAccount(AccID,SubscriptionName,UserName,Password,PackageID,StatusID,RegDate,
								RegBy,Track,bHouse,bStreet,bSangkatID,bKhanID,bCityID,bCountryID,iHouse1,iStreet1,iSangkatID1,iKhanID1,iCityID1,iCountryID1,iHouse2
								,iStreet2,iSangkatID2,iKhanID2,iCityID2,iCountryID2,Context)
							select Top 1 AccID,SubscriptionName,UserName,Password,PackageID,0,RegDate
							,RegBy,Track,bHouse,bStreet,bSangkatID,bKhanID,bCityID,bCountryID,iHouse1,iStreet1,iSangkatID1,iKhanID1,iCityID1,iCountryID1,
							iHouse2,iStreet2,iSangkatID2,iKhanID2,iCityID2,iCountryID2,Context from tblTrackAccount where AccID=".$AccountID;
			$mydb->sql_query($sql);
			
*/								
			$retOut = $account->Activate($txtStartBillDate);
			# Insert into product status history as status changed
			$sql = "INSERT INTO tblAccStatusHistory(AccID, StatusID, ChangeDate, OtherID, OtherText)
							VALUES(".$AccountID.", 0, '".$txtStartBillDate."', 0, 'Reconnect')";
			$mydb->sql_query($sql);
		}
			if($retOut){
				
				$sql = "
					
					Declare @BillEndDate datetime, @BillStartDate datetime
Select @BillEndDate=Min(BillEndDate)from tblSysBillRunCycleInfo where BillProcessed=0 and PackageID = 12
Select @BillStartDate=Min(BillStartDate)from tblSysBillRunCycleInfo where BillProcessed=0 and PackageID = 12


Delete from tblAccountStatus where BillEndDate = @BillEndDate
and iCityID1 in (select distinct iCityID1  from tblTrackAccount)

Insert into tblAccountStatus
Select	@BillEndDate BillEndDate,
		ta.TrackID,
		ta.iCityID1,
		City = (select Name from tlkpLocation where id=ta.iCityID1),
		se.Description GrpSN,
		se.GroupServiceID GrpSID,
		AccStatus = Case
						When ta.StatusID = 1 and IsNull(sh.ChangeDate,ta.RegDate) < @BillStartDate then 'Exist'
						Else ta.Track
					End,
					
		ChangeDate = Case 
						when ta.StatusID = 4 then ISNULL(ta.AccountEndDate,ta.StartBillingDate)
						else IsNull(sh.ChangeDate,ta.RegDate)
					End	,					
		cp.CustID,			
		ta.AccID, 
		ta.PackageID, 
		tp.TarName, 
		ta.UserName, 
		ta.StatusID,
		st.status,
		ta.SubscriptionName, 
		cp.BillingEmail,
		ta.StartBillingDate,
		ta.NextBillingDate,
		ta.AccountEndDate,
		ta.RegDate SetupDate,
		ta.RegBy SetupBy			
			
from tblTrackAccount ta
join tblCustProduct cp on cp.AccID = ta.AccID
join tblTarPackage tp on tp.PackageID = ta.PackageID
join tlkpService se on se.ServiceID = tp.ServiceID
join tlkpStatus st on st.StatusID = ta.StatusID
left join (select AccID, Max(ChangeDate) ChangeDate from tblAccStatusHistory group by AccID) sh on sh.AccID = ta.AccID
where ta.StatusID = 1 
or (ta.StatusID=4 and IsNull(ta.AccountEndDate,ta.StartBillingDate) >= @BillStartDate)
				
				";
				
				$mydb->sql_query($sql);
				
				$sql = "UPDATE tblJobAcctStatus 
								SET IsDone = 1,
										csDate = '".$now."' 
								WHERE JobID = $JID";
				if($mydb->sql_query($sql)){

						$Audit = new Audit();
						$Description = "Change account status from $cstatus to $nstatus";
						$Audit->AddAudit($CustomerID, $AccountID, "Close pending request", $Description, $user['FullName'], 1, 6);
						redirect('./?CustomerID='.$CustomerID.'&AccountID='.$AccountID.'&pg=91');

				}
			}			
	}
?>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="javascript">
	function submitForm(){
		txtStartBillDate = factivate.txtStartBillDate.value;
		UserName = factivate.UserName.value;
		nstatus = factivate.nstatus.value;
		hh = factivate.hh.options[factivate.hh.selectedIndex].value;
		mm = factivate.mm.options[factivate.mm.selectedIndex].value;
		ss = factivate.ss.options[factivate.ss.selectedIndex].value;
		if(factivate.txtStartBillDate.value == ""){
			alert("Please enter start billing date of account to be activated");
			factivate.txtStartBillDate.focus();
			return;
		}else{
			if(confirm("Do you want to "+nstatus+" account " + UserName + " on " + txtStartBillDate + " " + hh +":" + mm + ":" + ss + "?")){
				factivate.btnSubmit.disabled = true;
				factivate.submit();
			}
		}
	}
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="50%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><?php print $nstatus; ?> Account: <b><?php print $UserName; ?></b></td>
					<td align="right">&nbsp;
						
					</td>
				</tr> 				
				<tr>
					<td colspan="2">
						<form name="factivate" method="post" action="./" onSubmit="return false;">
							<table border="0" cellpadding="3" cellspacing="0" align="left" width="100%" bgcolor="#feeac2">																
								<tr>
									<td align="left">
										<?php 
											if(($nstatus == "Bar") || ($nstatus == "Close"))
												print "Close date";
											else
												print "Start billing date:";
										?>	
									</td>
									<td align="left">
										<input type="text" tabindex="4" name="txtStartBillDate" class="boxenabled" size="12" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?factivate|txtStartBillDate', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
									</td>
									<td align="left">
										<select name="hh">
											<option value="00">00</option>
											<option value="01">01</option>
											<option value="02">02</option>
											<option value="03">03</option>
											<option value="04">04</option>
											<option value="05">05</option>
											<option value="06">06</option>
											<option value="07">07</option>
											<option value="08">08</option>
											<option value="09">09</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											<option value="13">13</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="16">16</option>
											<option value="17">17</option>
											<option value="18">18</option>
											<option value="19">19</option>
											<option value="20">20</option>
											<option value="21">21</option>
											<option value="22">22</option>
											<option value="23">23</option>											
										</select>
										<select name="mm">
											<option value="00">00</option>
											<option value="01">01</option>
											<option value="02">02</option>
											<option value="03">03</option>
											<option value="04">04</option>
											<option value="05">05</option>
											<option value="06">06</option>
											<option value="07">07</option>
											<option value="08">08</option>
											<option value="09">09</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											<option value="13">13</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="16">16</option>
											<option value="17">17</option>
											<option value="18">18</option>
											<option value="19">19</option>
											<option value="20">20</option>
											<option value="21">21</option>
											<option value="22">22</option>
											<option value="23">23</option>
											<option value="24">24</option>
											<option value="25">25</option>
											<option value="26">26</option>
											<option value="27">27</option>
											<option value="28">28</option>
											<option value="29">29</option>
											<option value="30">30</option>
											<option value="31">31</option>
											<option value="32">32</option>
											<option value="33">33</option>
											<option value="34">34</option>
											<option value="35">35</option>
											<option value="36">36</option>
											<option value="37">37</option>
											<option value="38">38</option>
											<option value="39">39</option>
											<option value="40">40</option>
											<option value="41">41</option>
											<option value="42">42</option>
											<option value="43">43</option>
											<option value="44">44</option>
											<option value="45">45</option>
											<option value="46">46</option>
											<option value="47">47</option>
											<option value="48">48</option>
											<option value="49">49</option>
											<option value="50">50</option>
											<option value="51">51</option>
											<option value="52">52</option>
											<option value="53">53</option>
											<option value="54">54</option>
											<option value="55">55</option>
											<option value="56">56</option>
											<option value="57">57</option>
											<option value="58">58</option>											
											<option value="59">59</option>
										</select>
										<select name="ss">
											<option value="00">00</option>
											<option value="01">01</option>
											<option value="02">02</option>
											<option value="03">03</option>
											<option value="04">04</option>
											<option value="05">05</option>
											<option value="06">06</option>
											<option value="07">07</option>
											<option value="08">08</option>
											<option value="09">09</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											<option value="13">13</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="16">16</option>
											<option value="17">17</option>
											<option value="18">18</option>
											<option value="19">19</option>
											<option value="20">20</option>
											<option value="21">21</option>
											<option value="22">22</option>
											<option value="23">23</option>
											<option value="24">24</option>
											<option value="25">25</option>
											<option value="26">26</option>
											<option value="27">27</option>
											<option value="28">28</option>
											<option value="29">29</option>
											<option value="30">30</option>
											<option value="31">31</option>
											<option value="32">32</option>
											<option value="33">33</option>
											<option value="34">34</option>
											<option value="35">35</option>
											<option value="36">36</option>
											<option value="37">37</option>
											<option value="38">38</option>
											<option value="39">39</option>
											<option value="40">40</option>
											<option value="41">41</option>
											<option value="42">42</option>
											<option value="43">43</option>
											<option value="44">44</option>
											<option value="45">45</option>
											<option value="46">46</option>
											<option value="47">47</option>
											<option value="48">48</option>
											<option value="49">49</option>
											<option value="50">50</option>
											<option value="51">51</option>
											<option value="52">52</option>
											<option value="53">53</option>
											<option value="54">54</option>
											<option value="55">55</option>
											<option value="56">56</option>
											<option value="57">57</option>
											<option value="58">58</option>											
											<option value="59">59</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left">(YYYY-MM-DD)</td>
									<td align="left">&nbsp;HH: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MM: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SS</td>
								</tr>
								<tr><td colspan="3">&nbsp;</td></tr>								
								<tr> 				  
								<td>&nbsp;</td>
								<td align="left">
									<input type="submit" tabindex="10" name="btnSubmit" value="Submit" class="button" onClick="submitForm();" />						
								</td>
								<td>&nbsp;</td>
							 </tr>
							 <?php
									if(isset($retOut) && (!empty($retOut))){
										print "<tr><td colspan=\"3\" align=\"left\">$retOut</td></tr>";
									}
								?>
							
							</table>						
						<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
						<input type="hidden" name="AccountID" value="<?php print $AccountID; ?>" />	
						<input type="hidden" name="UserName" value="<?php print $UserName; ?>" />	
						<input type="hidden" name="conf" value="yes" />
						<input type="hidden" name="cstatus" value="<?php print $cstatus; ?>" />
						<input type="hidden" name="nstatus" value="<?php print $nstatus; ?>" />
						<input type="hidden" name="JID" value="<?php print $JID; ?>" />
						<input type="hidden" name="pg" value="216" />

						</form>	
					</td>
				</tr>			
			</table>	
		</td>
	</tr>
</table>