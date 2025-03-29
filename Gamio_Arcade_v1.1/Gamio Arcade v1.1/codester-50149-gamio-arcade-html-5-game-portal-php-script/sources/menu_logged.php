<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if (isset($_GET['b'])) {
$b = protect($_GET['b']);
}
if (isset($_GET['c'])) {
$c = protect($_GET['c']); 
} else {
	$c = "";
}
?>
<div class="col-lg-3">
    <?php if ($b !== "settings") { ?>
	<div class="sideUserBox" align="center">
		<div class="rel userprofBack">
			<img src="<?= $settings['url'] ?>theme/img/user.png" class="userimg">
			<a href="/account/settings" title="Edit profile image"><i class="fa fa-pencil editPicIcon btn1 abs"></i></a>
		</div>
		<p class="name"><?=idinfo($_SESSION['uid'],"full_name"); ?></p>
		<p class="email cgray"><?=idinfo($_SESSION['uid'],"email"); ?></p>
	</div>
	<?php } ?>
	<script type="text/javascript">
		$(document).ready(function(){
    		$(".mobDashMenu").click(function(){
    			$(".leftNavBar").slideToggle();
    			$(this).toggleClass("active_mob_menu");
    		});
		});
	</script>
	<div class="mobDashMenu">
		<div class="flex ai jcb">
			<p>MENU</p>
			<i class="fa fa-bars"></i>
		</div>
	</div>
	<div class="leftNavBar">
		<h2 class="leftnavhead">General</h2>
		<ul class="leftNavbarUl">
			<li>
				<a href="<?= $settings['url']; ?>account/summary">
					<img src="<?= $settings['url'] ?>theme/img/bcaseimg.png" class="leftNavImg">
					Dashboard
				</a>
			</li>
			<li>
				<a href="<?= $settings['url']; ?>account//favourite">
					<img src="<?= $settings['url'] ?>theme/img/heratlinkimg.png" class="leftNavImg">
					Favorites
				</a>
			</li>
			<li>
				<a href="<?= $settings['url']; ?>account//activity">
					<img src="<?= $settings['url'] ?>theme/img/historyimg1.png" class="leftNavImg">
					Game History
				</a>
			</li>
			<li>
				<a href="<?= $settings['url']; ?>account/settings">
					<img src="<?= $settings['url'] ?>theme/img/settinglinkimg.png" class="leftNavImg">
					Settings
				</a>
			</li>
		</ul>
	</div> <!-- leftNavBar -->
	<!--
	<div class="dashSideAd">
		<a href="000000000000000:">
			<img src="<?= $settings['url'] ?>theme/img/banner-300.png">
		</a>
	</div>
	-->
</div> <!-- col -->