<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
if(isset($_GET["category"]) && !empty($_GET["category"])){
    $category = isset($_GET['category']) ? addslashes(protect($_GET['category'])) : "";
    $category_check = $db->query("SELECT * FROM game_categories WHERE slug='$category'");
    if($category_check && $category_check->num_rows > 0) {
        $category_data = $category_check->fetch_assoc();
        $category_name = $category_data["name"];
?>

<?php include("menu_notlogged.php"); ?>
<br>
<style>
    .vip {
        filter: brightness(0) invert(1);
    }
</style>
<div class="aboutCont">
	<div class="container">
		<div class="inner">
			<h2 class="head"><img class="vip" width="28" src="<?= $settings['url']; ?>uploads/categories/<?=$category_data['slug']?>.png" alt="<?=$category_data['name']?> Games by <?=$settings['name'];?>"> <?=$category_name;?> Games</h2>
			<?php if (!empty($category_data['description'])) { ?>
			<p class="txt">
				<?=$category_data['description'];?>
			</p>
			<?php } ?>
		</div>
	</div>
</div>

<div class="homeMainBody">
	<div class="container">
	    <!-- <?=$category_name;?> Games -->
		<div class="game-block-outer">
			<?php
            $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
            $limit = 144;
            $startpoint = ($page * $limit) - $limit;
            if($page == 1) {
              $i = 1;
            } else {
              $i = $page * $limit;
            }
            $statement = "games  WHERE status='enable' and category LIKE '%$category_name%' ORDER BY id DESC";
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
				<?php if ($data['Featured']=="yes") {?> 
				<div class="game-tooltip1">
					<img src="<?= $settings['url']; ?>theme/img/tagimg1.png" class="tooltip-img1">
					<span class="tooltip-txt1">Featured</span>
				</div>
				<?php } ?>
				<img src="<?=$thumbnail;?>" loading="lazy" alt="<?=$data["Title"];?>" class="game-single-thumb">
				<div class="game-hov-back">
					<p class="game-name"><?=shrink($data["Title"],13);?></p>
				</div>
			</a>
			<!-- <?=$data["Title"];?> -->
			<?php } } ?>
		</div>
		<!-- <?=$category_name;?> Games -->
		
		<?php
  
  		$ver = "/category/$_GET[category]";
        if(web_paginationme($statement,$ver,$limit,$page)) {
          echo web_paginationme($statement,$ver,$limit,$page);
        }	
  			
  		?>
  		<br>		
	</div>
</div>
<?php if (!empty($category_data['long_desc'])) { ?>
<div class="aboutCont">
	<div class="container">
		<div class="inner">
			<h2 class="head"><img class="vip" width="33" style="margin-top:-5px;" src="<?= $settings['url']; ?>uploads/categories/<?=$category_data['slug']?>.png" alt="<?=$category_data['name']?> Games by <?=$settings['name'];?>"> Play <?=$category_name;?> Games - <?=$settings['name'];?></h2>
			<p class="txt">
				<?=$category_data['long_desc'];?>
			</p>
		</div>
	</div>
</div>
<?php } ?>
<?php } else { header("Location: ../"); }  } else { header("Location: ../"); } ?>

<?php  include("footer.php"); ?>