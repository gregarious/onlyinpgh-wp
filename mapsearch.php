<?php  
/**
 	mapsearch.php: Script called by map.js to search events. The script
 	outputs JSON data as described below.

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
require_once 'include/eventsearcher.class.php';

// if key is in the array, returns the correspondingvalue
// if it doesn't exist, returns NULL without a warning
function safeLookup($key,$ar) {
	return array_key_exists($key,$ar) ? $ar[$key] : NULL;
}

$searcher = new EventSearcher();

$searcher->queryLocation();
$searcher->queryOrganization();
if(array_key_exists('userid',$_GET)){
	$searcher->queryAttendance($_GET['userid']);
}

if( array_key_exists('lat',$_GET) &&
	array_key_exists('long',$_GET) &&
	array_key_exists('radius',$_GET) ) {
		$searcher->filterByDistance($_GET['lat'],$_GET['long'],$_GET['radius']);
}

if(array_key_exists('startdate', $_GET)) {
	$searcher->filterByStartDate($_GET['startdate']);
}

if(array_key_exists('enddate', $_GET)) {
	$searcher->filterByStartDate($_GET['enddate']);
}

if(array_key_exists('search_terms', $_GET)) {
	$terms = str_getcsv($_GET['search_terms'],' ');
	$searcher->filterByKeywords($terms);
}
if(array_key_exists('onlyattending', $_GET)) {
	if($_GET['onlyattending']) {
		$searcher->filterByAttendance();
	}
}

$offset = 0;
$limit = 20;
if(array_key_exists('offset',$_GET)) {
	$offset = intval($_GET['offset']);
}
if(array_key_exists('limit',$_GET)) {
	$limit = intval($_GET['limit']);
}

$results = $searcher->runQuery($offset,$limit);

$json_events = array();
foreach($results as $result) {
	$json_events[] = 
		array(	'id'		=> safeLookup('id',$result),
				'name'		=> safeLookup('name',$result),
				'wp_slug'		=> safeLookup('wp_slug',$result),
				'description'   => safeLookup('description',$result),
				'categories'	=> safeLookup('categories',$result),
				'image_url'		=> safeLookup('image_url',$result),
				'attending'		=> safeLookup('attending',$result),
				'location'		=> array(
					'address'		=> safeLookup('address',$result),
					'lat'			=> safeLookup('lat',$result),
					'long'			=> safeLookup('long',$result) ),
				'organization' => array(
					'name'			=> safeLookup('org_name',$result),
					'url'			=> safeLookup('org_url',$result),
					'fancount'		=> safeLookup('org_fancount',$result) ),
				'timespan'	=> array(
					'start_date' 	=> ($result['start_dt']) ? $result['start_dt']->format('Y-m-d') : NULL,
					'start_time' 	=> ($result['start_dt']) ? $result['start_dt']->format('H:i') : NULL,
					'end_date' 		=> ($result['end_dt']) ? $result['end_dt']->format('Y-m-d') : NULL,
					'end_time' 		=> ($result['end_dt']) ? $result['end_dt']->format('H:i') : NULL )
			);
}

$output_json = array( 'more_results' => $searcher->moreResultsAvailable(),
						'events' => $json_events );

// print JSON
header("Content-type: application/json");
print json_encode($output_json);