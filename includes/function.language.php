<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}

function getLanguage($url, $ln = null, $type = null) {
	global $db, $settings;
	// Type 1: Output the available languages
	// Type 2: Change the path for the /requests/ folder location
	// Set the directory locations
	if($type == 2) {
		$languagesDir = '../languages/';
	} else {
		$languagesDir = './languages/';
	}
	// Search for pathnames matching the .png pattern
	$language = glob($languagesDir . '*.php', GLOB_BRACE);

	if($type == 1) {
		// Add to array the available images
		foreach($language as $lang) {
			// The path to be parsed
			$path = pathinfo($lang);
			
			// Add the filename into $available array
			if($path['filename'] !== "index") {
				$available .= '<li class="nav-item"><a class="nav-link" href="'.$url.'index.php?lang='.$path['filename'].'">'.ucfirst(strtolower($path['filename'])).'</a></li>';
			}
		}
		return $available;
	} else {
		// If get is set, set the cookie and stuff
		$lang = $settings['default_language']; // DEFAULT LANGUAGE
		if($type == 2) {
			$path = '../languages/';
		} else {
			$path = './languages/';
		}
		if(isset($_GET['lang'])) {
			if(in_array($path.$_GET['lang'].'.php', $language)) {
				$lang = $_GET['lang'];
				setcookie('lang', $lang, time() +  (10 * 365 * 24 * 60 * 60)); // Expire in one month
			} else {
				setcookie('lang', $lang, time() +  (10 * 365 * 24 * 60 * 60)); // Expire in one month
			}
			header("Location: $settings[url]");
		} elseif(isset($_COOKIE['lang'])) {
			if(in_array($path.$_COOKIE['lang'].'.php', $language)) {
				$lang = $_COOKIE['lang'];
			}
		} else {
			setcookie('lang', $lang, time() +  (10 * 365 * 24 * 60 * 60)); // Expire in one month
		}

		if(in_array($path.$lang.'.php', $language)) {
			return $path.$lang.'.php';
		}
	}
}
?>