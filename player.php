<?php

require './config/main.php';
$db = require './config/database.php';

$region = isset($_COOKIE['region'])?$_COOKIE['region']:1;

$wid = isset($_GET['wid'])?$_GET['wid']:null;

$active = -1;
$bigContent = true;
$title = 'Loading...';

ob_start();
?>
	<script src="js/<?=$jsVersion?>/lib/class.js"></script>
	<script src="js/<?=$jsVersion?>/lib/underscore-min.js"></script>
	<script src="js/<?=$jsVersion?>/utils.php"></script>
	<script data-main="js/<?=$jsVersion?>/require.js/player/main" src="js/<?=$jsVersion?>/lib/require.js"></script>
<?
$scripts = ob_get_contents();
ob_end_clean();

ob_start();
?>
<div class="hero-unit player">
	<div class="row">
		<div class="ad-300-250">
			<!-- BuySellAds Zone Code -->
			<div id="bsap_1289970" class="bsarocks bsap_29ec8931db05ecf5d8b2ae91858a5977"></div>
			<!-- End BuySellAds Zone Code -->
		</div>
		<div class="ad-fill-rest">
			<h1><img class="clan-emblem" src="img/clan-loader.gif"/><span class="tag"></span><span class="name"></span></h1>
			<div class="row-fluid">
				<div class="span6">
					<div class="row"><div class="left">Battles:</div><div class="right"><span class="label" id="battles"></span></div></div>
					<div class="row"><div class="left">Frags:</div><div class="right"><span class="label" id="frags"></span></div></div>
					<div class="row"><div class="left">Damage:</div><div class="right"><span class="label" id="damage"></span></div></div>
					<div class="row"><span class="left" id="last_updated"></span></div>
					<div class="row"><span class="left" id="last_logout"></span></div>
				</div>
				<div class="span6">
					<div class="row"><div class="left">Winrate:</div><div class="right"><span class="label" id="winrate"></span></div></div>
					<div class="row"><div class="left">Efficiency:</div><div class="right"><span class="label" id="efficiency"></span></div></div>
					<div class="row"><div class="left">WN7:</div><div class="right"><span class="label" id="wn7"></span></div></div>
					<div class="row"><div class="left">WN8:</div><div class="right"><span class="label" id="wn8"></span></div></div>
					<div class="row"><div class="left">Score:</div><div class="right"><span class="label" id="score"></span></div></div>
				</div>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span6">
			<label for="link-label">Link to this page: </label>
			<input class="uneditable-input span5" id="link-label" name="link-label" value="<?=URL_BASE?>player.php?wid=<?=$wid?>">
		</div>
		<div class="span6">
			<a class="btn btn-primary btn-large btn-block" id="wot-link" href="http://<?=getHost($region)?>/uc/accounts/<?=$wid?>">Go to World of Tanks clan page</a>
		</div>
	</div>
</div>	
<div id="alert_msg" class="alert alert-info">
    Loading player info...
</div>
<ul class="nav nav-tabs" id="player-tabs">
	<li class="span4 active"><a data-toggle="tab" href="#player-tanks">Tanks</a></li>
	<li class="span4"><a data-toggle="tab" href="#stats-info">Statistics</a></li>
	<li class="span4"><a data-toggle="tab" href="#player-history">Player history</a></li>
</ul>	
<div class="tabbable">
	<div class="tab-content">
		<div id="player-tanks" class="tab-pane active">
			<div class="row veh-row" id="2-veh-row">
				<div class="veh-row-title">
					<h3>Heavy</h3>
					<div class="row"><div class="left">&nbsp;</div></div>
					<div class="row"><div class="left">Battles:</div><div class="right"><span class="label label-c0" id="2-battles"></span></div></div>
					<div class="row"><div class="left">Winrate:</div><div class="right"><span class="label" id="2-winrate"></span></div></div>
					<div class="row"><div class="left">Score:</div><div class="right"><span class="label" id="2-score"></span></div></div>
				</div>
			</div>
			<div class="row veh-row" id="1-veh-row">
				<div class="veh-row-title">
					<h3>Medium</h3>
					<div class="row"><div class="left">&nbsp;</div></div>
					<div class="row"><div class="left">Battles:</div><div class="right"><span class="label label-c0" id="1-battles"></span></div></div>
					<div class="row"><div class="left">Winrate:</div><div class="right"><span class="label" id="1-winrate"></span></div></div>
					<div class="row"><div class="left">Score:</div><div class="right"><span class="label" id="1-score"></span></div></div>
				</div>
			</div>
			<div class="row veh-row" id="4-veh-row">
				<div class="veh-row-title">
					<h3>Arty</h3>
					<div class="row"><div class="left">&nbsp;</div></div>
					<div class="row"><div class="left">Battles:</div><div class="right"><span class="label label-c0" id="4-battles"></span></div></div>
					<div class="row"><div class="left">Winrate:</div><div class="right"><span class="label" id="4-winrate"></span></div></div>
					<div class="row"><div class="left">Score:</div><div class="right"><span class="label" id="4-score"></span></div></div>
				</div>
			</div>
			<div class="row veh-row" id="3-veh-row">
				<div class="veh-row-title">
					<h3>TDs</h3>
					<div class="row"><div class="left">&nbsp;</div></div>
					<div class="row"><div class="left">Battles:</div><div class="right"><span class="label label-c0" id="3-battles"></span></div></div>
					<div class="row"><div class="left">Winrate:</div><div class="right"><span class="label" id="3-winrate"></span></div></div>
					<div class="row"><div class="left">Score:</div><div class="right"><span class="label" id="3-score"></span></div></div>
				</div>
			</div>
		</div>
		
		<?require "./shared/stats.php";?>
		
		<div id="player-history" class="tab-pane">
		<h3>Player history:<img class="detail-loading" src="img/detail-loader.gif"/></h3>
		
		<table id="player_changes_table" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Clan</th>
					<th>Change</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	</div>
</div>
	
	<script>
		var WID = <?=$wid?>,
			WOT_BASE = 'http://clans.<?=getHost($region)?>/',
			URL_BASE = '<?=URL_BASE?>';		
	</script>
	
<?
$content = ob_get_contents();
ob_end_clean();

require 'layout.php';