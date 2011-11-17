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

	<title>Onlyinpgh Sceneable Beta Testing</title>
	<meta name="description" content="">
	<meta name="author" content="">

	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/1140.css">
	<link rel="stylesheet" href="assets/francois-one-fontfacekit/stylesheet.css">
	<link rel="stylesheet" href="assets/Komika-Title-fontfacekit/stylesheet.css">
	<link rel="stylesheet" href="css/default-style.css">

	<script src="js/libs/modernizr-2.0.min.js"></script>
	<script src="js/libs/css3-mediaqueries.js"></script>
	<script src="js/libs/respond.min.js"></script>

	<meta property="og:title" content="Onlyinpgh Sceneable Beta Testing" />
	<meta property="og:type" content="company" />
	<meta property="og:url" content="http://onlyinpgh.com/beta" />
	<meta property="og:image" content="http://onlyinpgh.com/beta/img/beta-thumb.png" />
	<meta property="og:site_name" content="Onlyinpgh" />
	<meta property="fb:app_id" content="203898346321665" />

</head>
<body>

	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>

	<header class="points-container">
		<span class="points">+5 !</span>
	</header>

	<div class="skyline"></div>
	
	<article class="main">
		
		<div class="row">
			<section class="content fivecol">
				<header class="content-intro">
					<h2>
						Ready to take this whole<br />&#8220;internet&#8221;<br /> thing to the next level?
					</h2>
				</header>

				<p class="content-about"><em>
					<a href="http://onlyinpgh.com" title="Onlyinpgh" targe="_blank">OnlyinPgh</a> is building a new way for you to get into your favorite Scenes in Pittsburgh.</em>
				</p>
			</section> <!-- .content -->

			<section class="announcement-form fivecol">
			
				<div class="announcement row">
					<h2>Private Beta launching soon.</h2>
					<p class="content-about"><em>Signup now to be part of it.</em></p>
				</div>
				<div class="row clearfix">
					<?php if ($formStatus === 'SUBMITTED'): ?>
						<p class="post-submit-thanks">Thanks! We'll be in touch!</p>
					<?php else: ?>
						<form method="post" id="email-form">
							<?php $formKey->outputKey(); ?>
							<input id="email-address" name="email-address" type="email" placeholder="Email" />
							<input type="submit" value="Submit" />
						</form>
						<?php if ($formStatus == 'ERROR'): ?>
							<br />
							<p class='post-submit-thanks'>Problem submitting e-mail. Try again later!</p>	
						<?php endif; ?>
					<?php endif; ?>
				</div>

				<div class="social row">
					<a href="https://twitter.com/onlyinpgh" class="twitter-follow-button" data-button="grey" data-text-color="#FFFFFF" data-link-color="#00AEFF">Follow @onlyinpgh</a>
<script src="//platform.twitter.com/widgets.js" type="text/javascript"></script>

					<div class="fb-like" data-href="http://onlyinpgh.com/sticker" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-action="recommend"></div>
				</div>

			</section> <!-- .announcement-form -->
		</div> <!-- .row -->

	</article> <!-- .content -->

	<!-- Start of StatCounter Code for Default Guide -->
	<script type="text/javascript">
	var sc_project=7412511;
	var sc_invisible=1;
	var sc_security="45e77450";
	</script>
	<script type="text/javascript"
	src="http://www.statcounter.com/counter/counter.js"></script>
	<noscript><div class="statcounter"><a title="hit counter"
	href="http://statcounter.com/" target="_blank"><img
	class="statcounter"
	src="http://c.statcounter.com/7412511/0/45e77450/1/"
	alt="hit counter"></a></div></noscript>
	<!-- End of StatCounter Code for Default Guide -->

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/libs/jquery-1.6.2.min.js"><\/script>')</script>

	<script src="js/script.js"></script>

	<!--[if lt IE 7 ]>
		<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
		<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
	<![endif]-->

</body>
</html>
