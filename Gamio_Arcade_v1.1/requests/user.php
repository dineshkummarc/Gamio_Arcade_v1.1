<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
header('Content-Type: application/json; charset=utf-8');
define('V1_INSTALLED',TRUE);
ob_start();
session_start();
include("../configs/bootstrap.php");
include("../includes/bootstrap.php");
$data = array();
$a = protect($_GET['a']);
$is_json=1; 
if(checkSession()) {
    if($a == "ar_like") {
        $id = protect($_GET['id']);
        $CheckItem = $db->query("SELECT * FROM games WHERE id='$id'");
        if($CheckItem->num_rows>0) {
            $item = $CheckItem->fetch_assoc();
            $CheckFavorite = $db->query("SELECT * FROM votes WHERE uid='$_SESSION[uid]' and gid='$id'");
            if($CheckFavorite->num_rows>0) {
                $fav_row = $CheckFavorite->fetch_assoc();
                if ($fav_row['vote'] == "2") {
                    $time = time();
                    $update = $db->query("UPDATE votes SET vote='1' WHERE uid='$_SESSION[uid]' and gid='$id'");
                    $data['status'] = 'success';
                    $data['content'] = '<a href="javascript:void(0);" id="ar_like" data-id="'.$id.'"><i class="fa fa-thumbs-o-up gameThumbsBtn like-active" title="Like!"></i></a>';
                    $data['contentw'] = '<a href="javascript:void(0);" id="ar_dislike" data-id="'.$id.'"><i class="fa fa-thumbs-o-down gameThumbsBtn" title="Dislike!"></i></a>';
                } else {
                    $delete = $db->query("DELETE FROM votes WHERE uid='$_SESSION[uid]' and gid='$id'");
                    $data['status'] = 'success';
                    $data['content'] = '<a href="javascript:void(0);" id="ar_like" data-id="'.$id.'"><i class="fa fa-thumbs-o-up gameThumbsBtn" title="Like!"></i></a>';
                }
            } else {
                $time = time();
                $insert = $db->query("INSERT votes (uid,gid,vote,time) VALUES ('$_SESSION[uid]','$id','1','$time')");
                $data['status'] = 'success';
                $data['content'] = '<a href="javascript:void(0);" id="ar_like" data-id="'.$id.'"><i class="fa fa-thumbs-o-up gameThumbsBtn like-active" title="Like!"></i></a>';
            }
        } else {
            $data['status'] = 'error';
            $data['msg'] = error("This games does not exists.");
        }
    } elseif ($a == "ar_dislike") {
        $id = protect($_GET['id']);
        $CheckItem = $db->query("SELECT * FROM games WHERE id='$id'");
        if($CheckItem->num_rows>0) {
            $item = $CheckItem->fetch_assoc();
            $CheckFavorite = $db->query("SELECT * FROM votes WHERE uid='$_SESSION[uid]' and gid='$id'");
            if($CheckFavorite->num_rows>0) {
                $fav_row = $CheckFavorite->fetch_assoc();
                if ($fav_row['vote'] == "1") {
                    $time = time();
                    $update = $db->query("UPDATE votes SET vote='2' WHERE uid='$_SESSION[uid]' and gid='$id'");
                    $data['status'] = 'success';
                    $data['content'] = '<a href="javascript:void(0);" id="ar_dislike" data-id="'.$id.'"><i class="fa fa-thumbs-o-down gameThumbsBtn dislike-active" title="Dislike!"></i></a>';
                    $data['contentw'] = '<a href="javascript:void(0);" id="ar_like" data-id="'.$id.'"><i class="fa fa-thumbs-o-up gameThumbsBtn" title="Like!"></i></a>';
                } else {
                    $delete = $db->query("DELETE FROM votes WHERE uid='$_SESSION[uid]' and gid='$id'");
                    $data['status'] = 'success';
                    $data['content'] = '<a href="javascript:void(0);" id="ar_dislike" data-id="'.$id.'"><i class="fa fa-thumbs-o-down gameThumbsBtn" title="Dislike!"></i></a>';
                }
            } else {
                $time = time();
                $insert = $db->query("INSERT votes (uid,gid,vote,time) VALUES ('$_SESSION[uid]','$id','2','$time')");
                $data['status'] = 'success';
                $data['content'] = '<a href="javascript:void(0);" id="ar_dislike" data-id="'.$id.'"><i class="fa fa-thumbs-o-down gameThumbsBtn dislike-active" title="Dislike!"></i></a>';
            }
        } else {
            $data['status'] = 'error';
            $data['msg'] = error("This games does not exists.");
        }
    } else {
        $data['status'] = 'error';
        $data['msg'] = error("Unknown request.");
    }
} else {
    $data['status'] = 'error';
    $data['msg'] = error("You must login with your account.");
}

if($is_json == "1") { 
    echo json_encode($data);
}
?>