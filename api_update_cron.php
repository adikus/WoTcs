<?php

require 'config/main.php';
$db = require 'config/database.php';

$serverStats = new ServerStats($db,0);
$serverStats->updateScoreData();
$serverStats->updateData();