<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if (!defined('V1_INSTALLED')) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

if (isset($_GET["play"]) && !empty($_GET["play"])) {
    $slug = addslashes(protect(htmlspecialchars($_GET["play"])));
    $game_check = $db->query("SELECT * FROM games WHERE Slug='$slug' and status='enable'");
    if ($game_check && $game_check->num_rows > 0) {
        $game_data = $game_check->fetch_assoc();
        $max_recommended_games = 33;
        
        // Get first set of recommended games
        $recomended_games = getRandomGames($max_recommended_games);
        
        // Extract the IDs of the games in the first set
        $recommended_games_ids = array_column($recomended_games, 'id');
        
        // Get the second set of recommended games, excluding the ones already selected
        $recomended_games_b = getRandomGames($max_recommended_games, $recommended_games_ids);
?>

<?php include("menu_notlogged.php"); ?>
<div class="playPage">
	<div class="container">
		<div class="row">
		    <!-- Left Side -->
			<div class="col-lg-2 game-col-1 ">
			    <div class="midGmaePanel">
			        <p><center style="font-size:11px;"><b>ADVERTISEMENT</b></center></p>
    				<?=advertisement(1);?>
    			</div>
				<div class="game-block-outer">
				    <?php
				    if (count($recomended_games) > 0) {
                    foreach ($recomended_games as $list) {
                        $active_thumbnail = $list["ActiveThumbnail"];
                        $actual_link = parse_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
                        $site_url = $actual_link["scheme"]."://".$actual_link["host"].($actual_link["path"] ?? "");
                        $img_folder = $site_url."/uploads/images/";
    
                        if($active_thumbnail == 1) {
                            $thumbnail = $img_folder.$list["image_store_url"];
                        }else {
                            $thumbnail = (is_array(json_decode($list['Thumbnail']))) ? json_decode($list['Thumbnail'])[0] : $list["Thumbnail"];
                        }
                    ?>
                    <!-- <?=$list["Title"];?> -->
					<a href="<?= $settings['url']; ?>play/<?=$list["Slug"];?>" title="<?=$list["Title"];?>" class="gameBox-single">
						<img src="<?=$thumbnail;?>" title="<?=$list["Title"];?>" alt="<?=$list["Title"];?>" class="game-single-thumb">
						<div class="game-hov-back">
							<p class="game-name"><?=shrink($list["Title"],5);?></p>
						</div>
					</a>
					<!-- <?=$list["Title"];?> -->
                    <?php } } ?>
                </div> <!-- game-block-outer -->
            </div> 
            <!-- Left Side -->
            <!-- Main -->
            <div class="col-lg-8 game-col-2-">
				<div class="midGmaePanel">
					<div class="gameframeouter">
					    <iframe id="gameFrame" class="game-frame" src="<?=$settings['url'];?>embed/<?=$slug;?>" title="<?=$game_data["Title"];?>" scrolling="none" frameborder="0" allowfullscreen></iframe>
					</div> <!-- gameframeouter -->
					<script>
					    //var clickIframe = window.setInterval(checkFocus, 1000);
                        //var i = 0;
                        function checkFocus() {
                            if(document.activeElement == document.getElementById("gameFrame")) {
                                if(i == "5") {
                                    $.ajax({
                                      type: "POST",
                                      url: window.location.href,
                                      data: "gameFrame=true&play=1",
                                      success: function() {}
                                    });
                                } else if(i > 10) {
                                    $.ajax({
                                      type: "POST",
                                      url: window.location.href,
                                      data: "gameFrame=true&gftm=1",
                                      success: function() {}
                                    });
                                }
                                i++;
                            }
                        }
						var elem = document.getElementById("gameFrame");
						function openFullscreen() {
							if (elem.requestFullscreen) {
							    elem.requestFullscreen();
							} else if (elem.webkitRequestFullscreen) { /* Safari */
							    elem.webkitRequestFullscreen();
							} else if (elem.msRequestFullscreen) { /* IE11 */
							    elem.msRequestFullscreen();
							}
						}
					</script>
					<div class="gameControlBar">
						<div class="row ai">
							<div class="col-lg-7">
								<h2 class="single-game-name">
									<?=$game_data['Title'];?>
								</h2>
								<div class="game-star-cont flex">
									<i class="fa fa-star star1"></i>
									<i class="fa fa-star star1"></i>
									<i class="fa fa-star star1"></i>
									<i class="fa fa-star star1"></i>
									<i class="fa fa-star star2"></i>
									<span class="cgray">(4.9/5)</span>
								</div>
							</div> <!-- col -->
							<div class="col-lg-5">
								<div class="flex gamecontFlex jce">
									<div>
										<i class="fa fa-arrows-alt fullscreenBtn" title="Fullscreen" onclick="openFullscreen();"></i>
									</div>
									<div class="flex likeGameFlex ai">
									    <?php
									    $class_vote_like = "";
                                        $class_vote_dislike = "";
									    if (checkSession()) {
                                            $CheckFavorite = $db->query("SELECT * FROM votes WHERE uid='$_SESSION[uid]' and gid='$game_data[id]'");
                                            if($CheckFavorite->num_rows == "1") {
                                                $vote_is_there = "1";
                                                $vote_row = $CheckFavorite->fetch_assoc();
                                                
                                                //vote - 1 - Like
                                                //vote - 2 - Dislike
                                                //vote - 0 or Null or empty - No Vote
                                                
                                                if ($vote_row['vote'] == "1") {
                                                    $class_vote_like = " like-active";
                                                    $class_vote_dislike = "";
                                                } elseif ($vote_row['vote'] == "2") {
                                                    $class_vote_like = "";
                                                    $class_vote_dislike = " dislike-active";
                                                } else {
                                                    $class_vote_like = "";
                                                    $class_vote_dislike = "";
                                                }
                                            }
									    }
									    
                                        // Extract like and dislike counts
                                        $Count_Like_Query = $db->query("SELECT * FROM votes WHERE gid='$game_data[id]' and vote='1'");
                                        if ($Count_Like_Query->num_rows > 0) {
                                            $Count_Like = $Count_Like_Query->num_rows;
                                        } else {
                                            $Count_Like = "1";
                                        }
                                        $Count_DisLike_Query = $db->query("SELECT * FROM votes WHERE gid='$game_data[id]' and vote='2'");
                                        $Count_DisLike = $Count_DisLike_Query->num_rows;
                                        if ($Count_DisLike_Query->num_rows > 0) {
                                            $Count_DisLike = $Count_DisLike_Query->num_rows;
                                        } else {
                                            $Count_DisLike = "0";
                                        }
                                        $Total_count = $Count_Like + $Count_DisLike;
                                        $Like_Per =  $Count_Like/$Total_count*100;
                                        $DisLike_Per = 100-$Like_Per;
                                        
                                        ?>
                                        
                                        <a href="javascript:void(0);" id="ar_like" data-id="<?= $game_data['id']; ?>">
                                            <i class="fa fa-thumbs-o-up gameThumbsBtn<?=$class_vote_like;?>" title="Like!"></i>
										</a>
										
										<div class="LikeStatCont">
											<div class="likeStatBar flex">
												<div class="like-bar" style="width: <?=$Like_Per;?>%"></div>
												<div class="dislike-bar" style="width: <?=$DisLike_Per;?>%"></div>
											</div>
											<div class="likeStatValue flex jcb ai">
												<p class="like-value"><?=$Count_Like;?></p>
												<p class="dislike-value"><?=$Count_DisLike;?></p>
											</div>
										</div>
										
										<a href="javascript:void(0);" id="ar_dislike" data-id="<?= $game_data['id']; ?>">
										    <i class="fa fa-thumbs-o-down gameThumbsBtn<?=$class_vote_dislike;?>" title="Dislike!"></i>
										</a>
										
									</div>
								</div> <!-- gamecontFlex -->
							</div> <!-- col --> 
						</div> <!-- row -->		
					</div> <!-- gameControlBar -->
					<script>
					    $("#ar_like").on("click", function() {
                            $.ArLike($(this).data("id"));
                        });
                        $("#ar_dislike").on("click", function() {
                            $.ArDisLike($(this).data("id"));
                        });
					    $(function() {
                            "use strict";
                            $.ArLike = function(id) {
                                var url = "<?=$settings['url'];?>";
                                var data_url = url + "requests/user.php?a=ar_like&id=" + id;
                                $.ajax({
                                    type: "GET",
                                    url: data_url,
                                    dataType: "json",
                                    success: function(data) {
                                        if (data.status == "success") {
                                            $("#ar_like").html(data.content);
                                            $("#ar_dislike").html(data.contentw);
                                        } else {
                                            console.log(data.msg);
                                            $("#error_modal_msg").html(data.msg);
                                            $("#error_modal").modal("show");
                                        }
                                    },
                                    error: function(request, status, error) {
                                        console.log(request.responseText);
                                    }
                                });
                            };
                        });
                        $(function() {
                            "use strict";
                            $.ArDisLike = function(id) {
                                var url = "<?=$settings['url'];?>";
                                var data_url = url + "requests/user.php?a=ar_dislike&id=" + id;
                                $.ajax({
                                    type: "GET",
                                    url: data_url,
                                    dataType: "json",
                                    success: function(data) {
                                        if (data.status == "success") {
                                            $("#ar_dislike").html(data.content);
                                            $("#ar_like").html(data.contentw);
                                        } else {
                                            console.log(data.msg);
                                            $("#error_modal_msg").html(data.msg);
                                            $("#error_modal").modal("show");
                                        }
                                    },
                                    error: function(request, status, error) {
                                        console.log(request.responseText);
                                    }
                                });
                            };
                        });
					</script>
					<div class="gameDescCont">
					    <?php $categories = explode(',', $game_data["Category"]); ?>
						<h2 class="head">Category</h2>
						<p><?=$categories[0];?></p>
						
						<?php if (!empty($game_data['Description'])) { ?>
    						<h2 class="head">Description</h2>
						    <p> <?=$game_data['Description'];?> </p>
						<?php } ?>
						
						<?php if (!empty($game_data['Instructions'])) { ?>
    						<h2 class="head">Instruction</h2>
    						<p> <?=$game_data['Instructions'];?> </p>
						<?php } ?>
						
					</div> <!-- gameDescCont -->
					<div class="gamepageTags flex fwrap">
					    <?php
					    $header = $db->query("SELECT * FROM game_categories ORDER BY id");
                        if($header && $header->num_rows > 0) {
                            while($head = $header->fetch_assoc()) {
                        ?>
                        <a href="<?= $settings['url']; ?>category/<?=$head['slug']?>"><?=$head['name']?></a>
        				<?php } } ?>
					</div> <!-- gamepageTags -->
				</div> <!-- midGmaePanel -->
				<br>
				<div class="midGmaePanel">
				    <p><center style="font-size:11px;margin-bottom:3px;"><b>ADVERTISEMENT</b></center></p>
    				<?=advertisement(2);?>
    				<br>
    				<!-- <div class="banner-place-done m20" align="center">
    					<a href="#"><img src="/theme/img/728-banner.png"></a>
    				</div> -->
    			</div>
			</div>
			<!-- Main -->
			<!-- Right Side -->
            <div class="col-lg-2 game-col-3">
				<div class="midGmaePanel">
			        <p><center style="font-size:11px;"><b>ADVERTISEMENT</b></center></p>
    				<?=advertisement(1);?>
    			</div>
				<div class="game-block-outer">
				    <?php
                   if (count($recomended_games_b) > 0) {
                    foreach ($recomended_games_b as $list) {
                            $active_thumbnail = $list["ActiveThumbnail"];
                            $actual_link = parse_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
                            $site_url = $actual_link["scheme"]."://".$actual_link["host"].($actual_link["path"] ?? "");
                            $img_folder = $site_url."/uploads/images/";
    
                            if($active_thumbnail == 1) {
                                $thumbnail = $img_folder.$list["image_store_url"];
                            } else {
                                $thumbnail = (is_array(json_decode($list['Thumbnail']))) ? json_decode($list['Thumbnail'])[0] : $list["Thumbnail"];
                            }
                    ?>
                    <!-- <?=$list["Title"];?> -->
					<a href="<?= $settings['url']; ?>play/<?=$list["Slug"];?>" title="<?=$list["Title"];?>" class="gameBox-single">
						<img src="<?=$thumbnail;?>" title="<?=$list["Title"];?>" alt="<?=$list["Title"];?>" class="game-single-thumb">
						<div class="game-hov-back">
							<p class="game-name"><?=shrink($list["Title"],5);?></p>
						</div>
					</a> 
					<!-- <?=$list["Title"];?> -->
                    <?php } } ?>
				</div>
			</div> 
			<!-- Right Side -->
		</div>
	</div>
</div> <!-- playPage -->

<?php 
    } else { 
        header("Location: /"); 
    } 
} 
include("footer.php"); 
?>