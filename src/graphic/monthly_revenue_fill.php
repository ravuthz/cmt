<?php
include ("../jpgraph.php");
include ("../jpgraph_line.php");

include ("../../common/configs.php");
include ("../../common/mssql.php");
$mydb = new sql_db($DBSERVER, $DBUSERNAME, $DBPASSWORD, $DBNAME, true) or die("failed to connect to database");


$sid = $_GET['sid'];


$sql =	"	select	year(br.BillEndDate) Yid, 
					month(br.BillEndDate) Mid, 
					Sum(InvoiceAmount)/10000.00 InvoiceAmount
			from tblCustomerInvoice ci
			join tblCustProduct cp on ci.accid = cp.accid
			join tblTarPackage tp on tp.packageid = cp.packageid
			join tblSysBillRunCycleInfo br on br.cycleid = ci.billingcycleid
			where";
if($sid == 2){
			$sql .= " tp.ServiceID = 2 ";
}elseif($sid == 4){
			$sql .= " tp.ServiceID = 4 ";
}elseif($sid == 5){	
			$sql .= " tp.ServiceID in (1, 3, 8) ";
}
$sql	.= " and Year(br.BillEndDate) = Year(GetDate()) and br.BillProcessed = 1";								 
$sql	.= " Group by year(br.BillEndDate),month(br.BillEndDate)
			order by year(br.BillEndDate),month(br.BillEndDate)
		";
		$i = 0;
if($que = $mydb->sql_query($sql)){		
		while($result = $mydb->sql_fetchrow()){
			$BillEndDate[$i] = $result['BillEndDate'];																															
			$InvoiceAmount[$i] = $result['InvoiceAmount'];
			$i++;						
		}
	}
$mydb->sql_freeresult();

//Close Connection to database
$mydb->sql_close();


//$datay = array(20,15,33,5,17,35,22);

$datay = $InvoiceAmount;

// Setup the graph
$graph = new Graph(400,200);
$graph->SetMargin(40,40,20,30);	
$graph->SetScale("intlin");
$graph->SetMarginColor('darkgreen@0.8');

$graph->title->Set('Gradient filled line plot');
$graph->yscale->SetAutoMin(0);

// Create the line
$p1 = new LinePlot($datay);
$p1->SetColor("blue");
$p1->SetWeight(0);
$p1->SetFillGradient('red','yellow');

$graph->Add($p1);

// Output line
$graph->Stroke();

?>


