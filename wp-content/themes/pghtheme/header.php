<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<title><?php bp_page_title() ?></title>

	<!-- Define a JS global to hold the site url -->
	<script type="text/javascript">
		var OIP_SITE_URL = "<?php bloginfo('url'); ?>";
	</script>
	
<?php do_action( 'bp_head' ) ?>

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<link type="text/css" href="http://jqueryui.com/latest/themes/base/ui.all.css" rel="stylesheet" />

<?php wp_head(); ?>

<link href="<?php includes_url() ?>/js/jquery/ui.tabs.js" type="text/javascript" />
<link href="<?php includes_url() ?>/js/jquery/ui.widget.js" type="text/javascript" />
<link href="<?php includes_url() ?>/js/jquery/ui.datepicker.js" type="text/javascript" />
<script src="http://maps.google.com/maps/api/js?libraries=places&amp;sensor=false" type="text/javascript"></script>

<script type="text/javascript">

    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'none')
          e.style.display = 'block';
       else
          e.style.display = 'none';
    }

function textChange(){
document.getElementById("attending").value="You are attending this event.";
}

function showSearch()
{
document.getElementById("datepicksearch").style.display = "block";
document.getElementById("bydate").style.display = "none";
}

function hideSearch()
{
document.getElementById("datepicksearch").style.display = "none";
document.getElementById("bydate").style.display = "block";
}

function clearkeywordtip(t)
{
	if(t.value == 'Keyword search (optional)') {
		t.value = '';
	}
}

function showPlace() {
document.getElementById("resultsholder").style.display = "none";
document.getElementById("event").className = document.getElementById("event").className.replace(/\bsidebartogglecurrent\b/,'');
document.getElementById("event").className += " sidebartoggle";
document.getElementById("location_sidebar").style.display = "block";
document.getElementById("place").className += " sidebartogglecurrent";
}

function showEvent() {
	document.getElementById("resultsholder").style.display = "block";
	document.getElementById("event").className += " sidebartogglecurrent";
	document.getElementById("location_sidebar").style.display = "none";
	document.getElementById("place").className = document.getElementById("place").className.replace(/\bsidebartogglecurrent\b/,'');
	document.getElementById("place").className += " sidebartoggle";
}

function showKey() {
	var see = document.getElementById("mapkey").style.display;
	if (see == 'none'){
		document.getElementById("mapkey").style.display = "block";
	} else if (see =='block') {
	    document.getElementById("mapkey").style.display = "none";
	}
}

function checkAll(field){
{
for (i = 0; i < field.length; i++)
	field[i].checked = true ;
}
}
</script>


<script type="text/javascript">
//JQuery scripts for the datepicker
	jQuery(function() {
		jQuery( ".datepicker" ).datepicker({dateFormat: 'yy-mm-dd'});
	});
	
//Removes all the checks from location key
	function clearChecks() {
	jQuery('#locationtypes input:checkbox').removeAttr('checked');
	};
	
	function jqCheckAll( id, name, flag )
		{
   if (flag == 0)
   		{
      jQuery("form#" + id + " INPUT[@name=" + name + "][type='checkbox']").attr('checked', false);
   		}
   		else
   		{
      jQuery("form#" + id + " INPUT[@name=" + name + "][type='checkbox']").attr('checked', true);
   		}
	}
	
	
	
//JQuery for the slider on the photos page
	jQuery(document).ready(function(){
	jQuery("dd:not(:first)").hide();
	jQuery("dt a").click(function(){
		jQuery("dd:visible").slideUp("slow");
		jQuery(this).parent().next().slideDown("slow");
		return false;
	});
	
});
	
	</script>

<link href='http://fonts.googleapis.com/css?family=Francois+One' rel='stylesheet' type='text/css'>

<?php

	/* Facebook OpenGraph meta info for single event pages */
	if ( is_page('event')) {
		require(ABSPATH . 'events.php'); // implicit is a requirement that GET has an eid entry
		$eid = $results[0]['id'];
		$name =  $results[0]['name'];
		$desc = $results[0]['description'];
		$img = $results[0]['image_url'];
		$lat = $results[0]['lat'];
		$long = $results[0]['long'];

		?>
		<meta property="og:title" content="<?php echo $name; ?> | OnlyinPgh"/>
    	<meta property="og:type" content="activity"/>
    	<meta property="og:url" content="<?php echo getCanonicalEventURL($eid); ?>"/>
		<meta property="og:image" content="<?php echo $img; ?>"/>
		<meta property="og:site_name" content="OnlyinPgh"/>
		<meta property="fb:app_id" content="203898346321665"/>
		<meta property="og:description" content="<?php echo $desc; ?>"/>
        <?php
    	if($lat && $long) {
    		?>
    		<meta property="og:latitude" content="<?php echo $lat; ?>"/>
    		<meta property="og:longitude" content="<?php echo $long; ?>"/>	
    		<?php
    	}
	}
