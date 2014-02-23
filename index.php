<?php

require 'config/main.php';
$db = require 'config/database.php';

$region = isset($_GET['region'])?$_GET['region']:(isset($_COOKIE['region'])?$_COOKIE['region']:1);
setcookie('region',$region,time()+3600*24*100,'/');

ob_start();
?>

<div class="hero-unit">
	<h1>World of Tanks Clan Statistics</h1>
	<p>World of Tanks Clan Statistics is simple tool which will show you battle potential of your enemy.
		It shows all you need, including Efficiency, WN7 and custom made Score.
		Apart from clan info, it has many useful features, such as player and vehicle general statistics.</p>
	<form action="search.php" method="get" class="form-search">
		<input class="span4" type="text" autofocus placeholder="Search" name="search"/>
		<select class="span1" name="region">
			<option value="1" <?=$region == 1?"selected='selected'":""?>>EU</option>
			<option value="2" <?=$region == 2?"selected='selected'":""?>>NA</option>
			<option value="0" <?=$region == 0?"selected='selected'":""?>>RU</option>
			<option value="3" <?=$region == 3?"selected='selected'":""?>>SEA</option>
			<option value="5" <?=$region == 5?"selected='selected'":""?>>KR</option>
		</select>
		<select class="span2" name="t">
			<option value="clans" selected='selected'>Clans</option>
			<option value="accounts" >Players</option>
		</select>
		<button class="btn" type="submit">Search</button>
	</form>
</div>

<div class="ad-728-90">
	<!-- BuySellAds Zone Code -->
	<div id="bsap_1290118" class="bsarocks bsap_29ec8931db05ecf5d8b2ae91858a5977"></div>
	<!-- End BuySellAds Zone Code -->
</div>

<? if(ON_WOTCS){ ?>
<div class="wp-listing">
	<div class="row">
		<h1 class="span1"><a href="http://blog.wotcs.com">Blog</a></h1>
		<p class="span8">This project has now its own blog to keep you updated what is new.</p>
	</div>
	<?
	define('WP_USE_THEMES', false); require('./blog/wp-blog-header.php');
	global $post;
	$args = array( 'posts_per_page' => 3 );
	$myposts = get_posts( $args );
	foreach( $myposts as $post ) :	setup_postdata($post); ?>
					
	<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
		
		<header>
		
			<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'wpbs-featured' ); ?></a>
			
			<div class="page-header"><h3 class="h2"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3></div>
			
			<p class="meta">
				<?php _e("Posted", "bonestheme"); ?> <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time> 
				<?php _e("by", "bonestheme"); ?> <?php the_author_posts_link(); ?> <span class="amp">&</span> <?php _e("filed under", "bonestheme"); ?> <?php the_category(', '); ?> .
				<br><a href="<?php the_permalink() ?>#respond" title="Leave a comment">Leave a comment</a>
			</p>
		
		</header> <!-- end article header -->
	
		<section class="post_content clearfix">
			<?php the_excerpt( __("Read more &raquo;","bonestheme") ); ?>
		</section> <!-- end article section -->
		
		<footer>

			<p class="tags"><?php the_tags('<span class="tags-title">' . __("Tags","bonestheme") . ':</span> ', ' ', ''); ?></p>
			
		</footer> <!-- end article footer -->
	
	</article> <!-- end article -->
	
	<?php endforeach; ?>
</div>

<?}
$content = ob_get_contents();
ob_end_clean();
$title = 'Search';
$active = 0;

require 'layout.php';