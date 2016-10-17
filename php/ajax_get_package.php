<?php
// we'll generate XML output
header('Content-Type: text/xml');
// generate XML header
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
// create the <response> element
//echo '<response>';
require_once("../common/functions.php");
require_once("../common/agent.php");
$s = $_GET['s'];
			
	if ($s==0 || empty($s) || !isset($s))  
	{
		$sql = "SELECT PackageID, TarName, CreatedDate, RegistrationFee, ConfigurationFee, CPEFee,
									ISDNFee, SpecialNumber
						from tblTarPackage where Status = 1 and ServiceID = 2 
						and DepositAmount<>1
						order by 2";													
	}
	else if ($s==1)  
	{
		$sql = "SELECT PackageID, TarName, CreatedDate, RegistrationFee, ConfigurationFee, CPEFee,
									ISDNFee, SpecialNumber
						from tblTarPackage where Status = 1 and ServiceID in (1,3,8) and DepositAmount<>1 order by 2";													
	}		
	else if ($s==2)  
	{
		$sql = "SELECT PackageID, TarName, CreatedDate, RegistrationFee, ConfigurationFee, CPEFee,
									ISDNFee, SpecialNumber
						from tblTarPackage where Status = 1 and ServiceID in (4) and DepositAmount<>1 order by 2";													
	}
			
	
	$out = "";

	if($que = $mydb->sql_query($sql)){
		while($result = $mydb->sql_fetchrow($que)){
			$id = $result['PackageID'];
			$name = $result['TarName'];
				$out .= "<option value='".$id."'>".str_replace(array("&","."),"",$name)."|".$id."</option>";
		}
	}

?>
<response>
	
		<?php 			
			echo $out;
		?>
	
</response>
