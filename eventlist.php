<?php

require_once 'etc/config.php';
require_once 'include/eventsearcher.class.php';

if(!array_key_exists('date',$_GET)) {
	die();
}

$date_str = $_GET['date'];

// Prev/next day navigation
$date_dt = new DateTime($date_str);
$iter = clone $date_dt;
$prev_day_anchor = $iter->sub(new DateInterval('P1D'))->format('Y-m-d');
$next_day_anchor = $iter->add(new DateInterval('P2D'))->format('Y-m-d');

$date_txt = $date_dt->format('l, F j, Y');

$searcher = new EventSearcher();
$searcher->queryLocation();
$searcher->queryOrganization();
$searcher->setTimezone('US/Eastern');

// WP/BP functions -- this means this PHP script won't work without WP calling it
if( is_user_logged_in() ) {
	$searcher->queryAttendance(bp_loggedin_user_id());
}

$searcher->filterByStartDate($date_str);
$searcher->filterByEndDate($date_str);

$events = $searcher->runQuery(0,10000);		// runQuery needs a result limit, use something huge

?>
