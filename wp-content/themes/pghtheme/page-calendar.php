<?php

/*
Template Name: Events Calendar
*/

get_header(); 

function my_scripts_method() {
    wp_enqueue_script('calendar');            
}    
 
add_action('wp_enqueue_scripts', 'my_scripts_method');

?>



	<div id="content">
		<div class="padder" id="cal-page">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page">

			<h2 class="pagetitle">Upcoming Events</h2>
			
			<?php require_once('calendar.php'); ?>
					
		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
		
</div><!-- #content -->

<?php get_footer() ?>
