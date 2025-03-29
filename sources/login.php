<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
if(checkSession()) {
    $redirect = $settings['url']."account/summary";
    header("Location: $redirect");
}
if(isset($_GET['type'])) {
        $type = protect($_GET['type']);


if($type == "auth") {
$auth_id = $_SESSION['auth_uid'];
$query = $db->query("SELECT * FROM users WHERE id='$auth_id'");
if($query->num_rows==0) { 
    $redirect = $settings['url']."login";
    header("Location: $redirect");
}
$u = $query->fetch_assoc();
$ga 		= new GoogleAuthenticator();
$qrCodeUrl 	= $ga->getQRCodeGoogleUrl(idinfo($_SESSION['auth_uid'],"email"), $_SESSION['secret'], $settings['name']);
?>
<!DOCTYPE html>
<html>
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Two Factor Auth - <?= $settings['name']; ?></title>
    <meta name="description" content="<?= $settings['description']; ?>">
    <meta name="keywords" content="<?=$settings['keywords']; ?>">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../../assets/new/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../assets/new/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/new/dist/css/adminlte.min.css">
    <?php if($settings['favicon']) { ?>
		<link rel="icon" type="image/png" href="<?= $settings['url'].$settings['favicon'] ?>">
	<?php } else { ?>
		<link rel="icon" type="image/png" href="<?= $settings['url'] ?>assets/logo/favicon.png">
	<?php } ?>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
          <a href="<?=$settings['url']; ?>" class="h1"><b><?=$settings['name']; ?></b></a>
        </div>
        <div class="card-body">
          <p class="login-box-msg"><?=$lang['title_2fa']; ?></p>
          <?php
            $FormBTN = protect($_POST['auth']);
            if($FormBTN == "auth") {
                $code = protect($_POST['code']);
                $checkResult = $ga->verifyCode($_SESSION['secret'], $code, 2);    // 2 = 2*30sec clock tolerance
                if($checkResult) {
                            $_SESSION['auth_code'] = false;
                            $_SESSION['auth_id'] = false;
                            $_SESSION['uid'] = $u['id'];
                            if(protect($_POST['remember_me']) == "yes") {
                                setcookie("prowall_uid", $u['id'], time() + (86400 * 30), '/'); // 86400 = 1 day
                            }
                            $last_login = $login['last_login']+5000;
                            if(time() > $last_login) {
                                $time = time();
                                $update = $db->query("UPDATE users SET last_login='$time' WHERE id='$u[id]'");
                            }
                            $time = time();
                            $login_ip = $_SERVER['REMOTE_ADDR'];
                            $insert = $db->query("INSERT users_logs (uid,type,time,u_field_1) VALUES ('$u[id]','1','$time','$login_ip')");
                            if($_SESSION['payorder_url']) {
                                $redirect = $_SESSION['payorder_url'];
                                header("Location: $redirect");
                            } else {
                                $redirect = $settings['url']."account/summary";
                                header("Location: $redirect");
                            }
                } else {
                    echo error($lang['error_51']);
                }
            } 
          ?>
          <form action="" method="post">
            <div class="input-group mb-3">
              <input class="form-control" type="email" disabled value="<?=$u['email']; ?>" name="email" placeholder="<?=$lang['placeholder_3']; ?>">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input class="form-control" type="text" name="code" placeholder="<?=$lang['placeholder_12']; ?>" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-vr-cardboard"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <button type="submit" name="auth" value="auth" class="btn btn-primary btn-block"><?=$lang['btn_29']; ?></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
                            
    <script src="../../assets/new/plugins/jquery/jquery.min.js"></script>
    <script src="../../assets/new/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/new/dist/js/adminlte.min.js"></script>
</body>
</html>

  
<?php
} 
} else {
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - <?=$settings['title']; ?></title>
    <meta name="description" content="Login to <?=$settings['name']; ?> account and access to advance features. Play any game as your choice.">
    <meta name="keywords" content="login,sign in,login to <?=$settings['name']; ?>,<?=$settings['keywords']; ?>">
    <meta name="author" content="Deluxe Script">
    <script src="https://use.fontawesome.com/32efc5ddb7.js"></script>
	<link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/slick.css">
	<link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/slick-theme.min.css">
	<link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/bootstrap.min.css">
	<?php if ($_SESSION['mode'] && $_SESSION['mode'] == "dark") { ?>
	    <link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/custom_styles.css">
	<?php } else { ?>
	    <link rel="stylesheet" type="text/css" href="<?= $settings['url'] ?>theme/css/custom_styles_light.css">
	<?php } ?>
	<script src="<?= $settings['url'] ?>theme/js/jquery.min.js"></script> 
	<script src="<?= $settings['url'] ?>theme/js/slick.min.js"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php if($settings['favicon']) { ?>
		<link rel="icon" type="image/png" href="<?= $settings['url'].$settings['favicon'] ?>">
	<?php } else { ?>
		<link rel="icon" type="image/png" href="<?= $settings['url'] ?>theme/img/favicon.png">
	<?php } ?>
</head>
<body>
<img src="<?= $settings['url'] ?>theme/img/back2.png" class="background1">

<form method="POST" action="" class="logForm">
    <div class="logRegPage block992">
        <div class="flex logFlex block992 ai ">
            <div class="logRegForm flex block992">
                    
                <div class="logLeft flex ai jc block992">
                    <a href="<?= $settings['url'] ?>">
                        <?php if($settings['white_logo'] && $settings['logo']) { ?>
        				    <?php if ($_SESSION['mode'] && $_SESSION['mode'] == "light") { ?>
        				        <img src="<?= $settings['url'].$settings['white_logo'] ?>" class="logregLogo">
        				    <?php } else { ?>
        					    <img src="<?= $settings['url'].$settings['logo'] ?>" class="logregLogo">
        					<?php } ?>
        				<?php } else { ?>
        					<img src="<?= $settings['url']; ?>theme/img/logo-main.png" class="logregLogo">
        				<?php } ?>
                    </a>
                    <div class="flex logTermLinks">
                        <a target="_blank" href="<?= $settings['url']; ?>page/terms-and-conditions">Terms</a>
                        <a target="_blank" href="<?= $settings['url']; ?>page/privacy-policy">Privacy</a>
                    </div>
                </div> <!-- logLeft -->
                <div class="logRight ">
                <?php
                if(isset($_POST['login'])) {
                    $FormBTN = protect($_POST['login']);
                    if($FormBTN == "login") {
                        $email = protect($_POST['email']);
                        $password = protect($_POST['password']);
                        if(isset($_POST['g-recaptcha-response'])) {
                        $recaptcha_response = protect($_POST['g-recaptcha-response']);
                        }
                        $CheckLogin = $db->query("SELECT * FROM users WHERE email='$email'");
                        if(empty($email) or empty($password)) { 
                            echo error($lang['error_36']);
                        } elseif($CheckLogin->num_rows==0) {
                            echo error($lang['error_37']);
                        } elseif($settings['enable_recaptcha'] == "1" && !VerifyGoogleRecaptcha($recaptcha_response)) {
                            echo error($lang['error_52']);  
                        } else {
                            $login = $CheckLogin->fetch_assoc();
                            if(password_verify($password, $login['password'])) {
                            
                                if($login['status'] == "11") {
                                    echo error($lang['error_38']);
                                } else {
                                    if($login['2fa_auth'] == "1" && $login['2fa_auth_login'] == "1") {
                                        $_SESSION['auth_uid'] = $login['id'];
                                        $_SESSION['secret'] = $login['googlecode'];
                                        $_SESSION['auth_code'] = strtoupper(randomHash(7));
                                        $redirect = $settings['url']."login/auth";
                                        header("Location: $redirect");
                                    } else {
                                        $_SESSION['uid'] = $login['id'];
                                        if(isset($_POST['remember_me'])) {
                                            if(protect($_POST['remember_me']) == "yes") {
                                            setcookie("prowall_uid", $login['id'], time() + (86400 * 30), '/'); // 86400 = 1 day
                                            }
                                        }
                                        
                                        $last_login = $login['last_login']+5000;
                                        if(time() > $last_login) {
                                            $time = time();
                                            $update = $db->query("UPDATE users SET last_login='$time' WHERE id='$login[id]'");
                                        }
                                        $time = time();
                                        $login_ip = $_SERVER['REMOTE_ADDR'];
                                        $insert = $db->query("INSERT users_logs (uid,type,time,u_field_1) VALUES ('$login[id]','1','$time','$login_ip')");
                                        EmailSys_loginNotification($email,$login_ip);
                                        $redirect = $settings['url']."account/summary";
                                        header("Location: $redirect");
                                    }
                                }
                            } else {
                                echo error($lang['error_37']);
                            }
                        }
                    }
                }
                ?>
                    <h2 class="logTitle">LOG IN</h2>
                    <p class="cgray mb20">Login to your Account, Start Playing Amazing games now...!</p>
                    <div class="inpBack rel flex ai">
                        <img src="<?= $settings['url'] ?>theme/img/envelope1.png">
                        <input type="email" name="email" class="inp1" placeholder="<?=$lang['placeholder_3']; ?>">
                    </div>
                    <div class="inpBack rel flex ai">
                        <img src="<?= $settings['url'] ?>theme/img/passwordimg.png">
                        <input type="password" name="password" class="inp1" placeholder="<?=$lang['placeholder_11']; ?>">
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="flex ai pointer mb15 us">
                                <input type="checkbox" name="remember_me" value="yes" class="checkLog">
                                &nbsp;&nbsp;&nbsp;
                                <span><?=$lang['remember_me']; ?></span>
                            </label>
                        </div>
                        <div class="col">
                            <?php if ($m["forget_password"] == "1") { ?>
                                <a href="<?= $settings['url'] ?>password/reset" class="fs13 inline"  style="float:right;"><?= $lang['forgot_password'] ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if($settings['enable_recaptcha'] == "1") { ?>
                    <br>
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    <div class="g-recaptcha" data-sitekey="<?=$settings['recaptcha_publickey']; ?>"></div>
                    <br>
                    <?php } ?>
                    <button type="submit" name="login" value="login" class="btn2 LogRegBtn"><?=$lang['btn_27']; ?></button>
                    <!--
                    <div class="logRegSeperate rel">
                        <span class="title">or login in with</span>
                    </div>
                    <div class="flex socialLogFlex jc">
                        
                        <a href="#">		
                            <img src="<?= $settings['url'] ?>theme/img/facebookicon.png" class="logsocialicon">
                        </a>
                        <a href="#">		
                            <img src="<?= $settings['url'] ?>theme/img/twittericon.png" class="logsocialicon">
                        </a>
                        <a href="#">		
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
<?php } ?>