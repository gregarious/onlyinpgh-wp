<?php

if(!array_key_exists('hid',$_GET)) {
	header("Content-type: text/calendar");
	die();
}

require_once('etc/config.php');
require_once('include/icalendar.php');

$hid = $_GET['hid'];
$uid = unhash($hid);

iCalGenerator($uid);

function unhash($hid) {
	return $hid;
}
?>