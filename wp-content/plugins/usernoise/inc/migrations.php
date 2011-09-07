<?php

class UN_Migrations {
	function __construct(){
		add_action('plugins_loaded', array(&$this, 'action_plugins_loaded'));
	}
	
	public function action_plugins_loaded(){
		global $wpdb;
		foreach(un_get_default_options() as $name => $value){
			if (get_option($name, null) === null){
				add_option($name, $value, false, false);
			}
		}
		$db_version = get_option('un_version');
		if ($db_version == UN_VERSION)
			return;
		if (!$db_version){
			add_option('un_version', UN_VERSION);
			$wpdb->query("UPDATE $wpdb->postmeta 
				INNER JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->postmeta.post_id
				SET meta_key = '_email' 
				WHERE meta_key = 'email' AND post_type = 'feedback'
				");
		}
		if (version_compare($db_version, '0.4') == -1){
			$wpdb->query($wpdb->prepare("UPDATE $wpdb->posts SET post_type = %s WHERE post_type = %s",
				FEEDBACK, 'feedback'));
			global $wp_roles;
			if ( ! isset( $wp_roles ) )
				$wp_roles = new WP_Roles();
			foreach(un_get_capable_roles() as $role)
				foreach(un_get_feedback_capabilities() as $cap)
							$wp_roles->add_cap($role, $cap);
		}
		update_option('un_version', UN_VERSION);
	}
}

$un_migrations = new UN_Migrations();

?>