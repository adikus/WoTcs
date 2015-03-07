<?php

class PlayerStats {
	
	private $data = array("veh" => array(), "player" => array(),"clan" => array());
	
	public function __construct($db){
		$this->db = $db;
	}
	
	public function loadData(){
		$this->requestData();
		$this->calcData();
		$this->saveData("player");
		$this->saveData("veh");
		$this->saveData("clan");
		$this->calcBorders();
	}
	
	public function requestData() {
		$this->rdata = APIRequest::request('players','stats/players');
		$this->r2data = APIRequest::request('players','stats/vehs');
		$this->r3data = APIRequest::request('players','stats/clans');
	}
	
	public function calcData() {
		foreach($this->rdata["counts"] as $key => $count){
			$this->calcStatData($key,$count,$this->rdata["stats"][$key]);
		}
		foreach($this->r2data["counts"] as $key => $count){
			$this->calcStatData($key."-S",$count,$this->r2data["stats"][$key]["S"],"veh");
			$this->calcStatData($key."-W",$count,$this->r2data["stats"][$key]["W"],"veh");
			$this->calcStatData($key."-B",$count,$this->r2data["stats"][$key]["B"],"veh");
		}
		foreach($this->r3data["counts"] as $key => $count){
			$this->calcStatData("C".$key,$count,$this->r3data["stats"][$key],"clan");
		}
	}
	
	public function calcStatData($t, $count, $data, $type = "player") {
		$stat = array("data" => $data, "count" => $count, "average" => 0, "stdev" => 0, "percentiles" => array());
		$total = 0;
		foreach ($stat["data"] as $value => $vcount) {
			if(!is_numeric ($value)){
				$count -= $vcount;
				unset($stat["data"][$value]);
			}elseif(($t == "SC3" && $value == 0) || ($t == "WN8" && $value > 100000) || $value > 100000000){
				$count -= $vcount;
				unset($stat["data"][$value]);
			}else $total += $value*$vcount;
		}
		$stat["average"] = $total/$count;
		ksort($stat["data"],SORT_NUMERIC);
		$tdev = 0;
		foreach ($stat["data"] as $value => $vcount) {
			$tdev += pow($value - $stat["average"],2)*$vcount;
		}
		$stat["stdev"] = sqrt($tdev/$count);
		$tcount = 0;
		foreach ($stat["data"] as $value => $vcount) {
			$tcount += $vcount;
			$stat["percentiles"][$value] = $tcount/$count*100;
		}
		$this->data[$type][$t] = $stat;
	}
	
	public function saveData($type = "player") {
		$this->db->query("TRUNCATE TABLE `".$type."_stats` ");
		$this->db->query("TRUNCATE TABLE `".$type."_stats_data` ");
		$insertRows = array();
		foreach($this->data[$type] as $t => $stat){
			foreach($stat["data"] as $value => $count ){
				$insertRows[] = "('".$t."',".$value.",".$count.",".$stat["percentiles"][$value].")";
			}
		}
		$this->db->query("INSERT INTO `".$type."_stats_data` (`key`, `value`, `count`, `percentile`) VALUES ".implode(',', $insertRows)." ON DUPLICATE KEY UPDATE `key`=VALUES(`key`),`value`=VALUES(`value`),`count`=VALUES(`count`),`percentile`=VALUES(`percentile`);");
		$insertRows = array();
		foreach($this->data[$type] as $t => $stat){
			$insertRows[] = "('".$t."',".$stat["average"].",".$stat["stdev"].")";
		}
		$this->db->query("INSERT INTO `".$type."_stats` (`key`, `average`, `stdev`) VALUES ".implode(',', $insertRows)." ON DUPLICATE KEY UPDATE `key`=VALUES(`key`),`average`=VALUES(`average`),`stdev`=VALUES(`stdev`);");
	}
	
	public function chartData($type = "player") {
		$ret = array();	
		$sdata = $this->db->query("SELECT * FROM `".$type."_stats`");
		foreach ($sdata as $i => $stat) {
			if(!isset($ret[$stat["key"]]))$ret[$stat["key"]] = array(
				"data" => array(array($stat["key"],"Count")),
				"average" => $stat["average"],
				"stdev" => $stat["stdev"]
			);
		}
		$data = $this->db->query("SELECT `key`, `value`, `count` FROM `".$type."_stats_data`");
		foreach ($data as $i => $stat) {
			if($stat["value"] < $ret[$stat["key"]]["average"] + 3*$ret[$stat["key"]]["stdev"] && $stat["value"] > $ret[$stat["key"]]["average"] - 3*$ret[$stat["key"]]["stdev"])
				$ret[$stat["key"]]["data"][] = array($stat["value"],$stat["count"]);
		}
		return json_encode($ret);
	}
	
