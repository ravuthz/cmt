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
			join tblSysBillRunCycleInfo br on br.cycleid = ci.billingcycleid
			join tblTarPackage tp on tp.packageid = br.packageid
			
			where";
if($sid == 2){
			$sql .= " tp.ServiceID = 2 ";
}elseif($sid == 4){
			$sql .= " tp.ServiceID = 4 ";
}elseif($sid == 1){	
			$sql .= " tp.ServiceID in (1, 3, 8) ";
}
$sql	.= " and datediff(month,br.BillEndDate,GetDate()) <= 12 and br.BillProcessed = 1 ";								 
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



//$ydata = array(11,3,8,12,5,1,9,13,5,7);

$ydata = $InvoiceAmount;

// Create the graph. These two calls are always required
$graph = new Graph(300,110,"auto");	
$graph->SetScale("textlin");

// Create the linear plot
$lineplot=new LinePlot($ydata);
$lineplot->mark->SetType(MARK_UTRIANGLE);

// Add the plot to the graph
$graph->Add($lineplot);

$graph->img->SetMargin(40,25,20,40);
$graph->title->Set("Monthly Revenue Status");
$graph->xaxis->title->Set("Month");
$graph->yaxis->title->Set("Revenue");

$graph->title->SetFont(FF_FONT1,FS_BOLD,6);
$graph->yaxis->title->SetFont(FF_FONT1,FS_NORMAL,6);
$graph->xaxis->title->SetFont(FF_FONT1,FS_NORMAL,6);

$lineplot->SetColor("blue");
$lineplot->SetWeight(2);
$graph->yaxis->SetColor("blue");
$graph->yaxis->SetWeight(2);
$graph->SetShadow();


// Create the line
$p1 = new LinePlot($ydata);
$p1->SetColor("red");
$p1->SetWeight(0);
$p1->SetFillGradient('red','yellow');

$graph->Add($p1);



// Display the graph
$graph->Stroke();
?>
