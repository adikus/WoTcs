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
		
		if($c = @file_get_contents('http://wotcsapi'.$server.'.herokuapp.com/'.$path."/".$optionSting)){
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