<?PHP

$db_handle = mysql_connect(OIP_DB_HOST, OIP_DB_USER, OIP_DB_PASSWORD);
$db_found = mysql_select_db(OIP_DB_NAME, $db_handle);

if ($db_found) {

    $SQL = "INSERT INTO wp_em_bookings (event_id, person_id, booking_spaces) VALUES ('$_POST["eventid"]', '$_COOKIE[$username]', '1')";

$result = mysql_query($SQL);

mysql_close($db_handle);

print "Now attending";
}
else {
print "Database NOT Found ";
mysql_close($db_handle);
}

?>