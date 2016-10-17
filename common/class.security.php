
<?php
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	
	/**
	 *	@Project: Wise Biller	
	 *	@File:		security.php	
	 *	
	 *	@Author: Chea vey	 
	 *
	 */
require_once("agent.php");
require_once("sendmail.php");
	class Security{
		
		function Security(){			
		}
		
		//
		//	Create User
		//	
		function CreateUser($UserName, $Password, $FullName, $EmailAddress, $Telephone="", $DOB="", $Description=""){

			global $mydb, $myinfo;
			$Password = md5($Password);
			$today = date("Y-m-d H:i:s");
			# check if user exist in the system
			$sql = "SELECT COUNT(*) AS 'exis' FROM tblSecuser WHERE UserName = '".$UserName."' or FullName = '".$FullName."'";
			if($que = $mydb->sql_query($sql)){
				$result = $mydb->sql_fetchrow($que);
				$exis = $result['exis'];
				if($exis > 0){
					#user name or fullname exist
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Username or full is already exist in the system.");
					return $retOut;
				}else{
					$sql = "INSERT INTO tblSecuser(UserName, Password, FullName, Status, EmailAddress, Telephone, DOB, Description, CreatedDate)
						 	VALUES('".$UserName."', '".$Password."', '".$FullName."', 1, '".$EmailAddress."', '".$Telephone."', '"
											 .$DOB."', '".$Description."', '".$today."')  
						 ";
			
					if($mydb->sql_query($sql)){
						# Upate user history
						$sql = "SELECT UserID FROM tblSecUser WHERE UserName='".$UserName."'";
						if($mydb->sql_query($sql)){
							$result = $mydb->sql_fetchrow();
							$UserID = $result['UserID'];
							#	Record user history
							$Description = "Create new user. Username: $UserName; Fullname: $FullName; Email: $EmailAddress; Telephone: $Telephone;
															Date of birth: $DOB; Description: $Description";
							$retOut = $this->CreateUserHistory($UserID, $Description);
							return $retOut;
						}else{
							$error = $mydb->sql_error();
							$retOut = $myinfo->error("Failed to get user id from user name $UserName.", $error['message']);
							return $retOut;
						}
					}else{
						$error = $mydb->sql_error();
						$retOut = $myinfo->error("Failed to create user to access to system.", $error['message']);
						return $retOut;
					}
				}
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to user exist in system.", $error['message']);
				return $retOut;				
			}						
			$mydb->sql_freeresult();
		}
		
		//
		//	Change uername / password
		//
		function ChageUserNamePass($UserID, $NewUserName, $NewPassword = ""){
			global $mydb, $myinfo;
			#	Change only user name
			if((empty($NewPassword)) || (is_null($NewPassword)) || ($NewPassword == "")){
				$sql = "UPDATE tblSecuser SET UserName ='".$NewUserName."' WHERE UserID = $UserID";
				$Description = "Change username. Username: $NewUserName";
			}else{
				#	Atleast changed password
				$NewPassword = md5($NewPassword);
				$sql = "UPDATE tblSecUser SET UserName ='".$NewUserName."', Password ='".$NewPassword."' WHERE UserID = $UserID";
				$Description = "Change username and password. Username: $NewUserName";
			}
			if($mydb->sql_query($sql)){
				$retOut = $this->CreateUserHistory($UserID, $Description);
				return $retOut;
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to change username / password.", $error['message']);
				return $retOut;
			}
			
		}
		
		//
		//	Update user status
		//
		function UpdateUserStatus($UserID, $Status){
			global $mydb, $myinfo;
			$sql = "UPDATE tblSecUser SET Status = $Status WHERE UserID = $UserID";
			if($que = $mydb->sql_query($sql)){
				$Description = "Update user status to $Status";
				$retOut = $this->CreateUserHistory($UserID, $Description);
				return $retOut;
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to update user status.", $error['message']);
				return $retOut;
			}
		}
		
		//
		//	Update user profile Email / Telephone / DOB / Description
		//
		function UpdateUserProfile($UserID, $Email, $Telephone, $DOB, $Description){
			global $mydb, $myinfo;
			$sql = "UPDATE tblSecUser SET 
								EmailAddress = '".$Email."', 
								Telephone = '".$Telephone."',
								DOB = '".$DOB."',
								Description = '".$Description."'
							WHERE UserID = $UserID";
			if($que = $mydb->sql_query($sql)){
				$Description = "Update user profile as Email: $Email; Telephone: $Telephone; DOB: $DOB; Description: $Description";
				$retOut = $this->CreateUserHistory($UserID, $Description);
				return $retOut;
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to update user profile.", $error['message']);
				return $retOut;
			}
		}
		
		//
		//	Generate new password and send to user via email
		//
		function RetrievePassword($UserName = "", $EmailAddress = ""){
			global $mydb, $myinfo;
			if((is_null($UserName)) || ($UserName == "") || (is_null($EmailAddress)) || ($EmailAddress == "")){
				$retOut = $myinfo->info("Please enter both username and correct email address.<br>Your password will be regenerated and sent to your provided email");
				return $retOut;
			}else{
				# get user id
				$sql = "SELECT UserID, FullName FROM tblSecUser WHERE UserName='".$UserName."' AND EmailAddress = '".$EmailAddress."'";

				if($que = $mydb->sql_query($sql)){
					$result = $mydb->sql_fetchrow();
					$UserID = $result['UserID'];
					$FullName = $result['FullName'];

					if((empty($UserID))  || (is_null($UserID)) || ($UserID == "")){
						$retOut = $myinfo->error("Both usrname and email address provided not found");
						return $retOut;
					}else{
						$NewPassword = $this->GenerateNewPassword();
						$NewPasswordmd5 = md5($NewPassword);
						$sql = "UPDATE tblSecUser SET Password = '".$NewPasswordmd5."' WHERE UserID = $UserID";
						if($mydb->sql_query($sql)){
							# start sending new password to user email
							$subject = "Wise Biller- your password";
							$body = "Dear <b>".$FullName."</b> Please use your information below to log in to Wise Bill system.<br>
												When you logged in to the system you can change your username and password anytime.<br><br>
												Username: ".$UserName."<br>
												Password: ".$NewPassword;
							$body .= $EMAILSIGNATURE;
							$retOut = sendMail($EmailAddress, "", $subject, $body, "");
							return $retOut;
							if(is_bool($retOut)){
								# record user history
								$Description = "Regenerate new password.";
								$retOut = $this->CreateUserHistory($UserID, $Description);
								return $retOut;
							}else{
								$error = "New password has been regenerated and update in data base but it fails to send to user email.";
								//$retOut = $myinfo->error("Failed to send password to user.".$error);
								return $retOut;
							}
						}else{
							$error = $mydb->sql_error();
							$retOut = $myinfo->error("Failed to update new password.", $error['message']);
							return $retOut;
						}
					}
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to get your password from your privided information.", $error['message']);
					return $retOut;
				}
				$mydb->sql_freeresult();
			}
		}
				
		//
		//	Create user history
		//
		function CreateUserHistory($UserID, $Description){
			global $mydb, $myinfo;
			$today = date("Y-m-d H:i:s");
			$sql = "INSERT INTO tblSecuserHistory(UserID, Description, ActionDate)
							VALUES($UserID, '".$Description."', '".$today."')";
			if($que = $mydb->sql_query($sql)){
				return true;
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to create user profile history.", $error['message']);
				return $retOut;
			}
		}
		
		
		//
		//	Generate new password / Will offer 10 words randomly.
		//
		function GenerateNewPassword(){
			return  chr(rand(97, 122)).chr(rand(97, 122)).
							chr(rand(97, 122)).chr(rand(97, 122)).
							chr(rand(97, 122)).chr(rand(97, 122)).
							chr(rand(97, 122)).chr(rand(97, 122)).
							chr(rand(97, 122)).chr(rand(97, 122));
		}				
	}// end class
?>
