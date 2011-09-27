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
      // Finish the rest of the days in the week with next months days - printing 3 extras now?
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
