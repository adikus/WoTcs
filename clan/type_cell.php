<div class="tank-section template">
	<img title="template" alt="template" src="#">
	<div class="label label-wr">(0;0%)</div>
</div>
<?php
$vehs = array_filter($vehicles,function($vehicle){global $type;return $vehicle->getType() == $type;});
if(!empty($vehs)){
	if($type>0){
		$i = -1;
		foreach($vehs as $k => $v){
			$i = $k;
			break;
		}
		$tier = $i >= 0?$vehs[$i]->getTierRoman():false;
	}
	else{
		$i = -1;
		foreach($vehs as $k => $v){
			if($v->getTier() != 5){
				$i = $k;
				break;
			}
		}
		$tier = $i >= 0?$vehs[$i]->getTierRoman():false;
	}
	echo '<div class="tierb">'.$tier.'</div>';
}
$count = 0;
foreach($vehs as $vehicle){
	if($vehicle->getType() == $type && !($type == 0 && $vehicle->getTier() == 5)){
		$count ++;
		?>
		<div class="tank-section">
			<img title="<?=$vehicle->getName()?>" alt="<?=$vehicle->getName()?>" src="http://<?=getHost($region)?>/static/3.6.0.3/encyclopedia/tankopedia/vehicle/contour/<?=$vehicle->getNationtext()?>-<?=strtolower($vehicle->getNameG())?>.png">
			<div class="label label-wr<?=$vehicle->WRclass()?>">(<?=$vehicle->getBattles()?>;<?=$vehicle->getWinsPercentage()?>%)</div>
		</div>
		<?
	}
}
if($type == 0){
	if(isset($tier) && $tier != 'V' && $count < count($vehs))echo '<div class="tierb">V</div>';
	foreach($vehs as $vehicle){
		if($vehicle->getType() == $type && $vehicle->getTier() == 5){
			?>
			<div class="tank-section">
				<img title="<?=$vehicle->getName()?>" alt="<?=$vehicle->getName()?>" src="http://<?=getHost($region)?>/static/3.6.0.3/encyclopedia/tankopedia/vehicle/contour/<?=$vehicle->getNationtext()?>-<?=strtolower($vehicle->getNameG())?>.png">
				<div class="label label-wr<?=$vehicle->WRclass()?>">(<?=$vehicle->getBattles()?>;<?=$vehicle->getWinsPercentage()?>%)</div>
			</div>
			<?
		}
	}
}