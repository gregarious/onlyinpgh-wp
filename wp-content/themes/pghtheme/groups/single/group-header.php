<?php

do_action( 'bp_before_group_header' );

?>

<div id="item-header-content">
	<h3>Pittsburgh</h3>
	<h2><a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>"><?php bp_group_name(); ?></a></h2>
	<span class="highlight"><?php bp_group_type(); ?></span> <span class="activity"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span>

	<?php do_action( 'bp_before_group_header_meta' ); ?>

	<div id="item-meta">

		<div id="item-header-avatar">
		
			<a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>"> <?php

			if (is_page('scenes')) {
				$img_dir = get_stylesheet_directory_uri();
				echo '<img src="'.$img_dir.'/images/music-scene-head.png">';
			} ?>

		</a>

	</div><!-- #item-header-avatar -->

		<?php //bp_group_description(); ?>

		<div id="item-buttons">

			<div id="item-actions">


			</div><!-- #item-actions -->
			
			<?php if ( !is_user_logged_in() ) { ?>
				<a href="/wp-login.php?redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>" class="group-button join-group button simplemodal-login">Log in to Join!</a> <?php
			}

			do_action( 'bp_group_header_actions' );

			?>

		</div><!-- #item-buttons -->

		<?php do_action( 'bp_group_header_meta' ); ?>

	</div>

</div><!-- #item-header-content -->

<?php
do_action( 'bp_after_group_header' );
do_action( 'template_notices' );
?>