<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
?>

<?php include("menu_notlogged.php"); ?>

<div class="homeMainBody">
    <?php
    $query = $db->query("SELECT * FROM games WHERE status='enable'");
    if($query->num_rows > 0) {
    ?>
    	<div class="container">
    	    <?php
    	    // Define the limit of games to show
            $limit = 140;
            
            // Fetch popular games with random offset
            $offset = get_random_offset('games', "status='enable'","40");
            $popular_games_query = "SELECT * FROM games WHERE status='enable' ORDER BY Played DESC LIMIT $offset, 40";
            $popular_games_result = $db->query($popular_games_query);
            $popular_games = $popular_games_result->fetch_all(MYSQLI_ASSOC);
            
            // Fetch new releases with random offset
            $offset = get_random_offset('games', "status='enable'", "40");
            $new_releases_query = "SELECT * FROM games WHERE status='enable' ORDER BY id DESC LIMIT $offset, 40";
            $new_releases_result = $db->query($new_releases_query);
            $new_releases = $new_releases_result->fetch_all(MYSQLI_ASSOC);
            
            // Fetch random games with a subquery approach
            $max_id_result = $db->query("SELECT MAX(id) as max_id FROM games WHERE status='enable'");
            $max_id_row = $max_id_result->fetch_assoc();
            $max_id = $max_id_row['max_id'];
            $random_games = [];
            for ($i = 0; $i < 40; $i++) {
                $random_id = mt_rand(1, $max_id);
                $random_games_query = "SELECT * FROM games WHERE id >= $random_id AND status='enable' LIMIT 1";
                $random_games_result = $db->query($random_games_query);
                if ($random_games_result && $random_games_result->num_rows > 0) {
                    $random_games[] = $random_games_result->fetch_assoc();
                }
            }
            
            // Fetch featured games with random offset
            $offset = get_random_offset('games', "status='enable' AND Featured='yes'", 20);
            $featured_games_query = "SELECT * FROM games WHERE status='enable' AND Featured='yes' LIMIT $offset, 20";
            $featured_games_result = $db->query($featured_games_query);
            $featured_games = $featured_games_result->fetch_all(MYSQLI_ASSOC);
            
            // Combine results into a unique associative array to avoid duplicates
            $games = [];
            $unique_game_ids = [];
            add_games($games, $unique_game_ids, $popular_games);
            add_games($games, $unique_game_ids, $new_releases);
            add_games($games, $unique_game_ids, $random_games);
            add_games($games, $unique_game_ids, $featured_games);
            
            // Shuffle the combined array to mix different types of games
            shuffle($games);
            
            // Limit the total number of games to display
            $games = array_slice($games, 0, $limit);
            ?>
            
            <!-- Display Games -->
            <div class="game-block-outer">
                <?php foreach ($games as $data) {
                    $active_thumbnail = $data["ActiveThumbnail"];
                    $actual_link = parse_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
                    $site_url = $actual_link["scheme"]."://".$actual_link["host"].($actual_link["path"] ?? "");
                    $img_folder = $site_url."/uploads/images/";
            
                    if ($active_thumbnail == 1) {
                        $thumbnail = $img_folder . $data["image_store_url"];
                    } else {
                        $thumbnail = (is_array(json_decode($data['Thumbnail']))) ? json_decode($data['Thumbnail'])[0] : $data["Thumbnail"];
                    } 
                ?>
                <!-- Display game block -->
                <a href="<?= $settings['url']; ?>play/<?=$data["Slug"];?>" alt="<?=$data["Title"];?>" class="gameBox-single<?php if ($data['Featured'] == "yes") {?> game-big-box<?php } ?>">
                    <?php if ($data['Featured'] == "yes") {?> 
                    <div class="game-tooltip1">
                        <img src="<?= $settings['url']; ?>theme/img/tagimg1.png" class="tooltip-img1">
                        <span class="tooltip-txt1">Featured</span>
                    </div>
                    <?php } ?>
                    <img src="<?=$thumbnail;?>" loading="lazy" alt="<?=$data["Title"];?>" class="game-single-thumb">
                    <div class="game-hov-back">
                        <p class="game-name"><?=shrink($data["Title"], 13);?></p>
                    </div>
                </a>
                <?php } ?>
            </div>
            <!-- End Display Games -->
            
    		<!-- Trending Games -->
    		<div class="heading-block">
    			<h2>Trending Games</h2>
    		</div>
    		<div class="game-block-outer">
    			<?php
        		$gquery = "Played DESC";
            	$gtitle = "Trend";
                $limit = 48;
                $statement = "games WHERE status='enable'";
                $query = $db->query("SELECT * FROM {$statement} ORDER BY {$gquery} LIMIT 1 , {$limit}");
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
    		<!-- Trending Games -->
    		
    		
    		
    		
    		<div class="heading-block">
    			<h2>Check This Out!</h2>
    		</div> <!-- heading-block -->
    		<div class="game-block-outer2">
    			
    			<?php
        		$gtitle = "Trend";
    			$random_games = getRandomGames(10);
                if (count($random_games) > 0) {
                foreach ($random_games as $data) {
                $active_thumbnail = $data["ActiveThumbnail"];
                $actual_link = parse_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
                $site_url = $actual_link["scheme"]."://".$actual_link["host"].($actual_link["path"] ?? "");
                $img_folder = $site_url."/uploads/images/";
        
                if ($active_thumbnail == 1) {
                    $thumbnail = $img_folder.$data["image_store_url"];
                } else {
                    $thumbnail = (is_array(json_decode($data['Thumbnail']))) ? json_decode($data['Thumbnail'])[0] : $data["Thumbnail"];
                }
                $categories = explode(',', $data["Category"]);
                ?>
                <!-- <?=$data["Title"];?> -->
                <a href="<?= $settings['url']; ?>play/<?=$data["Slug"];?>" alt="<?=$data["Title"];?>" class="gameBox-single1">
    				<div class="flex gamesingleFlex">
    					<div class="cont">
    						<img src="<?=$thumbnail;?>" loading="lazy" alt="<?=$data["Title"];?>" class="gamesingle1Thumb">
    					</div>
    					<div class="cont">
    						<p class="name"><?=shrink($data["Title"],13);?></p>
    						<p class="cat"><?=$categories[0];?></p>
    					</div>
    				</div> <!-- flex -->
    			</a>
                <!-- <?=$data["Title"];?> -->
                <?php } } ?>
            </div> <!-- game-block-outer -->
    	</div> <!-- container -->
    <?php } else { ?>
    <br>
    <div class="container">
        <?=error("Currently No Games Found.");?>
    </div> <!-- container -->
    <?php } ?>
