<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
if(isset($_GET["a"]) && !empty($_GET["a"])) {
    if ($_GET["a"] == "new") {
        $gquery = "id DESC";
        $gtitle = "Newest";
        $imgta= "newimg.png";
        $imgti= "New";
        $long_description = $settings['new_page'];
    } else if($_GET["a"] == "trending") {
        $gquery = "Played DESC";
        $gtitle = "Trending";
        $imgta= "popularimg.png";
        $imgti= "Popular";
        $long_description = $settings['trending_page'];
    } else if($_GET["a"] == "top-picks") {
        $gquery = "Featured";
        $gtitle = "Top Picks";
        $imgta= "hotimg.png";
        $imgti= "Hot";
        $long_description = $settings['toppick_page'];
    } else {
        header("Location: /");
    }
?>

<?php include("menu_notlogged.php"); ?>
<br>
<div class="aboutCont">
	<div class="container">
		<div class="inner">
			<h2 class="head"><img width="25" src="<?= $settings['url']; ?>theme/img/<?=$imgta;?>" alt="<?=$gtitle;?> Games by <?=$settings['name'];?>"> <?=$gtitle;?> Games</h2>
			<?php if (!empty($description)) { ?>
			<p class="txt">
				<?=$description;?>
			</p>
			<?php } ?>
		</div>
	</div>
</div>

<div class="homeMainBody">
	<div class="container">
	    <!-- Random Games -->
		<div class="game-block-outer">
			<?php
              $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
  			  $limit = 240;
              $startpoint = ($page * $limit) - $limit;
              if($page == 1) {
                $i = 1;
              } else {
                $i = $page * $limit;
              }
              $statement = "games WHERE status='enable' ORDER BY {$gquery}";
              $query = $db->query("SELECT * FROM {$statement} LIMIT {$startpoint} , {$limit}");

              if($query->num_rows > 0) {
                while($data = $query->fetch_assoc()) {
                  $active_thumbnail = $data["ActiveThumbnail"];
                  $actual_link = parse_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
                  $site_url = $actual_link["scheme"]."://".$actual_link["host"].($actual_link["path"] ?? "");
                  $img_folder = $site_url."/uploads/images/";

                  if($active_thumbnail == 1) {
                    $thumbnail = $img_folder.$data["image_store_url"];
                  }else {
                    $thumbnail = (is_array(json_decode($data['Thumbnail']))) ? json_decode($data['Thumbnail'])[0] : $data["Thumbnail"];
                  }
            ?>
            <!-- <?=$data["Title"];?> -->
			<a href="<?= $settings['url']; ?>play/<?=$data["Slug"];?>" alt="<?=$data["Title"];?>" class="gameBox-single<?php if ($data['Featured']=="yes") {?> game-big-box<?php } ?>">
				<div class="game-tooltip1">
					<img src="<?= $settings['url']; ?>theme/img/<?=$imgta;?>" class="tooltip-img1">
					<span class="tooltip-txt1"><?=$imgti;?></span>
				</div>
				<img src="<?=$thumbnail;?>" loading="lazy" alt="<?=$data["Title"];?>" class="game-single-thumb">
				<div class="game-hov-back">
					<p class="game-name"><?=shrink($data["Title"],13);?></p>
				</div>
			</a>
			<!-- <?=$data["Title"];?> -->
			<?php } } ?>
		</div>
		<!-- Random Games -->
		<!--
		<?php
        $ver = "/subject/";
        $subject = isset($_GET['subject']) ? $_GET['subject'] : '';
        
        if (!empty($subject)) {
            $ver .= urlencode($subject);
        }
        
        if (web_paginationme($statement, $ver, $limit, $page)) {
            echo web_paginationme($statement, $ver, $limit, $page);
        }
        ?>
  		-->
  		<br>		
	</div>
</div>
<?php if (!empty($long_description)) { ?>
<div class="aboutCont">
	<div class="container">
		<div class="inner">
			<h2 class="head"><img width="33" style="margin-top:-5px;" src="<?= $settings['url']; ?>theme/img/<?=$imgta;?>" alt="<?=$gtitle;?> Games by <?=$settings['name'];?>"> <?=$gtitle;?> Games</h2>
			<p class="txt">
				<?=$long_description;?>
			</p>
		</div>
	</div>
</div>
<?php } ?>
<?php } include("footer.php");  ?>