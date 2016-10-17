<?php
// we'll generate XML output
header('Content-Type: text/xml');
// generate XML header
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
// create the <response> element
//echo '<response>';
require_once("../common/functions.php");
require_once("../common/agent.php");

	$sql = "select ItemID, ItemName from tlkpInvoiceItem where IsManual = 1 and itemgroupid = 2 order by ItemName";
			
	
	$out = "";

	if($que = $mydb->sql_query($sql)){
		while($result = $mydb->sql_fetchrow($que)){
			$id = $result['ItemID'];
			$name = $result['ItemName'];
				$out .= "<option value='".$id."'>".str_replace(array("&","."),"",$name)."|".$id."</option>";
		}
	}

?>
<response>
	
		<?php 			
			echo $out;
		?>
	
</response>
