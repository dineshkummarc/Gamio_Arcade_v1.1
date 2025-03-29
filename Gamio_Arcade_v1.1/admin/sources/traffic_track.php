<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(isset($_GET['b'])) {
    $b = protect($_GET['b']);
} else {
    $b = 0;
}

if($b == "add") {?>
<div class="breadcrumbs">
	<div class="col-sm-4">
		<div class="page-header float-left">
			<div class="page-title">
				<h1>Traffic Source</h1>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="page-header float-right">
			<div class="page-title">
				<ol class="breadcrumb text-right">
					<li>Add traffic sources to track!</li>
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
					$source_name = protect($_POST['source_name']);
					$check = $db->query("SELECT * FROM traffic_sources WHERE source_name='$source_name'");
					if(empty($title) or empty($source_name)) { echo error_a("All fields are required."); }
					elseif(!isValidUsername($source_name)) { echo error_a("Please enter valid prefix."); }
					elseif($check->num_rows>0) { echo error_a("This prefix is already used. Please choose another. "); }
					else {
						$page = $settings['url']."sources/".$source_name;
						$link = '<a href="'.$page.'" target="_blank">'.$page.'</a>';
						$time = time();
						$insert = $db->query("INSERT INTO traffic_sources (title,source_name,date) VALUES ('$title','$source_name','$time')");
						
						if ($insert) {
							echo success_a("Tracker was created successfully. Preview link: $link");
						}
						
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
							<span class="input-group-addon"><?php echo $settings['url']; ?>?sources=</span>
							<input type="text" class="form-control" name="source_name">
						</div>
						<small>Use latin characters and symbols - and _. Do not make spaces between words.</small>
					</div>
					<button type="submit" class="btn btn-primary" name="btn_add"><i class="fa fa-plus"></i> Add</button>
				</form>		
			</div>
		</div>
	</div>
</div>
<?php } elseif ($b == "edit") {
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM traffic_sources WHERE id='$id'");
if($query->num_rows==0) { header("Location: ./?a=traffic_track"); }
$row = $query->fetch_assoc();
?>
	
<div class="breadcrumbs">
	<div class="col-sm-4">
		<div class="page-header float-left">
			<div class="page-title">
				<h1>Trafic Source</h1>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="page-header float-right">
			<div class="page-title">
				<ol class="breadcrumb text-right">
					<li>Edit /Update your config</li>
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
					$source_name = protect($_POST['source_name']);
					$check = $db->query("SELECT * FROM traffic_sources WHERE source_name='$source_name'");
					if(empty($title) or empty($source_name)) { echo error_a("All fields are required."); }
					elseif(!isValidUsername($source_name)) { echo error_a("Please enter valid source name."); }
					elseif($row['source_name'] !== $source_name && $check->num_rows>0) { echo error_a("This source name is already used. Please choose another. "); }
					else {
						$page = $settings['url']."sources/".$source_name;
						$link = '<a href="'.$page.'" target="_blank">'.$page.'</a>';
						$time = time();
						$update = $db->query("UPDATE traffic_sources SET title='$title',source_name='$source_name' WHERE id='$row[id]'");
						$query = $db->query("SELECT * FROM traffic_sources WHERE id='$id'");
						$row = $query->fetch_assoc();
						if ($update) {
							echo success_a("Page was updated successfully. Preview link: $link");
						}
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
							<span class="input-group-addon"><?php echo $settings['url']; ?>?sources=</span>
							<input type="text" class="form-control" name="source_name" value="<?php echo $row['source_name']; ?>">
						</div>
						<small>Use latin characters and symbols - and _. Do not make spaces between words.</small>
					</div>
					<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
				</form>
			</div>
		</div>
	</div>
</div>
	<?php
} elseif($b == "delete") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM traffic_sources WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=traffic_track"); }
	$row = $query->fetch_assoc();
	?>
	
		<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Trafiic Sources</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
							<li>Want to delete this sources?</li>
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
				$delete = $db->query("DELETE FROM traffic_sources WHERE id='$row[id]'");
				echo success_a("Source <b>$row[title]</b> was deleted.");
			} else {
				echo info_a("Are you sure you want to delete source <b>$row[title]</b>?");
				echo '<a href="./?a=traffic_track&b=delete&id='.$row['id'].'&confirm=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
					<a href="./?a=traffic_track" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
			}
			?>
		</div>
	</div>
	</div>
	<?php
} else {
?>

		<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Tracking Leads</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
							<li><a href="./?a=traffic_track&b=add"><i class="fa fa-plus"></i> Add Source</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content mt-3">
<?php echo info_a('This tool is very important for any CMS. Every one want to track there traffic. This will help you track your visits and traffic with there source. For Example,
            You are running promotion on Facebook, you have created a source url below and copy that link and drive traffic from that url. Once any one visit with that you will see the states below.'); ?>
           <div class="col-md-12">
					<div class="card">
                        <div class="card-body">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Title</th>
					<th>Prefix</th>
                  	<th>Visits</th>
					<th>Created on</th>
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
				$statement = "traffic_sources";
				$query = $db->query("SELECT * FROM {$statement} ORDER BY today DESC LIMIT {$startpoint} , {$limit}");
				if($query->num_rows>0) {
					while($row = $query->fetch_assoc()) {
						?>
						<tr>
							<td><?php echo $row['title']; ?></td>
							<td><?php echo $row['source_name']; ?></td>
							<td>Life time : <?php if ($row['lifetime'] > 0) { ?> <?=$row['lifetime']?> <?php } else { ?> 0 <?php } ?></td>
							<td><?php if($row['date']) {?><span class="label label-default"><?=date("d/m/Y H:i:s", $row['date']);?><?php } else { echo '-'; } ?></td>
							<td>
								<a href="./?a=traffic_track&b=edit&id=<?php echo $row['id']; ?>" title="Edit"><span class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</span></a> 
								<a href="./?a=traffic_track&b=delete&id=<?php echo $row['id']; ?>" title="Delete"><span class="badge badge-danger"><i class="fa fa-trash"></i> Delete</span></a>
							</td>
						</tr>
						<?php
					}
				} else {
					echo '<tr><td colspan="5">No have sources yet. <a href="./?a=traffic_track&b=add">Click here</a> to add.</td></tr>';
				}
				?>
			</tbody>
		</table>
		<?php
		$ver = "./?a=traffic_track";
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