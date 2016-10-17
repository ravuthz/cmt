<?php
	require_once("./common/agent.php");	
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
<script language="JavaScript" type="text/javascript">
	function changeImage(imgCode, imgSource){
		//alert(imgSource);
			 if(imgCode == 1)
				document.customer.src = imgSource;
		else if(imgCode ==2)
				document.product.src = imgSource;
		else if(imgCode ==3)
				document.finance.src = imgSource;
		else if(imgCode ==4)
				document.usage.src = imgSource;
		else if(imgCode ==5)
				document.audit.src = imgSource;	
	}
</script>
<?php
		
	# =============== Get customer header =====================
		
		$sql = "select c.CustName, sum(d.NationalDeposit) as ncDeposit, sum(d.InternationDeposit) as 'icDeposit',
									sum(d.MonthlyDeposit) as mfDeposit, sum(b.Credit) as Credit, sum(b.Outstanding) as Outstanding 
						from tblCustomer c, tblCustProduct a, tblAccountBalance b, tblAccDeposit d
						where c.CustID = a.CustID and a.AccID = b.AccID and a.AccID = d.AccID and c.CustID=$CustomerID
						group by c.CustName";
						
		if($que = $mydb->sql_query($sql)){
			if($rst = $mydb->sql_fetchrow($que)){
				$CustName = $rst['CustName'];
				$ncDeposit = $rst['ncDeposit'];
				$icDeposit = $rst['icDeposit'];
				$mfDeposit = $rst['mfDeposit'];
				$Deposit = $ncDeposit + $icDeposit + $mfDeposit;
				$Deposit = FormatCurrency($Deposit);
				$Credit = FormatCurrency($rst['Credit']);
				$Outstanding = FormatCurrency($rst['Outstanding']);
			}
		}
		
		$mydb->sql_freeresult();
		
	# =============== Get customer information =====================	
	$sql = "select cu.CustName, ct.CustTypeID, ct.CustTypeName, cu.VATNumber, cu.IsVATException, cu.RegisteredDate, inv.InvoiceType,
							cu.IdentityData, cu.BillingEmail, cu.BusinessReg, cu.IdentityMode, cu.DOB, cu.Salutation, me.Salutation as mS, me.Name,
							l1.[name] as Country, l2.[name] as City, l3.[name] as Khan, l4.[name] as Sangkat, ad.Address,
							na.Country, ca.Career	
					from tblCustomer cu, tlkpCustType ct, tlkpCustInvoiceType inv, tblCustAddress ad, tlkpLocation l1, 
							tlkpLocation l2, tlkpLocation l3, tlkpLocation l4, tlkpMessenger me, tlkpCountry na, tlkpCareer ca			
					where cu.CustTypeID = ct.CustTypeID and cu.CustID = ad.CustID and cu.InvoiceTypeID = inv.InvoiceTypeID 
						and ad.CountryID = l1.id and ad.CityID = l2.id and ad.KhanID = l3.id and ad.SangkatID = l4.id 
						and cu.MessengerID = me.MessengerID	and ad.IsBillingAddress = 0 
						and na.CountryID = cu.NationalityID and ca.CareerID = cu.OccupationID and cu.CustID=$CustomerID";
					
	if($que = $mydb->sql_query($sql)){
		if($rst = $mydb->sql_fetchrow($que)){
			$CustName = $rst['CustName'];
			$CustTypeID = $rst['CustTypeID'];
			$CustTypeName = $rst['CustTypeName'];
			$VATNumber = $rst['VATNumber'];
			$IsVATException = $rst['IsVATException'];
			$RegisteredDate = $rst['RegisteredDate'];
			$IdentityData = $rst['IdentityData'];
			$BillingEmail = $rst['BillingEmail'];
			$BusinessReg = $rst['BusinessReg'];
			$IdentityMode = $rst['IdentityMode'];
			$DOB = $rst['DOB'];		
			$Salutation = $rst['Salutation'];		
			$Country = $rst['Country'];		
			$City = $rst['City'];		
			$Khan = $rst['Khan'];		
			$Sangkat = $rst['Sangkat'];					
			$Address = $rst['Address'];
			$Country = $rst['Country'];
			$Career = $rst['Career'];				
			$InvoiceType = $rst['InvoiceType'];			
			$mS = $rst['mS'];			
			$mName = $rst['Name'];			
			$ResidentAddress = $Address.", ".$Sangkat.", ".$Khan.", ".$City.", ".$Country;	
			if($IsVATException)
				$VATCharge = "No";
			else
				$VATCharge = "Yes";
			$Messenger = $mS." ".$mName;	
			$CustName = $Salutation." ".$CustName;
			$Duplicate = $IdentityMode."- ".$IdentityData;
		}
	}
	$mydb->sql_freeresult();			
