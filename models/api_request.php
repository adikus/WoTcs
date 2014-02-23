<?php
class APIRequest{
	
	public static function request($server,$path,$options = array()) {
		$optionsArray = array();	
		foreach ($options as $key => $value) {
			$optionsArray[] = $key."=".$value;
		}
		$optionSting = implode('/',$optionsArray);
		if($c = @file_get_contents('http://wotcsapi'.$server.'.herokuapp.com/'.$path."/".$optionSting)){
			return json_decode($c,true);
		}
		else return false;	
	}
	
}