<?php

require 'config/main.php';
$db = require 'config/database.php';

$region = isset($_COOKIE['region'])?$_COOKIE['region']:1;

$playerStats = new PlayerStats($db);

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
			<a class="btn" href="<?=URL_BASE?>statsv.php">Tanks</a>
			<a class="btn disabled" href="<?=URL_BASE?>statsp.php">Players</a>
			<a class="btn" href="<?=URL_BASE?>statsc.php">Clans</a>
	    </div>
	</h2>
	<div class="row">
		<div id="gpl_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('GPL')?></div>
	</div>
	<div class="row">
		<div id="win_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('WIN','%')?></div>
	</div>
	<div class="row">
		<div id="sur_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('SUR','%')?></div>
	</div>
	<div class="row">
		<div id="frg_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('FRG')?></div>
	</div>
	<div class="row">
		<div id="kd_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('KD')?></div>
	</div>
	<div class="row">
		<div id="spt_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('SPT')?></div>
	</div>
	<div class="row">
		<div id="dmg_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('DMG')?></div>
	</div>
	<div class="row">
		<div id="cpt_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CPT')?></div>
	</div>
	<div class="row">
		<div id="dpt_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('DPT')?></div>
	</div>
	<div class="row">
		<div id="exp_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('EXP')?></div>
	</div>
	<div class="row">
		<div id="wn7_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('WN7')?></div>
	</div>
	<div class="row">
		<div id="wn8_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('WN8')?></div>
	</div>
	<div class="row">
		<div id="efr_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('EFR')?></div>
	</div>
	<div class="row">
		<div id="sc3_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('SC3')?></div>
	</div>
	
	<script>
	$(document).ready(function(){
	
		var cdata = <?=$playerStats->chartData()?>;
		
		drawPlayerDataChart(cdata.GPL,"Battles","gpl_chart");
		drawPlayerDataChart(cdata.WIN,"Victories","win_chart");
		drawPlayerDataChart(cdata.SUR,"Survived","sur_chart");
		drawPlayerDataChart(cdata.FRG,"Frags","frg_chart");
		drawPlayerDataChart(cdata.KD,"Kill/Death ratio","kd_chart");
		drawPlayerDataChart(cdata.SPT,"Spotted","spt_chart");
		drawPlayerDataChart(cdata.DMG,"Damage","dmg_chart");
		drawPlayerDataChart(cdata.CPT,"Capture points","cpt_chart");
		drawPlayerDataChart(cdata.DPT,"Defense points","dpt_chart");
		drawPlayerDataChart(cdata.EXP,"Experience","exp_chart");
		drawPlayerDataChart(cdata.WN7,"WN7","wn7_chart");
		drawPlayerDataChart(cdata.WN8,"WN8","wn8_chart");
		drawPlayerDataChart(cdata.EFR,"Efficiency","efr_chart");
		drawPlayerDataChart(cdata.SC3,"Score","sc3_chart");
	});
	</script>
<?
$content = ob_get_contents();
ob_end_clean();
$title = 'Statistics';
$active = 2;
$bigContent = true;

require 'layout.php';