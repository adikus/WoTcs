<?php

class ServerStats {
	
	private $data = array();
	
	public function __construct($db,$region = -1){
		$this->db = $db;
		$this->region = $region;
		$data = $db->query("SELECT * FROM `api_server`");
		if(empty($data)){
			$this->requestData();
			$this->saveData();
		}else{
			foreach($data as $pair){
				$this->data[$pair["key"]] = $pair["value"];
			}
			if(isset($this->data[$this->region."scr"]))$this->data[$this->region."scr"] = json_decode($this->data[$this->region."scr"],true);
		}
	}
	
	public function updateData() {
		$this->requestData();
		$this->saveData();
		echo "Updated db stats.";
	}
	
	public function requestData() {
		$clanData = APIRequest::request('clans','');
		$this->data["c1m"] = $clanData["updated1m"];
		$this->data["c1h"] = $clanData["updated1h"];
		$this->data["ct"] = $clanData["total"];
		$playerData = APIRequest::request('players','');
		for($i=1;$i<13;$i++){
			$this->data["p".$i."h"] = $playerData["updated"][$i."h"];
		}
		if(isset($playerData["total"]))$this->data["pt"] = $playerData["total"];
	}
	
	public function requestScoreData() {
		$sData = APIRequest::request('clans','scores',array("r"=>$this->region));
		$this->data[$this->region."scr"] = $sData["scores"];
		$this->data["updsc".$this->region] = time();
	}
	
	public function saveData() {
		$insertRows = array();	
		foreach($this->data as $key => $val){
			if(is_array($val))$val = json_encode($val);
			$insertRows[] = "('".$key."','".$val."')";
		}
		$this->db->query("INSERT INTO `api_server` (`key`, `value`) VALUES ".implode(',', $insertRows)." ON DUPLICATE KEY UPDATE `key`=VALUES(`key`),`value`=VALUES(`value`);");
	}
	
	public function updateScoreData(){
		$this->requestScoreData();
		$this->region = 1;	
		$this->requestScoreData();
		$this->region = 2;	
		$this->requestScoreData();
		$this->region = 3;	
		$this->requestScoreData();
		$this->region = 4;	
		$this->requestScoreData();
		$this->region = 5;	
		$this->requestScoreData();
		echo "Updated scores.";
		$this->saveData();
	}
	
	public function getClans() {
		return $this->data[$this->region."scr"];
	}
	
	public function getTotalTo($to) {
		$ret = 0;	
		for($i=1;$i<=$to;$i++){
			$ret += $this->data["p".$i."h"];
		}
		return $ret;
	}
	
	public function get($key) {
		return isset($this->data[$key])?$this->data[$key]:"-";
	}
	
}