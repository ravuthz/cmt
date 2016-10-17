<?php
// Example for use of JpGraph, 
// ljp, 01/03/01 20:32
include ("../jpgraph.php");
include ("../jpgraph_bar.php");

include ("../../common/configs.php");
include ("../../common/mssql.php");
$mydb = new sql_db($DBSERVER, $DBUSERNAME, $DBPASSWORD, $DBNAME, true) or die("failed to connect to database");

$sid = $_GET['sid'];

$sql =	"	select  DateName(yy,br.BillEndDate) BillEndDate, Sum(UnpaidAmount) Unpaid
			from tblCustomerInvoice ci
			join tblCustProduct cp on ci.accid = cp.accid
			join tblTarPackage tp on tp.packageid = cp.packageid
			join tblSysBillRunCycleInfo br on br.cycleid = ci.billingcycleid
			where tp.ServiceID in (select ServiceID from tlkpService where GroupServiceID = ".$sid.")";
			 
$sql	.= " Group by DateName(yy,br.BillEndDate)
			order by DateName(yy,br.BillEndDate)
		";
		$i = 0;
if($que = $mydb->sql_query($sql)){		
		while($result = $mydb->sql_fetchrow()){
			
			$BillEndDate[$i] = $result['BillEndDate'];																															
			$Unpaid[$i] = $result['Unpaid'];												
			$i++;						
		}
	}
$mydb->sql_freeresult();





// We need some data

$datay=$Unpaid;
$datax=$BillEndDate;


// Setup the graph. 
$graph = new Graph(370,180,"auto");	
$graph->img->SetMargin(42,20,35,40);
$graph->SetScale("textlin");
$graph->SetMarginColor("lightblue");
$graph->SetShadow();

// Set up the title for the graph
$graph->title->Set("Aging Report per Year");
$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
$graph->title->SetColor("darkred");

// Setup font for axis
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);;
$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,8);;

// Show 0 label on Y-axis (default is not to show)
$graph->yscale->ticks->SupressZeroLabel(false);

// Setup X-axis labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(50);

// Create the bar pot
$bplot = new BarPlot($datay);
$bplot->SetWidth(0.4);

$bplot->value->Show();


// Setup color for gradient fill style 
$bplot->SetFillGradient("navy","#EEEEEE",GRAD_LEFT_REFLECTION);

// Set color for the frame of each bar
$bplot->SetColor("white");
$graph->Add($bplot);

// Finally send the graph to the browser


$graph->Stroke();




//Close Connection to database
$mydb->sql_close();
?>




