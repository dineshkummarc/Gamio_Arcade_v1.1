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
if($b == "editor") {
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
					<h1>Ads.txt Editor</h1>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="page-header float-right">
				<div class="page-title">
					<ol class="breadcrumb text-right">
						<li></li>
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
                        $file = fopen("../ads.txt", "w") or die(error_a("Unable to open file!"));
                        // Write the new content to the file
                        fwrite($file, $new_content);
                        // Close the file
                        fclose($file);
                        echo success_a("File edited successfully!");
        			}
        			$file = fopen("../ads.txt", "r") or die("Unable to open file!");
					$filesize = filesize("../ads.txt");
                    $content = ($filesize > 0) ? fread($file, $filesize) : ''; // Check if filesize is greater than 0
                    fclose($file);
        			?>
        			<form action="" method="POST">
        				<div class="form-group">
						    <label for="new_content">Edit Ads.txt:</label><br>
							<textarea class="" id="new_content" name="new_content"><?=$content;?></textarea><br>
						</div>
        				<button type="submit" class="btn btn-primary" name="save"><i class="fa fa-check"></i> Save</button>
        			</form>		
                </div>
            </div>
    	</div>
	</div>
<?php
} elseif ($b =="add") {
?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Create Ad</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li></li>
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
    			if(isset($_POST['btn_add'])) {
    				$name = protect($_POST['name']);
    				$type = protect($_POST['type']);
    				$status = protect($_POST['status']);
    				$code = $_POST['code'];
    				if(empty($name) or empty($type) or empty($code)) { echo error_a("All fields are required."); }
    				else {
    					$insert = $db->query("INSERT INTO ads (name,type,status,code) VALUES ('$name','$type','$status','$code')");
                        if ($insert) {
                          echo success_a("Ad was created successfully.");
                        }
    				}
    			}
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
    			<form action="" method="POST">
    				<div class="form-group">
						<label>Title</label>
						<input type="text" class="form-control" name="name">
					</div>
					<div class="form-group">
						<label>Ad Size</label>
						<select class="form-control" name="type">
						    <option value="1">300 x 300</option>
						    <option value="2">728 x 90</option>
						    <option value="3">Popup Ads</option>
						</select>
					</div>
					<div class="form-group">
						<label>Ad Code</label>
						<textarea name="code"></textarea><br>
					</div>
					<div class="form-group">
						<label>Status</label>
						<select class="form-control" name="status">
						    <option value="1">Active</option>
						    <option value="0">Inactive</option>
						</select>
					</div>
    				<button type="submit" class="btn btn-primary btn-block" name="btn_add"><i class="fa fa-plus"></i> Add</button>
    			</form>		
            </div>
        </div>
    </div>
<?php
} elseif($b == "edit") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM ads WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=ads"); }
	$row = $query->fetch_assoc();
	?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Edit Ads</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li></li>
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
        		if(isset($_POST['btn_save'])) {
        			$name = protect($_POST['name']);
        			$type = protect($_POST['type']);
        			$code = $_POST['code'];
        			$status = protect($_POST['status']);
        			if(empty($name) or empty($type) or empty($code)) { echo error_a("All fields are required."); }
        			else {
        				$update = $db->query("UPDATE ads SET name='$name',type='$type',code='$code',status='$status' WHERE id='$row[id]'");
        				$query = $db->query("SELECT * FROM ads WHERE id='$id'");
        				$row = $query->fetch_assoc();
                      	if ($update) {
                          echo success_a("Ad was updated successfully.");
                        }
        				
        			}	
        		}
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
    			<form action="" method="POST">
    				<div class="form-group">
    					<label>Name</label>
    					<input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>">
    				</div>
    				<div class="form-group">
						<label>Ad Size</label>
						<select class="form-control" name="type">
						    <option value="1" <?php if ($row['type'] == "1") { echo "selected";} ?>>300 x 300</option>
						    <option value="2" <?php if ($row['type'] == "2") { echo "selected";} ?>>728 x 90</option>
						</select>
					</div>
					<div class="form-group">
						<label>Ad Code</label>
						<textarea name="code"><?php echo $row['code']; ?></textarea><br>
					</div>
					<div class="form-group">
						<label>Status</label>
						<select class="form-control" name="status">
						    <option value="1" <?php if ($row['status'] == "1") { echo "selected";} ?>>Active</option>
						    <option value="0" <?php if ($row['status'] == "0") { echo "selected";} ?>>Inactive</option>
						</select>
					</div>
    				<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
    			</form>
    		</div>
    	</div>
	</div>
<?php
} elseif($b == "delete") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM ads WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=ads"); }
	$row = $query->fetch_assoc();
	?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Delete Ads</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li>Want to delete this ad?</li>
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
    				$delete = $db->query("DELETE FROM ads WHERE id='$row[id]'");
    				echo success_a("Ad <b>$row[name]</b> was deleted.");
    			} else {
    				echo info_a("Are you sure you want to delete source <b>$row[name]</b>?");
    				echo '<a href="./?a=ads&b=delete&id='.$row['id'].'&confirm=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
    					<a href="./?a=ads" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
    			}
    			?>
    		</div>
	    </div>
	</div>
<?php } else { ?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Manage Ads</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li><a href="./?a=ads&b=add"><i class="fa fa-plus"></i> Create Ads</a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content mt-3">
   	<div class="col-md-12">
       <?php echo info_a('Run any banners ads you want. Create Ads, write codes and select size of the ads.'); ?>
        <?php echo warn_a('Want to add code on header? Go to Content > Add code in header. For Google Adsense you need to add code there.'); ?>
		<div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
            			<tr>
            				<th>Name</th>
            				<th>Status</th>
                          	<th>Action</th>
            			</tr>
            		</thead>
            		<tbody>
            			<?php
            			$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
            			$limit = 20;
            			$startpoint = ($page * $limit) - $limit;
            			if($page == 1) {
            				$i = 1;
            			} else {
            				$i = $page * $limit;
            			}
            			$statement = "ads";
            			$query = $db->query("SELECT * FROM {$statement} LIMIT {$startpoint} , {$limit}");
            			if($query->num_rows>0) {
            				while($row = $query->fetch_assoc()) {
            					?>
            					<tr>
            						<td><?php echo $row['name']; ?></td>
            						<td>
            						    <?php 
            						    if ($row['status'] == "1") { 
            						    echo "<span class='badge badge-success'>Active</span>";
            						    } else {
            						    echo "<span class='badge badge-danger'>Inactive</span>";
            						    } 
            						    ?>
            						</td>
            						<td>
            							<a href="./?a=ads&b=edit&id=<?php echo $row['id']; ?>" title="Edit"><span class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</span></a> 
            							<a href="./?a=ads&b=delete&id=<?php echo $row['id']; ?>" title="Delete"><span class="badge badge-danger"><i class="fa fa-trash"></i> Delete</span></a>
            						</td>
            					</tr>
            					<?php
            				}
            			} else {
            				echo '<tr><td colspan="5">No have ads yet. <a href="./?a=ads&b=add">Click here</a> to add.</td></tr>';
            			}
            			?>
            		</tbody>
            	</table>
        		<?php
        		$ver = "./?a=ads";
        		if(admin_pagination($statement,$ver,$limit,$page)) {
        			echo admin_pagination($statement,$ver,$limit,$page);
        		}
        		?>
    	    </div>
    	</div>
	</div>
</div>
<?php } ?>