<?php
require_once 'config/main.php';

$stats = new ServerStats($db,$region);
?>

<div class="well">
	<ul class="nav nav-list">
		<li class="nav-header">Best clans</li>
		<?$i = 0; foreach ($stats->getClans() as $clan) { ?>
			<li>
				<a class="item" href="<?=URL_BASE?>clan.php?wid=<?=$clan["wid"]?>">
					<?=++$i?>.
					<b><?=$clan["tag"]?></b>
					<i><?=formatNumber($clan["SC3"])?></i>
				</a>
			</li>
		<?if($i == 10)break;}?>
	</ul>
</div>

<div class="well server-stats">
	<ul class="nav nav-list">
		<li class="nav-header">Server statistics</li>
		<li>Clans updated &lt;1 hour:<span class="server-stat-value"><?=$stats->get("c1h")?></span></li>
		<li># Clans tracked:<span class="server-stat-value"><?=$stats->get("ct")?></span></li>
		<li>Players updated &lt;1 hour:<span class="server-stat-value"><?=$stats->get("p1h")?></span></li>
		<li>Players updated &lt;12 hour:<span class="server-stat-value"><?=$stats->getTotalTo(12)?></span></li>
		<li># Players in DB:<span class="server-stat-value"><?=$stats->get("pt")?></span></li>
	</ul>
</div>

<div class="well server-stats">
	<ul class="nav nav-list">
		<li class="nav-header">Donate</li>
		<li>
			<form class="form-donate" action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick"/>
				<input type="hidden" name="hosted_button_id" value="JKC6ESFMCQT8C"/>
				<p class="sub-title">
					As you can see above I have lots of data in database which requires some processing power.
					This of course costs something. If you like this page, please consider donating. <br>
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!"/>
				</p>
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"/>
			</form>
		</li>
	</ul>
</div>