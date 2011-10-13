<?php get_header( 'buddypress' ); ?>

<div id="wrapper">
	<div id="content">
		<div class="padder" id="scene">

		<form action="" method="post" id="groups-directory-form" class="dir-form">

			<!-- Commenting out Create Group functionality -->
			<h3><?php _e( 'Scenes', 'buddypress' ); ?><!--<?php //if ( is_user_logged_in() && bp_user_can_create_groups() ) : ?> &nbsp;<a class="button" href="<?php //echo trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create' ); ?>"><?php //_e( 'Add a Scene', 'buddypress' ); ?></a><?php //endif; ?>--></h3>

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
					<div class="news-content"> <?php
						locate_template( array( 'mypgh-templates/scene-news.php' ), true ); ?>							
					</div>
				</div>
				
				<div id="item-header" role="complementary" class="scene">

					<?php locate_template( array( 'groups/single/group-header.php' ), true ); ?>

				</div><!-- #item-header -->

		<!--<pre>
			<?php 
			global $bp;
			echo '<strong>CUSTOM</strong><br>';
			print_r(bp_is_group_single());
			//print_r($bp);
			
			?>
		</pre>-->

				<div id="item-body">
					<div id="events-container" class="scene-part">
						<?php locate_template( array( 'mypgh-templates/scene-events.php' ), true ); ?>							
					</div>
					
					<div id="places-container" class="scene-part">
						<?php locate_template( array( 'mypgh-templates/scene-places.php' ), true ); ?>							
					</div>

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
							<h2>The Chatter Box</h2>
								<div class="chatter-content"> 
									<?php
										if ( is_user_logged_in() && bp_group_is_member() ) :
											locate_template( array( 'activity/post-form.php'), true );
										else: ?>
											<h4>
												<a href="/wp-login.php?redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>" class="sign-up simplemodal-login"> Sign up or login to post!</a> 
											</h4><?php
											do_action( 'bp_after_group_activity_post_form' );
											do_action( 'bp_before_group_activity_content' ); 
										
										endif; ?>
									<?php
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