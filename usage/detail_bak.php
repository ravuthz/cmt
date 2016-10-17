<?php
	require_once("./common/functions.php");
	require("./common/configs.php");
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
<script language="JavaScript" src="./javascript/ajax_detail.js"></script>
<script language="JavaScript" src="./javascript/ajax_detail_tra.js"></script>
<script language="javascript" type="text/javascript">
	
	function showDetail(){	
	

			if(detail.selAcc.selectedIndex<0)
			{
				alert("Please account to view detail.");
				return;
			}
			calltype="";
			if(detail.chkMobile.checked)
			{
				calltype+=",'Mobile'";
			}
			if(detail.chkLDC.checked)
			{
				calltype+=",'LDC'"
			}
			if(detail.chkLOC.checked)
			{
				calltype+=",'LOC'";
			}
			if(detail.chkIDD.checked)
			{
				calltype+=",'IDD'";
			}

			cid = detail.selCycle.options[detail.selCycle.selectedIndex].value;
			aid = detail.selAcc.options[detail.selAcc.selectedIndex].value;

			url = detail.webroot.value+"PrintingDetailbyAccID.aspx?TrackID="+aid+"&BillEndDate="+cid+"&CallType="+calltype.substring(1)+"&mt="+ new Date().getTime();
			window.open(url);	
	}
	
	function showInvoice(){	
	

			if(detail.selAcc.selectedIndex<0 && detail.rblStyle[4].checked==false)
			{
				alert("Please account to view detail.");
				return;
			}
		
			if(detail.rblStyle[4].checked)
			{
				url = detail.webroot.value+"PrintingInvoiceID.aspx?InvoiceID="+detail.txtSearchText.value+"&mt="+ new Date().getTime();
				window.open(url);		
			}
			else
			{
				cid = detail.selCycle.options[detail.selCycle.selectedIndex].value;
				aid = detail.selAcc.options[detail.selAcc.selectedIndex].value;
				url = detail.webroot.value+"PrintingAccID.aspx?AccID="+aid+"&BillEndDate="+cid+"&mt="+ new Date().getTime();
				window.open(url);		
			}
	}
	
	
	function ValidTextSearch(){
		if(detail.rblStyle[0].checked)
		{
			location_tra(7, detail.txtSearchText.value, "selAcc");
		}
		else if(detail.rblStyle[1].checked)
		{
			location_tra(8, detail.txtSearchText.value, "selAcc");
		}
		else if(detail.rblStyle[2].checked)
		{
			location_tra(9, detail.txtSearchText.value, "selAcc");
		}
		else if(detail.rblStyle[3].checked)
		{
			location_tra(10, detail.txtSearchText.value, "selAcc");
		}
		else if(detail.rblStyle[4].checked) 
		{
			url = detail.webroot.value+"PrintingInvoiceID.aspx?InvoiceID="+detail.txtSearchText.value+"&mt="+ new Date().getTime();
			window.open(url);	
		}
	}
	
	function disa(th){
		if(th.value!='InvoiceID')
		{
			detail.selCycle.disabled=false;
			detail.selAcc.disabled=false;
		}
		else
		{	
			detail.selCycle.disabled=true;
			detail.selAcc.disabled=true;
		}			
	}
	
