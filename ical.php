<?php

if(!array_key_exists('hid',$_GET)) {
	header("Content-type: text/calendar");
	die();
}

require_once('etc/config.php');
require_once('include/MyPghiCal.class.php');

function unhash_from_icalid($hid) {
	$sql = 'SELECT `uid` FROM `oip_icalid_mapping` WHERE `hid`=:hid';
	try {
		$pdo = new PDO('mysql:host='.OIP_DB_HOST.';dbname='.OIP_DB_NAME, 
						OIP_DB_USER, OIP_DB_PASSWORD);
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$statement = $pdo->prepare($sql);
		$statement->execute(array('hid'=>$hid));
		$statement->setFetchMode(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e) {  
	    die('PDO MySQL error: ' . $e->getMessage());  
	}

	$row = $statement->fetch();
	if($row) {
		return $row['uid'];
	} 
	else {
		return NULL;
	}
}

$hid = $_GET['hid'];
$uid = unhash_from_icalid($hid);

if($uid===NULL) {
	header("Content-type: text/calendar");
	die();
}

$ical = new MyPghiCal($uid,$hid.'.ics');
$ical->generate('Test calendar');

?>