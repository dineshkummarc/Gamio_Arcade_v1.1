<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
function idinfo($uid,$value) {
	global $db;
	$query = $db->query("SELECT * FROM users WHERE id='$uid'");
	$row = $query->fetch_assoc();
	if (!empty($row[$value])) {
	    return $row[$value];
	} else {
	    return "";
	}
}


function CheckUser($email) {
	global $db;
	$query = $db->query("SELECT * FROM users WHERE email='$email'");
	if($query->num_rows>0) {
		// user with this email address is exists
		return true;
	} else {
		// user with this email address does not exists
		return false;
	}
}
function GetUserID($email) {
	global $db;
	$query = $db->query("SELECT * FROM users WHERE email='$email'");
	if($query->num_rows>0) {
		// user with this email address is exists
		$row = $query->fetch_assoc();
		return $row['id'];
	} else {
		// user with this email address does not exists
		return false;
	}
}

/**
 * Function to see if a string is a UK postcode or not. The postcode is also 
 * formatted so it contains no strings. Full or partial postcodes can be used.
 * 
 * @param string $toCheck
 * @return boolean 
 */
function postcode_check(&$toCheck) {
	// Permitted letters depend upon their position in the postcode.
	$alpha1 = "[abcdefghijklmnoprstuwyz]";                          // Character 1
	$alpha2 = "[abcdefghklmnopqrstuvwxy]";                          // Character 2
	$alpha3 = "[abcdefghjkstuw]";                                   // Character 3
	$alpha4 = "[abehmnprvwxy]";                                     // Character 4
	$alpha5 = "[abdefghjlnpqrstuwxyz]";                             // Character 5
   
	// Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA with a space
	// Or AN, ANN, AAN, AANN with no whitespace
	$pcexp[0] = '^(' . $alpha1 . '{1}' . $alpha2 . '{0,1}[0-9]{1,2})([[:space:]]{0,})([0-9]{1}' . $alpha5 . '{2})?$';
   
	// Expression for postcodes: ANA NAA
	// Or ANA with no whitespace
	$pcexp[1] = '^(' . $alpha1 . '{1}[0-9]{1}' . $alpha3 . '{1})([[:space:]]{0,})([0-9]{1}' . $alpha5 . '{2})?$';
   
	// Expression for postcodes: AANA NAA
	// Or AANA With no whitespace
	$pcexp[2] = '^(' . $alpha1 . '{1}' . $alpha2 . '[0-9]{1}' . $alpha4 . ')([[:space:]]{0,})([0-9]{1}' . $alpha5 . '{2})?$';
   
	// Exception for the special postcode GIR 0AA
	// Or just GIR
	$pcexp[3] = '^(gir)([[:space:]]{0,})?(0aa)?$';
   
	// Standard BFPO numbers
	$pcexp[4] = '^(bfpo)([[:space:]]{0,})([0-9]{1,4})$';
   
	// c/o BFPO numbers
	$pcexp[5] = '^(bfpo)([[:space:]]{0,})(c\/o([[:space:]]{0,})[0-9]{1,3})$';
   
	// Overseas Territories
	$pcexp[6] = '^([a-z]{4})([[:space:]]{0,})(1zz)$';
   
	// Anquilla
	$pcexp[7] = '^(ai\-2640)$';
   
	// Load up the string to check, converting into lowercase
	$postcode = strtolower($toCheck);
   
	// Assume we are not going to find a valid postcode
	$valid = false;
   
	// Check the string against the six types of postcodes
	foreach ($pcexp as $regexp) {
	  if (preg_match('/' . $regexp . '/i', $postcode, $matches)) {
   
		// Load new postcode back into the form element 
		$postcode = strtoupper($matches[1]);
		if (isset($matches[3])) {
		  $postcode .= ' ' . strtoupper($matches[3]);
		}
   
		// Take account of the special BFPO c/o format
		$postcode = preg_replace('/C\/O/', 'c/o ', $postcode);
   
		// Remember that we have found that the code is valid and break from loop
		$valid = true;
		break;
	  }
	}
   
	// Return with the reformatted valid postcode in uppercase if the postcode was 
	// valid
	if ($valid) {
	  $toCheck = $postcode;
	  return true;
	} else {
	  return false;
	}
}
function shrink($title,$limit) {
	return substr($title, 0, $limit);
}
function get_random_offset($table, $condition, $limit) {
    global $db;
    $count_query = "SELECT COUNT(*) as count FROM $table WHERE $condition";
    $count_result = $db->query($count_query);
    if (!$count_result) {
        // Handle query error
        return 0;
    }
    $count_row = $count_result->fetch_assoc();
    $count = $count_row['count'];
    return rand(0, max(0, $count - $limit));
}
function add_games(&$games, &$unique_game_ids, $new_games) {
    foreach ($new_games as $game) {
        if (!in_array($game['id'], $unique_game_ids)) {
            $games[] = $game;
            $unique_game_ids[] = $game['id'];
        }
    }
}
function refresh_featured_games() {
    global $db;
    
    // Define the limit of featured games to select
    $featured_limit = 50;
    
    // Fetch a mix of popular, new, and random games
    $offset_popular = get_random_offset('games', "status='enable'", '50');
    $popular_games_query = "SELECT * FROM games WHERE status='enable' ORDER BY Played DESC LIMIT $offset_popular, 50";
    $popular_games_result = $db->query($popular_games_query);
    $popular_games = $popular_games_result->fetch_all(MYSQLI_ASSOC);
    
    $offset_new = get_random_offset('games', "status='enable'", '30');
    $new_releases_query = "SELECT * FROM games WHERE status='enable' ORDER BY id DESC LIMIT $offset_new, 30";
    $new_releases_result = $db->query($new_releases_query);
    $new_releases = $new_releases_result->fetch_all(MYSQLI_ASSOC);
    
    $random_games_query = "SELECT * FROM games WHERE status='enable' ORDER BY RAND() LIMIT 20";
    $random_games_result = $db->query($random_games_query);
    $random_games = $random_games_result->fetch_all(MYSQLI_ASSOC);
    
    // Combine results into a unique associative array to avoid duplicates
    $featured_games = [];
    $unique_game_ids = [];
    
    add_games($featured_games, $unique_game_ids, $popular_games);
    add_games($featured_games, $unique_game_ids, $new_releases);
    add_games($featured_games, $unique_game_ids, $random_games);

    // Shuffle the combined array to mix different types of games
    shuffle($featured_games);

    // Limit the total number of featured games
    $featured_games = array_slice($featured_games, 0, $featured_limit);

    // Reset the featured status for all games
    $db->query("UPDATE games SET Featured='no' WHERE status='enable'");

    // Update the featured status for selected games
    foreach ($featured_games as $game) {
        $db->query("UPDATE games SET Featured='yes' WHERE id=" . $game['id']);
    }
}

function getRandomGames($max_recommended_games, $exclude_ids = []) {
    global $db;
    // Get the maximum ID in the table
    $result = $db->query("SELECT MAX(id) as max_id FROM games WHERE status='enable'");
    $row = $result->fetch_assoc();
    $max_id = $row['max_id'];

    $games = [];
    $selected_ids = $exclude_ids;

    while (count($games) < $max_recommended_games) {
        $random_id = mt_rand(1, $max_id);

        // Ensure the random ID has not been selected already
        if (in_array($random_id, $selected_ids)) {
            continue;
        }

        $game_check = $db->query("SELECT * FROM games WHERE id >= $random_id AND status='enable' LIMIT 1");
        if ($game_check && $game_check->num_rows > 0) {
            $game_data = $game_check->fetch_assoc();

            // Ensure the selected game ID has not been selected already
            if (in_array($game_data['id'], $selected_ids)) {
                continue;
            }

            $games[] = $game_data;
            $selected_ids[] = $game_data['id'];
        }
    }
    return $games;
}
?>