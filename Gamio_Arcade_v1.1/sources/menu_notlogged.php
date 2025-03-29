<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
	// Get a current page and display different title for every page
	$a = isset($_GET['a']) ? protect($_GET['a']) : "";
	$title = "";
	$keyword = "";
	$description = "";
	if ($a == "login") {
	    $title = "Login - $settings[title]";
	    $description = "Welcome back to $settings[name]";
	} elseif ($a == "register") {
	    $title = "Register - $settings[title]";
	    $description = "Join $settings[name] today";
	} elseif ($a == "about") {
	    $title = "About - $settings[title]";
	    $description = "Learn about $settings[name]";
	} elseif ($a == "password") {
	    $title = "Change Password - $settings[title]";
	    $description = "Secure your $settings[name] account";
	} elseif ($a == "email_verify") {
	    $title = "Email Verification - $settings[title]";
	    $description = "Verify your email";
	} elseif ($a == "contacts") {
	    $title = "Contact us - $settings[title]";
	    $description = "Get in touch with $settings[name]";
	} elseif ($a == "page") {
	    $prefix = isset($_GET['prefix']) ? protect($_GET['prefix']) : '';
        $query = $db->query("SELECT * FROM pages WHERE prefix='$prefix'");
        if($query->num_rows==0) {
            $title = "Error - $settings[title]";
            $description = "Error - $settings[title]";
        } else {
            $row = $query->fetch_assoc();
            $title = "$row[title] - $settings[title]";
            $description = $row["descrip"];
        }
	} elseif ($a == "blog") {
	    $prefix = isset($_GET['prefix']) ? protect($_GET['prefix']) : '';

        if (!empty($prefix)) {
            $query = $db->query("SELECT * FROM blogs WHERE prefix='$prefix'");
            if ($query->num_rows == 0) {
                $title = "Error - {$settings['title']}";
                $description = "Error - {$settings['title']}";
            } else {
                $row = $query->fetch_assoc();
                $title = "{$row['title']} - {$settings['title']}";
                $description = $row["descrip"];
            }
        } else {
            $title = "Blog - {$settings['title']}";
            $description = "Welcome to {$settings['name']} blog";
        }
        
	} elseif ($a == "play") {
	    $slug = addslashes(protect(htmlspecialchars($_GET["play"])));
        $game_check = $db->query("SELECT * FROM games WHERE Slug='$slug' and status='enable'");
        if($game_check && $game_check->num_rows > 0) {
            $game_data = $game_check->fetch_assoc();
            $title = "$game_data[Title] | $settings[title]";
            $description = $game_data['Description'];
            $keyword = $game_data['Tag'];
            $img = $game_data['Thumbnail'];
        }
	} elseif ($a == "category") {
	    $slug = protect($_GET["category"]);
        $cate_check = $db->query("SELECT * FROM game_categories WHERE slug='$slug'");
        if($cate_check && $cate_check->num_rows > 0) {
            $cat_data = $cate_check->fetch_assoc();
            $title = "$cat_data[name] Games - $settings[title]";
            $description = $cat_data['description'];
            $keyword = $settings['keywords'];
        }
	} elseif ($a == "new") {
	    $title = "New Games - $settings[title]";
	    $description = "Latest games on $settings[name]";
	} elseif ($a == "trending") {
	    $title = "Trending Games - $settings[title]";
	    $description = "$settings[name] trending games";
	} elseif ($a == "top-picks") {
	    $title = "Top-Picks Games - $settings[title]";
	    $description = "Experience the best of HTML5 gaming.";
	} else {
	    $title = $settings['title'];
	    $description = $settings['description'];
	    $keyword = $settings['keywords'];
	}
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
    $url_cano = "https://";   
    else  
    $url_cano = "http://";   
    $url_cano.= $_SERVER['HTTP_HOST'];   
    $url_cano.= $_SERVER['REQUEST_URI'];   
    
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    // Get the server name
    $serverName = $_SERVER['SERVER_NAME'];
    // Get the request URI
    $requestURI = $_SERVER['REQUEST_URI'];
    // Construct the full URL
    $currentURL = $protocol . $serverName . $requestURI;
    ?>
	<title><?=$title;?></title>
	<meta name="description" content="<?= $description; ?>">
  	<meta name="keywords" content="<?= $keyword; ?>">
	<meta charset="UTF-8">
	<meta name="author" content="<?php echo $title; ?>">
	<meta name="publisher" content="<?php echo $title; ?>"/>
    <meta name="application-name" content="<?php echo $title; ?>">
    <link rel="canonical" href="<?=$url_cano?>"/>
    <link rel="alternate" href="<?=$url_cano?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://use.fontawesome.com/32efc5ddb7.js"></script>
	<link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/slick.css">
	<link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/slick-theme.min.css">
	<link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/bootstrap.min.css">
	<?php if ($_SESSION['mode'] && $_SESSION['mode'] == "dark") { ?>
	    <link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/custom_styles.css">
	<?php } else { ?>
	    <link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/custom_styles_light.css">
	<?php } ?>
	<script src="<?= $settings['url'] ?>theme/js/jquery.min.js"></script>
	<script src="<?= $settings['url'] ?>theme/js/slick.min.js"></script>
	<script src="<?= $settings['url'] ?>theme/js/js.cookie.min.js"></script>
	<?php if($settings['favicon']) { ?>
		<link rel="icon" type="image/png" href="<?= $settings['url'].$settings['favicon'] ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="<?= $settings['url'].$settings['favicon'] ?>">
        <link rel="shortcut icon" href="<?= $settings['url'].$settings['favicon'] ?>" />
        <link rel="apple-touch-icon" sizes="32x32" href="<?= $settings['url'].$settings['favicon'] ?>">
	<?php } else { ?>
		<link rel="icon" type="image/png" href="<?= $settings['url'] ?>theme/img/favicon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?= $settings['url'] ?>theme/img/favicon.png">
        <link rel="shortcut icon" href="<?= $settings['url'] ?>theme/img/favicon.png" />
        <link rel="apple-touch-icon" sizes="32x32" href="<?= $settings['url'] ?>theme/img/favicon.png">
	<?php } ?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
	<?php if ($m["google_analytics"] == "1") { ?>
    	<?= $settings['google_analytics_code'] ?>
    <?php } ?>
    <meta name="turbo-visit-control" content="reload">
    <script type="application/ld+json">
	{
	  "@context": "http://schema.org",
	  "@type": "WebSite",
	  "name": "<?=$settings['name'];?>",
	  "url": "<?=$settings['url'];?>"
     }
 	</script>
    <meta name="robots" content="index,follow" />
    <link rel="sitemap" type="application/xml" title="Sitemap" href="<?= $settings['url']; ?>sitemap.xml">
  	<meta name="theme-color" content="#81E6E8">
  	<meta property="og:title" content="<?=$title;?>" />
  	<meta property="og:site_name" content="<?= $settings['name']; ?>" />
  	<?php if (!empty($img)) { ?>
	    <meta property="og:image" content="<?=$img;?>" />
	    <link rel="image_src" href="<?=$img;?>">
	    <meta name="twitter:image" content="<?=$img;?>" />
	    <meta itemprop="image" content="<?=$img;?>"/>
	    <link itemprop="thumbnailUrl" href="<?=$img;?>">
	    <meta name="twitter:image:src" content="<?=$img;?>">
	<?php } ?>
	<meta name="og:description" content="<?= $description; ?>">
  	<meta name="og:keywords" content="<?= $keyword; ?>">
  	<meta property="og:type" content="website" />
  	<meta property="og:locale" content="en_US" />
  	<meta property="og:url" content="<?=$url_cano;?>" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@<?= $settings['name']; ?>" />
    <meta name="twitter:title" content="<?=$title;?>" />
    <meta name="twitter:description" content="<?= $description; ?>" />
  	<meta name="twitter:creator" content="@<?= $settings['name']; ?>">
    <meta name="twitter:data2" content="<?= $settings['name']; ?>" />
  	<meta name="twitter:label2" content="Website" />
  	<link itemprop="url" href="<?=$url_cano;?>">
    <meta itemprop="name" content="<?=$title;?>"/>
	<meta itemprop="description" content="<?= $description; ?>"/>
	<?php if (!empty($settings['header'])) { ?>
	<?php echo htmlspecialchars_decode($settings['header']); ?>
    <?php } ?>
