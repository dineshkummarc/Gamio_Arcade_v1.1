<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
$url_cano = "https://";   
else  
$url_cano = "http://";   
$url_cano.= $_SERVER['HTTP_HOST'];   
$url_cano.= $_SERVER['REQUEST_URI'];    
if(isset($_POST["gameFrame"]) && $_POST["gameFrame"] == "true") {
    if(isset($_POST["play"])){
      $play = $_POST["play"];
    } else {
        $play = "1111";
    }
    if(isset($_POST["gftm"])){
      $timer = $_POST["gftm"];
    } else {
        $timer = "1111";
    }
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { 
        $slug = addslashes(protect(htmlspecialchars($_GET["play"])));
        $game_check = $db->query("SELECT * FROM games WHERE Slug='$slug'");
        if($game_check && $game_check->num_rows > 0) {
            $game_datas = $game_check->fetch_assoc();
            if($play == "1") {
                $db->query("UPDATE games SET Played=Played+1 WHERE Slug='$slug'");
                if (checkSession()) {
                    //Creating user stats
                    $db->query("UPDATE users SET Played=Played+1 WHERE id='$_SESSION[uid]'"); 
                    $time = time();
                    //Creating user logs
                    $log_check = $db->query("SELECT * FROM users_logs WHERE u_field_1='$game_datas[id]' and uid='$_SESSION[uid]'");
                    if($log_check && $log_check->num_rows > 0) {
                        $log_datas = $log_check->fetch_assoc();
                        $update= $db->query("UPDATE users_logs SET time=$time WHERE uid='$_SESSION[uid]' and u_field_1='$game_datas[id]'"); 
                    } else {
                        $insert = $db->query("INSERT users_logs (uid,type,time,u_field_1,u_field_2) VALUES ('$_SESSION[uid]','10','$time','$game_datas[id]','$slug')");
                    }
                    
                    //Integration of Leaderboard
                    if ($settings['leaderboard'] == "1") {
                        $leaderboard_check = $db->query("SELECT * FROM leaderboard WHERE uid='$_SESSION[uid]'");
                        if($leaderboard_check && $leaderboard_check->num_rows > 0) {
                            $update= $db->query("UPDATE leaderboard SET played_games=played_games+1 WHERE uid='$_SESSION[uid]'");
                            //Verifing is this new game... (Pending)
                        } else {
                            $insert = $db->query("INSERT leaderboard (uid,played_games) VALUES ('$_SESSION[uid]','1')");
                        }
                    }
                    
                    //exit("dddddddddddddddddddddddddd $_SESSION[uid]");
                }
                exit();
                //exit("ok played $game_datas[Played]");
            } else if($timer == "1") {
                $db->query("UPDATE games SET Playtime=Playtime+1 WHERE Slug='$slug'");
                if (checkSession()) {
                    $db->query("UPDATE users SET Playtime=Playtime+1 WHERE id='$_SESSION[uid]'");
                    
                    //Integration of Leaderboard
                    if ($settings['leaderboard'] == "1") {
                        $leaderboard_check = $db->query("SELECT * FROM leaderboard WHERE uid='$_SESSION[uid]'");
                        if($leaderboard_check && $leaderboard_check->num_rows > 0) {
                            $update= $db->query("UPDATE leaderboard SET play_time=play_time+1 WHERE uid='$_SESSION[uid]'");
                            //Verifing is this new game... (Pending)
                        } else {
                            $insert = $db->query("INSERT leaderboard (uid,play_time) VALUES ('$_SESSION[uid]','1')");
                        }
                    }
                }
                exit();
                //exit("ok timer $game_datas[Playtime]");
            }
        } else {
            exit();
            //exit("error php");
        }
    } else{
        exit();
        //exit("not ajax");
    }
}
  
if(isset($_GET["play"]) && !empty($_GET["play"])) {
    $Md = protect($_GET["play"]);
    $game_check = $db->query("SELECT * FROM games WHERE Slug='$Md' and status='enable'");
    if($game_check && $game_check->num_rows > 0) {
        $game_data = $game_check->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$game_data['Title'];?> - <?=$settings['title'];?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .game-frame-container {
            flex: 1 1 auto;
            overflow: hidden;
            position: relative;
        }
        .game-frame {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        .banner {
            flex: 0 0 auto;
            text-align: center;
        }
        .banner img {
            width: 100%;
            max-width: 600px; /* Adjust max width as needed */
        }
    </style>
    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="gameframeouter">
        <?php 
        if ($game_data["Company"] == "Gamedistribution.com") {
            $gurl = "$game_data[Url]?gd_sdk_referrer_url=$url_cano";
        } else {
            $gurl = $game_data["Url"];
        }
        ?>
        <iframe id="gameFrame" class="game-frame" src="<?=$gurl; ?>" title="<?=$game_data["Title"];?>" allowfullscreen></iframe>
    </div>
    <!--
    <div class="banner">
        <?=advertisement(2);?>
    </div>
    -->
     <script>
      var playTime = 0;
      var focusCheckInterval = 1000; // Check focus every second
      var focusTimeInterval;
      
      // Function to check if iframe is in focus
      function checkFocus() {
        if(document.activeElement == document.getElementById("gameFrame")) {
          if(!focusTimeInterval) {
            focusTimeInterval = setInterval(function() {
              playTime++;
              if(playTime == 5) { // Send play count after 5 seconds
                $.ajax({
                  type: "POST",
                  url: window.location.href,
                  data: "gameFrame=true&play=1",
                  success: function(response) {
                    console.log(response);
                  },
                  error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error);
                  }
                });
              }
              if(playTime % 10 === 0) { // Send playtime every 10 seconds
                $.ajax({
                  type: "POST",
                  url: window.location.href,
                  data: "gameFrame=true&gftm=1",
                  success: function(response) {
                    console.log(response);
                  },
                  error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error);
                  }
                });
              }
            }, 1000);
          }
        } else {
          if(focusTimeInterval) {
            clearInterval(focusTimeInterval);
            focusTimeInterval = null;
          }
        }
      }

      // Check focus state every second
      setInterval(checkFocus, focusCheckInterval);

      // Function to reload iframe on error
      function reloadIframe() {
        var iframe = document.getElementById('gameFrame');
        iframe.src = iframe.src; // Reloads the iframe by setting its src attribute again
      }

      // Check for errors and reload iframe
      var iframe = document.getElementById('gameFrame');
      iframe.addEventListener('error', function(event) {
        console.error('Error loading iframe:', event);
        reloadIframe();
      });
    </script>
</body>
</html>

<?php
    
  } else {
    header("Location: /");
  }
  
}
?>
