<?php
include ("../jpgraph.php");
include ("../jpgraph_bar.php");
include ("../jpgraph_line.php");


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
			where tp.ServiceID in (select ServiceID from tlkpService where GroupServiceID = ".$sid.")";
			

$sql	.= " and Year(br.BillEndDate)=".$year;			 
$sql	.= " Group by month(br.BillEndDate),DateName(mm,br.BillEndDate)
			order by month(br.BillEndDate)
		";
		$i = 0;
if($que = $mydb->sql_query($sql)){		
		while($result = $mydb->sql_fetchrow()){
			
			$BillEndDate[$i] = $result['BillEndDate'];																															
			$Unpaid[$i] = floatval($result['Unpaid']);
			$Paid[$i] = $result['Paid'];												
			$i++;						
		}
	}
$mydb->sql_freeresult();

//Close Connection to database
$mydb->sql_close();



// Some "random" data
//$ydata  = array(10,120,80,190,260,170,60,40,20,230);
//$ydata2 = array(10,70,40,120,200,60,80,40,20,5);

$ydata  = $Unpaid ; 
$ydata2 = $Unpaid ;
// Get a list of month using the current locale
//$months = $gDateLocale->GetShortMonth();

$months = $BillEndDate ;

// Create the graph. 
$graph = new Graph(500,300);	
$graph->SetScale("textlin");
$graph->SetMarginColor('white');

// Adjust the margin slightly so that we use the 
// entire area (since we don't use a frame)
$graph->SetMargin(40,40,50,40);

// Box around plotarea
$graph->SetBox(); 

// No frame around the image
$graph->SetShadow();
$graph->SetFrame(true);



// Setup graph title ands fonts
$graph->title->Set("Aging of Unpaid-Scale per Month");
$graph->title->SetFont(FF_VERDANA,FS_BOLD,14);
$graph->title->SetColor('blue');


// Setup the tab title
$graph->tabtitle->Set('Year '.$year);
$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,10);

// Setup the X and Y grid
$graph->ygrid->SetFill(true,'#DDDDDD@0.5','#BBBBBB@0.5');
$graph->ygrid->SetLineStyle('dashed');
$graph->ygrid->SetColor('gray');
$graph->xgrid->Show();
$graph->xgrid->SetLineStyle('dashed');
$graph->xgrid->SetColor('gray');

// Setup month as labels on the X-axis
$graph->xaxis->SetTickLabels($months);
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
$graph->xaxis->SetLabelAngle(45);

// Create a bar pot
$bplot = new BarPlot($ydata);
$bplot->SetWidth(0.4);
$fcol='#440000';
$tcol='#FF9090';

$bplot->SetFillGradient($fcol,$tcol,GRAD_LEFT_REFLECTION);

// Set line weigth to 0 so that there are no border
// around each bar
$bplot->SetWeight(0);

$graph->Add($bplot);

// Create filled line plot
$lplot = new LinePlot($ydata2);
$lplot->SetFillColor('skyblue@0.5');
$lplot->SetColor('navy@0.7');
$lplot->SetBarCenter();

$lplot->mark->SetType(MARK_SQUARE);
$lplot->mark->SetColor('blue@0.5');
$lplot->mark->SetFillColor('lightblue');
$lplot->mark->SetSize(6);

$graph->Add($lplot);

// Setup the values that are displayed on top of each bar
$bplot->value->Show();
// Must use TTF fonts if we want text at an arbitrary angle
$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot->value->SetAngle(30);
$bplot->value->SetFormat('$ %0.2f');
// Black color for positive values and darkred for negative values
$bplot->value->SetColor("black","darkred");

$graph->Add($bplot);

// .. and finally send it back to the browser
$graph->Stroke();
?>
