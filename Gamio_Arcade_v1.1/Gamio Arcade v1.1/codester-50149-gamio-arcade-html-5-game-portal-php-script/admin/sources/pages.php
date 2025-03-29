<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
?>
<?php
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
                        <h1>Pages</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
							<li>Add page</li>
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
                				$prefix = protect($_POST['prefix']);
                				$content = addslashes($_POST['content']);
                				$check = $db->query("SELECT * FROM pages WHERE prefix='$prefix'");
                				if(empty($title) or empty($prefix) or empty($content)) { echo error_a("All fields are required."); }
                				elseif(!isValidUsername($prefix)) { echo error_a("Please enter valid prefix."); }
                				elseif($check->num_rows>0) { echo error_a("This prefix is already used. Please choose another. "); }
                				else {
                					$page = $settings['url']."page/".$prefix;
                					$link = '<a href="'.$page.'" target="_blank">'.$page.'</a>';
                					$time = time();
                					$insert = $db->query("INSERT pages (title,prefix,content,created,descrip) VALUES ('$title','$prefix','$content','$time','$descrip')");
                					echo success_a("Page was created successfully. Preview link: $link");
                				}	
                			}
                			?>
			
                			<form action="" method="POST">
                				<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="title">
								</div>
								<div class="form-group">
									<label>Prefix</label>
									<div class="input-group">
									  <span class="input-group-addon"><?php echo $settings['url']; ?>page/</span>
									  <input type="text" class="form-control" name="prefix">
									</div>
									<small>Use latin characters and symbols - and _. Do not make spaces between words.</small>
								</div>
								<div class="form-group">
									<label>Description</label>
									<input type="text" class="form-control" name="descrip">
								</div>
								<div class="form-group">
									<label>Content</label>
									<textarea class="cleditor" rows="15" name="content"></textarea>
								</div>
                				<button type="submit" class="btn btn-primary" name="btn_add"><i class="fa fa-plus"></i> Add</button>
                			</form>		
		                </div>
	                </div>
	            </div>
	<?php
} elseif($b == "edit") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM pages WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=pages"); }
	$row = $query->fetch_assoc();
	?>
	<br>
		<br>
		<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Pages</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
							<li>Edit page</li>
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
				$prefix = protect($_POST['prefix']);
				$content = addslashes($_POST['content']);
				$check = $db->query("SELECT * FROM pages WHERE prefix='$prefix'");
				if(empty($title) or empty($prefix) or empty($content)) { echo error_a("All fields are required."); }
				elseif(!isValidUsername($prefix)) { echo error_a("Please enter valid prefix."); }
				elseif($row['prefix'] !== $prefix && $check->num_rows>0) { echo error_a("This prefix is already used. Please choose another. "); }
				else {
					$page = $settings['url']."page/".$prefix;
					$link = '<a href="'.$page.'" target="_blank">'.$page.'</a>';
					$time = time();
					$update = $db->query("UPDATE pages SET title='$title',prefix='$prefix',content='$content',updated='$time',descrip='$descrip' WHERE id='$row[id]'");
					$query = $db->query("SELECT * FROM pages WHERE id='$id'");
					$row = $query->fetch_assoc();
					echo success_a("Page was updated successfully. Preview link: $link");
				}	
			}
			?>
			
			<form action="" method="POST">
				<div class="form-group">
					<label>Title</label>
					<input type="text" class="form-control" name="title" value="<?php echo $row['title']; ?>">
				</div>
				<div class="form-group">
					<label>Prefix</label>
					<div class="input-group">
					  <span class="input-group-addon"><?php echo $settings['url']; ?>page/</span>
					  <input type="text" class="form-control" name="prefix" value="<?php echo $row['prefix']; ?>">
					</div>
					<small>Use latin characters and symbols - and _. Do not make spaces between words.</small>
				</div>
				<div class="form-group">
					<label>Description</label>
					<input type="text" class="form-control" name="descrip" value="<?php echo $row['descrip']; ?>">
				</div>
				<div class="form-group">
					<label>Content</label>
					<textarea class="cleditor" rows="15" name="content"><?php echo $row['content']; ?></textarea>
				</div>
				<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
			</form>
		</div>
	</div>
	</div>
	<?php
} elseif($b == "delete") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM pages WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=pages"); }
	$row = $query->fetch_assoc();
	?>
	<br>
		<br>
		<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Pages</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
							<li>Delete page</li>
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
				$delete = $db->query("DELETE FROM pages WHERE id='$row[id]'");
				echo success_a("Page <b>$row[title]</b> was deleted.");
			} else {
				echo info_a("Are you sure you want to delete page <b>$row[title]</b>?");
				echo '<a href="./?a=pages&b=delete&id='.$row['id'].'&confirm=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
					<a href="./?a=pages" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
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
                        <h1>Pages</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
							<li><a href="./?a=pages&b=add"><i class="fa fa-plus"></i> Add</a></li>
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
				$statement = "pages";
				$query = $db->query("SELECT * FROM {$statement} ORDER BY id LIMIT {$startpoint} , {$limit}");
				if($query->num_rows>0) {
					while($row = $query->fetch_assoc()) {
						?>
						<tr>
							<td><?php echo $row['title']; ?></td>
							<td><?php echo $row['prefix']; ?></td>
							<td><?php if($row['created']) { echo '<span class="label label-default">'.date("d/m/Y H:i:s".$row['created']).'</span>'; } else { echo '-'; } ?></td>
							<td><?php if($row['updated']) { echo '<span class="label label-default">'.date("d/m/Y H:i:s".$row['updated']).'</span>'; } else { echo '-'; } ?></td>
							<td>
								<a href="./?a=pages&b=edit&id=<?php echo $row['id']; ?>" title="Edit"><span class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</span></a> 
								<a href="./?a=pages&b=delete&id=<?php echo $row['id']; ?>" title="Delete"><span class="badge badge-danger"><i class="fa fa-trash"></i> Delete</span></a>
							</td>
						</tr>
						<?php
					}
				} else {
					echo '<tr><td colspan="5">No have pages yet. <a href="./?a=pages&b=add">Click here</a> to add.</td></tr>';
				}
				?>
			</tbody>
		</table>
		<?php
		$ver = "./?a=pages";
		if(admin_pagination($statement,$ver,$limit,$page)) {
			echo admin_pagination($statement,$ver,$limit,$page);
		}
		?>
	</div>
	</div>
</div>
<?php
}
?>