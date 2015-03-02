<?php

foreach (glob("functions/*.php") as $filename){
	include $filename;
}
foreach (glob("models/*.php") as $filename){
	include $filename;
}

const DEBUG = false;
const UPDATE_INTERVAL = 43200;//12 hours
define('CACHE_DIR',dirname(__FILE__)."/../cache/");

if ( '127.0.0.1' == $_SERVER['REMOTE_ADDR'] || '::1' == $_SERVER['REMOTE_ADDR']){
	define( 'URL_BASE',	'http://localhost/WoTcs/' );
	define('LOCAL',true);
}else {
	define( 'URL_BASE',	'http://'.$_SERVER['SERVER_NAME'].'/' );
	define('LOCAL',false);
}
define('ON_WOTCS', $_SERVER['SERVER_NAME'] == 'wotcs.com');

if(!LOCAL){ob_start("ob_gzhandler");}

session_start();

$cssVersion = "1.5";
$jsVersion = "1.6.1";

const MAINTENANCE = false;

$a = isset($_GET["a"]) ? $_GET["a"] == "a" : false;
if(!$a)$a = isset($_COOKIE["a"]) ? $_COOKIE["a"] == "a" : false;

if(MAINTENANCE && !$a){
	$active = 0;
	$bigContent = true;
	$title = 'Maintenance';
	$content = '<div class="hero-unit"><h1>Under maintenance</h1>Site will be back up soon.</div>';
	require 'layout.php';
	exit();
}

//$messageFromAdmin = 'Hi, there seems to be problem on the WGs end. Keep in mind that you can experience bugs or glitches now. If something doen\'t work, let me know (see bottom of the FAQ page).';
