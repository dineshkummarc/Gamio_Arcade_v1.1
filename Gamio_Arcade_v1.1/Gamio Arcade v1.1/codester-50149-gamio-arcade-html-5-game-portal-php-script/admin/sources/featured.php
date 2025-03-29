<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Featured Games</h1>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12"><br>
	<div class="card">
        <div class="card-body">
            <form action="" method="POST">
                <div class="row">
                    <div class="col-md-3" style="padding:10px;">
                        <input type="text" class="form-control" name="title" placeholder="Title" value="<?php if(isset($_POST['title'])) { echo $_POST['title']; } ?>">
                    </div>
                    <div class="col-md-3" style="padding:10px;">
                        <input type="text" class="form-control" name="category" placeholder="Category" value="<?php if(isset($_POST['category'])) { echo $_POST['category']; } ?>">
                    </div>
                    <div class="col-md-3" style="padding:10px;">
                        <input type="text" class="form-control" name="company" placeholder="Company" value="<?php if(isset($_POST['company'])) { echo $_POST['company']; } ?>">
                    </div>
                    <div class="col-md-3" style="padding:10px;">
                        <button type="submit" class="btn btn-primary btn-block" name="btn_search" value="games">Search</button>
                    </div>
                </div>
            </form>
            <button class="btn btn-danger btn-block" id="refresh-featured">Refresh Featured Games</button>
        </div>
    </div>
</div>
<div class="col-md-12">
	<div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="10%">Thumbnail</th>
                        <th width="25%">Title</th>
                        <th width="25%">Description</th>
                        <th width="5%">Category</th>
                        <th width="5%">Played</th>
                        <th width="5%">Playtime</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Handle the refresh request
                    if (isset($_POST['refresh'])) {
                        $run_ai = refresh_featured_games();
                        echo success_a("Featured Games has been refreshed.");
                    }
                    
                    $searching=0;
                    $FormBTN = isset($_POST['btn_search']) ? protect($_POST['btn_search']) : "";
                    if($FormBTN == "games") {
                        $searching=1;
                        $search_query = array();
                        $title = isset($_POST['title']) ? protect($_POST['title']) : "";
                        if(!empty($title)) { $search_query[] = "Title LIKE '%$title%'"; }
                        $category = isset($_POST['category']) ? protect($_POST['category']) : "";
                        if(!empty($category)) { $search_query[] = "Category LIKE '%$category%'"; }
                        $company = isset($_POST['company']) ? protect($_POST['company']) : "";
                        if(!empty($company)) { $search_query[] = "Company LIKE '%$company%'"; }
                        $p_query = implode(" and ",$search_query);
                    }
                    $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                    $limit = 10;
                    $startpoint = ($page * $limit) - $limit;
                    if($page == 1) {
                        $i = 1;
                    } else {
                        $i = $page * $limit;
                    }
                    $statement = "games WHERE Featured='yes' and status='enable'";
                    if($searching==1) {
                        if(empty($p_query)) {
                            $searching=0;
                            $query = $db->query("SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
                        }else {
                            $query = $db->query("SELECT * FROM {$statement} and $p_query ORDER BY id LIMIT {$startpoint} , {$limit}");
                        }
                    } else {
                        $query = $db->query("SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
                    }
                    
                    if($query->num_rows>0) {
                        while($row = $query->fetch_assoc()) {
                            $active_thumbnail = $row["ActiveThumbnail"];
                            if($active_thumbnail == 1) {
                                $thumbnail = "../uploads/images/".$row["image_store_url"];
                            }else {
                                $thumbnail = (is_array(json_decode($row['Thumbnail']))) ? json_decode($row['Thumbnail'])[0] : $row["Thumbnail"];
                            }
                            
                            ?>
                            <tr>
                                <td>
                                    <?php echo "<img src='$thumbnail' style='border-radius:10px;width:100px;height:70px'/>" ?>
                                </td>
                                <td>
                                    <?php echo $row['Title']; ?>
                                    &nbsp;&nbsp;<a class="text-danger" style="font-size:12px;" target="_blank" href="../play/<?php echo $row['Slug']; ?>"><i class="fa fa-external-link-square"></i></a>
                                </td>
                                <td><?php echo strlen($row['Description']) > 100 ? substr($row['Description'],0,100)."..." : $row['Description']; ?></td>
                                <td><?=$row['Category'];?></td>
                                <td><?php echo empty($row['Played']) ? 0 : $row['Played']; ?></td>
                                <td><?=playtime_format($row['Playtime']);?></td>
                                <td>
                                    <a target="_blank" href="./?a=games&b=edit&id=<?php echo $row['id']; ?>" title="Edit"><span class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</span></a> 
                                    <a target="_blank" href="./?a=games&b=delete&id=<?php echo $row['id']; ?>" title="Delete"><span class="badge badge-danger"><i class="fa fa-trash"></i> Delete</span></a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        if($searching == "1") {
                            echo '<tr><td colspan="6">No found results.</td></tr>';
                        } else {
                            echo '<tr><td colspan="6">No have featured games yet.</td></tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php
            if($searching == "0") {
                $ver = "./?a=featured";
                if(admin_pagination($statement,$ver,$limit,$page)) {
                    echo admin_pagination($statement,$ver,$limit,$page);
                }
            }
            ?>
        </div>
    </div>
</div>
<form id="refresh-form" method="post" style="display: none;">
    <input type="hidden" name="refresh" value="1">
</form>

<script>
document.getElementById('refresh-featured').addEventListener('click', function() {
    document.getElementById('refresh-form').submit();
});
</script>