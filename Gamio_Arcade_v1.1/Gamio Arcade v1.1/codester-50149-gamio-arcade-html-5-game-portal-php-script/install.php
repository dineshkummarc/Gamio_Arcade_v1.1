<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
define('V1_INSTALLED', true);
ob_start();
session_start();
include("includes/function.web.php");
include("includes/function.messages.php");
?>

<html>
<head>
	<title>Gamio v1.0 Install Wizard</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/fontawesome.min.css" />
</head>
<body>
    <div class="container">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="row" style="margin-top:10px;margin-bottom:10px;">
					<div class="col-md-12">
						<nav class="navbar navbar-expand-lg navbar-light bg-light">
							<a class="navbar-brand" href="./install.php">Gamio v1.0 Install Wizard</a>
						</nav>
						<br>
					</div>
					<div class="col-md-4">
						<div class="list-group">
						  <li class="list-group-item">1. MySQL Connection</li>
						  <li class="list-group-item">2. Web Settings</li>
						  <li class="list-group-item">3. Admin Account</li>
						  <li class="list-group-item">4. Ready to use</li>
						</div>
					</div>
					<div class="col-md-8">
						<div class="card panel-default">
							<div class="card-body">
								<?php if (isset($_GET['step'])) {
								    $step = protect($_GET['step']);
								?>
								<?php if($step == "web_settings") { ?>
    								<?php
    								if(isset($_POST['goNext'])) {
    									$title = protect($_POST['title']);
    									$description = protect($_POST['description']);
    									$keywords = protect($_POST['keywords']);
    									$name = protect($_POST['name']);
    									$infoemail = protect($_POST['infoemail']);
    									$supportemail = protect($_POST['supportemail']);
    									$url = protect($_POST['url']);
    									$default_language = protect($_POST['default_language']);
    									if(empty($title) or empty($description) or empty($keywords) or empty($name) or empty($url) or empty($infoemail) or empty($supportemail) or empty($default_language)) {
    									echo error("All fields are required."); 
    									} elseif(!isValidEmail($infoemail)) { echo error("Please enter valid info email address. Example: no-reply@yourdomain.com"); }
    									elseif(!isValidEmail($supportemail)) { echo error("Please enter valid support email address. Example: support@yourdomain.com"); }
    									elseif(!isValidURL($url)) { echo error("Please enter valid site url address. Example: http://yourdomain.com/"); }
    										else {
    										$_SESSION['web_title'] = $title;
    										$_SESSION['web_description'] = $description;
    										$_SESSION['web_keywords'] = $keywords;
    										$_SESSION['web_name'] = $name;
    										$_SESSION['web_infoemail'] = $infoemail;
    										$_SESSION['web_supportemail'] = $supportemail;
    										$_SESSION['web_url'] = $url;
    										$_SESSION['web_default_language'] = $default_language;
    										header("Location: ./install.php?step=admin_account");
    									}
    								}
    								?>
								    <form action="" method="POST">
										<div class="form-group">
											<label>Title</label>
											<input type="text" class="form-control" placeholder="Type your website title" name="title" value="<?php if(isset($_POST['title'])) { echo $_POST['title']; } ?>">
										</div>
										<div class="form-group">
											<label>Description</label>
											<textarea class="form-control" placeholder="Type your website short description" name="description" rows="2"><?php if(isset($_POST['description'])) { echo $_POST['description']; } ?></textarea>
										</div>
										<div class="form-group">
											<label>Keywords</label>
											<textarea class="form-control" placeholder="Type few keywords for your website" name="keywords" rows="2"><?php if(isset($_POST['keywords'])) { echo $_POST['keywords']; } ?></textarea>
										</div>
										<div class="form-group">
											<label>Site name</label>
											<input type="text" class="form-control" placeholder="Type your website name" name="name" value="<?php if(isset($_POST['name'])) { echo $_POST['name']; } ?>">
										</div>
										<?php
                                        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                        $parsedUrl = parse_url($url);
                                        $host = $parsedUrl['host'];
                                        $hostParts = explode('.', $host);
                                        $domain = $hostParts[count($hostParts) - 2] . '.' . $hostParts[count($hostParts) - 1];
                                        ?>
										<div class="form-group">
											<label>Info email address</label>
											<input type="text" class="form-control" placeholder="noreply@example.com" name="infoemail" value="<?php if(isset($_POST['infoemail'])) { echo $_POST['infoemail']; } ?>">
										</div>
										<div class="form-group">
											<label>Support email address</label>
											<input type="text" class="form-control" placeholder="support@example.com" name="supportemail" value="<?php if(isset($_POST['supportemail'])) { echo $_POST['supportemail']; } ?>">
										</div>
										<div class="form-group">
											<label>Site url address</label>
											<input type="text" class="form-control" placeholder="https://www.example.com/" name="url" value="<?php if(isset($_POST['url'])) { echo $_POST['url']; } ?>">
										</div>
										<div class="form-group">
											<label>Default language</label>
											<select class="form-control" name="default_language">
											<?php
											if ($handle = opendir('./languages')) {
												while (false !== ($file = readdir($handle)))
												{
													if ($file != "." && $file != ".." && $file != "index.php" && strtolower(substr($file, strrpos($file, '.') + 1)) == 'php')
													{
														$lang = str_ireplace(".php","",$file);
														echo '<option value="'.$lang.'">'.$lang.'</option>';
													}
												}
												closedir($handle);
											}
											?>
											</select>
										</div>
										<button type="submit" class="btn btn-primary" name="goNext">Next</button>
									</form>
								<?php } elseif($step == "admin_account") { ?>
									<?php
									if (isset($_POST['admin_username']) && isset($_POST['admin_password']) && isset($_POST['admin_email'])) {
    									$username = protect($_POST['admin_username']);
    									$password = protect($_POST['admin_password']);
    									$email = protect($_POST['admin_email']);
    									if(empty($username) or empty($password) or empty($email)) { 
    									    echo error("All fields are required."); 
    									} elseif(!isValidUsername($username)) { 
    									    echo error("Please enter valid username."); 
    									} elseif(!isValidEmail($email)) { 
    									    echo error("Please enter valid email address."); 
    									} else {
    										$_SESSION['admin_username'] = $username;
    										$_SESSION['admin_password'] = $password;
    										$_SESSION['admin_email'] = $email;
    										header("Location: ./install.php?step=ready_to_use");
    									}
									}
									?>
									<form action="" method="POST">
										<div class="form-group">
											<label>Admin username</label>
											<input type="text" class="form-control" name="admin_username" value="<?php if(isset($_POST['admin_username'])) { echo $_POST['admin_username']; } ?>">
										</div>
										<div class="form-group">
											<label>Admin email address</label>
											<input type="text" class="form-control" name="admin_email" value="<?php if(isset($_POST['admin_email'])) { echo $_POST['admin_email']; } ?>">
										</div>
										<div class="form-group">
											<label>Admin password</label>
											<input type="password" class="form-control" name="admin_password" value="<?php if(isset($_POST['admin_password'])) { echo $_POST['admin_password']; } ?>">
										</div>
										<button type="submit" class="btn btn-primary" name="goNext">Finish</button>
									</form>
								
								    <?php } elseif($step == "ready_to_use") { error_reporting(1); ?>
									
									<?php
									
									$title = $_SESSION['web_title'];
									$description = $_SESSION['web_description'];
									$keywords = $_SESSION['web_keywords'];
									$name = $_SESSION['web_name'];
									$url = $_SESSION['web_url'];
									$infoemail = $_SESSION['web_infoemail'];
									$supportemail = $_SESSION['web_supportemail'];
									$default_language = $_SESSION['web_default_language'];	
									$admin_username = $_SESSION['admin_username'];
									$admin_password = $_SESSION['admin_password'];
									$password = password_hash($admin_password, PASSWORD_DEFAULT);
                                    $admin_email = $_SESSION['admin_email'];
									$mysql_host = $_SESSION['mysql_host'];
									$mysql_user = $_SESSION['mysql_user'];
									$mysql_pass = $_SESSION['mysql_pass'];
									$mysql_name = $_SESSION['mysql_name'];
									
									$db = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_name);
			 						$db->set_charset("utf8");
									$sqlScript = file('database.sql');
                                    foreach ($sqlScript as $line)	{
                                    	$startWith = substr(trim($line), 0 ,2);
                                    	$endWith = substr(trim($line), -1 ,1);
                                    	if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') { continue; }
                                    	$query = $query . $line;
                                    	if ($endWith == ';') {  mysqli_query($db,$query) or die('Problem in executing the SQL query <b>' . $query. '</b>');$query= ''; }
                                    }
									$current .= '<?php
';
									$current .= '$CONF = array();
';
									$current .= '$CONF["host"] = "'.$mysql_host.'";
';
									$current .= '$CONF["user"] = "'.$mysql_user.'";
';
									$current .= '$CONF["pass"] = "'.$mysql_pass.'";
';
									$current .= '$CONF["name"] = "'.$mysql_name.'";
';
									$current .= '?>';
									file_put_contents("configs/sql.settings.php", $current);
									$insert = $db->query("INSERT settings (title) VALUES ('Installing...')");
									$update = $db->query("UPDATE settings SET title='$title',description='$description',keywords='$keywords',default_language='$default_language',name='$name',url='$url',infoemail='$infoemail',supportemail='$supportemail'");
									$insert_admin = $db->query("INSERT users (password,email,account_user,status,account_level) VALUES ('$password','$admin_email','$admin_username','1','666')");
									$countries = getCountries();
									foreach($countries as $code=>$name) {
										$insert = $db->query("INSERT country (`name`, `code`, `status`) VALUES ('$name','$code','1')");	
									}
									@unlink("./install.php");
									@unlink("./database.sql");
									?>
									<h4><i class="fa fa-check"></i> Gamio v1.0 was installed!</h4>
									<p>Your Gamio url address: <a href="<?php echo $url; ?>"><?php echo $url; ?></a></p>
									<p>Your Gamio admin url address: <a href="<?php echo $url; ?>admin"><?php echo $url; ?>admin</a></p>
									<p>Admin username: <?php echo $admin_username; ?></p>
									<p>Admin password: <?php echo $admin_password; ?></p>
									<p>If have some questions or problems, feel free to contact us <b><a href="https://support.deluxescript.com/">https://support.deluxescript.com/</a></b></p>
									
								<?php } } else { ?>
									
								<?php
								if(isset($_POST['goNext'])) {
									$mysql_host = protect($_POST['mysql_host']);
									$mysql_user = protect($_POST['mysql_user']);
									$mysql_pass = protect($_POST['mysql_pass']);
									$mysql_name = protect($_POST['mysql_name']);
									
									if(empty($mysql_host) or empty($mysql_user) or empty($mysql_name)) { echo error("All fields are required."); }
									else {
										$db = new mysqli($mysql_host,$mysql_user,$mysql_pass,$mysql_name);
										if ($db->connect_errno) {
											echo error("Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error);
										} else {
											$_SESSION['mysql_host'] = $mysql_host;
											$_SESSION['mysql_user'] = $mysql_user;
											$_SESSION['mysql_pass'] = $mysql_pass;
											$_SESSION['mysql_name'] = $mysql_name;
											header("Location: ./install.php?step=web_settings");
										}
									}
								} 
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>MySQL Host</label>
										<input type="text" class="form-control" name="mysql_host" value="<?php if(isset($_POST['mysql_host'])) { echo $_POST['mysql_host']; } ?>">
									</div>
									<div class="form-group">
										<label>MySQL Username</label>
										<input type="text" class="form-control" name="mysql_user" value="<?php if(isset($_POST['mysql_user'])) { echo $_POST['mysql_user']; } ?>">
									</div>
									<div class="form-group">
										<label>MySQL Password</label>
										<input type="password" class="form-control" name="mysql_pass" value="<?php if(isset($_POST['mysql_pass'])) { echo $_POST['mysql_pass']; } ?>">
									</div>
									<div class="form-group">
										<label>MySQL Database</label>
										<input type="text" class="form-control" name="mysql_name" value="<?php if(isset($_POST['mysql_name'])) { echo $_POST['mysql_name']; } ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="goNext">Next</button>
								</form>
								<?php } ?>
							</div>
						</div>	
					</div>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>
</body>
</html>
