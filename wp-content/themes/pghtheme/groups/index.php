<?php

/**
 * BuddyPress - Groups Directory
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php get_header( 'buddypress' ); ?>

	<?php do_action( 'bp_before_directory_groups_page' ); ?>

<div id="wrapper">
	<div id="content">
		<div class="padder about" id="sidebar-page">
		
		<?php do_action( 'bp_before_directory_groups' ); ?>

		<form action="" method="post" id="groups-directory-form" class="dir-form">

			<?php do_action( 'bp_before_directory_groups_content' ); ?>

			<div class="item-list-tabs" role="navigation">
				<ul>
					<li class="selected" id="groups-about"><a href="http://oip.local/scenes"><?php echo 'About'; ?></a></li>
					<li><a href="<?php echo get_bloginfo('url') . '/scenes/music-scene/';?>">Music</a></li>
					<li><a href="<?php echo get_bloginfo('url') . '/scenes/arts-scene/';?>">Arts</a></li>
				</ul>
			</div><!-- .item-list-tabs -->
			<div id="content-about">
				<h1>Welcome to OnlyinPgh Scenes!</h1>

				<p>Pittsburgh has lots of Scenes, but how do you learn about them, connect to them and get involved?</p>

				<p>Starting with the Arts and Music Scenes, we're working on giving you a better way to do that. On the tabs to your right you'll see the names of different Scenes, and clicking on them will bring you to the Scene's Page. On each Scene Page you'll be able to:</p>

				<ul>
					<li>Read news and articles from local sources about each Scene</li>
					<li>See a list of curated Scene Events </li>
					<li>Learn about the Places and People that make the Scene great</li>
					<li>Join the Scene to get special updates about what's going on</li>
					<li>Use the Chatterbox where you can share, discuss and connect to other people interested in the Scene.</li>
				</ul>

				<p>Try it out and let us know what you think! This is a beta launch and we really want to hear your feedback and ideas so we can incorporate them into additional Scenes. Feel free to use the Feedback tab on the right of every page or the form below!</p>

			</div>

			<br/><br/>
			<div id="survey-container">
				<h2 class="scene-part-title show-survey">What do you think of scenes? Click to let us know.</h2>
				<div id="scene-survey"><?php
					echo do_shortcode('[gravityform id=9 name=SceneSurvey title=false ajax=true]'); ?>
				</div>
			</div> <!-- #survey-container -->

		</div><!-- .padder -->
			
			<div id="sidebar"><?php locate_template( array( 'sidebar-blog.php' ), true ) ?></div>

	</div><!-- #content -->
</div><!-- #wrapper -->
	<?php do_action( 'bp_after_directory_groups_page' ); ?>

<?php get_footer(); ?>

<script>

jQuery(document).ready( function() {
	jQuery("#scene-survey").hide();
	jQuery(".show-survey").click( function() {
		jQuery("#scene-survey").slideToggle(900);
	});

});

</script>