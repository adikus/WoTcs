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
	
	public function searchRequest($t,$req,$l = null,$o = 0,$r = -1) {
		$l = isset($l)?$l:10;
		$req = urlencode ($req);
		$t = $t == "clans"?"clans/search":$t;
    $t = $t == "accounts" && ($r != 2 && $r != 5)?"accounts/search":$t;
		return $this->JSONRequest("GET /community/".$t."/?type=table&search=".$req."&limit=".$l."&offset=".$o."&order_by=name HTTP/1.0\r\n");
	}
	
	public function clanDataRequest($req,$id = null) {
		return $this->JSONRequest("GET /community/clans/".$id."/".$req."/?type=table HTTP/1.0\r\n");
	}
	
	public function JSONRequest($request){
		$error = 0;
		$data = array();
		
		$request.= "Accept: text/html, */*\r\n";
		$request.= "User-Agent: Mozilla/3.0 (compatible; easyhttp)\r\n";
		$request.= "X-Requested-With: XMLHttpRequest\r\n";
		$request.= "Host: ".$this->getHost($this->region)."\r\n";
		$request.= "Connection: Keep-Alive\r\n";
		$request.= "\r\n";
		$n = 0;
		while(!isset($fp)){
			$fp = fsockopen(gethostbyname($this->getHost($this->region)), 80, $errno, $errstr, 15);
			if($n == 3){
				break;
			}
			$n++;
		}
		if (!$fp) {
			echo "$errstr ($errno)<br>\n";
		} else {
			$timeOut = $this->region==0?1000000:400000;
			stream_set_timeout($fp,0,$timeOut);
			
			$info = stream_get_meta_data($fp);
				
			fwrite($fp, $request);
				
			$page = '';
			
			while (!feof($fp)) {
			$page .= fgets($fp, 4096);
				$info = stream_get_meta_data($fp);
				if($info['timed_out'])$timeOut += 200000;
				if($info['timed_out'] && $timeOut > 10400000)break;
				if(substr($page, -1) == "}"){
					list($header, $body) = explode("\r\n\r\n", $page, 2);
					if(json_decode($body,TRUE) != null )break;
				}
			}
			fclose($fp);
			if ($info['timed_out'] && substr($page, -1) != "}") {
				$error = 1; //Connection Timed Out
			}
		}
		if($error == 0){
			preg_match_all("/{\"request(.*?)success\"}/", $page, $matches);
			if(!isset($matches[0][0]))preg_match_all("/{\"result(.*?)error\"}/", $page, $matches);
			$data = (json_decode($matches[0][0], true));
		}
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