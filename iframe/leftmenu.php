
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#feeac2" bordercolor="#aaaaaa" style="border-collapse:collapse">
	<tr>
		<td valign="top">			
			<div id="mainmenu"><span id="todo" class="white">TODO</span></div>
			<div style="background-color:#ffffff" id="menutodo">	
				<?php
					if((empty($pg)) || (!isset($pg))){
				?>
				<!--<ul id="menu_links">								
					<li><a href="./?pg=890">Advanced search</a></li>											
					<li><a href="./?pg=201">Request pending...</a></li>											
					<li><a href="./?pg=202">Package list</a></li>															
				</ul>	-->	
				<?php 
					}else{ 
						print "<script>process(".$pg.", 0);</script>";
					}
				?>
						
			</div>			
			<div id="mainmenu"><a href="javascript:process(0, 1);"><span id="lcustomerservice" class="blue">Customer service</span></a></div>	
			<div id="mainmenu"><a href="javascript:process(0, 2);"><span id="lsales" class="blue">Registration</span></a></div>	
			<div id="mainmenu"><a href="javascript:process(0, 3);"><span id="lcashier" class="blue">Cashier</span></a></div>	
			<div id="mainmenu"><a href="javascript:process(0, 4);"><span id="lfinance" class="blue">Finance</span></a></div>
			<div id="mainmenu"><a href="javascript:process(0, 5);"><span id="ltechnical" class="blue">Technical</span></a></div>	
			<div id="mainmenu"><a href="javascript:process(0, 6);"><span id="ladministrator" class="blue">Administration</span></a></div>
			<div id="mainmenu"><a href="javascript:process(0, 7);"><span id="lreport" class="blue">Reports</span></a></div>
			<div id="mainmenu"><a href="javascript:process(0, 8);"><span id="lhelp" class="blue">Help</span></a></div>									
			<div id="mainmenu"><a href="javascript:process(0, 9);"><span id="Traffic" class="blue">Traffic</span></a></div>
			<div id="mainmenu"><a href="javascript:process(0, 10);"><span id="Announcement" class="blue">Announcement</span></a></div>									
		</td>
	</td>
</table>