</div> <!-- homeMainBody -->
<div class="blogcont">
	<div class="container">
		<script type="text/javascript">
			$(function(){
			$('.blogSlider').slick({
			dots: true,
			infinite: false,
			speed: 300,
			slidesToShow: 4,
			slidesToScroll: 4,
			responsive: [
				{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					infinite: true,
					dots: true
				}
				},
				{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2
				}
				},
				{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
				}
			]
			});
			$(".blog-left").click(function(){
				$(".blogSlider").find(".slick-prev").trigger("click");
			});
			$(".blog-right").click(function(){
				$(".blogSlider").find(".slick-next").trigger("click");
			});
			});
		</script>
		<div class="heading-block flex ai jcb">
			<h2>Our Blogs</h2>
			<div class="blogArrows flex">
				<i class="fa fa-angle-left blogArrow blog-left"></i>
				<i class="fa fa-angle-right blogArrow blog-right"></i>
			</div>
		</div> <!-- heading-block -->
		<?php
        $query = $db->query("SELECT * FROM blogs ");
        if($query->num_rows > 0) {
        ?>
		<div class="inner">
			<div class="blogSlider">
				<?php
				$limit = 10;
				$statement = "blogs ORDER BY id DESC";
			    $query = $db->query("SELECT * FROM {$statement} LIMIT {$limit}");
                if($query->num_rows > 0) {
                    while($data = $query->fetch_assoc()) {
                ?>
                <!--<?=$data['title'];?>-->
				<div class="blog-slide">
					<div class="blogBox">
						<a href="<?= $settings['url']; ?>blog/<?=$data['prefix'];?>" class="thumbBack">
							<img src="/<?=$data['image'];?>" class="blogthumbimg">
							<!--<span class="blogCategory">Shooter</span>-->
						</a>
						<a href="<?= $settings['url']; ?>blog/<?=$data['prefix'];?>" class="title"><?=$data['title'];?></a>
						<p class="desc">
							<?=$data['content'];?>
						</p>
						<a href="<?= $settings['url']; ?>blog/<?=$data['prefix'];?>" class="blogBtn">Read more <i class="fa fa-angle-right"></i></a>
					</div>
				</div>
				<!--<?=$data['title'];?>-->
				<?php } } ?>
			</div>
		</div>
		<?php } else { ?>
		    <br>
		    <?=error("Currently No Any Blog Post Found.");?>
		<?php } ?>
	</div>
</div> <!-- blogcont -->

<?php include("footer.php"); ?>