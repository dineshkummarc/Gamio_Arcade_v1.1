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
if($b == "add") {
?>
<br>
<br>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Blogs</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li>Add Blog</li>
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
    				$title = protect($_POST['title']);
    				$descrip = protect($_POST['descrip']);
    				$prefix = strtolower($title);
                    $prefix = str_replace("|","",$prefix);
                    $prefix = str_replace(",","",$prefix);
                    $prefix = str_replace("&","",$prefix);
                    $prefix = str_replace("?","",$prefix);
                    $prefix = str_replace("--","-",$prefix);
                    $prefix = str_replace("---","-",$prefix);
                    $prefix = str_replace("-"," ",$prefix);
                    $prefix = rtrim($prefix);
                    $prefix = str_replace(" ","-",$prefix);
    				$content = xss_clean($_POST['content']);
    				$check = $db->query("SELECT * FROM blogs WHERE prefix='$prefix'");
    				
    				$extensions = array('jpg','jpeg','png','svg');
                    $fileParts = explode('.', $_FILES['uploadFile']['name']);
                    $fileextension = end($fileParts);


                    $fileextension = strtolower($fileextension); 
                    $filesize = $_FILES['uploadFile']['size'];
                    $maxfilesize = '5242880'; // 5 MB
                    if(!empty($_FILES['uploadFile']['name'])) { 
                        $image_info = getimagesize($_FILES["uploadFile"]["tmp_name"]);
                        $image_width = $image_info[0];
                        $image_height = $image_info[1];
                    }
                                
    				if(empty($title) or empty($prefix) or empty($content)) { echo error_a("All fields are required."); }
    				elseif($check->num_rows>0) { echo error_a("This prefix is already used. Please choose another. "); }
    			    elseif(!empty($_FILES['uploadFile']['name']) && !in_array($fileextension,$extensions)) { 
                        echo error_a("Allowed image formats are jpg, png and svg."); 
                    } elseif(!empty($_FILES['uploadFile']['name']) && $filesize > $maxfilesize) {
                        echo error_a("Image file size is exceed the allowed 5MB.");  
                    } elseif(!empty($_FILES['uploadFile']['name']) && $image_width != "926") {
                        echo error_a("Image size must be 926x520px.");
                    } elseif(!empty($_FILES['uploadFile']['name']) && $image_height!= "520") {
                        echo error_a("Image size must be 926x520px."); }
    				else {
    					$page = $settings['url']."blog/".$prefix;
    					$link = '<a href="'.$page.'" target="_blank">'.$page.'</a>';
    					$time = time();
    					$filename='';
                        if(!empty($_FILES['uploadFile']['name'])) {
                            $filename = "post_".time().".".$fileextension;
                            $upload_file = 'uploads/blogs/'.$filename;
                            @move_uploaded_file($_FILES['uploadFile']['tmp_name'],'../'.$upload_file);
                        }
    					$insert = $db->query("INSERT blogs (title,prefix,content,created,image,descrip) VALUES ('$title','$prefix','$content','$time','$upload_file','$descrip')");
    					echo success_a("Blog was created successfully. Preview link: $link");
    				}	
    			}
    			?>
    			<form action="" method="POST" enctype="multipart/form-data">
    				<div class="form-group">
						<label>Title</label>
						<input type="text" class="form-control" name="title">
					</div>
					<div class="form-group">
						<label>Description</label>
						<input type="text" class="form-control" name="descrip">
					</div>
					<div class="form-group">
						<label>Content</label>
						<textarea class="cleditor" name="content"></textarea>
					</div>
					<div class="form-group">
                        <div class="form-control-wrap">
                            <label class="form-label">Upload image</label>
                            <input type="file" class="form-control" id="uploadFile" name="uploadFile">
                            <small>Allowed formats: .jpg .png .svg<br/>Size: 926x520px</small>
                            <div id="cm_hidden_doc_file" style="display:none;" class="card card-bordered card-preview">
                                <div class="card-inner">
                                    <center>
                                    <img id="preview_FS" src="">
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
    				<button type="submit" class="btn btn-primary" name="btn_add"><i class="fa fa-plus"></i> Add</button>
    			</form>		
            </div>
        </div>
    </div>
    <script src="/admin/assets/js/admin.js"></script>
