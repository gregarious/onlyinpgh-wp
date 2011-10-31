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

	<link rel="stylesheet/less" href="assets/francois-one-fontfacekit/stylesheet.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet/less" href="css/basic.less">

	<script src="js/libs/less-1.1.3.min.js"></script>
	<script src="js/libs/modernizr-2.0.min.js"></script>
	<script src="js/libs/respond.min.js"></script>
</head>
<body>
	<header id="top">
		<h1 id="title"  class="wrapper" >+5 !</h1>
	</header>
	
	<div id="skyline"> 
		<!-- Skyline image here -->
	</div>

	<section id="main">
		<article class="wrapper">
		
			<h4 id="coming-soon">Coming soon:</h4>
			<h2> Pittsburgh 3.0</h2>								<h4 id="sign-up">Sign up to get the 412.</h4>
			
			<div id="email-form">
				<?php if ($formStatus === 'SUBMITTED'): ?>
					<h2 id='post-submit-thanks'>Thanks! We'll be in touch!</p>
				<?php else: ?>
					<form action="" method="post" id="email-form">
					<?php $formKey->outputKey(); ?>
					<input id="email-address" name="email-address" type="email" placeholder="Email">
					<button type="sumbit">Send</button>
					</form>
					<?php if ($formStatus == 'ERROR'): ?>
						<br />
						<h4 id='post-submit-thanks'>Problem submitting e-mail. Try again later!</p>	
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</article>
	</section>
	<div class="pull"></div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.6.2.min.js"><\/script>')</script>

<script src="js/script.js"></script>

<!--[if lt IE 7 ]>
	<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
	<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
<![endif]-->

</body>
</html>
