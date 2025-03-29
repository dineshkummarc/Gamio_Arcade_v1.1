<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
?>

<?php include("menu_notlogged.php"); ?>
<div class="aboutPage">
	<div class="container">
		<div class="aboutSlider cwhite">
			<div class="row">
				<div class="col-lg-6">
					<h2>
					    Play <u>20,000+</u> Games For Free <span class="fw1">At <?=$settings['name'];?>!</span>
					</h2>
					<p class="desc">
					    Play over 20,000 games for free at <?=$settings['name'];?>! 
					    Dive into endless entertainment with our vast collection of arcade games, 
					    available anytime, anywhere. Whether you're a casual gamer 
					    or a hardcore enthusiast, <?=$settings['name'];?> has something for everyone!
					</p>
				</div>
			</div>
		</div>
		<div class="block1">
			<div class="row abtBoxBack">
				<div class="col-lg-3">
					<div class="abtBox1">
						<img src="<?= $settings['url'] ?>theme/img/abtimg1.png"> 
						<h2>
							Game Collection
						</h2>
						<p>
						    Discover a vast array of over 20,000 games, all available for free. 
						    From action-packed adventures to brain-teasing puzzles, there's something for everyone.
						</p>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="abtBox1">
						<img src="<?= $settings['url'] ?>theme/img/abtimg2.png">
						<h2>
							Endless Entertainment
						</h2>
						<p>
							Dive into nonstop fun with our extensive library of arcade games. 
							New games are added regularly, ensuring you'll never run out of options.
						</p>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="abtBox1">
						<img src="<?= $settings['url'] ?>theme/img/abtimg3.png">
						<h2>
							Play Anytime, Anywhere
						</h2>
						<p>
							Enjoy your favorite games on any device, anytime you want. 
							Our platform is optimized for seamless gameplay across desktops, tablets, and smartphones.
						</p>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="abtBox1">
						<img src="<?= $settings['url'] ?>theme/img/abtimg4.png">
						<h2>
							Join Community
						</h2>
						<p>
							Connect with fellow gamers and share your achievements. Compete in leaderboards, 
							participate in events, and be a part of our vibrant gaming community.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include("footer.php"); ?>