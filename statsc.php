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
			<a class="btn" href="<?=URL_BASE?>statsp.php">Players</a>
			<a class="btn disabled" href="<?=URL_BASE?>statsc.php">Clans</a>
	    </div>
	</h2>
	<div class="row">
		<div id="gpl_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CGPL')?></div>
	</div>
	<div class="row">
		<div id="win_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CWIN','%')?></div>
	</div>
	<div class="row">
		<div id="sur_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CSUR','%')?></div>
	</div>
	<div class="row">
		<div id="frg_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CFRG')?></div>
	</div>
	<div class="row">
		<div id="kd_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CKD')?></div>
	</div>
	<div class="row">
		<div id="spt_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CSPT')?></div>
	</div>
	<div class="row">
		<div id="dmg_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CDMG')?></div>
	</div>
	<div class="row">
		<div id="cpt_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CCPT')?></div>
	</div>
	<div class="row">
		<div id="dpt_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CDPT')?></div>
	</div>
	<div class="row">
		<div id="exp_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CEXP')?></div>
	</div>
	<div class="row">
		<div id="wn7_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CWN7')?></div>
	</div>
	<div class="row">
		<div id="wn7a_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CWN7A')?></div>
	</div>
	<div class="row">
		<div id="wn8_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CWN8')?></div>
	</div>
	<div class="row">
		<div id="wn8a_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CWN8A')?></div>
	</div>
	<div class="row">
		<div id="efr_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CEFR')?></div>
	</div><div class="row">
		<div id="efra_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CEFRA')?></div>
	</div>
	<div class="row">
		<div id="sc3_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CSC3')?></div>
	</div>
	<div class="row">
		<div id="sc3a_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CSC3A')?></div>
	</div>
	<div class="row">
		<div id="mc_chart" class="span12"></div>
	</div>
	<div class="row">
		<div class="span12 border-row"><?=$playerStats->bordersRow('CMC')?></div>
	</div>
	
	<script>
	$(document).ready(function(){
	
		var cdata = <?=$playerStats->chartData("clan")?>;
		
		drawPlayerDataChart(cdata.CGPL,"Battles","gpl_chart");
		drawPlayerDataChart(cdata.CWIN,"Victories","win_chart");
		drawPlayerDataChart(cdata.CSUR,"Survived","sur_chart");
		drawPlayerDataChart(cdata.CFRG,"Frags","frg_chart");
		drawPlayerDataChart(cdata.CKD,"Kill/Death ratio","kd_chart");
		drawPlayerDataChart(cdata.CSPT,"Spotted","spt_chart");
		drawPlayerDataChart(cdata.CDMG,"Damage","dmg_chart");
		drawPlayerDataChart(cdata.CCPT,"Capture points","cpt_chart");
		drawPlayerDataChart(cdata.CDPT,"Defense points","dpt_chart");
		drawPlayerDataChart(cdata.CEXP,"Experience","exp_chart");
		drawPlayerDataChart(cdata.CWN7,"WN7","wn7_chart");
		drawPlayerDataChart(cdata.CWN7A,"Average WN7","wn7a_chart");
		drawPlayerDataChart(cdata.CWN8,"WN8","wn8_chart");
		drawPlayerDataChart(cdata.CWN8A,"Average WN8","wn8a_chart");
		drawPlayerDataChart(cdata.CEFR,"Efficiency","efr_chart");
		drawPlayerDataChart(cdata.CEFRA,"Average Efficiency","efra_chart");
		drawPlayerDataChart(cdata.CSC3,"Score","sc3_chart");
		drawPlayerDataChart(cdata.CSC3A,"Average Score","sc3a_chart");
		drawPlayerDataChart(cdata.CMC,"Member count","mc_chart");
	});
	</script>
<?
$content = ob_get_contents();
ob_end_clean();
$title = 'Statistics';
$active = 2;
$bigContent = true;

require 'layout.php';