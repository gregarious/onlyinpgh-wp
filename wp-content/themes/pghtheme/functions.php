<?php


////////////////////////
// CALENDAR FUNCTIONS //
////////////////////////

/*

Function to Draw the calendar and it's controls.

Thanks:
David's Walsh's PHP Event Calendar tutorial: 
http://davidwalsh.name/php-event-calendar

CSS Tricks's Elastic Calendar Styling with CSS Tutorial:
http://css-tricks.com/794-elastic-calendar-styling-with-pure-css/

*/                

//// NEED TO DEBUG /////
// Add a wrapper function to variables
//calendar_vars_wrap();



//// NEED TO DEBUG ///// breaks when switching years
// AJAX not working.
// Current/past/future days not register when loaded (something to do with above)
// Also add a button that takes you back to current day
// Single/week/biweekly day views? Bah.


// Draw the calendar
function draw_calendar($month,$year) {

      // Open table 
      $calendar = '<ol class="calendar">';

      // Table headings
      $headings = array('<li class="weekday">Sunday</li>','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
      $calendar.= '<li class="weekday-head"><ol>'.implode('<li class="weekday">',$headings).'</li></ol></li>';

      // Define day and week variables
      $running_day = date('w',mktime(0,0,0,$month,1,$year));
      $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
      $days_in_this_week = 1;
      $day_counter = 0;
      $dates_array = array();
      $prev_month_days_arr = array();
      $next_month_days_arr = array();
      
      // Row for week one
      $calendar.= '<li id="thismonth"><ol>';

      //// NEED TO DEBUG ///// - pop off 0 and reverse
      // Print previous month days until the first of the current week
      for($x = 0; $x < $running_day; $x++):
            $prev_month =  ($month != 1 ? $month - 1 : 12);
            $days_in_prev_month = date('t',mktime(0,0,0,$prev_month,1,$year));
            
            for($i = 0; $i < $days_in_prev_month; $i++):
                  array_push($prev_month_days_arr, strval($i));
            endfor;
            
            $calendar.= '<li class="calendar-day" id="day-prev-month">'.$prev_month_days_arr[$x].'</li>';
            $days_in_this_week++;
      endfor;

      // Add the day cell, highlight current
      for($list_day = 1; $list_day <= $days_in_month; $list_day++):
      
            //$calendar.= '<td class="calendar-day"><div class="day-single">';
    
            if ($list_day == date('d') && $month == date('n') && $year == date('Y')):
                  $calendar.= '<li class="calendar-day" id="day-today">'.$list_day.'</li>';               
            elseif ($list_day < date('d') && $month == date('n') && $year == date('Y')):
                  $calendar.= '<li class="calendar-day" id="day-past">'.$list_day.'</li>';
                                    
            else:
                  $calendar.= '<li class="calendar-day" id="day-future">'.$list_day.'</li>';
            endif;
                        
            // Close the day cell
            //$calendar.= '</div></td>';
            
            // Create a new row for new week
            /*if($running_day == 6):
                  $calendar.= '</tr>';
            if(($day_counter+1) != $days_in_month):
                  $calendar.= '<tr class="calendar-row">';
            endif;*/
            
            $running_day = -1;
            $days_in_this_week = 0;
    
            //endif;
    
            $days_in_this_week++; $running_day++; $day_counter++;

      endfor;

      //// NEED TO DEBUG /////
      // Finish the rest of the days in the week with next months days - printing 3 extras now
      if($days_in_this_week < 8):
            for($x = 1; $x <= (8 - $days_in_this_week); $x++):            
                  $next_month =  ($month != 1 ? $month + 1 : 12);
                  $days_in_next_month = date('t',mktime(0,0,0,$next_month,1,$year));
            
                  for($i = 0; $i < $days_in_next_month; $i++):
                        array_push($next_month_days_arr, strval($i));
                  endfor;
            
                  $calendar.= '<li class="calendar-day" id="day-prev-month">'.$next_month_days_arr[$x].'</li>';
                  $days_in_this_week++;
            endfor;
      endif;

      // Final row
      $calendar.= '</ol></li>';

      // End the table
      $calendar.= '</ol>';

      ///* DEBUG *///
      $calendar = str_replace('</li>','</li>'."\n",$calendar);
      $calendar = str_replace('</ol>','</ol>'."\n",$calendar);

      // Draw the thing
      echo $calendar;
}


function draw_ajax_calendar($month,$year) {
      draw_calendar($month,$year);
      exit();
}


// Thanks: http://www.php.net/manual/en/function.array-pop.php#99629
/*function array_pop_first(&$array) {
    $array = array_reverse($array);
    array_pop($array);
    $array = array_reverse($array);
}*/

function random_number() {
      srand(time());
      return (rand() % 7);
}

// Thanks: PirateKitten at StackOverflow:
// http://stackoverflow.com/questions/7433110/move-through-php-calendar-months-with-jquery-ajax
$month = (int) ($_POST['month'] ? $_POST['month']
                    : ($_GET['month'] ? $_GET['month'] 
                                      : date('m')));
                                      
$year = (int) ($_POST['year'] ? $_POST['year']
                   : ($_GET['year'] ? $_GET['year'] 
                                      : date('Y')));

$prevMonthVal = ($month != 1 ? $month - 1 : 12);
$prevYearVal = ($month != 1 ? $year : $year - 1);

$nextMonthVal = ($month != 12 ? $month + 1 : 1);
$nextYearVal = ($month != 12 ? $year : $year + 1);

// Prep some variable strings to avoid a lot of messy <?php echo X; ?\> crap in the HTML
$month_title = date('F',mktime(0,0,0,$month,1,$year)) . ' ' . $year;
$prev_href = '?month=' . $prevMonthVal . '&year=' . $prevYearVal;
$next_href = '?month=' . $nextMonthVal . '&year=' . $nextYearVal;



// Adding the AJAX hooks
add_action('wp_ajax_nopriv_my_special_action', 'draw_ajax_calendar');
add_action('wp_ajax_my_special_ajax', 'draw_ajax_calendar');


function enqueue_calendar_script() {
      
    // embed the javascript file that makes the AJAX request
    wp_register_script( 'calendar-script.js', get_bloginfo('stylesheet_directory').'/scripts/calendar-script.js');
    wp_enqueue_script( 'calendar-script.js' );

    // declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
    wp_localize_script( 'calendar-script.js', 'wp_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); 

}
add_action('wp-head', 'enqueue_calendar_scripts');



/* NOT USING */

function ajax_admin_init(){
      if( !defined('DOING_AJAX') && !current_user_can('administrator') ){
            wp_redirect( home_url() );
            exit();
      }
}
//add_action('admin_init','ajax_admin_init');




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
		'class'	=> "attachment-post-thumbnail wp-post-image");

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



?>