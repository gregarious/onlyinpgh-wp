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
$start_month = $_GET["monthFrom"];
$start_day = $_GET["dayFrom"];
$start_year = $_GET["yearFrom"];
$end_month = $_GET["monthTo"];
$end_day = $_GET["dayTo"];
$end_year = $_GET["yearTo"];
$searchword = $_GET["searchword"];

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
$query = sprintf("SELECT DISTINCT e.event_name, e.event_start_time, e.event_end_time, e.event_slug, e.event_start_date, l.location_address, l.location_latitude, l.location_longitude, o.pic_square, o.name, o.type, ( 3959 * acos( cos( radians('$center_lat') ) * cos( radians( l.location_latitude ) ) * cos( radians( l.location_longitude ) - radians('$center_lng') ) + sin( radians('$center_lat') ) * sin( radians( l.location_latitude ) ) ) ) AS distance FROM wp_em_events e JOIN wp_em_locations l USING (location_id) JOIN wp_fbpage o WHERE e.event_owner = o.page_id HAVING distance < '$radius' AND e.event_start_date > '$start_year-$start_month-$start_day' AND e.event_start_date < '$end_year-$end_month-$end_day' AND o.type = '$searchword' ORDER BY e.event_start_date DESC", 
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($center_lng),
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($radius),
mysql_real_escape_string($start_year),
  mysql_real_escape_string($start_month),
  mysql_real_escape_string($start_day),
  mysql_real_escape_string($end_year),
  mysql_real_escape_string($end_month),
  mysql_real_escape_string($end_day),
  mysql_real_escape_string($searchword));
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
  $newnode->setAttribute("address", $row['location_address']);
  $newnode->setAttribute("start_date", date("$row['event_start_date']", "F j, Y"));
  $newnode->setAttribute("start_time", $row['event_start_time']);
  $newnode->setAttribute("end_time", $row['event_end_time']);
  $newnode->setAttribute("lat", $row['location_latitude']);
  $newnode->setAttribute("lng", $row['location_longitude']);
  $newnode->setAttribute("slug", $row['event_slug']);
  $newnode->setAttribute("type", $row['type']);   
  $newnode->setAttribute("organization", $row['name']);
  $newnode->setAttribute("picture", $row['pic_square']);
}
 
echo $dom->saveXML();
?>