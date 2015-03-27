<?php
header("Content-Type: text/plain");

require './config/main.php';
$db = require './config/database.php';

if(isset($_POST['wid'])){
	$clan = Clan::findFirst($db,array('wid' => $_POST['wid']));
	if(isset($_POST['remove'])){
		$clan->delete();
		return;
	}
	$clan->setName($_POST['name']);
	$clan->setTag($_POST['tag']);
	$clan->setMotto($_POST['motto']);
	$clan->setDescription($_POST['description']);
	$clan->setStat('WR',$_POST['WR']);
	$clan->setStat('SC3',$_POST['SC3']);
	$clan->setStat('EFR',$_POST['EFR']);
	$clan->setStat('WN7',$_POST['WN7']);
	$clan->setStat('WN8',$_POST['WN8']);
	$clan->setUpdatedAt(time());
	$clan->saveToDB();
}