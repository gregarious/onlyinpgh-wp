<?php  
header("Content-type: application/xml");
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Get parameters from URL
$center_lat = $_GET["lat"];
$center_lng = $_GET["lng"];
$radius = $_GET["radius"];
$startdate = $_GET["startdate"];
$timespan = $_GET["timespan"];
$limitvalue = $_GET["limitvalue"];
$enddate = date("Y-m-d", strtotime("+$timespan days"));

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
$query = sprintf("SELECT DISTINCT e.event_name, e.event_id, SUBSTRING_INDEX(e.event_notes, ' ', 30) AS event_notes, e.event_start_date, e.event_end_date, e.event_start_time, e.event_end_time, e.event_slug, l.location_address, l.location_latitude, l.location_longitude, o.name AS organization_name, e.event_pic AS organization_pic_url, o.link_url AS organization_link_url, o.fan_count AS organization_fan_count, e.event_type, ( 3959 * acos( cos( radians('$center_lat') ) * cos( radians( l.location_latitude ) ) * cos( radians( l.location_longitude ) - radians('$center_lng') ) + sin( radians('$center_lat') ) * sin( radians( l.location_latitude ) ) ) ) AS distance FROM wp_em_events e JOIN wp_em_locations l USING (location_id) JOIN wp_em_organizations o WHERE (o.id = e.event_creator) HAVING distance < '$radius' AND e.event_start_date >= '$startdate' AND e.event_start_date <= '$enddate' ORDER BY e.event_start_date ASC LIMIT $limitvalue,100", 
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($center_lng),
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($radius),
  mysql_real_escape_string($startdate),
  mysql_real_escape_string($enddate),
  mysql_real_escape_string($limitvalue));
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
  $newnode->setAttribute("ename", htmlentities ($row['event_name'],ENT_QUOTES,"UTF-8"));
  $newnode->setAttribute("eventid", htmlentities ($row['event_id']));
  $newnode->setAttribute("address", htmlentities ($row['location_address']));
  $newnode->setAttribute("start_date", date("F j, Y", strtotime($row['event_start_date'])));
  $newnode->setAttribute("end_date", htmlentities ($row['event_end_date']));
  $newnode->setAttribute("description", htmlentities ($row['event_notes'],ENT_QUOTES,"UTF-8"));
  $newnode->setAttribute("start_time", date("g:i a", strtotime($row['event_start_time'])));
  $newnode->setAttribute("end_time", date("g:i a", strtotime($row['event_end_time'])));
  $newnode->setAttribute("lat", htmlentities ($row['location_latitude']));
  $newnode->setAttribute("lng", htmlentities ($row['location_longitude']));
  $newnode->setAttribute("slug", $row['event_slug']);
  $newnode->setAttribute("type", htmlentities ($row['event_type']));   
  $newnode->setAttribute("organization", htmlentities ($row['organization_name']));
  $newnode->setAttribute("picture", $row['organization_pic_url']);
  $newnode->setAttribute("org_url", $row['organization_link_url']);
  $newnode->setAttribute("org_fancount", htmlentities ($row['organization_fan_count']));

} 
echo $dom->saveXML();
?>