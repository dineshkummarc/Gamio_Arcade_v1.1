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

					
<div class="col-lg-9">
	<div class="dashLeftCont">
		<h2 class="fs20">Welcome, <span class="cfirst"><?=idinfo($_SESSION['uid'],"full_name"); ?></span></h2>
		<div class="row">
			<div class="col-lg-3">
				<div class="dashStatBox flex ai">
					<div class="cont">
						<img src="<?= $settings['url'] ?>theme/img/gamesimg1.png">
					</div>
                    <?php 
                        $calc_played = $db->query("SELECT SUM(Played) FROM users WHERE id='$_SESSION[uid]'");
                        $c_played = $calc_played->fetch_row()[0];
                    ?>
					<div class="cont">
						<p class="head">Games Played</p>
						<p class="val"><?=$c_played;?></p>
					</div>
				</div>
			</div> <!-- col -->
			<div class="col-lg-3">
				<div class="dashStatBox flex ai" style="background: #ece1cc;">
					<div class="cont">
						<img src="<?= $settings['url'] ?>theme/img/gamesimg2.png">
					</div>
					<?php 
                        $calc_playtime = $db->query("SELECT SUM(Playtime) FROM users WHERE id='$_SESSION[uid]'");
                        $c_playtime = playtime_format($calc_playtime->fetch_row()[0]);
                    ?>
					<div class="cont">
						<p class="head">Time Played</p>
						<p class="val"><?=$c_playtime;?></p>
					</div>
				</div>
			</div> <!-- col -->
			<div class="col-lg-3">
				<div class="dashStatBox flex ai" style="background: #e8e4f5;">
					<div class="cont">
						<img src="<?= $settings['url'] ?>theme/img/gamesimg3.png">
					</div>
					<div class="cont">
					    <?php
					    $Count_Like_Query = $db->query("SELECT * FROM votes WHERE uid='$_SESSION[uid]' and vote='1'");
					    if ($Count_Like_Query->num_rows > 0) {
                            $Count_Like = $Count_Like_Query->num_rows;
                        } else {
                            $Count_Like = "0";
                        }
					    ?>
						<p class="head">Favorites</p>
						<p class="val"><?=$Count_Like;?></p>
					</div>
				</div>
			</div> <!-- col -->
			<div class="col-lg-3">
				<div class="dashStatBox flex ai" style="background: #ceffe6;">
					<div class="cont">
						<img src="<?= $settings['url'] ?>theme/img/gamesimg4.png">
					</div>
					<div class="cont">
						<p class="head">Server Time</p>
						<p class="val" id="clock"></p>
					</div>
					<script>
                    var serverTime = new Date("<?php echo date('Y-m-d H:i:s'); ?>");
                    function updateClock() {
                        serverTime.setSeconds(serverTime.getSeconds() + 1);
                        var hours = serverTime.getHours().toString().padStart(2, '0');
                        var minutes = serverTime.getMinutes().toString().padStart(2, '0');
                        var seconds = serverTime.getSeconds().toString().padStart(2, '0');
                        document.getElementById('clock').textContent = hours + ':' + minutes + ':' + seconds;
                    }
                    setInterval(updateClock, 1000);
                    updateClock();
                </script>
				</div>
			</div>
		</div>
		<div class="row">
		    <?php
            $statement = "users_logs  WHERE type='10' and uid='$_SESSION[uid]' ORDER BY id DESC";
            $query = $db->query("SELECT * FROM {$statement} LIMIT 1");
            if($query->num_rows > 0) {
              while($row = $query->fetch_assoc()) {
                $query_game = $db->query("SELECT * FROM games WHERE id='$row[u_field_1]'");
                $data = $query_game->fetch_assoc();
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
			<div class="col-lg-9">
				<div class="dashLastGame " style="background-image: url(<?=$thumbnail;?>);">
					<div class="jcb flex ai w100 favGamIn">
						<div class="cont">
							<p class="title">Last game played</p>
							<h2 class="name"><?=$data["Title"];?></h2>
							<a target="_blank" href="/play/<?=$data["Slug"];?>" class="playBtn inline">Play Game &nbsp; <i class="fa fa-arrow-circle-down"></i></a>
						</div>
						<div class="cont">
							<div class="gameBack">
								<img src="<?=$thumbnail;?>" class="gameImg">
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } } ?>
			<div class="col-lg-3">
				<div class="gameHistCont">
					<div class="flex ai jcb mb10">
						<h2 class="fs18">Recently Played</h2>
						<a href="#" class="cfirst fs13">View all</a>
					</div>
					<div class="gameHistSideOv">
						    <?php
                            $statement = "users_logs  WHERE type='10' and uid='$_SESSION[uid]' ORDER BY id DESC";
                            $query = $db->query("SELECT * FROM {$statement} LIMIT 4");
                            if($query->num_rows > 0) {
                              while($row = $query->fetch_assoc()) {
                                $query_game = $db->query("SELECT * FROM games WHERE id='$row[u_field_1]'");
                                $data = $query_game->fetch_assoc();
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
							<div class="gameHisBlock">
								<div class="flex ai">
									<div><img src="<?=$thumbnail;?>" class="img"></div>
									<div class="cont">
										<p class="name"><?=shrink($data["Title"],15);?></p>
										<span class="date"><?= date("d/m/Y",$row['time']); ?></span>
									</div>
								</div>
								<a target="_blank" href="/play/<?=$data["Slug"];?>" class="playBtn">Play</a>
							</div>
							<?php } } ?>
					</div> <!-- gameHistSideOv -->
				</div>
			</div>
		</div>
		<div class="dashHeadBlock flex ai jcb m20">
			<h2 class="fs18">Favourite Games</h2>
			<a href="/account/favourite" class="cfirst fs13">View all</a>
		</div>
		<div class="row">
		    <?php
            $statement = "votes  WHERE vote='1' and uid='$_SESSION[uid]' ORDER BY id DESC";
            $query = $db->query("SELECT * FROM {$statement} LIMIT 4");
            if($query->num_rows > 0) {
              while($row = $query->fetch_assoc()) {
                $query_game = $db->query("SELECT * FROM games WHERE id='$row[gid]'");
                $data = $query_game->fetch_assoc();
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
			<div class="col-lg-3">
			    <a target="_blank" href="/play/<?=$data["Slug"];?>" alt="<?=$data["Title"];?>">
    				<div class="favGameBlock">
    					<div class="favThumbBack">
    						<img src="<?=$thumbnail;?>" class="thumbimg">
    					</div>
    					<h2 class="title">
    						<?=shrink($data["Title"],15);?>
    					</h2>
    					<div class="starFavBlock1 flex">
    						<i class="fa fa-circle star1"></i>
    						<i class="fa fa-circle star1"></i>
    						<i class="fa fa-circle star1"></i>
    						<i class="fa fa-circle star1"></i>
    						<i class="fa fa-circle star2"></i>
    					</div>
    				</div>
				</a>
			</div>
			<!-- <?=$data["Title"];?> -->
			<?php } } else { ?>
			<div class="col-lg-12">
			    <?= info('You have not liked any games yet.'); ?>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
				