<?php

////////////////
// THUMBNAILS //
////////////////


// Creating thumbnails from attachments: code from theme WPFolio Two http://notlaura.com/wpfolio-two


// Add support for post thumbnails of 250px square
// Add custom image size for cat thumbnails
if ( function_exists( 'add_theme_support' ) ) {
      add_theme_support( 'post-thumbnails' );
      set_post_thumbnail_size( 200, 200, true );
      add_image_size('wpf-thumb', 200, 200, true);
}


// Get post attachments
function wpf_get_attachments() {
      global $post;
      return get_posts( 
            array(
                  'post_parent' => get_the_ID(), 
                  'post_type' => 'attachment', 
                  'post_mime_type' => 'image') 
            );
}

// Get the URL of the first attachment image - called in wpf-category.php. If no attachments, display default-thumb.png
function wpf_get_first_thumb_url() {

      $attr = array( 
            'class'     => "attachment-post-thumbnail wp-post-image");

      $imgs = wpf_get_attachments();
      if ($imgs) {
            $keys = array_reverse($imgs);
            $num = $keys[0];
            $url = wp_get_attachment_image($num->ID, 'wpf-thumb', true,$attr);
            print $url;
      } else {
            echo '<img src=http://notlaura.com/default-thumb.png alt="no attachments here!" title="default thumb" class="attachment-post-thumbnail wp-post-image">';
      }
}

// END - get attachment function

// Make featured image thumbnail a permalink
add_filter( 'post_thumbnail_html', 'my_post_image_html', 10, 3 );
function my_post_image_html( $html, $post_id, $post_image_id ) {
      $html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '">' . $html . '</a>';
      return $html;
}




// Do not enqueue BuddyPress stylesheets and enqueue our's last

function bp_dtheme_enqueue_styles() {
    wp_enqueue_style( 'pgh-child-theme-css', get_stylesheet_directory_uri() . '/css/pgh-default.css', array(), $version );
}
add_action( 'wp_print_styles', 'bp_dtheme_enqueue_styles' );


//////////////////////
// REGISTER WIDGETS //
//////////////////////

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
      'before_widget' => '<div class="widget" id="search-photos">',
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

/*if(function_exists('register_sidebar'))
      register_sidebar(array(
      'name' => 'Photo Sidebar 3', // The sidebar name to register
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h5>',
      'after_title' => '</h5>',
 ));*/



//////////
// MISC //
//////////

// Hide admin bar from all users
remove_action('wp_footer', 'bp_core_admin_bar', 8);
remove_action('admin_footer', 'bp_core_amdin_bar');

// Defining header image size
define( 'HEADER_IMAGE_HEIGHT', 260 );

// This theme uses wp_nav_menu() in one location.
register_nav_menus( array(
	'primary' => __( 'Primary Navigation' ),
	) );

// Let WP load jQuery on it's own if we're in the admin panel. 
// http://digwp.com/2009/06/use-google-hosted-javascript-libraries-still-the-right-way/

function my_init_method() {
      
      if( !is_admin()) {      
            wp_deregister_script('jquery'); 
            wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js"), false, '1.6.3');
            wp_enqueue_script('jquery');
      }
}
add_action('init', 'my_init_method');


function new_excerpt_more($more) {
	return '';
}
add_filter('excerpt_more', 'new_excerpt_more');

// Changing excerpt length to 30 words
function new_excerpt_length($length) {
	return 30;
}
add_filter('excerpt_length', 'new_excerpt_length'); 


function getCanonicalEventURL($eid) {
      return "http://www.onlyinpgh.com/event/".$eid;
}

?>