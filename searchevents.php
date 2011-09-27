<?php  
/**
 	searchevents.php: Script to query the events table in the WP database for
		events based on search criteria fed in via a GET request. The script 
		outputs a set of XML data with the schema detailed below.

	Input GET arguments (all are optional):
	- 'lat' 		: latitude of center point of search
	- 'long' 		: longitude of center point
	- 'rad' 		: radius of center point
	- 'startdate' 	: minimum start date of events (in MySQL DATE-friendly 
						format (e.g. YYYY-MM-DD))
	- 'enddate'   	: max end date of events (in MySQL DATE-friendly format)
	- 'search_terms': string of space-separated keyword terms
	- 'result_offset' : result index to start with (zero-based)
	- 'result_limit'  : number of results to return
	- 'userid' 		: id of user making the request (used to determine
						value of attending in results)
	- 'onlyattending' : if nonzero, only return results the given user is attending

	Output JSON has the following type
		{	more_results 	: boolean,
			events : [{
				id 			: number,
			 	name 		: string,
				wp_slug  	: string,
				description : string,
				categories 	: [string],
				image_url 	: string,
				timespan 	: {
					start_date  : string,
					start_time  : string,
					end_date	: string,		
					end_time	: string }
				location 	: {
					address  	: string,
					lat 	 	: number,
					long 	 	: number }
				organization : {
					name 	 	: string,
					url 	 	: string,
					fancount 	: number }
				attending 	: boolean
				}]
		}
**/

require_once 'etc/config.php';

// if key is in the $_GET array, returns the correspondingvalue
// if it doesn't exist, returns NULL without a warning
function extract_get($key) {
  return array_key_exists($key,$_GET) ? $_GET[$key] : NULL;
}


// Get parameters from GET URL
$lat = extract_get("lat");      // search $rad center latitude (if omitted, resulting XML will not include distance)
$long = extract_get("long");      // search radius center longitude (if omitted, resulting XML will not include distance)
$rad = extract_get("radius");   // search radius distance
$startdate = extract_get("startdate");  // in MySQL DATE-friendly format (e.g. 'YYYY-MM-DD')
$enddate = extract_get("enddate");      // in MySQL DATE-friendly format (e.g. 'YYYY-MM-DD')
$terms_str = extract_get("search_terms");  // searches organization name, event name, event type, or event notes
$result_offset = extract_get("offset");  // result offset to use in query (offset is 0-based)
$result_limit = extract_get("limit");    // result limit (will return rows number <offset> to <offset+limit>)
$userid = extract_get("userid");	// currently logged in user
$only_attending = extract_get("onlyattending");
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

// if lat/long were specified, caller wants distance information in the XML
$is_query_loc_based = ($lat !== NULL && $long !== NULL);
if( $is_query_loc_based )
{
	// add distance calculation to query
	$select .= ", ( 3959 * acos( cos( radians(:lat) ) * cos( radians( l.location_latitude ) ) * cos( radians( l.location_longitude ) - radians(:long) ) + sin( radians(:lat) ) * sin( radians( l.location_latitude ) ) ) ) AS distance";
	$query_args['lat'] = $lat;
	$query_args['long'] = $long;

}

// 2: Add a join to the bookings table to get logged in user attendance declarations
$bookings_join = '';
if($userid!==NULL) {
	$select .= ', b.booking_spaces';
	if($only_attending) {
		$bookings_join = 'JOIN wp_em_bookings b ON (e.event_id = b.event_id) AND (b.person_id = ' . intval($userid) . ') AND (b.booking_spaces = 1)';
	} else {
		$bookings_join = 'LEFT JOIN wp_em_bookings b ON (e.event_id = b.event_id) AND (b.person_id = ' . intval($userid) . ')';
	}
}

// 2: Set the static FROM, WHERE, and ORDER BY clauses
$from = "FROM wp_em_events e " . $bookings_join . " JOIN wp_em_locations l USING (location_id) JOIN wp_em_organizations o";
$where = "WHERE (o.id = e.event_creator) ";
// this doesn't work well with the current setup of recurring events -- they get top billing all the time cause they started a long time again
//$orderby = "ORDER BY e.event_start_date ASC";
// for now, order by end date. this is kludgey, but good for now
$orderby = "ORDER BY e.event_end_date ASC";

