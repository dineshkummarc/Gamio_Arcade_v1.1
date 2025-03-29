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
        <p class="fs20 m20">Favourite Games</p>
        <!--  Must always add a tableOuter  div outside table  -->
        <div class="tableOuter">
        	<table class="table">
        		<tr>
        			<th>Name</th>
        			<th>Date</th>
        			<th></th>
        		</tr
        		<?php
                
                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                $limit = 10;
                $startpoint = ($page * $limit) - $limit;
                if($page == 1) {
                    $i = 1;
                } else {
                    $i = $page * $limit;
                }
                $statement = "votes  WHERE vote='1' and uid='$_SESSION[uid]'";
                $query = $db->query("SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
                        
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
        		<tr>
        			<td>
        				<div class="flex ai">
        					<div>
        						<img src="<?=$thumbnail;?>" width="30px" class="br5">
        						&nbsp;&nbsp;
        					</div>
        					<div>
        						<?=shrink($data["Title"],15);?>
        					</div>
        				</div>
        			</td>
        			<td><?= date("d/m/Y",$row['time']); ?></td>
        			<td>
        				<a target="_blank" href="/play/<?=$data["Slug"];?>" class="cfirst">Play&nbsp; <i class="fa fa-angle-right fs12"></i></a>
        			</td>
        		</tr>
        		<?php } } else { ?>
    			<div class="col-lg-12">
    			    <?= info('You have not liked any games yet.'); ?>
    			</div>
    			<?php } ?>
            </table>
            <?php
            $ver = $settings['url']."account/favourite";
            if(web_pagination($statement,$ver,$limit,$page)) {
                echo web_pagination($statement,$ver,$limit,$page);
            }
            ?>
        </div> <!-- tableOuter -->
    </div>
</div>							