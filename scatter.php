<?php

require 'config/main.php';
$db = require 'config/database.php';

$region = isset($_COOKIE['region'])?$_COOKIE['region']:1;

ob_start();
?>
	<script type="text/javascript" charset="utf-8" src="https://www.google.com/jsapi"></script>  
	<script type="text/javascript" charset="utf-8" src="js/<?=$jsVersion?>/scatter.js"></script>
<?
$scripts = ob_get_contents();
ob_end_clean();

ob_start();
?>
	<h2>Player - Vehicle scatter chart</h2>
	<div class="row">
		<div id="scatter_chart" class="span12" style="width: 100%; height: 1200px;"></div>
	</div>
	
	<script>
	google.load("visualization", "1.1", {packages:["line"]});

	$(document).ready(function(){

		var dataArray = [];
		var finalData = {};
		var firstRow = ['Player WinRate'];
		var ids = [];

		for(var id in SCATTER_DATA){
			firstRow.push(SCATTER_DATA[id].name);
			ids.push(id);

			var tankData = SCATTER_DATA[id].values;

			for(var PWR in tankData){
				if(PWR > 80 || PWR < 30)continue;
				var PWRr = Math.round(PWR/3)*3;
				if(!finalData[PWRr])finalData[PWRr] = {};
				for(var TWR in tankData[PWR]){
					if(!finalData[PWRr][TWR])finalData[PWRr][TWR] = {};
					finalData[PWRr][TWR][id] = tankData[PWR][TWR];
				}
			}
		}
		firstRow.push('Average');

		console.log(finalData);

		for(var PWR in finalData){
			var row = [parseFloat(PWR)];

			var tTWRs = 0;
			var tTWRc = 0;

			for(var i in ids){
				var TWRs = 0;
				var TWRc = 0;
				id = ids[i];


				for(var TWR in finalData[PWR]){
					if(finalData[PWR][TWR][id]){
						TWRc += finalData[PWR][TWR][id];
						TWRs += parseFloat(TWR) * finalData[PWR][TWR][id];
					}
				}

				tTWRc += TWRc;
				tTWRs += TWRs;
				row.push(TWRc ? TWRs/TWRc : null);					
			}
			row.push(tTWRs/tTWRc);
			dataArray.push(row);
		}

		dataArray.sort(function(a, b){
			return a > b;
		});

		dataArray.unshift(firstRow);

		console.log(dataArray);

		var data = google.visualization.arrayToDataTable(dataArray);
        var options = {
          title: 'Player WinRate vs. Tank WinRate comparison',
          hAxis: {title: 'Player WinRate', viewWindow: { min: 40, max: 70}},
          vAxis: {title: 'Tank WinRate', viewWindow: { min: 49, max: 62}}
        };
        var chart = new google.charts.Line(document.getElementById('scatter_chart'));

        chart.draw(data, google.charts.Line.convertOptions(options));
        //chart.draw(data, options);
	});
	</script>
<?
$content = ob_get_contents();
ob_end_clean();
$title = 'Statistics';
$active = 2;
$bigContent = true;

require 'layout.php';