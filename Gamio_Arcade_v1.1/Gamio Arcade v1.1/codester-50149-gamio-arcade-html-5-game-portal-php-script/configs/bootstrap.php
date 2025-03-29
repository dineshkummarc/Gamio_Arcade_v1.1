<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}

include("sql.settings.php");
include("smtp.settings.php");
include("module.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
    exit;
}
$db->set_charset("utf8");

$settingsQuery = $db->query("SELECT * FROM settings ORDER BY id DESC LIMIT 1");
$settings = $settingsQuery->fetch_assoc();

date_default_timezone_set('Asia/Karachi');
?>
