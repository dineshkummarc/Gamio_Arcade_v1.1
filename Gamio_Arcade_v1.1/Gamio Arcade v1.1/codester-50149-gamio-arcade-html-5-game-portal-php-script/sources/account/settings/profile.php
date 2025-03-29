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
<h3><?php echo $lang['head_profile']; ?></h3>
<hr/>

<?php
if(isset($_POST['save_profile'])) {
$FormBTN = protect($_POST['save_profile']);
if($FormBTN == "save_profile") {
    $first_name = protect($_POST['first_name']);
    $last_name = protect($_POST['last_name']);
    $country = protect($_POST['country']);
    $city = protect($_POST['city']);
    $zip_code = protect($_POST['zip_code']);
    $address = protect($_POST['address']);
    if(empty($first_name) or empty($last_name) or empty($country) or empty($city) or empty($zip_code) or empty($address)) {
        echo error($lang['error_20']);
    } elseif($country !== "United Kingdom" && !is_numeric($zip_code)) { 
        echo error($lang['error_21']);
    } elseif($country == "United Kingdom" && postcode_check($zip_code) == false) {
		echo error($lang['error_21']);	
	} else {
        $update = $db->query("UPDATE users SET first_name='$first_name',last_name='$last_name',country='$country',city='$city',zip_code='$zip_code',address='$address' WHERE id='$_SESSION[uid]'");
        echo success($lang['success_10']);
    }
}
}
?>
<form class="contactForm" action="" method="POST">
    <div class="row form-group">
        <div class="col">
            <label class="lbl1"><?php echo $lang['field_11']; ?></label>
            <input type="text" class="inp2" name="first_name" value="<?=idinfo($_SESSION['uid'],"first_name"); ?>">
        </div>
        <div class="col">
            <label  class="lbl1"><?php echo $lang['field_12']; ?></label>
            <input type="text" class="inp2" name="last_name" value="<?=idinfo($_SESSION['uid'],"last_name"); ?>">
        </div>
    </div>
    <div class="form-group">
        <label  class="lbl1"><?php echo $lang['field_13']; ?></label>
        <select class="inp2" name="country">
            <?php
			$country_Query = $db->query("SELECT * FROM country WHERE status='1'");
            while($country = $country_Query->fetch_assoc()) {
				if(idinfo($_SESSION['uid'],"country") == $country['code']) { $sel = 'selected'; } else { $sel = ''; } 
                echo '<option value="'.$country['code'].'" '.$sel.'>'.$country['name'].'</option>';
            }
            ?>
        </select>
    </div>
    <div class="row form-group">
        <div class="col">
            <label class="lbl1"><?php echo $lang['field_14']; ?></label>
            <input type="text" class="inp2" name="city" value="<?php echo idinfo($_SESSION['uid'],"city"); ?>">
        </div>
        <div class="col">
            <label class="lbl1"><?php echo $lang['field_15']; ?></label>
            <input type="text" class="inp2" name="zip_code" value="<?php echo idinfo($_SESSION['uid'],"zip_code"); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="lbl1"><?php echo $lang['field_16']; ?></label>
        <input type="text" class="inp2" name="address" value="<?php echo idinfo($_SESSION['uid'],"address"); ?>">
    </div>
    <button type="submit" name="save_profile" value="save_profile"  class="btn btn-primary" style="padding:12px;"><?php echo $lang['btn_18']; ?></button>
</form>
