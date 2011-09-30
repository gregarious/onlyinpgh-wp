<?php
/*
 	searchevents.php

	Input arguments (all are optional and default to NULL):
	- 'startdate' 	: minimum start date of events (in MySQL DATE-friendly 
						format (e.g. YYYY-MM-DD))
	- 'enddate'   	: max end date of events (in MySQL DATE-friendly format)
	- 'userid' 		: id of user making the request (used to determine
						value of attending in results)

	Output arguments:
*/

//require_once('../etc/config.php');

function findEvents($startdate=NULL,$enddate=NULL,$userid=NULL,$eventid=NULL) {
	/* QUERY BUILDING 
		There are two basic kinds of queries: One with all event info in entire 
		DB, and one with a specified center point. Either of these queries can be
		made more selective by adding the following filters:
		- startdate and/or enddate
		- keyword search
		- offset and/or limit (default limit is 100)

		The query will be built piecemeal in different variables as follows:
		1. the SELECT statement with the optional distance calculation
		2. the optional LEFT JOIN with the bookings table for logged-in "Count me in" status
		3. static clauses for FROM, WHERE, and ORDER BY
		4. a HAVING clause built using only the search criteia provided
		5. a LIMIT clause to limit the results as specified by the caller, or a
				default setting of "LIMIT 0,100"

		Also, an array of values to bind to the PDO query will be built in 
		$query_args when the option's text is added to a clause.
	*/
	$query_args = array();
	// 1: First build the select portion
	$select = "SELECT DISTINCT e.event_name, 
								e.event_id, 
								e.event_notes as event_notes_full,
								SUBSTRING_INDEX(e.event_notes, ' ', 30) as event_notes,
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
								e.event_type";

	// Add a join to the bookings table to get logged in user attendance declarations
	$bookings_join = '';
	if($userid!==NULL) {
		$select .= ', b.booking_spaces';
		$bookings_join = 'LEFT JOIN wp_em_bookings b ON (e.event_id = b.event_id) AND (b.person_id = ' . intval($userid) . ')';
	}

	// Set the static FROM, WHERE, and ORDER BY clauses
	$from = "FROM wp_em_events e " . $bookings_join . " JOIN wp_em_locations l USING (location_id) JOIN wp_em_organizations o";
	$where = "WHERE (o.id = e.event_creator) ";
	if($eventid !== NULL) {
		$where .= " AND e.event_id = $eventid";
	}
	// this doesn't work well with the current setup of recurring events -- they get top billing all the time cause they started a long time again
	//$orderby = "ORDER BY e.event_start_date ASC";
	// for now, order by end date. this is kludgey, but good for now
	$orderby = "ORDER BY e.event_end_date ASC";

	// Build HAVING clause
	// only include events that end after our start date
	$having = "";
	$having_clauses = array();

	if( $startdate !== NULL ) {
		$having_clauses[] = "e.event_end_date >= :startdate";
		$query_args['startdate'] = $startdate;
	}
	// only include events that begin before our end date
	if( $enddate !== NULL ) {
		$having_clauses[] = "e.event_start_date <= :enddate";
		$query_args['enddate'] = $enddate;
	}


	// if any having subclause was set above, create a HAVING clause here
	if ( count($having_clauses) > 0 )
	{
		$having = "HAVING (" . implode($having_clauses,") AND (") . ")";
	}

	/****************************
	 * Behold! The royal query! *
	****************************/
	$query = $select . ' ' . $from . ' ' . $where . ' ' . $having . ' ' . $orderby;


	// connect to DB and run query
	try {
		$pdo = new PDO('mysql:host='.OIP_DB_HOST.';dbname='.OIP_DB_NAME, 
						OIP_DB_USER, OIP_DB_PASSWORD);
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$statement = $pdo->prepare($query);
		$statement->execute($query_args);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e) {  
	    die('PDO MySQL error: ' . $e->getMessage());  
	} 

	// Create Array to encode into JSON output
	return $statement->fetchAll();
}

// returns NULL if event doesn't exist
function findSingleEvent($eventid,$userid=NULL) {
	$results = findEvents(NULL,NULL,$userid,$eventid);
	if(count($results)>0) {
		return $results[0];
	}
	else {
		return NULL;
	}
}
