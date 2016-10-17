<?php
	//require_once("../common/agent.php");
	//require_once("../common/class.invoice.php");	
	require_once("../common/functions.php");
	require_once("../common/sessions.php");
	require_once("../common/configs.php");		
	//require_once("../common/cnum2letter.php");		
	$pg = 850; // receipt
	
	$sql = "select p.PageURL 
					from tblSecUserPage up, tblSecPage p
					where up.PageID = p.PageID 
						and p.PageCode = '".$pg."' and up.UserID = ".$user['userid']." union
					select p.PageURL
					from tblSecUserGroup ug, tblSecGroupPage gp, tblSecPage p
					where ug.GroupID = gp.GroupID and gp.PageID = p.PageID 
						and p.PageCode = '".$pg."' and ug.UserID = ".$user['userid'];

		if($que = $mydb->sql_query($sql)){
			if($result = $mydb->sql_fetchrow($que)){
				$pageurl = $result['PageURL'];
				redirect($WebQuickBillRoot."PrintingReceipt.aspx?CustomerID=".$CustomerID."&PaymentID=".$PaymentID);				
			}else{
				include("../901.html");
			}
		}else{
			include("../901.html");
		}
		$mydb->sql_freeresult();
			
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="keywords" content="BRC Technology" />
<meta name="reply-to" content="supports@brc-tech.com" />
<title>..:: Wise Biller ::..</title>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
</head>

<body>


</body>
</html>
