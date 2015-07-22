<?php
class WotRequest{
	private $region;
	
	public function __construct($region){
		$this->region = $region;
	}
	
	public static function getHost($r){
		switch($r){
			case 0:
				return 'worldoftanks.ru';
				break;
			case 1:
				return 'worldoftanks.eu';
				break;
			case 2:
				return 'worldoftanks.com';
				break;
			case 3:
				return 'worldoftanks.asia';
				break;
			case 4:
				return 'portal-wot.go.vn';
				break;
			case 5:
				return 'worldoftanks.kr';
				break;
		}
	}

	public static function getClanHost($r){
		switch($r){
			case 0:
				return 'ru.wargaming.net';
				break;
			case 1:
				return 'eu.wargaming.net';
				break;
			case 2:
				return 'na.wargaming.net';
				break;
			case 3:
				return 'asia.wargaming.net';
				break;
			case 4:
				return 'portal-wot.go.vn';
				break;
			case 5:
				return 'kr.wargaming.net';
				break;
		}
	}
	
	public function searchRequest($t,$req,$l = null,$o = 0,$r = -1) {
		$l = isset($l)?$l:10;
		$req = urlencode ($req);
		if($t == "clans"){
			return $this->JSONRequest("/clans/search/list/autocomplete?search=".$req."&offset=".$o."&limit=".$l, true);
		}else{
			return $this->JSONRequest("/community/accounts/search/?name=".$req);
		}		
	}
	
	public function clanDataRequest($req,$id = null) {
		return $this->JSONRequest("/community/clans/".$id."/".$req."/?type=table");
	}
	
	public function JSONRequest($request, $clan_host = false){
		$opts = array(
		  'http'=>array(
		    'method'=>"GET",
		    'header'=>"X-Requested-With: XMLHttpRequest\r\n"
		  )
		);
		
		$context = stream_context_create($opts);
		
		$host = $clan_host ? $this->getClanHost($this->region) : $this->getHost($this->region);
		$result = file_get_contents("http://".$host.$request, false, $context);
		
		$data = (json_decode($result, true));
		$new = &$data;
		return $new;
	}
	
	public static function playerRequest($id,$r){
		$opts = array('http' =>	array('timeout' => 5));
		$context  = stream_context_create($opts);
		$api = $r == 4?'7':'9';
		if($c = @file_get_contents('http://'.self::getHost($r).'/community/accounts/'.$id.'/api/1.'.$api.'/?source_token=WG-WoT_Assistant-1.3.2', false, $context)){
			return json_decode($c,true);
		}
		else return false;	
	}
	
	public static function clanRequest($id,$r){
		$opts = array('http' =>	array('timeout' => 5));
		$context  = stream_context_create($opts);
		if($c = @file_get_contents('http://'.self::getHost($r).'/community/clans/'.$id.'/api/1.1/?source_token=WG-WoT_Assistant-1.3.2', false, $context)){
			return json_decode($c,true);
		}
		else return false;
	}
	
	public function multiget($urls, &$result) {
		$curl = new CURL();
		$opts = array( CURLOPT_RETURNTRANSFER => true );
		foreach($urls as $key => $link){
			$curl->addSession( $link, $key, $opts );
		}
		$result = $curl->exec();
		$curl->clear();
	}
}