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
                <h1>Manage Content</h1>
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
   <div class="col-md-12">
		<div class="card">
            <div class="card-body">
        		<?php
        		if(isset($_POST['btn_save'])) {
                    // Sanitize input
                    $footer_section_h1 = protect($_POST['footer_section_h1']);
                    $footer_section_text = addslashes($_POST['footer_section_text']);
                    
                    $footer_section_h2 = protect($_POST['footer_section_h2']);
                    $footer_section_text2 = addslashes($_POST['footer_section_text2']);
                    
                    $footer_section_h2 = protect($_POST['footer_section_h2']);
                    $footer_section_text2 = addslashes($_POST['footer_section_text2']);
                    
                    $footer_section_h3 = protect($_POST['footer_section_h3']);
                    $footer_section_text3 = addslashes($_POST['footer_section_text3']);
                    
                    $footer_section_h4 = protect($_POST['footer_section_h4']);
                    $footer_section_text4 = addslashes($_POST['footer_section_text4']);
                    
                    $footer_section_h5 = protect($_POST['footer_section_h5']);
                    $footer_section_text5 = addslashes($_POST['footer_section_text5']);
                    
                    $new_page = addslashes($_POST['new_page']);
                    $toppick_page = addslashes($_POST['toppick_page']);
                    $trending_page = addslashes($_POST['trending_page']);
                    
                    $footer = protect($_POST['footer']);
                    $header = protect($_POST['header']);
                
                    // Prepare the SQL statement with placeholders
                    $sql = "UPDATE settings 
                            SET footer_section_h1=?, 
                                footer_section_text=?, 
                                footer_section_h2=?, 
                                footer_section_text2=?, 
                                footer_section_h3=?, 
                                footer_section_text3=?, 
                                footer_section_h4=?, 
                                footer_section_text4=?, 
                                footer_section_h5=?, 
                                footer_section_text5=?, 
                                new_page=?, 
                                toppick_page=?, 
                                trending_page=?, 
                                footer=?, 
                                header=?";
                
                    // Prepare the statement
                    $stmt = $db->prepare($sql);
                
                    // Bind parameters
                    $stmt->bind_param("sssssssssssssss", 
                        $footer_section_h1, 
                        $footer_section_text, 
                        $footer_section_h2, 
                        $footer_section_text2, 
                        $footer_section_h3, 
                        $footer_section_text3, 
                        $footer_section_h4, 
                        $footer_section_text4, 
                        $footer_section_h5, 
                        $footer_section_text5, 
                        $new_page, 
                        $toppick_page, 
                        $trending_page, 
                        $footer, 
                        $header
                    );
                
                    // Execute the statement
                    $result = $stmt->execute();
                
                    if($result) {
                        echo "<div class='alert alert-success'>Your changes were saved successfully.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Error: Unable to save changes.</div>";
                    }
                    $settingsQuery = $db->query("SELECT * FROM settings ORDER BY id DESC LIMIT 1");
                                        $settings = $settingsQuery->fetch_assoc();
                    // Close the statement
                    $stmt->close();
                }
        		?>
        		<form action="" method="POST">
        			<div class="form-group">
        				<label>Footer Section #1 Heading</label>
        				<textarea class="form-control" name="footer_section_h1"><?php echo $settings['footer_section_h1']; ?></textarea>
        			</div>
        			<div class="form-group">
						<label>Footer Section #1 Content</label>
						<textarea class="cleditor" rows="15" name="footer_section_text"><?php echo $settings['footer_section_text']; ?></textarea>
					</div>
					
					<div class="form-group">
        				<label>Footer Section #2 Heading</label>
        				<textarea class="form-control" name="footer_section_h2"><?php echo $settings['footer_section_h2']; ?></textarea>
        			</div>
        			<div class="form-group">
						<label>Footer Section #2 Content</label>
						<textarea class="cleditor" rows="15" name="footer_section_text2"><?php echo $settings['footer_section_text2']; ?></textarea>
					</div>
					
					<div class="form-group">
        				<label>Footer Section #3 Heading</label>
        				<textarea class="form-control" name="footer_section_h3"><?php echo $settings['footer_section_h3']; ?></textarea>
        			</div>
        			<div class="form-group">
						<label>Footer Section #3 Content</label>
						<textarea class="cleditor" rows="15" name="footer_section_text3"><?php echo $settings['footer_section_text3']; ?></textarea>
					</div>
					
					<div class="form-group">
        				<label>Footer Section #4 Heading</label>
        				<textarea class="form-control" name="footer_section_h4"><?php echo $settings['footer_section_h4']; ?></textarea>
        			</div>
        			<div class="form-group">
						<label>Footer Section #4 Content</label>
						<textarea class="cleditor" rows="15" name="footer_section_text4"><?php echo $settings['footer_section_text4']; ?></textarea>
					</div>
					
					<div class="form-group">
        				<label>Footer Section #5 Heading</label>
        				<textarea class="form-control" name="footer_section_h5"><?php echo $settings['footer_section_h5']; ?></textarea>
        			</div>
        			<div class="form-group">
						<label>Footer Section #5 Content</label>
						<textarea class="cleditor" rows="15" name="footer_section_text5"><?php echo $settings['footer_section_text5']; ?></textarea>
					</div>
					<div class="form-group">
						<label>Trending Games Page Content</label>
						<textarea class="cleditor" rows="15" name="trending_page"><?php echo $settings['trending_page']; ?></textarea>
					</div>
					<div class="form-group">
						<label>New Game Page Content</label>
						<textarea class="cleditor" rows="15" name="new_page"><?php echo $settings['new_page']; ?></textarea>
					</div>
					<div class="form-group">
						<label>Top Pick Game Page Content</label>
						<textarea class="cleditor" rows="15" name="toppick_page"><?php echo $settings['toppick_page']; ?></textarea>
					</div>
        			<div class="form-group">
        				<label>Add code in footer</label>
        				<textarea class="form-control" name="footer"><?php echo $settings['footer']; ?></textarea>
        			</div>
        			<div class="form-group">
                        <label>Add code in header</label>
                        <textarea class="form-control" row="5" name="header"><?php echo $settings['header']; ?></textarea>
                    </div>
        			<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
        		</form>
            </div>
        </div>
    </div>
</div>