	public function test($key,$r){
		echo $key."<br>";
		$sdata = $this->db->query("SELECT * FROM `player_stats` WHERE `key` = '".$key."'");
		$average = $sdata[0]["average"];
		$stdev = $sdata[0]["stdev"];
		for($i=-3;$i<=3;$i++){
			$val = $average+$i*$stdev;
			$data = $this->db->query("SELECT * FROM `player_stats_data` WHERE `key` = '".$key."' AND `value` > ".($val-$r)." AND value <= ".($val+$r));
			$p1 = isset($data[0])?$data[0]["percentile"]:0;
			$p2 = isset($data[1])?$data[1]["percentile"]:0;
			echo $i.": ".$val.": ".$p1." - ".$p2;
			echo "<br>";
		}
	}
	
	public function getBorder($key,$b,$t = "player"){
		$data = $this->db->query("SELECT * FROM `".$t."_stats_data` WHERE `key` = '".$key."' AND `percentile` >= ".$b." ORDER BY `percentile` ASC LIMIT 1");
		if(!isset($data[0]))$ub = array(100,100);
		else $ub = array($data[0]["value"],$data[0]["percentile"]);
		$data = $this->db->query("SELECT * FROM `".$t."_stats_data` WHERE `key` = '".$key."' AND `percentile` < ".$b." ORDER BY `percentile` DESC LIMIT 1");
		if(!isset($data[0]))$lb = array(0,0);
		else $lb = array($data[0]["value"],$data[0]["percentile"]);
		$g = ($ub[0]-$lb[0])/($ub[1]-$lb[1]);
		return round($lb[0] + ($b - $lb[1])*$g,2);
	}
	
	public function getBorders($key,$fromDB = false,$t = "player"){
		if($fromDB){
			return array(
				$this->getBorder($key,10,$t),
				$this->getBorder($key,45,$t),
				$this->getBorder($key,80,$t),
				$this->getBorder($key,95,$t),
				$this->getBorder($key,99,$t)
			);
		}else{	
			if(!isset($this->fdata)){
				$this->fdata = json_decode(file_get_contents("./borders.json"),true);
			}
			return array(
				$this->fdata[$key][0],
				$this->fdata[$key][1],
				$this->fdata[$key][2],
				$this->fdata[$key][3],
				$this->fdata[$key][4],
			);	
		}
	}
	
	public function getAllBorders($fromDB = false){
		$ret = array();
		$keys = $this->db->query("SELECT `key` FROM `player_stats`"); 
		foreach($keys as $key)
			$ret[$key["key"]] = $this->getBorders($key["key"],$fromDB);
		$keys = $this->db->query("SELECT `key` FROM `veh_stats`"); 
		foreach($keys as $key)
			$ret[$key["key"]] = $this->getBorders($key["key"],$fromDB,"veh");
		$keys = $this->db->query("SELECT `key` FROM `clan_stats`"); 
		foreach($keys as $key)
			$ret[$key["key"]] = $this->getBorders($key["key"],$fromDB,"clan");
		return $ret;
	}
	
	public function calcBorders(){
		$fdata = json_encode($this->getAllBorders(true));
		$fname = "./borders.json";
		if (!$fp=@fopen($fname,"w")) {
			exit("Could not open ".$fname);
		}
		if (!@fwrite($fp,$fdata)) {
			fclose($fp);
			exit("Could not write to ".$fname);
		}
		fclose($fp);
	}
	
	public function bordersRow($key,$s = '') {
		$data = $this->getBorders($key);
		$ret = '<span class="label label-c1 statsp">&rArr; '.$data[0].$s.'</span>';
		$ret .= '<span class="label label-c2 statsp">'.$data[0].$s.' &rArr; '.$data[1].$s.'</span>';
		$ret .= '<span class="label label-c3 statsp">'.$data[1].$s.' &rArr; '.$data[2].$s.'</span>';
		$ret .= '<span class="label label-c4 statsp">'.$data[2].$s.' &rArr; '.$data[3].$s.'</span>';
		$ret .= '<span class="label label-c5 statsp">'.$data[3].$s.' &rArr; '.$data[4].$s.'</span>';
		$ret .= '<span class="label label-c6 statsp">'.$data[4].$s.' &rArr;</span>';
		return $ret;
	}
	
	public function getPercentile($key,$value,$type){
		$type = substr($type, 0, -1);
		$data = $this->db->query("SELECT * FROM `".$type."_stats_data` WHERE `key` = '".$key."' AND `value` >= ".$value." ORDER BY `value` ASC LIMIT 1");
		$up = array($data[0]["value"],$data[0]["percentile"]);
		$data = $this->db->query("SELECT * FROM `".$type."_stats_data` WHERE `key` = '".$key."' AND `value` < ".$value." ORDER BY `value` DESC LIMIT 1");
		$lp = isset($data[0])?array($data[0]["value"],$data[0]["percentile"]):array($data[0]["value"],$data[0]["percentile"]);
		$g = $up[0]!=$lp[0]?($up[1]-$lp[1])/($up[0]-$lp[0]):0;
		return round($lp[1] + ($value - $lp[0])*$g,2);
	}
	
	public function getPercentiles($stats){
		$ret = array();
		$type = $stats["type"];
		unset($stats["type"]);
		foreach ($stats as $key => $value) {
			$ret[$key] = $this->getPercentile($key,$value,$type);
		}
		return $ret;
	}
	
}