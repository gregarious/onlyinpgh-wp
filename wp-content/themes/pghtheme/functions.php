<?php
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
     ));          if(function_exists('register_sidebar'))
          register_sidebar(array(
          'name' => 'Ad Space 3', // The sidebar name to register
          'before_widget' => '<div class="widget">',
          'after_widget' => '</div>',
          'before_title' => '<h5>',
          'after_title' => '</h5>',
     ));          if(function_exists('register_sidebar'))
          register_sidebar(array(
          'name' => 'Ad Space 4', // The sidebar name to register
          'before_widget' => '<div class="widget">',
          'after_widget' => '</div>',
          'before_title' => '<h5>',
          'after_title' => '</h5>',
     ));	if(function_exists('register_sidebar'))
          register_sidebar(array(
          'name' => 'Submit Photos', // The sidebar name to register
          'before_widget' => '<div class="widget">',
          'after_widget' => '</div>',
          'before_title' => '<h5>',
          'after_title' => '</h5>',
     ));	if(function_exists('register_sidebar'))
          register_sidebar(array(
          'name' => 'Search Photos', // The sidebar name to register
          'before_widget' => '<div class="widget">',
          'after_widget' => '</div>',
          'before_title' => '<h5>',
          'after_title' => '</h5>',
     ));	if(function_exists('register_sidebar'))
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

define( 'HEADER_IMAGE_HEIGHT', 260 );

// This theme uses wp_nav_menu() in one location.
register_nav_menus( array(
		'primary' => __( 'Primary Navigation' ),
	) );

function my_init_method() {
		wp_enqueue_script('jquery', 'http://onlyinpgh.com/wp-includes/js/jquery/jquery.js');
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



?>