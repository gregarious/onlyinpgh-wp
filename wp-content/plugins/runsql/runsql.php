<?php
/*
Plugin Name: RunSQL
Plugin URI: http://hawkenterprises.org
Description: Although highly dangerous I findy it handy to be able to execute directly to the database, 
error handling and security installed.
Version: 2.3.0
Author: GRX3
Author URI: http://grx3.com
*/
if(is_admin()){
	function runsql_form(){
		global $wpdb;
		if(isset($_POST['sql'])){
			ob_start();
			echo '<h3> Query Results </h3> ';
			$wpdb->show_errors();
			echo $_POST['sql'];
			$wpdb->query(mysql_escape_string($_POST['sql']));
			$runsql_c = ob_get_contents();
			ob_end_clean();
		}
	?>
	<div style="text-align:left">
	HawkEnterprises' RunSQL<br>
	<?php echo $runsql_c;?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo plugin_basename(__FILE__); ?>" method="post">
	<textarea name="sql" style="width:500px;height:400px;">
	</textarea>
	<input type="submit" value="Execute">
	</form>
	<div style="float:left;">
		<h3>Useful Queries</h3>
		<ul>
			<li><b>Date Randomizer</b>UPDATE  `wp_posts` SET post_date = DATE_ADD( post_date, INTERVAL - RAND( ) *300 HOUR ) ,
post_date_gmt = DATE_ADD( post_date_gmt, INTERVAL - RAND( ) *300 HOUR )</li>
			<li><b>- by category id</b>UPDATE  `wp_posts`,wp_term_relationships r SET post_date = DATE_ADD( post_date, INTERVAL - RAND( ) *300 HOUR ) ,
post_date_gmt = DATE_ADD( post_date_gmt, INTERVAL - RAND( ) *300 HOUR ) WHERE r.object_id = ID AND r.term_taxonomy_id = 23</li>
			<li> -- Date Units : MINUTE, HOUR, DAY, MONTH, YEAR</li>
		</ul>
	</div>
	</div>
	<?php
	}
}
function runsql_admin(){
	add_options_page('RunSQL','RunSQL','manage_options',__FILE__,'runsql_form');
}
add_action('admin_menu','runsql_admin');
?>