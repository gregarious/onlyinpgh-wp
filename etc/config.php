<?php
	/* 
	 * config.php: Sets a group of global constants based on config information.
	 *
	 * Constants currently defined:
	 * OIP_DB_HOST: primary WP database host (e.g. "localhost")
	 * OIP_DB_NAME: primary WP database name
	 * OIP_DB_USER: primary WP database user name
	 * OIP_DB_PASSWORD: primary WP database password
	 *
	 * EVENTS_DB_HOST: primary events database host (e.g. "localhost")
	 * EVENTS_DB_NAME: primary events database name
	 * EVENTS_DB_USER: primary events database user name
	 * EVENTS_DB_PASSWORD: primary events database password
	 *
	 */
	$config = parse_ini_file('config.ini',TRUE);
	
	// database constants
	define("OIP_DB_HOST", $config['wp_database']['host']);
	define("OIP_DB_NAME", $config['wp_database']['db']);
	define("OIP_DB_USER", $config['wp_database']['user']);
	define("OIP_DB_PASSWORD", $config['wp_database']['password']);

	define("EVENTS_DB_HOST", $config['events_database']['host']);
	define("EVENTS_DB_NAME", $config['events_database']['db']);
	define("EVENTS_DB_USER", $config['events_database']['user']);
	define("EVENTS_DB_PASSWORD", $config['events_database']['password']);
?>