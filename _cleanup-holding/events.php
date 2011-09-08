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
$searchword = $_GET["searchword"];
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
$query = sprintf("SELECT * FROM wp_em_events WHERE location_id > 1", 
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
  $newnode->setAttribute("location", $row['location_id']);

} 
echo $dom->saveXML();
?>