// 3: Build HAVING clause
$having = "";
$having_clauses = array();
// constrain search to radius
if( $rad !== NULL && $is_query_loc_based ) {
	$having_clauses[] = "distance < :rad";
	$query_args['rad'] = $rad;
}
// only include events that end after our start date
if( $startdate !== NULL ) {
	$having_clauses[] = "e.event_end_date >= :startdate";
	$query_args['startdate'] = $startdate;
}
// only include events that begin before our end date
if( $enddate !== NULL ) {
	$having_clauses[] = "e.event_start_date <= :enddate";
	$query_args['enddate'] = $enddate;
}
// only include events whose event/organization fields include the keyword
if( $terms_str !== NULL ) {
	// split the search term string into indivudal terms
	$terms = str_getcsv($terms_str,' ');	// doesn't split at spaces in quotes
	$term_clauses = array();
	$i = 0;
	foreach ($terms as $term) {
		$term_clauses[] = "organization_name rLIKE :keyword$i OR 
							e.event_name rLIKE :keyword$i OR 
							e.event_type rLIKE :keyword$i OR 
							event_notes_full rLIKE :keyword$i";
		$query_args["keyword$i"] = $term;
		$i++;
	}
	$having_clauses[] = implode(' OR ', $term_clauses);
}
// if any having subclause was set above, create a HAVING clause here
if ( count($having_clauses) > 0 )
{
	$having = "HAVING (" . implode($having_clauses,") AND (") . ")";
}

// 4: Build LIMIT clause
$result_offset = $result_offset !== NULL ? intval($result_offset) : 0;
$result_limit = $result_limit !== NULL ? intval($result_limit) : 20;

// Note: PDO binding via an assoc. array won't work for us here because these 
// values must be numerical, not strings. Just do it manually unless there's
// a compelling reason not to later.
// Also, we search for requested number + 1. We don't actually return the extra result, we just use it to see if there are additional results available.
$limit = "LIMIT " . intval($result_offset) . ',' . intval($result_limit+1);

/****************************
 * Behold! The royal query! *
****************************/
$query = $select . ' ' . $from . ' ' . $where . ' ' . $having . ' ' . $orderby . ' ' . $limit;


// connect to DB and run query
try {
	$pdo = new PDO('mysql:host='.OIP_DB_HOST.';dbname='.OIP_DB_NAME, 
					OIP_DB_USER, OIP_DB_PASSWORD);
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$statement = $pdo->prepare($query);
	$statement->execute($query_args);
}
catch(PDOException $e) {  
    die('PDO MySQL error: ' . $e->getMessage());  
} 

// Create Array to encode into JSON output
$all_events = array();
$counter = 0;
while($row = $statement->fetch()) {
	$counter++;
	if($counter > $result_limit) break;
	// GDN: this isn't the cleanest way to do this. Other better ways might exist (e.g. PDO 
	// 	FETCH_CLASS with a constructor), but we'll worry about that after the site overhaul

	// Fill in the NOT NULL entries currently include event_id, event_name, event_slug, event_start_date, 
	//		event_start_time, event_end_time, address, organization
	$all_events[] = 
		array(	'id'			=> intval($row['event_id']),
				'name'			=> htmlentities($row['event_name'],ENT_QUOTES,'ISO-8859-1',FALSE),
				'wp_slug'		=> $row['event_slug'],
				'description'   => ($row['event_notes'] !== NULL) ? 
										htmlentities($row['event_notes'],ENT_QUOTES,'ISO-8859-1',FALSE) : NULL,
				'categories'	=> ($row['event_type'] !== NULL) ?
										explode(',',htmlentities($row['event_type'],ENT_QUOTES,'ISO-8859-1',FALSE)) : NULL,
				'image_url'		=> $row['event_pic'],
				'attending'		=> array_key_exists('booking_spaces',$row) ? $row['booking_spaces']==TRUE : FALSE,
				'timespan'		=> array(
					'start_date'	=> date("F j, Y", strtotime($row['event_start_date'])),
					'start_time'	=> date("g:i a", strtotime($row['event_start_time'])),
					'end_date'		=> ($row['event_end_date'] !== NULL) ?
											date("g:i a", strtotime($row['event_end_time'])) : NULL,
					'end_time'		=> date("g:i a", strtotime($row['event_end_time'])), 
					),
				'location'		=> array(
					'address'		=> htmlentities($row['location_address'],ENT_QUOTES,'ISO-8859-1',FALSE),
					'lat'			=> ($row['location_latitude'] !== NULL) ?
										floatval($row['location_latitude']) : NULL,
					'long'			=> ($row['location_longitude'] !== NULL) ?
										floatval($row['location_longitude']) : NULL,
					),
				'organization'		=> array(
					'name'		=> htmlentities ($row['organization_name'],ENT_QUOTES,'ISO-8859-1',FALSE),
					'url'		=> $row['organization_link_url'],
					'fancount' 	=> ($row['organization_fan_count'] !== NULL) ?
									intval($row['organization_fan_count']) : NULL
					)
			);
}

$output_json = array( 'more_results' => ($counter>$result_limit),
						'events' => $all_events );

// print JSON
header("Content-type: application/json");
print json_encode($output_json);