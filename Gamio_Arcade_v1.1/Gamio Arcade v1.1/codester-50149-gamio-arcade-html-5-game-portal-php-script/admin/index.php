<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(file_exists("./install.php")) {
	header("Location: ./install.php");
} 
define('V1_INSTALLED',TRUE);
ob_start();
session_start(); 
//error_reporting(0);
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include("../configs/bootstrap.php");
include("../includes/bootstrap.php");

if(checkAdminSession()) {
	include("sources/header.php");
	$a = isset($_GET['a']) ? protect($_GET['a']) : "";
	switch($a) {
		case "games": include("sources/games.php"); break;
		case "game-categories": include("sources/game_categories.php"); break;
		case "users": include("sources/users.php"); break;
      	case "traffic_track": include("sources/traffic_track.php"); break;
		case "pages": include("sources/pages.php"); break;
		case "blogs": include("sources/blogs.php"); break;
		case "smtp_settings": include("sources/smtp_settings.php"); break;
		case "settings": include("sources/settings.php"); break;
		case "ads": include("sources/ads.php"); break;
		case "content": include("sources/content.php"); break;
		case "branding": include("sources/branding.php"); break;
		case "sitemap": include("sources/sitemap.php"); break;
		case "featured": include("sources/featured.php"); break;
		case "faqs": include("sources/faqs.php"); break;
		case "logout": 
			unset($_SESSION['admin_uid']);
			unset($_COOKIE['admin_uid']);
			setcookie("admin_uid", "", time() - (86400 * 30), '/'); // 86400 = 1 day
			session_unset();
			session_destroy();
			header("Location: $settings[url]");
		break;
		default: include("sources/dashboard.php");
	}
	include("sources/footer.php");
} else {
	include("sources/login.php");
}
mysqli_close($db);
?>