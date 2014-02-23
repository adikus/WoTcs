<?php
require_once 'config/main.php';
require_once 'config/etag.php';
?>
 <!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="content-language" content="en" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />
		<meta name="author" content="Andrej Hoos" />
		<meta name="robots" content="index,follow" />
		
		<title>WoT cs | <?=$title?></title>
		<link href="img/favicon.ico" type="image/x-icon" rel="icon" />
		<link href="img/favicon.ico" type="image/x-icon" rel="shortcut icon" />	
		
		<meta name="description" content="World of Tanks Clan Statistics is simple tool which will show you battle potential of your enemy.
		It shows all you need, including Efficiency, WN7 and custom made Score.
		Apart from clan info, it has many useful features, such as player and vehicle general statistics." />
		<meta name="keywords" content="clan, statistics, adikus, wot, worldoftanks, cs" />
		
		<link rel="stylesheet" type="text/css" href="css/<?=$cssVersion?>/reset.css"/>
		<link rel="stylesheet" type="text/css" href="css/<?=$cssVersion?>/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="css/<?=$cssVersion?>/bootstrap-responsive.min.css"/>
		<link rel="stylesheet" type="text/css" href="css/<?=$cssVersion?>/main.css"/> 
		
		<script type="text/javascript" charset="utf-8" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	    <script type="text/javascript" charset="utf-8" src="js/<?=$jsVersion?>/bootstrap.min.js"></script>
		<script type="text/javascript">
		    var _gaq = _gaq || [];
		    _gaq.push(['_setAccount', 'UA-20440671-4']);
		    _gaq.push(['_trackPageview']);
		
		    (function() {
		      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		    })();	
	    </script>
	    
	    <?=isset($scripts)?$scripts:''?>
		
	</head>
	<body>
		<!-- BuySellAds Ad Code -->
		<?if(ON_WOTCS){?>
		<script type="text/javascript">
		(function(){
		  var bsa = document.createElement('script');
		     bsa.type = 'text/javascript';
		     bsa.async = true;
		     bsa.src = 'http://s3.buysellads.com/ac/bsa.js';
		  (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);
		})();
		</script>
		<?}?>
		<!-- End BuySellAds Ad Code -->
	    <div id="wrap">
		  <div class="navbar navbar navbar-fixed-top">
		      <div class="navbar-inner">
		        <div class="container">
		          <a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		          </a>
		          <a href="<?=URL_BASE?>" class="brand"><img src="<?=URL_BASE?>img/logo-small.png"></a>
		          <div class="nav-collapse collapse">
		            <ul class="nav">
		              <li class="<?=$active==0?'active':''?>"><a href="<?=URL_BASE?>">Home</a></li>
		              <li class="<?=$active==1?'active':''?>"><a href="<?=URL_BASE?>clans.php">Clans</a></li>
		              <li class="<?=$active==2?'active':''?>"><a href="<?=URL_BASE?>statsv.php">Statistics</a></li>
		              <li class="<?=$active==3?'active':''?>"><a href="<?=URL_BASE?>faq.php">FAQ &amp; contact</a></li>
		              <li class="<?=$active==4?'active':''?>"><a href="http://blog.wotcs.com">Blog</a></li>
		            </ul>
		            <div class="btn-group pull-right" id="search-region">
    					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
							<?
							if(!isset($region))$region = 99;
							switch($region){
								case 1:echo "EU";break;
								case 2:echo "NA";break;
								case 0:echo "RU";break;
								case 3:echo "SEA";break;
								case 4:echo "VN";break;
								case 5:echo "KR";break;
								case 99:echo "&nbsp;";break;
							}?>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<?if($region!=1){?><li><a href="<?=URL_BASE?>clans.php?region=1">EU</a></li><?}?>
							<?if($region!=2){?><li><a href="<?=URL_BASE?>clans.php?region=2">NA</a></li><?}?>
							<?if($region!=0){?><li><a href="<?=URL_BASE?>clans.php?region=0">RU</a></li><?}?>
							<?if($region!=3){?><li><a href="<?=URL_BASE?>clans.php?region=3">SEA</a></li><?}?>
							<?if($region!=5){?><li><a href="<?=URL_BASE?>clans.php?region=5">KR</a></li><?}?>
						</ul>
    				</div>
		            <form action="search.php" method="get" class="navbar-search pull-right">
		              <input type="hidden" name="region" value="<?=$region?>">
			          <input type="text" name="search" placeholder="Search" class="search-query span2" value="<?=isset($_GET['search'])?$_GET['search']:''?>">
			        </form>
		          </div><!--/.nav-collapse -->
		        </div>
		      </div>
		    </div>
	      <div class="container">
	      	<?if(isset($messageFromAdmin)){?>
	      		<div class="alert alert-info"><?=$messageFromAdmin?><a class="close" data-dismiss="alert" href="#">&times;</a></div>
	      	<?}if(MAINTENANCE && $a){?>
	      		<div class="alert alert-warning">You are viewing this site as admin during maintenance.<a class="close" data-dismiss="alert" href="#">&times;</a></div>
	      	<?}if(isset($_SESSION["emsg"])){?>
	      		<div class="alert alert-warning"><?=$_SESSION["emsg"]?><a class="close" data-dismiss="alert" href="#">&times;</a></div>
	      	<?}$_SESSION["emsg"]=null;?>
	      	
      		<div class="content<?=isset($bigContent)?' big':''?>">
      			<?=$content?>
      			<?if(!isset($bigContent)){?>
					<div class="sidebar">
						<div class="ad-300-250 main">
							<!-- BuySellAds Zone Code -->
							<div id="bsap_1289970" class="bsarocks bsap_29ec8931db05ecf5d8b2ae91858a5977"></div>
							<!-- End BuySellAds Zone Code -->
						</div>
						<?require 'extra.php';?>
					</div>
				<?}?>
      		</div>

		  </div>
	
	      <div id="push"></div>
	    </div>
	    <div id="footer">
			<div class="container">
			WoT clan statistics by <a href="http://forum.worldoftanks.eu/index.php?/user/367012-adikus/">adikus</a> | 2012
			</div>    
	    </div>
	</body>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".alert").alert();
			
			if(navigator.userAgent.toLowerCase().indexOf('chrome') > -1){
				$(".ad.wowp").mousedown(function(){
					window.open('http://mmotraffic.com/catalog/goplay/1000246/MTA5NTMvLy8xMDAwMjQ2/', '_blank');
				});
			}else{
				$(".ad.wowp").click(function(){
					window.open('http://mmotraffic.com/catalog/goplay/1000246/MTA5NTMvLy8xMDAwMjQ2/', '_blank');
				});
			}
			
			
		});
	</script>
</html>