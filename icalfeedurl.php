<?php

if(!array_key_exists('uid',$_GET)) {
	die();
}

require_once('etc/config.php');

function _run_icalid_query($uid) {
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
		return $row['uid'];
	} 
	else {
		return NULL;
	}
}

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
		return $row['uid'];
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

$uid = $_GET['uid'];
$hid = hash_to_icalid($uid);

if($hid!==NULL) {
	print 'webcal://www.onlyinpgh.com/icalgenerate?hid=' . $hid;
}