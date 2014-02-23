<?php

require 'config/main.php';
$db = require 'config/database.php';

$region = isset($_COOKIE['region'])?$_COOKIE['region']:1;
$t = isset($_GET['t'])?$_GET['t']:2;
$s = isset($_GET['s'])?$_GET['s']:'W';

$playerStats = new PlayerStats($db);

$vehs = array(2 => array(
			"E-100"=>"E-100",
			"Maus"=>"Maus",
			"IS-7"=>"IS-7",
			"IS-4"=>"IS-4",
			"T110"=>"T110E5",
			"F10_AMX_50B"=>"AMX 50B",
			"T57_58"=>"T57 Heavy Tank",
			"Ch22_113"=>"113",
			"GB13_FV215b"=>"FV215b",
			//"VK7201"=>"VK7201",
			),1 => array(
			"Bat_Chatillon25t"=>"Bat Chatillon 25 t",
			"T62A"=>"T-62A",
			"M48A1"=>"M48A1 Patton",
			"E50_Ausf_M"=>"E-50 Ausf. M",
			"Ch19_121"=>"121",
			"GB70_FV4202_105"=>"FV4202",
			//"Object_907"=>"Object 907",
			//"M60"=>"M60",
			"Leopard1"=>"Leopard1",
			),4 => array(
			"G_E"=>"GW Typ E",
			"Object_261"=>"Object 261",
			"T92"=>"T92",
			"Bat_Chatillon155_58"=>"Bat Chatillon 155 58",
			),3 => array(
			"JagdPz_E100"=>"JagdPz E-100",
			"T110E4"=>"T110E4",
			"T110E3"=>"T110E3",
			"Object268"=>"Object 268",
			"GB48_FV215b_183"=>"FV215b (183)",
			"Object263"=>"Object 263",
			"AMX_50Fosh_155"=>"AMX-50 Foch (155)",
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
			drawPlayerDataChart(cdata[name+"-"+s],vehs[t][name],name+"-"+s+"_chart");
			$("#"+name+"_average").html(cdata[name+"-"+s].average);
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