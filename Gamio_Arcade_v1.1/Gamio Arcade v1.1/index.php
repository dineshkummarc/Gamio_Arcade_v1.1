<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if (file_exists("./install.php")) {
  header("Location: ./install.php");
  exit();
}

define('V1_INSTALLED', true);
ob_start();
session_start();

include("configs/bootstrap.php");
include("includes/bootstrap.php");
include(getLanguage($settings['url'], null, null));

$a = isset($_GET['a']) ? protect($_GET['a']) : '';

if (empty($_SESSION['mode'])) {
    $_SESSION['mode'] = "light";
}

switch ($a) {
    case "account":
        include("sources/account.php");
        break;
    case "login":
        include("sources/login.php");
        break;
    case "contact":
        include("sources/contact.php");
        break;
    case "register":
        include("sources/register.php");
        break;
    case "password":
        include("sources/password.php");
        break;
    case "email_verify":
        include("sources/email_verify.php");
        break;
    case "page":
        include("sources/page.php");
        break;
    case "blog":
        include("sources/blog.php");
        break;
    case "play":
        include("sources/play.php");
        break;
    case "embed":
        include("sources/embed.php");
        break;
    case "search":
        include("sources/search.php");
        break;
    case "category":
        include("sources/category.php");
        break;
    case "top-picks":
    case "trending":
    case "new":
        include("sources/subject.php");
        break;
    case "about":
        include("sources/about.php");
        break;
    case "sources":
        if (isset($_GET['track'])) {
            $source_name = protect($_GET['track']);
            $check = $db->query("SELECT * FROM traffic_sources WHERE source_name='$source_name'");
            if ($check->num_rows > 0) {
                $row = $check->fetch_assoc();
                $lifetime_hits = $row['lifetime'] + 1;
                $db->query("UPDATE traffic_sources SET lifetime='$lifetime_hits' WHERE id='{$row['id']}'");
            }
        }
        header("Location: {$settings['url']}");
        exit();
    case "modes":
        if (isset($_GET['mode'])) {
            $mode = protect($_GET['mode']);
            $_SESSION['mode'] = ($mode === "dark") ? "dark" : "light";
        }
        if (isset($_GET['redirect'])) {
            $redirect_url = (strpos($_GET['redirect'], "https:/") === 0) ? str_replace("https:/", "https://", $_GET['redirect']) : $settings['url'];
            header("Location: $redirect_url");
        } else {
            header("Location: {$settings['url']}");
        }
        exit();
    case "logout":
        session_unset();
        session_destroy();
        setcookie("uid", "", time() - 86400, '/'); // 86400 = 1 day
        header("Location: {$settings['url']}");
        exit();
    default:
        include("sources/home.php");
}

mysqli_close($db);
