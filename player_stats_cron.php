<?php

require 'config/main.php';
$db = require 'config/database.php';

$playerStats = new PlayerStats($db);
$playerStats->loadData();
