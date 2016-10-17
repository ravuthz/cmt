<?php
	# global config
	
	//
	//	Database configuration
	//	
	
	
	$DBSERVER = "UTHDOM-PC";
	$DBUSERNAME = "sa";
	$DBPASSWORD = "Uthdom777189";
	$DBNAME = "CmtWisebiller";			
	
	//
	//	Server Configuration
	//
	$SERVERROOT = "http://".$HTTP_HOST."/cmt/";
	$WebInvoiceRoot = "http://".$HTTP_HOST.":8080/WebInvoice/";
	
	$WebQuickBillRoot = "http://".$HTTP_HOST.":8080/QuickBill/InvoicePDF/";
	
	
	//
	// UI interface
	//
	$bgUnactivate = "gray";
	$foreUnactivate = "white";
	$bgActivate = "blue";
	$foreActivate = "white";
	$bgLock = "orange";
	$foreLock = "white";
	$bgClose = "red";
	$foreClose = "white";
	
	//
	//	Email configuration
	//			
	$MAILFROM = "boreivichet@camintel.com";
	$MAILFROMNAME = "Billing Admin";
	//$MAILSERVER = "smtp.camintel.com";
	$MAILSERVER = "smtp.camintel.com";
	$MAILSMTPAUTH = false;
	//$MAILUSERNAME = "boreivichet.buth@3tel.com.kh";
	$MAILUSERNAME = "boreivichet@camintel.com";
	$MAILPASSWORD = "";
	
	$EMAILSIGNATURE = "<br><br>
										 Best regards,<br>
										 Wise Biller<br><br>
										 ------------------------------<br>
										 <font size='-1' color='#aaaaaa'>Powered by: <a href='http://www.brc-tech.com'>BRC Technology</a></font>	
										";
		
?>