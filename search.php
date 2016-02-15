<?php

require 'config/main.php';
$db = require 'config/database.php';

$region = isset($_GET['region'])?$_GET['region']:1;
setcookie('region',$region,time()+3600*24*100,'/');
$max = 12;
$p = new Paginator( $max, isset($_GET['p'])?$_GET['p']-1:0);
$t = isset($_GET['t'])?$_GET['t']:'clans';

$wotReq = new WotRequest($region);
$data = $wotReq->searchRequest($t,$_GET['search'],$max,$p->getRealOffset(),$region);

$data['items'] = $data['search_autocomplete_result'];
$results = array();

if($t == 'clans' && count($data['items']) > 0){
	foreach ($data["items"] as $clan){
		$results[] = array(
			'clantag' => $clan['tag'],
			'wid' => $clan['id'],
			'name' => $clan['name']
		);
	}
}
if($t == 'clans' && count($data['items']) == 0){
	$data2 = $wotReq->searchRequest('accounts',$_GET['search'],$max,$p->getRealOffset(),$region);
	if(count($data2["response"]) > 0){
		$data = $data2;
		$t = "accounts";		
	}
}
if($t == 'accounts'){
	foreach ($data["search_autocomplete_result"] as $player){
		$results[] = array(
			'clantag' => $player['clan']['tag'],
			'wid' => $player['id'],
			'name' => $player['name']
		);
	}
}

$p->setCount(count($results));

$c = new ColumnManager($results,3);

ob_start();
?>

<form action="search.php" method="get" class="form-search">
	<legend>Search</legend>
	<p>
	Just type down the clan you are searching for.
	</p>
	<input class="span5" type="text" autofocus placeholder="Search" name="search" value="<?=$_GET['search']?>"/>
	<select class="span1" name="region">
		<option value="1" <?=$region == 1?"selected='selected'":""?>>EU</option>
		<option value="2" <?=$region == 2?"selected='selected'":""?>>NA</option>
		<option value="0" <?=$region == 0?"selected='selected'":""?>>RU</option>
		<option value="3" <?=$region == 3?"selected='selected'":""?>>SEA</option>
		<option value="5" <?=$region == 5?"selected='selected'":""?>>KR</option>
	</select>
	<select class="span2" name="t">
		<option value="clans" <?=$t == "clans"?"selected='selected'":""?>>Clans</option>
		<option value="accounts" <?=$t == "accounts"?"selected='selected'":""?>>Players</option>
	</select>
	<button class="btn" type="submit">Search</button>
</form>

<div class="ad-728-90 search">
	<!-- BuySellAds Zone Code -->
	<div id="bsap_1290118" class="bsarocks bsap_29ec8931db05ecf5d8b2ae91858a5977"></div>
	<!-- End BuySellAds Zone Code -->
</div>

<div class="row-fluid">
	<?
	foreach($c->getColumns() as $column){
		?><div class="span4"><?
		foreach($column as $result){
			
			?>
			<div class="search-result">
				<a href="<?=URL_BASE?><?=$t=="clans"?"clan":"player"?>.php?wid=<?=$result['wid']?>">
					<?if($result['clantag']){?><b>[<?=$result['clantag']?>]</b><?}?><?=$result['name']?>
				</a>
			</div>
			<?
		}
	?></div><?
	}
	?>
</div>

<div class="pagination pagination-centered">
    <ul>
	    <li<?=$p->isPrev()?' class="disabled"':''?>><a href="<?=URL_BASE?>search.php?region=<?=$region?>&t=<?=$t?>&search=<?=urlencode($_GET['search'])?>&p=<?=$p->prev()?>">Prev</a></li>
	    <?foreach($p->paginateList() as $o){?>
	    	<li <?=$p->current($o)?'class="active"':''?>><a href="<?=URL_BASE?>search.php?region=<?=$region?>&t=<?=$t?>&search=<?=urlencode($_GET['search'])?>&p=<?=$o?>"><?=$o?></a></li>
	    <?}?>
	    <li<?=$p->isNext()?' class="disabled"':''?>><a href="<?=URL_BASE?>search.php?region=<?=$region?>&t=<?=$t?>&search=<?=urlencode($_GET['search'])?>&p=<?=$p->next()?>">Prev</a></li>
    </ul>
</div>

<script>
$('.pagination .disabled a, .pagination .active a').on('click', function(e) {
    e.preventDefault();
});
</script>

<?
$content = ob_get_contents();
ob_end_clean();
$title = 'List of clans';
$active = 0;

require 'layout.php';