<?php
function mapVehiclesToJSON($typeVehs){
	if(isset($typeVehs['tanks'])){
		$ret['tanks'] = array_map('mapVehiclesToJSONSub',$typeVehs['tanks']);
		$ret['tier'] = $typeVehs['tier'];
		$ret['tier_roman'] = $typeVehs['tier_roman'];
	}else{
		$ret = array('tier' => 0,'tier_roman' => '');
	}
	if(isset($typeVehs['scouts']))$ret['scouts'] = array_map('mapVehiclesToJSONSub',$typeVehs['scouts']);
	return $ret;
}

function mapVehiclesToJSONSub($vehicle){
	return array(
			'id'	=>	$vehicle->getVehicle()->getId(),
			'tier'	=>	$vehicle->getTierRoman(),
			'tier_num'	=>	$vehicle->getTier(),
			'type'	=>	$vehicle->getType(),
			'nation'=>	$vehicle->getNation(),
			'name'=>	$vehicle->getName(),
			'name_g'=>	$vehicle->getNationText()."-".$vehicle->getNameG(),
			'battles'=>	$vehicle->getBattles(),
			'victories'=>$vehicle->getWins(),
	);
}