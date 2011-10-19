<?php get_header( 'buddypress' ); ?>

<div id="wrapper">
	<div id="content">
		<div class="padder" id="scene">

		<form action="" method="post" id="groups-directory-form" class="dir-form">

			<?php do_action( 'bp_before_directory_groups_content' ); ?>

			<div class="top-stuff">
				<div class="item-list-tabs" role="navigation">
						
					<ul>
						<li><a href="<?php get_bloginfo('site_url'); ?>/scenes"><?php echo 'About'; ?></a></li>
						<?php
						// Toggling the active group tabs
						////***** CHANGE GROUP SLUGS HERE *****///
						global $bp;
						$group = $bp->groups->current_group->name;
						$art = 'Arts Scene';
						$music = 'Music Scene';
						
						if ( $group == $music ) { ?>
							<li class="selected"><a href="<?php echo get_bloginfo('url') . '/scenes/music-scene/';?>">Music</a></li>
							<li><a href="<?php echo get_bloginfo('url') . '/scenes/arts-scene/';?>">Arts</a></li><?php
						} else if ( $group == $art ) { ?>
							<li><a href="<?php echo get_bloginfo('url') . '/scenes/music-scene/';?>">Music</a></li>
							<li class="selected"><a href="<?php echo get_bloginfo('url') . '/scenes/arts-scene/';?>">Arts</a></li> <?php
						} ?>

					</ul> 

				</div><!-- .item-list-tabs -->


				<div class="email-form">
				<?php
				// If user is a member, show the email form for that group
				$is_member = $bp->groups->current_group->is_member;
				if ( $is_member == 1 ) { ?>
					<?php
						if ( $group == $art ) { ?>
							<p><strong>Sign up for Art Scene emails:</strong></p><?php
							echo do_shortcode('[gravityform id=10 name=UntitledForm title=false]');
						} else if ( $group == $music ) { ?>
							<p><strong>Sign up for Music Scene emails:</strong></p><?php
							echo do_shortcode('[gravityform id=10 name=UntitledForm title=false]');
						} 
					} else if ( $is_member == 0 && is_user_logged_in() ) { ?>
						<h4>Join Scene for email updates!</h4> <?php
					} else { ?>
						<h4><a href="<?php get_bloginfo('site_url'); ?>/register">Sign up</a> or <a class="simplemodal-login" href="/wp-login.php?redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>">login</a> for scene updates!</h4> <?php
					}?>
				</div>
					
			</div><!-- .top-stuff -->

			<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

				<?php do_action( 'bp_before_group_home_content' ) ?>

				<div id="news-container" class="scene-part">
					<h2 class="scene-part-title">From the Burghosphere</h2> 
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
					
					<div id="place-chat-container">
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
						
						<!--[if !IE]><!-->
						<div id="chatter-container">
							<h2 class="scene-part-title">The Chatter Box</h2>
							<div class="chatter-content"><?php
							
								if ( is_user_logged_in() && bp_group_is_member() ) :
									locate_template( array( 'activity/post-form.php'), true );
								else: ?>
									<h4><a href="<?php get_bloginfo('site_url'); ?>/register">Sign up</a> or <a class="simplemodal-login" href="/wp-login.php?redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>">login</a> to post!</h4> <?php
									
									do_action( 'bp_after_group_activity_post_form' );
									do_action( 'bp_before_group_activity_content' ); 
								
								endif;
								
								locate_template( array( 'groups/single/activity.php' ), true ); ?>		
						
							</div> <!-- .chatter-content -->
						</div><!-- #chatter-container --> 
						<!--<![endif]-->
						
						<!--[if IE]>
						<div class="fuck-ie">
							<style type="text/css">
								a.stupid:hover {
									text-decoration:none;
								}
							</style>
							<h1><a class="stupid" style="cursor:text;">Please download</a> <a href="http://www.mozilla.org/en-US/firefox/new/">FireFox</a>, <a href="http://www.google.com/chrome">Chrome</a>, or another modern browser to take advantage of the features on our site. Thanks!</h1>
						</div>
						<![endif]--> <?php

						elseif ( bp_group_is_visible() ) :
							locate_template( array( 'groups/single/members.php' ), true );

						elseif ( !bp_group_is_visible() ) :
							// The group is not visible, show the status message

							do_action( 'bp_before_group_status_message' );

						endif; ?>
						
					</div> <!-- #place-chat-container-->
					
				</div><!-- #item-body -->

				<div id="survey-container">
					<h2 class="scene-part-title show-survey">What did you think of this scene? Click to let us know.</h2>
					<div id="scene-survey"><?php
						echo do_shortcode('[gravityform id=9 name=Whatdo you think of Scenes? title=false ajax=true]'); ?>
					</div>
				</div> <!-- #survey-container -->

					
			<?php endwhile; endif; ?>

		</div><!-- .padder -->
	</div><!-- #content -->

</div><!-- #wrapper -->
<?php get_footer(); ?>

<script>

jQuery(document).ready( function() {
	jQuery("#scene-survey").hide();
	jQuery(".show-survey").click( function() {
		jQuery("#scene-survey").slideToggle(900);
	});

});

</script>