<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}

$prefix = protect($_GET['prefix']);
$query = $db->query("SELECT * FROM pages WHERE prefix='$prefix'");
if($query->num_rows==0) {
    $redirect = $settings['url'];
    header("Location: $redirect");
}
$row = $query->fetch_assoc();
?>
<?php include("menu_notlogged.php"); ?>
<br>
<div class="aboutCont">
	<div class="container">
		<div class="inner">
			<h2 class="head"><?php echo $row['title']; ?></h2>
			<p class="txt">
			    <?php echo $row['content']; ?>	
			</p>
		</div>
	</div>
</div> <!-- aboutCont -->
<br>
<?php include("footer.php"); ?>
