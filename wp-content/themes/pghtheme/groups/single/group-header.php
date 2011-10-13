<?php

do_action( 'bp_before_group_header' );

?>
<div id="item-header-content">
	<h3>Pittsburgh</h3>
	<h2><a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>"><?php bp_group_name(); ?></a></h2>
	<span class="highlight"><?php bp_group_type(); ?></span> <span class="activity"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span>

	<?php do_action( 'bp_before_group_header_meta' ); ?>

	<div id="item-meta">

		<div id="item-header-avatar"> <?php

			if (is_page('scenes')) {
				$img_dir = get_stylesheet_directory_uri();
				echo '<img src="'.$img_dir.'/images/music-scene-head.png">';
			} ?>

		</div><!-- #item-header-avatar -->

		<div id="item-buttons"><?php 
			// If user isn't logged in, prompt them to login 
			if ( !is_user_logged_in() ) { ?>
				<a href="/wp-login.php?redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>" class="group-button join-group button simplemodal-login">Log in to Join!</a> <?php
			}
			do_action( 'bp_group_header_actions' );?>
		</div><!-- #item-buttons -->

		<?php do_action( 'bp_group_header_meta' ); ?>

	</div>
	
</div><!-- #item-header-content -->
<h3> <a class="show-survey">What do you think of scenes?</a></h3>	
<div id="scene-survey"><?php
	echo do_shortcode('[gravityform id=9 name=Whatdo you think of Scenes? ajax=true]'); ?>
</div>

<?php
do_action( 'bp_after_group_header' );
do_action( 'template_notices' );
?>


<script>

jQuery(document).ready( function() {
	jQuery("#scene-survey").hide();
	jQuery(".show-survey").click( function() {
		jQuery("#scene-survey").slideToggle(900);
	});

});

</script>