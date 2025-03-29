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
?>
<?php include("menu_notlogged.php"); ?>
<div class="dashbBody">
	<div class="container">
		<div class="dashinner">
			<div class="row">
                <?php include("menu_logged.php"); ?>
                <?php
                $b = protect($_GET['b']);
                switch($b) {
                    case "summary": include("account/summary.php"); break;
                    case "favourite": include("account/favourite.php"); break;
                    case "activity": include("account/activity.php"); break;
                    case "settings": include("account/settings.php"); break;
                    default: include("account/summary.php");
                }
                ?>
            </div> <!-- row -->
		</div> <!-- dashinner -->
	</div>
</div> <!-- dashbBody -->
<?php include("footer.php"); ?>
    
    
