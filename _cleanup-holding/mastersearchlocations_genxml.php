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
$query = sprintf("SELECT DISTINCT o.pic_url, o.name, o.fan_count, l.location_latitude, l.location_longitude, o.type, l.location_address, ( 3959 * acos( cos( radians('$center_lat') ) * cos( radians( l.location_latitude ) ) * cos( radians( l.location_longitude ) - radians('$center_lng') ) + sin( radians('$center_lat') ) * sin( radians( l.location_latitude ) ) ) ) AS distance FROM wp_em_organizations o JOIN wp_em_locations l USING (location_id) HAVING distance < '$radius'", 
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($center_lng),
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($radius));
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
 $newnode->setAttribute("type", $row['type']);   
$newnode->setAttribute("organization", $row['name']);
$newnode->setAttribute("picture", $row['pic_url']);
$newnode->setAttribute("lat", $row['location_latitude']);
$newnode->setAttribute("lng", $row['location_longitude']);
$newnode->setAttribute("address", $row['location_address']);
$newnode->setAttribute("fans", $row['fan_count']);

} 
echo $dom->saveXML();
?>