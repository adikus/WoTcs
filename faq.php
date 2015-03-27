<?php

require 'config/main.php';
$db = require 'config/database.php';

$region = isset($_COOKIE['region'])?$_COOKIE['region']:1;
ob_start();
?>

<div class="hero-unit">
	<h1>FAQ</h1>
	
	<ol class="numbered">
		<li>
			<a href="#question1">What are score of a clan and score of a player and how is it obtained?</a>
		</li>
		<li>
			<a href="#question2">What is rating color scheme based on?</a>
		</li>
		<li>
			<a href="#question3">What is an efficiency and WN7/WN8 rating of a player?</a>
		</li>
		<li>
			<a href="#question5">Other questions?</a>
		</li>
	</ol>
</div>

<h2>
	<span id="question1">1. What are score of a clan and score of a player and how is it obtained?</span>
</h2>
<p>
	Score of a clan is sum of scores of all its members. It should represent a 'power' of a clan in terms of number of top tier vehicles.
	Score of a player is calculated as follows:
	<br><img src="img/formula.png" alt="Score formula"></br>
	&#8721;: denotes summation over all player's tanks.</br>
	S<sub>i</sub>: is base score: 1000 for tier 10 heavys and mediums, 900 for tier 10 TDs and tier 8 artys. 0 for all other vehicles.</br>
	W<sub>i</sub>: winrate percentage on current tank.</br>
	B<sub>i</sub>: battles played on current tank.</br>
	WN7: player's WN7 rating. </br>
</p>
<div class="separator"></div>
<h2>
	<span id="question2">2. What is rating color scheme based on?</span>
</h2>
<p>
	<span class="label label-c1">Bottom 10% of players.</span>
	<span class="label label-c2">10% - 45%</span>
	<span class="label label-c3">45% - 80%</span>
	<span class="label label-c4">80% - 95%</span>
	<span class="label label-c5">95% - 99%</span>
	<span class="label label-c6">Top percentage (1%).</span>
</p>
<div class="separator"></div>
<h2>
	<span id="question3">3. What is an efficiency and WN7/WN8 rating of a player?</span>
</h2>
<p>
	Efficiency rating is popular rating formula obtained same as <a href="http://wot-news.com/stat/calc/en/ru">here</a>.
	WN7 is another popular rating system. You can find out more <a href="http://www.koreanrandom.com/forum/topic/2575-wn6-wn7-efficiency-formula-%D1%80%D0%B5%D0%B9%D1%82%D0%B8%D0%BD%D0%B3-wn6wn7-%D0%B0%D0%BB%D1%8C%D1%82%D0%B5%D1%80%D0%BD%D0%B0%D1%82%D0%B8%D0%B2%D0%BD%D0%B0%D1%8F-%D1%84%D0%BE%D1%80%D0%BC%D1%83%D0%BB%D0%B0-%D1%8D%D1%84%D1%84%D0%B5%D0%BA%D1%82/">here</a>.
	WN8 is new and better version of WN7.
</p>
<div class="separator"></div>
<h2>
	<span id="question5">4. Other questions?</span>
</h2>
<p>
	If you have any other questions mail me at <a href="mailto:andrej.hoos@gmail.com">andrej.hoos@gmail.com</a> or post in <a href="http://forum.worldoftanks.eu/index.php?/topic/42993-wot-clan-vehicle-statistics/">this</a> topic.
</p>
<?
$content = ob_get_contents();
ob_end_clean();
$title = 'FAQ';
$active = 3;

require 'layout.php';