</head>
<body>
<img src="<?= $settings['url'] ?>theme/img/back2.png" class="background1">
<script type="text/javascript">
$(function(){
	$(".barsBtn").click(function(){
		$("body").addClass("ov");
		$(".mobNav").toggleClass("show-menu");
	});
	$(".closeMobNav").click(function(){
		$("body").removeClass("ov");
		$(".mobNav").removeClass("show-menu");
	});
});
</script>

<div class="mobNav">
	<div class="flex ai jcb">
		<div class="flex ai">
			<a href="<?= $settings['url']; ?>">
				<?php if($settings['white_logo'] && $settings['logo']) { ?>
				    <?php if ($_SESSION['mode'] && $_SESSION['mode'] == "light") { ?>
				        <img src="<?= $settings['url'].$settings['logo'] ?>" class="logo-main">
				    <?php } else { ?>
					    <img src="<?= $settings['url'].$settings['white_logo'] ?>" class="logo-main">
					<?php } ?>
				<?php } else { ?>
					<img src="<?= $settings['url']; ?>theme/img/logo-main.png" class="logo-main">
				<?php } ?>
			</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 	<div class="theme_buttom">
		 		<a href="<?= $settings['url'] ?>modes/light/<?=$currentURL;?>"><i class="fa fa-sun-o theme-light-btn themeIconTop<?php if ($_SESSION['mode'] && $_SESSION['mode'] == "light") { ?> activeTheme<?php } ?>" title="Light mode"></i></a>
		 		&nbsp;
		 		<a href="<?= $settings['url'] ?>modes/dark/<?=$currentURL;?>"><i class="fa fa-moon-o theme-dark-btn themeIconTop<?php if ($_SESSION['mode'] && $_SESSION['mode'] == "dark") { ?> activeTheme<?php } ?>" title="Dark mode"></i></a>
		 	</div>
		</div>
		 <i class="fa fa-times closeMobNav"></i>
	</div>
	<div class="seperator rel">
		<h2 class="title rel">Games</h2>
	</div>
	<ul class="mobNavUl">
	    <?php
	    $header = $db->query("SELECT * FROM game_categories ORDER BY id");
        if($header && $header->num_rows > 0) {
          while($head = $header->fetch_assoc()) {
        ?>
		<li><a href="<?= $settings['url'] ?>category/<?=$head['slug']?>" ><?=$head['name']?></a></li>
		<?php } } ?>
		
		
		<!--
		<li class="dropdownLi">
			<span class="main-link us pointer">
				Other games
				&nbsp;
				<i class="fa fa-angle-down"></i>
			</span>
			<ul class="dropdownUl">
				<li>
					<a href="#">
						Shooting
					</a>
				</li>
			</ul>
		</li>
		-->
	</ul>
	<div class="seperator rel">
		<h2 class="title rel">Links</h2>
	</div>
	 <div class="topLinksflex">
	 	<a href="<?= $settings['url'] ?>new" class="headLink">Newest</a>
	 	<a href="<?= $settings['url'] ?>trending" class="headLink">Trending</a>
	 	<a href="<?= $settings['url'] ?>top-picks" class="headLink">Top Picks</a>
	 	<?php if(!checkSession()) { ?>
			<a href="<?= $settings['url']; ?>login" class="headLogBtn logBtn"><i class="fa fa-lock"></i> <?= $lang['menu_login'] ?></a>
			<a href="<?= $settings['url']; ?>register" class="headLogBtn regBtn"><i class="fa fa-user"></i> <?= $lang['menu_register'] ?></a>
		<?php } ?>
		<?php if(checkSession()) { ?>
			<a href="<?= $settings['url']; ?>account/summary" class="headLogBtn logBtn"><i class="fa fa-dashboard"></i> <?= $lang['title_summary'] ?></a>
			<a href="<?= $settings['url']; ?>logout" class="headLogBtn regBtn"><i class="fa fa-sign-out"></i> <?= $lang['log_out'] ?></a>
		<?php } ?>
	 </div>
