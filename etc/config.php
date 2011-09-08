<?php
	/* 
	 * config.php: Sets a group of global constants based on config information.
	 *
	 * Constants currently defined:
	 * OIP_DB_HOST: primary database host (e.g. "localhost")
	 * OIP_DB_NAME: primary database name
	 * OIP_DB_USER: primary database user name
	 * OIP_DB_PASSWORD: primary database password
	 *
	 */
	$config = parse_ini_file('config.ini',TRUE);
	
	// database constants
	define("OIP_DB_HOST", $config['database']['host']);
	define("OIP_DB_NAME", $config['database']['db']);
	define("OIP_DB_USER", $config['database']['user']);
	define("OIP_DB_PASSWORD", $config['database']['password']);
?>