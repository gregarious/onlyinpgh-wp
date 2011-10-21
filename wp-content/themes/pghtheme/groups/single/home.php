<?php get_header( 'buddypress' ); 

global $bp;
$group = $bp->groups->current_group->name;
$art = 'Arts Scene';
$music = 'Music Scene';
$is_member = $bp->groups->current_group->is_member;

?>

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
						
						if ( $group == $music ) { ?>
							<li class="selected"><a href="<?php echo get_bloginfo('url') . '/scenes/music-scene/';?>">Music</a></li>
							<li><a href="<?php echo get_bloginfo('url') . '/scenes/arts-scene/';?>">Arts</a></li><?php
						} else if ( $group == $art ) { ?>
							<li><a href="<?php echo get_bloginfo('url') . '/scenes/music-scene/';?>">Music</a></li>
							<li class="selected"><a href="<?php echo get_bloginfo('url') . '/scenes/arts-scene/';?>">Arts</a></li> <?php
						} ?>

					</ul> 

				</div><!-- .item-list-tabs -->


		
					
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


				<div id="survey-container"><?php
					if ( $is_member == 1 ) { ?>
						<h2 class="scene-part-title show-survey loggedin">Click to sign up for scene emails:</h2><?php
					} else if ( $is_member == 0 && is_user_logged_in() ) { ?>
						<a href="#join-scene"><h2 class="scene-part-title show-survey">Join this Scene for email updates!</h4></a> <?php
					} else { ?>
						<a class="simplemodal-login" href="/wp-login.php?redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>"><h2 class="scene-part-title show-survey">Login and join the scene for email updates!</h2></a> <?php
					}?>
					<div id="scene-survey"><?php
						//echo do_shortcode('[gravityform id=10 name=SceneSurvey title=false ajax=true]'); 
						if ( $group == $music ) { ?>
							<iframe src="https://docs.google.com/spreadsheet/embeddedform?formkey=dEd5RnBvSzNSR2RrZkpwNUVubW5LWlE6MQ" width="760" height="229" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe><?php
						} else if ( $group == $art ) { ?>
							<iframe src="https://docs.google.com/spreadsheet/embeddedform?formkey=dGNaeGpPRlJNRWhjUV9WNmRCMjk2SGc6MQ" width="760" height="229" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe><?php
						}?>
						
						
					</div>
				</div> <!-- #survey-container -->
					
					
			<?php endwhile; endif; ?>

		</div><!-- .padder -->
	</div><!-- #content -->

</div><!-- #wrapper -->
<?php get_footer(); ?>


<script>

	jQuery("#scene-survey").hide();
	jQuery(".loggedin").click( function() {
		jQuery("#scene-survey").slideToggle(900);
	});

</script>