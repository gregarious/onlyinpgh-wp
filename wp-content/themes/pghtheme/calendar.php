<?php

/*

Function to Draw the calendar and it's controls.

Credit:
David's Walsh's PHP Event Calendar tutorial: 
http://davidwalsh.name/php-event-calendar

*/			


// Draw the calendar
function draw_calendar($month,$year,$events = array()) {

	// Open table 
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

  	// Table headings
	$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	// Define day and week variables
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	// Row for week one
	$calendar.= '<tr class="calendar-row">';

	// Print "blank" days until the first of the current week (will likely change this for Onlyinpgh)
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		$days_in_this_week++;
	endfor;

	// Add the day cell, highlight current
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
	
    	$calendar.= '<td class="calendar-day"><div style="position:relative;height:100px;">';
    
    
      	if ($list_day == date('d') && $month == date('n') && $year == date('Y')):
			$calendar.= '<div class="day-today">'.$list_day.'</div>';			
		else:
			$calendar.= '<div class="day-number">'.$list_day.'</div>';
		endif;


		$event_day = $year.'-'.$month.'-'.$list_day;
		
		// If there are no events, echo a "blank"
		if(isset($events[$event_day])):
        	foreach($events[$event_day] as $event):
          		$calendar.= '<div class="event">'.$event['title'].'</div>';
        	endforeach;
		else:
			$calendar.= str_repeat('<p>&nbsp;</p>',2);
		endif;
		
		// Close the day cell
		$calendar.= '</div></td>';
		
		// Create a new row for new week
		if($running_day == 6):
			$calendar.= '</tr>';
		if(($day_counter+1) != $days_in_month):
			$calendar.= '<tr class="calendar-row">';
		endif;
		
		$running_day = -1;
		$days_in_this_week = 0;
    
		endif;
    
		$days_in_this_week++; $running_day++; $day_counter++;

	endfor;

	// Finish the rest of the days in the week
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		endfor;
	endif;

	// Final row
	$calendar.= '</tr>';
	
	// End the table
	$calendar.= '</table>';
	
	///* DEBUG *///
	$calendar = str_replace('</td>','</td>'."\n",$calendar);
	$calendar = str_replace('</tr>','</tr>'."\n",$calendar);
	
	// Return result
	return $calendar;
  
}

function random_number() {
	srand(time());
	return (rand() % 7);
}

// Date settings
$month = (int) ($_GET['month'] ? $_GET['month'] : date('m'));
$year = (int)  ($_GET['year'] ? $_GET['year'] : date('Y'));


// Select month control
$select_month_control = '<select name="month" id="month">';



for($x = 1; $x <= 12; $x++) {
	$select_month_control.= '<option value="'.$x.'"'.($x != $m ? '' : ' selected="selected"').'>'.date('F',mktime(0,0,0,$x,1,$y)).'</option>';
}

$select_month_control.= '</select>';



// Select year control
$year_range = 7;
$select_year_control = '<select name="year" id="year">';

for($x = ($year-floor($year_range/2)); $x <= ($year+floor($year_range/2)); $x++) {
	$select_year_control.= '<option value="'.$x.'"'.($x != $year ? '' : ' selected="selected"').'>'.$x.'</option>';
}

$select_year_control.= '</select>';

// "Next Month" control
$next_month_link = '<a href="?month='.($month != 12 ? $month + 1 : 1).'&year='.($month != 12 ? $year : $year + 1).'" class="control">Next Month &gt;&gt;</a>';


// "Previous Month" control
$previous_month_link = '<a href="?month='.($month != 1 ? $month - 1 : 12).'&year='.($month != 1 ? $year : $year - 1).'" class="control">&lt;&lt;   Previous Month</a>';


// Bringing the controls together
$controls = '<form method="get">'.$select_month_control.$select_year_control.'&nbsp;<input type="submit" name="submit" value="Go" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$previous_month_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$next_month_link.' </form>';


/* get all events for the given month 
$events = array();
$query = &quot;SELECT title, DATE_FORMAT(event_date,'%Y-%m-%D') AS event_date FROM events WHERE event_date LIKE '$year-$month%'&quot;
$result = mysql_query($query,$db_link) or die('cannot get results!');
while($row = mysql_fetch_assoc($result)) {
  $events[$row['event_date']][] = $row;
}*/

// Print the calendar and controls 
echo '<h2 style="float:left; padding-right:30px;">'.date('F',mktime(0,0,0,$month,1,$year)).' '.$year.'</h2>';
echo '<div style="float:left;">'.$controls.'</div>';
echo '<div style="clear:both;"></div>';
echo draw_calendar($month,$year,$events);
echo '<br /><br />';


			
?>