<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
?>
<br>
<br>
    
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Website Branding</h1>
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
    <div class="col-md">
		<div class="row">
			<div class="col-md card">
				<div class="card-body">
					<?php 
					if(isset($_POST['logo'])) {
						$extensions = array('jpg','jpeg','png'); 
						if(isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != '') {
							$fileParts = explode('.', $_FILES['uploadFile']['name']);
							$fileextension = end($fileParts);
							$fileextension = strtolower($fileextension); 
							if(empty($_FILES['uploadFile']['name'])) { echo error_a("Please select a file."); }
							elseif(!in_array($fileextension,$extensions)) { echo error_a("Allowed extensions: jpg and png."); }
							else {
								$filename = randomHash(10)."_".$_FILES['uploadFile']['name'];
								$sql_upload_path = 'uploads/logo/'.$filename;
								$upload_path = '../uploads/logo/'.$filename;
								@move_uploaded_file($_FILES['uploadFile']['tmp_name'],$upload_path);
								$update = $db->query("UPDATE settings SET logo='$sql_upload_path'");
								$settingsQuery = $db->query("SELECT * FROM settings ORDER BY id DESC LIMIT 1");
								$settings = $settingsQuery->fetch_assoc();
								echo success_a("Your logo was updated successfully.");
							}
						} else {
							echo success_a("File Extension or Name or File is not supported.");
						}
					}
					?>
					<div class="alert alert-secondary">
						<b>Current Dark Logo:</b><br/><br/>
						<?php if($settings['logo']) { ?>
							<img src="<?= $settings['url'].$settings['logo'] ?>">
						<?php } else { ?>
							<img src="<?= $settings['url'] ?>theme/img/logo-main.png" style="width:70px;">
						<?php } ?>
					</div>
					<form action="" method="POST" enctype="multipart/form-data">
						<div class="form-group">
							<label>Select Logo</label>
							<input type="file" class="form-control" name="uploadFile">
						</div>
						<button type="submit" class="btn btn-primary" name="logo"><i class="fa fa-upload"></i> Upload</button>
					</form>
				</div>
			</div>
			<div class="col-md card">
				<div class="card-body">
					<?php 
					if(isset($_POST['favicon'])) {
						$extensions = array('jpg','jpeg','png'); 

						if(isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != '') {
							$fileParts = explode('.', $_FILES['uploadFile']['name']);
							$fileextension = end($fileParts);
							$fileextension = strtolower($fileextension); 
							if(empty($_FILES['uploadFile']['name'])) { echo error_a("Please select a file."); }
							elseif(!in_array($fileextension,$extensions)) { echo error_a("Allowed extensions: jpg and png."); }
							else {
								$filename = randomHash(10)."_".$_FILES['uploadFile']['name'];
								$sql_upload_path = 'uploads/logo/'.$filename;
								$upload_path = '../uploads/logo/'.$filename;
								@move_uploaded_file($_FILES['uploadFile']['tmp_name'],$upload_path);
								$update = $db->query("UPDATE settings SET favicon='$sql_upload_path'");
								$settingsQuery = $db->query("SELECT * FROM settings ORDER BY id DESC LIMIT 1");
								$settings = $settingsQuery->fetch_assoc();
								echo success_a("Your favicon was updated successfully.");
							}
						} else {
							echo success_a("File Extension or Name or File is not supported.");
						}
					}
					?>
					<div class="alert alert-secondary">
						<b>Current Favicon:</b><br/><br/>
						<?php if($settings['favicon']) { ?>
							<img src="<?= $settings['url'].$settings['favicon'] ?>">
						<?php } else { ?>
							<img src="<?= $settings['url'] ?>theme/img/favicon.png" style="width:35px;">
						<?php } ?>
					</div>
					<form action="" method="POST" enctype="multipart/form-data">
						<div class="form-group">
							<label>Select Favicon</label>
							<input type="file" class="form-control" name="uploadFile">
						</div>
						<button type="submit" class="btn btn-primary" name="favicon"><i class="fa fa-upload"></i> Upload</button>
					</form>
				</div>
			</div>
			<div class="col-md card">
				<div class="card-body">
					<?php 
					if(isset($_POST['white_logo'])) {
						$extensions = array('jpg','jpeg','png'); 
						if(isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != '') {
							$fileParts = explode('.', $_FILES['uploadFile']['name']);
							$fileextension = end($fileParts);
							$fileextension = strtolower($fileextension); 
							if(empty($_FILES['uploadFile']['name'])) { echo error_a("Please select a file."); }
							elseif(!in_array($fileextension,$extensions)) { echo error_a("Allowed extensions: jpg and png."); }
							else {
								$filename = randomHash(10)."_".$_FILES['uploadFile']['name'];
								$sql_upload_path = 'uploads/logo/'.$filename;
								$upload_path = '../uploads/logo/'.$filename;
								@move_uploaded_file($_FILES['uploadFile']['tmp_name'],$upload_path);
								$update = $db->query("UPDATE settings SET white_logo='$sql_upload_path'");
								$settingsQuery = $db->query("SELECT * FROM settings ORDER BY id DESC LIMIT 1");
								$settings = $settingsQuery->fetch_assoc();
								echo success_a("Your White Logo was updated successfully.");
							}
						} else {
							echo success_a("File Extension or Name or File is not supported.");
						}
					}
					?>
									
					<div class="alert alert-secondary">
						<b>Current White Logo:</b><br/><br/>
						<?php if($settings['white_logo']) { ?>
							<img src="<?= $settings['url'].$settings['white_logo'] ?>">
						<?php } else { ?>
							<img src="<?= $settings['url'] ?>theme/img/logo-white.png" style="width:70px;">
						<?php } ?>
					</div>
					<form action="" method="POST" enctype="multipart/form-data">
						<div class="form-group">
							<label>Select White Logo</label>
							<input type="file" class="form-control" name="uploadFile">
						</div>
						<button type="submit" class="btn btn-primary" name="white_logo"><i class="fa fa-upload"></i> Upload</button>
					</form>
				</div>
			</div>
		</div>
    </div>