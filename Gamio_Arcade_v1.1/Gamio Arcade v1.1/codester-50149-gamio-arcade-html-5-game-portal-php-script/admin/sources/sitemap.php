<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0

?>
<style>
	textarea {
	width: 100%;
	height: 300px;
	padding: 10px;
	font-size: 16px;
	border: 2px solid #ccc;
	border-radius: 5px;
	resize: vertical;
	}
</style>
<div class="breadcrumbs">
	<div class="col-sm-4">
		<div class="page-header float-left">
			<div class="page-title">
				<h1>Sitemap Generator</h1>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="page-header float-right">
			<div class="page-title">
				<ol class="breadcrumb text-right">
					<li><button class="btn btn-info" onclick="downloadSitemap()"><em class="icon ni ni-download"></em><span>Download</span></button></li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="content mt-3">
   	<div class="col-md-12">
		<div class="card">
            <div class="card-body">
    			<?php
    			if(isset($_POST['save'])) {
    			    $new_content = $_POST['new_content'];
                    // Open the file for writing
                    $file = fopen("../sitemap.xml", "w") or die(error_a("Unable to open file!"));
                    // Write the new content to the file
                    fwrite($file, $new_content);
                    // Close the file
                    fclose($file);
                    echo success_a("File edited successfully!");
    			}
    			$file = fopen("../sitemap.xml", "r") or die("Unable to open file!");
                $content = fread($file, filesize("../sitemap.xml"));
                fclose($file);
    			?>
    			<form action="" method="POST">
    				<div class="form-group">
					    <label for="new_content">Edit Sitemap.xml</label><hr>
						<textarea id="sitemapContent" class="form-control" name="new_content" style="height:500px;">
<?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>'); ?>						    
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<url>
    <loc><?= $settings['url']; ?></loc>
    <lastmod><?=date('Y-m-d');?></lastmod>
    <priority>1.0</priority>
</url>
<url>
    <loc><?= $settings['url']; ?>new</loc>
    <lastmod><?=date('Y-m-d');?></lastmod>
    <priority>1.0</priority>
</url>
<url>
    <loc><?= $settings['url']; ?>trending</loc>
    <lastmod><?=date('Y-m-d');?></lastmod>
    <priority>1.0</priority>
</url>
<url>
    <loc><?= $settings['url']; ?>top-picks</loc>
    <lastmod><?=date('Y-m-d');?></lastmod>
    <priority>1.0</priority>
</url>
<url>
    <loc><?= $settings['url']; ?>blog</loc>
    <lastmod><?=date('Y-m-d');?></lastmod>
    <priority>1.0</priority>
</url>
<url>
    <loc><?= $settings['url']; ?>contact</loc>
    <lastmod><?=date('Y-m-d');?></lastmod>
    <priority>0.8</priority>
</url>
<url>
    <loc><?= $settings['url']; ?>about</loc>
    <lastmod><?=date('Y-m-d');?></lastmod>
    <priority>0.8</priority>
</url>
<url>
    <loc><?= $settings['url']; ?>page/terms-and-conditions</loc>
    <priority>0.5</priority>
</url>
<url>
    <loc><?= $settings['url']; ?>page/privacy-policy</loc>
    <priority>0.5</priority>
</url>
<?php
// Categories URL
$GetCat = $db->query("SELECT * FROM game_categories");
while($gc = $GetCat->fetch_assoc()) {
?>
<url>
    <loc><?= $settings['url']; ?>category/<?=$gc['slug'];?></loc>
    <priority>0.8</priority>
</url>
<?php } ?>
<?php
// Games URL
$GetItem = $db->query("SELECT * FROM games WHERE status='enable'");
while($gi = $GetItem->fetch_assoc()) {
?>
<url>
    <loc><?= $settings['url']; ?>play/<?=$gi['Slug'];?></loc>
    <lastmod><?php if ($gi['updated'] > 0) { ?><?=date('Y-m-d', $gi['updated']);?><?php } else { ?><?=date('Y-m-d', $gi['created']);?><?php } ?></lastmod>
    <priority>1.0</priority>
</url>
<?php } ?>
<?php
// Blog URL
$GetBlog = $db->query("SELECT * FROM blogs");
while($gb = $GetBlog->fetch_assoc()) {
?>
<url>
    <loc><?= $settings['url']; ?>blog/<?=$gb['prefix'];?></loc>
    <lastmod><?php if ($gb['updated'] > 0) { ?><?=date('Y-m-d', $gb['updated']);?><?php } else { ?><?=date('Y-m-d', $gb['created']);?><?php } ?></lastmod>
    <priority>0.5</priority>
</url>                                  
<?php } ?>
</urlset>
                        </textarea>
                        <br>
        			</div>
        			<button type="submit" class="btn btn-primary" name="save"><i class="ni ni-check"></i>&nbsp;&nbsp;Save</button>
        		</form>		
                <script>
                function downloadSitemap() {
                var sitemapContent = document.getElementById("sitemapContent").value;
                var blob = new Blob([sitemapContent], { type: "text/xml" });
                var url = URL.createObjectURL(blob);
                var a = document.createElement("a");
                a.href = url;
                a.download = "sitemap.xml";
                a.click();
                }
                </script>
            </div>
        </div>
    </div><!-- .nk-block -->
</div>