<?php

require_once 'etc/config.php';

if(!array_key_exists('eid',$_GET)) {
	die();
}

$eid = intval($_GET['eid']);

$sql = "SELECT DISTINCT e.event_name, 
							e.event_id, 
							e.event_notes,
							e.event_start_date, 
							e.event_end_date, 
							e.event_start_time, 
							e.event_end_time, 
							e.event_slug, 
							l.location_address, 
							l.location_latitude, 
							l.location_longitude, 
							e.event_pic,
							o.name AS organization_name, 
							o.link_url AS organization_link_url, 
							o.fan_count AS organization_fan_count, 
							e.event_type
							FROM wp_em_events e 
								JOIN wp_em_locations l USING (location_id) 
								JOIN wp_em_organizations o 
							WHERE (o.id = e.event_creator) AND (e.event_id = :eid)"; 

try {
	$pdo = new PDO('mysql:host='.OIP_DB_HOST.';dbname='.OIP_DB_NAME, 
					OIP_DB_USER, OIP_DB_PASSWORD);
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$statement = $pdo->prepare($sql);
	$statement->execute(array('eid'=>$eid));
	$statement->setFetchMode(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {  
    die('PDO MySQL error: ' . $e->getMessage());  
} 
