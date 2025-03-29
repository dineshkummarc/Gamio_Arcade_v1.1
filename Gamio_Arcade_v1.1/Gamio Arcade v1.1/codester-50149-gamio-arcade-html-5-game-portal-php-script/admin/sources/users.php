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

if($b == "edit") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM users WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=users"); }
	$row = $query->fetch_assoc();
?>
<br>
<br>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Users</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li>Edit user</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content mt-3">
    <div class="col-md-12">
    <?php
    if(isset($_POST['btn_save'])) {
        $FormBTN = protect($_POST['btn_save']);
    } else {
        $FormBTN = "0";
    }
    if($FormBTN == "profile") {
        $full_name = protect($_POST['full_name']);
        $email = protect($_POST['email']);
        $country = protect($_POST['country']);
        $status = protect($_POST['status']);
        $CheckEmail = $db->query("SELECT * FROM users WHERE email='$email'");
        if(empty($full_name) or empty($email) or empty($country)) {
            echo error_a("All fields are required.");
        } elseif(!isValidEmail($email)) {
            echo error_a("Please enter valid email address.");
        } elseif($row['email'] !== $email && $CheckEmail->num_rows>0) { 
            echo error_a("This email address is already used.");
        } else {
            $update = $db->query("UPDATE users SET full_name='$full_name',email='$email',country='$country' WHERE id='$row[id]'");
            if(!empty($_POST['newpass'])) {
                $password = protect($_POST['newpass']);
                $password = password_hash($password, PASSWORD_DEFAULT);
                $update = $db->query("UPDATE users SET password='$password' WHERE id='$row[id]'");
            }
            $query = $db->query("SELECT * FROM users WHERE id='$row[id]'");
            $row = $query->fetch_assoc();
            echo success_a("Profile changes was saved successfully.");
        }
    }
	?>
    </div>
    <div class="col-md-8">
		<div class="card">
            <div class="card-body">
                <h3>Profile Information</h3>
                <hr/>

                <form action="" method="POST">
                <div class="form-group">
                        <label>Full name</label>
                        <input type="text" class="form-control" name="full_name" value="<?=$row['full_name']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="text" class="form-control" name="email" value="<?=$row['email']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <select class="form-control" name="country">
                        <?php
                        $countries = getCountries();
                        foreach($countries as $code=>$country) {
                          $sel='';
                          if($row['country'] == $country) { $sel = 'selected'; }
                          echo '<option value="'.$country.'" '.$sel.'>'.$country.'</option>';
                        }
                        ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>New password</label>
                        <input type="text" class="form-control" name="newpass" placeholder="Leave empty if do not want to change it.">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="1" <?php if($row['status'] == "1") { echo 'selected'; } ?>>Active</option>
                            <option value="11" <?php if($row['status'] == "11") { echo 'selected'; } ?>>Blocked</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="btn_save" value="profile"><i class="fa fa-check"></i> Save changes</button>
                </form>
            </div>
        </div>
    </div>
<?php
} elseif($b == "delete") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM users WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=users"); }
	$row = $query->fetch_assoc();
	?>
	<br>
		<br>
		<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Admin</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
							<li>Delete user</li>
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
			    if ($_SESSION['admin_uid'] == $id) {
			        echo error_a("You can not delete yourself.");
			    } else {
			        $delete = $db->query("DELETE FROM users WHERE id='$row[id]'");
    				$delete = $db->query("DELETE FROM users_logs WHERE uid='$row[id]'");
                    echo success_a("User <b>$row[email]</b> was deleted.");
			    }
			} else {
				echo info_a("Are you sure you want to delete user <b>$row[email]</b>?<br/><small>Once this action is completed, user information will be deleted from the database and will not be restored.</small>");
				echo '<a href="./?a=users&b=delete&id='.$row['id'].'&confirm=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
					<a href="./?a=users" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
			}
			?>
		</div>
	</div>
	</div>
	<?php
} else {
?>
<br>
<br>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Admins</h1>
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
<div class="content mt-3">
    <div class="col-md-12">
		<div class="card">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-2" style="padding:10px;">
                            <input type="text" class="form-control" name="first_name" placeholder="First name" value="<?php if(isset($_POST['first_name'])) { echo $_POST['first_name']; } ?>">
                        </div>
                        <div class="col-md-2" style="padding:10px;">
                            <input type="text" class="form-control" name="last_name" placeholder="Last name" value="<?php if(isset($_POST['last_name'])) { echo $_POST['last_name']; } ?>">
                        </div>
                        <div class="col-md-3" style="padding:10px;">
                            <input type="text" class="form-control" name="email" placeholder="Email address" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } ?>">
                        </div>
                        <div class="col-md-2" style="padding:10px;">
                            <select class="form-control" name="country">
                                <option value="">Country</option>
                            <option></option>
                            <?php
                            $countries = getCountries();
                            foreach($countries as $code=>$country) {
                                $sel='';
                                if(isset($_POST['country'])) { if($_POST['country'] == $country) { $sel = 'selected'; } }
                                echo '<option value="'.$country.'" '.$sel.'>'.$country.'</option>';
                            }
                            ?>
                            </select>
                        </div>
                        <div class="col-md-3" style="padding:10px;">
                            <button type="submit" class="btn btn-primary btn-block" name="btn_search" value="users">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
		<div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $searching=0;
                        if(isset($_POST['btn_search'])) {
                            $FormBTN = protect($_POST['btn_search']);
                        } else {
                            $FormBTN = "0";
                        }
                        if($FormBTN == "users") {
                            $searching=1;
                            $search_query = array();
                            $s_first_name = protect($_POST['first_name']);
                            if(!empty($s_first_name)) { $search_query[] = "first_name='$s_first_name'"; }
                            $s_last_name = protect($_POST['last_name']);
                            if(!empty($s_last_name)) { $search_query[] = "last_name='$s_last_name'"; }
                            $s_email = protect($_POST['email']);
                            if(!empty($s_email)) { $search_query[] = "email='$s_email'"; }
                            $s_country = protect($_POST['country']);
                            if(!empty($s_country)) { $search_query[] = "country='$s_country'"; }
                            $p_query = implode(" and ",$search_query);
                        }
                        $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                        $limit = 20;
                        $startpoint = ($page * $limit) - $limit;
                        if($page == 1) {
                            $i = 1;
                        } else {
                            $i = $page * $limit;
                        }
                        $statement = "users";
                        if($searching==1) {
                            if(empty($p_query)) {
                                $qry = 'empty query';
                            }
                            $query = $db->query("SELECT * FROM {$statement} WHERE $p_query ORDER BY id");
                        } else {
                            $query = $db->query("SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
                        }
                        if($query->num_rows>0) {
                            while($row = $query->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo $row['email']; ?></td>
                                    <td>
                                        <a href="./?a=users&b=edit&id=<?php echo $row['id']; ?>" title="Edit"><span class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</span></a> 
                                        <a href="./?a=users&b=delete&id=<?php echo $row['id']; ?>" title="Delete"><span class="badge badge-danger"><i class="fa fa-trash"></i> Delete</span></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            if($searching == "1") {
                                echo '<tr><td colspan="5">No found results.</td></tr>';
                            } else {
                                echo '<tr><td colspan="5">No have users yet.</td></tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                if($searching == "0") {
                    $ver = "./?a=users";
                    if(admin_pagination($statement,$ver,$limit,$page)) {
                        echo admin_pagination($statement,$ver,$limit,$page);
                    }
                }
                ?>
            </div>
        </div>
    </div>
<?php
}
?>