<?php
	require_once("../common/agent.php");	
	require_once("../common/functions.php");	
	$fname = FixQuotes($_GET['fname']);
	$furl =FixQuotes( $_FILES['furl']);	
	
	
	
	$now = date("Y/M/d H:i:s");
	$Operator = $user["FullName"];
	
	$filetype = substr($furl, strlen($furl)-3, 3);
	
	$url = str_replace(" ", "_", $fname);
	$url .= ".".$filetype;
	$dest = "./report/extra/";
	$dest .= $url;
		
	#_________[Register file]
	$sql = "INSERT INTO tblreportlist(Name, URL, SubmittedDate, SubmittedBy, IsAchieved)
					VALUES('".$fname."', '".$url."', '".$now."', '".$Operator."', 0)";
	if($mydb->sql_query($sql)){
		#________[Copy file to server]		
		
		if(move_uploaded_file($_FILES['furl']['tmp_name'], $dest)) {
				echo "The file ".  basename( $_FILES['furl']['name']). 
				" has been uploaded";
		} else{
				echo "There was an error uploading the file, please try again!".$dest;
		}
	}else{
		$error = $mydb->sql_error();
		print $myinfo->eorror("Error: Failed to register report in system to be download.", $error['message']);
	}
?>