<?php

require_once(ABSPATH . 'etc/config.php');

function hash_to_icalid($uid) {
	// look in the db for an entry for this user id
	$sql = 'SELECT `hid` FROM `oip_icalid_mapping` WHERE `uid`=:uid';
	try {
		$pdo = new PDO('mysql:host='.OIP_DB_HOST.';dbname='.OIP_DB_NAME, 
						OIP_DB_USER, OIP_DB_PASSWORD);
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$statement = $pdo->prepare($sql);
		$statement->execute(array('uid'=>$uid));
		$statement->setFetchMode(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e) {  
	    die('PDO MySQL error: ' . $e->getMessage());  
	}

	$row = $statement->fetch();
	if($row) {
		return $row['hid'];
	} 

	// if we get here, the user id doesn't have a mapped icalid yet. let's add one.
	$hid = sha1('kosher'.$uid.'salt');
	$sql = 'INSERT INTO  `onlyinpgh_dev`.`oip_icalid_mapping` 
				(`uid`,`hid`)
			VALUES ( :uid, :hid )';
	try {
		$statement = $pdo->prepare($sql);
		$statement->execute(array('uid'=>$uid,'hid'=>$hid));
		$statement->setFetchMode(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e) {  
	    die('PDO MySQL error: ' . $e->getMessage());  
	}
	return $hid;
}

function get_ical_url($userid) {
	$hid = hash_to_icalid($userid);
	if($hid!==NULL) {
		return 'webcal://www.onlyinpgh.com/ical?hid=' . $hid;
	}
	else {
		return NULL;
	}
}
