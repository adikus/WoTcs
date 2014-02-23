<?php

require './config/main.php';
$db = require './config/database.php';

$region = isset($_COOKIE['region'])?$_COOKIE['region']:1;

$id = isset($_GET['id'])?$_GET['id']-10000:null;
print_r($id);
$wid = isset($_GET['wid'])?$_GET['wid']:null;

if($id == null && $wid == null){
	header( 'Location: '.URL_BASE );
	exit();
}
if($wid == null){
	header( 'Location: '.URL_BASE );
	$_SESSION['emsg'] = "We are sorry but your link is outdated as a result of a recent update. Please use search bar below.";
	exit();
}

$clan = Clan::findFirst($db,array('wid' => $wid),array(),'*');//new Clan($db,$wid,'*');
if(!$clan){
	$clan = new Clan($db);
	$clan->setWid($wid);	
}
if(!$clan->exists()){
	$clan->saveToDB();
}

//TODO visits

$active = 1;
$bigContent = true;
$title = $clan->getTag()?$clan->getTag():'Loading...';

ob_start();
?>
	<script src="js/<?=$jsVersion?>/lib/class.js"></script>
	<script src="js/<?=$jsVersion?>/utils.php"></script>
	<script src="js/<?=$jsVersion?>/lib/underscore-min.js"></script>
	<script src="js/<?=$jsVersion?>/jquery.tablesorter.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="https://www.google.com/jsapi"></script>
	<script data-main="js/<?=$jsVersion?>/require.js/clan/main" src="js/<?=$jsVersion?>/lib/require.js"></script>
	<script>
		google.load("visualization", "1", {packages:["corechart"]});
	</script>
<?
$scripts = ob_get_contents();
ob_end_clean();

ob_start();
?>
<div class="hero-unit">
	<div class="row">
		<div class="ad-300-250">
			<!-- BuySellAds Zone Code -->
			<?if(!LOCAL){?>
			<div id="bsap_1289970" class="bsarocks bsap_29ec8931db05ecf5d8b2ae91858a5977"></div>
			<?}?>
			<!-- End BuySellAds Zone Code -->
		</div>
		<div class="ad-fill-rest">
			<h1><img class="clan-emblem" src="img/clan-loader.gif"/><span class="tag">[<?=$clan->getTag();?>] </span><?=$clan->getName();?></h1>
			<p id="motto"><?=$clan->getMotto();?></p>
			<div class="row-fluid">
				<div class="span5 offset1">
					Members count: <span class="clan-info-value" id="members_count"></span><br>
					<span id="last_updated"></span>
				</div>
				<div id="hero_clan_stats" class="span6">
					<div class="row head">
						<div class="left">&nbsp;</div>
						<div class="right">Total</div>
						<div class="right">Average</div>
					</div>
					<div class="row">
						<div class="left">Winrate:</div>
						<div class="right"><span class="label" id="winrate_average"></span></div>
					</div>
					<div class="row">
						<div class="left">Efficiency:</div>
						<div class="right"><span class="label" id="efficiency_total"></span></div>
						<div class="right"><span class="label" id="efficiency_average"></span></div>
					</div>
					<div class="row">
						<div class="left">WN7:</div>
						<div class="right"><span class="label" id="wn7_total"></span></div>
						<div class="right"><span class="label" id="wn7_average"></span></div>
					</div>
					<div class="row">
						<div class="left">Score:</div>
						<div class="right"><span class="label" id="score"></span></div>
						<div class="right"><span class="label" id="score_average"></span></div>
					</div>
				</div>
			</div>
		</div>		
	</div>
	<button type="button" class="btn btn-inverse" id="show_description" data-toggle="button">Description</button>
	<div id="description" style="display:none"><?=$clan->getDescription();?></div>
	<div class="row-fluid">
		<div class="span6">
			<label for="link-label">Link to this page: </label>
			<input class="uneditable-input span5" id="link-label" name="link-label" value="<?=URL_BASE?>clan.php?wid=<?=$clan->getWid()?>">
		</div>
		<div class="span6" id="wot-link-span">
			<a class="btn btn-primary btn-block btn-large" id="wot-link" href="http://<?=getHost($region)?>/uc/clans/<?=$clan->getWid()?>">Go to World of Tanks clan page</a>
		</div>
	</div>
	<div class="row-fluid">
		<div class="progress progress-striped span12 active">
			<div id="queue_bar" style="width: 0;" class="bar bar-warning"></div>
			<div id="load_bar" style="width: 0;" class="bar "></div>
		</div>
	</div>