?>

</head>

<body style="margin:0px; padding:0px;">
<?php do_action( 'bp_before_header' ) ?>

<div id="header">
	<div id="photoborder"></div>
	<div class="header_content">
	<div id="topbar">
		<?php if ( is_user_logged_in() ) : ?>

			<?php do_action( 'bp_before_sidebar_me' ) ?>
			<input type="hidden" value="y" id="isloggedin">
			<input type="hidden" value="<?php echo bp_loggedin_user_id() ?>" id="loggedinid">
			<div id="sidebar-me">
				<a class="showinmodal" href="<?php echo bp_loggedin_user_domain() ?>">
					<?php bp_loggedin_user_avatar( 'type=thumb&width=20&height=20' ) ?>
				</a>

				<?php echo bp_core_get_userlink( bp_loggedin_user_id() ); ?> | 
	<?php wp_loginout($_SERVER['REQUEST_URI']); ?>

				<?php do_action( 'bp_sidebar_me' ) ?>
			</div>

			<?php do_action( 'bp_after_sidebar_me' ) ?>

			<?php if ( function_exists( 'bp_message_get_notices' ) ) : ?>
				<?php bp_message_get_notices(); /* Site wide notices to all users */ ?>
			<?php endif; ?>

		<?php else : ?>
			<input type="hidden" value="n" id="isloggedin">
			<input type="hidden" value="0" id="loggedinid">
			<a href="/register" class="simplemodal">Register</a> | <?php wp_loginout($_SERVER['REQUEST_URI']); ?> | <?php jfb_output_facebook_btn(); jfb_output_facebook_callback(); ?>
		<?php endif; ?>
	</div><!--Closes topbar-->

	<div id="header_left">
		<div class="logo">
			<a href="<?php bloginfo('url'); ?>" title="Home">
				<img src="<?php bloginfo('stylesheet_directory'); ?>/images/logoframe2_beta.png">
			</a>
		</div><!--Closes logo-->
	</div><!--Closes headerleft-->

	<?php do_action( 'bp_header' ) ?>

	</div><!--Closes header_content-->

	<div id="header_below">
		<div class="header_content">
			<div id="header_navigation">
				<ul id="staticheader">

					<?php if( is_front_page()) : ?>
						<li class="current_page_item"><a class="gohomelink" href="<?php bloginfo('url'); ?>/">Home</a></li>
					<?php else : ?>
						<li><a class="gohomelink" href="<?php bloginfo('url'); ?>/">Home</a></li>
					<?php endif; ?>


					<?php 
						
						// If user is logged in, MyPgh goes to their BuddyPress homepage	
						// Else show the the simplemodal login popup
						if ( is_user_logged_in() ) {
							if ( bp_is_home()) { ?>
            					<li class="current_page_item"><a class="mypghlink" href="<?php echo bp_loggedin_user_domain() ?>map/">My PGH</a></li><?php 
            				} else { ?>
            					<li><a class="mypghlink" href="<?php echo bp_loggedin_user_domain() ?>map/">My PGH</a></li><?php 
         					}
						} else { ?>
								<li><a class="mypghlink simplemodal-login" href="/wp-login.php?redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>">My PGH</a></li> <?php
						}

						// *** STUPID BUDDYPRESS MUST HARCODE- change for dev/prod *** //
						// If on the 'Scenes' page, highlight its menu item ?>
						<a href="http://onlyinpgh.com/scenable/"> <?php
						if ( is_page('scenable') ) { ?>
							<li class="current_page_item menu-item scenes">Scenable</li><?php 
						} else { ?>
							<li class="menu-item scenes">Scenable</li><?php 
						} ?>
						</a>

				</ul>

				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>

			

			</div> <!--Close header_navigation-->
		</div> <!--Close header_content-->

		<?php do_action( 'bp_header' ) ?>
		<div class="socialicons">
			<a href="https://www.facebook.com/onlyinpgh"><img src="<?php bloginfo('url'); ?>/menunav_images/fbicon.png"></a>
			<a href="https://twitter.com/#!/onlyinpgh/"><img src="<?php bloginfo('url'); ?>/menunav_images/twittericon.png"></a>
			<a href="<?php bloginfo('url'); ?>/feed/rss/"><img src="<?php bloginfo('url'); ?>/menunav_images/rssicon.png"></a></div>
	</div><!--Close header_below-->
</div><!--Closes header-->

<?php do_action( 'bp_after_header' ) ?>
<?php do_action( 'bp_before_container' ) ?>