</script>
<body>
<table border="0" cellpadding="0" cellspacing="5" align="left" >
	<tr>		
		<td width="769" align="left" valign="top">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
				
					<td width="508"  align="left" class="formtitle"><b>Printing Multiple Invoices Type

 </b></td>
					<td width="248" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="detail">
						<table width="100%" height="100%" border="0" align="center" cellpadding="3" cellspacing="0" bordercolor="#aaaaaa" class="sortable" id="audit3" style="border-collapse:collapse">
							<tr>
							  <td align="left">&nbsp;</td>
							  <td align="left">&nbsp;</td>
							  <td align="left">&nbsp;</td>
							  <td align="left">&nbsp;</td>
						  </tr>
							<tr>
							  <td width="9%" align="left">&nbsp;</td>
								<td width="26%" align="left"><SPAN id="ctl00_ContentPlaceHolder1_Label2">Do you want to   search by ?</SPAN></td>	
								<td width="31%" align="left"><SPAN id="ctl00_ContentPlaceHolder1_Label5">Type fuzzy search text in here :</SPAN></td>		
								<td width="34%" align="left"><SPAN id="ctl00_ContentPlaceHolder1_Label4">Verify Bill End Date :</SPAN></td>														
							</tr>
							
							<tr>
							  <td rowspan="3" align="left">&nbsp;</td>
								<td rowspan="3" align="left"><p>
								  <label>
								    <input name="rblStyle" type="radio" value="Telephone" onClick="disa(this);" checked>
								    Telephone</label>
								  <br>
								  <label>
								    <input type="radio" name="rblStyle" value="SubscriptionName" onClick="disa(this);">
								    SubscriptionName</label>
								  <br>
								  <label>
								    <input type="radio" name="rblStyle" value="CustomerName" onClick="disa(this);">
								    CustomerName</label>
								  <br>
								  <label>
								    <input type="radio" name="rblStyle" value="AccountID" onClick="disa(this);">
								    AccountID</label>
								  <br>
								  <label>
								    <input type="radio" name="rblStyle" value="InvoiceID" onClick="disa(this);">
								    InvoiceID</label>
								  <br>
							    </p></td>
								
								<td align="left"><input type="text" name="txtSearchText" id="txtSearchText" value="<?php print $txtSearchText;?>" tabindex="1" class="boxenabled" size="27" maxlength="30" onBlur="ValidTextSearch();"/></td>
								
								<td align="left"><select name="selCycle" style="width:150px">
                                  <?php
											$sql = "Select Min(BillEndDate) BillEndDate,Null [option] from tblSysBillRuncycleInfo where BillProcessed = 0 Union SELECT DISTINCT BillEndDate,(Select Max(BillEndDate) from tblSysBillRuncycleInfo where BillProcessed = 1) [option] FROM tblSysBillRUnCycleInfo WHERE BillProcessed = 1 ORDER BY BillEndDate DESC";
											if($que = $mydb->sql_query($sql)){
												while($result = $mydb->sql_fetchrow($que)){
													$BillEndDate = $result['BillEndDate'];
													$option = $result['option'];
													$selected='';
													if($option==$BillEndDate)
													{
														$selected='selected';
													}
													
													print "<option value='".FormatDate($BillEndDate,10)."' $selected>".FormatDate($BillEndDate, 10)."</option>";
												}
											}
											$mydb->sql_freeresult($que);
										?>
                                </select></td>					
							</tr>
							<tr>
							  <td colspan="2" align="left"><SPAN id="ctl00_ContentPlaceHolder1_Label3">Then, Choose the   correct account or subscription name :</SPAN></td>
						  </tr>
							<tr>
							  <td colspan="2" align="left">
							  	<select name="selAcc" id="selAcc" class="boxenabled" style="width:386px"></select>							  </td>
						  </tr>
							<tr>
							  <td align="left">&nbsp;</td>
							  <td align="left">&nbsp;</td>
							  <td colspan="2" align="left">&nbsp;</td>
						  </tr>
							<tr>
							  <td align="left">&nbsp;</td>
							  <td colspan="3" align="left"><label>
							    <input type="checkbox" name="chkMobile" value="Mobile">
							    Mobile</label>
                                <label>
                                <input type="checkbox" name="chkLOC" value="LOC">
LOC
<input name="chkLDC" type="checkbox" value="LDC" checked>
LDC
<input name="chkIDD" type="checkbox" value="IDD" checked>
IDD</label></td>
						  </tr>
							<tr>
							  <td colspan="4">&nbsp;</td>
						  </tr>
							<tr>
								<td colspan="4">
									<div id="d-package"></div>								</td>
							</tr>			
							<tr>								
							  <td align="center" colspan="4">
									<p>
									  <input type="button" name="btnDetail" value="View Detail" onClick="showDetail();" /> &nbsp;&nbsp;
									   <input type="button" name="btnInvoice" value="View Invoice" onClick="showInvoice();" />
								</p>
									<p>&nbsp;</p></td> 
							</tr>
						</table>						
						<input type="hidden" name="webroot" id="webroot" value="<?php echo $WebQuickBillRoot?>" />
						</form>
					</td>
				</tr>
			</table>
	  </td>
	</tr>		
	<tr><td colspan="2">&nbsp;</td></tr>				
	<tr><td colspan="2">
		<div id="d-invoicereport">
		</div>
	</td></tr>
</table>	
</body>