?>
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bordercolor="#aaaaaa" style="border-collapse:collapse">
		<tr>
			<td valign="top"  height="50">
					<table border="0" cellpadding="4" cellspacing="0" width="100%">
						<tr>
							<td align="left">ID: <b><?php print $CustomerID ?></b></td>						
							<td align="left" colspan="2">Name:<b><?php print $CustName ?></b></td>						
						</tr>
						<tr>
							<td align="left">Deposit: <b><?php print $Deposit; ?></b></td>						
							<td align="left">Balance: <b><?php print $Credit; ?></b></td>						
							<td align="left">Invoice: <b><?php print $Outstanding; ?></b></td>
						</tr>
					</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<!-- Individual customer tab menu -->
				<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" bordercolor="#ffffff" align="center">
					 <tr>
						<td align="left" width="15" background="./images/tab_null.gif">&nbsp;</td>						
						
						<td align="left" width="85"><img src="./images/tab/customer_active.gif" name="customer" border="0" id="customer" /></td>						
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=90"><img src="./images/tab/product.gif" name="product" border="0" id="product" onmouseover="changeImage(2, './images/tab/product_over.gif');" onmouseout="changeImage(2, './images/tab/product.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=41"><img src="./images/tab/finance.gif" name="finance" border="0" id="finance" onmouseover="changeImage(3, './images/tab/finance_over.gif');" onmouseout="changeImage(3, './images/tab/finance.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=70"><img src="./images/tab/usage.gif" name="usage" border="0" id="usage" onmouseover="changeImage(4, './images/tab/usage_over.gif');" onmouseout="changeImage(4, './images/tab/usage.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID;?>&pg=30"><img src="./images/tab/audit.gif" name="audit" border="0" id="audit" onmouseover="changeImage(5, './images/tab/audit_over.gif');" onmouseout="changeImage(5, './images/tab/audit.gif');"/></a></td>						
						
						<td align="center" width="*" background="./images/tab_null.gif">&nbsp;</td>		
					</tr>
				</table>
					<!-- end customer table menu -->			
			</td>
		</tr>
		<tr>
			<td height="100%" valign="top">
					<!-- Individual customer main page -->				
					<table border="0" cellpadding="0" cellspacing="5" width="100%" height="100%" align="left">
						
						<tr>
							<td width="150"><?php include("content.php");?></td>
							<!-- Customer information -->
							<td align="left" valign="top">
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
									 	<td align="left" class="formtitle"><b>CUSTOMER INFORMATION</b></td>
										<td align="right">[<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=12">Edit</a>]</td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">
												<tr>
													<td align="left">Name:</td>
													<td align="left" colspan="3"><b><?php print $CustName; ?></b></td>
												</tr>
												<tr>
													<td align="left" width="20">Type:</td>
													<td align="left" width="30%"><b><?php print $CustTypeName; ?></b></td>
													<?php 
														if($CustTypeID = 1){
															print '<td align="left"">Date of birth:</td>';
															print '<td align="left" width="30%"><b>'.formatDate($DOB, 3).'</b></td>';
														}else{
															print '<td align="left">Business No.:</td>';
															print '<td align="left" width="30%"><b>'.$BusinessReg.'</b></td>';
														}
													?>													
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">Duplicate:</td>
													<td align="left"><b><?php print $Duplicate; ?></b></td>
													<td align="left" nowrap="nowrap">Registered date:</td>
													<td align="left"><b><?php print formatDate($RegisteredDate, 3); ?></b></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">VAT charge:</td>
													<td align="left"><b><?php print $VATCharge; ?></b></td>
													<td align="left" nowrap="nowrap">VAT number:</td>
													<td align="left"><b><?php print $VATNumber; ?></b></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">Occupation:</td>
													<td align="left"><b><?php print $Career; ?></b></td>
													<td align="left" nowrap="nowrap">Nationality:</td>
													<td align="left"><b><?php print $Country; ?></b></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">Invoice type:</td>
													<td align="left"><b><?php print $InvoiceType; ?></b></td>
													<td align="left" nowrap="nowrap">Messenger:</td>
													<td align="left"><b><?php print $Messenger; ?></b></td>
												</tr>
												<tr>
													<td align="left" valign="top">Address:</td>
													<td align="left" colspan="3"><b><?php print $ResidentAddress; ?></b></td>
												</tr>																																																
											</table>
										</td>
									</tr>
								</table>	
							</td>						
						</tr>
						<!-- customer information -->						
						<tr>
							<td height="100%">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
			
</table>
<?php
# Close connection
$mydb->sql_close();
?>
