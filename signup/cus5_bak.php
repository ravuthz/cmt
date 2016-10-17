<?php
	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");	
	require_once("./common/class.invoice.php");	
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	function getService($ServiceID){
		global $mydb;
		$sql = "select ServiceName from tlkpService where ServiceID=$ServiceID";
		$que = $mydb->sql_query($sql);
		if($que){
			$rst = $mydb->sql_fetchrow($que);
			$ServiceName = $rst['ServiceName'];
		}
		$mydb->sql_freeresult();
		return $ServiceName;
	}
	
	# ================= Save =======================
	if(!empty($smt) && isset($smt) && ($smt == "save")){
	

		# --------------- Audit ----------------------
		$audit = new Audit();
				
		//
		// Customer profile
		//
		$selCustSalutation = FixQuotes($selCusSalutation);
		$txtCustomerName = FixQuotes($txtCustomerName);
		$txtCustDOB = FixQuotes($txtCusDOB);				
		$txtCustBusNo = FixQuotes($txtCusBus);						
		$selCustDuplicateID = FixQuotes($selCusDuplicateID);						
		$CustDuplicate = FixQuotes($txtCusDuplicate);								
		$radExemption = FixQuotes($radExemption);
		$txtVATNumber = FixQuotes($txtVATNumber);						
		$selCustNationality = FixQuotes($selCusNationality);		
		$selCustOccupation = FixQuotes($selCusOccupation);
		$radCustType = FixQuotes($radCustType);
		$selCusCountry = FixQuotes($selCusCountry);
		$selCusCity = FixQuotes($selCusCity);
		$selCusKhan = FixQuotes($selCusKhan);
		$selCusSangkat = FixQuotes($selCusSangkat);
		$selBusinessType = FixQuotes($selBusinessType);
		$txtCusEmail = FixQuotes($txtCusEmail);
		$txtCusPhone = FixQuotes($txtCusPhone);
		$txtCusAddress = FixQuotes($txtCusAddress);
		
		
		//
		//	Designate profile
		//
		$selDesSalutation = FixQuotes($selDesSalutation);
		$txtDesignateName = FixQuotes($txtDesignateName);
		$txtDesDOB = FixQuotes($txtDesDOB);
		$selDesDuplicateID = FixQuotes($selDesDuplicateID);
		$txtDesDuplicate = FixQuotes($txtDesDuplicate);
		$DesPhone = FixQuotes($txtDesPhone);
		$DesEmail = FixQuotes($txtDesEmail);
		$selDesNationality = FixQuotes($selDesNationality);
		$selDesOccupation = FixQuotes($selDesOccupation);
		$txtDesAddress = FixQuotes($txtDesAddress);
		$selDesSangkat = FixQuotes($selDesSangkat);
		$selDesKhan = FixQuotes($selDesKhan);
		$selDesCity = FixQuotes($selDesCity);
		$selDesCountry = FixQuotes($selDesCountry);
		
		//
		//	Guarranter profile
		//
		$selGuaSalutation = FixQuotes($selGuaSalutation);
		$txtGarrentorName = FixQuotes($txtGarrentorName);
		$txtGuaDOB = FixQuotes($txtGuaDOB);
		$selGuaDuplicateID = FixQuotes($selGuaDuplicateID);
		$txtGuaDuplicate = FixQuotes($txtGuaDuplicate);
		$GuaPhone = FixQuotes($txtGuaPhone);
		$GuaEmail = FixQuotes($txtGuaEmail);
		$selGuaNationality = FixQuotes($selGuaNationality);
		$selGuaOccupation = FixQuotes($selGuaOccupation);
		$txtGuaAddress = FixQuotes($txtGuaAddress);
		$selGuaSangkat = FixQuotes($selGuaSangkat);
		$selGuaKhan = FixQuotes($selGuaKhan);
		$selGuaCity = FixQuotes($selGuaCity);
		$selGuaCountry = FixQuotes($selGuaCountry);
		
		//
		//	Contact profile
		//
		$selConSalutation = FixQuotes($selConSalutation);
		$txtContactName = FixQuotes($txtContactName);
		$txtConDOB = FixQuotes($txtConDOB);
		$selConDuplicateID = FixQuotes($selConDuplicateID);
		$txtConDuplicate = FixQuotes($txtConDuplicate);
		$ConPhone = FixQuotes($txtConPhone);
		$ConEmail = FixQuotes($txtConEmail);
		$selonNationality = FixQuotes($selConNationality);
		$selConOccupation = FixQuotes($selConOccupation);
		$txtConAddress = FixQuotes($txtConAddress);
		$selConSangkat = FixQuotes($selConSangkat);
		$selConKhan = FixQuotes($selConKhan);
		$selConCity = FixQuotes($selConCity);
		$selConCountry = FixQuotes($selConCountry);
		
		//
		//	Addrss profile
		//		
		$txtIntAddress = FixQuotes($txtIntAddress);
		$selIntSangkat = FixQuotes($selIntSangkat);
		$selIntKhan = FixQuotes($selIntKhan);
		$selIntCity = FixQuotes($selIntCity);
		$selIntCountry = FixQuotes($selIntCountry);
		
		$txtBilAddress = FixQuotes($txtBilAddress);
		$selBilSangkat = FixQuotes($selBilSangkat);
		$selBilKhan = FixQuotes($selBilKhan);
		$selBilCity = FixQuotes($selBilCity);
		$selBilCountry = FixQuotes($selBilCountry);
		$SelMessengerID = FixQuotes($SelMessengerID);
		$selBilInvoiceType = FixQuotes($selBilInvoiceType);
		$txtBilEmail = FixQuotes($txtBilEmail);
		
		//
		//	Service
		//
		$ServiceID = FixQuotes($ServiceID);
		
		//
		//	Account profile
		//
		$SubscriptionName = FixQuotes($SubscriptionName);
		$PackageID = FixQuotes($PackageID);
		$RegistrationFee = FixQuotes($RegistrationFee);
		$ConfigurationFee = FixQuotes($ConfigurationFee);
		$CPEFee = FixQuotes($CPEFee);
		$ISDNFee = FixQuotes($ISDNFee);
		$SPNFee = FixQuotes($SPNFee);
		$SelStationID = FixQuotes($SelStationID);
		$txtAccountName = FixQuotes($txtAccountName);
		$CPETypeID = FixQuotes($CPETypeID);		
		$password = FixQuotes($password);
		$selSalesman = FixQuotes($selSalesman);
		$ncDeposit = FixQuotes($ncDeposit);
		if(empty($ncDeposit) || !isset($ncDeposit)) $ncDeposit = 0;
		$chkncDeposit = FixQuotes($chkncDeposit);
		if(empty($chkncDeposit) || !isset($chkncDeposit)) $chkncDeposit = 0;
		$icDeposit = FixQuotes($icDeposit);
		if(empty($icDeposit) || !isset($icDeposit)) $icDeposit = 0;
		$chkicDeposit = FixQuotes($chkicDeposit);
		if(empty($chkicDeposit) || !isset($chkicDeposit)) $chkicDeposit = 0;
		$mfDeposit = FixQuotes($mfDeposit);
		if(empty($mfDeposit) || !isset($mfDeposit)) $mfDeposit = 0;
		$chkmfDeposit = FixQuotes($chkmfDeposit);
		if(empty($chkmfDeposit) || !isset($chkmfDeposit)) $chkmfDeposit = 0;
		
		$vatRegistrationFee = FixQuotes($vatRegistrationFee);
		if((empty($vatRegistrationFee)) || (!isset($vatRegistrationFee))) $vatRegistrationFee = 0;
		$vatConfigurationFee = FixQuotes($vatConfigurationFee);
		if((empty($vatConfigurationFee)) || (!isset($vatConfigurationFee))) $vatConfigurationFee = 0;
		$vatCPEFee = FixQuotes($vatCPEFee);
		if((empty($vatCPEFee)) || (!isset($vatCPEFee))) $vatCPEFee = 0;
		$vatISDNFee = FixQuotes($vatISDNFee);
		if((empty($vatISDNFee)) || (!isset($vatISDNFee))) $vatISDNFee = 0;
		$vatSPNFee = FixQuotes($vatSPNFee);
		if((empty($vatSPNFee)) || (!isset($vatSPNFee))) $vatSPNFee = 0;
		
		#Encrypt password using md5
		//$Password = md5($Password);
		
		$now = date("Y/M/d H:i:s");
		$Operator = $user["FullName"];
		$StatusID = 0; # inactive
		
		$IsAddProduct = true;
		# --------------- Get station code ----------------
		$StationCode = getConfigue('Billing Center');

	if(isset($ext) && (!empty($ext)) && ($ext != "") && ($ext == "ap")){			
	}else{						
		
		//
		//	Get Customer id
		//
		$CustomerID = getID("Customer");
		$CustomerID = $StationCode.$CustomerID;
		
		//
		//	Add guarranter 
		//		
		$sql = "INSERT INTO tblCustGuarrantor(CustID, GuarrantorName, Salutation, DOB, Address, KhanID, SangkatID, CityID, CountryID, 
								NationalityID, OccupationID, Phone, Email, IdentityMode, IdentityData, SignupDate)
						VALUES('".$CustomerID."', '".$txtGarrentorName."', '".$selGuaSalutation."', '".$txtGuaDOB."', 
									 '".$txtGuaAddress."', '".$selGuaKhan."', '".$selGuaSangkat."', '".$selGuaCity."', 
									 '".$selGuaCountry."', '".$selGuaNationality."', '".$selGuaOccupation."', '".$GuaPhone."', 
									 '".$GuaEmail."', '".$selGuaDuplicateID."', '".$txtGuaDuplicate."', '".$now."')";

		if($mydb->sql_query($sql, true)){
			$title = "Add new customer guarranter";
			$comment = "Add new customer guarranter information when first sign up new customer";
			$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);
			//
			//	Add designate 
			//
			$sql = "INSERT INTO tblCustDesignate(CustID, DesignateName, Salutation, DOB, Address, KhanID, SangkatID, CityID, CountryID, 
								NationalityID, OccupationID, Phone, Email, IdentityMode, IdentityData, SignupDate)
						VALUES('".$CustomerID."', '".$txtDesignateName."', '".$selDesSalutation."', '".$txtDesDOB."', 
									 '".$txtDesAddress."', '".$selDesKhan."', '".$selDesSangkat."', '".$selDesCity."', 
									 '".$selDesCountry."', '".$selDesNationality."', '".$selDesOccupation."', '".$DesPhone."', 
									 '".$DesEmail."', '".$selDesDuplicateID."', '".$txtDesDuplicate."', '".$now."')";

			if($mydb->sql_query($sql, true)){
				$title = "Add new customer designate";
				$comment = "Add new customer designate information when first sign up new customer";
				$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);
				//
				//	Add contact 
				//
				$sql = "INSERT INTO tblCustContact(CustID, ContactName, Salutation, DOB, Address, KhanID, SangkatID, CityID, CountryID, 
								NationalityID, OccupationID, Phone, Email, IdentityMode, IdentityData, SignupDate)
						VALUES('".$CustomerID."', '".$txtContactName."', '".$selConSalutation."', '".$txtConDOB."', 
									 '".$txtConAddress."', '".$selConKhan."', '".$selConSangkat."', '".$selConCity."', 
									 '".$selConCountry."', '".$selConNationality."', '".$selConOccupation."', '".$ConPhone."', 
									 '".$ConEmail."', '".$selConDuplicateID."', '".$txtConDuplicate."', '".$now."')";

				if($mydb->sql_query($sql, true)){
					$title = "Add new customer contact";
					$comment = "Add new customer contact information when first sign up new customer";
					$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);					
					//
					//	Customer profile
					//
					$sql = "INSERT INTO tblCustomer(CustID, CustName, CustTypeID, VATNumber, IsVATException, RegisteredDate, IdentityData,
										InvoiceTypeID, NationalityID, Telephone, BillingEmail, OccupationID, BusinessReg, MessengerID, 
										IdentityMode, DOB, Salutation, Address, SangkatID, KhanID, CityID, CountryID, IsAccGroup, 
										Category, Email)
										VALUES('".$CustomerID."', '".$txtCustomerName."', '".$radCustType."', '".$txtVATNumber."',
													 '".$radExemption."', '".$now."', '".$CustDuplicate."', '".$selBilInvoiceType."',
													 '".$selCustNationality."', '".$txtCusPhone."', '".$txtBilEmail."', '".$selCustOccupation."', 
													 '".$txtCusBus."', '".$SelMessengerID."', '".$selCustDuplicateID."', '".$txtCustDOB."', 
													 '".$selCustSalutation."', '".$txtCusAddress."', '".$selCusSangkat."', '".$selCusKhan."', 
													 '".$selCusCity."', '".$selCusCountry."', '0', '".$selBusinessType."', '".$txtCusEmail."')";

					if($mydb->sql_query($sql, true)){
						$title = "Add new customer information";
						$comment = "Sign up new customer information.";	
						$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);
					}else{
						$error = $mydb->sql_error();
						$retOut = $myinfo->error("Failed to add new customer information.", $error['message']);
						$IsAddProduct = false;
					}	#	end add customer info												 
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to add customer contact.", $error['message']);
					$IsAddProduct = false;
				}	#	end add contact	
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to add customer designate.", $error['message']);
				$IsAddProduct = false;
			} # end add designate
		}else{
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to add customer guarranter.", $error['message']);
			$IsAddProduct = false;
		} # end add guarranter
}	# end add customer		
if($IsAddProduct){			
			# --------------- Get account id --------------------------
			$AccountID = getID("Account");		
			$AccountID = $StationCode.$AccountID;
			//
			//	Installation address 
			//
			$sql = "INSERT INTO tblCustAddress(CustID, IsBillingAddress, Address, SangkatID, KhanID, CityID, CountryID, AccID)
							VALUES('".$CustomerID."', 0, '".$txtIntAddress."', '".$selIntSangkat."', '".$selIntKhan."', '".$selIntCity."',
										 '".$selIntCountry."', '".$AccountID."')";

			if($mydb->sql_query($sql, true)){
				$title = "Add new installation address";
				$comment = "Add new customer installation address when first sign up new customer";	
				$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);
				//
				//	Billing address
				//
				$sql = "INSERT INTO tblCustAddress(CustID, IsBillingAddress, Address, SangkatID, KhanID, CityID, CountryID, AccID)
								VALUES('".$CustomerID."', 1, '".$txtBilAddress."', '".$selBilSangkat."', '".$selBilKhan."', '".$selBilCity."',
											 '".$selBilCountry."', '".$AccountID."')";

				if($mydb->sql_query($sql, true)){
					$title = "Add new billing address";
					$comment = "Add new customer billing address when first sign up new customer";	
					$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);
					
					# lease line
					if($ServiceID == 4){
						$sql = "INSERT INTO tblCustAddress(CustID, IsBillingAddress, Address, SangkatID, KhanID, CityID, CountryID, AccID)
								VALUES('".$CustomerID."', 2, '".$txtLeaAddress."', '".$selLeaSangkat."', '".$selLeaKhan."', '".$selLeaCity."',
											 '".$selLeaCountry."', '".$AccountID."')";
						if($mydb->sql_query($sql, true)){
							$title = "Add new installation to address for lease line";
							$comment = "Add new customer installation address for lease line account. Lease line from is installation address.";	
							$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);
						}
					}
					//
					//	Add new account
					//
					
					$sql = "INSERT INTO tblCustProduct(AccID, CustID, PackageID, UserName, Password, SalePersonID, StatusID, SetupDate, 
										CreatedBy, SubscriptionName, NoBillRun, Score, StationID, IsNCDeposit, IsICDeposit, IsMFDeposit, InvoiceTypeID, MessengerID, BillingEmail) 
										VALUES('".$AccountID."', '".$CustomerID."', '".$PackageID."', '".$txtAccountName."', '".$password."', 
													 '".$selSalesman."', '".$StatusID."', '".$now."', '".$Operator."', '".$SubscriptionName."',
													 0, 0, '".$SelStationID."', '".$chkncDeposit."', '".$chkicDeposit."', '".$chkmfDeposit."', '".$selBilInvoiceType."', '".$SelMessengerID."', '".$txtBilEmail."')";

					if($mydb->sql_query($sql, true)){
						# Insert into product status history as status changed
						$sql = "INSERT INTO tblAccStatusHistory(AccID, StatusID, ChangeDate, OtherID, OtherText)
										VALUES(".$AccountID.", 0, '".$now."', 0, 'New subscribe')";
						$mydb->sql_query($sql);
						# Create account supplement
						$sql = "INSERT INTO tblProductStatus(AccID, Incoming, Outgoing, International, IncomingLoc, 
																IncomingNat, OutgoingLoc, OutgoingNat, Other) 
													VALUES($AccountID, 0, 0, 0, 0, 0, 0, 0, 0)";
						$mydb->sql_query($sql);
						
						# set cpe use
						$sql = "INSERT INTO tblCPEUsed(AccID, CPEID, UsedDate) VALUES(".$AccountID.", ".$CPETypeID.", '".$now."')";
						$mydb->sql_query($sql);
						
						$title = "Add new account";
						$comment = "Signup new account with package: $txtAccPackage";	
						$audit->AddAudit($CustomerID, $AccountID, $title, $comment, $Operator, 1, 1);
											
							//
							//	Create invoice
							//
							$Finance = new Invoice();
							$InvoiceID = $Finance->GetInvoiceID();
							//
							//	Add required deposit
							//

							$retOut = $Finance->URDeposit($CustomerID, $AccountID, $ncDeposit, $icDeposit, $mfDeposit);
		
							if($retOut){
		
								$title = "Add product require deposit";
								$comment = "Set require National Call Deposit: ".FormatCurrency($ncDeposit)."; International Call Deposit: 
													 ".FormatCurrency($icDeposit)."; Monthly Fee Deposit: ".FormatCurrency($mfDeposit);	
								$audit->AddAudit($CustomerID, $AccountID, $title, $comment, $Operator, 1, 8);
		
								//
								//	add registration fee
								//
								$ItemID = getInvoiceItem("Registration Fee");
								$retOut = $Finance->CreateInvoiceDetail($CustomerID, $AccountID, $InvoiceID, $ItemID, $RegistrationFee, 0, $vatRegistrationFee);
								if($retOut){
									//
									//	add installation fee
									//
									$ItemID = getInvoiceItem("Configuration Fee");
									$retOut = $Finance->CreateInvoiceDetail($CustomerID, $AccountID, $InvoiceID, $ItemID, $ConfigurationFee, 0, $vatConfigurationFee);
									if($retOut){
										//
										//	add cpe fee
										//
										$ItemID = getInvoiceItem("CPE Fee");
										$retOut = $Finance->CreateInvoiceDetail($CustomerID, $AccountID, $InvoiceID, $ItemID, $CPEFee, 0, $vatCPEFee);
										if($retOut){
											//
											//	add isdn fee
											//
											$ItemID = getInvoiceItem("ISDN Fee");
											$retOut = $Finance->CreateInvoiceDetail($CustomerID, $AccountID, $InvoiceID, $ItemID, $ISDNFee, 0, $vatISDNFee);
											if($retOut){
												//
												//	add special number									
												//	
												$ItemID = getInvoiceItem("SPN Fee");
												$retOut = $Finance->CreateInvoiceDetail($CustomerID, $AccountID, $InvoiceID, $ItemID, $SPNFee, 0, $vatSPNFee);
												if($retOut){
													//create invoice
													//	
													//
													$TransactionStart = $now;
													$TransactionEnd = date("Y/M/d H:i:s");
													$retOut = $Finance->CreateInvoice($CustomerID, $AccountID, $InvoiceID, $TransactionStart, $TransactionEnd);
													if($retOut){
														$title = "Generate new registration invoice";
														$comment = "Issue invoice for first registration as registration fee: ".FormatCurrency($RegistrationFee).";
																																								installation fee: ".FormatCurrency($ConfigurationFee).";
																																								CPE fee: ".FormatCurrency($CPEFee).";
																																								ISDN fee: ".FormatCurrency($ISDNFee).";
																																								special number fee: ".FormatCurrency($SPNFee).";";
														$audit->AddAudit($CustomerID, $AccountID, $title, $comment, $Operator, 1, 9);
													
													redirect('./?CustomerID='.$CustomerID.'&pg=10');
																																								
												}	#	end increate invoice
											}	#	end special number
										}	#	end ISND Fee
									}	#	end CPE Fee
								}	#	end add installation
							}	#end add registration									
						}	# end add deposit
					}else{
						$error = $mydb->sql_error();
						$retOut = $myinfo->error("Failed to add new account.", $error['message']);
					}	# end add account																															
				}else{
					$error = $mydb->sql_error();				
					$retOut = $myinfo->error("Failed to add customer product.", $error['message'].$sql);
				}
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to add billing address.", $error['message']);
				$IsAddProduct = false;
			}	#	end add billing address	
		}else{
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to add installation address.", $error['message'].$sql);
			$IsAddProduct = false;
		}	#	end add installation address
	}
		

