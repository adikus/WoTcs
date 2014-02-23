<?php

require 'config/main.php';
$db = require 'config/database.php';

$playerStats = new PlayerStats($db);

echo json_encode($playerStats->getPercentiles($_POST));
