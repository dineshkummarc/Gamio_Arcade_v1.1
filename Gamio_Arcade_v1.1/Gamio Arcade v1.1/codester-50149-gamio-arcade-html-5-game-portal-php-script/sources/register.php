<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
} 
?>
<!DOCTYPE html>
<html>
<head> 
	<meta charset="UTF-8">
	<title>Register - <?=$settings['name']; ?></title>
    <meta name="description" content="Register an account at <?=$settings['name']; ?> and get access to advance features, like leaderboard, like games and more.">
    <meta name="keywords" content="register, sign up, join now,<?=$settings['keywords']; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://use.fontawesome.com/32efc5ddb7.js"></script>
	<link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/slick.css">
	<link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/slick-theme.min.css">
	<link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/custom_styles.css">
	<script src="<?= $settings['url'] ?>theme/js/jquery.min.js"></script>
	<script src="<?= $settings['url'] ?>theme/js/slick.min.js"></script>
	<?php if($settings['favicon']) { ?>
		<link rel="icon" type="image/png" href="<?= $settings['url'].$settings['favicon'] ?>">
	<?php } else { ?>
		<link rel="icon" type="image/png" href="<?= $settings['url'] ?>theme/img/favicon.png">
	<?php } ?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
	
</head>
<body>
<img src="<?= $settings['url'] ?>theme/img/back2.png" class="background1">
<form method="POST" action="" class="logForm">
	<div class="logRegPage block992 regPage">
		<div class="flex logFlex block992 ai ">
			<div class="logRegForm flex block992">
				<div class="logLeft flex ai jc block992" style="background: #1ab5a5  url(<?= $settings['url'] ?>theme/img/regsterPgBack.png);">
					<a href="/">
						<?php if($settings['white_logo'] && $settings['logo']) { ?>
        				    <?php if ($_SESSION['mode'] && $_SESSION['mode'] == "light") { ?>
        				        <img src="<?= $settings['url'].$settings['white_logo'] ?>" class="logregLogo">
        				    <?php } else { ?>
        					    <img src="<?= $settings['url'].$settings['white_logo'] ?>" class="logregLogo">
        					<?php } ?>
        				<?php } else { ?>
        					<img src="<?= $settings['url']; ?>theme/img/logo-main.png" class="logregLogo">
        				<?php } ?>
					</a>
					<p>
						Already a member? &nbsp; <a href="<?= $settings['url'] ?>login" class="cwhite"><u>Sign in</u></a>
					</p>
				</div> <!-- logLeft -->
				<div class="logRight ">
					<?php
					if ($m["registration"] !== "1") {
						echo error("Registration is OFF Currently, Please contact support.");
					} else {
						if(isset($_POST['register'])) {
							$FormBTN = protect($_POST['register']);
							if($FormBTN == "reg") {
								$full_name = protect($_POST['full_name']);
								$email = protect($_POST['email']);
								$password = protect($_POST['password']);
								$cpassword = protect($_POST['cpassword']);
								if (isset($_POST['g-recaptcha-response'])){
									$recaptcha_response = protect($_POST['g-recaptcha-response']);
								} else {
									$recaptcha_response = "";
								}
								$accept_tou = protect($_POST['accept_tou']);
								if($accept_tou == "yes") { $accept_tou = '1'; } else { $accept_tou = '0'; }
								if(empty($full_name) or empty($email) or empty($password) or empty($cpassword)) {
									echo error($lang['error_20']);
								} elseif(!isValidEmail($email)) {
									echo error($lang['error_45']);
								} elseif($settings['enable_recaptcha'] == "1" && !VerifyGoogleRecaptcha($recaptcha_response)) {
									echo error($lang['error_52']);  
								} elseif(CheckUser($email)==true) {
									echo error($lang['error_46']);
								} elseif(strlen($password)<8) { 
									echo error($lang['error_47']);
								} elseif($password !== $cpassword) {
									echo error($lang['error_48']);
								} elseif($accept_tou==0) {
									echo error($lang['error_49']);
								} else {
									$password = password_hash($password, PASSWORD_DEFAULT);
									$ip = $_SERVER['REMOTE_ADDR'];
									$time = time();
									$insert = $db->query("INSERT users (password,email,email_verified,status,ip,signup_time,full_name) VALUES ('$password','$email','1','1','$ip','$time','$full_name')");
									$GetU = $db->query("SELECT * FROM users WHERE email='$email'");
									$gu = $GetU->fetch_assoc();
									if($settings['require_email_verify'] == "1") {
										$email_hash = randomHash(25);
										$update = $db->query("UPDATE users SET status='2',email_hash='$email_hash',email_verified='0' WHERE email='$email'");
										EmailSys_Send_Email_Verification($email);
										echo success($lang['success_22']);
									} else {
										echo success($lang['success_23']);
									}
								}
							}
						}
					?>
					<h2 class="logTitle" style="color: #1ab5a5;">SIGN UP</h2>
					<p class="cgray mb20 regTxt">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eius</p>
					<div class="row">
						
						<div class="col-lg-6">
							<div class="inpBack rel flex ai">
								<img src="<?= $settings['url'] ?>theme/img/userimg1.png">
								<input type="text" name="full_name" class="inp1" placeholder="Full name">
							</div>
						</div> <!-- col -->
						<div class="col-lg-6">
							<div class="inpBack rel flex ai">
								<img src="<?= $settings['url'] ?>theme/img/envelope1.png">
								<input type="email" name="email" class="inp1" placeholder="Email Address">
							</div>
						</div> <!-- col -->
						<div class="col-lg-6">
							<div class="inpBack rel flex ai">
								<img src="<?= $settings['url'] ?>theme/img/passwordimg.png">
								<input type="password" name="password" class="inp1" placeholder="Password">
							</div>
						</div> <!-- col -->
						<div class="col-lg-6">
							<div class="inpBack rel flex ai">
								<img src="<?= $settings['url'] ?>theme/img/passwordimg.png">
								<input type="password" name="cpassword" class="inp1" placeholder="Confirm Password">
							</div>
						</div> <!-- col -->
					</div> <!-- row -->
					<?php if($settings['enable_recaptcha'] == "1") { ?>
                    <br>
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    <div class="g-recaptcha" data-sitekey="<?php echo filter_var($settings['recaptcha_publickey']); ?>"></div>
                    <br>
                    <?php } ?>
					<label class="flex ai pointer mb15 us">
						<input type="checkbox" name="accept_tou" value="yes" class="checkLog">
						&nbsp;&nbsp;&nbsp;
						<span class="fs14">I agree the <a target="_blank" href="/page/terms-and-conditions" class="cfirst"><u>Terms & conditions</u></a> of <?= $settings['name'] ?></span>
					</label>
					<button type="submit" name="register" value="reg" class="btn2 LogRegBtn">Register</button>
					<?php } ?>
					<!--
					<div class="logRegSeperate rel">
						<span class="title">or login in with</span>
					</div>
					<div class="flex socialLogFlex jc">
						
						<a href="000000000000000000:">		
							<img src="<?= $settings['url'] ?>theme/img/facebookicon.png" class="logsocialicon">
						</a>
						<a href="000000000000000000:">		
							<img src="<?= $settings['url'] ?>theme/img/twittericon.png" class="logsocialicon">
						</a>
						<a href="000000000000000000:">		
							<img src="<?= $settings['url'] ?>theme/img/googleicon.png" class="logsocialicon">
						</a>
					</div>
					-->
				</div> <!-- logRight -->
			</div>
		</div> <!-- logFlex -->
	</div> <!-- logRegPage -->
</form>
</body>
</html>

<!--
<a href="javascript:">
    <?php if($settings['logo']) { ?>
		<img src="<?= $settings['url'].$settings['logo'] ?>" width="180px" class="loginPageLogo">
	<?php } else { ?>
		<img src="<?= $settings['url'] ?>assets/logo/logo_red.png" width="180px" class="loginPageLogo">
	<?php } ?>
</a>


-->