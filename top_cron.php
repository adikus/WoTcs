<?php

$region = isset($_GET['r']) ? $_GET['r'] : 1;

require 'config/main.php';

set_time_limit(0);

$data = array();
for($i = $region;$i < $region+1;$i++){
	if($i != 4){
		$data[$i] = APIRequest::request('players','clans/top',array("region"=>$i));
		echo "Loaded ".$i.".<br>";	
	}
}
$csvdata = array();
for($i = $region;$i < $region+1;$i++){
	if($i != 4){
		$csvdata[$i] = "";
		$pos = array("1"=>array(),"2"=>array(),"3"=>array(),"4"=>array());
		foreach ($data[$i]["clans"] as $clan)if(isset($clan["vehs"])) {
			foreach ($clan["vehs"] as $type => $vehs) {
				foreach ($vehs as $veh) {
					if(!in_array($veh["name"],$pos[$type]))$pos[$type][] = $veh["name"];
				}
			}
		}
		$row = "/";
		for($j = 1;$j < 5;$j++){
			foreach($pos[$j] as $tank){
				$row .=  ";".$tank;
			}
		}
		$row .= "\n";
		$csvdata[$i] .= $row;
		$maxpos = count($pos[1])+count($pos[2])+count($pos[3])+count($pos[4])+1;
		foreach ($data[$i]["clans"] as $clan)if(isset($clan["vehs"]))  {
			$row = array($clan["tag"]);
			foreach ($clan["vehs"] as $type => $vehs) {
				foreach ($vehs as $veh) {
					$vehPos = 1;
					for($j = 1;$j < $type;$j++)$vehPos += count($pos[$j]);
					$vehPos += array_search($veh["name"],$pos[$type]);
					$row[$vehPos] = $veh["count"];
				}
			}
			for($j = 1;$j < $maxpos;$j++)
			if(!isset($row[$j]))$row[$j] = 0;
			ksort ($row);
			$csvdata[$i] .= implode(';',$row)."\n";
		}
		//echo $csvdata[$i];
	}
}

for($i = $region;$i < $region+1;$i++)if($i != 4){
	$fname = "./top-".$i.".json";
	if (!$fp=@fopen($fname,"w")) {
		exit("Could not open ".$fname);
	}
	if (!@fwrite($fp,json_encode($data[$i]))) {
		fclose($fp);
		exit("Could not write to ".$fname);
	}
	fclose($fp);
	$fname = "./top-".$i.".csv";
	if (!$fp=@fopen($fname,"w")) {
		exit("Could not open ".$fname);
	}
	if (!@fwrite($fp,$csvdata[$i])) {
		fclose($fp);
		exit("Could not write to ".$fname);
	}
	fclose($fp);
	echo "Writen ".$i.".<br>";
}
echo "Done.";