?>
<script language="javascript">
	function edit(index){
		if(index == 1){
			fsignupcust5.pg.value = "506";
			fsignupcust5.submit();
		}else if(index == 2){
			fsignupcust5.pg.value = "505";
			fsignupcust5.submit();
		}else if(index == 3){
			fsignupcust5.pg.value = "504";
			fsignupcust5.submit();
		}else if(index == 4){
			fsignupcust5.pg.value = "503";
			fsignupcust5.submit();
		}else if(index == 5){
			fsignupcust5.pg.value = "507";
			fsignupcust5.submit();
		}else if(index == 55){
			fsignupcust5.pg.value = "509";
			fsignupcust5.submit();
		}else if(index == 6){
			fsignupcust5.pg.value = "500";
			fsignupcust5.submit();		
		}else if(index == 7){
			fsignupcust5.pg.value = "501";
			fsignupcust5.submit();
		}else if(index == 8){
			fsignupcust5.pg.value = "502";
			fsignupcust5.submit();
		}else if(index == 9){
			fsignupcust5.pg.value = "508";
			fsignupcust5.submit();
		}
	}
</script>
<br>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="180">
			<fieldset>
				<legend align="center">Signup Customer Process</legend>
				
					<!-- ================================ Processing step ================================= -->
					<table border="0" cellpadding="5" cellspacing="0" align="center" width="100%" bordercolor="#aaaaaa" bgcolor="#feeac2" id="submenu">			
						<tr><td>&nbsp;</td></tr>			
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Customer profile
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Address information
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Product information
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Account information
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left" bgcolor="#ffffff">
								<b>Information summary</b>
						 	</td>		 
						</tr>		
					 </table>
			</fieldset>		
		</td>
		<td valign="top" width="650" align="left"> 
			<form name="fsignupcust5" method="post" action="./">				
				<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#ffffff" align="left">								 							
					<?php 
						if(empty($ext)){
					?>
						<tr>
							<td>
								<!--
								//
								//	Customer profile
								//	
								-->
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
										<td align="left" class="formtitle"><b>CUSTOMER PROFILE</b></td>
										<td align="right">[<a href="javascript:edit(1);">Edit</a>] </td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">
												<tr>
													<td align="left" nowrap="nowrap">Customer name:</td>
													<td align="left" colspan="3"><b><?php print ($selCusSalutation." ".$txtCustomerName); ?></b></td>
												</tr>
												<tr>																										
													<?php
														if($radCustType == 1){														
													?>
													<td align="left" valign="top" width="20%">Customer type:</td>
													<td align="left" width="30%"><b>Personal</b></td>
													<td align="left" valign="top" width="20%">Date of birth:</td>
													<td align="left" width="30%"><b><?php print $txtCusDOB;?></b></td>
													<?php }else{?>
													<td align="left" valign="top" width="20%">Customer type:</td>
													<td align="left" width="30%"><b>Corporate</b></td>
													<td align="left" valign="top" width="20%">Business reg.:</td>
													<td align="left" width="30%"><b><?php print $txtCusBus;?></b></td>
													<?php }?>
												</tr>
												<tr>
													<td align="left" valign="top" width="20%">Category:</td>
													<td align="left" width="30%"><b><?php print $txtBusinessType;?></b></td>
													<td align="left" valign="top" width="20%">Duplicate:</td>
													<td align="left" width="30%"><b><?php print $selCusDuplicateID."- ".$txtCusDuplicate;?></b></td>
												</tr>												
												<tr>													
													<td align="left" valign="top">VAT charge:</td>
													<td align="left"><b><?php if($radExemption) print "No"; else print "Yes";?></b></td>
													<?php if($radCustType == 2){?>
													<td align="left" valign="top">VAT number:</td>
													<td align="left"><b><?php print $txtVATNumber;?></b></td>
													<?php } else{?>
													<td></td><td></td>
													<?php }?>
												</tr>												
												<tr>													
													<td align="left" valign="top">Occupation:</td>
													<td align="left"><b><?php print $txtCusOccupation;?></b></td>
													<?php if($radCustType == 1){?>
													<td align="left" valign="top">Nationality:</td>
													<td align="left"><b><?php print $txtCusNationality;?></b></td>
													<?php }else{?>
													<td></td><td></td>
													<?php }?>
												</tr>
												<tr>
													<td align="left" valign="top">Address:</td>
													<td align="left" colspan="3">
														<?php print ($txtCusAddress.", ".$txtCusSangkat.", ".$txtCusKhan.", ".$txtCusCity.", ".$txtCusCountry);;?>
													</td>
												</tr>																																															
											</table>
										</td>
									</tr>
								</table>	
								
							</td>					 		
						</tr>
						<tr><td>&nbsp;</td></tr>
						<tr>
							<td>
								<!--
								//
								//	Designate profile
								//
								-->
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
										<td align="left" class="formtitle"><b>DESIGNATE PROFILE</b></td>
										<td align="right">[<a href="javascript:edit(2);">Edit</a>] </td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">
												<tr>
													<td align="left" nowrap="nowrap">Designate name:</td>
													<td align="left" colspan="3"><b><?php print ($selDesSalutation." ".$txtDesignateName); ?></b></td>
												</tr>												
												<tr>
													<td align="left" valign="top" width="20%">Date of birth:</td>
													<td align="left" width="30%"><b><?php print formatDate($txtDesDOB, 6);?></b></td>
													<td align="left" valign="top" width="20%">Duplicate:</td>
													<td align="left" width="30%"><b><?php print ($selDesDuplicateID."- ".$txtDesDuplicate);?></b></td>
												</tr>												
												<tr>
													<td align="left" valign="top">Nationality:</td>
													<td align="left"><b><?php print $txtDesNationality;?></b></td>
													<td align="left" valign="top">Occupation:</td>
													<td align="left"><b><?php print $txtDesOccupation;?></b></td>
												</tr>
												<tr>
													<td align="left" valign="top">Telephone:</td>
													<td align="left"><b><?php print $txtDesPhone;?></b></td>
													<td align="left" valign="top">Email:</td>
													<td align="left"><b><?php print $txtDesEmail;?></b></td>
												</tr>												
												<tr>
													<td align="left">Address:</td>
													<td align="left" colspan="3">
														<?php print ($txtDesAddress.", ".$txtDesSangkat.", ".$txtDesKhan.", ".$txtDesCity.", ".$txtDesCountry);?>
													</td>
												</tr>																																															
											</table>
										</td>
									</tr>
								</table>	
								<!-- ================= step 1 ================-->
							</td>					 		
						</tr>
						<tr><td>&nbsp;</td></tr>
						<tr>
							<td>
								<!-- 
								//
								//	Guarranter profile
								//
								-->
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
										<td align="left" class="formtitle"><b>GUARANTOR PROFILE</b></td>
										<td align="right">[<a href="javascript:edit(3);">Edit</a>] </td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">
												<tr>
													<td align="left" nowrap="nowrap">Garantor name:</td>
													<td align="left" colspan="3"><b><?php print ($selGuaSalutation." ".$txtGarrentorName); ?></b></td>
												</tr>												
												<tr>
													<td align="left" valign="top" width="20%">Date of birth:</td>
													<td align="left" width="30%"><b><?php print formatDate($txtGuaDOB, 6);;?></b></td>
													<td align="left" valign="top" width="20%">Duplicate:</td>
													<td align="left" width="30%"><b><?php print ($selGuaDuplicateID."- ".$txtGuaDuplicate);?></b></td>
												</tr>												
												<tr>
													<td align="left" valign="top">Nationality:</td>
													<td align="left"><b><?php print $txtGuaNationality;?></b></td>
													<td align="left" valign="top">Occupation:</td>
													<td align="left"><b><?php print $txtGuaOccupation;?></b></td>
												</tr>
												<tr>
													<td align="left" valign="top">Telephone:</td>
													<td align="left"><b><?php print $txtGuaPhone;?></b></td>
													<td align="left" valign="top">Email:</td>
													<td align="left"><b><?php print $txtGuaEmail;?></b></td>
												</tr>												
												<tr>
													<td align="left">Address:</td>
													<td align="left" colspan="3">
														<?php print ($txtGuaAddress.", ".$txtGuaSangkat.", ".$txtGuaKhan.", ".$txtGuaCity.", ".$txtGuaCountry);?>
													</td>
												</tr>																																															
											</table>
										</td>
									</tr>
								</table>	
								<!-- ================= step 1 ================-->
							</td>					 		
						</tr>
						<tr><td>&nbsp;</td></tr>
						<tr>
							<td>
								<!-- 
								//
								//	Contact profile
								//
								-->
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
										<td align="left" class="formtitle"><b>CONTACT PROFILE</b></td>
										<td align="right">[<a href="javascript:edit(4);">Edit</a>] </td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">
												<tr>
													<td align="left" nowrap="nowrap">Contact name:</td>
													<td align="left" colspan="3"><b><?php print ($selConSalutation." ".$txtContactName); ?></b></td>
												</tr>												
												<tr>
													<td align="left" valign="top" width="20%">Date of birth:</td>
													<td align="left" width="30%"><b><?php print formatDate($txtConDOB, 6);?></b></td>
													<td align="left" valign="top" width="20%">Number:</td>
													<td align="left" width="30%"><b><?php print ($selConDuplicateID."- ".$txtConDuplicate);?></b></td>
												</tr>												
												<tr>
													<td align="left" valign="top">Nationality:</td>
													<td align="left"><b><?php print $txtConNationality;?></b></td>
													<td align="left" valign="top">Occupation:</td>
													<td align="left"><b><?php print $txtConOccupation;?></b></td>
												</tr>
												<tr>
													<td align="left" valign="top">Telephone:</td>
													<td align="left"><b><?php print $txtConPhone;?></b></td>
													<td align="left" valign="top">Email:</td>
													<td align="left"><b><?php print $txtConEmail;?></b></td>
												</tr>												
												<tr>
													<td align="left">Address:</td>
													<td align="left" colspan="3">
														<?php print ($txtConAddress.", ".$txtConSangkat.", ".$txtConKhan.", ".$txtConCity.", ".$txtConCountry);?>
													</td>
												</tr>																																															
											</table>
										</td>
									</tr>
								</table>	
								<!-- ================= step 1 ================-->
							</td>					 		
						</tr>
						<tr><td>&nbsp;</td></tr>						
				<?php 
					}
				?>						
						<tr>
							<td>
								<!-- 
									//
									//	ACCOUNT INFORMATION
									//
								-->
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
										<td align="left" class="formtitle"><b>ACCOUNT INFORMATION</b></td>
										<td align="right">
										<?php 
											if($ServiceID == 2)
												print '[<a href="javascript:edit(5);">Edit</a>]';
											else
												print '[<a href="javascript:edit(55);">Edit</a>]';
										?>	
										</td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">
												
												<tr>
													<td align="left" valign="top" nowrap="nowrap">Product name:</td>
													<td align="left"><b><?php print getService($ServiceID); ?></b></td>
													<td align="left" valign="top" nowrap="nowrap">Package:</td>
													<td align="left"><b><?php print $txtAccPackage;?></b></td>
												</tr>
												<tr>
													<td align="left" valign="top" width="20%">Installation:</td>
													<td align="left" width="30%"><b><?php print $ConfigurationFee;?></b></td>
													<td align="left" valign="top" width="20%" nowrap="nowrap">Registration fee:</td>
													<td align="left" width="30%"><b><?php print $RegistrationFee;?></b></td>
												</tr>												
												<tr>
													<td align="left" valign="top" width="20%" nowrap="nowrap">CPE Cost:</td>
													<td align="left" width="30%"><b><?php print $CPEFee;?></b></td>
													<td align="left" valign="top" width="20%" nowrap="nowrap">ISDN Cost:</td>
													<td align="left" width="30%"><b><?php print $ISDNFee;?></b></td>
												</tr>
												<tr>
													<td align="left" valign="top" width="20%" nowrap="nowrap">Special Number:</td>
													<td align="left" width="30%"><b><?php print $SPNFee;?></b></td>
													<td align="left" valign="top" width="20%" nowrap="nowrap">Salesman:</td>
													<td align="left" width="30%"><b><?php print $txtSaleman; ?></b></td>
												</tr>
												<tr>
													<td align="left" valign="top">CPE Type:</td>
													<td align="left" valign="top"><?php print $txtCPE;?></td>
													<td align="left" nowrap="nowrap" valign="top">Subscription name:</td>
													<td align="left"><b><?php print $SubscriptionName; ?></b></td>
												</tr>
												<tr>
													<td align="left" valign="top" width="20%" nowrap="nowrap">Account name:</td>
													<td align="left" width="30%"><b><?php print $txtAccountName;?></b></td>
													<td align="left" valign="top" width="20%" nowrap="nowrap">Password:</td>
													<td align="left" width="30%"><i>(Not show)</i></td>
												</tr>																																	
											</table>
										</td>
									</tr>
								</table>	
							</td>					 		
						</tr>
						<tr><td>&nbsp;</td></tr>
						<!--<tr>
							<td>
								
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
										<td align="left" class="formtitle"><b>DEPOSIT INFORMATION</b></td>
										<td align="right">[<a href="javascript:edit(6);">Edit</a>] </td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">												
												<tr>
													<td align="left" valign="top" nowrap="nowrap">National Call Deposit:</td>
													<td align="left"><b>
														<?php 
															if(!empty($chkncDeposit))
																print "(YES)- ";
															else 
																	print "(NO)- ";
															print FormatCurrency($ncDeposit); 
														?>
														</b></td>
													<td align="left" valign="top" nowrap="nowrap">Internation Call Deposit:</td>
													<td align="left"><b>
														<?php 															
															if(!empty($chkicDeposit)) 
																print "(YES)- ";
															else 
																	print "(NO)- ";
															print FormatCurrency($icDeposit); 
														?>															
														</b></td>
												</tr>
												<tr>
													<td align="left" valign="top" width="20%">Monthly Fee Deposit:</td>
													<td align="left" width="30%"><b>
															<?php 
																if(!empty($chkmfDeposit)) 
																	print "(YES)- ";
																else 
																	print "(NO)- ";
																print FormatCurrency($mfDeposit);
															?>
														</b></td>
													<td align="left" valign="top" width="20%" nowrap="nowrap">&nbsp;</td>
													<td align="left" width="30%"><b>&nbsp;</b></td>
												</tr>																																																								
											</table>
										</td>
									</tr>
								</table>	
							</td>					 		
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>-->	
						<tr>
							<td>
								<!-- 
								//
								//	Installation address
								//
								-->
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
										<td align="left" class="formtitle"><b>INSTALLATION ADDRESS</b></td>
										<td align="right">[<a href="javascript:edit(7);">Edit</a>] </td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">																								
												<tr>
													<td align="left">Address:</td>
													<td align="left" colspan="3">
														<?php print ($txtIntAddress.", ".$txtIntSangkat.", ".$txtIntKhan.", ".$txtIntCity.", ".$txtIntCountry);?>
													</td>
												</tr>																																															
											</table>
										</td>
									</tr>
								</table>	
								<!-- ================= step 1 ================-->
							</td>					 		
						</tr>
						<?php if($ServiceID == 2){?>
						<tr><td colspan="2">&nbsp;</td></tr>	
						<tr>
							<td>
								<!-- 
								//
								//	Lease line to
								//
								-->
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
										<td align="left" class="formtitle"><b>INSTALLATION ADDRESS TO</b></td>
										<td align="right">[<a href="javascript:edit(9);">Edit</a>] </td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">																								
												<tr>
													<td align="left">Address:</td>
													<td align="left" colspan="3">
														<?php print ($txtLeaAddress.", ".$txtLeaSangkat.", ".$txtLeaKhan.", ".$txtLeaCity.", ".$txtLeaCountry);?>
													</td>
												</tr>																																															
											</table>
										</td>
									</tr>
								</table>	
								<!-- ================= step 1 ================-->
							</td>					 		
						</tr>	
						<?php }?>
						<tr><td>&nbsp;</td></tr>
						<tr>
							<td>
								<!-- 
								//
								//	Billing information
								//
								-->
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
										<td align="left" class="formtitle"><b>BILLING INFORMATION</b></td>
										<td align="right">[<a href="javascript:edit(8);">Edit</a>] </td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">																								
												<tr>
													<td align="left">Billing Address:</td>
													<td align="left" colspan="3">
														<?php print ($txtBilAddress.", ".$txtBilSangkat.", ".$txtBilKhan.", ".$txtBilCity.", ".$txtBilCountry);?>
													</td>
												</tr>	
												<tr>
													<td align="left" valign="top">Invoice Type:</td>
													<td align="left"><b><?php print $txtBilInvoiceType;?></b></td>
													<td align="left" valign="top" nowrap="nowrap">Messenger:</td>
													<td align="left"><b><?php print $txtBilMessenger;?></b></td>
												</tr>
												<tr>
													<td align="left">Billing Email:</td>
													<td align="left" colspan="3">
														<?php print $txtBilEmail;?>
													</td>
												</tr>																																														
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>						
						<tr><td>&nbsp;</td></tr>							
						<tr> 				  
					  <td align="center" colspan="2">
							
							<input type="submit" tabindex="3" name="btnSubmit" value="Submit" class="button" <?php print $disabled; ?> />						
						</td>
					 </tr>
					 <?php
							if(isset($retOut) && (!empty($retOut))){
								print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
							}
						?>		
			 </table>
				 					
					<input type="hidden" name="pg" id="pg" value="6" />	
					<input type="hidden" name="smt" value="save" /> 
									
					<input type="hidden" name="SubscriptionName" value="<?php print $SubscriptionName; ?>" />
					<input type="hidden" name="PackageID" value="<?php print $PackageID; ?>" />
					<input type="hidden" name="RegistrationFee" value="<?php print $RegistrationFee; ?>" />
					<input type="hidden" name="ConfigurationFee" value="<?php print $ConfigurationFee; ?>" />
					<input type="hidden" name="CPEFee" value="<?php print $CPEFee; ?>" />
					<input type="hidden" name="ISDNFee" value="<?php print $ISDNFee; ?>" />
					<input type="hidden" name="SPNFee" value="<?php print $SPNFee; ?>" />
					<input type="hidden" name="SelStationID" value="<?php print $SelStationID; ?>" />
					<input type="hidden" name="SelPhonePreset" value="<?php print $SelPhonePreset; ?>" />
					<input type="hidden" name="UserName" value="<?php print $UserName; ?>" />
					<input type="hidden" name="Password" value="<?php print $Password; ?>" />
					<input type="hidden" name="selSalesman" value="<?php print $selSalesman; ?>" />
					<input type="hidden" name="chkncDeposit" value="<?php print $chkncDeposit; ?>" />
					<input type="hidden" name="ncDeposit" value="<?php print $ncDeposit; ?>" />
					<input type="hidden" name="chkicDeposit" value="<?php print $chkicDeposit; ?>" />
					<input type="hidden" name="icDeposit" value="<?php print $icDeposit; ?>" />
					<input type="hidden" name="chkmfDeposit" value="<?php print $chkmfDeposit; ?>" />
					<input type="hidden" name="mfDeposit" value="<?php print $mfDeposit; ?>" />
					<input type="hidden" name="txtCPE" value="<?php print $txtCPE; ?>" />
					<input type="hidden" name="CPETypeID" value="<?php print $CPETypeID; ?>" />
					
					<input type="hidden" name="txtAccPackage" value="<?php print $txtAccPackage; ?>" />
					<input type="hidden" name="txtSaleman" value="<?php print $txtSaleman; ?>" />
					<input type="hidden" name="txtStation" value="<?php print $txtStation; ?>" />
					<input type="hidden" name="txtAccountName" value="<?php print $txtAccountName; ?>" />
					
					<input type="hidden" name="ServiceID" value="<?php print $ServiceID; ?>" />
					
					<input type="hidden" name="selCusSalutation" value="<?php print $selCusSalutation; ?>" />
					<input type="hidden" name="txtCustomerName" value="<?php print $txtCustomerName; ?>" />
					<input type="hidden" name="txtCusDOB" value="<?php print $txtCusDOB; ?>" />
					<input type="hidden" name="txtCusBus" value="<?php print $txtCusBus; ?>" />
					<input type="hidden" name="selCusDuplicateID" value="<?php print $selCusDuplicateID; ?>" />
					<input type="hidden" name="txtCusDuplicate" value="<?php print $txtCusDuplicate; ?>" />
					<input type="hidden" name="radExemption" value="<?php print $radExemption; ?>" />
					<input type="hidden" name="txtVATNumber" value="<?php print $txtVATNumber; ?>" />
					<input type="hidden" name="selCusNationality" value="<?php print $selCusNationality; ?>" />
					<input type="hidden" name="selCusOccupation" value="<?php print $selCusOccupation; ?>" />
					<input type="hidden" name="txtCusPhone" value="<?php print $txtCusPhone; ?>" />
					<input type="hidden" name="txtCusEmail" value="<?php print $txtCusEmail; ?>" />
					<input type="hidden" name="txtCusAddress" value="<?php print $txtCusAddress; ?>" />
					<input type="hidden" name="selCusCountry" value="<?php print $selCusCountry; ?>" />
					<input type="hidden" name="selCusCity" value="<?php print $selCusCity; ?>" />
					<input type="hidden" name="selCusKhan" value="<?php print $selCusKhan; ?>" />
					<input type="hidden" name="selCusSangkat" value="<?php print $selCusSangkat; ?>" />
					
					<input type="hidden" name="selConSalutation" value="<?php print $selConSalutation; ?>" />
					<input type="hidden" name="txtContactName" value="<?php print $txtContactName; ?>" />
					<input type="hidden" name="txtConDOB" value="<?php print $txtConDOB; ?>" />
					<input type="hidden" name="selConDuplicateID" value="<?php print $selConDuplicateID; ?>" />
					<input type="hidden" name="selConNationality" value="<?php print $selConNationality; ?>" />
					<input type="hidden" name="selConOccupation" value="<?php print $selConOccupation; ?>" />
					<input type="hidden" name="txtConPhone" value="<?php print $txtConPhone; ?>" />
					<input type="hidden" name="txtConEmail" value="<?php print $txtConEmail; ?>" />
					<input type="hidden" name="txtConAddress" value="<?php print $txtConAddress; ?>" />
					<input type="hidden" name="selConCountry" value="<?php print $selConCountry; ?>" />
					<input type="hidden" name="selConCity" value="<?php print $selConCity; ?>" />
					<input type="hidden" name="selConKhan" value="<?php print $selConKhan; ?>" />
					<input type="hidden" name="selConSangkat" value="<?php print $selConSangkat; ?>" />
					
					<input type="hidden" name="selDesSalutation" value="<?php print $selDesSalutation; ?>" />
					<input type="hidden" name="txtDesignateName" value="<?php print $txtDesignateName; ?>" />
					<input type="hidden" name="txtDesDOB" value="<?php print $txtDesDOB; ?>" />
					<input type="hidden" name="selDesDuplicateID" value="<?php print $selDesDuplicateID; ?>" />
					<input type="hidden" name="selDesNationality" value="<?php print $selDesNationality; ?>" />
					<input type="hidden" name="selDesOccupation" value="<?php print $selDesOccupation; ?>" />
					<input type="hidden" name="txtDesPhone" value="<?php print $txtDesPhone; ?>" />
					<input type="hidden" name="txtDesEmail" value="<?php print $txtDesEmail; ?>" />
					<input type="hidden" name="txtDesAddress" value="<?php print $txtDesAddress; ?>" />
					<input type="hidden" name="selDesCountry" value="<?php print $selDesCountry; ?>" />
					<input type="hidden" name="selDesCity" value="<?php print $selDesCity; ?>" />
					<input type="hidden" name="selDesKhan" value="<?php print $selDesKhan; ?>" />
					<input type="hidden" name="selDesSangkat" value="<?php print $selDesSangkat; ?>" />
					
					<input type="hidden" name="selGuaSalutation" value="<?php print $selGuaSalutation; ?>" />
					<input type="hidden" name="txtGarrentorName" value="<?php print $txtGarrentorName; ?>" />
					<input type="hidden" name="txtGuaDOB" value="<?php print $txtGuaDOB; ?>" />
					<input type="hidden" name="selGuaDuplicateID" value="<?php print $selGuaDuplicateID; ?>" />
					<input type="hidden" name="selGuaNationality" value="<?php print $selGuaNationality; ?>" />
					<input type="hidden" name="selGuaOccupation" value="<?php print $selGuaOccupation; ?>" />
					<input type="hidden" name="txtGuaPhone" value="<?php print $txtGuaPhone; ?>" />
					<input type="hidden" name="txtGuaEmail" value="<?php print $txtGuaEmail; ?>" />
					<input type="hidden" name="txtGuaAddress" value="<?php print $txtGuaAddress; ?>" />
					<input type="hidden" name="selGuaCountry" value="<?php print $selGuaCountry; ?>" />
					<input type="hidden" name="selGuaCity" value="<?php print $selGuaCity; ?>" />
					<input type="hidden" name="selGuaKhan" value="<?php print $selGuaKhan; ?>" />
					<input type="hidden" name="selGuaSangkat" value="<?php print $selGuaSangkat; ?>" />					
					
					<input type="hidden" name="txtCusNationality" value="<?php print $txtCusNationality; ?>" />
					<input type="hidden" name="txtCusOccupation" value="<?php print $txtCusOccupation; ?>" />
					<input type="hidden" name="txtCusSangkat" value="<?php print $txtCusSangkat; ?>" />
					<input type="hidden" name="txtCusKhan" value="<?php print $txtCusKhan; ?>" />
					<input type="hidden" name="txtCusCity" value="<?php print $txtCusCity; ?>" />
					<input type="hidden" name="txtCusCountry" value="<?php print $txtCusCountry; ?>" />
					
					<input type="hidden" name="txtConNationality" value="<?php print $txtConNationality; ?>" />
					<input type="hidden" name="txtConOccupation" value="<?php print $txtConOccupation; ?>" />
					<input type="hidden" name="txtConDuplicate" value="<?php print $txtConDuplicate; ?>" />
					<input type="hidden" name="txtConSangkat" value="<?php print $txtConSangkat; ?>" />
					<input type="hidden" name="txtConKhan" value="<?php print $txtConKhan; ?>" />
					<input type="hidden" name="txtConCity" value="<?php print $txtConCity; ?>" />
					<input type="hidden" name="txtConCountry" value="<?php print $txtConCountry; ?>" />
					
					<input type="hidden" name="txtGuaNationality" value="<?php print $txtGuaNationality; ?>" />
					<input type="hidden" name="txtGuaOccupation" value="<?php print $txtGuaOccupation; ?>" />
					<input type="hidden" name="txtGuaDuplicate" value="<?php print $txtGuaDuplicate; ?>" />
					<input type="hidden" name="txtGuaSangkat" value="<?php print $txtGuaSangkat; ?>" />
					<input type="hidden" name="txtGuaKhan" value="<?php print $txtGuaKhan; ?>" />
					<input type="hidden" name="txtGuaCity" value="<?php print $txtGuaCity; ?>" />
					<input type="hidden" name="txtGuaCountry" value="<?php print $txtGuaCountry; ?>" />
					
					<input type="hidden" name="txtDesNationality" value="<?php print $txtDesNationality; ?>" />
					<input type="hidden" name="txtDesOccupation" value="<?php print $txtDesOccupation; ?>" />
					<input type="hidden" name="txtDesDuplicate" value="<?php print $txtDesDuplicate; ?>" />
					<input type="hidden" name="txtDesSangkat" value="<?php print $txtDesSangkat; ?>" />
					<input type="hidden" name="txtDesKhan" value="<?php print $txtDesKhan; ?>" />
					<input type="hidden" name="txtDesCity" value="<?php print $txtDesCity; ?>" />
					<input type="hidden" name="txtDesCountry" value="<?php print $txtDesCountry; ?>" />
					
					<input type="hidden" name="selIntCountry" value="<?php print $selIntCountry; ?>" />
					<input type="hidden" name="selIntCity" value="<?php print $selIntCity; ?>" />
					<input type="hidden" name="selIntKhan" value="<?php print $selIntKhan; ?>" />
					<input type="hidden" name="selIntSangkat" value="<?php print $selIntSangkat; ?>" />
					
					<input type="hidden" name="txtIntAddress" value="<?php print $txtIntAddress; ?>" />
					<input type="hidden" name="txtIntSangkat" value="<?php print $txtIntSangkat; ?>" />
					<input type="hidden" name="txtIntKhan" value="<?php print $txtIntKhan; ?>" />
					<input type="hidden" name="txtIntCity" value="<?php print $txtIntCity; ?>" />
					<input type="hidden" name="txtIntCountry" value="<?php print $txtIntCountry; ?>" />
					
					<input type="hidden" name="selLeaCountry" value="<?php print $selLeaCountry; ?>" />
					<input type="hidden" name="selLeaCity" value="<?php print $selLeaCity; ?>" />
					<input type="hidden" name="selLeaKhan" value="<?php print $selLeaKhan; ?>" />
					<input type="hidden" name="selLeaSangkat" value="<?php print $selLeaSangkat; ?>" />
					
					<input type="hidden" name="txtLeaAddress" value="<?php print $txtLeaAddress; ?>" />
					<input type="hidden" name="txtLeaSangkat" value="<?php print $txtLeaSangkat; ?>" />
					<input type="hidden" name="txtLeaKhan" value="<?php print $txtLeaKhan; ?>" />
					<input type="hidden" name="txtLeaCity" value="<?php print $txtLeaCity; ?>" />
					<input type="hidden" name="txtLeaCountry" value="<?php print $txtLeaCountry; ?>" />
					
					
					<input type="hidden" name="selBilCountry" value="<?php print $selBilCountry; ?>" />
					<input type="hidden" name="selBilCity" value="<?php print $selBilCity; ?>" />
					<input type="hidden" name="selBilKhan" value="<?php print $selBilKhan; ?>" />
					<input type="hidden" name="selBilSangkat" value="<?php print $selBilSangkat; ?>" />
					
					<input type="hidden" name="txtBilAddress" value="<?php print $txtBilAddress; ?>" />
					<input type="hidden" name="txtBilSangkat" value="<?php print $txtBilSangkat; ?>" />
					<input type="hidden" name="txtBilKhan" value="<?php print $txtBilKhan; ?>" />
					<input type="hidden" name="txtBilCity" value="<?php print $txtBilCity; ?>" />
					<input type="hidden" name="txtBilCountry" value="<?php print $txtBilCountry; ?>" />
					<input type="hidden" name="SelMessengerID" value="<?php print $SelMessengerID; ?>" />
					<input type="hidden" name="txtBilEmail" value="<?php print $txtBilEmail; ?>" />
					<input type="hidden" name="selBilInvoiceType" value="<?php print $selBilInvoiceType; ?>" />
					<input type="hidden" name="txtBilInvoiceType" value="<?php print $txtBilInvoiceType; ?>" />
					<input type="hidden" name="txtBilMessenger" value="<?php print $txtBilMessenger; ?>" />
					
					<input type="hidden" name="radCustType" value="<?php print $radCustType; ?>" />
					<input type="hidden" name="selBusinessType" value="<?php print $selBusinessType; ?>" />
					<input type="hidden" name="txtBusinessType" value="<?php print $txtBusinessType; ?>" />
					
					<input type="hidden" name="vatRegistrationFee" value="<?php print $vatRegistrationFee; ?>" />
					<input type="hidden" name="vatConfigurationFee" value="<?php print $vatConfigurationFee; ?>" />
					<input type="hidden" name="vatCPEFee" value="<?php print $vatCPEFee; ?>" />
					<input type="hidden" name="vatISDNFee" value="<?php print $vatISDNFee; ?>" />
					<input type="hidden" name="vatSPNFee" value="<?php print $vatSPNFee; ?>" />
					
					<input type="hidden" name="ext" value="<?php print $ext; ?>" />
					<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
				</form>
			</td>
		</tr>
	</table>	
<br>&nbsp;
<?php
# Close connection
$mydb->sql_close();
?>