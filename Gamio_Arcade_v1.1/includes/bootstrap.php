<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}

include("phpmailer/phpmailer.class.php");
include("function.email.php");
include("function.user.php");
include("function.web.php");
include("function.language.php");
include("function.messages.php");
include("function.pagination.php");
include("class.template.php");
include("version.php");
include("GoogleAuthenticator.php");
?>