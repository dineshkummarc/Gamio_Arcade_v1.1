<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
function EmailSys_loginNotification($email,$login_ip) {
	global $db, $settings, $smtpconf;
	$eQuery = $db->query("SELECT * FROM users WHERE email='$email'");
	if($eQuery->num_rows>0) {
		$row = $eQuery->fetch_assoc();
		$to = $row['email'];
		$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->Host = $smtpconf["host"];
		$mail->Port = $smtpconf["port"];
		$mail->SMTPAuth = $smtpconf['SMTPAuth'];
		$mail->Username = $smtpconf["user"];
		$mail->Password = $smtpconf["pass"];
		if ($smtpconf["ssl"] == "1") {
		    $mail->SMTPSecure = "ssl";
		}
		$mail->setFrom($settings['infoemail'], $settings['name']);
		$mail->addAddress($to, $to);
		//Set the subject line
		$lang = array();
		$mail->Subject = $settings['name'].' Login Attempt';
		$tpl = new Template("templates/Email_Templates/login_notification.tpl",$lang);
		$tpl->set("url",$settings['url']);
		$tpl->set("name",$settings['name']);
		$tpl->set("email",$row['email']);
		$tpl->set("code",$login_ip);
		$email_template = $tpl->output();
		$mail->msgHTML($email_template);
		//Replace the plain text body with one created manually
		$mail->AltBody = $settings['name'].' Login Attempt';
		//Attach an image file
		//send the message, check for errors
		$send = $mail->send();
		if($send) { 
			return true;
		} else {
			return false;
		}
	}
}
function mass_mail($subject, $message, $type, $email) {
    global $db, $settings, $smtpconf;
    if($type == 1) {
		$eQuery = $db->query("SELECT * FROM users WHERE email='$email'");
		if($eQuery->num_rows>0) {
			$row = $eQuery->fetch_assoc();
			$to = $row['email'];
			$mail = new PHPMailer;
			$mail->From = $settings['infoemail'];
			$mail->FromName = $settings['name'];
			$mail->addAddress($to, $to);  // Name is optional
			$mail->addReplyTo($settings['infoemail'], 'Information');
			$mail->addBCC($to);                             // Set email format to HTML
			$mail->Subject = $subject;
			$mail->Body    = html_entity_decode($message);
			$mail->AltBody = html_entity_decode($message);
			$send = $mail->send();
			if($send) { 
				return success("Email has been sended.");
			} else {
				return error("Some error occur.");
			}
		}
    } else if($type == 2) {
		$eQuery = $db->query("SELECT * FROM users ORDER BY id DESC"); 
		if($eQuery->num_rows>0) {
			$row = $eQuery->fetch_assoc();
			$to = $row['email'];
			while($row = $eQuery->fetch_assoc()){
				$mail = new PHPMailer;
				$mail->addAddress($row['email'], $row['email']);
				$mail->addBCC($row['email']); // Set email format to HTML
				$mail->From = $settings['infoemail'];
				$mail->FromName = $settings['name'];  // Name is optional
				$mail->addReplyTo($settings['infoemail'], 'Information');
				$mail->Subject = $subject;
				$mail->Body    = html_entity_decode($message);
				$mail->AltBody = html_entity_decode($message);
				$send = $mail->send();
			}
			if($send) { 
				return success("Email has been sended.");
			} else {
				return error("Some error occur.");
			}
		}
    }
}
function EmailSys_Send_Email_Verification($email) {
	global $db, $settings, $smtpconf;
	$eQuery = $db->query("SELECT * FROM users WHERE email='$email'");
	if($eQuery->num_rows>0) {
		$row = $eQuery->fetch_assoc();
		$to = $row['email'];
		$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->Host = $smtpconf["host"];
		$mail->Port = $smtpconf["port"];
		$mail->SMTPAuth = $smtpconf['SMTPAuth'];
		$mail->Username = $smtpconf["user"];
		$mail->Password = $smtpconf["pass"];
		if ($smtpconf["ssl"] == "1") {
		    $mail->SMTPSecure = "ssl";
		}
		$mail->setFrom($settings['infoemail'], $settings['name']);
		$mail->addAddress($to, $to);
		//Set the subject line
		$lang = array();
		$mail->Subject = 'Activate your '.$settings['name'].' account';
		$tpl = new Template("templates/Email_Templates/Email_Verification.tpl",$lang);
		$tpl->set("url",$settings['url']);
		$tpl->set("name",$settings['name']);
		$tpl->set("email",$row['email']);
		$tpl->set("hash",$row['email_hash']);
		$email_template = $tpl->output();
		$mail->msgHTML($email_template);
		//Replace the plain text body with one created manually
		$mail->AltBody = $settings['name'].' Account verification';
		//Attach an image file
		//send the message, check for errors
		$send = $mail->send();
		if($send) { 
			return true;
		} else {
			return false;
		}
	}
}
function EmailSys_Send_Password_Reset($email) {
	global $db, $settings, $smtpconf;
	$eQuery = $db->query("SELECT * FROM users WHERE email='$email'");
	if($eQuery->num_rows>0) {
		$row = $eQuery->fetch_assoc();
		$to = $row['email'];
		$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->Host = $smtpconf["host"];
		$mail->Port = $smtpconf["port"];
		$mail->SMTPAuth = $smtpconf['SMTPAuth'];
		$mail->Username = $smtpconf["user"];
		$mail->Password = $smtpconf["pass"];
		if ($smtpconf["ssl"] == "1") {
		    $mail->SMTPSecure = "ssl";
		}
		$mail->setFrom($settings['infoemail'], $settings['name']);
		$mail->addAddress($to, $to);
		//Set the subject line
		$lang = array();
		$mail->Subject = 'Recover your '.$settings['name'].' account password';
		$tpl = new Template("templates/Email_Templates/Password_Reset.tpl",$lang);
		$tpl->set("url",$settings['url']);
		$tpl->set("name",$settings['name']);
		$tpl->set("email",$row['email']);
		$tpl->set("hash",$row['password_recovery']);
		$email_template = $tpl->output();
		$mail->msgHTML($email_template);
		//Replace the plain text body with one created manually
		$mail->AltBody = 'Recover your '.$settings['name'].' account password';
		//Attach an image file
		//send the message, check for errors
		$send = $mail->send();
		if($send) { 
			return true;
		} else {
			return false;
		}
	}
}
function EmailSys_Send_2FA_Code($email,$code) {
	global $db, $settings, $smtpconf;
	$eQuery = $db->query("SELECT * FROM users WHERE email='$email'");
	if($eQuery->num_rows>0) {
		$row = $eQuery->fetch_assoc();
		$to = $row['email'];
		$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->Host = $smtpconf["host"];
		$mail->Port = $smtpconf["port"];
		$mail->SMTPAuth = $smtpconf['SMTPAuth'];
		$mail->Username = $smtpconf["user"];
		$mail->Password = $smtpconf["pass"];
		if ($smtpconf["ssl"] == "1") {
		    $mail->SMTPSecure = "ssl";
		}
		$mail->setFrom($settings['infoemail'], $settings['name']);
		$mail->addAddress($to, $to);
		//Set the subject line
		$lang = array();
		$mail->Subject = $settings['name'].' 2FA';
		$tpl = new Template("templates/Email_Templates/2FA_Auth.tpl",$lang);
		$tpl->set("url",$settings['url']);
		$tpl->set("name",$settings['name']);
		$tpl->set("email",$row['email']);
		$tpl->set("code",$code);
		$email_template = $tpl->output();
		$mail->msgHTML($email_template);
		//Replace the plain text body with one created manually
		$mail->AltBody = $settings['name'].' 2FA';
		//Attach an image file
		//send the message, check for errors
		$send = $mail->send();
		if($send) { 
			return true;
		} else {
			return false;
		}
	}
}
