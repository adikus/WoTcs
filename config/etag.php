<?php

$rand = rand(0, 10);
if($rand < 2)$adChoice = 0;
elseif($rand < 7)$adChoice = 1;
else $adChoice = 2;

if(LOCAL)return false;

$layoutVersion = '1.4.2'.$jsVersion.$cssVersion;

if(isset($content)){
	if(!isset($bigContent)){
		$stats = new ServerStats($db,$region);
		$etag = md5($content.$layoutVersion.$stats->get("p1h").$adChoice);
	}else{
		$etag = md5($content.$layoutVersion.$adChoice);
	}
	header("Cache-Control: max-age=0, private, must-revalidate");
	header_remove("Pragma");
	$tag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : '';
	$tag_parts = explode('-',$tag);
	$tag = $tag_parts[0];
	$tag = str_replace('"','',$tag);
	$tag = str_replace('\\','',$tag);
	$iftag = $tag == $etag;
	if($iftag){
		header('HTTP/1.0 304 Not Modified');
		exit();
	}else
		header('ETag: "'.$etag.'"');
}