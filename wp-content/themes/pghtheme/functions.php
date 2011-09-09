<?php

///////////////////////
// REGISTER SIDEBARS //
///////////////////////

// The first sidebar
 if(function_exists('register_sidebar'))
      register_sidebar(array(
      'name' => 'Footer Sidebar One', // The sidebar name to register
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h5>',
      'after_title' => '</h5>',
 ));
 
 // The second sidebar
 if(function_exists('register_sidebar'))
      register_sidebar(array(
      'name' => 'Footer Sidebar Two', // The sidebar name to register
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h5>',
      'after_title' => '</h5>',
 ));
 
 // The third sidebar
 if(function_exists('register_sidebar'))
      register_sidebar(array(
      'name' => 'Footer Sidebar Three', // The sidebar name to register
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h5>',
      'after_title' => '</h5>',
 ));


if(function_exists('register_sidebar'))
	register_sidebar(array(
		'name' => 'Blog Sidebar Area', // The sidebar name to register
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h5>',
		'after_title' => '</h5>',
 ));

if(function_exists('register_sidebar'))
      register_sidebar(array(
      'name' => 'Ad Space 1', // The sidebar name to register
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h5>',
      'after_title' => '</h5>',
 ));

if(function_exists('register_sidebar'))
      register_sidebar(array(
      'name' => 'Ad Space 2', // The sidebar name to register
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h5>',
      'after_title' => '</h5>',
 ));          
 
if(function_exists('register_sidebar'))
      register_sidebar(array(
      'name' => 'Ad Space 3', // The sidebar name to register
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h5>',
      'after_title' => '</h5>',
 ));          
 
if(function_exists('register_sidebar'))
      register_sidebar(array(
      'name' => 'Ad Space 4', // The sidebar name to register
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h5>',
      'after_title' => '</h5>',
 ));	
 
if(function_exists('register_sidebar'))
      register_sidebar(array(
      'name' => 'Submit Photos', // The sidebar name to register
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h5>',
      'after_title' => '</h5>',
 ));	
 
if(function_exists('register_sidebar'))
      register_sidebar(array(
      'name' => 'Search Photos', // The sidebar name to register
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h5>',
      'after_title' => '</h5>',
 ));	
 
if(function_exists('register_sidebar'))
      register_sidebar(array(
      'name' => 'Contest Info', // The sidebar name to register
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h5>',
      'after_title' => '</h5>',
 ));

if(function_exists('register_sidebar'))
      register_sidebar(array(
      'name' => 'Photo Sidebar 3', // The sidebar name to register
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h5>',
      'after_title' => '</h5>',
 ));


//////////
// MISC //
//////////

// Hide admin bar
remove_action('wp_footer', 'bp_core_admin_bar', 8);
remove_action('admin_footer', 'bp_core_amdin_bar');

// Defining header image size
define( 'HEADER_IMAGE_HEIGHT', 260 );

// This theme uses wp_nav_menu() in one location.
register_nav_menus( array(
	'primary' => __( 'Primary Navigation' ),
	) );

function my_init_method() {
      
      // let WP load jQuery on it's own if we're in the admin panel. if we load 
      // this in the admin panel, conflicts will arise.
      // See http://digwp.com/2009/06/use-google-hosted-javascript-libraries-still-the-right-way/
      if( !is_admin()) {      
            wp_deregister_script('jquery'); 
            wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js"), false, '1.6.3');
            wp_enqueue_script('jquery');
      }
}
add_action('init', 'my_init_method');

add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 9999999, 150); // 100 pixels wide by 100 pixels tall, hand crop

function new_excerpt_more($more) {
	return '';
}
add_filter('excerpt_more', 'new_excerpt_more');

function new_excerpt_length($length) {
	return 30;
}
add_filter('excerpt_length', 'new_excerpt_length');


// Customizing title for BuddyPress Profile page
// http://buddypress.org/community/groups/how-to-and-troubleshooting/forum/topic/editing-group-and-forum-page-title-tags/
function my_page_title( $title, $b ) {
	global $bp;

	if ( $bp->current_action == 'forum' && $bp->action_variables[0] == 'topic' ) {
		if ( bp_has_topic_posts() ) {
			$topic_title = bp_get_the_topic_title();
			$title .= ' | ' . $topic_title;
		}
	}
	return $title;
}
add_filter( 'bp_page_title', 'my_page_title', 10, 2 );

?>