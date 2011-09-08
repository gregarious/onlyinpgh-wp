<?php
$username = OIP_DB_USER;
$password = OIP_DB_PASSWORD;
$database = OIP_DB_NAME;

system("mysqlimport -u $username --password=$password $database sqldump.sql");

?>
