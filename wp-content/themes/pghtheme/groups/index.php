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
		<div class="padder">

		<?php do_action( 'bp_before_directory_groups' ); ?>

		<form action="" method="post" id="groups-directory-form" class="dir-form">

			<!-- Commenting out Create Group functionality -->
			<h3><?php _e( 'Scenes', 'buddypress' ); ?><!--<?php //if ( is_user_logged_in() && bp_user_can_create_groups() ) : ?> &nbsp;<a class="button" href="<?php //echo trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create' ); ?>"><?php //_e( 'Add a Scene', 'buddypress' ); ?></a><?php //endif; ?>--></h3>

			<?php do_action( 'bp_before_directory_groups_content' ); ?>

			<div class="item-list-tabs" role="navigation">
				<ul>
					<li class="selected" id="groups-about"><a href="http://oip.local/scenes"><?php echo 'About'; ?></a></li>
					<li><a href="<?php echo get_bloginfo('url') . '/scenes/pittsburgh-music-scene/';?>">Music</a></li>
					<li><a href="<?php echo get_bloginfo('url') . '/scenes/oakland-scene/';?>">Arts</a></li>
				</ul>
			</div><!-- .item-list-tabs -->

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

			<p>Try it out and let us know what you think! This is a beta launch and we really want to hear your feedback and ideas so we can incorporate them into additional Scenes.  Feel free to use the Feedback tab on the right of every page or the contact page to let us know what you think!</p>

			<p>In particular, we want to know your thoughts about:</p>
			<ul>
				<li>What other Scenes would you like to see?</li>
				<li>What other features would you like in each Scene page?</li>
			</ul>

			<p>Enjoy!</p>


		</div><!-- .padder -->
	</div><!-- #content -->
</div><!-- #wrapper -->
	<?php do_action( 'bp_after_directory_groups_page' ); ?>


<?php get_footer(); ?>