</div>	
<div id="history_stats_charts"><div id="history_stats_chart_elem"></div></div>
<div class="row">
	<div class="span12">
		<div id="alert_msg" class="alert alert-info">
		    Loading clan info...
		</div>
	</div>
</div>	
<ul class="nav nav-tabs" id="clan-tabs">
	<li class="span3 active"><a data-toggle="tab" href="#vehicle-list">Player list</a></li>
	<li class="span3"><a data-toggle="tab" href="#clan-charts">Vehicle info</a></li>
	<li class="span3"><a data-toggle="tab" href="#stats-info">Statistics</a></li>
	<li class="span3"><a data-toggle="tab" href="#clan-info">Provinces and battles</a></li>
	<li class="span3"><a data-toggle="tab" href="#clan-members">Member changes</a></li>
</ul>	
<div class="tabbable">
	<div class="tab-content">
	<table id='vehicle-list' class="tab-pane active table table-bordered table-striped tablesorter">
		<thead>
			<tr>
				<th class="no">#</th>
				<th class="name">Player</th>
				<th class="stats">
					Stats:<br>
					<span class="sort-eff label label-info">EFF</span>
					<span class="sort-wn7 label">WN7</span>
					<span class="sort-wr label" href="#">WR</span>
					<span class="sort-dmg label" href="#">DMG</span>
					<span class="sort-gpl label" href="#">BTL</span> 
					<span class="sort-scr label" href="#">SCR</span>
				</th>
				<th class="hevth tankth">Heavy</th>
				<th class="medth tankth">Medium</th>
				<th class="artyth tankth">Arty</th>					
				<th class="tdth tankth">TD</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	
	<div id="clan-charts" class="tab-pane">
		<?require "./clan/charts.php";?>
	</div>
	
	<?$isclan=true;require "./shared/stats.php";?>
	
	<div id="clan-info" class="tab-pane">
		<? require "./clan/clan_info.php"; ?>
	</div>
	
	<div id="clan-members" class="tab-pane">
		<h3><img class="detail-loading" src="img/detail-loader.gif"/>Member changes</h3>
		
		<table id="member_changes_table" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Player name</th>
					<th>Change</th>
					<th>Date</th>
					<th>More info</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	</div>
