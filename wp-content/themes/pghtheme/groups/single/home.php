<?php get_header( 'buddypress' ); ?>

<div id="wrapper">
	<div id="content">
		<div class="padder" id="scene">

		<form action="" method="post" id="groups-directory-form" class="dir-form">

			<?php do_action( 'bp_before_directory_groups_content' ); ?>

			<div class="item-list-tabs" role="navigation">
				<!-- If user is a group admin, show the admin menu -->
				<?php if ( bp_group_is_admin() ) { ?>		
					<div id="item-nav" class="scene-admin">
						<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
							<ul>

								<?php bp_get_options_nav(); ?>

								<?php do_action( 'bp_group_options_nav' ); ?>

							</ul>
						</div>
					</div><!-- #item-nav -->
				<?php } ?> 

				<ul>
					<li><a href="http://oip.local/scenes"><?php echo 'About'; ?></a></li>
					<?php
					// Toggling the active group tabs
					if (bp_is_group_single() == 'pittsburgh-music-scene') { ?>
						<li class="selected"><a href="<?php echo get_bloginfo('url') . '/scenes/pittsburgh-music-scene/';?>">Music</a></li>
						<li><a href="<?php echo get_bloginfo('url') . '/scenes/oakland-scene/';?>">Arts</a></li><?php
					} else if (bp_is_group_single() == 'oakland-scene') { ?>
						<li><a href="<?php echo get_bloginfo('url') . '/scenes/pittsburgh-music-scene/';?>">Music</a></li>
						<li class="selected"><a href="<?php echo get_bloginfo('url') . '/scenes/oakland-scene/';?>">Arts</a></li> <?php
					}?>
					
				</ul>
			</div><!-- .item-list-tabs -->

			<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

				<?php do_action( 'bp_before_group_home_content' ) ?>

				<div id="news-container" class="scene-part">
					<h2 class="scene-part-title">What's up?</h2> 
					<div class="news-content"> <?php
						locate_template( array( 'mypgh-templates/scene-news.php' ), true ); ?>							
					</div>	
				</div> <!-- #news-container-->

				<div id="item-header" role="complementary" class="scene">

					<?php locate_template( array( 'groups/single/group-header.php' ), true ); ?>

				</div><!-- #item-header -->

				<div id="item-body">
					<div id="events-container" class="scene-part">
						<?php locate_template( array( 'mypgh-templates/scene-events.php' ), true ); ?>							
					</div> <!-- #events-container -->
			
					<div id="places-container" class="scene-part">
						<?php locate_template( array( 'mypgh-templates/scene-places.php' ), true ); ?>							
					</div> <!-- #places-container -->

					<?php do_action( 'bp_before_group_body' );

					if ( bp_is_group_admin_page() && bp_group_is_visible() ) :
						locate_template( array( 'groups/single/admin.php' ), true );

					elseif ( bp_is_group_members() && bp_group_is_visible() ) :
						locate_template( array( 'groups/single/members.php' ), true );

					elseif ( bp_is_group_invites() && bp_group_is_visible() ) :
						locate_template( array( 'groups/single/send-invites.php' ), true );

						elseif ( bp_is_group_forum() && bp_group_is_visible() && bp_is_active( 'forums' ) && bp_forums_is_installed_correctly() ) :
							locate_template( array( 'groups/single/forum.php' ), true );

					elseif ( bp_is_group_membership_request() ) :
						locate_template( array( 'groups/single/request-membership.php' ), true );

					elseif ( bp_group_is_visible() && bp_is_active( 'activity' ) ) : ?>

						<div id="chatter-container">
							<h2>The Chatter Box</h2><?php
							
							if ( is_user_logged_in() && bp_group_is_member() ) :
								locate_template( array( 'activity/post-form.php'), true );
							else: ?>
								<h4> Sign up or login to post!</p> <?php
								
								do_action( 'bp_after_group_activity_post_form' );
								do_action( 'bp_before_group_activity_content' ); 
							
							endif;?>
							
							<!-- <div id="item-actions">
									<?php if ( bp_group_is_visible() ) : ?>
										<h3><?php _e( 'Admins:', 'buddypress' ); ?></h3>
										<?php bp_group_list_admins();
										do_action( 'bp_after_group_menu_admins' );
									endif; ?>
								</div> #item-actions -->

							<div class="chatter-content"> <?php
								locate_template( array( 'groups/single/activity.php' ), true ); ?>
							</div> 
						</div><!-- #chatter-container --> <?php

					elseif ( bp_group_is_visible() ) :
						locate_template( array( 'groups/single/members.php' ), true );

					elseif ( !bp_group_is_visible() ) :
						// The group is not visible, show the status message

						do_action( 'bp_before_group_status_message' );

					endif; ?>

				</div><!-- #item-body -->

			<?php endwhile; endif; ?>

		</div><!-- .padder -->
	</div><!-- #content -->
</div><!-- #wrapper -->
<?php get_footer(); ?>