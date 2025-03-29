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

<?php
if (isset($_GET['prefix']) && !empty($_GET['prefix'])) {
    $prefix = protect($_GET['prefix']);
    $query = $db->query("SELECT * FROM blogs WHERE prefix='$prefix'");
    if($query->num_rows==0) {
        $redirect = $settings['url'];
        //header("Location: $redirect");
    }
    $row = $query->fetch_assoc();
?>
<div class="singleBlogPage">
	<div class="container">
		<div class="row">
			<script>
				function myFunction() {
				var copyText = document.getElementById("linkUrl");

				copyText.select();
				copyText.setSelectionRange(0, 99999); 

				navigator.clipboard.writeText(copyText.value);
				
				alert("Blog URL Copied!");
				}
			</script>
			<?php
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
                $url = "https://";   
            else  
                $url = "http://";   
                $url.= $_SERVER['HTTP_HOST'];   
                $url.= $_SERVER['REQUEST_URI'];    
            ?>
			<input type="text" name="url" value="<?=$url;?>" id="linkUrl">
			<div class="col-lg-1">
				<div class="socialblogShare sticky">
					<a onclick="return fbs_click()" class="shareLinkBlog">
						<i class="fa fa-facebook shareIcon1"></i>
						<span class="shareTooptip">Share on Facebook</span>
					</a>
					<a onclick="return tbs_click()" class="shareLinkBlog">
						<i class="fa fa-twitter shareIcon1"></i>
						<span class="shareTooptip">Share on Twitter</span>
					</a>
					<a onclick="return ins_click()" class="shareLinkBlog">
						<i class="fa fa-linkedin shareIcon1"></i>
						<span class="shareTooptip">Share on Linkedin</span>
					</a>
					<a onclick="return red_click()" class="shareLinkBlog">
						<i class="fa fa-instagram shareIcon1"></i>
						<span class="shareTooptip">Share on Instagram</span>
					</a>
					<span class="shareLinkBlog copyLinkBlog" onclick="myFunction()">
						<i class="fa fa-clone shareIcon1"></i>
						<span class="shareTooptip">Copy Blog Link</span>
					</span>
				</div> <!-- socialblogShare -->
			</div> <!-- col -->
			
			<script type="text/javascript">
                var pageLink = window.location.href;
                var pageTitle = String(document.title).replace(/\&/g, '%26');
                function tbs_click(){pageLink = 'https://twitter.com/intent/tweet?text=<?= $row['title']; ?>&url=<?=$url?>';socialWindow(pageLink, 570, 570);}
                function fbs_click(){pageLink = 'https://www.facebook.com/sharer.php?u=<?=$url?>&quote=<?= $row['title']; ?>';socialWindow(pageLink, 570, 570);}
                function ins_click(){pageLink = 'https://www.linkedin.com/sharing/share-offsite/?url=<?=$url?>';socialWindow(pageLink, 570, 570);}
                function red_click(){pageLink = 'https://www.reddit.com/submit?url=<?=$url?>';socialWindow(pageLink, 570, 570);}
                function socialWindow(pageLink, width, height){var left = (screen.width - width) / 2;var top = (screen.height - height) / 2;var params = "menubar=no,toolbar=no,status=no,width=" + width + ",height=" + height + ",top=" + top + ",left=" + left;window.open(pageLink,"",params);}
            </script>
			<div class="col-lg-8">
				<div class="block1 singleBlogArea mb0">
					<div class="singleThumbBack ov rel">
						<img src="/<?=$row['image'];?>" class="thumbImgSingle">
						<!--<span class="catSingle">Adventure</span>-->
					</div> <!-- singleThumbBack -->
					<div class="inner">
						<h2 class="signletitle">
							<?php 
						    if (!empty($row['title'])) {
						        echo $row['title'];
						    } else {
						        echo error("Something went wrong... Please report this error...");
						    }
						    ?>
						</h2>
						<div class="row">
							<div class="col-md-3">
								<p class="cgray fs18">
									<i class="fa fa-clock-o"></i> 
									<?php 
    							    if (!empty($row['created'])) {
    							        echo date("d/m/Y",$row['created']);
    							    } else {
    							        echo error("Something went wrong... Please report this error...");
    							    }
    							    ?>
								</p>
							</div>
							<div class="col-md-3">
								<p class="cgray fs18">
									<i class="fa fa-user"></i> 
									Administrator
								</p>
							</div>
						</div> <!-- row -->
						<hr class="blogHr">
						<div class="descSingle">
							<p>
							    <?php 
							    if (!empty($row['content'])) {
							        echo $row['content'];
							    } else {
							        echo error("Something went wrong... Please report this error...");
							    }
							    ?>
							</p>
						</div> <!-- descSingle -->
					</div> <!-- inner -->
				</div> <!-- block1 -->
				<div class="block1 singleBlogArea mb0">
				    <p><center style="font-size:11px;margin-bottom:3px;"><b>ADVERTISEMENT</b></center></p>
    				<?=advertisement(2);?>
    			</div>
			</div> <!-- col -->
			
			<div class="col-lg-3 ">
				<div class="block1 mb0" style="padding: 21px;">
					<h2 class="block-head2">
						Games Categories
					</h2>
					<div class="cateBlogFlex flex fwrap">
					    <?php
    				    $header = $db->query("SELECT * FROM game_categories ORDER BY id");
                        if($header && $header->num_rows > 0) {
                          while($head = $header->fetch_assoc()) {
                        ?>
						    <a href="/category/<?=$head['slug']?>"><img width="15" src="<?= $settings['url']; ?>uploads/categories/<?=$head['slug']?>.png" alt="<?=$head['name']?> Games by <?=$settings['name'];?>">&nbsp;&nbsp;<?=$head['name']?></a>
						<?php } } ?>
					</div> <!-- cateBlogFlex -->
			    </div> <!-- sticky -->
				<div class="block1" style="padding: 21px">
					<h2 class="block-head2">
						Recent Blogs
					</h2>
					<?php
					$limit = 4;
					$statement = "blogs ORDER BY id DESC";
				    $query = $db->query("SELECT * FROM {$statement} LIMIT {$limit}");
                    if($query->num_rows > 0) {
                        while($data = $query->fetch_assoc()) {
                    ?>
                    <!--<?=$data['title'];?>-->
					<div class="recBlogList">
						<a href="/blog/<?=$data['prefix'];?>">
							<img src="/<?=$data['image'];?>" class="recImgBlog">
						</a>
						<div class="cont">
							<a href="/blog/<?=$data['prefix'];?>" class="titleRecBlog">
								<?=$data['title'];?>
							</a>	
							<p class="recBlogDate">
								<?= date("d/m/Y",$data['created']); ?>
							</p>
						</div>
					</div>
					<!--<?=$data['title'];?>-->
					<?php } } ?>
					<hr>
					<a href="/blog" class="flex ai">
						Read all blogs &nbsp; <i class="fa fa-angle-right fs11"></i>
					</a>
				</div> <!-- sticky -->
				<div class="block1" style="padding: 21px">
				    <p><center style="font-size:11px;"><b>ADVERTISEMENT</b></center></p>
    				<?=advertisement(1);?>
    				<br>
    			</div>
			</div> <!-- col -->
		</div> <!-- row -->
	</div>
</div> <!-- blogList -->

<?php }  else { ?>
<div class="blogList">
	<div class="container">
		<div class="row">
			<div class="col-lg-9">
				<div class="block1 mb0">
					<h2 class="block-head">
						<?= $settings['name'] ?> Blog
					</h2>
					<div class="row">
					    <?php
					    $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                        $limit = 15;
                        $startpoint = ($page * $limit) - $limit;
                        if($page == 1) {
                          $i = 1;
                        } else {
                          $i = $page * $limit;
                        }
					    $statement = "blogs ORDER BY id DESC";
					    $query = $db->query("SELECT * FROM {$statement} LIMIT {$startpoint} , {$limit}");
                        if($query->num_rows > 0) {
                            while($data = $query->fetch_assoc()) {
                        ?>
                        <!--<?=$data['title'];?>-->
						<div class="col-lg-4">
							<div class="blogBox">
							    <a href="/blog/<?=$data['prefix'];?>" class="thumbBack">
									<img src="/<?=$data['image'];?>" class="blogthumbimg">
									<!--<span class="blogCategory">Shooter</span>-->
								</a>
								<a href="/blog/<?=$data['prefix'];?>" class="title"><?=$data['title'];?></a>
								<p class="desc">
									<?=$data['content'];?>
								</p>
								<div class="flex ai jcb">
									<div class="cgray">
										<i class="fa fa-clock-o"></i> 
										<?= date("d/m/Y",$data['created']); ?>
									</div>
									<a href="/blog/<?=$data['prefix'];?>" class="blogBtn">Read more <i class="fa fa-angle-right"></i></a>
								</div>
							</div>
						</div>
						<!--<?=$data['title'];?>-->
						<?php } } ?>
				    </div>
					<?php
              		$ver = "/blog?";
                    if(web_paginationme($statement,$ver,$limit,$page)) {
                      echo web_paginationme($statement,$ver,$limit,$page);
                    }	
              		?>
				</div> <!-- block1 -->
			</div> <!-- col -->
			<div class="col-lg-3 ">
				<div class="sticky">
				    <!--
					<div class="block1 mb0" style="padding: 21px;">
						<h2 class="block-head2">
							Find Blogs
						</h2>
						<form method="post" action="000000000000000:">
							<div class="blogSearchBack flex">
								<input type="text" name="blog_search" class="blogSrchInp" placeholder="Search blog">
								<button class="blogSrchBtn btn1"><i class="fa fa-search"></i></button>
							</div>
						</form>
					</div>
                    -->
					<div class="block1 mb0" style="padding: 21px;">
						<h2 class="block-head2">
							Games Categories
						</h2>
						<div class="cateBlogFlex flex fwrap">
						    <?php
        				    $header = $db->query("SELECT * FROM game_categories ORDER BY id");
                            if($header && $header->num_rows > 0) {
                              while($head = $header->fetch_assoc()) {
                            ?>
							    <a href="/category/<?=$head['slug']?>"><img width="15" src="<?= $settings['url']; ?>uploads/categories/<?=$head['slug']?>.png" alt="<?=$head['name']?> Games by <?=$settings['name'];?>">&nbsp;&nbsp;<?=$head['name']?></a>
							<?php } } ?>
						</div> <!-- cateBlogFlex -->
				    </div> <!-- sticky -->
				</div> <!-- block1 -->
			</div> <!-- col -->
		</div> <!-- row -->
	</div>
</div> <!-- blogList -->
<?php } ?>
<br>
<?php include("footer.php"); ?>



