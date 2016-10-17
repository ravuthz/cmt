<?php
require_once("../common/functions.php");
require_once("../common/agent.php");
// we'll generate XML output
//header('Content-Type: text/xml');
// generate XML header
//echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"? >';
// create the <response> element
//echo '<response>';
$pag = $_GET['pag'];
$id = $_GET['contentid'];
if(intval($pag) != 0){
$sql = "select s.Menu, s.URL
				from tblcatSub s, tblCatpage p
				where s.CatID = p.CatID and p.PageID = $pag";
}else{
	$sql = "select Menu, URL
					from tblcatSub 
					where CatID = $id";
}
if($que = $mydb->sql_query($sql)){
	$i = 0;
	$out = '<ul id="menu_links">';
	while($result = $mydb->sql_fetchrow($que)){
		$contentMenu = $result['Menu'];
		$contentURL = $result['URL'];
		$i++; 
		$out .= '<li>'.$contentURL.'</li>';
	}
	$out .= '</ul>';
	if($i == 0){
		$out = '<ul id="menu_links">
							<li><span class="disabled">Unable to get content</span></li>
						 </ul>
						';
	}
}else{
	$out = '<ul id="menu_links">
						<li><span class="disabled">Unknown content</span></li>
					 </ul>
					';
}
$mydb->sql_freeresult($que);

/*
$id = $_GET['contentid'];
	switch(intval($id)){
		# Customer service
		case 1: 
						$out = '<ul id="menu_links">
											<li><a href="./?pg=1">New customer</a></li>
											<li><a href="./?pg=2">New corperate</a></li>
											<li><a href="./?pg=21">New messenger</a></li>
											<li><a href="./?pg=22">New salesman</a></li>
											<li>&nbsp;</li>
											<li><a href="./?pg=890">Advanced search</a></li>
											<li><a href="./?pg=300">Unpaid Invoice Search</a></li>
											<li><a href="./?pg=201">Request pending...</a></li>
											<li><a href="./?pg=236">Cash drawer</a></li>
											<li><a href="./?pg=202">Package list</a></li>
											<li>&nbsp;</li>
											<li><a href="./?pg=229">Salesman list</a></li>
											<li><a href="./?pg=228">Messenger list</a></li>
											<li><a href="./?pg=209">New account reports</a></li>	
											<li><a href="./?pg=223">Reconnect account</a></li>
											
										</ul>
										';	
					break;
		# Finance					
		case 2: $out = '<ul id="menu_links">
											<li><a href="./?pg=203">Account no deposit</a></li>
											<li><a href="./?pg=204">Account with deposit</a></li>
											<li><a href="./?pg=206">Cash daily collection</a></li>											
											<li><a href="./?pg=210">Credit note</a></li>
											<li><a href="./?pg=211">Write off bad debt</a></li>											
										</ul>
										';
				break;
		# Administrator
		case 3: $out = '<ul id="menu_links">																
										  <li><a href="./?pg=900">Open cash drawer</a></li>
											<li><a href="./?pg=901">Close cash drawer</a></li>
											<li><a href="./?pg=1012">Tariff</a></li>											
										  	<li><a href="./?pg=1015">Discount</a></li>											
										    <li><a href="./?pg=1022">ISP</a></li>
										    <li><a href="./?pg=1100">Credit Management</a></li>
											<li><a href="./?pg=903">Security management</a></li>
											<li><a href="./?pg=800">CPE Administration</a></li>
										</ul>';
				break;
		
		# Report
		case 4: $out = '<ul id="menu_links">
											<li><a href="./?pg=200">Switch reports</a></li>
											<li><a href="./?pg=215">Operation reports</a></li>
																																												
											<li><a href="./?pg=208">Barred accounts</a></li>											
											<li><a href="./?pg=207">Closed accounts</a></li>
											<li><a href="./?pg=213">Services</a></li>									
											<li><a href="./?pg=222">Aging open invoice</a></li>
											<li><a href="./?pg=226">Cash collection</a></li>
											<li><a href="./?pg=240">Close cash drawer</a></li>
											<li><a href="./?pg=221">Invoice Report</a></li>																						
											<li><a href="./?pg=220">Risk debtor</a></li>
											<li><a href="./?pg=239">Risk debtor period</a></li>
											<li><a href="./?pg=234">Late invoice payment</a></li>
											<li><a href="./?pg=212">Log transaction</a></li>
											<li><a href="./?pg=217">Account Request</a></li>
											<li><a href="./?pg=230">Interconnection Report</a></li>
											<li><a href="./?pg=233">Credit rule Report</a></li>
											<li><a href="./?pg=231">CPE Used Report</a></li>
											<li><a href="./?pg=232">Customer by category</a></li>
										</ul>
										';
			break;
		# Help
		case 5: $out = '<ul id="menu_links">
											<li><span class="disabled">About</span></li>
											<li><span class="disabled">User help</span></li>
											<li><span class="disabled">Administration help</span></li>
										</ul>
										';
						break;				
		default: $out = '<ul id="menu-links">
											<li><span class="disabled">Unknown content</span></li>
										 </ul>
										';
	}

*/
// close the <response> element
//echo '</response>';
?>
<!--<response>-->
	
		<?php 
			//$out = xmlEncode($out);
			echo $out;
		?>
	
<!--</response>-->