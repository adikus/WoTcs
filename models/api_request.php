<?php
class APIRequest{
	
	public static function request($server,$path,$options = array()) {
		$optionsArray = array();	
		foreach ($options as $key => $value) {
			$optionsArray[] = $key."=".$value;
		}

		if($server == 'players'){
			$optionSting = "?" . implode('&',$optionsArray);
		}else{
			$optionSting = implode('&',$optionsArray);
		}

		$url = 'http://wotcsapi'.$server.'.herokuapp.com/';
		if($path){
			$url = $url.$path."/";
		}
		$url = $url.$optionSting;
		
		if($c = @file_get_contents($url)){
			$decoded = json_decode($c,true);

			if($server == 'players'){
				return $decoded['data'];
			}else{
				return $decoded;
			}
		}
		else return false;	
	}
	
}