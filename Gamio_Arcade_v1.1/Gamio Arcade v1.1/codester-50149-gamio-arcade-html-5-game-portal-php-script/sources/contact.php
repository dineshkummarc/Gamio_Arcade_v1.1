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
<script type="text/javascript">
	$(function(){
    	$(".faqQeustion").click(function(){
    		$(this).parent().find(".faqAnswer").slideToggle();
    		$(this).parent().find(".fa").toggleClass("fa-minus");
    	});
    });
</script>

<div class="contactPage">
	<div class="container">
		<div class="inner">
			<div class="row">
				<div class="col-lg-4">
					<h2 class="block-heading">FAQ</h2>
					<div class="faqSection">
					    
					    <?php
                        $statement = "faqs";
                        $query = $db->query("SELECT * FROM {$statement}");
                        if($query->num_rows > 0) {
                          while($data = $query->fetch_assoc()) {
                       	?>
                        <!-- <?=$data["question"];?> -->
                        <div class="faqBack">
							<div class="faqQeustion">
								<i class="fa fa-plus"></i>
								<h2><?=$data["question"];?>?
								</h2>
							</div>
							<div class="faqAnswer">
								<p>
									<?=$data["answer"];?>
								</p>
							</div>
						</div>
            			<!-- <?=$data["question"];?> -->
            			<?php } } else { echo error("No FAQs added yet."); } ?>
					</div>  <!-- faqSection -->
					<p class="cgray fs14 mt20">
						Not found the answers? Send us a message to our support email.
					</p>
				</div> <!-- col -->
				<div class="col-lg-3">
						<div class="getTouchInner">
							<h2 class="block-heading">Get in touch</h2>
							<div class="row">
							    <?php if ($settings['facebook'] !== "#") { ?>
								<div class="col-lg-12">
									<a href="<?=$settings['facebook'];?>" target="_blank" class="contSocialFlex flex">
										<i class="fa fa-facebook"></i>
										<p>Facebook</p>
									</a>
								</div>
								<?php } ?>
								<?php if ($settings['twitter'] !== "#") { ?>
								<div class="col-lg-12">
									<a href="#" target="_blank" class="contSocialFlex flex">
										<i class="fa fa-twitter"></i>
										<p>Twitter</p>
									</a>
								</div>
								<?php } ?>
								<?php if ($settings['instagram'] !== "#") { ?>
								<div class="col-lg-12">
									<a href="#" target="_blank" class="contSocialFlex flex">
										<i class="fa fa-instagram"></i>
										<p>Instagram</p>
									</a>
								</div>
								<?php } ?>
								<?php if ($settings['youtube'] !== "#") { ?>
								<div class="col-lg-12">
									<a href="#" target="_blank" class="contSocialFlex flex">
										<i class="fa fa-youtube"></i>
										<p>Youtube</p>
									</a>
								</div>
								<?php } ?>
								<div class="col-lg-12">
									<a href="emailto:<?=$settings['supportemail'];?>" target="_blank" class="contSocialFlex flex">
										<i class="fa fa-envelope"></i>
										<p><?=$settings['supportemail'];?></p>
									</a>
								</div>
							</div>
						</div>
				</div> <!-- col -->
				<div class="col-lg-5">
					<h2 class="block-heading">Contact us</h2>
					<?php
    				if (isset($_POST['send'])){
                        $FormBTN = protect($_POST['send']);
    				} else {
    				    $FormBTN = "";
    				}
                    if($FormBTN == "message") {
                        $name = protect($_POST['name']);
                        $email = protect($_POST['email']);
                        $subject = protect($_POST['subject']);
                        $message = protect($_POST['message']);
                        if(empty($name) or empty($email) or empty($subject) or empty($message)) {
                            echo error("Some fields are missing.");
                        } elseif(!isValidEmail($email)) {
                            echo error("Email address is not valid.");
                        } else {
                            $mail = new PHPMailer;
                            $mail->isSMTP();
                            $mail->SMTPDebug = 0;
                            $mail->Host = $smtpconf["host"];
                            $mail->Port = $smtpconf["port"];
                            $mail->SMTPAuth = $smtpconf['SMTPAuth'];
                            $mail->Username = $smtpconf["user"];
                            $mail->Password = $smtpconf["pass"];
                            $mail->setFrom($email, $name);
                            $mail->addAddress($settings['supportemail'], $settings['supportemail']);
                            //Set the subject line
                            $lang = array();
                            $mail->Subject = $subject;
                            $mail->msgHTML($message);
                            //Replace the plain text body with one created manually
                            $mail->AltBody = $message;
                            //Attach an image file
                            //send the message, check for errors
                            $send = $mail->send();
                            if($send) {
                                echo success("You Query has been received.");
                            } else {
                                echo error("Some thing is wrong.");
                            }
                        }
                    }
                    ?>
					<form method="POST" action="">
						<div class="contactForm">
							<div class="row">
								<div class="col-lg-6">
									<label class="lbl1">Full name</label>
									<input type="text" name="name" class="inp2" placeholder="Full name" required>
								</div>
								<div class="col-lg-6">
									<label class="lbl1">Email address</label>
									<input type="email" name="email" class="inp2" placeholder="Email address" required>
								</div>
							</div>
							<label class="lbl1">Subject</label>
							<input type="text" name="subject" placeholder="Subject" class="inp2" required>
							<label class="lbl1">Enter your query</label>
							<textarea name="message" placeholder="Type your message" class="inp2 txtarea1" required></textarea>
							<button type="submit" name="send" value="message" class="btn2 contactBtn">Send Message&nbsp;<i class="fa fa-caret-right"></i></button>
						</div>
						<p class="cgray">
							Please refrain sending multiple message and wait for 24 hours for us to response your previous messages. Sending multiple messages may result in getting blocked!
						</p>
					</form>
				</div> <!-- col -->
 			</div> <!-- row -->
		</div> <!-- inner -->
	</div>
</div> <!-- contactPage -->
<?php include("footer.php"); ?>