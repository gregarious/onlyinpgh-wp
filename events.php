<?php

require_once 'etc/config.php';
require_once 'include/eventsearcher.class.php';

if(!array_key_exists('eid',$_GET)) {
	die();
}
$eid = intval($_GET['eid']);

$searcher = new EventSearcher();

$searcher->queryLocation();
$searcher->queryOrganization();

// WP/BP functions -- this means this PHP script won't work without WP calling it
if( is_user_logged_in() ) {
	$searcher->queryAttendance(bp_loggedin_user_id());
}

$searcher->filterByEventId($eid);
$results = $searcher->runQuery(0,1);


function onSameDay($dt1,$dt2) {
	if( $dt1->format('H:i') < '04:01' ) {
		$dt1->sub(new DateInterval('P1D'));
	}
	if( $dt2->format('H:i') < '04:01' ) {
		$dt2->sub(new DateInterval('P1D'));
	}

	return $dt1->format('Y-m-d') == $dt2->format('Y-m-d');
}


/*echo '<pre>';
echo print_r($results);
echo '</pre>';*/
