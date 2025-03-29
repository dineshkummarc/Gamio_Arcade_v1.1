<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(isset($_GET['b'])) {
    $b = protect($_GET['b']);
} else {
    $b = "0";
}

if ($b == "edit") {
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM game_categories WHERE id='$id'");
if($query->num_rows==0) { header("Location: ./?a=game-categories"); }
$row = $query->fetch_assoc();
?>
<div class="breadcrumbs">
	<div class="col-sm-4">
		<div class="page-header float-left">
			<div class="page-title">
				<h1>Manage Category</h1>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="page-header float-right">
			<div class="page-title">
				<ol class="breadcrumb text-right">
					<li>Edit Category</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<?php
$btnsubmit = isset($_POST['btn_submit']) ? protect($_POST['btn_submit']) : "";
if($btnsubmit == "Update") {
    $id = $row['id'];
    $category_name = addslashes($_POST["category_name"]);
    $description = addslashes($_POST["description"]);
    $long_desc = addslashes($_POST["long_desc"]);
    $slug = strtolower(str_replace(' ', '-', $_POST["slug"]));
    if(empty($category_name)) {
        echo error_a("Please enter category name");
    }
    $upd = $db->query("UPDATE game_categories SET name='$category_name', slug='$slug', description='$description', long_desc='$long_desc' WHERE id='$id'");
    if($upd) {
        echo success_a("Category has been updated.");
    }else {
        echo error_a("Something went wrong. please try again");
    }
    $query = $db->query("SELECT * FROM game_categories WHERE id='$id'");
    $row = $query->fetch_assoc();
}
?>
<div class="content mt-3">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
                <form action="" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editGameLabel">Edit Category</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type='text' name='category_name' value='<?=$row['name'];?>' class='form-control'/>
                            </div>
                            <div class="form-group">
                                <label>Slug</label>
                                <input type='text' name='slug' value='<?=$row['slug'];?>' class='form-control'/>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type='text' name='description' value='<?=$row['description'];?>' placeholder="Description (Optional)" class='form-control'/>
                            </div>
                            <div class="form-group">
            					<textarea class='cleditor' name='long_desc'><?=$row['long_desc'];?></textarea>
            				</div>
                        </div>
                        <button type="submit" class="btn btn-primary" name="btn_submit" value="Update">Save changes</button>
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>
<?php
} elseif($b == "delete") {
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM game_categories WHERE id='$id'");
if($query->num_rows==0) { header("Location: ./?a=game_categories"); }
$row = $query->fetch_assoc();
?>
	
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Delete Category</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li>Want to delete this category?</li>
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
    			if(isset($_GET['confirm'])) {
    				$delete = $db->query("DELETE FROM game_categories WHERE id='$row[id]'");
    				echo success_a("Category <b>$row[name]</b> was deleted.");
    			} else {
    				echo info_a("Are you sure you want to delete category <b>$row[name]</b>?");
    				echo '<a href="./?a=game-categories&b=delete&id='.$row['id'].'&confirm=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
    					<a href="./?a=game-categories" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
    			}
    			?>
	        </div>
        </div>
    </div>
</div>
<?php } else { ?>
<br>
<br>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Game Categories</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <span class="pull-right" style="margin-top:5px;margin-bottom:-10px;">
					
				</span>
            </div>
        </div>
    </div>
</div>
<?php
$btnsubmit = isset($_POST['btn_submit']) ? protect($_POST['btn_submit']) : "";
if($btnsubmit == "Add") {
        $category_name = addslashes($_POST["category_name"]);
        $description = addslashes($_POST["description"]);
        $long_desc = addslashes($_POST["long_desc"]);
        $slug = strtolower(str_replace(' ', '-', $category_name));
        if(empty($category_name)) {
            echo error_a("Please enter category name");
        }
        $check_category = $db->query("SELECT * FROM game_categories WHERE name='$category_name'");
        if($check_category && $check_category->num_rows > 0) {
            echo error_a("Category is already in database");
        }else {
            $ins = $db->query("INSERT INTO game_categories(name, slug, description,long_desc) VALUES('$category_name', '$slug', '$description', '$long_desc')");
            if($ins) {
                echo success_a("Category was added");
            }else {
                echo error_a("Something went wrong. please try again");
            }
        }
}
?>
<div class="content mt-3">
    <?php echo warn_a('This is best tool to categorized your games. You can create category and Its can filter all games automatically. You have not need to worry about
    categorizing each games. Make sure to store category images under assets/img/.. folder. We have created few category by default.');?>
    <div class='col-md-8'>
        <div class="card">
            <div class="card-body">
                <?php
                $categories = $db->query("SELECT * FROM game_categories ORDER BY name ASC");
                if($categories && $categories->num_rows > 0) {
                while($data = $categories->fetch_assoc()) {
                $count_categories = $db->query("SELECT COUNT(*) FROM games WHERE Category LIKE '%$data[name]%'");
                $total_categories = $count_categories->fetch_row()[0];
                ?>
                <div class='border p-3 d-inline-block w-100 mb-2'>
                    <div class='col-lg-9 col-md-8 col-sm-7'><img src="/uploads/categories/<?=$data["slug"];?>.png" style="width:20px;">&nbsp;&nbsp;&nbsp;<?=$data["name"];?> &nbsp;&nbsp;<a class="text-danger" style="font-size:12px;" target="_blank" href="/category/<?php echo $data['slug']; ?>"><i class="fa fa-external-link-square"></i></a> <span class='bg-primary text-white p-1 ml-2 rounded-circle' style='font-size:10px'><?=$total_categories;?></span></div>
                    <div class='col-lg-3 col-md-4 col-sm-5'>
                        <a href="./?a=game-categories&b=edit&id=<?php echo $data['id']; ?>" title="Edit"><span class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</span></a> 
                        <a href="./?a=game-categories&b=delete&id=<?php echo $data['id']; ?>" title="Delete"><span class="badge badge-danger"><i class="fa fa-trash"></i> Delete</span></a>
                    </div>
                </div>
                <?php } } else { ?>
                <div>No records found</div>
                <?php } ?>
            </div>
            <div class="card-body">
            </div>
        </div>
    </div>
    <div class='col-md-4'>
        <div class="card">
            <div class="card-body">
                <h4 class='mb-3'>Add New Category</h4>
                <form action="" method="POST">
                    <div class='form-group'>
                        <input type='text' name='category_name' class='form-control' placeholder="Category Name"/>
                    </div>
                    <div class='form-group'>
                        <input type='text' name='description' class='form-control' placeholder="Description (Optional)"/>
                    </div>
                    <div class="form-group">
						<textarea class="cleditor" name="long_desc" placeholder="Long Description (Optional)"></textarea>
					</div>
                    <div class='form-group'>
                        <button type="submit" class="btn btn-primary btn-block" name="btn_submit" value="Add">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>