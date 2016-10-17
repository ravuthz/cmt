<?php
// $Id: barscalecallbackex1.php,v 1.2 2002/07/11 23:27:28 aditus Exp $
include ("../jpgraph.php");
include ("../jpgraph_bar.php");





include ("../../common/configs.php");
include ("../../common/mssql.php");
$mydb = new sql_db($DBSERVER, $DBUSERNAME, $DBPASSWORD, $DBNAME, true) or die("failed to connect to database");

$year = $_GET['year'];
$sid = $_GET['sid'];


$sql =	"	select  month(br.BillEndDate) Mid, left(DateName(mm,br.BillEndDate),3) BillEndDate, Sum(UnpaidAmount) Unpaid, Sum(InvoiceAmount) - Sum(UnpaidAmount) Paid
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

$sql	.= " and Year(br.BillEndDate)=".$year;			 
$sql	.= " Group by month(br.BillEndDate),DateName(mm,br.BillEndDate)
			order by month(br.BillEndDate)
		";
		$i = 0;
if($que = $mydb->sql_query($sql)){		
		while($result = $mydb->sql_fetchrow()){
			
			$BillEndDate[$i] = $result['BillEndDate'];																															
			$Unpaid[$i] = $result['Unpaid'];
			$Paid[$i] = $result['Paid'];												
			$i++;						
		}
	}
$mydb->sql_freeresult();

//Close Connection to database
$mydb->sql_close();


// Callback function for Y-scale
function yScaleCallback($aVal) {
	return number_format($aVal);
}

// Some data

//$datay=array(120567,134013,192000,87000);
$datay = $Unpaid;

// Create the graph and setup the basic parameters 
$graph = new Graph(600,350,'auto');	
$graph->img->SetMargin(50,30,35,50);
$graph->SetScale("textint");
$graph->SetShadow();
$graph->SetFrame(true); // No border around the graph


// Box around plotarea
//$graph->SetBox(); 

// Setup the tab title
//$graph->tabtitle->Set('Year 2003');
//$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,10);



// Setup the X and Y grid
$graph->ygrid->SetFill(true,'#DDDDDD@0.5','#BBBBBB@0.5');
$graph->ygrid->SetLineStyle('dashed');
$graph->ygrid->SetColor('gray');
$graph->xgrid->Show();
$graph->xgrid->SetLineStyle('dashed');
$graph->xgrid->SetColor('gray');



// Add some grace to the top so that the scale doesn't
// end exactly at the max value. 
// Since we are using integer scale the gace gets intervalled
// to adding integer values.
// For example grace 10 to 100 will add 1 to max, 101-200 adds 2
// and so on...
$graph->yaxis->scale->SetGrace(20);
$graph->yaxis->SetLabelFormatCallback('yScaleCallback');
$graph->yaxis->SetColor('yellow');


// Setup X-axis labels
//$a = $gDateLocale->GetShortMonth();
$a = $BillEndDate;

$graph->xaxis->SetTickLabels($a);
$graph->xaxis->SetFont(FF_FONT2);


// Setup graph title ands fonts
$graph->title->Set("Aging of Unpaid-Scale per Year ".$year);
$graph->title->SetFont(FF_VERDANA,FS_BOLD,14);
$graph->title->SetColor('blue');
//$graph->xaxis->title->Set("Year ".$year);
//$graph->xaxis->title->SetFont(FF_FONT2,FS_BOLD);
                              
// Create a bar pot
$bplot = new BarPlot($datay);
$bplot->SetFillGradient("#440000","#FF9090",GRAD_LEFT_REFLECTION);
$bplot->SetWidth(0.3);
$bplot->SetShadow();

// Setup the values that are displayed on top of each bar
$bplot->value->Show();
// Must use TTF fonts if we want text at an arbitrary angle
$bplot->value->SetFont(FF_ARIAL,FS_BOLD);
$bplot->value->SetAngle(45);
$bplot->value->SetFormat('$ %0.0f');
// Black color for positive values and darkred for negative values
$bplot->value->SetColor("black","darkred");
$graph->Add($bplot);

// Finally stroke the graph
$graph->Stroke();
?>
