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
                <h1>Web Settings</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					
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
        			$description = protect($_POST['description']);
        			$keywords = protect($_POST['keywords']);
        			$name = protect($_POST['name']);
        			$url = protect($_POST['url']);
        			$infoemail = protect($_POST['infoemail']);
        			$supportemail = protect($_POST['supportemail']);
        			$default_language = protect($_POST['default_language']);
        			$gamepix_id = protect($_POST['gamepix_id']);
        			
        			$instagram = protect($_POST['instagram']);
        			$facebook = protect($_POST['facebook']);
        			$twitter = protect($_POST['twitter']);
        			$youtube = protect($_POST['youtube']);
        			
        			if(isset($_POST['enable_recaptcha'])) { $enable_recaptcha = 1; } else { $enable_recaptcha = '0'; }
                    $recaptcha_publickey = protect($_POST['recaptcha_publickey']);
                    $recaptcha_privatekey = protect($_POST['recaptcha_privatekey']);
        			if(empty($title) or empty($description) or empty($keywords) or empty($default_language) or empty($name) or empty($url) or empty($infoemail) or empty($supportemail)) {
        				echo error_a("All fields are required."); 
        			} elseif(!isValidURL($url)) { 
        				echo error_a("Please enter valid site url address.");
        			} elseif(!isValidEmail($infoemail)) { 
        				echo error_a("Please enter valid info email address.");
        			} elseif(!isValidEmail($supportemail)) { 
        				echo error_a("Please enter valid support email address.");
        			} elseif($enable_recaptcha == "1" && empty($recaptcha_publickey)) {
                        echo error_a("Please enter a reCaptcha public key.");
                    } elseif($enable_recaptcha == "1" && empty($recaptcha_privatekey)) {
                        echo error_a("Please enter a reCaptcha private key.");
        			} else {
        				$update = $db->query("UPDATE settings SET enable_recaptcha='$enable_recaptcha',recaptcha_publickey='$recaptcha_publickey',recaptcha_privatekey='$recaptcha_privatekey',title='$title',description='$description',keywords='$keywords',default_language='$default_language',name='$name',url='$url',infoemail='$infoemail',supportemail='$supportemail',instagram='$instagram',facebook='$facebook',twitter='$twitter',youtube='$youtube'");
        				if (!empty($gamepix_id)) {
        				    $updatess = $db->query("UPDATE settings SET gamepix_id='$gamepix_id'");
        				} else {
        				    $updatess = $db->query("UPDATE settings SET gamepix_id=''");
        				}
        				$settingsQuery = $db->query("SELECT * FROM settings ORDER BY id DESC LIMIT 1");
        				$settings = $settingsQuery->fetch_assoc();
        				echo success_a("Your changes was saved successfully.");
        			}
        		}
        		?>
        		<form action="" method="POST">
        			<div class="form-group">
        				<label>Title</label>
        				<input type="text" class="form-control" name="title" value="<?php echo $settings['title']; ?>">
        			</div>
        			<div class="form-group">
        				<label>Description</label>
        				<textarea class="form-control" name="description" rows="2"><?php echo $settings['description']; ?></textarea>
        			</div>
        			<div class="form-group">
        				<label>Keywords</label>
        				<textarea class="form-control" name="keywords" rows="2"><?php echo $settings['keywords']; ?></textarea>
        			</div>
        			<div class="form-group">
        				<label>Site name</label>
        				<input type="text" class="form-control" name="name" value="<?php echo $settings['name']; ?>">
        			</div>
        			<div class="form-group">
        				<label>Site url address</label>
        				<input type="text" class="form-control" name="url" value="<?php echo $settings['url']; ?>">
        			</div>
        			<div class="row">
        			    <div class="col col-md">
        			        <div class="form-group">
                				<label>Info email address</label>
                				<input type="text" class="form-control" name="infoemail" value="<?php echo $settings['infoemail']; ?>">
                			</div>
        			    </div>
        			    <div class="col col-md">
        			        <div class="form-group">
                				<label>Support email address</label>
                				<input type="text" class="form-control" name="supportemail" value="<?php echo $settings['supportemail']; ?>">
                			</div>
        			    </div>
        			</div>
        			<div class="row">
        			    <div class="col col-md">
        			        <div class="form-group">
                				<label>Gamepix ID</label>
                				<input type="text" class="form-control" name="gamepix_id" value="<?php echo $settings['gamepix_id']; ?>">
                				<small>If you want to add games from gamepix you can enter the Gamepix ID here else leave It empty.</small>
                			</div>
        			    </div>
        			    <div class="col col-md">
        			        <div class="form-group">
        						<label>Default language</label>
        						<select class="form-control" name="default_language">
        						<?php
        						if ($handle = opendir('../languages')) {
        							while (false !== ($file = readdir($handle)))
        							{
        								if ($file != "." && $file != ".." && $file != "index.php" && strtolower(substr($file, strrpos($file, '.') + 1)) == 'php')
        								{
        									$lang = str_ireplace(".php","",$file);
        									if($settings['default_language'] == $lang) { $sel ='selected'; } else { $sel = ''; }
        									echo '<option value="'.$lang.'" '.$sel.'>'.$lang.'</option>';
        								}
        							}
        							closedir($handle);
        						}
        						?>
        						</select>
        					</div>
        			    </div>
        			</div>
        			<div class="form-check">
                        <div class="checkbox">
                            <label for="checkbox1" class="form-check-label ">
                                <input type="checkbox" id="checkbox1" name="enable_recaptcha" <?php if($settings['enable_recaptcha'] == "1") { echo 'checked'; } ?> value="1" class="form-check-input"> Enable Google reCaptcha
                            </label>
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
        			    <div class="col col-md">
        			        <div class="form-group">
                                <label>reCaptcha Public Key</label>
                                <input type="text" class="form-control" name="recaptcha_publickey" value="<?php echo $settings['recaptcha_publickey']; ?>">
                            </div>
        			    </div>
        			    <div class="col col-md">
        			        <div class="form-group">
                                <label>reCaptcha Private Key</label>
                                <input type="text" class="form-control" name="recaptcha_privatekey" value="<?php echo $settings['recaptcha_privatekey']; ?>">
                            </div>
        			    </div>
        			</div>
        			<div class="row">
        			    <div class="col col-md">
        			        <div class="form-group">
                                <label>Facebook Page URL</label>
                                <input type="text" class="form-control" name="facebook" value="<?=$settings['facebook'];?>">
                            </div>
        			    </div>
        			    <div class="col col-md">
        			        <div class="form-group">
                                <label>Twiter (X) Page URL</label>
                                <input type="text" class="form-control" name="twitter" value="<?=$settings['twitter'];?>">
                            </div>
        			    </div>
        			</div>
        			<div class="row">
        			    <div class="col col-md">
        			        <div class="form-group">
                                <label>Instagram Profile URL</label>
                                <input type="text" class="form-control" name="instagram" value="<?=$settings['instagram'];?>">
                            </div>
        			    </div>
        			    <div class="col col-md">
        			        <div class="form-group">
                                <label>Youtube Channel URL</label>
                                <input type="text" class="form-control" name="youtube" value="<?=$settings['youtube'];?>">
                            </div>
        			    </div>
        			</div>
        			<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
        		</form>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>SMTP Settings</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					
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
		if(isset($_POST['btn_save_smtp'])) {
			if(isset($_POST['SMTPAuth'])) { $SMTPAuth = true; $SMTPAuth_c = 'true'; } else { $SMTPAuth = false; $SMTPAuth_c = 'false'; }
			$smtp_host = protect($_POST['smtp_host']);
			$smtp_port = protect($_POST['smtp_port']);
			$smtp_user = protect($_POST['smtp_user']);
			$smtp_pass = protect($_POST['smtp_pass']);
			if(isset($_POST['smtp_ssl'])) { $ssl = 1; } else { $ssl = 0; }
			
			if($SMTPAuth == true && empty($smtp_host) or empty($smtp_port) or empty($smtp_user) or empty($smtp_pass)) { echo error_a("Please enter a SMTP settings."); }
			else {
				$contents = '<?php
$smtpconf = array();
$smtpconf["host"] = "'.$smtp_host.'"; // SMTP SERVER IP/HOST
$smtpconf["user"] = "'.$smtp_user.'";	// SMTP AUTH USERNAME if SMTPAuth is true
$smtpconf["pass"] = "'.$smtp_pass.'";	// SMTP AUTH PASSWORD if SMTPAuth is true
$smtpconf["port"] = "'.$smtp_port.'";	// SMTP SERVER PORT
$smtpconf["ssl"] = "'.$ssl.'"; // 1 -  YES, 0 - NO
$smtpconf["SMTPAuth"] = '.$SMTPAuth_c.'; // true / false
?>
';				
				$update = file_put_contents("../configs/smtp.settings.php",$contents);
				if($update) {
					$smtpconf["host"] = $smtp_host; // SMTP SERVER IP/HOST
					$smtpconf["user"] = $smtp_user;		// SMTP AUTH USERNAME if SMTPAuth is true
					$smtpconf["pass"] = $smtp_pass;	// SMTP AUTH PASSWORD if SMTPAuth is true
					$smtpconf["port"] = $smtp_port;	// SMTP SERVER PORT
					$smtpconf["ssl"] = $ssl; // 1 -  YES, 0 - NO
					$smtpconf["SMTPAuth"] = $SMTPAuth; // true / false
					echo success_a("Your changes was saved successfully.");
				} else {
					echo error_a("Please set chmod 777 of file <b>includes/smtp.settings.php</b>.");
				}
			}
		}
		?>
		<form action="" method="POST">
			<div class="form-check">
				<div class="checkbox">
					<label for="checkbox1" class="form-check-label ">
						<input type="checkbox" id="checkbox1" name="SMTPAuth" <?php if($smtpconf['SMTPAuth'] == true) { echo 'checked'; } ?> value="1" class="form-check-input"> SMTP Authentication
					</label>
				</div>
			</div>
			<br>
			<div class="form-group">
				<label>SMTP Host</label>
				<input type="text" class="form-control" name="smtp_host" value="<?php echo $smtpconf['host']; ?>">
			</div>
			<div class="form-group">
				<label>SMTP Port</label>
				<input type="text" class="form-control" name="smtp_port" value="<?php echo $smtpconf['port']; ?>">
			</div>
			<div class="form-group">
				<label>SMTP Username</label>
				<input type="text" class="form-control" name="smtp_user" value="<?php echo $smtpconf['user']; ?>">
			</div>
			<div class="form-group">
				<label>SMTP Password</label>
				<input type="text" class="form-control" name="smtp_pass" value="<?php echo $smtpconf['pass']; ?>">
			</div>
			<div class="form-check">
				<div class="checkbox">
					<label for="checkbox2" class="form-check-label ">
						<input type="checkbox" id="checkbox2" name="smtp_ssl" <?php if($smtpconf['ssl'] == 1) { echo 'checked'; } ?> value="1" class="form-check-input"> Secure SSL/TLS Connection
					</label>
				</div>
			</div>
			<br>
			<button type="submit" class="btn btn-primary" name="btn_save_smtp"><i class="fa fa-check"></i> Save changes</button>
		</form>
	</div>
</div>
</div>