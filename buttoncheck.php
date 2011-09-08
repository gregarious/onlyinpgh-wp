<?php  

header("Content-type: application/xml");
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Get parameters from URL
$userid = $_GET["userid"];
$eventid = $_GET["eventid"];

// Start XML file, create parent node
$dom = new DOMDocument("1.0", "utf-8");
$node = $dom->createElement("markers");
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
$query = sprintf("SELECT DISTINCT person_id, event_id FROM wp_em_bookings WHERE person_id = '$userid' AND event_id = '$eventid'", 
  mysql_real_escape_string($userid),
  mysql_real_escape_string($eventid));
$result = mysql_query($query);

if (!$result) {
  die("Invalid query: " . mysql_error());
}

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
// ADD TO XML DOCUMENT NODE  


  
  $node = $dom->createElement("attendance");  
  $newnode = $parnode->appendChild($node);   
  $newnode->setAttribute("event_id", $row['event_id']);
  $newnode->setAttribute("person", $row['person_id']);
}
 
echo $dom->saveXML();
?>