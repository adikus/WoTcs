<?php

require 'config/main.php';
$db = require 'config/database.php';

$region = isset($_COOKIE['region'])?$_COOKIE['region']:1;

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
			<a class="btn disabled" href="<?=URL_BASE?>stats.php">Tanks</a>
			<a class="btn" href="<?=URL_BASE?>statsp.php">Players</a>
	    </div>
	</h2>
	<div class="row">
		<div id="heavys_count_chart" class="span4"></div>
		<div id="heavys_battles_chart" class="span4"></div>
		<div id="heavys_win_chart" class="span4"></div>
	</div>
	<div class="row">
		<div id="meds_count_chart" class="span4"></div>
		<div id="meds_battles_chart" class="span4"></div>
		<div id="meds_win_chart" class="span4"></div>
	</div>
	<div class="row">
		<div id="tds_count_chart" class="span4"></div>
		<div id="tds_battles_chart" class="span4"></div>
		<div id="tds_win_chart" class="span4"></div>
	</div>
	<div class="row">
		<div id="artys_count_chart" class="span4"></div>
		<div id="artys_battles_chart" class="span4"></div>
		<div id="artys_win_chart" class="span4"></div>
	</div>
	
	<script>
	tanks = {1:[],2:[],3:[],4:[]};
	$(document).ready(function(){
		
		$.ajax({
			url: 'http://wotcsapiplayers.herokuapp.com/stats/',
			dataType: 'json',
			success: function(data){
				
				for(i in data.vehs){
					data.vehs[i].winrate = Math.round(data.vehs[i].winrate*100)/100;
					tanks[data.vehs[i].type].push(data.vehs[i]);
				}
				
				options.width = $('#heavys_count_chart').width();
		
				drawChartsHeavy();
				drawChartsMed();
				drawChartsTD();
				drawChartsArty();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log('AJAX Error');
			}
		});
	});
	</script>
<?
$content = ob_get_contents();
ob_end_clean();
$title = 'Statistics';
$active = 2;
$bigContent = true;

require 'layout.php';