</div>
	
	<script>
		var WID = <?=$clan->getWid()?>,
			WOT_BASE = 'http://clans.<?=getHost($clan->getRegion())?>/',
			CONTOUR_URL = 'http://<?=getHost($clan->getRegion())?>/static/3.6.0.3/encyclopedia/tankopedia/vehicle/contour/'; 
			STATS_SORT = 'eff',
			INFO_LOADED = false,
			URL_BASE = '<?=URL_BASE?>';

		$.tablesorter.addParser({ 
	        // set a unique id 
	        id: 'stats', 
	        is: function(s) { 
	            return false; 
	        }, 
	        format: function(s) {
				switch(STATS_SORT){
		        case 'wr':
		        	i = s.indexOf('Win Ratio:');
		        	return parseFloat(s.slice(i+10,i+15));
			        break;
		        case 'eff':
		        	i = s.indexOf('Efficiency:');
		        	return parseFloat(s.slice(i+11,i+19));
		        case 'wn7':
		        	i = s.indexOf('WN7:');
		        	return parseFloat(s.slice(i+4,i+12));
		        case 'dmg':
		        	i = s.indexOf('Damage:');
		        	return parseFloat(s.slice(i+7,i+14));
				case 'gpl':
		        	i = s.indexOf('Battles:');
		        	return parseFloat(s.slice(i+8,i+13));
				case 'scr':
		        	i = s.indexOf('Score:');
		        	return parseFloat(s.slice(i+6,i+14)); 
			    } 
	        }, 
	        type: 'numeric' 
	    });
			
		$(document).ready(function(){
			$("#vehicle-list").tablesorter({ 
	            headers: { 
	            	0: { 
	                    sorter:false 
	                },
	                2: { 
	                    sorter:'stats' 
	                },
	            } 
	        }); 
			$("#vehicle-list").bind("sortEnd",function() { 
				var i = 1;
		        $('#vehicle-list tbody tr').each(function(){
					$(this).find('.no').html(i);
					i++;
				});
		    });
		    function sortClick(elem){
		    	$('#vehicle-list th.stats .label').removeClass('label-info');
				$(elem).addClass('label-info');
				$("#vehicle-list").trigger('update');
			}
		    $('.sort-wr').click(function(){
				if(!$(this).hasClass('label-info')){
					STATS_SORT = 'wr';
					sortClick(this);
				}		
			});
			$('.sort-wn7').click(function(){
				if(!$(this).hasClass('label-info')){
					STATS_SORT = 'wn7';
					sortClick(this);
				}		
			});
		    $('.sort-eff').click(function(){
				if(!$(this).hasClass('label-info')){
					STATS_SORT = 'eff';
					sortClick(this);
				}		
			});
		    $('.sort-dmg').click(function(){
				if(!$(this).hasClass('label-info')){
					STATS_SORT = 'dmg';	
					sortClick(this);
				}		
			});
		    $('.sort-gpl').click(function(){
				if(!$(this).hasClass('label-info')){
					STATS_SORT = 'gpl';	
					sortClick(this);
				}		
			});
		    $('.sort-scr').click(function(){
				if(!$(this).hasClass('label-info')){
					STATS_SORT = 'scr';	
					sortClick(this);
				}		
			});
		    $('a[href="#clan-info"]').on('show',function(){
				if(!INFO_LOADED){
					$.ajax({
						url: URL_BASE+'map_info.php',
						data: {wid:WID},
						dataType: 'json',
						success: function(data){
							if(data.provinces)
							for(var i in data.provinces.request_data.items){
								var time = new Date(data.provinces.request_data.items[i].prime_time*1000);
								row = '<tr><td><a href="'+WOT_BASE+'/clanwars/maps/?province='+data.provinces.request_data.items[i].id+'" target="_blank">'
								+data.provinces.request_data.items[i].name+'</a></td>';
								row += '<td>'+data.provinces.request_data.items[i].arena_name+'</td>';
								row += '<td>'+data.provinces.request_data.items[i].revenue+'</td>';
								row += '<td>'+time.getHours()+':'+("0"+time.getMinutes()).slice(-2)+'</td>';
								row += '<td>'+data.provinces.request_data.items[i].occupancy_time+'</td></tr>';
								$('#owned_provinces tbody').append(row);
							}
							if(data.battles && data.battles.request_data)
							for(var i in data.battles.request_data.items){
								var time = new Date(data.battles.request_data.items[i].time*1000);	
								row = '<tr><td><a href="'+WOT_BASE+'/clanwars/maps/?province='+data.battles.request_data.items[i].provinces[0].id+'" target="_blank">'
									+data.battles.request_data.items[i].provinces[0].name+'</a></td>';
								row += '<td>'+data.battles.request_data.items[i].arenas[0]+'</td>';
								if(data.battles.request_data.items[i].time > 0)row += '<td>'+time.getHours()+':'+("0"+time.getMinutes()).slice(-2)+'</td></tr>';
								else row += '<td>-</td></tr';
								$('#battle_schedule tbody').append(row);
							}
							$('#clan-info .detail-loading').hide();
							INFO_LOADED = true;
						},
						error: function(jqXHR, textStatus, errorThrown) {
							console.log('AJAX Error: '+URL_BASE+'map_info.php');
						}
					});
				}
			});
			$('#show_description').click(function(){
				if(!$('#show_description').is('.active')){
					$('#description').slideDown();
				}else{
					$('#description').slideUp();
				}
			});
		});
	</script>
	<script type="text/javascript" charset="utf-8" src="js/<?=$jsVersion?>/experiment/experiment.js"></script>
	<script>
		$(function(){
			$.get('http://clanapi.wotcs.com/queue/client',{},function(data){
				if(data.accept_new){
          			var worker = new ClanWorker('ws://clanapi.wotcs.com', <?= $region ?>);					
				}else{
					console.log('Too many client workers');
				}				
			});
      	});
	</script>
<?
$content = ob_get_contents();
ob_end_clean();

require 'layout.php';