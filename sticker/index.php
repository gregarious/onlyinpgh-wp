<?php  
session_start();
require_once('form_key.inc.php');
$formKey = new formKey();

$formStatus = 'UNSUBMITTED';	// either UNSUBMITTED, SUBMITTED, ERROR
if($_SERVER['REQUEST_METHOD'] == 'POST')  
{  
    //Validate the form key 
    if(isset($_POST['form_key'])) {
    	if($formKey->validate()) { 
    		require_once('email_store.inc.php');
    		if(	isset($_POST['email-address']) && 
    			storeEmail($_POST['email-address']) ) {
				$formStatus = 'SUBMITTED';
			}
			else {
				$formStatus = 'ERROR';
			}
    	}
    	else {
    		$formStatus = 'ERROR';
    	}
    }
}

?>  

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Sticker Scanned</title>
	<meta name="description" content="">
	<meta name="author" content="">

	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/1140.css">
	<link rel="stylesheet" href="assets/francois-one-fontfacekit/stylesheet.css">
	<link rel="stylesheet" href="css/default-style.css">

	<script src="js/libs/modernizr-2.0.min.js"></script>
	<script src="js/libs/css3-mediaqueries.js"></script>
	<script src="js/libs/respond.min.js"></script>

	<?php 
	// include any applicable analytics code
	//include_once('analytics.html');
	?>
</head>
<body>

	<header class="skyline">
		<div class="row">
			<span class="points">+5 !</span>
		</div>
	</header>
	
		<article class="content">
			
			<div class="row">
				<section class="fivecol">
					<header class="content-intro">
						<h2>
							Ready to take this whole<br />	&#8220;internet	&#8221;<br /> thing to the next level?
						</h2>
					</header>

					<p>
						<a href="http://onlyinpgh.com" title="Onlyinpgh" targe="_blank">OnlyinPgh</a> is building a new way for you to get into your favorite Scenes in Pittsburgh.
					</p>
				</section>

				<section class="email-form sixcol last">
					<hgroup>
						<h3>Private Beta launching soon.</h3>
						<h4>Signup now to be part of it.</h4>
					</hgroup>
					<?php if ($formStatus === 'SUBMITTED'): ?>
						<p class="post-submit-thanks">Thanks! We'll be in touch!</p>
					<?php else: ?>
						<form method="post" id="email-form">
							<?php $formKey->outputKey(); ?>
							<input id="email-address" name="email-address" type="email" placeholder="Email">
							<input class="submit-email" type="sumbit" value="Submit">
						</form>
						<?php if ($formStatus == 'ERROR'): ?>
							<br />
							<p class='post-submit-thanks'>Problem submitting e-mail. Try again later!</p>	
						<?php endif; ?>
					<?php endif; ?>
				</section>
			</div> <!-- .row -->

		</article> <!-- .content -->


	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/libs/jquery-1.6.2.min.js"><\/script>')</script>

	<script src="js/script.js"></script>

	<!--[if lt IE 7 ]>
		<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
		<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
	<![endif]-->

</body>
</html>
