<?php
function formatTime($time){
	$ctime = time();
	if($ctime-$time < 60){
		$dif = ($ctime-$time);
		if($dif == 1)$text = ' second ago'; else $text = ' seconds ago';
		return $dif.$text;
	}
	elseif($ctime-$time < 3600){
		$dif = round(($ctime-$time)/60);
		if($dif == 1)$text = ' minute ago'; else $text = ' minutes ago';
		return $dif.$text;
	}
	elseif($ctime-$time < 3600*24){
		$dif = round(($ctime-$time)/3600);
		if($dif == 1)$text = ' hour ago'; else $text = ' hours ago';
		return $dif.$text;
	}
	else {
		$dif = round(($ctime-$time)/3600/24);
		if($dif == 1)$text = ' day ago'; else $text = ' days ago';
		return $dif.$text;
	}
}

function formatNumber($num){
	if($num > 10000000)return floor($num/1000000)."M";
	if($num > 10000)return floor($num/1000)."k";
	else return round($num);
}

function getHost($r){
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

function getMediaHost($r){
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

function getAPIKey($r){
	switch($r){
		case 0:
			return '171745d21f7f98fd8878771da1000a31';
			break;
		case 1:
			return 'd0a293dc77667c9328783d489c8cef73';
			break;
		case 2:
			return '16924c431c705523aae25b6f638c54dd';
			break;
		case 3:
			return '39b4939f5f2460b3285bfa708e4b252c';
			break;
		case 4:
			return '?';
			break;
		case 5:
			return 'ffea0f1c3c5f770db09357d94fe6abfb';
			break;
	}
}

function iptocountry($ip) {
	if(strpos($ip,':')!==FALSE)return 'unknown';   
    $numbers = preg_split( "/\./", $ip);   
    include("./ip_files/".$numbers[0].".php");
    $code=($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);   
	
    foreach($ranges as $key => $value){
        if($key<=$code){
            if($ranges[$key][0]>=$code){$country=$ranges[$key][1];break;}
            }
    }
    if ($country==""){$country="unkown";}
    return $country;
}
?>