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
                <h1>FAQs</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
    				<li>Add faq</li>
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
    				$question = protect($_POST['question']);
    				$answer = addslashes($_POST['answer']);
    				$check = $db->query("SELECT * FROM faqs WHERE question='$question'");
    				if(empty($question) or empty($answer)) { echo error_a("All fields are required."); }
    				elseif($check->num_rows>0) { echo error_a("This question is already added."); }
    				else {
    					$time = time();
    					$insert = $db->query("INSERT faqs (question,answer,created) VALUES ('$question','$answer','$time')");
    					echo success_a("Faq was created successfully.");
    				}	
    			}
    			?>

    			<form action="" method="POST">
    				<div class="form-group">
						<label>Question</label>
						<input type="text" class="form-control" name="question">
						<small>There is no need to add "?" sign.</small>
					</div>
					<div class="form-group">
						<label>Answer</label>
						<textarea class="form-control" rows="5" name="answer"></textarea>
					</div>
    				<button type="submit" class="btn btn-primary" name="btn_add"><i class="fa fa-plus"></i> Add</button>
    			</form>		
            </div>
        </div>
    </div>
</div>
	<?php
} elseif($b == "edit") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM faqs WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=faqs"); }
	$row = $query->fetch_assoc();
	?>
<br>
<br>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>FAQs</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li>Edit Faq</li>
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
				$question = protect($_POST['question']);
				$answer = addslashes($_POST['answer']);
				if(empty($question) or empty($answer)) { echo error_a("All fields are required."); }
				else {
					$time = time();
					$update = $db->query("UPDATE faqs SET question='$question',answer='$answer',updated='$time' WHERE id='$row[id]'");
					$query = $db->query("SELECT * FROM faqs WHERE id='$id'");
					$row = $query->fetch_assoc();
					echo success_a("Question was updated successfully.");
				}	
			}
			?>
			
			<form action="" method="POST">
				<div class="form-group">
					<label>Question</label>
					<input type="text" class="form-control" name="question" value="<?=$row['question']; ?>">
				</div>
				<div class="form-group">
					<label>Answer</label>
					<textarea class="form-control" rows="5" name="answer"><?=$row['answer']; ?></textarea>
				</div>
				<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
			</form>
		</div>
	</div>
	</div>
	<?php
} elseif($b == "delete") {
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM faqs WHERE id='$id'");
if($query->num_rows==0) { header("Location: ./?a=faqs"); }
$row = $query->fetch_assoc();
?>
<br>
<br>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>FAQs</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li>Delete Faq</li>
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
    				$delete = $db->query("DELETE FROM faqs WHERE id='$row[id]'");
    				echo success_a("Question: <b>$row[question]</b> was deleted.");
    			} else {
    				echo info_a("Are you sure you want to delete question <b>$row[question]</b>?");
    				echo '<a href="./?a=faqs&b=delete&id='.$row['id'].'&confirm=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
    					<a href="./?a=faqs" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
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
                <h1>FAQs</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li><a href="./?a=faqs&b=add"><i class="fa fa-plus"></i> Add</a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content mt-3">
    <div class="col-md-12">
        <?=info_a("It is recommended to add 4 FAQs.");?>
		<div class="card">
            <div class="card-body">
        		<table class="table table-striped">
        			<thead>
        				<tr>
        					<th width="50%">Question</th>
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
        				$statement = "faqs";
        				$query = $db->query("SELECT * FROM {$statement} ORDER BY id LIMIT {$startpoint} , {$limit}");
        				if($query->num_rows>0) {
        					while($row = $query->fetch_assoc()) {
        						?>
        						<tr>
        							<td><?php echo $row['question']; ?></td>
        							<td><?php if($row['created']) { echo '<span class="label label-default">'.date("d/m/Y H:i:s",$row['created']).'</span>'; } else { echo '-'; } ?></td>
        							<td><?php if($row['updated']) { echo '<span class="label label-default">'.date("d/m/Y H:i:s",$row['updated']).'</span>'; } else { echo '-'; } ?></td>
        							<td>
        								<a href="./?a=faqs&b=edit&id=<?php echo $row['id']; ?>" title="Edit"><span class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</span></a> 
        								<a href="./?a=faqs&b=delete&id=<?php echo $row['id']; ?>" title="Delete"><span class="badge badge-danger"><i class="fa fa-trash"></i> Delete</span></a>
        							</td>
        						</tr>
        						<?php
        					}
        				} else {
        					echo '<tr><td colspan="5">No have faqs yet. <a href="./?a=faqs&b=add">Click here</a> to add.</td></tr>';
        				}
        				?>
        			</tbody>
        		</table>
        		<?php
        		$ver = "./?a=faqs";
        		if(admin_pagination($statement,$ver,$limit,$page)) {
        			echo admin_pagination($statement,$ver,$limit,$page);
        		}
        		?>
    	    </div>
	    </div>
    </div>
</div>
<?php } ?>