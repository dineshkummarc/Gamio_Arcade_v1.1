<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}

if(!checkSession()) {
    $redirect = $settings['url']."login";
    header("Location: $redirect");
}   
?>
<h3><?php echo $lang['head_change_password']; ?></h3>
<hr/>
<?php
if(isset($_POST['change'])) {
$FormBTN = protect($_POST['change']);
if($FormBTN == "change") {
    $cpassword = protect($_POST['cpassword']);
    $npassword = protect($_POST['npassword']);
    $cnpassword = protect($_POST['cnpassword']);
    if(!password_verify($cpassword, idinfo($_SESSION['uid'],"password"))) {
        echo error($lang['error_17']);
    } elseif(empty($npassword)) {
        echo error($lang['error_18']);
    } elseif($npassword !== $cnpassword) {
        echo error($lang['error_19']);
    } else {
        $password = password_hash($cnpassword, PASSWORD_DEFAULT);
        $update = $db->query("UPDATE users SET password='$password' WHERE id='$_SESSION[uid]'");
        echo success($lang['success_9']);
    }
}
}
?>
<form class="user-connected-from user-signup-form" action="" method="POST">
<div class="form-group">
        <label class="lbl1"><?php echo $lang['field_8']; ?></label>
        <input type="password" class="inp2" name="cpassword">
    </div>
    <div class="form-group">
        <label class="lbl1"><?php echo $lang['field_9']; ?></label>
        <input type="password" class="inp2" name="npassword">
    </div>
    <div class="form-group">
        <label class="lbl1"><?php echo $lang['field_10']; ?></label>
        <input type="password" class="inp2" name="cnpassword">
    </div>
    <button type="submit" name="change" value="change" class="btn btn-primary" style="padding:12px;"><?php echo $lang['btn_17']; ?></button>
</form>