<?php } elseif($b == "edit") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM blogs WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=blogs"); }
	$row = $query->fetch_assoc();
	?>
<br>
<br>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Blogs</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li>Edit blog</li>
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
    				$title = protect($_POST['title']);
    				$descrip = protect($_POST['descrip']);
    				$prefix = strtolower($title);
                    $prefix = str_replace("|","",$prefix);
                    $prefix = str_replace(",","",$prefix);
                    $prefix = str_replace("&","",$prefix);
                    $prefix = str_replace("?","",$prefix);
                    $prefix = str_replace("--","-",$prefix);
                    $prefix = str_replace("---","-",$prefix);
                    $prefix = str_replace("-"," ",$prefix);
                    $prefix = rtrim($prefix);
                    $prefix = str_replace(" ","-",$prefix);
    				$content = xss_clean($_POST['content']);
    				
    				$extensions = array('jpg','jpeg','png','svg');
                    $fileParts = explode('.', $_FILES['uploadFile']['name']);
                    $fileextension = end($fileParts);

                    $fileextension = strtolower($fileextension); 
                    $filesize = $_FILES['uploadFile']['size'];
                    $maxfilesize = '5242880'; // 5 MB
                    if(!empty($_FILES['uploadFile']['name'])) { 
                        $image_info = getimagesize($_FILES["uploadFile"]["tmp_name"]);
                        $image_width = $image_info[0];
                        $image_height = $image_info[1];
                    }
                                
    				$check = $db->query("SELECT * FROM blogs WHERE prefix='$prefix'");
    				if(empty($title) or empty($content)) { 
    				    echo error_a("All fields are required."); 
    				} elseif(!isValidUsername($prefix)) { 
    				    echo error_a("Please enter valid prefix."); 
    				} elseif(!empty($_FILES['uploadFile']['name']) && !in_array($fileextension,$extensions)) { 
                        echo error_a("Allowed image formats are jpg, png and svg."); 
                    } elseif(!empty($_FILES['uploadFile']['name']) && $filesize > $maxfilesize) {
                        echo error_a("Image file size is exceed the allowed 5MB.");  
                    } elseif(!empty($_FILES['uploadFile']['name']) && $image_width != "926") {
                        echo error_a("Image size must be 926x520px.");
                    } elseif(!empty($_FILES['uploadFile']['name']) && $image_height!= "520") {
                        echo error_a("Image size must be 926x520px.");
    				} else {
    					$page = $settings['url']."blog/".$prefix;
    					$link = '<a href="'.$page.'" target="_blank">'.$page.'</a>';
    					$time = time();
    					$filename='';
                        if(!empty($_FILES['uploadFile']['name'])) {
                            $filename = "post_".time().".".$fileextension;
                            $upload_file = 'uploads/blogs/'.$filename;
                            @move_uploaded_file($_FILES['uploadFile']['tmp_name'],'../'.$upload_file);
                            $update = $db->query("UPDATE blogs SET image='$upload_file',updated='$time' WHERE id='$row[id]'");
                        }
    					$update = $db->query("UPDATE blogs SET title='$title',descrip='$descrip',prefix='$prefix',content='$content',updated='$time' WHERE id='$row[id]'");
    					$query = $db->query("SELECT * FROM blogs WHERE id='$id'");
    					$row = $query->fetch_assoc();
    					echo success_a("Blog was updated successfully. Preview link: $link");
    				}	
    			}
    			?>
    			
    			<form action="" method="POST" enctype="multipart/form-data">
    				<div class="form-group">
    					<label>Title</label>
    					<input type="text" class="form-control" name="title" value="<?php echo $row['title']; ?>">
    				</div>
    				<div class="form-group">
    					<label>Prefix</label>
    					<input type="text" class="form-control" disabled value="<?php echo $row['prefix']; ?>">
    				</div>
    				<div class="form-group">
    					<label>Description</label>
    					<input type="text" class="form-control" name="descrip" value="<?php echo $row['descrip']; ?>">
    				</div>
    				<div class="form-group">
    					<label>Content</label>
    					<textarea class="cleditor" rows="15"  name="content"><?php echo $row['content']; ?></textarea>
    				</div>
    				<div class="form-group">
                        <div class="form-control-wrap">
                            <label class="form-label">Upload image</label>
                            <input type="file" class="form-control" id="uploadFile" name="uploadFile">
                            <small>Allowed formats: .jpg .png .svg<br/>Size: 926x520px</small>
                            <div id="cm_hidden_doc_file" style="display:none;" class="card card-bordered card-preview">
                                <div class="card-inner">
                                    <center>
                                    <img id="preview_FS" src="">
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
    			    <button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
    			</form>
    		</div>
    	</div>
    </div>
    <script src="/admin/assets/js/admin.js"></script>
	<?php
} elseif($b == "delete") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM blogs WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=blogs"); }
	$row = $query->fetch_assoc();
	?>
