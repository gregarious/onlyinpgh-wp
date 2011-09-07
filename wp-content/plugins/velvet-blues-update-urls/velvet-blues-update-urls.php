<?php
/*
Plugin Name: Velvet Blues Update URLs
Plugin URI: http://www.velvetblues.com/web-development-blog/wordpress-plugin-update-urls/
Description: This plugin updates all urls in your website by replacing old urls with new urls.
Author: VelvetBlues.com
Author URI: http://www.velvetblues.com/
Version: 2.0.1
*/
/*  Copyright 2011  Velvet Blues Web Design  (email : info@velvetblues.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	
/* Functions for the options page */	
	function VelvetBluesUU_add_options_page(){
		add_options_page("Update URLs Setings", "Update Urls", "manage_options", basename(__FILE__), "VelvetBluesUU_options_page");
	}
	function VelvetBluesUU_options_page(){
		/* Function which updates urls */
		function VB_update_urls($links,$oldurl,$newurl){	
			global $wpdb;
			//permalinks query
			$permquery = "UPDATE $wpdb->posts SET guid = replace(guid, '".$oldurl."','".$newurl."')";
			$result = $wpdb->query( $permquery );
			if($links == 1){
				//content query
				$contquery = "UPDATE $wpdb->posts SET post_content = replace(post_content, '".$oldurl."','".$newurl."')";
				$result = $wpdb->query( $contquery );
				$excquery = "UPDATE $wpdb->posts SET post_excerpt = replace(post_excerpt, '".$oldurl."','".$newurl."')";
				$result = $wpdb->query( $excquery );
			}
		}
		if( isset( $_POST['VBUU_settings_submit'] ) ){
			$vbuu_update_links = attribute_escape($_POST['VBUU_update_links']);
			$vbuu_oldurl = attribute_escape($_POST['VBUU_oldurl']);
			$vbuu_newurl = attribute_escape($_POST['VBUU_newurl']);
			VB_update_urls($vbuu_update_links,$vbuu_oldurl,$vbuu_newurl);
			echo '<div id="message" class="updated fade"><p><strong>URLs have been updated.</p></strong><p>You can now uninstall this plugin.</p></div>';
		}
?>
<div class="wrap">
<h2>Update URLs Settings</h2>
<form method="post" action="options-general.php?page=<?php echo basename(__FILE__); ?>">
<input type="hidden" id="_wpnonce" name="_wpnonce" value="abcab64052" />
<p>These settings let you update your permalinks AND any old urls embedded in content or excerpts.<br/>It will replace all occurences of the old url with the new url.</p>
<table class="form-table">
<tr>
<th scope="row" style="width:150px;"><b>Update permalinks<br/>AS WELL AS<br/>links in site content?</b></th><td>
	<p style="margin:0;padding:0;"><input name="VBUU_update_links" type="radio" id="VBUU_update_true" value="1" checked="checked" /> <label for="VBUU_update_true">Yes</label><br/>
	<input name="VBUU_update_links" type="radio" id="VBUU_update_false" value="0" /> <label for="VBUU_update_false">No, I only want to update page and image urls,<br/>and not any links to pages or images that may be<br/>embedded in content.</label></p>&nbsp;<br/>&nbsp;
</td>
</tr>
<tr>
<th scope="row" style="width:150px;"><b>Old URL</b></th>
<td>
	<input name="VBUU_oldurl" type="text" id="VBUU_oldurl" value="" style="width:300px;" />
</td>
</tr>
<tr>
<th scope="row" style="width:150px;"><b>New URL</b></th>
<td>
	<input name="VBUU_newurl" type="text" id="VBUU_newurl" value="" style="width:300px;" />
</td>
</tr>
</table>
<p class="submit">
<input name="VBUU_settings_submit" value="Update URLs" type="submit" />
</p>
</form>
<p>&nbsp;<br/>Need help? Get support at the <a href="http://www.velvetblues.com/web-development-blog/wordpress-plugin-update-urls/" target="_blank">Velvet Blues Update URLs plugin page</a>.</p>
<?php
} 
add_action('admin_menu', 'VelvetBluesUU_add_options_page'); 
?>