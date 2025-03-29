<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Dashboard</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
					
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content mt-3">
    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-primary">
                <div class="card-body pb-0">
                    <div class="dropdown float-right">
                        <i class="fa fa-gamepad"></i>
                    </div>
                    <h4 class="mb-0">
                        <span class="count"><?php $query = $db->query("SELECT * FROM games"); echo $query->num_rows; ?></span>
                    </h4>
                    <p class="text-light">Games</p>
    
                </div>
            </div>
        </div>
      	<div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-success">
                <div class="card-body pb-0">
                    <div class="dropdown float-right">
                        <i class="fa fa-gamepad"></i>
                    </div>
                    <h4 class="mb-0">
                        <span class="count"><?php $query = $db->query("SELECT * FROM games WHERE status='enable'"); echo $query->num_rows; ?></span>
                    </h4>
                    <p class="text-light">Active Games</p>
    
                </div>
    
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-dark bg-warning">
                <div class="card-body pb-0">
                    <div class="dropdown float-right">
    					<i class="fa fa-th"></i>
    				</div>
                    <h4 class="mb-0">
                         <span class="count"><?php $query = $db->query("SELECT * FROM game_categories"); echo $query->num_rows; ?></span>
                    </h4>
                    <p class="text-dark">Game Categories</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-danger">
                <div class="card-body pb-0">
                    <div class="dropdown float-right">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <h4 class="mb-0">
                        <?php 
                            $calc_playtime = $db->query("SELECT SUM(Playtime) FROM games");
                            echo playtime_format($calc_playtime->fetch_row()[0]);
                        ?>
                        /
                        <?php 
                            $calc_played = $db->query("SELECT SUM(Played) FROM games");
                            echo $calc_played->fetch_row()[0];
                        ?>
                    </h4>
                    <p class="text-light">Total Playtime / Game Played</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-info">
                <div class="card-body pb-0">
                    <div class="dropdown float-right">
                        <i class="fa fa-gamepad"></i>
                    </div>
                    <h4 class="mb-0">
                        <span class="count"><?php $query = $db->query("SELECT * FROM games WHERE Company='Gamepix.com'"); echo $query->num_rows; ?></span>
                    </h4>
                    <p class="text-light">Gamepix</p>
    
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-info">
                <div class="card-body pb-0">
                    <div class="dropdown float-right">
                        <i class="fa fa-gamepad"></i>
                    </div>
                    <h4 class="mb-0">
                        <span class="count"><?php $query = $db->query("SELECT * FROM games WHERE Company='Gamemonetize.com'"); echo $query->num_rows; ?></span>
                    </h4>
                    <p class="text-light">Game Monetize</p>
    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-md-6">
		<div class="card">
                <div class="card-header">
                    <strong class="card-title">TOP 10 Games</strong>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                      <thead>
                        <tr>
							<th width="">Game Name</th>
                            <th width="8%">Category</th>
                            <th width="8%">Played</th>
                            <th width="8%">Playtime</th>
						</tr>
                      </thead>
                      <tbody>
						<?php
						$i=1;
						$query = $db->query("SELECT * FROM games WHERE Played!=0 ORDER BY Played DESC LIMIT 10");
						if($query && $query->num_rows>0) {
							while($row = $query->fetch_assoc()) {
							    $active_thumbnail = $row["ActiveThumbnail"];
                                if($active_thumbnail == 1) {
                                    $thumbnail = "../uploads/images/".$row["image_store_url"];
                                }else {
                                    $thumbnail = (is_array(json_decode($row['Thumbnail']))) ? json_decode($row['Thumbnail'])[0] : $row["Thumbnail"];
                                }
								?>
								<tr>
                                    <td><?php echo "<img src='$thumbnail' style='border-radius:10px;width:70px;height:50px'/>" ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row['Title'] ?>&nbsp;&nbsp;<a class="text-danger" style="font-size:12px;" target="_blank" href="./?a=games&b=edit&id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a></td>
                                    <td class="text-info"><?php echo $row['Category']; ?></td>
                                    <td class="text-success"><?php echo (!$row['Played']) ? 0 : $row['Played'] ?></td>
                                    <td><?php echo playtime_format($row['Playtime']); ?></td>
                                </tr>
								<?php
							}
						} else {
							echo '<tr><td colspan="4">No records found</td></tr>';
						}
						?>
                      </tbody>
                    </table>
                </div>
            </div>
	</div>
	<div class="col-md-6">
		<div class="card">
                <div class="card-header">
                    <strong class="card-title">Top 10 FEATURED Games</strong>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                      <thead>
                        <tr>
							<th width="">Game Name</th>
                            <th width="8%">Category</th>
                            <th width="8%">Played</th>
                            <th width="8%">Playtime</th>
						</tr>
                      </thead>
                      <tbody>
						<?php
						$queryw = $db->query("SELECT * FROM games WHERE Featured='yes' LIMIT 10");
						if($queryw && $queryw->num_rows>0) {
							while($roww = $queryw->fetch_assoc()) {
							    $active_thumbnail = $roww["ActiveThumbnail"];
                                if($active_thumbnail == 1) {
                                    $thumbnail = "../uploads/images/".$roww["image_store_url"];
                                }else {
                                    $thumbnail = (is_array(json_decode($roww['Thumbnail']))) ? json_decode($roww['Thumbnail'])[0] : $roww["Thumbnail"];
                                }
								?>
								<tr>
                                    <td><?php echo "<img src='$thumbnail' style='border-radius:10px;width:70px;height:50px'/>" ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $roww['Title'] ?>&nbsp;&nbsp;<a class="text-danger" style="font-size:12px;" target="_blank" href="./?a=games&b=edit&id=<?php echo $roww['id']; ?>"><i class="fa fa-edit"></i></a></td>
                                    <td><?php echo $roww['Category']; ?></td>
                                    <td><?php echo (!$roww['Played']) ? 0 : $roww['Played'] ?></td>
                                    <td><?php echo playtime_format($roww['Playtime']); ?></td>
                                </tr>
								<?php
							}
						} else {
							echo '<tr><td colspan="4">No records found</td></tr>';
						}
						?>
                      </tbody>
                    </table>
                </div>
            </div>
	</div>
	</div>
</div> <!-- .content -->