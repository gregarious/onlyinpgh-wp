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

		$query = 'SELECT * FROM `oip_sticker_interest` ' .
					'WHERE `email_address` = :email';
		$statement = $pdo->prepare($query);
		$statement->execute(
			array( 'email' =>	trim($email) )
		);

		if($statement->rowCount() < 1) {
			$query = 'INSERT INTO `oip_sticker_interest` ' .
						'SET `email_address` = :email';

			$statement = $pdo->prepare($query);
			$statement->execute(
				array( 'email' =>	trim($email) )
			);
		}
	}
	catch(PDOException $e) {  
		die($e);
	    return FALSE;
	} 
	return TRUE;
}
?>