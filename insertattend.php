<?php  
header("Content-type: application/xml");
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Get parameters from URL
$userid = $_GET["userid"];
$eventid = $_GET["eventid"];

// Start XML file, create parent node
$dom = new DOMDocument("1.0", "utf-8");
$node = $dom->createElement("success");
$parnode = $dom->appendChild($node);

// Opens a connection to a mySQL server
$connection = mysql_connect(OIP_DB_HOST, OIP_DB_USER, OIP_DB_PASSWORD);
if (!$connection) {
  die("Not connected : " . mysql_error());
}

// Set the active mySQL database
$db_selected = mysql_select_db(OIP_DB_NAME, $connection);
if (!$db_selected) {
  die ("Can\'t use db : " . mysql_error());
}

// Search the rows in the markers table
$query = sprintf("INSERT INTO wp_em_bookings (event_id, person_id, booking_spaces) VALUES ('$eventid', '$userid', '1')", 
  mysql_real_escape_string($eventid),
  mysql_real_escape_string($userid));
$result = mysql_query($query);

if (!$result) {
  die("Invalid query: " . mysql_error());
}

// Iterate through the rows, adding XML nodes for each
else {
	
// ADD TO XML DOCUMENT NODE  
  $node = $dom->createElement("success");  
  $newnode = $parnode->appendChild($node);   
  $newnode->setAttribute("message", "You are now checked in");
} 
echo $dom->saveXML();
?>