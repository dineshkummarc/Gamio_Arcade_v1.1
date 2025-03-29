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
<p class="fs20 m20"><?php echo $lang['head_account_logs']; ?></p>
<div class="tableOuter">
    <table class="table">
        <thead>
            <tr>
                <td width="25%"><?php echo $lang['date']; ?></td>
                <td width="25%"><?php echo $lang['ip']; ?></td>
                <td><?php echo $lang['activity']; ?></td>
            </tr>
        </thead>
        <tbody>
        <?php
        $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
        $limit = 10;
        $startpoint = ($page * $limit) - $limit;
        if($page == 1) {
            $i = 1;
        } else {
            $i = $page * $limit;
        }
        $statement = "users_logs WHERE uid='$_SESSION[uid]' and type='1'";
        $query = $db->query("SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
        if($query->num_rows>0) {
            while($row = $query->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo date("d M Y H:i",$row['time']); ?></td>
                <td><?php echo $row['u_field_1']; ?></td>
                <td>
                    <?php
                    if($row['type'] == "1") {
                        echo 'Login';
                    } else {
                        echo 'Unknown'; 
                    }
                    ?>
                </td>
            </tr>
            <?php
            }
        } else {
            echo '<tr><td colspan="3">'.$lang['info_4'].'</td></tr>';
        }
        ?>
        </tbody>
    </table>

    <?php
    $ver = $settings['url']."account/settings/logs";
    if(web_pagination($statement,$ver,$limit,$page)) {
        echo web_pagination($statement,$ver,$limit,$page);
    }
    ?>
</div>