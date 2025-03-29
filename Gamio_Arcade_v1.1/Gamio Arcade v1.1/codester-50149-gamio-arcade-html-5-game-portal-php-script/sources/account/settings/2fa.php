<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
error_reporting(1);
if(!checkSession()) {
    $redirect = $settings['url']."login";
    header("Location: $redirect");
}
?>

<?php
$ga = new GoogleAuthenticator();
if(isset($_POST['2fa'])) {
$FormBTN = protect($_POST['2fa']);
if($FormBTN == "enable") {
    if(idinfo($_SESSION['uid'],"googlecode")) {
        $secret = idinfo($_SESSION['uid'],"googlecode");
        $_SESSION['secret'] = $secret;
    } else {
        $secret = $ga->createSecret();
        $_SESSION['secret'] = $secret;
    }
    $_SESSION['security_verification'] = "0";
    $update = $db->query("UPDATE users SET googlecode='$secret' WHERE id='$_SESSION[uid]'");    
    //$update = $db->query("UPDATE users SET 2fa_auth='1',2fa_auth_login='1',2fa_auth_send='1',2fa_auth_withdrawal='1',googlecode='$secret' WHERE id='$_SESSION[uid]'");    
}

if($FormBTN == "enable_u") {
    $ga 		= new GoogleAuthenticator();
    $qrCodeUrl 	= $ga->getQRCodeGoogleUrl(idinfo($_SESSION['uid'],"email"), $_SESSION['secret'], $settings['name']);
    
    $code = protect($_POST['code_verify']);
    $checkResult = $ga->verifyCode($_SESSION['secret'], $code, 2);    // 2 = 2*30sec clock tolerance
    
    if($_SESSION['security_verification'] == "0" && !$checkResult) {
         echo error($lang['error_51']);
    } else {
         $update = $db->query("UPDATE users SET 2fa_auth='1',2fa_auth_login='1',2fa_auth_send='1',2fa_auth_withdrawal='1' WHERE id='$_SESSION[uid]'");    
    }
}

if($FormBTN == "disable") {
    //$update = $db->query("UPDATE users SET 2fa_auth='0' WHERE id='$_SESSION[uid]'");
}

if($FormBTN == "disable_u") {
    $ga 		= new GoogleAuthenticator();
    $qrCodeUrl 	= $ga->getQRCodeGoogleUrl(idinfo($_SESSION['uid'],"email"), $_SESSION['secret'], $settings['name']);
    $code = protect($_POST['code_verify']);
    $checkResult = $ga->verifyCode($_SESSION['secret'], $code, 2);    // 2 = 2*30sec clock tolerance
    if(idinfo($_SESSION['uid'],"2fa_auth") == "1" && !$checkResult) {
         echo error($lang['error_51']);
    } else {
         $update = $db->query("UPDATE users SET 2fa_auth='0' WHERE id='$_SESSION[uid]'");
    }
}

if($FormBTN == "save") {
    if(isset($_POST['2fa_auth_send'])) { $fa_auth_send = 1; } else { $fa_auth_send = 0; }
    if(isset($_POST['2fa_auth_withdrawal'])) { $fa_auth_withdrawal = 1; } else { $fa_auth_withdrawal = 0; }
    $update = $db->query("UPDATE users SET 2fa_auth_send='$fa_auth_send',2fa_auth_withdrawal='$fa_auth_withdrawal' WHERE id='$_SESSION[uid]'");
    echo success('Setting Updated...');
}
}
?>
<?php if ($_SESSION['security_verification'] == "0") {  } else {?>
<h3><?php echo $lang['head_2fa']; ?></h3>
<p><?php echo $lang['head_2fa_info']; ?></p>
<hr/>
<?php } ?>
<?php if(idinfo($_SESSION['uid'],"2fa_auth") == "1") { ?>
<form class="user-connected-from user-signup-form" action="" method="POST">
    <?php echo $lang['currency_status']; ?>: <span class="badge badge-success"><?php echo $lang['enabled']; ?></span>
    <a href="#disable" class="btn btn-danger float-right"><?php echo $lang['btn_14']; ?></a>
</form>
<br>
<form action="" method="POST">
<div class="form-group">
        <div class="custom-control custom-checkbox">
            <div class="custom-checkbox-wrap">
                <input type="checkbox" class="inp2" id="2fa_auth_login" name="2fa_auth_login" <?php if(idinfo($_SESSION['uid'],"2fa_auth_login") == "1") { echo 'checked'; } ?> value="yes">
                <label class="custom-control-label" for="2fa_auth_login">Require Google Authenticator code when login</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <div class="custom-checkbox-wrap">
                <input type="checkbox" class="inp2" id="2fa_auth_send" name="2fa_auth_send" <?php if(idinfo($_SESSION['uid'],"2fa_auth_send") == "1") { echo 'checked'; } ?> value="yes">
                <label class="custom-control-label" for="2fa_auth_send">Require Google Authenticator code when send funds from your wallet</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <div class="custom-checkbox-wrap">
                <input type="checkbox" class="inp2" id="2fa_auth_withdrawal" name="2fa_auth_withdrawal" <?php if(idinfo($_SESSION['uid'],"2fa_auth_withdrawal") == "1") { echo 'checked'; } ?> value="yes">
                <label class="custom-control-label" for="2fa_auth_withdrawal">Require Google Authenticator code when withdrawal funds from your wallet</label>
            </div>
        </div>
    </div>
    <button type="submit" name="2fa" value="save"  class="btn btn-primary" style="padding:12px;">Save Changes</button>
</form>
<br>
<h3>Configurate your device</h3>
<p>Download Google Authenticator and Scan QR Code below or enter your token manually.</p>
<hr/>
<?php
$qrCodeUrl 	= $ga->getQRCodeGoogleUrl(idinfo($_SESSION['uid'],"email"), $_SESSION['secret'], $settings['name']); 
?>
    Token: <span class="float-right"><?php echo idinfo($_SESSION['uid'],"googlecode"); ?></span><br><br>
    <center><img src='<?php echo $qrCodeUrl; ?>'><br>
            <a class="" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">
                    <img src="<?php echo $settings['url']; ?>assets/images/android.png" width="150px">
            </a> 
            <a class="" href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank">
                <img src="<?php echo $settings['url']; ?>assets/images/iphone.png" width="150px">
            </a>
            </center>
            
            <br>
        
        <form action="" method="POST" id="disable">
            
            <div class="row md-12">
                <div class="col-md-9">
                    <input class="inp2" required type="text" name="code_verify" placeholder="Enter 2FA Code">
                </div>
                <div class="col-md-3">
                    <button type="submit" name="2fa" value="disable_u" class="btn btn-danger" style="width:100%; margin-top:3px;">Disable 2FA</button>
                </div>
            </div>
            
        </form>
        
<?php } else { ?>

    <?php if ($_SESSION['security_verification'] == "0") { ?>
    <h3>Configurate your device</h3>
    <p>Download Google Authenticator and Scan QR Code below or enter your token manually.</p>
    <hr/>
    <?php
    //$qrCodeUrl 	= $ga->getQRCodeGoogleUrl(idinfo($_SESSION['uid'],"email"), $_SESSION['secret'], $settings['name']);
    $qrCodeUrl = 'https://quickchart.io/qr?text='.idinfo($_SESSION['uid'],"googlecode").'&size=200&margin=1';
    ?>
    <div class="row">
        <div class="col-md-3">
            Token: <span class="float-right"><?php echo idinfo($_SESSION['uid'],"googlecode"); ?></span>
            <br><br>
            <img src='<?php echo $qrCodeUrl; ?>'>
        </div>
        <br>
        <div class="col-md-4">
            <div style="margin-top:25%;margin-bottom:25%;">
                <a class="" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">
                    <img src="<?php echo $settings['url']; ?>assets/images/android.png" width="150px">
                </a>
                <br><br>
                <a class="" href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank">
                    <img src="<?php echo $settings['url']; ?>assets/images/iphone.png" width="150px">
                </a>
            </div>
        </div>
        <br>
        <div class="col-md-3">
            <form action="" method="POST" style="margin-top:25%;margin-bottom:25%;">
                <input class="inp2" required type="text" name="code_verify" placeholder="Enter 2FA Code">
                <button type="submit" name="2fa" value="enable_u" class="btn btn-success" style="width:100%; margin-top:3px;"><?php echo $lang['btn_15']; ?></button>
            </form>
        </div>
    </div>
    <?php } else { ?>

    <form class="user-connected-from user-signup-form" action="" method="POST">
    <?php echo $lang['currency_status']; ?>: <span class="pill tag-lite"> <?php echo $lang['disabled']; ?></span>
    <button type="submit" name="2fa" value="enable" class="btn btn-success" style="float:right;"><?php echo $lang['btn_15']; ?></button>
    </form>
    
    <?php } ?>
    
<?php } ?>
