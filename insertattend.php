<?php  

require_once('etc/config.php');
// Get parameters from URL
$userid = $_GET["userid"];
$eventid = $_GET["eventid"];


// Opens a connection to a mySQL server
$connection = mysql_connect(EVENTS_DB_HOST, EVENTS_DB_USER, EVENTS_DB_PASSWORD);
if (!$connection) {
  die("Not connected : " . mysql_error());
}

// Set the active mySQL database
$db_selected = mysql_select_db(EVENTS_DB_NAME, $connection);
if (!$db_selected) {
  die ("Can\'t use db : " . mysql_error());
}

// Search the rows in the markers table
$query = sprintf("INSERT INTO events_attendee (event_id, individual) VALUES ('%s', '%s')", 
  mysql_real_escape_string($eventid),
  mysql_real_escape_string($userid));
$result = mysql_query($query);

if (!$result) {
  die("Invalid query: " . mysql_error());
}

/* also insert attendance info into the old DB for safe keeping. ignore any errors. */
$connection = mysql_connect(OIP_DB_HOST, OIP_DB_USER, OIP_DB_PASSWORD);
if ($connection) {
	$db_selected = mysql_select_db(OIP_DB_NAME, $connection);
	if ($db_selected) {
  		$query = sprintf("INSERT INTO wp_em_bookings (event_id, person_id, booking_spaces) VALUES ('%s','%s',1)",
  			mysql_real_escape_string($eventid),
  			mysql_real_escape_string($userid));
  		$result = mysql_query($query);
	}
}

$output_json= array('status' => 'success');

header("Content-type: application/json");
print json_encode($output_json);