<?php

require './config/main.php';
$db = require './config/database.php';

$clan = Clan::findFirst($db,array('wid' => $_GET['wid']));

$ret = array(
	'provinces'	=>	$clan->getProvinces(),
	'battles'	=>  $clan->getBattles()
);

echo json_encode($ret);