<br>
<br>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Blogs</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li>Delete blog</li>
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
    				$delete = $db->query("DELETE FROM blogs WHERE id='$row[id]'");
    				echo success_a("Page <b>$row[title]</b> was deleted.");
    			} else {
    				echo info("Are you sure you want to delete page <b>$row[title]</b>?");
    				echo '<a href="./?a=blogs&b=delete&id='.$row['id'].'&confirm=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
    					<a href="./?a=blogs" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
    			}
    			?>
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
                <h1>Blogs</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li><a href="./?a=blogs&b=add"><i class="fa fa-plus"></i> New</a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content mt-3">
   <div class="col-md-12">
		<div class="card">
            <div class="card-body">
                <table class="table table-striped">
                	<thead>
                		<tr>
                			<th width="30%">Title</th>
                			<th width="20%">Prefix</th>
                			<th width="20%">Created on</th>
                			<th width="20%">Updated on</th>
                			<th width="10%">Action</th>
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
        				$statement = "blogs";
        				$query = $db->query("SELECT * FROM {$statement} ORDER BY id LIMIT {$startpoint} , {$limit}");
        				if($query->num_rows>0) {
        					while($row = $query->fetch_assoc()) {
        				?>
        						<tr>
        							<td><?php echo $row['title']; ?></td>
        							<td><?php echo $row['prefix']; ?></td>
        							<td><?php if($row['created']) { echo '<span class="label label-default">'.date("d/m/Y H:i:s",$row['created']).'</span>'; } else { echo '-'; } ?></td>
        							<td><?php if($row['updated']) { echo '<span class="label label-default">'.date("d/m/Y H:i:s",$row['updated']).'</span>'; } else { echo '-'; } ?></td>
        							<td>
        								<a href="./?a=blogs&b=edit&id=<?php echo $row['id']; ?>" title="Edit"><span class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</span></a> 
        								<a href="./?a=blogs&b=delete&id=<?php echo $row['id']; ?>" title="Delete"><span class="badge badge-danger"><i class="fa fa-trash"></i> Delete</span></a>
        							</td>
        						</tr>
        				<?php } } else {
        					echo '<tr><td colspan="5">No have blogs yet. <a href="./?a=blogs&b=add">Click here</a> to add.</td></tr>';
        				}
        				?>
        			</tbody>
                </table>
        		<?php
        		$ver = "./?a=blogs";
        		if(admin_pagination($statement,$ver,$limit,$page)) {
        			echo admin_pagination($statement,$ver,$limit,$page);
        		}
        		?>
            </div>
        </div>
    </div>
<?php } ?>