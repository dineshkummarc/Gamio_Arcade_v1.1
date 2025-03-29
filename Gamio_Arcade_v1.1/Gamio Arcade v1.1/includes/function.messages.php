<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}

function success($text) {
	return '<div class="alert tag-success"><i class="fa fa-check"></i> '.$text.'</div>';
}

function error($text) {
	return '<div class="alert tag-error"><i class="fa fa-times"></i> '.$text.'</div>';
}

function info($text) {
	return '<div class="alert tag-info"><i class="fa fa-info-circle"></i> '.$text.'</div>';
}
function warn($text) {
	return '<div class="alert tag-warning"><i class="fa fa-info-circle"></i> '.$text.'</div>';
}

function success_a($text) {
	return '<div class="alert alert-success"><i class="fa fa-check"></i> '.$text.'</div>';
}

function error_a($text) {
	return '<div class="alert alert-danger"><i class="fa fa-times"></i> '.$text.'</div>';
}

function info_a($text) {
	return '<div class="alert alert-info"><i class="fa fa-info-circle"></i> '.$text.'</div>';
}
function warn_a($text) {
	return '<div class="alert alert-warning"><i class="fa fa-info-circle"></i> '.$text.'</div>';
}
?>