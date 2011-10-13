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

			<h1>We'll have some generic, about scenes content here.<h1>
			
			<p>
				Vinyl fap synth retro, vice carles banksy wes anderson tofu. PBR craft beer salvia mustache. Tofu next level wolf, master cleanse hoodie mlkshk put a bird on it mixtape irony. Marfa you probably haven't heard of them craft beer carles. Food truck wayfarers fanny pack carles, master cleanse gluten-free banh mi messenger bag you probably haven't heard of them scenester quinoa chambray blog mustache vice. Cliche whatever vegan banksy brooklyn, tumblr vinyl wayfarers banh mi squid pitchfork high life craft beer marfa. Cardigan high life organic, mcsweeney's wayfarers synth scenester lomo fap.
			</p>

		</div><!-- .padder -->
	</div><!-- #content -->
</div><!-- #wrapper -->
	<?php do_action( 'bp_after_directory_groups_page' ); ?>


<?php get_footer(); ?>

