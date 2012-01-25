<?php

require_once 'etc/config.php';
require_once 'include/eventsearcher.class.php';

if(!array_key_exists('date',$_GET)) {
	die();
}

$DAYTIME_CUTOFF = '09:00';  // anytime before 4am is considered part of the previous day (for now using 9 AM for utc)

$date_str = $_GET['date'];
$dt = new DateTime($date_str . ' ' . $DAYTIME_CUTOFF);
$date_txt = $dt->format('l, F j, Y');

$startdt = $dt->format('Y-m-d h:i');
$enddt = $dt->add(new DateInterval('P1D'))->format('Y-m-d h:i');

// // Prev/next day navigation
$dt = new DateTime($date_str);
$iter = clone $dt;
$prev_day_anchor = $iter->sub(new DateInterval('P1D'))->format('Y-m-d');
$next_day_anchor = $iter->add(new DateInterval('P2D'))->format('Y-m-d');

$searcher = new EventSearcher();
$searcher->queryLocation();
$searcher->queryOrganization();
$searcher->setTimezone('US/Eastern');

// WP/BP functions -- this means this PHP script won't work without WP calling it
if( is_user_logged_in() ) {
	$searcher->queryAttendance(bp_loggedin_user_id());
}

$searcher->filterByStartDate($startdt);
$searcher->filterByEndDate($enddt);

$events = $searcher->runQuery(0,10000);		// runQuery needs a result limit, use something huge

?>
