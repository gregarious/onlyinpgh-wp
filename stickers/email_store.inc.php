<?php
/*
table creation on servers:

CREATE TABLE `oip_sticker_interest` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `email_address` varchar(256) NOT NULL,
 PRIMARY KEY  (`id`)
)
*/

require( $_SERVER['DOCUMENT_ROOT'] . '/etc/config.php' );

function storeEmail($email) {
	try {
		$pdo = new PDO('mysql:host='.OIP_DB_HOST.';dbname='.OIP_DB_NAME, 
						OIP_DB_USER, OIP_DB_PASSWORD);
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$query = 'INSERT IGNORE INTO `sticker_interest` ' .
					'SET `email_address` = :email';

		$statement = $pdo->prepare();
		$statement->execute(
			array( 'email' =>	trim($email) )
		);
		$statement->setFetchMode();
	}
	catch(PDOException $e) {  
	    return FALSE;
	} 
	return TRUE;
}
?>