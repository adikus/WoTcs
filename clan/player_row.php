<?php
if(isset($player)){
	$vehicles = $player->getVehicles();
	$name = $player->getName();
	$wid = $player->getWid();
	$EFR = $player->getEFR();
	$WR = $player->getWR();
	$DMG = $player->getDMGAv();
	$GPL = $player->getGPL();
	$SCR = $player->getSCR();
	$EFRclass = $player->EFRclass();
	$WRclass = $player->WRclass();
	$DMGclass = $player->DMGclass();
	$GPLclass = $player->GPLclass();
	$SCRclass = $player->SCRclass();
	$updated = date('H:i d.m.Y',$player->getUpdatedAt());
}else{
	$vehicles = array();
	$name = 'template';
	$wid = '';
	$EFR = 0;
	$WR = 0;
	$DMG = 0;
	$GPL = 0;
	$SCR = 0;
	$EFRclass = '';
	$WRclass = '';
	$DMGclass = '';
	$GPLclass = '';
	$SCRclass = '';
	$updated = 'Never';
}

?>
<tr id="player-<?=$wid?$wid:$name?>"<?=!$wid?'class="template"':''?>>
	<td class="no"><?=($i+1)?></td>
	<td class="name">
		<a href="http://<?=getHost($region)?>/community/accounts/<?=$wid?>"><?=$name?></a><br>
		Last updated<br>
		<span class="date"><?=$updated?></span>
	</td>
	<td class="stats">
		<div class="row">
			<div class="name-eff">Efficiency:</div>
			<div class="value eff"><span class="label label-eff<?=$EFRclass?>"><?=$EFR?></span></div>
		</div>
		<div class="row">
			<div class="name-wr">Winrate:</div>
			<div class="value wr"><span class="label label-wr<?=$WRclass?>"><?=$WR?></span></div>
		</div>
		<div class="row">
			<div class="name-dmg">Damage:</div>
			<div class="value dmg"><span class="label label-dmg<?=$DMGclass?>"><?=$DMG?></span></div>
		</div>
		<div class="row">
			<div class="name-gpl">Battles:</div>
			<div class="value gpl"><span class="label label-gpl<?=$GPLclass?>"><?=$GPL?></span></div>
		</div>
		<div class="row">
			<div class="name-scr">Score:</div>
			<div class="value scr"><span class="label label-scr<?=$SCRclass?>"><?=$SCR?></span></div>
		</div>
	</td>
	<td class='hev type2'><?$type=2;require('./clan/type_cell.php');?></td>
	<td class='med type1'><?$type=1;require('./clan/type_cell.php');?></td>
	<td class='lgt type0'><?$type=0;require('./clan/type_cell.php');?></td>
	<td class='arty type4'><?$type=4;require('./clan/type_cell.php');?></td>
	<td class='td type3'><?$type=3;require('./clan/type_cell.php');?></td>
</tr>