</div> <!-- mobNav -->
<div class="header">
	
	<div class="container">
		<div class="headerInner">
			<div class="row jcb ai headToprow">
				<div class="col-lg-3 rel ai jcb">
					<a href="<?= $settings['url']; ?>">
						<?php if($settings['white_logo'] && $settings['logo']) { ?>
        				    <?php if ($_SESSION['mode'] && $_SESSION['mode'] == "light") { ?>
        				        <img src="<?= $settings['url'].$settings['logo'] ?>" class="logo-main">
        				    <?php } else { ?>
        					    <img src="<?= $settings['url'].$settings['white_logo'] ?>" class="logo-main">
        					<?php } ?>
        				<?php } else { ?>
        					<img src="<?= $settings['url']; ?>theme/img/logo-main.png" class="logo-main">
        				<?php } ?>
					</a>
					<i class="fa fa-bars barsBtn"></i>
				</div> <!-- col -->
				<div class="col-lg-4">
				    <?php
    					if(isset($_POST["search_done"]) && !empty($_POST["search"])) {
                          	$searching=0;
    						$search = isset($_POST['search']) ? protect($_POST['search']) : "";
                         	header("Location: $settings[url]search/$search");
                        }
    				?>
					<form method="POST" action="">
						<div class="headSearchFlex flex">
							<input type="text" name="search" class="searchTop" placeholder="Search games">
							<button type="submit" name="search_done" class="searchBtnTop"><i class="fa fa-search"></i></button>
						</div>
					</form>
				</div> <!-- col -->
				<div class="col-lg-5 hide992">
					 <div class="topLinksflex flex jce ai">
					 	<div class="theme_buttom">
					 	    <a rel="nofollow" href="<?= $settings['url']; ?>modes/light/<?=$currentURL;?>"><i class="fa fa-sun-o theme-light-btn themeIconTop<?php if ($_SESSION['mode'] && $_SESSION['mode'] == "light") { ?> activeTheme<?php } ?>" title="Light mode"></i></a>
            		 		&nbsp;
            		 		<a rel="nofollow" href="<?= $settings['url']; ?>modes/dark/<?=$currentURL;?>"><i class="fa fa-moon-o theme-dark-btn themeIconTop<?php if ($_SESSION['mode'] && $_SESSION['mode'] == "dark") { ?> activeTheme<?php } ?>" title="Dark mode"></i></a>
            		 	</div>
					 	<a href="<?= $settings['url']; ?>new" class="headLink">New</a>
					 	<a href="<?= $settings['url']; ?>trending" class="headLink">Trending</a>
					 	<a href="<?= $settings['url']; ?>top-picks" class="headLink">Top Picks</a>


						
						<?php if(!checkSession()) { ?>
							<a href="<?= $settings['url']; ?>login" class="headLogBtn logBtn"><i class="fa fa-lock"></i> <?= $lang['menu_login'] ?></a>
							<a href="<?= $settings['url']; ?>register" class="headLogBtn regBtn"><i class="fa fa-user"></i> <?= $lang['menu_register'] ?></a>
						<?php } ?>
						<?php if(checkSession()) { ?>
							<a href="<?= $settings['url']; ?>account/summary" class="headLogBtn logBtn"><i class="fa fa-dashboard"></i> <?= $lang['title_summary'] ?></a>
							<a href="<?= $settings['url']; ?>logout" class="headLogBtn regBtn"><i class="fa fa-sign-out"></i> <?= $lang['log_out'] ?></a>
						<?php } ?>
					</div>
				</div> <!-- col -->
			</div> <!-- row -->
			<div class="headerNav hide992">
				
				<ul class="mainUl">
				    <?php
				    $header = $db->query("SELECT * FROM game_categories ORDER BY id");
                    if($header && $header->num_rows > 0) {
                      while($head = $header->fetch_assoc()) {
                    ?>
    				<li>
					    <a href="<?= $settings['url']; ?>category/<?=$head['slug']?>" class="main-link">
						<img src="<?= $settings['url']; ?>uploads/categories/<?=$head['slug']?>.png" alt="<?=$head['name']?> Games by <?=$settings['name'];?>"> <?=$head['name']?></a>
					</li>
    				<?php } } ?>
    				<!--
					<script type="text/javascript">
						$(function(){
							$(".dropdownLi").click(function(){
								$(".dropdownUl").slideToggle();
							});
						});
						$(document).mouseup(function(e) 
						{
							var container = $(".dropdownUl, .dropdownLi");
							if (!container.is(e.target) && container.has(e.target).length === 0) 
							{
								$(".dropdownUl").hide();
							}
						});
					</script>
					<li class="dropdownLi">
						<span class="main-link us pointer">
							Other games
							&nbsp;
							<i class="fa fa-angle-down"></i>
						</span>
						<ul class="dropdownUl">
							<li>
								<a href="#">
									Shooting
								</a>
							</li>
							<li>
								<a href="#">
									Puzzle
								</a>
							</li>
							<li>
								<a href="#">
									Action
								</a>
							</li>
							<li>
								<a href="#">
									Adventure
								</a>
							</li>
							<li>
								<a href="#">
									Roleplaying
								</a>
							</li>
						</ul>
					</li>
					-->
				</ul>
			</div> <!-- headerNav -->
		</div> <!-- headerInner -->
	</div> <!-- container -->
</div> <!-- header -->