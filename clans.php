<?php

require 'config/main.php';
$db = require 'config/database.php';

$region = isset($_GET['region'])?$_GET['region']:(isset($_COOKIE['region'])?$_COOKIE['region']:1);
setcookie('region',$region,time()+3600*24*100,'/');
$sort = isset($_GET['order'])?$_GET['order']:'az';
$p = new Paginator( 30, isset($_GET['p'])?$_GET['p']-1:0);
$where = '';

switch($sort){
	case 'sc3':
		$order = 'SC3 DESC';
		$where = ' AND updated_at > '.(time() - 60*60*24*7);	break;
	case 'wn7':
		$order = 'WN7 DESC';
		$where = ' AND updated_at > '.(time() - 60*60*24*7);	break;
	case 'wn8':
		$order = 'WN8 DESC';
		$where = ' AND updated_at > '.(time() - 60*60*24*7);	break;
	default:
		$order = 'tag ASC';	break;
}
$clans = Clan::sql($db,"SELECT * FROM `clans` WHERE `tag` <> '' AND `region` = ".$region.$where." ORDER BY ".$order.$p->getLimit());
$p->setCount($db->getCount());

$c = new ColumnManager($clans,3);

ob_start();
?>

<h2>
	List of clans
	<div class="btn-group">
		<a class="btn<?=$region==1?" disabled":""?>" href="<?=URL_BASE?>clans.php?region=1">EU</a>
		<a class="btn<?=$region==2?" disabled":""?>" href="<?=URL_BASE?>clans.php?region=2">NA</a>
		<a class="btn<?=$region==0?" disabled":""?>" href="<?=URL_BASE?>clans.php?region=0">RU</a>
		<a class="btn<?=$region==3?" disabled":""?>" href="<?=URL_BASE?>clans.php?region=3">SEA</a>
		<a class="btn<?=$region==5?" disabled":""?>" href="<?=URL_BASE?>clans.php?region=5">KR</a>
    </div>
</h2>

<p>
	Here you can see list of all clans which are in this projects database. You can sort them alphabeticaly, by score or average WN7/WN8 rating. If you have any questions feel free to look at the <a href="<?=URL_BASE?>faq.php">FAQ</a> page.
</p>

<div class="ad-728-90 search">
	<!-- BuySellAds Zone Code -->
	<div id="bsap_1290118" class="bsarocks bsap_29ec8931db05ecf5d8b2ae91858a5977"></div>
	<!-- End BuySellAds Zone Code -->
</div>

<a href="<?=URL_BASE?>top.php">Detailed list of Top 100 clans.</a>

<ul class="nav nav-tabs" id="clan-order-tabs">
	<li class="span3<?=$sort == 'az'?" active":""?>"><a href="<?=URL_BASE?>clans.php?order=az">A-Z</a></li>
    <li class="span3<?=$sort == 'sc3'?" active":""?>"><a href="<?=URL_BASE?>clans.php?order=sc3">Score</a></li>
    <li class="span3<?=$sort == 'wn7'?" active":""?>"><a href="<?=URL_BASE?>clans.php?order=wn7">WN7</a></li>
    <!--li class="span3<?=$sort == 'wn8'?" active":""?>"><a href="<?=URL_BASE?>clans.php?order=wn8">WN8</a></li-->
</ul>

<div class="row-fluid">
<?
$j = 1;
foreach($c->getColumns() as $column){?>
	<div class="span4">
		<?
		foreach($column as $clan){
			?>
			<div class="search-result small">
				<a href="<?=URL_BASE?>clan.php?wid=<?=$clan->getWid()?>">
					<?if($sort == 'sc3' || $sort == 'wn7' || $sort == 'wn8'){echo $p->getRealOffset()+$j.".";}?>
					<b><?=$clan->getTag()?></b>
					<span class="info<?=$sort == 'sc3' || $sort == 'wn7' || $sort == 'wn8'?" score":""?>">
						<?if($sort == 'sc3'){
							echo formatNumber($clan->getStat("SC3"));
						}elseif($sort == 'wn7'){
							echo formatNumber($clan->getStat("WN7"));
						}elseif($sort == 'wn8'){
							echo formatNumber($clan->getStat("WN8"));
						}elseif($sort == 'az' && $clan->getUpdatedAt() > 0){
							echo formatTime($clan->getUpdatedAt());
						}?>
					</span>
				</a>
			</div>
			<?	$j++;	
		}
		?>
	</div>
<?}?>
</div>

<div class="pagination pagination-centered">
    <ul>
	    <li<?=$p->isPrev()?' class="disabled"':''?>><a href="<?=URL_BASE?>clans.php?order=<?=$sort?>&p=<?=$p->prev()?>">Prev</a></li>
	    <?foreach($p->paginateList() as $o){?>
	    	<li <?=$p->current($o)?'class="active"':''?>><a href="<?=URL_BASE?>clans.php?order=<?=$sort?>&p=<?=$o?>"><?=$o?></a></li>
	    <?}?>
	    <li<?=$p->isNext()?' class="disabled"':''?>><a href="<?=URL_BASE?>clans.php?order=<?=$sort?>&p=<?=$p->next()?>">Next</a></li>
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
$active = 1;

require 'layout.php';
