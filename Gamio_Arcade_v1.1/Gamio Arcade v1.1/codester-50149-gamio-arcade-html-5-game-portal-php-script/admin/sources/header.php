<?php

// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0

?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Panel - <?php echo $settings['name']; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="/assets/img/favicon.png">
    <?php if($settings['favicon']) { ?>
		<link rel="icon" type="image/png" href="<?= $settings['url'].$settings['favicon'] ?>">
	<?php } else { ?>
		<link rel="icon" type="image/png" href="/assets/img/favicon.png">
	<?php } ?>
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/scss/style.css">
    <link href="assets/css/lib/vector-map/jqvmap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body>
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="./">CMS Portal</a>
            </div>
           
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="./"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>
                    <h3 class="menu-title">game management</h3><!-- /.menu-title -->
					<li class="active"><a href="./?a=games"><i class="menu-icon fa fa-gamepad"></i> All Games</a></li>
					<li class="active"><a href="./?a=games&b=add"><i class="menu-icon fa fa-plus"></i> Add Games</a></li>
					<li class="active"><a href="./?a=games&b=disable"><i class="menu-icon fa fa-minus-circle"></i> Disable Games</a></li>
					<li class="active"><a href="./?a=games&b=enable"><i class="menu-icon fa fa-plus-circle"></i> Enable Games</a></li>
					<li class="active"><a href="./?a=games&b=images"><i class="menu-icon fa fa-file-image-o"></i> Games Images</a></li>
					<li class="active"><a href="./?a=game-categories"><i class="menu-icon fa fa-th"></i> Game Categories</a></li>
                  	<li class="active"><a href="./?a=traffic_track"><i class="menu-icon fa fa-map"></i> Traffic Track</a></li>
                  	<li class="active"><a href="./?a=featured"><i class="menu-icon fa fa-star"></i> AI Featured</a></li>
                    <h3 class="menu-title">Content Management</h3><!-- /.menu-title -->
                    <li class="active"><a href="./?a=content"><i class="menu-icon fa fa-bookmark"></i> Content</a></li>
                    <li class="active"><a href="./?a=branding"><i class="menu-icon fa fa-camera-retro"></i> Branding</a></li>
                    <h3 class="menu-title">Users Management</h3><!-- /.menu-title -->
                    <li class="active"><a href="./?a=users"><i class="menu-icon fa fa-users"></i> All Users</a></li>
                    <h3 class="menu-title">admin settings</h3><!-- /.menu-title -->
                    <li class="active"><a href="./?a=ads"><i class="menu-icon fa fa-dollar"></i> Advertisement</a></li>
                    <li class="active"><a href="./?a=ads&b=editor"><i class="menu-icon fa fa-bug"></i> Edit Ads.txt</a></li>
                    <li class="active"><a href="./?a=sitemap"><i class="menu-icon fa fa-list-alt"></i> Sitemap</a></li>
                    <li class="active"><a href="./?a=blogs"> <i class="menu-icon fa fa-sticky-note-o"></i>Manage Blogs </a></li>
					<li class="active"><a href="./?a=pages"> <i class="menu-icon fa fa-bars"></i>Manage Pages </a></li>
					<li class="active"><a href="./?a=faqs"> <i class="menu-icon fa fa-question"></i>Manage Faqs </a></li>
                    <li class="active"><a href="./?a=settings"> <i class="menu-icon fa fa-cogs"></i>Web Settings </a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->
    <div id="right-panel" class="right-panel">