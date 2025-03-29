<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
if(!checkSession()) {
    $redirect = $settings['url']."login";
    header("Location: $redirect");
}   
$c = isset($_GET['c']) ? protect($_GET['c']) : '';

?>

    
<div class="col-md-2">
    <div class="leftNavBar">
        <h2 class="leftnavhead">Settings</h2>
		<ul class="leftNavbarUl">
			<li>
				<a href="<?= $settings['url']; ?>account/settings/profile">
					<?=$lang['settings_profile'];?>
				</a>
			</li>
			<li>
				<a href="<?= $settings['url']; ?>account/settings/change_password">
					<?=$lang['settings_change_password'];?>
				</a>
			</li>
			<!--
			<li>
				<a href="<?= $settings['url']; ?>account/settings/2fa">
					<?=$lang['settings_2fa'];?>
				</a>
			</li>
			-->
			<li>
				<a href="<?= $settings['url']; ?>account/settings/logs">
					<?=$lang['settings_account_logs'];?>
				</a>
			</li>
		</ul>
    </div>
</div>
<div class="col-md-7">
    <?php
    $redirect_profile = $settings['url']."account/settings/profile";
    switch($c) {
        case "profile": include("settings/profile.php"); break;
        case "change_password": include("settings/change_password.php"); break;
        case "2fa": include("settings/2fa.php"); break;
        case "logs": include("settings/logs.php"); break;
        default: header("Location: $redirect_profile");
    }
    ?>
</div>
    
