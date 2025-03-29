<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
$b = (isset($_GET['b'])) ? protect($_GET['b']) : "";

if($b == "add") {
    $addGames = isset($_POST['addgames']) ? protect($_POST['addgames']) : "";
        if($addGames == "true") {
            $add_type = protect($_POST["addgames_type"]);
            if($add_type == "manual") {
                $title = ucwords(strtolower(protect($_POST["title"])));
                $description = addslashes($_POST["description"]);
                $thumbnail = protect($_POST["thumbnail"]);
                $url = protect($_POST["url"]);
                $category = addslashes($_POST["category"]);
                $company = protect($_POST["game_company"]);
                $status = protect($_POST["status"]);
                $res = strtolower($title);
                $res = preg_replace('/[^[:alnum:]]/', ' ', $res);
                $res = preg_replace('/[[:space:]]+/', '-', $res);
                $slug = trim($res, '-');
                $time = time();
                if(empty($title)) {
                    echo error_a("Please enter game title");
                }else if(empty($url)) {
                    echo error_a("Please enter game url");
                }

                $check_games = $db->query("SELECT * FROM games WHERE Title = '$title' AND Url = '$url'");
                $check_slug = $db->query("SELECT * FROM games WHERE Slug = '$slug'");
                if($check_games->num_rows > 0) {
                    echo error_a("The Game is already in database, please try to enter different Title or Game Url");
                } elseif($check_slug->num_rows > 0) {
                    echo error_a("The Game slug is already in database, please try to enter different slug.");
                }else {
                    $ins = $db->query("INSERT INTO games(Slug, Title, Description, Thumbnail, Url, Category, Company, Status, created) 
                    VALUES('$slug', '$title', '$description', '$thumbnail', '$url', '$category', '$company', '$status', '$time')");
                    if($ins) {
                        echo success_a("Game has been added!");
                    }else {
                        echo error_a("Something went wrong. please try again");
                    }
                }
            } elseif($add_type == "bulk") {
                $company = protect($_POST["company"]);
                if(empty($company)) {
                    echo error_a("Please select a game company");
                }
    
                if($company == "1") {
                    $added = 0;
                    $notadded = 0;
                    $failadded = 0;
                    
                    for ($x = 1; $x <= 201; $x++) {
                        $game_data = "https://catalog.api.gamedistribution.com/api/v2.0/rss/All/?collection=all&categories=All&tags=All&subType=all&type=all&mobile=all&rewarded=all&amount=100&page=$x&format=json";
                        $game_list = file_get_contents($game_data);
                        $game_list = json_decode($game_list);
                        foreach($game_list as $data) {
                            $title = ucwords(strtolower($data->Title));
                            $md5 = !empty($data->Md5) ? $data->Md5 : null;
                            $description = !empty($data->Description) ? addslashes($data->Description) : null;
                            $instructions = !empty($data->Instructions) ? addslashes($data->Instructions) : null;
                            $thumbnail = !empty($data->Asset) ? json_encode($data->Asset) : null;
                            $type = !empty($data->Type) ? $data->Type : null;
                            $subtype = !empty($data->SubType) ? $data->SubType : null;
                            $mobile = !empty($data->Mobile) ? $data->Mobile : null;
                            $mobile_mode = !empty($data->MobileMode) ? $data->MobileMode : null;
                            $height = !empty($data->Height) ? $data->Height : null;
                            $width = !empty($data->Width) ? $data->Width : null;
                            $https = !empty($data->Https) ? $data->Https : null;
                            $status = !empty($data->Status) ? $data->Status : null;
                            $url = !empty($data->Url) ? $data->Url : null;
                            $asset = !empty($data->Asset) ? json_encode($data->Asset) : null;
                            $category = !empty($data->Category) ? addslashes(implode(", ", $data->Category)) : null;
                            $tag = !empty($data->Tag) ? addslashes(implode(", ", $data->Tag)) : null;
                            $res = strtolower($title);
                            $res = preg_replace('/[^[:alnum:]]/', ' ', $res);
                            $res = preg_replace('/[[:space:]]+/', '-', $res);
                            $slug = trim($res, '-');
                            $titleee = mysqli_real_escape_string($db, $title);    
                            $check_games = $db->query("SELECT * FROM games WHERE Title = '$titleee' AND Url = '$url'");
                            $time = time(); 
                            if ($check_games) {
                                if ($check_games->num_rows == 0) {
                                    // If game does not exist, insert into database
                                    $check_slug = $db->query("SELECT * FROM games WHERE Slug = '$slug'");
                                    if ($check_slug->num_rows > 0) {
                                        $segments = explode('-', $slug);
                                        $segments = array_filter($segments);
                                        if (!empty($segments)) {
                                            $randomSegment = array_rand($segments);
                                            $randomContent = $segments[$randomSegment];
                                        } else {
                                            $randomContent = 'game';
                                        }
                                        $slug = $slug . '-' . $randomContent;
                                    }
                
                                    // Insert game into database
                                    $ins = $db->query("INSERT INTO `games`(`Slug`, `Title`, `Md5`, `Description`, `Instructions`, `Thumbnail`, `Type`, `SubType`, `Mobile`, `MobileMode`, `Height`, `Width`, `Https`, `Status`, `Url`, `Asset`, `Category`, `Tag`, `Company`, `created`) 
                                        VALUES ('$slug', '$titleee','$md5','$description','$instructions','$thumbnail','$type','$subtype','$mobile','$mobile_mode','$height','$width','$https','enable','$url','$asset','$category','$tag', 'Gamedistribution.com', '$time')");
                
                                    if ($ins) {
                                        $added++;
                                    } else {
                                        $failadded++;
                                    }
                                } else {
                                    $notadded++;
                                }
                                $check_games->free();
                            }
                        }
                    }

                    if($added > 1 && $notadded == 0 && $failadded == 0) {
                        echo success_a("$added Games has been added into database");
                    }else if($added > 1 && $notadded > 0 && $failadded == 0) {
                        echo success_a("$added Games has been added into database");
                        echo error_a("$notadded Games cannot be added because the games is already in database");
                    }else if($added > 1 && $notadded == 0 && $failadded > 0) {
                        echo success_a("$added Games has been added into database");
                        echo error_a("$failadded Games cannot be added because have an error in the data");
                    }else if($added > 1 && $notadded > 0 && $failadded > 0) {
                        echo success_a("$added Games has been added into database");
                        echo error_a("$notadded Games cannot be added because the games is already in database");
                        echo error_a("$failadded Games cannot be added because have an error in the data");
                    }else if($added == 0 && $notadded > 0 && $failadded > 0) {
                        echo error_a("$notadded Games cannot be added because the games is already in database");
                        echo error_a("$failadded Games cannot be added because have an error in the data");
                    }else if($added == 0 && $notadded > 0 && $failadded == 0) {
                        echo error_a("$notadded Games cannot be added because the games is already in database");
                    }else if($added == 0 && $notadded == 0 && $failadded > 0) {
                        echo error_a("$failadded Games cannot be added because have an error in the data");
                    }else {
                        echo error_a("Something went wrong, please try again");
                    }
                } else if($company == "2") {
                    if (empty($settings['gamepix_id'])) {
                        echo error_a("Please add Gamepix ID under Web Settings first.");
                    } else {
                        $added = 0;
                        $notadded = 0;
                        $failadded = 0;
                        // Loop through all 71 pages
                        for ($page = 1; $page <= 71; $page++) {
                            // Construct the URL for each page
                            $url = "https://feeds.gamepix.com/v2/json?sid=$settings[gamepix_id]&pagination=96&page={$page}";
                        
                            // Fetch JSON data from the API
                            $game_list = file_get_contents($url);
                            $game_list = json_decode($game_list);
                        
                            // Check if data retrieval was successful
                            if (!empty($game_list)) {
                                $game_data = $game_list->items;
                        
                                // Process each game entry
                                foreach ($game_data as $data) {
                                    // Sanitize and prepare data for insertion
                                    $title = ucwords(strtolower($data->title));
                                    $description = !empty($data->description) ? addslashes($data->description) : "";
                                    $height = !empty($data->height) ? protect($data->height) : "";
                                    $width = !empty($data->width) ? protect($data->width) : "";
                                    $thumbnail = json_encode(array(addslashes($data->banner_image), addslashes($data->image)));
                                    $url = $data->url;
                                    $category = [];
                                    if (is_array($data->category)) {
                                        $category = array_map('addslashes', $data->category); // Add slashes to each category element
                                    } else {
                                        $category[] = addslashes($data->category); // Add slashes to the single category string
                                    }
                                    $category = implode(", ", $category); // Now implode the array
                                    $res = strtolower($title);
                                    $res = preg_replace('/[^[:alnum:]]/', ' ', $res);
                                    $res = preg_replace('/[[:space:]]+/', '-', $res);
                                    $slug = trim($res, '-');
                                    $time = time();
                                    $titleee = mysqli_real_escape_string($db, $title);
                        
                                    // Check if game already exists in database by Title and Url
                                    $check_games = $db->query("SELECT * FROM games WHERE Title = '$titleee' AND Url = '$url'");
                                    if ($check_games) {
                                        if ($check_games->num_rows == 0) {
                                            // If game does not exist, insert into database
                                            $check_slug = $db->query("SELECT * FROM games WHERE Slug = '$slug'");
                                            if ($check_slug->num_rows > 0) {
                                                $segments = explode('-', $slug);
                                                $segments = array_filter($segments);
                                                if (!empty($segments)) {
                                                    $randomSegment = array_rand($segments);
                                                    $randomContent = $segments[$randomSegment];
                                                } else {
                                                    $randomContent = 'game';
                                                }
                                                $slug = $slug . '-' . $randomContent;
                                            }
                        
                                            // Insert game into database
                                            $ins = $db->query("INSERT INTO `games`(`Slug`, `Title`, `Description`, `Thumbnail`, `Height`, `Width`, `Url`, `Category`, `Company`, `Status`, `created`) 
                                            VALUES ('$slug', '$titleee','$description','$thumbnail','$height','$width','$url','$category', 'Gamepix.com', 'enable', '$time')");
                        
                                            if ($ins) {
                                                $added++;
                                            } else {
                                                $failadded++;
                                            }
                                        } else {
                                            $notadded++;
                                        }
                                        $check_games->free();
                                    }
                                }
                            } else {
                                echo error_a("Something went wrong fetching data from page $page");
                            }
                        }
                    
                        // Output results
                        if ($added > 0) {
                            echo success_a("$added games have been added into the database.");
                        }
                        if ($notadded > 0) {
                            echo error_a("$notadded games were already in the database and not added.");
                        }
                        if ($failadded > 0) {
                            echo error_a("$failadded games could not be added due to an error.");
                        }
                    }
                    
                } elseif($company == "3") {
                    $game_data = file_get_contents("https://gamemonetize.com/rssfeed.php?format=json&category=All&type=html5&popularity=newest&company=All&amount=All");
                    $game_list = json_decode($game_data);
                    $added = 0;
                    $notadded = 0;
                    $failadded = 0;
                    foreach($game_list as $data){
                        $title = ucwords(strtolower($data->title));
                        $description = !empty($data->description) ? addslashes($data->description) : "";
                        $instructions = !empty($data->instructions) ? addslashes($data->instructions) : "";
                        $height = !empty($data->height) ? protect($data->height) : "";
                        $width = !empty($data->width) ? protect($data->width) : "";
                        $thumbnail = addslashes($data->thumb);
                        preg_match('/https:\/\/img\.gamemonetize\.com\/([a-zA-Z0-9]+)\/512x384\.jpg/', $thumbnail, $matches);
                        $md5 = $matches[1];
                        $url = $data->url;
                        $category = addslashes($data->category);
                        $tags = addslashes($data->tags);
                        $res = strtolower($title);
                        $res = preg_replace('/[^[:alnum:]]/', ' ', $res);
                        $res = preg_replace('/[[:space:]]+/', '-', $res);
                        $slug = trim($res, '-');
                        $time = time();
                        $titleee = mysqli_real_escape_string($db, $title);
                        $check_games = $db->query("SELECT * FROM games WHERE Title = '$titleee' AND Url = '$url'");
                        $check_slug = $db->query("SELECT * FROM games WHERE Slug = '$slug'");
                        if($check_games) {
                            if($check_games->num_rows == 0) {
                                if($check_slug->num_rows == 0) {
                                    $ins = $db->query("INSERT INTO `games`(`Slug`, `Title`, `Md5`, `Description`, `Instructions`, `Thumbnail`, `Height`, `Width`, `Url`, `Category`, `Tag`, `Company`, `Status`, `created`) 
                                    VALUES ('$slug', '$titleee', '$md5','$description','$instructions','$thumbnail','$height','$width','$url','$category','$tags', 'Gamemonetize.com', 'enable', '$time')");
                                    if($ins) {
                                        $added++;
                                    }else {
                                        $failadded++;
                                    }
                                } else {
                                    $segments = explode('-', $slug);
                                    $segments = array_filter($segments);
                                    if (!empty($segments)) {
                                        $randomSegment = array_rand($segments);
                                        $randomContent = $segments[$randomSegment];
                                    } else {
                                        $randomContent = 'game';
                                    }
                                    $slug = $slug . '-' . $randomContent;
                                    $ins = $db->query("INSERT INTO `games`(`Slug`, `Title`, `Md5`, `Description`, `Instructions`, `Thumbnail`, `Height`, `Width`, `Url`, `Category`, `Tag`, `Company`, `Status`, `created`) 
                                    VALUES ('$slug', '$titleee', '$md5','$description','$instructions','$thumbnail','$height','$width','$url','$category','$tags', 'Gamemonetize.com', 'enable', '$time')");
                                    if($ins) {
                                        $added++;
                                    }else {
                                        $failadded++;
                                    }
                                }
                                    
                            }else if($check_games->num_rows > 0) {
                                $notadded++;
                            }
                            unset($check_games);
                        }
                    }
                    if($added > 1 && $notadded == 0) {
                        echo success_a("$added Games has been added into database");
                    }else if($added > 1 && $notadded > 1) {
                        echo success_a("$added Games has been added into database");
                        echo error_a("$notadded Games cannot be added because the games is already in database");
                    }else if($added == 0 && $notadded > 1) {
                        echo error_a("$notadded Games cannot be added because the games is already in database");
                    }else {
                        echo error_a("Something went wrong, please try again");
                    }
                } else if($company == "4") {
                    $game_data = file_get_contents("http://api.famobi.com/feed");
                    $game_list = json_decode($game_data);
                    $game_list = $game_list->games;
                    $added = 0;
                    $notadded = 0;
                    $failadded = 0;
                    foreach($game_list as $data){
                        $title = ucwords(strtolower($data->name));
                        $description = !empty($data->description) ? addslashes($data->description) : "";
                        $thumbnail = json_encode(array(addslashes($data->thumb), addslashes($data->thumb_60), addslashes($data->thumb_120), addslashes($data->thumb_180)));
                        $url = $data->link;
                        $category = addslashes(implode(", ", $data->categories));
                        $slug = $data->package_id;
                        $titleee = mysqli_real_escape_string($db, $title);    
                        $time = time();
                        $check_games = $db->query("SELECT * FROM games WHERE Title = '$titleee' AND Url = '$url'");
                        $check_slug = $db->query("SELECT * FROM games WHERE Slug = '$slug'");
                        if($check_games) {
                            if($check_games->num_rows == 0) {
                                if($check_slug->num_rows == 0) {
                                    $ins = $db->query("INSERT INTO `games`(`Slug`, `Title`, `Description`, `Thumbnail`, `Url`, `Category`, `Company`, `Status`, `created`) 
                                    VALUES ('$slug', '$titleee','$description','$thumbnail','$url','$category', 'Famobi.com', 'enable', '$time')");
                                    if($ins) {
                                        $added++;
                                    }else {
                                        $failadded++;
                                    }
                                } else {
                                    $segments = explode('-', $slug);
                                    $segments = array_filter($segments);
                                    if (!empty($segments)) {
                                        $randomSegment = array_rand($segments);
                                        $randomContent = $segments[$randomSegment];
                                    } else {
                                        $randomContent = 'game';
                                    }
                                    $slug = $slug . '-' . $randomContent;
                                    $ins = $db->query("INSERT INTO `games`(`Slug`, `Title`, `Description`, `Thumbnail`, `Url`, `Category`, `Company`, `Status`, `created`) 
                                    VALUES ('$slug', '$titleee','$description','$thumbnail','$url','$category', 'Famobi.com', 'enable', '$time')");
                                    if($ins) {
                                        $added++;
                                    }else {
                                        $failadded++;
                                    }
                                }
                            } else if($check_games->num_rows > 0) {
                                $notadded++;
                            }
                            unset($check_games);
                        }
                    }
                    if($added > 1 && $notadded == 0) {
                        echo success_a("$added Games has been added into database");
                    }else if($added > 1 && $notadded > 1) {
                        echo success_a("$added Games has been added into database");
                        echo error_a("$notadded Games cannot be added because the games is already in database");
                    }else if($added == 0 && $notadded > 1) {
                        echo error_a("$notadded Games cannot be added because the games is already in database");
                    }else {
                        echo error_a("Something went wrong, please try again");
                    }
                }else {
                    echo error_a("Something went wrong");
                }
            }
        }
    ?>		
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Games</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					<li>Add a Game</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<div class=" mt-3">
    <div class="col-md-12">
		<div class="card">
            <div class="card-body">
                <form action="" method="POST">
                    <input type="hidden" name="addgames" value="true"/>
                    <input type="radio" name="addgames_type" value="manual" id="AddManual" checked/> Add Games Manually&nbsp;&nbsp;
                    <input type="radio" name="addgames_type" value="bulk" id="AddBulk"/> Add Games in Bulk
                    <br><br>
                    <div id="addGameManually">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" placeholder="Title" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="description" placeholder="Description" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Thumbnail</label>
                                <input type="text" name="thumbnail" placeholder="Thumbnail Url" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Url</label>
                                <input type="text" name="url" placeholder="https://" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <input type="text" name="category" placeholder="Arcade, Puzzle, Adventure" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Company (optional)</label>
                                <input type="text" name="game_company" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="enable" selected>Enable</option>
                                    <option value="disable">Disable</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" name="btn_save" value="profile">Add</button>
                            </div>
                    </div>
                    <div id="addGamesinBulk" style="display: none;">
                            <div class="form-group">
                                <label>Company Name</label>
                                <select class="form-control" name="company">
                                    <option value="1">Gamedistribution.com</option>
                                    <option value="2">Gamepix.com</option>
                                    <option value="3">Gamemonetize.com</option>
                                    <option value="4">Famobi.com</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" name="btn_save" value="profile">Add</button>
                            </div>
                    </div>
                </form>
                <script>
                    $("#AddManual").on('click', function() {
                        $("#addGamesinBulk").hide();
                        $("#addGameManually").show();
                    });
                    $("#AddBulk").on('click', function() {
                        $("#addGamesinBulk").show();
                        $("#addGameManually").hide();
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<?php
} elseif ($b == "disable") { 
?>
<div class="breadcrumbs">
  <div class="col-sm-4">
    <div class="page-header float-left">
      <div class="page-title">
        <h1>Disable Games by Publisher</h1>
      </div>
    </div>
  </div>
  <div class="col-sm-8">
    <div class="page-header float-right">
      <div class="page-title">
        <ol class="breadcrumb text-right">
          <li></li>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class=" mt-3">
   	<div class="col-md-12">
     	<div class="card">
       		<div class="card-body">
              	<?php
  					$FormBTN = isset($_POST['btn_savecc']) ? protect($_POST['btn_savecc']) : "";
            		if($FormBTN == "disable") {
                      $publisher = protect($_POST["company"]);
                      
                       if ($publisher == "1") {
                         	$chk_games = $db->query("SELECT * FROM games WHERE status='enable' and Company='Gamedistribution.com'");
                            if($chk_games->num_rows==0) {
                                echo error_a("All Games are already disabled.");
                            } else {
                                $query = $db->query("SELECT * FROM games WHERE status='enable' and Company='Gamedistribution.com'");
                                while($row = $query->fetch_assoc()) {
                                    $db->query("UPDATE games SET status='disable' WHERE id='$row[id]'");
                                }
                                echo success_a("Games Disabled.");
                            }
                       } elseif ($publisher == "2") {
                         	$chk_games = $db->query("SELECT * FROM games WHERE status='enable' and Company='Gamepix.com'");
                            if($chk_games->num_rows==0) {
                                echo error_a("All Games are already disabled.");
                            } else {
                                $query = $db->query("SELECT * FROM games WHERE status='enable' and Company='Gamepix.com'");
                                while($row = $query->fetch_assoc()) {
                                    $db->query("UPDATE games SET status='disable' WHERE id='$row[id]'");
                                }
                                echo success_a("Games Disabled.");
                            }
                       } elseif ($publisher == "3") {
                         	$chk_games = $db->query("SELECT * FROM games WHERE status='enable' and Company='Gamemonetize.com'");
                            if($chk_games->num_rows==0) {
                                echo error_a("All Games are already disabled.");
                            } else {
                                $query = $db->query("SELECT * FROM games WHERE status='enable' and Company='Gamemonetize.com'");
                                while($row = $query->fetch_assoc()) {
                                    $db->query("UPDATE games SET status='disable' WHERE id='$row[id]'");
                                }
                                echo success_a("Games Disabled.");
                            }
                       } elseif ($publisher == "4") {
                         	$chk_games = $db->query("SELECT * FROM games WHERE status='enable' and Company='Famobi.com'");
                            if($chk_games->num_rows==0) {
                                echo error_a("All Games are already disabled.");
                            } else {
                                $query = $db->query("SELECT * FROM games WHERE status='enable' and Company='Famobi.com'");
                                while($row = $query->fetch_assoc()) {
                                    $db->query("UPDATE games SET status='disable' WHERE id='$row[id]'");
                                }
                                echo success_a("Games Disabled.");
                            }
                       } else {
                         echo error("No Publisher Found.");
                       }
                    }
  				?>
  				<?=info_a("It will disable all games of below selected publisher. This is a tool which help you to disable games in bulk in relation to publisher.");?>
              	<form action="" method="POST">
		 			<div class="form-group">
                      <label>Company Name</label>
                      <select class="form-control" name="company">
                        <option value="1">Gamedistribution.com</option>
                        <option value="2">Gamepix.com</option>
                        <option value="3">Gamemonetize.com</option>
                        <option value="4">Famobi.com</option>
                      </select>
                 </div>
                 <div class="form-group">
                   <button type="submit" class="btn btn-primary" name="btn_savecc" value="disable">Disable Games</button>
                 </div>
              </form>
        	</div>
		</div>
	</div>
</div>

<?php
} elseif ($b == "enable") { 
?>
<div class="breadcrumbs">
  <div class="col-sm-4">
    <div class="page-header float-left">
      <div class="page-title">
        <h1>Enable Games by Publisher</h1>
      </div>
    </div>
  </div>
  <div class="col-sm-8">
    <div class="page-header float-right">
      <div class="page-title">
        <ol class="breadcrumb text-right">
          <li></li>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class=" mt-3">
   	<div class="col-md-12">
     	<div class="card">
       		<div class="card-body">
              	<?php
  					$FormBTN = isset($_POST['btn_savecc']) ? protect($_POST['btn_savecc']) : "";
            		if($FormBTN == "disable") {
                      $publisher = protect($_POST["company"]);
                      
                       if ($publisher == "1") {
                         	$chk_games = $db->query("SELECT * FROM games WHERE status='disable' and Company='Gamedistribution.com'");
                            if($chk_games->num_rows==0) {
                                echo error_a("All Games are already enabled.");
                            } else {
                                $query = $db->query("SELECT * FROM games WHERE status='disable' and Company='Gamedistribution.com'");
                                while($row = $query->fetch_assoc()) {
                                    $db->query("UPDATE games SET status='enable' WHERE id='$row[id]'");
                                }
                                echo success_a("Games Enabled.");
                            }
                       } elseif ($publisher == "2") {
                         	$chk_games = $db->query("SELECT * FROM games WHERE status='disable' and Company='Gamepix.com'");
                            if($chk_games->num_rows==0) {
                                echo error_a("All Games are already enabled.");
                            } else {
                                $query = $db->query("SELECT * FROM games WHERE status='disable' and Company='Gamepix.com'");
                                while($row = $query->fetch_assoc()) {
                                    $db->query("UPDATE games SET status='enable' WHERE id='$row[id]'");
                                }
                                echo success_a("Games Enabled.");
                            }
                       } elseif ($publisher == "3") {
                         	$chk_games = $db->query("SELECT * FROM games WHERE status='disable' and Company='Gamemonetize.com'");
                            if($chk_games->num_rows==0) {
                                echo error_a("All Games are already enabled.");
                            } else {
                                $query = $db->query("SELECT * FROM games WHERE status='disable' and Company='Gamemonetize.com'");
                                while($row = $query->fetch_assoc()) {
                                    $db->query("UPDATE games SET status='enable' WHERE id='$row[id]'");
                                }
                                echo success_a("Games Enabled.");
                            }
                       } elseif ($publisher == "4") {
                         	$chk_games = $db->query("SELECT * FROM games WHERE status='disable' and Company='Famobi.com'");
                            if($chk_games->num_rows==0) {
                                echo error_a("All Games are already enabled.");
                            } else {
                                $query = $db->query("SELECT * FROM games WHERE status='disable' and Company='Famobi.com'");
                                while($row = $query->fetch_assoc()) {
                                    $db->query("UPDATE games SET status='enable' WHERE id='$row[id]'");
                                }
                                echo success_a("Games Enabled.");
                            }
                       } else {
                         echo error("No Publisher Found.");
                       }
                    }
  				?>
  				<?php echo info_a("It will enable all games of below selected publisher. This is a tool which help you to enable games in bulk in relation to publisher.");?>
              	<form action="" method="POST">
		 			<div class="form-group">
                      <label>Company Name</label>
                      <select class="form-control" name="company">
                        <option value="1">Gamedistribution.com</option>
                        <option value="2">Gamepix.com</option>
                        <option value="3">Gamemonetize.com</option>
                        <option value="4">Famobi.com</option>
                      </select>
                 </div>
                 <div class="form-group">
                   <button type="submit" class="btn btn-primary" name="btn_savecc" value="disable">Enable Games</button>
                 </div>
              </form>
        	</div>
		</div>
	</div>
</div>

    <?php

}else if($b == "edit") {
	$id = protect($_GET['id']);
	?>
	<div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Games</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
						<li>Edit Game</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="col-md-12">
            <?php
            $FormBTN = isset($_POST['btn_save']) ? protect($_POST['btn_save']) : "";
            if($FormBTN == "game_update") {
                $title = addslashes($_POST['title']);
                $md5 = protect($_POST['md5']);
                $description = addslashes($_POST['description']);
                $instructions = addslashes($_POST['instructions']);
                $active_thumbnail = protect($_POST['active_thumbnail']);
                $featured = protect($_POST['featured']);
                $url = protect($_POST['url']);
                $category = addslashes($_POST['category']);
                $company = addslashes($_POST['company']);
                $slug = protect($_POST['slug']);
                $time =  time();
                if(empty($title)) {
                    echo error_a("Please enter a game title");
                }else if(empty($url)) {
                    echo error_a("Please enter a game url");
                }
                $check_slug = $db->query("SELECT * FROM games WHERE Slug='$slug' AND id!=$id ");
                $check_game = $db->query("SELECT * FROM games WHERE Title='$title' AND Url='$url' AND id!=$id ");
                if($check_slug && $check_slug->num_rows > 0){
                    echo error_a("Slug already used");
                }else if($check_game && $check_game->num_rows > 0) {
                    echo error_a("Title and Url is already used");
                }else {
                    $upd = $db->query("UPDATE games SET Slug='$slug', Title='$title', Md5='$md5', Description='$description', Instructions='$instructions', ActiveThumbnail='$active_thumbnail', Featured='$featured', Url='$url', Category='$category', Company='$company', updated='$time' WHERE id='$id'");
                    if($upd) {
                        echo success_a("Game has been updated");
                    }else {
                        echo error_a("Something went wrong, Please try again");
                    }
                }
            }
            
            $query = $db->query("SELECT * FROM games WHERE id='$id'");
            if($query->num_rows==0) { header("Location: ./?a=games"); }
            $row = $query->fetch_assoc();
            ?>
        </div>
        <div class="col-md-12">
			<div class="card">
                <div class="card-body">
                    <h3>Game Statistics</h3>
                    <hr/>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width='20%'>Total Played</th>
                                <td><?php echo ($row["Played"] == "") ? 0 : $row["Played"];?> Times</td>
                            </tr>
                            <tr>
                                <th width='20%'>Total Playtime</th>
                                <td><?php echo playtime_format($row["Playtime"]);?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
       <div class="col-md-12">
			<div class="card">
                <div class="card-body">
                    <h3>Game Information</h3>
                    <hr/>
	
                    <form action="" method="POST">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" value="<?=$row['Title'];?>">
                        </div>
                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" class="form-control" name="slug" value="<?=$row['Slug'];?>">
                        </div>
                        <div class="form-group">
                            <label>MD5 / Unique ID</label>
                            <input type="text" class="form-control" name="md5" value="<?=$row['Md5']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control"><?=$row["Description"];?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Instructions</label>
                            <textarea name="instructions" class="form-control"><?=$row["Instructions"];?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Thumbnail</label>
                            <div style='display:flex'>
                            <?php
                                    $thumbnail = (is_array(json_decode($row['Thumbnail']))) ? json_decode($row['Thumbnail'])[0] : $row["Thumbnail"];
                                ?>
                                
                                    <div>
                                            <label for='t0' style='display:block' class='m-2'>
                                                <img src='<?=$thumbnail;?>' width='150px'/>
                                            </label>
                                            <input type='radio' name='active_thumbnail' value='0' id='t0' class='mx-2 ' <?php echo ($row["ActiveThumbnail"] == 0) ? 'checked' : ""; ?>/> Url Thumbnail <?php echo ($row["ActiveThumbnail"] == 0) ? '(Active)' : ""; ?>
                                    </div>
                                <?php
                                if($row["image_store"] == 1) {
                                    $thumbnail = "../uploads/images/".$row["image_store_url"];
                                    ?>
                                    <div>
                                        <label for='t0' style='display:block' class='m-2'>
                                            <img src='<?=$thumbnail;?>' width='150px'/>
                                        </label>
                                        <input type='radio' name='active_thumbnail' value='1' id='t1' class='mx-2 ' <?php echo ($row["ActiveThumbnail"] == 1) ? 'checked' : ""; ?>/> File Thumbnail <?php echo ($row["ActiveThumbnail"] == 1) ? '(Active)' : ""; ?>
                                    </div>
                                    <?php
                                }
                            ?>
                                    
                            </div>
                            
                        </div>
                        <div class="form-group">
                            <label>Featured ?</label>
                            <select class="form-control" name="featured">
                                <option <?php echo ($row["Featured"] == 'yes') ? 'selected' : '';?> value='yes'>Yes</option>
                                <option <?php echo ($row["Featured"] == '') ? 'selected' : '';?> value='no'>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Url</label>
                            <input type="text" class="form-control" name="url" value="<?php echo $row['Url']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" class="form-control" name="category" value="<?php echo $row['Category']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Company</label>
                            <input type="text" class="form-control" name="company" value="<?php echo $row['Company']; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary" name="btn_save" value="game_update"><i class="fa fa-check"></i> Save changes</button>
                    </form>
                </div>
            </div>
        </div>
	<?php
} elseif($b == "delete") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM games WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=games"); }
	$row = $query->fetch_assoc();
	?>
		<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Games</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
							<li>Delete Game</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content mt-3">

           <div class="col-md-12">
					<div class="card">
                        <div class="card-body">
			<?php
			if(isset($_GET['confirm'])) {
				$delete = $db->query("DELETE FROM games WHERE id='$row[id]'");
				echo success_a("Game <b>$row[Title]</b> was deleted.");
			} else {
				echo info_a("Are you sure you want to delete <b>$row[Title]</b> game?<br/><small>Once this action is completed, game information will be deleted from the database and will not be restored.</small>");
				echo '<a href="./?a=games&b=delete&id='.$row["id"].'&confirm=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
					<a href="./?a=games" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
			}
			?>
		</div>
	</div>
	</div>
	<?php
} elseif($b == "images") {
    if(isset($_POST["store_images"]) && $_POST["store_images"] == "true") {
        $max_store = protect($_POST["max_store"]);
        $chk_img_stored = $db->query("SELECT * FROM games WHERE image_store=0");
        if($chk_img_stored && $chk_img_stored->num_rows==0) {
            echo error_a("All Games Thumbnail is Already Stored in File Manager");
        }else {
            $query = $db->query("SELECT * FROM games WHERE image_store=0");
            $added = 0;
            while($row = $query->fetch_assoc()) {
                $thumbnail = (is_array(json_decode($row['Thumbnail']))) ? json_decode($row['Thumbnail'])[0] : $row["Thumbnail"];
                if (filter_var($thumbnail, FILTER_VALIDATE_URL) === FALSE) { continue; }
                if($max_store !== "all") {
                    if($added >= $max_store) {
                        break;
                    }
                }
                $image_name = crc32($thumbnail).crc32(rand(1, 9999999999999999));
                $ext = ".webp";
                $img_folder = "../uploads/images/";
                $img = $img_folder.$image_name.$ext;
                $thumbnail_stored = $image_name.$ext;
                file_put_contents($img, file_get_contents($thumbnail));
                $db->query("UPDATE games SET Thumbnail='$thumbnail', ActiveThumbnail=1, image_store_url='$thumbnail_stored', image_store=1 WHERE id='$row[id]'");
                $added++;
                flush();
                ob_flush();
            }
            echo success_a("$added Games Thumbnail was Stored in File Manager");
        }
    }
	?>
	<div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Games</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
						<li>Manage Games Images</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
       <div class="col-md-12">
			<div class="card">
                <div class="card-body">
                    <h5>Store Images in File Manager</h5><br/>
                    <?php echo info_a("It will store Games Thumbnails/Images in your webhosting. This is a tool which help you to store games images and assets locally. There is lot of pros and cons of that. If you have a large storage in your Hosting/VPS you can store the images which help you to
                    speed up the website loading. It's can also help you to optimize your website and will help you further in SEO.");?>
                    <form action="" method="POST">
                        <input type="hidden" name="store_images" value="true"/>
                        <?php echo warn_a("Make sure to select as low as possible and Speed was depends on your Virtual Machine/Web Hosting. Selecting large numbers
                        will result in high loading time and sometimes Its get website down for some minutes. We suggest to download 100 images and then 100 towards."); ?>
                        <select name='max_store' class="form-control">
                            <option value="all">All Games</option>
                            <option value="100">100 Games Maximum</option>
                            <option value="200">200 Games Maximum</option>
                            <option value="500">500 Games Maximum</option>
                            <option value="1000">1000 Games Maximum</option>
                            <option value="2000">2000 Games Maximum</option>
                            <option value="5000">5000 Games Maximum</option>
                            <option value="10000">10000 Games Maximum</option>
                        </select>
                        <div class="col-md-3" style="padding:10px;">
                            <button type="submit" class="btn btn-primary btn-block" name="btn_search" value="games">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
	<?php
}else {
    ?>
	<div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>All Games</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12"><br>
		<div class="card">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-3" style="padding:10px;">
                            <input type="text" class="form-control" name="title" placeholder="Title" value="<?php if(isset($_POST['title'])) { echo $_POST['title']; } ?>">
                        </div>
                        <div class="col-md-3" style="padding:10px;">
                            <input type="text" class="form-control" name="category" placeholder="Category" value="<?php if(isset($_POST['category'])) { echo $_POST['category']; } ?>">
                        </div>
                        <div class="col-md-3" style="padding:10px;">
                            <input type="text" class="form-control" name="company" placeholder="Company" value="<?php if(isset($_POST['company'])) { echo $_POST['company']; } ?>">
                        </div>
                        <div class="col-md-3" style="padding:10px;">
                            <button type="submit" class="btn btn-primary btn-block" name="btn_search" value="games">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
   <div class="col-md-12">
		<div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="10%">Thumbnail</th>
                            <th width="25%">Title</th>
                            <th width="25%">Description</th>
                            <th width="5%">Category</th>
                            <th width="5%">Played</th>
                            <th width="5%">Playtime</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $searching=0;
                        $FormBTN = isset($_POST['btn_search']) ? protect($_POST['btn_search']) : "";
                        if($FormBTN == "games") {
                            $searching=1;
                            $search_query = array();
                            $title = isset($_POST['title']) ? protect($_POST['title']) : "";
                            if(!empty($title)) { $search_query[] = "Title LIKE '%$title%'"; }
                            $category = isset($_POST['category']) ? protect($_POST['category']) : "";
                            if(!empty($category)) { $search_query[] = "Category LIKE '%$category%'"; }
                            $company = isset($_POST['company']) ? protect($_POST['company']) : "";
                            if(!empty($company)) { $search_query[] = "Company LIKE '%$company%'"; }
                            $p_query = implode(" and ",$search_query);
                        }
                        $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                        $limit = 20;
                        $startpoint = ($page * $limit) - $limit;
                        if($page == 1) {
                            $i = 1;
                        } else {
                            $i = $page * $limit;
                        }
                        $statement = "games";
                        if($searching==1) {
                            if(empty($p_query)) {
                                $searching=0;
                                $query = $db->query("SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
                            }else {
                                $query = $db->query("SELECT * FROM {$statement} WHERE $p_query ORDER BY id LIMIT {$startpoint} , {$limit}");
                            }
                        } else {
                            $query = $db->query("SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
                        }
                        
                        if($query->num_rows>0) {
                            while($row = $query->fetch_assoc()) {
                                $active_thumbnail = $row["ActiveThumbnail"];
                                if($active_thumbnail == 1) {
                                    $thumbnail = "../uploads/images/".$row["image_store_url"];
                                }else {
                                    $thumbnail = (is_array(json_decode($row['Thumbnail']))) ? json_decode($row['Thumbnail'])[0] : $row["Thumbnail"];
                                }
                                
                                ?>
                                <tr>
                                    <td>
                                        <?php echo "<img src='$thumbnail' style='border-radius:10px;width:100px;height:70px'/>" ?>
                                    </td>
                                    <td>
                                        <?php echo $row['Title']; ?>
                                        &nbsp;&nbsp;<a class="text-danger" style="font-size:12px;" target="_blank" href="../play/<?php echo $row['Slug']; ?>"><i class="fa fa-external-link-square"></i></a>
                                    </td>
                                    <td><?php echo strlen($row['Description']) > 100 ? substr($row['Description'],0,100)."..." : $row['Description']; ?></td>
                                    <td><?php echo $row['Category']; ?></td>
                                    <td><?php echo empty($row['Played']) ? 0 : $row['Played']; ?></td>
                                    <td><?php echo playtime_format($row['Playtime']) ?></td>
                                    <td>
                                        <a href="./?a=games&b=edit&id=<?php echo $row['id']; ?>" title="Edit"><span class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</span></a> 
                                        <a href="./?a=games&b=delete&id=<?php echo $row['id']; ?>" title="Delete"><span class="badge badge-danger"><i class="fa fa-trash"></i> Delete</span></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            if($searching == "1") {
                                echo '<tr><td colspan="6">No found results.</td></tr>';
                            } else {
                                echo '<tr><td colspan="6">No have games yet.</td></tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                if($searching == "0") {
                    $ver = "./?a=games";
                    if(admin_pagination($statement,$ver,$limit,$page)) {
                        echo admin_pagination($statement,$ver,$limit,$page);
                    }
                }
                ?>
            </div>
        </div>
    </div>
<?php
}
?>