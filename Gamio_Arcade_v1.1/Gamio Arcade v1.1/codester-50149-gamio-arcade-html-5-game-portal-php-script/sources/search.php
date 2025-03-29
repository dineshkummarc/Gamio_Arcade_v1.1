<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
?>
<?php include("menu_notlogged.php"); ?>
<?php
if(isset($_GET["search"]) && !empty($_GET["search"])) {
    $searching=0;
    $search = isset($_GET['search']) ? protect($_GET['search']) : "";
?>
<br>
<div class="homeMainBody">
	<div class="container">
	    <!-- Random Games -->
	    <div class="heading-block">
			<h2>Search Result #<?=$search;?></h2>
		</div>
		<div class="game-block-outer">
			<?php
                if(!empty($search)) {
                    $searching=1;
                    $search_query = array();
                    $search_query[] = "Title LIKE '%$search%'"; 
                    //$search_query[] = "Category LIKE '%$search%'";
                    //$search_query[] = "Tag LIKE '%$search%'";
                    $p_query = implode(" or ",$search_query);
                }
                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                $limit = 102;
                $startpoint = ($page * $limit) - $limit;
                if($page == 1) {
                    $i = 1;
                } else {
                    $i = $page * $limit;
                }
                $statement = "games WHERE status='enable'";
                if($searching==1) {
                    if(empty($p_query)) {
                      $searching=0;
                      $query = $db->query("SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
                    }else {
                      $statement = "games WHERE $p_query and status='enable'";
                      $query = $db->query("SELECT * FROM {$statement}  ORDER BY id LIMIT {$startpoint} , {$limit}");
                    }
                } else {
                    $query = $db->query("SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
                }
    
                if($query->num_rows > 0) {
                    while($data = $query->fetch_assoc()) {
                      $active_thumbnail = $data["ActiveThumbnail"];
                      $actual_link = parse_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
                      $site_url = $actual_link["scheme"]."://".$actual_link["host"].($actual_link["path"] ?? "");
                      $img_folder = $site_url."/uploads/images/";
    
                    if($active_thumbnail == 1) {
                        $thumbnail = $img_folder.$data["image_store_url"];
                    } else {
                        $thumbnail = (is_array(json_decode($data['Thumbnail']))) ? json_decode($data['Thumbnail'])[0] : $data["Thumbnail"];
                    }
            ?>
            <!-- <?=$data["Title"];?> -->
			<a href="<?= $settings['url']; ?>play/<?=$data["Slug"];?>" alt="<?=$data["Title"];?>" class="gameBox-single<?php if ($data['Featured']=="yes") {?> game-big-box<?php } ?>">
				<?php if ($data['Featured']=="yes") {?> 
				<div class="game-tooltip1">
					<img src="<?= $settings['url']; ?>theme/img/tagimg1.png" loading="lazy" class="tooltip-img1">
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
		<!-- Random Games -->
	    <?php
      		$ver = "/search/$_GET[search]";
            if(web_paginationme($statement,$ver,$limit,$page)) {
              echo web_paginationme($statement,$ver,$limit,$page);
            }	
  		?>
  		<br>
	</div>
</div>
<?php } else { header("Location: /"); } ?>
<?php include("footer.php"); ?>