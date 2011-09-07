DELIMITER $$ 
DROP PROCEDURE IF EXISTS OIP_mv_feeds $$ 
CREATE PROCEDURE OIP_mv_feeds() 
	READS SQL DATA
	DETERMINISTIC
BEGIN
	START TRANSACTION;
	INSERT INTO wp_feeds (SELECT * from wp_posts WHERE ID IN (SELECT m.post_id FROM wp_postmeta m WHERE m.meta_key rLIKE 'syndication' GROUP BY m.post_id)); 
	DELETE FROM wp_posts WHERE ID IN (SELECT m.post_id FROM wp_postmeta m WHERE m.meta_key rLIKE 'syndication' GROUP BY m.post_id); 
	DELETE FROM wp_postmeta WHERE meta_key rLIKE 'syndication'; 
	COMMIT; 
END; $$
DELIMITER ; 
