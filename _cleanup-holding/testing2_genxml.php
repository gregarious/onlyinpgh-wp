<?php  
// GDN: Make this based off of etc/config.php
$opts = parse_ini_file('etc/config.ini',TRUE);

$username = $opts['database']['user'];
$password = $opts['database']['password'];
$database = $opts['database']['db'];
$host = $opts['database']['host'];

header("Content-type: application/xml");
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Get parameters from URL
$center_lat = $_GET["lat"];
$center_lng = $_GET["lng"];
$radius = $_GET["radius"];
$startdate = $_GET["startdate"];
$timespan = $_GET["timespan"];
$enddate = date("Y-m-d", strtotime("+$timespan days"));

// Start XML file, create parent node
$dom = new DOMDocument("1.0", "utf-8");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Opens a connection to a mySQL server
$connection = mysql_connect($host, $username, $password);
if (!$connection) {
  die("Not connected : " . mysql_error());
}

// Set the active mySQL database
$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ("Can\'t use db : " . mysql_error());
}

// Search the rows in the markers table
$query = sprintf("SELECT DISTINCT e.event_name, e.event_id, SUBSTRING_INDEX(e.event_notes, ' ', 30) AS event_notes, e.event_start_date, e.event_end_date, e.event_start_time, e.event_end_time, e.event_creator, e.event_slug, CONCAT_WS(',', l.location_address, l.location_town, l.location_state, l.location_postcode, l.location_country) AS event_address, l.location_latitude, l.location_longitude FROM wp_em_events e JOIN wp_em_locations l USING (location_id) HAVING e.event_start_date >= '$startdate' AND e.event_start_date <= '$enddate' ORDER BY e.event_start_date DESC", 
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($center_lng),
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($radius),
  mysql_real_escape_string($startdate),
  mysql_real_escape_string($enddate));
$result = mysql_query($query);

$result = mysql_query($query);

if (!$result) {
  die("Invalid query: " . mysql_error());
}

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
	
// ADD TO XML DOCUMENT NODE  
  $node = $dom->createElement("marker");  
  $newnode = $parnode->appendChild($node);   
  $newnode->setAttribute("ename", $row['event_name']);
  $newnode->setAttribute("eventid", $row['event_id']);
  $newnode->setAttribute("address", $row['event_address']);
  $newnode->setAttribute("start_date", $row['event_start_date']);
  $newnode->setAttribute("end_date", $row['event_end_date']);
  $newnode->setAttribute("description", $row['event_notes']);
  $newnode->setAttribute("start_time", date("g:i a", strtotime($row['event_start_time'])));
  $newnode->setAttribute("end_time", date("g:i a", strtotime($row['event_end_time'])));
  $newnode->setAttribute("lat", $row['location_latitude']);
  $newnode->setAttribute("lng", $row['location_longitude']);
  $newnode->setAttribute("slug", $row['event_slug']);
  $newnode->setAttribute("creator", $row['event_creator']);

} 
echo $dom->saveXML();
?>