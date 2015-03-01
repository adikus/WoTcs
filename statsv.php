<?php

require 'config/main.php';
$db = require 'config/database.php';

$region = isset($_COOKIE['region'])?$_COOKIE['region']:1;
$t = isset($_GET['t'])?$_GET['t']:2;
$s = isset($_GET['s'])?$_GET['s']:'W';

$playerStats = new PlayerStats($db);

$vehs = array(
	1 => array(
		"3649" => "Bat.-Châtillon 25 t",
		"3681" => "STB-1",
		"4145" => "121",
		"7249" => "FV4202",
		"12305" => "E 50 Ausf. M",
		"13825" => "T-62A",
		"14113" => "M48A1 Patton",
		"14609" => "Leopard 1",
		"15617" => "Object 907",
		"15905" => "M60",
		"16897" => "Object 140",
		"17153" => "Object 430"
	),
	2 => array(
		"5425" => "113",
		"6145" => "IS-4",
		"6209" => "AMX 50 B",
		"6225" => "FV215b",
		"6929" => "Maus",
		"7169" => "IS-7",
		"9489" => "E 100",
		"10785" => "T110E5",
		"14881" => "T57 Heavy Tank",
		"58369" => "Object 260 mod. 1945",
		"58641" => "VK 72.01 (K)"
	),
	3 => array(
		"9297" => "FV215b (183)",
		"12049" => "Jagdpanzer E 100",
		"13089" => "T110E4",
		"13569" => "Object 268",
		"13857" => "T110E3",
		"13889" => "AMX 50 Foch (155)",
		"13905" => "FV4005 Stage II",
		"14337" => "Object 263",
		"16913" => "Waffenträger auf E 100"
	),
	4 => array(
		"8481" => "T92",
		"8705" => "Object 261",
		"9233" => "G.W. E 100",
		"11841" => "Bat.-Châtillon 155 58",
		"12369" => "Conqueror Gun Carriage" 
	)
);

ob_start();
?>
	<script type="text/javascript" charset="utf-8" src="https://www.google.com/jsapi"></script>  
	<script type="text/javascript" charset="utf-8" src="js/<?=$jsVersion?>/stats.js"></script>
<?
$scripts = ob_get_contents();
ob_end_clean();

ob_start();
?>
	<h2>Statistics
		<div class="btn-group">
			<a class="btn disabled" href="<?=URL_BASE?>statsv.php">Tanks</a>
			<a class="btn" href="<?=URL_BASE?>statsp.php">Players</a>
			<a class="btn" href="<?=URL_BASE?>statsc.php">Clans</a>
	    </div>
	</h2>
	<div class="btn-group">
		<a class="btn<?=$t==2?" disabled":""?>" href="<?=URL_BASE?>statsv.php?t=2&s=<?=$s?>">Heavy tanks</a>
		<a class="btn<?=$t==1?" disabled":""?>" href="<?=URL_BASE?>statsv.php?t=1&s=<?=$s?>">Medium tanks</a>
		<a class="btn<?=$t==4?" disabled":""?>" href="<?=URL_BASE?>statsv.php?t=4&s=<?=$s?>">Artillery</a>
		<a class="btn<?=$t==3?" disabled":""?>" href="<?=URL_BASE?>statsv.php?t=3&s=<?=$s?>">Tank destroyers</a>
    </div>
    <div class="btn-group">
		<a class="btn<?=$s=="W"?" disabled":""?>" href="<?=URL_BASE?>statsv.php?t=<?=$t?>&s=W">Winrate</a>
		<a class="btn<?=$s=="B"?" disabled":""?>" href="<?=URL_BASE?>statsv.php?t=<?=$t?>&s=B">Battles</a>
		<a class="btn<?=$s=="S"?" disabled":""?>" href="<?=URL_BASE?>statsv.php?t=<?=$t?>&s=S">Score</a>
    </div>
    <?foreach($vehs[$t] as $name => $lname){?>
		<div class="row">
			<div id="<?=$name?>-<?=$s?>_chart" class="span12"></div>
		</div>
		<div class="row">
			<div class="span12 border-row">Average: <span id="<?=$name?>_average"></span> | <?=$playerStats->bordersRow($name.'-'.$s)?></div>
		</div>
	<?}?>
	<script>
	$(document).ready(function(){
		var s = "<?=$s?>";
		var t = <?=$t?>;
		var vehs=<?=json_encode($vehs)?>;
		var cdata = <?=$playerStats->chartData("veh")?>;
		
		for(var name in vehs[t]){
			if(cdata[name+"-"+s]){
				drawPlayerDataChart(cdata[name+"-"+s],vehs[t][name],name+"-"+s+"_chart");
				$("#"+name+"_average").html(cdata[name+"-"+s].average);
			}
		}
	});
	</script>
<?
$content = ob_get_contents();
ob_end_clean();
$title = 'Statistics';
$active = 2;
$bigContent = true;

require 'layout.php';