<?php
require("class.phpmailer.php");
require("configs.php");
// Create function forward email
function sendMail($to, $cc, $subject, $content, $attachement){
	global $MAILFROM, $MAILFROMNAME, $MAILSERVER, $MAILSMTPAUTH, $MAILUSERNAME, $MAILPASSWORD;
	$mail = new PHPMailer();
	$mail->From = $MAILFROM;
	$mail->FromName = $MAILFROMNAME;

	$ato = split(";", $to);

	for ($intLoop=0; $intLoop < sizeof($ato); $intLoop++)
	{
		$mail->AddAddress(trim($ato[$intLoop]));
	}
	if($cc != "")
	{
		$acc = split(";", $cc);
			for ($intLoop=0; $intLoop < sizeof($acc); $intLoop++){
			$mail->AddCC(trim($acc[$intLoop]));
		}
	}

	$mail->WordWrap = 80; 
	if($aattachement != ""){
		$aattachement = split(";", $attachement);                                
		for ($intLoop=0; $intLoop < sizeof($aattachement); $intLoop++){
			$mail->AddAttachment(trim($aattachement[$intLoop]));
		}
	}
	
	$mail->IsHTML(true);                                  // set email format to HTML

	$mail->Subject = trim($subject);
	$mail->Body    = trim($content);	

	$mail->IsSMTP();                                      // set mailer to use SMTP
	$mail->Host = $MAILSERVER;  // specify main and backup server
	$mail->SMTPAuth = $MAILSMTPAUTH;     	// turn on SMTP authentication
	$mail->Username = $MAILUSERNAME;  	// SMTP username
	$mail->Password = $MAILPASSWORD; 	// SMTP password

	if(!$mail->Send())
	   return false;
	else
		return true;
}
?>