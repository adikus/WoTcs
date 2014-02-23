<?php

require './config/main.php';
$db = require './config/database.php';

$region = isset($_GET['region'])?$_GET['region']:(isset($_COOKIE['region'])?$_COOKIE['region']:1);
setcookie('region',$region,time()+3600*24*100,'/');

$clanJSON = json_decode(file_get_contents("./top-".$region.".json"),true);

$active = 1;
$bigContent = true;
$title = 'Top 100 clans';

ob_start();
?>
	<script src="js/<?=$jsVersion?>/utils.php"></script>	
	<script src="js/<?=$jsVersion?>/jquery.tablesorter.min.js"></script>
<?
$scripts = ob_get_contents();
ob_end_clean();

ob_start();
?>

<h1>Top 100 clans
	<div class="btn-group">
		<a class="btn<?=$region==1?" disabled":""?>" href="<?=URL_BASE?>top.php?region=1">EU</a>
		<a class="btn<?=$region==2?" disabled":""?>" href="<?=URL_BASE?>top.php?region=2">NA</a>
		<a class="btn<?=$region==0?" disabled":""?>" href="<?=URL_BASE?>top.php?region=0">RU</a>
		<a class="btn<?=$region==3?" disabled":""?>" href="<?=URL_BASE?>top.php?region=3">SEA</a>
		<a class="btn<?=$region==5?" disabled":""?>" href="<?=URL_BASE?>top.php?region=5">KR</a>
	</div>
</h1>
Export as: <a href="<?=URL_BASE?>top-<?=$region?>.csv">CSV</a>
<table id='vehicle-list' class="tab-pane active table table-bordered table-striped tablesorter">
	<thead>
		<tr>
			<th class="no">#</th>
			<th class="name">Clan<br>
				<span class="sort-eff label">EFF</span>
				<span class="sort-wn7 label">WN7</span>
				<span class="sort-wr label" href="#">WR</span><br>
				<span class="sort-dmg label" href="#">DMG</span>
				<span class="sort-gpl label" href="#">BTL</span> 
				<span class="sort-scr label label-info" href="#">SCR</span>
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
	
<script>
	var WOT_BASE = 'http://<?=getHost($region)?>/',
		CONTOUR_URL = 'http://<?=getHost($region)?>/static/3.6.0.3/encyclopedia/tankopedia/vehicle/contour/'; 
		STATS_SORT = 'eff',
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
    
    $("#vehicle-list").tablesorter({ 
        headers: { 
        	0: { 
                sorter:false 
            },
            1: { 
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
    	$('#vehicle-list th.name .label').removeClass('label-info');
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
		
	clans = <?=json_encode($clanJSON["clans"])?>;
	
	var i = 0;
	for(var j in clans){
		if(clans[j].name && clans[j].stats){
			renderRow(clans[j]);
			i++;
		}
	}
	
	function renderRow(clan) {
		row = '<tr><td class="no">'+(parseInt(i)+1)+'</td>';
		row += '<td class="name">'+renderName(clan)+renderStats(clan.stats)+'</td>';
		row += '<td class="hev">'+renderTanks(clan.vehs[2])+'</td>';
		row += '<td class="med">'+renderTanks(clan.vehs[1])+'</td>';
		row += '<td class="td big">'+renderTanks(clan.vehs[4])+'</td>';
		row += '<td class="arty big">'+renderTanks(clan.vehs[3])+'</td></tr>';
		$('#vehicle-list tbody').append(row);
		$("#vehicle-list").trigger('update');
		
	}
	
	function renderName(clan) {
		ret = '<a href="'+URL_BASE+'clan.php?wid='+clan.wid+'" title="'+clan.name.replace(/["']/g, "&quot;")+'">'+clan.tag+'</a>';
		return ret;
	}
	
	function renderStats(stats) {
		eff = Math.round(stats['EFR']/stats['member_count']*100)/100;
		ret = '<div class="row"><div class="name-eff">Efficiency:</div><div class="value eff"><span class="label label-c'+labelClass(eff,"CEFRA")+'">'+eff+'</span></div></div>';
		wn7 = Math.round(stats['WN7']/stats['member_count']*100)/100;
		ret += '<div class="row"><div class="name-wn7">WN7:</div><div class="value wn7"><span class="label label-c'+labelClass(wn7,"CWN7A")+'">'+wn7+'</span></div></div>';
		wr = Math.round(stats['WIN']/stats['GPL']*10000)/100;
		ret += '<div class="row"><div class="name-wr">Win Ratio:</div><div class="value wr"><span class="label label-c'+labelClass(wr,"CWIN")+'">'+wr+'%</span></div></div>';
		dmg = Math.round(stats['DMG']/stats['GPL']*100)/100;
		ret += '<div class="row"><div class="name-dmg">Damage:</div><div class="value dmg"><span class="label label-c'+labelClass(dmg,"CDMG")+'">'+dmg+'</span></div></div>';
		scr = stats['SC3'];
		ret += '<div class="row"><div class="name-scr">Score:</div><div class="value eff"><span class="label label-c'+labelClass(scr,"CSC3")+'">'+formatNumber(scr)+'</span></div></div>';
		gpl = stats['GPL'];
		ret += '<div class="row"><div class="name-scr">Battles:</div><div class="value gpl"><span class="label label-c'+labelClass(gpl,"CGPL")+'">'+formatNumber(gpl)+'</span></div></div>';
		
		return ret;
	}
	
	function renderTanks(vehs) {
		var sortValue = 0,
			count = 0;
		ret = '';
		if(!vehs)return ret;
		
		for(var i in vehs){
			var imgsrc = getNationName(vehs[i].nation)+"-"+vehs[i].name.toLowerCase(),
				WR = roundNumber(vehs[i].wins/vehs[i].battles*100,1),
				WRClass = labelClass(WR,vehs[i].name+"-W"),
				winRatio = ' <div class="label label-c'+(WRClass?WRClass:labelClass(WR,"WIN"))+'">('+vehs[i].count+';'+WR+'%)</div>';
			ret += '<div class="tank-section"><img src="'+CONTOUR_URL+imgsrc+'.png" alt="'+vehs[i].lname+'" title="'+vehs[i].lname+'">'+winRatio+'</div>';
			count += vehs[i].count;
			sortValue += vehs[i].count*WR/100;
		}
		sortValue = parseInt(sortValue);
		ret = '<div class="sort-value">'+("00000"+sortValue).slice(-6)+'</div><div class="tierb">'+count+'</div>'+ret;
		return ret;
	}
</script>
	
<?
$content = ob_get_contents();
ob_end_clean();

require 'layout.php';