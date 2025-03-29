<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
?>
<?php if (empty($a)) { ?>
<?php if (!empty($settings['footer_section_h1']) && !empty($settings['footer_section_text'])) { ?>
<div class="aboutCont">
	<div class="container">
		<div class="inner">
			<h1 class="head"><?=$settings['footer_section_h1'];?></h1>
			<p class="txt">
				<?=$settings['footer_section_text']; ?>
			</p>
		</div>
	</div>
</div>
<?php } ?>
<div class="specSec1">
	<div class="container">
		<div class="inner">
			<div class="row">
			    <?php if (!empty($settings['footer_section_h2']) && !empty($settings['footer_section_text2'])) { ?>
				<div class="col-lg-3">
					<div class="specBox1 rel">
						<div class="inner2">
							<h2 class="head"><?=$settings['footer_section_h2'];?></h2>
							<p class="desc">
								<?=$settings['footer_section_text2']; ?>
							</p>
						</div>
						<img src="<?= $settings['url'] ?>theme/img/spec1.png" class="specimg1">
					</div>
				</div> <!-- col -->
				<?php } ?>
				<?php if (!empty($settings['footer_section_h3']) && !empty($settings['footer_section_text3'])) { ?>
				<div class="col-lg-3">
					<div class="specBox1 rel">
						<div class="inner2">
							<h2 class="head"><?=$settings['footer_section_h3'];?></h2>
							<p class="desc">
								<?=$settings['footer_section_text3']; ?>
							</p>
						</div>
						<img src="<?= $settings['url'] ?>theme/img/spec3.png" class="specimg1">
					</div>
				</div> <!-- col -->
				<?php } ?>
				<?php if (!empty($settings['footer_section_h4']) && !empty($settings['footer_section_text4'])) { ?>
				<div class="col-lg-3">
					<div class="specBox1 rel">
						<div class="inner2">
							<h2 class="head"><?=$settings['footer_section_h4'];?></h2>
							<p class="desc">
								<?=$settings['footer_section_text4']; ?>
							</p>
						</div>
						<img src="<?= $settings['url'] ?>theme/img/spec2.png" class="specimg1">
					</div>
				</div> <!-- col -->
				<?php } ?>
				<?php if (!empty($settings['footer_section_h5']) && !empty($settings['footer_section_text5'])) { ?>
				<div class="col-lg-3">
					<div class="specBox1 rel">
						<div class="inner2">
							<h2 class="head"><?=$settings['footer_section_h5'];?></h2>
							<p class="desc">
								<?=$settings['footer_section_text5']; ?>
							</p>
						</div>
						<img src="<?= $settings['url'] ?>theme/img/spec4.png" class="specimg1">
					</div>
				</div> <!-- col -->
				<?php } ?>
			</div> <!-- row -->
		</div>  <!-- inner -->
	</div>
</div> <!-- specSec1 -->
<?php } ?>
<div class="footer">
	<div class="container">
		<div class="inner">
			<div class="row jcb">
				<div class="col-lg-6">
					<p>All rights reserved by <?= $settings['name'] ?> &copy; <?=date("Y",time());?>.</p>
				</div> <!-- col -->
				<div class="col-lg-6" align="right">
					
					<div class="flex footlinksflex jce">
					    <a href="<?= $settings['url']; ?>blog">Blog</a>
					    <a href="<?= $settings['url']; ?>about">About</a>
						<a href="<?= $settings['url']; ?>contact">Contact Us</a>
						<a href="<?= $settings['url']; ?>page/terms-and-conditions">Terms and Conditions</a>
						<a href="<?= $settings['url']; ?>page/privacy-policy"><?= $lang['privacy_policy']; ?></a>
					</div>				
				</div> <!-- col -->
			</div> <!-- row -->
		</div> <!-- inner -->
	</div>
</div> <!-- footer -->
<?=advertisement(3);?>
<?php if (!empty($settings['footer'])) { ?>
<?php echo htmlspecialchars_decode($settings['footer']); ?>
<?php } ?>
<?php if ($m["live_chat"] == "1") { ?><?= $settings['live_chat_code'] ?><?php } ?>
</body>
</html>