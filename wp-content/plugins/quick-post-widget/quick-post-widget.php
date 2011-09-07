<?php
/*
Plugin Name: Quick Post Widget
Plugin URI: http://www.famvanakkeren.nl/downloads/quick-post-widget/
Description: This plugin provides a widget to post directly from the frontpanel of your site without going into the backend.
Author: Perry van Akkeren
Version: 1.9
Author URI: http://www.famvanakkeren.nl/
*/

/*
Copyright 2011  Perry van Akkeren  (email : vanakkeren@live.nl)

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

function qpw_reg_widget_area() {
	if ( function_exists('register_sidebar') )
		register_sidebar( array(
			'name' => __('Quick Post Page', 'quick-post-widget'),
			'id' => 'quick-post-page',
			'description' => __('This dummy widget area can hold the Quick Post Widget to display on a page. Drag the widget here and configure it. Use the shortcode [quick-post-page] to call the widget area in a page. You can also drag some other widgets here.', 'quick-post-widget'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<!-- quick_post_page -->',
			'after_title' => '<br/>', 
	));
}

function quick_post_page() {
	qpw_reg_widget_area();
	if (function_exists('dynamic_sidebar')) {
		dynamic_sidebar('Quick Post Page');
	}
}

function qpw_init () {
	global $qpw_plugin_url;
	global $qpw_custom_fields_count;
	global $qpw_locale;
	$qpw_plugin_url = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
	$qpw_locale = ( '' == get_locale() ) ? 'en' : strtolower( substr(get_locale(), 0, 2) );
	if (!in_array($qpw_locale, array('de','en','es','fi','fr','nl','pt','it','pl'))) $qpw_locale='en';
	$qpw_custom_fields_count = 10;
	wp_enqueue_style('quick-post-style', $qpw_plugin_url . 'css/quick-post-widget.css');
	if ( 'rtl' == get_bloginfo( 'text_direction' ) ) {
		wp_enqueue_style('quick-post-style-rtl', $qpw_plugin_url . 'css/quick-post-widget-rtl.css');
	}
	if (!is_admin()) {
		wp_enqueue_style('jquery-ui-style', $qpw_plugin_url . 'css/jquery-ui.css');
		wp_enqueue_script('tinymce', get_bloginfo('wpurl') . '/wp-includes/js/tinymce/tiny_mce.js');
		wp_enqueue_script('tinybrowser', $qpw_plugin_url . 'mce/tinybrowser/tb_tinymce.js.php');
		wp_enqueue_script('datepicker', $qpw_plugin_url . 'js/ui.datepicker.min.js', array('jquery'));
		wp_enqueue_script('datepicker-' . $qpw_locale, $qpw_plugin_url . 'js/ui.datepicker-' . $qpw_locale . '.js', array('datepicker'));
		wp_enqueue_script('slider', $qpw_plugin_url . 'js/ui.slider.js', array('jquery','jquery-ui-dialog'));
		wp_enqueue_script('timepicker', $qpw_plugin_url . 'js/jquery-ui-timepicker-addon.js', array('slider'));
		wp_enqueue_script('quick-post-script', $qpw_plugin_url . 'js/qpw.js', array('jquery','jquery-ui-dialog'));
		wp_enqueue_script('qpw_locale_' . $qpw_locale, $qpw_plugin_url . 'mce/langs/' . $qpw_locale . '.js');
		add_shortcode('quick-post-page', 'quick_post_page');
		if(!isset($_SESSION)) { @session_start(); }
		$_SESSION['quick-post-widget']=true;
		$_SESSION['qpw_locale'] = $qpw_locale;
	} else {
		wp_enqueue_script('quick-post-script', $qpw_plugin_url . 'js/qpw_admin.js');
		add_action('admin_init', 'qpw_reg_widget_area');
	}
}

function qpw_upload_dir () {
	$siteurl = get_option( 'siteurl' );
	$upload_path = get_option( 'upload_path' );
	$upload_path = trim($upload_path);
	if ( empty($upload_path) ) {
		$dir = WP_CONTENT_DIR . '/uploads';
	} else {
		$dir = $upload_path;
		if ( 'wp-content/uploads' == $upload_path ) {
			$dir = WP_CONTENT_DIR . '/uploads';
		} elseif ( 0 !== strpos($dir, ABSPATH) ) {
			$dir = path_join( ABSPATH, $dir );
		}
	}
	if ( defined('UPLOADS') ) {
		$dir = ABSPATH . UPLOADS;
	}
	return str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', $dir));
}

function qpw_sendmail ($post_id, $post_title) {
	$blogname = get_option('blogname');
	$email = get_option('admin_email');
	$headers = "MIME-Version: 1.0\r\n" . "From: ".$blogname." "."<".$email.">\n" . "Content-Type: text/HTML; charset=\"" . get_settings('blog_charset') . "\"\r\n";
	$content = '<p>'.__('A new quick post has been made on', 'quick-post-widget').' '.$blogname.'.'.'<br/>' .__('To view the post click here:', 'quick-post-widget') . ' '.'<a href="'.get_permalink($post_id).'"><strong>'.$post_title.'</strong></a></p>';
	wp_mail($email, __('New quick post on', 'quick-post-widget') . ' ' . $blogname . ': ' . $post_title, $content, $headers);
}

function qpw_category_checklist ($selected_cats = false, $inexclude = 'include', $inexclude_cats = false, $orderby = 'name', $taxonomy = 'category' ) { 
    $walker = new Walker_Category_Checklist; 
    $args = array(); 
 	if ( is_array( $selected_cats ) ) 
		$args['selected_cats'] = $selected_cats; 
	else 
		$args['selected_cats'] = array(); 
	$args['popular_cats'] = get_terms($taxonomy, array( 'fields' => 'ids', 'orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hierarchical' => false ) );
	$args['taxonomy'] = $taxonomy;
	$categories = get_categories('taxonomy=' . $taxonomy .'&get=all&'. $inexclude . '='. $inexclude_cats . '&orderby='. $orderby); 
	echo call_user_func_array(array(&$walker, 'walk'), array($categories, 0, $args)); 
} 

function qpw_count_custom_fields () {
	global $wpdb;
	$cf = $wpdb->get_var("
		SELECT count(distinct meta_key)
		FROM $wpdb->postmeta
		WHERE meta_key NOT LIKE '\_%'" );
		return $cf;
}

function qpw_custom_fields_dropdown($customfield_nr, $customfield_id, $customfield_name, $customfield_value) {
	global $wpdb;
	$keys = $wpdb->get_col( "
		SELECT meta_key
		FROM $wpdb->postmeta
		GROUP BY meta_key
		HAVING meta_key NOT LIKE '\_%'
		ORDER BY meta_key" );
	if ( $keys )
		natcasesort($keys);
	if ( $keys ) { ?>
		<select style="width: 95%" id="<?php echo $customfield_id; ?>" name="<?php echo $customfield_name; ?>" title="<?php _e('Name of custom field', 'quick-post-widget'); echo ' ' . $customfield_nr; ?>" >
			<option value="#NONE#"><?php _e( '- Select -' ); ?></option>
			<?php
			foreach ( $keys as $key ) {
				echo "\n<option value='" . esc_attr($key) . "'" . (($key == $customfield_value) ? 'selected="selected"' : '') . ">" . esc_html($key) . "</option>";
			}
			?>
		</select>
	<?php
	}
}

function qpw_isValidDateTime($dateTime) {
	if (trim($dateTime) == '') {
		return true;
	}
	if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
		if (checkdate($matches[2], $matches[3], $matches[1])) {
			return true;
		}
	}
	return false;
}

function qpw_curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

class Quick_Post_Widget extends WP_Widget
{

	function Quick_Post_Widget()
	{
		$widget_ops = array('description' => __('The Quick Post Widget provides a quick way to post directly from your site.', 'quick-post-widget'));
		$control_ops = array('width' => '280');
		$this->WP_Widget('quick_post_widget', 'Quick Post Widget', $widget_ops, $control_ops);
	}

	function form($instance)
	{
		global $qpw_plugin_url;
		global $qpw_custom_fields_count;

		$title = esc_attr($instance['title']);
		$post_title_label = esc_attr($instance['post_title_label']);
		$post_content_label = esc_attr($instance['post_content_label']);
		$post_tags_label = esc_attr($instance['post_tags_label']);
		$post_date_label = esc_attr($instance['post_date_label']);
		$category_label = esc_attr($instance['category_label']);
		$new_cat_label = esc_attr($instance['new_cat_label']);
		$submit_label = esc_attr($instance['submit_label']);
		$editor_title = esc_attr($instance['editor_title']);
		$message_title = esc_attr($instance['message_title']);
		$post_excerpt_label = esc_attr($instance['post_excerpt_label']);
		$post_title_default = esc_attr($instance['post_title_default']);
		$post_content_default = esc_attr($instance['post_content_default']);
		$post_cat_parent_default = esc_attr($instance['post_cat_parent_default']);
		$post_tag_list_default = esc_attr($instance['post_tag_list_default']);
		$widget_style = esc_attr($instance['widget_style']);
		$post_title_style = esc_attr($instance['post_title_style']);
		$post_content_style = esc_attr($instance['post_content_style']);
		$post_tags_style = esc_attr($instance['post_tags_style']);
		$post_date_style = esc_attr($instance['post_date_style']);
		$cat_checklist_style = esc_attr($instance['cat_checklist_style']);
		$label_style = esc_attr($instance['label_style']);
		$button_style = esc_attr($instance['button_style']);
		$rb_style = esc_attr($instance['rb_style']);
		$new_cat_style = esc_attr($instance['new_cat_style']);
		$taxonomy_box_style = esc_attr($instance['taxonomy_box_style']);
		$post_excerpt_style = esc_attr($instance['post_excerpt_style']);
		$error_color = esc_attr($instance['error_color']);
		$cat_list_type = esc_attr($instance['cat_list_type']);
		$publish_status = esc_attr($instance['publish_status']);
		$top_content_code = esc_attr($instance['top_content_code']);
		$bottom_content_code = esc_attr($instance['bottom_content_code']);
		$disable_new_cat = esc_attr($instance['disable_new_cat']);
		$show_tags = esc_attr($instance['show_tags']);
		$show_post_date = esc_attr($instance['show_post_date']);
		$show_excerpt = esc_attr($instance['show_excerpt']);
		$post_mail = esc_attr($instance['post_mail']);
		$post_type = esc_attr($instance['post_type']);
		$disable_editor = esc_attr($instance['disable_editor']);
		$disable_plugins = esc_attr($instance['disable_plugins']);
		$editor_label = esc_attr($instance['editor_label']);
		$new_lines = esc_attr($instance['new_lines']);
		$editor_type = esc_attr($instance['editor_type']);
		$disable_media_upload = esc_attr($instance['disable_media_upload']);
		$shared_upload_dirs = esc_attr($instance['shared_upload_dirs']);
		$allow_guests = esc_attr($instance['allow_guests']);
		$guest_account = esc_attr($instance['guest_account']);
		$post_confirmation = esc_attr($instance['post_confirmation']);
		$post_fail_message = esc_attr($instance['post_fail_message']);
		$use_captcha = esc_attr($instance['use_captcha']);
		$captcha_label = esc_attr($instance['captcha_label']);
		$captcha_input_style = esc_attr($instance['captcha_input_style']);
		$captcha_image_style = esc_attr($instance['captcha_image_style']);
		$show_text = esc_attr($instance['show_text']);
		$hide_text = esc_attr($instance['hide_text']);
		$initial_visibility = esc_attr($instance['initial_visibility']);
		$redirect_to = esc_attr($instance['redirect_to']);
		$inexclude = esc_attr($instance['inexclude']);
		$cat_list = esc_attr($instance['cat_list']);
		$cat_default = esc_attr($instance['cat_default']);
		$cat_order = esc_attr($instance['cat_order']);
		$taxonomy_list = esc_attr($instance['taxonomy_list']);
		$post_custom_field_style = esc_attr($instance['post_custom_field_style']);
		for ($cfn = 1; $cfn <= $qpw_custom_fields_count; $cfn++) {
			$customfield = 'customfield_' . $cfn;
			$label_customfield = 'label_customfield_' . $cfn;
			$req_customfield = 'req_customfield_' . $cfn;
			$$customfield = esc_attr($instance[$customfield]);
			$$label_customfield = esc_attr($instance[$label_customfield]);
			$$req_customfield = esc_attr($instance[$req_customfield]);
		}
		if (function_exists('get_taxonomies')) {
			$taxonomies=get_taxonomies(array('public' => true, '_builtin' => false), 'names');
			if  ($taxonomies) {
				foreach ($taxonomies as $taxonomy ) {
					$req_taxonomy = 'req_taxonomy_' . $taxonomy;
					$$req_taxonomy = esc_attr($instance[$req_taxonomy]);
					$new_taxonomy = 'new_taxonomy_' . $taxonomy;
					$$new_taxonomy = esc_attr($instance[$new_taxonomy]);
					$type_taxonomy = 'type_taxonomy_' . $taxonomy;
					$$type_taxonomy = esc_attr($instance[$type_taxonomy]);
				}
			}
		}
		?>
		<div class="quick_post_option_form">
			<a href="javascript: void(0)" onclick="popup('<?php echo $qpw_plugin_url . 'quick-post-widget-help.html'?>')"><?php _e('Need help? Just click here!', 'quick-post-widget') ?></a>
			<br/>
			<div class="quick_post_option_box">
				<div class="quick_post_option_header_div">
					<span class="quick_post_option_header"><?php _e('General options', 'quick-post-widget') ?></span>
					<span class="quick_post_option_icon_no_js hide-if-js"></span>
					<span class="quick_post_option_icon hide-if-no-js"></span>
				</div>
				<div class="quick_post_option_div">
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget title', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" title="<?php _e('Widget title to display', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('publish_status'); ?>"><?php _e('Publish status', 'quick-post-widget') ?>:</label>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('publish_status'); ?>" name="<?php echo $this->get_field_name('publish_status'); ?>" title="<?php _e('Publish status', 'quick-post-widget') ?>">
						<option value="publish" <?php if ($publish_status == 'publish') echo "selected=\"selected\""; ?> ><?php _e('Publish', 'quick-post-widget') ?></option> 
						<option value="pending" <?php if ($publish_status == 'pending') echo "selected=\"selected\""; ?> ><?php _e('Pending', 'quick-post-widget') ?></option> 
						<option value="draft" <?php if ($publish_status == 'draft') echo "selected=\"selected\""; ?> ><?php _e('Draft', 'quick-post-widget') ?></option> 
						<option value="private" <?php if ($publish_status == 'private') echo "selected=\"selected\""; ?> ><?php _e('Private', 'quick-post-widget') ?></option> 
					</select>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('error_color'); ?>"><?php _e('Border error color', 'quick-post-widget') ?>:</label>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('error_color'); ?>" name="<?php echo $this->get_field_name('error_color'); ?>" title="<?php _e('Bordercolor to use in case a required field is empty', 'quick-post-widget') ?>" > 
					<?php
					 $color_arr = array (
						"#FF0000," . __('Red', 'quick-post-widget') . ",#000000", 
						"#FFFFFF," . __('White', 'quick-post-widget') . ",#000000", 
						"#FFFF00," . __('Yellow', 'quick-post-widget') . ",#000000", 
						"#FF00FF," . __('Fuchsia', 'quick-post-widget') . ",#000000", 
						"#C0C0C0," . __('Silver', 'quick-post-widget') . ",#000000", 
						"#808080," . __('Gray', 'quick-post-widget') . ",#000000", 
						"#808000," . __('Olive', 'quick-post-widget') . ",#000000", 
						"#800080," . __('Purple', 'quick-post-widget') . ",#FFFFFF", 
						"#800000," . __('Maroon', 'quick-post-widget') . ",#FFFFFF", 
						"#00FFFF," . __('Aqua', 'quick-post-widget') . ",#000000", 
						"#00FF00," . __('Lime', 'quick-post-widget') . ",#000000", 
						"#008080," . __('Teal', 'quick-post-widget') . ",#000000", 
						"#008000," . __('Green', 'quick-post-widget') . ",#000000", 
						"#0000FF," . __('Blue', 'quick-post-widget') . ",#FFFFFF", 
						"#000080," . __('Navy', 'quick-post-widget') . ",#FFFFFF", 
						"#000000," . __('Black', 'quick-post-widget') . ",#FFFFFF"
					);
					for($i=0; $i<count($color_arr); $i++) {
						$line = explode(",", $color_arr[$i]);
						$value = $line[0];
						$color = $line[1];
						$textcolor = $line[2];
						echo "<option value=\"".$value."\" style=\"background-color:".$value."; color:".$textcolor."\"";
						if ($value == $error_color) echo "selected=\"selected\"";
						echo ">".$color."</option>\n";
					}
					?>
					</select>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('show_tags'); ?>"><?php _e('Show tags field', 'quick-post-widget') ?>:</label>
					<?php if ($show_tags == 'yes') $show_tags='optional'; ?>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('show_tags'); ?>" name="<?php echo $this->get_field_name('show_tags'); ?>" title="<?php _e('Hide or show post tags input field, optional or required', 'quick-post-widget') ?>">
						<option value="hide" <?php if ($show_tags == 'hide') echo "selected=\"selected\""; ?> ><?php _e('Hide', 'quick-post-widget') ?></option> 
						<option value="optional" <?php if ($show_tags == 'optional') echo "selected=\"selected\""; ?> ><?php _e('Optional', 'quick-post-widget') ?></option> 
						<option value="required" <?php if ($show_tags == 'required') echo "selected=\"selected\""; ?> ><?php _e('Required', 'quick-post-widget') ?></option> 
					</select>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('show_post_date'); ?>"><?php _e('Show post date', 'quick-post-widget') ?>:</label>
					<?php if ($show_post_date == 'yes') $show_post_date='optional'; ?>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('show_post_date'); ?>" name="<?php echo $this->get_field_name('show_post_date'); ?>" title="<?php _e('Hide or show post date input field, optional or required', 'quick-post-widget') ?>">
						<option value="hide" <?php if ($show_post_date == 'hide') echo "selected=\"selected\""; ?> ><?php _e('Hide', 'quick-post-widget') ?></option> 
						<option value="optional" <?php if ($show_post_date == 'optional') echo "selected=\"selected\""; ?> ><?php _e('Optional', 'quick-post-widget') ?></option> 
						<option value="required" <?php if ($show_post_date == 'required') echo "selected=\"selected\""; ?> ><?php _e('Required', 'quick-post-widget') ?></option> 
					</select>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('show_excerpt'); ?>"><?php _e('Show excerpt', 'quick-post-widget') ?>:</label>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" title="<?php _e('Hide or show post excerpt input field, optional or required', 'quick-post-widget') ?>">
						<option value="hide" <?php if ($show_excerpt == 'hide') echo "selected=\"selected\""; ?> ><?php _e('Hide', 'quick-post-widget') ?></option> 
						<option value="optional" <?php if ($show_excerpt == 'optional') echo "selected=\"selected\""; ?> ><?php _e('Optional', 'quick-post-widget') ?></option> 
						<option value="required" <?php if ($show_excerpt == 'required') echo "selected=\"selected\""; ?> ><?php _e('Required', 'quick-post-widget') ?></option> 
					</select>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_confirmation'); ?>"><?php _e('Success message', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_confirmation'); ?>" name="<?php echo $this->get_field_name('post_confirmation'); ?>" type="text" value="<?php echo $post_confirmation; ?>" title="<?php _e('Optional message after a successful post', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_fail_message'); ?>"><?php _e('Post fail message', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_fail_message'); ?>" name="<?php echo $this->get_field_name('post_fail_message'); ?>" type="text" value="<?php echo $post_fail_message; ?>" title="<?php _e('Optional message after a failed post', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('redirect_to'); ?>"><?php _e('Redirect to', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('redirect_to'); ?>" name="<?php echo $this->get_field_name('redirect_to'); ?>" type="text" value="<?php echo $redirect_to; ?>" title="<?php _e('Complete URL to redirect to after a scuccesful post', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_mail'); ?>"><?php _e('Mail on post', 'quick-post-widget') ?>:</label>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('post_mail'); ?>" name="<?php echo $this->get_field_name('post_mail'); ?>" title="<?php _e('Mail when a new post is submitted', 'quick-post-widget') ?>">
						<option value="no" <?php if ($post_mail == 'no') echo "selected=\"selected\""; ?> ><?php _e('No', 'quick-post-widget') ?></option> 
						<option value="guest_posts" <?php if ($post_mail == 'guest_posts') echo "selected=\"selected\""; ?> ><?php _e('Guest posts', 'quick-post-widget') ?></option> 
						<option value="all_posts" <?php if ($post_mail == 'all_posts') echo "selected=\"selected\""; ?> ><?php _e('All posts', 'quick-post-widget') ?></option> 
					</select>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Post type', 'quick-post-widget') ?>:</label>
					<?php
					$custom_post_types=get_post_types(array('public' => true, '_builtin' => false), 'objects');
					?>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>" title="<?php _e('Post type, normal post or custom', 'quick-post-widget') ?>">
					  <option value="post"><?php _e('Normal (post)', 'quick-post-widget'); ?></option>
					  <?php
					  foreach ($custom_post_types  as $custom_post_type ) {
					   echo "\n<option value='" . esc_attr($custom_post_type->name) . "'" . (($custom_post_type->name == $post_type) ? 'selected="selected"' : '') . ">" . esc_html($custom_post_type->label) . "</option>";
					  }
					  ?>
					 </select>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('top_content_code'); ?>"><?php _e('Top of content', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('top_content_code'); ?>" name="<?php echo $this->get_field_name('top_content_code'); ?>" type="text" value="<?php echo $top_content_code; ?>" title="<?php _e('Code to put at the top of the content', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('bottom_content_code'); ?>"><?php _e('Bottom of content', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('bottom_content_code'); ?>" name="<?php echo $this->get_field_name('bottom_content_code'); ?>" type="text" value="<?php echo $bottom_content_code; ?>" title="<?php _e('Code to put at the bottom of the content', 'quick-post-widget') ?>" />
					<br/>
				</div>
			</div>
			<div class="quick_post_option_box">
				<div class="quick_post_option_header_div">
					<span class="quick_post_option_header"><?php _e('Visibility options', 'quick-post-widget') ?></span>
					<span class="quick_post_option_icon_no_js hide-if-js"></span>
					<span class="quick_post_option_icon hide-if-no-js"></span>
				</div>
				<div class="quick_post_option_div">
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('initial_visibility'); ?>"><?php _e('Initial visibility', 'quick-post-widget') ?>:</label>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('initial_visibility'); ?>" name="<?php echo $this->get_field_name('initial_visibility'); ?>" title="<?php _e('Inititial visibility', 'quick-post-widget') ?>">
						<option value="show" <?php if ($initial_visibility != 'hide') echo "selected=\"selected\""; ?> ><?php _e('Show', 'quick-post-widget') ?></option> 
						<option value="hide" <?php if ($initial_visibility == 'hide') echo "selected=\"selected\""; ?> ><?php _e('Hide', 'quick-post-widget') ?></option> 
					</select>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('show_text'); ?>"><?php _e('Showtext', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('show_text'); ?>" name="<?php echo $this->get_field_name('show_text'); ?>" type="text" value="<?php echo $show_text; ?>" title="<?php _e('Text to click to show the widget', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('hide_text'); ?>"><?php _e('Hidetext', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('hide_text'); ?>" name="<?php echo $this->get_field_name('hide_text'); ?>" type="text" value="<?php echo $hide_text; ?>" title="<?php _e('Text to click to hide the widget', 'quick-post-widget') ?>" />
					<br/>
				</div>
			</div>
			<div class="quick_post_option_box">
				<div class="quick_post_option_header_div">
					<span class="quick_post_option_header"><?php _e('Category options', 'quick-post-widget') ?></span>
					<span class="quick_post_option_icon_no_js hide-if-js"></span>
					<span class="quick_post_option_icon hide-if-no-js"></span>
				</div>
				<div class="quick_post_option_div">
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('cat_list_type'); ?>"><?php _e('Category list type', 'quick-post-widget') ?>:</label>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('cat_list_type'); ?>" name="<?php echo $this->get_field_name('cat_list_type'); ?>" title="<?php _e('Category list type', 'quick-post-widget') ?>">
						<option value="droplist" <?php if ($cat_list_type != 'checklist') echo "selected=\"selected\""; ?> ><?php _e('Droplist', 'quick-post-widget') ?></option> 
						<option value="checklist" <?php if ($cat_list_type == 'checklist') echo "selected=\"selected\""; ?> ><?php _e('Checklist', 'quick-post-widget') ?></option> 
					</select>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('inexclude'); ?>"><?php _e('Include/exclude categories', 'quick-post-widget') ?>:</label>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('inexclude'); ?>" name="<?php echo $this->get_field_name('inexclude'); ?>" title="<?php _e('Include or exclude certain categories', 'quick-post-widget') ?>">
						<option value="include" <?php if ($inexclude != 'include') echo "selected=\"selected\""; ?> ><?php _e('Include', 'quick-post-widget') ?></option> 
						<option value="exclude" <?php if ($inexclude == 'exclude') echo "selected=\"selected\""; ?> ><?php _e('Exclude', 'quick-post-widget') ?></option> 
					</select>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('cat_list'); ?>"><?php _e('Categories to include or exclude', 'quick-post-widget') ?>:</label>
					<br/>
					<div class="quick_post_cat_checklist_admin" id="<?php echo $this->get_field_id('cat_list'); ?>" name="<?php echo $this->get_field_id('cat_list'); ?>" title="<?php _e('List of categories to include or exclude', 'quick-post-widget') ?>">
						<ul id="cats">
						<?php qpw_category_checklist(explode(',', $cat_list), '', '', 'name');?>
						</ul>
					</div>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('cat_default'); ?>"><?php _e('Default category', 'quick-post-widget') ?>:</label>
					<span title="<?php _e('Default category', 'quick-post-widget') ?>"><?php wp_dropdown_categories('class=quick_post_droplist&hide_empty=0&hierarchical=1&name=' . $this->get_field_name('cat_default') . '&selected=' . $cat_default . '&show_option_none=' . __('None', 'quick-post-widget')); ?></span>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('cat_order'); ?>"><?php _e('Order categories by', 'quick-post-widget') ?>:</label>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('cat_order'); ?>" name="<?php echo $this->get_field_name('cat_order'); ?>" title="<?php _e('Order categories by name or id', 'quick-post-widget') ?>">
						<option value="name" <?php if ($cat_order != 'name') echo "selected=\"selected\""; ?> ><?php _e('Name', 'quick-post-widget') ?></option> 
						<option value="id" <?php if ($cat_order == 'id') echo "selected=\"selected\""; ?> ><?php _e('Id', 'quick-post-widget') ?></option> 
					</select>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('disable_new_cat'); ?>"><?php _e('Disable new category', 'quick-post-widget') ?>:</label>
					<input type="checkbox" value="yes" <?php if ($disable_new_cat == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('disable_new_cat'); ?>" name="<?php echo $this->get_field_name('disable_new_cat'); ?>" type="text" value="<?php echo $disable_new_cat; ?>" title="<?php _e('Disable creation of new categories (despite of role)', 'quick-post-widget') ?>" />
					<br/>
				</div>
			</div>
			<div class="quick_post_option_box">
				<div class="quick_post_option_header_div">
					<span class="quick_post_option_header"><?php _e('Visual editor options', 'quick-post-widget') ?></span>
					<span class="quick_post_option_icon_no_js hide-if-js"></span>
					<span class="quick_post_option_icon hide-if-no-js"></span>
				</div>
				<div class="quick_post_option_div">
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('disable_editor'); ?>"><?php _e('Disable visual editor', 'quick-post-widget') ?>:</label>
					<input type="checkbox" value="yes" <?php if ($disable_editor == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('disable_editor'); ?>" name="<?php echo $this->get_field_name('disable_editor'); ?>" type="text" value="<?php echo $disable_editor; ?>" title="<?php _e('Disable the visual editor', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('disable_plugins'); ?>"><?php _e('Disable editor plugins', 'quick-post-widget') ?>:</label>
					<input type="checkbox" value="yes" <?php if ($disable_plugins == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('disable_plugins'); ?>" name="<?php echo $this->get_field_name('disable_plugins'); ?>" type="text" value="<?php echo $disable_plugins; ?>" title="<?php _e('Disable the visual editor plugins', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('disable_media_upload'); ?>"><?php _e('Disable media upload', 'quick-post-widget') ?>:</label>
					<input type="checkbox" value="yes" <?php if ($disable_media_upload == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('disable_media_upload'); ?>" name="<?php echo $this->get_field_name('disable_media_upload'); ?>" type="text" value="<?php echo $disable_media_upload; ?>" title="<?php _e('Disable media uploading in the visual editor', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('shared_upload_dirs'); ?>"><?php _e('Shared upload directories', 'quick-post-widget') ?>:</label>
					<input type="checkbox" value="yes" <?php if ($shared_upload_dirs == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('shared_upload_dirs'); ?>" name="<?php echo $this->get_field_name('shared_upload_dirs'); ?>" type="text" value="<?php echo $shared_upload_dirs; ?>" title="<?php _e('Shared instead of private upload directories', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('disable_plugins'); ?>"><?php _e('Newlines tag', 'quick-post-widget') ?>:</label>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('new_lines'); ?>" name="<?php echo $this->get_field_name('new_lines'); ?>" title="<?php _e('Use P or BR tag for new lines', 'quick-post-widget') ?>">
						<option value="P" <?php if ($new_lines != 'BR') echo "selected=\"selected\""; ?> >&lt;P&gt;</option> 
						<option value="BR" <?php if ($new_lines == 'BR') echo "selected=\"selected\""; ?> >&lt;BR&gt;</option> 
					</select>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('editor_type'); ?>"><?php _e('Editor type on page', 'quick-post-widget') ?>:</label>
					<select class="quick_post_droplist" id="<?php echo $this->get_field_id('editor_type'); ?>" name="<?php echo $this->get_field_name('editor_type'); ?>" title="<?php _e('Show editor in popup or inline', 'quick-post-widget') ?>">
						<option value="inline" <?php if ($editor_type != 'popup') echo "selected=\"selected\""; ?> ><?php _e('Inline', 'quick-post-widget') ?></option> 
						<option value="popup" <?php if ($editor_type == 'popup') echo "selected=\"selected\""; ?> ><?php _e('Popup', 'quick-post-widget') ?></option> 
					</select>
					<br/>
				</div>
			</div>
			<div class="quick_post_option_box">
				<div class="quick_post_option_header_div">
					<span class="quick_post_option_header"><?php _e('Guest posting options', 'quick-post-widget') ?></span>
					<span class="quick_post_option_icon_no_js hide-if-js"></span>
					<span class="quick_post_option_icon hide-if-no-js"></span>
				</div>
				<div class="quick_post_option_div">
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('allow_guests'); ?>"><?php _e('Allow guests (not logged-in)', 'quick-post-widget') ?>:</label>
					<input type="checkbox" value="yes" <?php if ($allow_guests == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('allow_guests'); ?>" name="<?php echo $this->get_field_name('allow_guests'); ?>" type="text" value="<?php echo $allow_guests; ?>" title="<?php _e('Allow guest access (without being logged in, use with care!)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('guest_account'); ?>"><?php _e('Guest account', 'quick-post-widget') ?>:</label>
					<span title="<?php _e('Dedicated account to use for non-logged-in guests', 'quick-post-widget') ?>"><?php wp_dropdown_users('class=quick_post_droplist&name=' . $this->get_field_name('guest_account') . '&selected=' . $guest_account); ?></span>
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('use_captcha'); ?>"><?php _e('Enable captcha', 'quick-post-widget') ?>:</label>
					<input type="checkbox" value="yes" <?php if ($use_captcha == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('use_captcha'); ?>" name="<?php echo $this->get_field_name('use_captcha'); ?>" type="text" value="<?php echo $use_captcha; ?>" title="<?php _e('Enable captcha security code', 'quick-post-widget') ?>" />
					<br/>
				</div>
			</div>
			<div class="quick_post_option_box">
				<div class="quick_post_option_header_div">
					<span class="quick_post_option_header"><?php _e('Custom fields options', 'quick-post-widget') ?></span>
					<span class="quick_post_option_icon_no_js hide-if-js"></span>
					<span class="quick_post_option_icon hide-if-no-js"></span>
				</div>
				<div class="quick_post_option_div">
					<?php
					if ( qpw_count_custom_fields() > 0 ) {
							?>
							<div class="quick_post_custom_fields">
								<table class="quick_post_admin_table" width="auto" border=0>
									<tr>
										<th width="35%"><small><?php _e('Name', 'quick-post-widget') ?></small></th>
										<th width="10%"><small><?php _e('Req', 'quick-post-widget') ?></small></th>
										<th width="55%"><small><?php _e('Label', 'quick-post-widget') ?></small></th>
									</tr>
									<tr>
										<?php
										for ($cfn = 1; $cfn <= $qpw_custom_fields_count; $cfn++) {
											$customfield = 'customfield_' . $cfn;
											$label_customfield = 'label_customfield_' . $cfn;
											$req_customfield = 'req_customfield_' . $cfn;
											echo "<td>";
											qpw_custom_fields_dropdown($cfn, $this->get_field_id($customfield), $this->get_field_name($customfield), $$customfield);
											echo "</td>";
											?>
											<td>
												<input type="checkbox" value="yes" <?php if ($$req_customfield == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id($req_customfield); ?>" name="<?php echo $this->get_field_name($req_customfield); ?>" type="text" value="<?php echo $$req_customfield; ?>" title="<?php _e('Custom field', 'quick-post-widget'); echo ' ' . $cfn . ' '; _e('required', 'quick-post-widget'); echo '?'; ?>" />
											</td>
											<td>
												<input style="width: 100%" id="<?php echo $this->get_field_id($label_customfield); ?>" name="<?php echo $this->get_field_name($label_customfield); ?>" type="text" value="<?php echo $$label_customfield; ?>" title="<?php _e('Label for custom field', 'quick-post-widget'); echo ' ' . $cfn; ?>" />
											</td>
										</tr>
									<?php } ?>
								</table>
							</div>
					<?php } else { ?>
						<span class="quick_post_option_label"><?php _e('No custom fields exist!', 'quick-post-widget') ?></span>
					<?php } ?>
					<br/>
				</div>
			</div>
			<div class="quick_post_option_box">
				<div class="quick_post_option_header_div">
					<span class="quick_post_option_header"><?php _e('Custom taxonomy options', 'quick-post-widget') ?></span>
					<span class="quick_post_option_icon_no_js hide-if-js"></span>
					<span class="quick_post_option_icon hide-if-no-js"></span>
				</div>
				<div class="quick_post_option_div">
					<?php
					if (function_exists('get_taxonomies')) {
						$taxonomies=get_taxonomies(array('public' => true, '_builtin' => false), 'objects');
						if  ($taxonomies) {	?>
							<label class="quick_post_option_label" for="<?php echo $this->get_field_id('taxonomy_list'); ?>"><?php _e('Select taxonomies to use', 'quick-post-widget') ?>:</label>
							<br/>
							<div class="quick_post_cat_checklist_admin" id="<?php echo $this->get_field_id('taxonomy_list'); ?>" name="<?php echo $this->get_field_id('taxonomy_list'); ?>" title="<?php _e('List of taxonomies to use', 'quick-post-widget') ?>">
								<table class="quick_post_admin_table" width="auto" border=0>
									<tr>
										<th width="65%"><small><?php _e('Name', 'quick-post-widget') ?></small></th>
										<th width="25%"><small><?php _e('Type', 'quick-post-widget') ?></small></th>
										<th width="5%"><small><?php _e('Req', 'quick-post-widget') ?></small></th>
										<th width="5%"><small><?php _e('New', 'quick-post-widget') ?></small></th>
									</tr> <?php
									foreach ($taxonomies as $taxonomy) {
										echo "<tr>";
										echo "<td>";
										echo '<label class="selectit" title="' . __('Taxonomy name', 'quick-post-widget') . '">';
										$checked = in_array( $taxonomy->name, explode(',', $taxonomy_list) ) ? ' checked="checked"' : '';
										echo '<input value="' . $taxonomy->name . '" type="checkbox" name="taxonomy[]" id="in-taxonomy-' . $taxonomy->name . '" title="' . __('Select to use taxonomy', 'quick-post-widget') . '" ' . $checked . '> ' . $taxonomy->label . '</label>';
										echo "</td>";
										echo "<td>";
										$type_taxonomy = 'type_taxonomy_' . $taxonomy->name; ?>
										<select id="<?php echo $this->get_field_id($type_taxonomy); ?>" name="<?php echo $this->get_field_name($type_taxonomy); ?>" title="<?php _e('List type taxonomy', 'quick-post-widget') ?>">
											<option value="droplist" <?php if ($$type_taxonomy != 'checklist') echo "selected=\"selected\""; ?> ><?php _e('Drop', 'quick-post-widget') ?></option> 
											<option value="checklist" <?php if ($$type_taxonomy == 'checklist') echo "selected=\"selected\""; ?> ><?php _e('Check', 'quick-post-widget') ?></option> 
										</select> <?php
										echo "</td>";
										echo "<td>";
										$req_taxonomy = 'req_taxonomy_' . $taxonomy->name;
										?>
										<input type="checkbox" value="yes" <?php if ($$req_taxonomy == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id($req_taxonomy); ?>" name="<?php echo $this->get_field_name($req_taxonomy); ?>" type="text" value="<?php echo $$req_taxonomy; ?>" title="<?php _e('Taxonomy required', 'quick-post-widget'); echo '?'; ?>" />
										<?php
										echo "</td>";
										echo "<td>";
										$new_taxonomy = 'new_taxonomy_' . $taxonomy->name;
										?>
										<input type="checkbox" value="yes" <?php if ($$new_taxonomy == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id($new_taxonomy); ?>" name="<?php echo $this->get_field_name($new_taxonomy); ?>" type="text" value="<?php echo $$new_taxonomy; ?>" title="<?php _e('Allow new category for taxonomy', 'quick-post-widget'); echo '?'; ?>" />
										<?php
										echo "</td>";
										echo "</tr>";
									} ?>
								</table>
							</div>
						<?php } else { ?>
							<span class="quick_post_option_label"><?php _e('No custom taxonomies exist!', 'quick-post-widget') ?></span>
						<?php }
					} else { ?>
						<span class="quick_post_option_label"><?php _e('Require at least WordPress 3.0!', 'quick-post-widget') ?></span>
					<?php } ?>
					<br/>
				</div>
			</div>
			<div class="quick_post_option_box">
				<div class="quick_post_option_header_div">
					<span class="quick_post_option_header"><?php _e('Labels', 'quick-post-widget') ?></span>
					<span class="quick_post_option_icon_no_js hide-if-js"></span>
					<span class="quick_post_option_icon hide-if-no-js"></span>
				</div>
				<div class="quick_post_option_div">
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_title_label'); ?>"><?php _e('Post title', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_title_label'); ?>" name="<?php echo $this->get_field_name('post_title_label'); ?>" type="text" value="<?php echo $post_title_label; ?>" title="<?php _e('Label for the field [Post title]', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_content_label'); ?>"><?php _e('Post content', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_content_label'); ?>" name="<?php echo $this->get_field_name('post_content_label'); ?>" type="text" value="<?php echo $post_content_label; ?>" title="<?php _e('Label for the field [Post content]', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_tags_label'); ?>"><?php _e('Tags', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_tags_label'); ?>" name="<?php echo $this->get_field_name('post_tags_label'); ?>" type="text" value="<?php echo $post_tags_label; ?>" title="<?php _e('Label for the field [Tags]', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_date_label'); ?>"><?php _e('Post date', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_date_label'); ?>" name="<?php echo $this->get_field_name('post_date_label'); ?>" type="text" value="<?php echo $post_date_label; ?>" title="<?php _e('Label for the field [Post date]', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('category_label'); ?>"><?php _e('Category', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('category_label'); ?>" name="<?php echo $this->get_field_name('category_label'); ?>" type="text" value="<?php echo $category_label; ?>" title="<?php _e('Label of the [Categories] droplist', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('new_cat_label'); ?>"><?php _e('New category', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('new_cat_label'); ?>" name="<?php echo $this->get_field_name('new_cat_label'); ?>" type="text" value="<?php echo $new_cat_label; ?>" title="<?php _e('Label of the field [New category]', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('submit_label'); ?>"><?php _e('Post button', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('submit_label'); ?>" name="<?php echo $this->get_field_name('submit_label'); ?>" type="text" value="<?php echo $submit_label; ?>" title="<?php _e('Label of the [Post] button', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('editor_label'); ?>"><?php _e('Editor button', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('editor_label'); ?>" name="<?php echo $this->get_field_name('editor_label'); ?>" type="text" value="<?php echo $editor_label; ?>" title="<?php _e('Label of the [Editor] button', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('captcha_label'); ?>"><?php _e('Captcha input', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('captcha_label'); ?>" name="<?php echo $this->get_field_name('captcha_label'); ?>" type="text" value="<?php echo $captcha_label; ?>" title="<?php _e('Captcha security field', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('editor_title'); ?>"><?php _e('Visual editor title', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('editor_title'); ?>" name="<?php echo $this->get_field_name('editor_title'); ?>" type="text" value="<?php echo $editor_title; ?>" title="<?php _e('Title popup visual editor window', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('message_title'); ?>"><?php _e('Message title', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('message_title'); ?>" name="<?php echo $this->get_field_name('message_title'); ?>" type="text" value="<?php echo $message_title; ?>" title="<?php _e('Title popup message window', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_excerpt_label'); ?>"><?php _e('Post excerpt', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_excerpt_label'); ?>" name="<?php echo $this->get_field_name('post_excerpt_label'); ?>" type="text" value="<?php echo $post_excerpt_label; ?>" title="<?php _e('Label of the field [Excerpt]', 'quick-post-widget') ?>" />
					<br/>
				</div>
			</div>
			<div class="quick_post_option_box">
				<div class="quick_post_option_header_div">
					<span class="quick_post_option_header"><?php _e('Defaults', 'quick-post-widget') ?></span>
					<span class="quick_post_option_icon_no_js hide-if-js"></span>
					<span class="quick_post_option_icon hide-if-no-js"></span>
				</div>
				<div class="quick_post_option_div">
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_title_default'); ?>"><?php _e('Post title', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_title_default'); ?>" name="<?php echo $this->get_field_name('post_title_default'); ?>" type="text" value="<?php echo $post_title_default; ?>" title="<?php _e('Default content of the field [Post title]', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_content_default'); ?>"><?php _e('Post content', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_content_default'); ?>" name="<?php echo $this->get_field_name('post_content_default'); ?>" type="text" value="<?php echo $post_content_default; ?>" title="<?php _e('Default content of the field [Post content]', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_tag_list_default'); ?>"><?php _e('Tag list', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_tag_list_default'); ?>" name="<?php echo $this->get_field_name('post_tag_list_default'); ?>" type="text" value="<?php echo $post_tag_list_default; ?>" title="<?php _e('Default content of the Tag listbox', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_cat_parent_default'); ?>"><?php _e('Parent category', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_cat_parent_default'); ?>" name="<?php echo $this->get_field_name('post_cat_parent_default'); ?>" type="text" value="<?php echo $post_cat_parent_default; ?>" title="<?php _e('Default content of the field [Parent category]', 'quick-post-widget') ?>" />
					<br/>
				</div>
			</div>
			<div class="quick_post_option_box">
				<div class="quick_post_option_header_div">
					<span class="quick_post_option_header"><?php _e('Styles (override CSS)', 'quick-post-widget') ?></span>
					<span class="quick_post_option_icon_no_js hide-if-js"></span>
					<span class="quick_post_option_icon hide-if-no-js"></span>
				</div>
				<div class="quick_post_option_div">
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('widget_style'); ?>"><?php _e('Widget', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('widget_style'); ?>" name="<?php echo $this->get_field_name('widget_style'); ?>" type="text" value="<?php echo $widget_style; ?>" title="<?php _e('Styling for the widget (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_title_style'); ?>"><?php _e('Post title', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_title_style'); ?>" name="<?php echo $this->get_field_name('post_title_style'); ?>" type="text" value="<?php echo $post_title_style; ?>" title="<?php _e('Styling for the field [Post title] (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_content_style'); ?>"><?php _e('Post content', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_content_style'); ?>" name="<?php echo $this->get_field_name('post_content_style'); ?>" type="text" value="<?php echo $post_content_style; ?>" title="<?php _e('Styling for the field [Post content] (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_tags_style'); ?>"><?php _e('Post tags', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_tags_style'); ?>" name="<?php echo $this->get_field_name('post_tags_style'); ?>" type="text" value="<?php echo $post_tags_style; ?>" title="<?php _e('Styling for the field [Tags] (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_date_style'); ?>"><?php _e('Post date', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_date_style'); ?>" name="<?php echo $this->get_field_name('post_date_style'); ?>" type="text" value="<?php echo $post_date_style; ?>" title="<?php _e('Styling for the field [Post date] (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('cat_checklist_style'); ?>"><?php _e('Category checklist', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('cat_checklist_style'); ?>" name="<?php echo $this->get_field_name('cat_checklist_style'); ?>" type="text" value="<?php echo $cat_checklist_style; ?>" title="<?php _e('Styling for the [Category checklist] (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('label_style'); ?>"><?php _e('Labels', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('label_style'); ?>" name="<?php echo $this->get_field_name('label_style'); ?>" type="text" value="<?php echo $label_style; ?>" title="<?php _e('Styling for labels (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('rb_style'); ?>"><?php _e('Radio', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('rb_style'); ?>" name="<?php echo $this->get_field_name('rb_style'); ?>" type="text" value="<?php echo $rb_style; ?>" title="<?php _e('Styling for the radio button (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('new_cat_style'); ?>"><?php _e('New category', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('new_cat_style'); ?>" name="<?php echo $this->get_field_name('new_cat_style'); ?>" type="text" value="<?php echo $new_cat_style; ?>" title="<?php _e('Styling for the field [New category] (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('button_style'); ?>"><?php _e('Buttons', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('button_style'); ?>" name="<?php echo $this->get_field_name('button_style'); ?>" type="text" value="<?php echo $button_style; ?>" title="<?php _e('Styling for the [Post] button (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('captcha_input_style'); ?>"><?php _e('Captcha input', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('captcha_input_style'); ?>" name="<?php echo $this->get_field_name('captcha_input_style'); ?>" type="text" value="<?php echo $captcha_input_style; ?>" title="<?php _e('Styling for the field [Captcha security] (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('captcha_image_style'); ?>"><?php _e('Captcha image', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('captcha_image_style'); ?>" name="<?php echo $this->get_field_name('captcha_image_style'); ?>" type="text" value="<?php echo $captcha_image_style; ?>" title="<?php _e('Styling for the captcha image (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_custom_field_style'); ?>"><?php _e('Custom fields', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_custom_field_style'); ?>" name="<?php echo $this->get_field_name('post_custom_field_style'); ?>" type="text" value="<?php echo $post_custom_field_style; ?>" title="<?php _e('Styling for the custom fields (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('taxonomy_box_style'); ?>"><?php _e('Taxonomy box', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('taxonomy_box_style'); ?>" name="<?php echo $this->get_field_name('taxonomy_box_style'); ?>" type="text" value="<?php echo $taxonomy_box_style; ?>" title="<?php _e('Styling for the taxonomy boxes (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
					<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_excerpt_style'); ?>"><?php _e('Post excerpt', 'quick-post-widget') ?>:</label>
					<input class="quick_post_option" id="<?php echo $this->get_field_id('post_excerpt_style'); ?>" name="<?php echo $this->get_field_name('post_excerpt_style'); ?>" type="text" value="<?php echo $post_excerpt_style; ?>" title="<?php _e('Styling for the field [Post excerpt] (use valid CSS syntax)', 'quick-post-widget') ?>" />
					<br/>
				</div>
			</div>
		</div>
		<?php
	}

	function update($new_instance, $old_instance)
	{
		global $qpw_custom_fields_count;
		$instance = $old_instance;
		$instance['title']						= strip_tags($new_instance['title']);
		$instance['post_title_label']			= strip_tags($new_instance['post_title_label']);
		$instance['category_label']				= strip_tags($new_instance['category_label']);
		$instance['new_cat_label']				= strip_tags($new_instance['new_cat_label']);
		$instance['post_content_label']			= strip_tags($new_instance['post_content_label']);
		$instance['post_tags_label']			= strip_tags($new_instance['post_tags_label']);
		$instance['post_date_label']			= strip_tags($new_instance['post_date_label']);
		$instance['submit_label']				= strip_tags($new_instance['submit_label']);
		$instance['editor_title']				= strip_tags($new_instance['editor_title']);
		$instance['message_title']				= strip_tags($new_instance['message_title']);
		$instance['post_excerpt_label']			= strip_tags($new_instance['post_excerpt_label']);
		$instance['post_title_default']			= strip_tags($new_instance['post_title_default']);
		$instance['post_content_default']		= strip_tags($new_instance['post_content_default']);
		$instance['post_tag_list_default']		= strip_tags($new_instance['post_tag_list_default']);
		$instance['post_cat_parent_default']	= strip_tags($new_instance['post_cat_parent_default']);
		$instance['widget_style']				= strip_tags($new_instance['widget_style']);
		$instance['post_title_style']			= strip_tags($new_instance['post_title_style']);
		$instance['post_content_style']			= strip_tags($new_instance['post_content_style']);
		$instance['post_tags_style']			= strip_tags($new_instance['post_tags_style']);
		$instance['post_date_style']			= strip_tags($new_instance['post_date_style']);
		$instance['cat_checklist_style']		= strip_tags($new_instance['cat_checklist_style']);
		$instance['label_style']				= strip_tags($new_instance['label_style']);
		$instance['button_style']				= strip_tags($new_instance['button_style']);
		$instance['rb_style']					= strip_tags($new_instance['rb_style']);
		$instance['new_cat_style']				= strip_tags($new_instance['new_cat_style']);
		$instance['taxonomy_box_style']			= strip_tags($new_instance['taxonomy_box_style']);
		$instance['post_excerpt_style']			= strip_tags($new_instance['post_excerpt_style']);
		$instance['error_color']				= strip_tags($new_instance['error_color']);
		$instance['cat_list_type']				= strip_tags($new_instance['cat_list_type']);
		$instance['publish_status']				= strip_tags($new_instance['publish_status']);
		$instance['top_content_code']			= $new_instance['top_content_code'];
		$instance['bottom_content_code']		= $new_instance['bottom_content_code'];
		$instance['disable_new_cat']			= strip_tags($new_instance['disable_new_cat']);
		$instance['show_tags']					= strip_tags($new_instance['show_tags']);
		$instance['show_post_date']				= strip_tags($new_instance['show_post_date']);
		$instance['show_excerpt']				= strip_tags($new_instance['show_excerpt']);
		$instance['post_mail']					= strip_tags($new_instance['post_mail']);
		$instance['post_type']					= strip_tags($new_instance['post_type']);
		$instance['disable_editor']				= strip_tags($new_instance['disable_editor']);
		$instance['disable_plugins']			= strip_tags($new_instance['disable_plugins']);
		$instance['editor_label']				= strip_tags($new_instance['editor_label']);
		$instance['new_lines']					= strip_tags($new_instance['new_lines']);
		$instance['editor_type']				= strip_tags($new_instance['editor_type']);
		$instance['disable_media_upload']		= strip_tags($new_instance['disable_media_upload']);
		$instance['shared_upload_dirs']			= strip_tags($new_instance['shared_upload_dirs']);
		$instance['allow_guests']				= strip_tags($new_instance['allow_guests']);
		$instance['guest_account']				= strip_tags($new_instance['guest_account']);
		$instance['post_confirmation']			= strip_tags($new_instance['post_confirmation']);
		$instance['post_fail_message']			= strip_tags($new_instance['post_fail_message']);
		$instance['use_captcha']				= strip_tags($new_instance['use_captcha']);
		$instance['captcha_label']				= strip_tags($new_instance['captcha_label']);
		$instance['captcha_input_style']		= strip_tags($new_instance['captcha_input_style']);
		$instance['captcha_image_style']		= strip_tags($new_instance['captcha_image_style']);
		$instance['show_text']					= strip_tags($new_instance['show_text']);
		$instance['hide_text']					= strip_tags($new_instance['hide_text']);
		$instance['initial_visibility']			= strip_tags($new_instance['initial_visibility']);
		$instance['redirect_to']				= strip_tags($new_instance['redirect_to']);
		$instance['inexclude']					= strip_tags($new_instance['inexclude']);
		$instance['cat_list']					= strip_tags(implode(',', $_POST['post_category']));
		$instance['cat_default']				= strip_tags($new_instance['cat_default']);
		$instance['cat_order']					= strip_tags($new_instance['cat_order']);
		$instance['taxonomy_list']				= strip_tags(implode(',', $_POST['taxonomy']));
		$instance['post_custom_field_style']	= strip_tags($new_instance['post_custom_field_style']);
		for ($cfn = 1; $cfn <= $qpw_custom_fields_count; $cfn++) {
			$instance['customfield_' . $cfn] =  strip_tags($new_instance['customfield_' . $cfn]);
			$instance['label_customfield_' . $cfn] = strip_tags($new_instance['label_customfield_' . $cfn]);
			$instance['req_customfield_' . $cfn] = strip_tags($new_instance['req_customfield_' . $cfn]);
		}
		if (function_exists('get_taxonomies')) {
			$taxonomies=get_taxonomies(array('public' => true, '_builtin' => false), 'names');
			if  ($taxonomies) {
				foreach ($taxonomies as $taxonomy ) {
					$instance['req_taxonomy_' . $taxonomy] = strip_tags($new_instance['req_taxonomy_' . $taxonomy]);
					$instance['new_taxonomy_' . $taxonomy] = strip_tags($new_instance['new_taxonomy_' . $taxonomy]);
					$instance['type_taxonomy_' . $taxonomy] = strip_tags($new_instance['type_taxonomy_' . $taxonomy]);
				}
			}
		}
		return $instance;
	}

	function widget($args, $instance)
	{
		global $qpw_plugin_url;
		global $qpw_locale;
		global $qpw_custom_fields_count;

		extract($args);
		
		$allow_guests				= ( $instance['allow_guests'] != '' ) ? esc_attr($instance['allow_guests']) : '';
		$guest_account				= ( $instance['guest_account'] != '' ) ? esc_attr($instance['guest_account']) : '1';
		$title						= ( $instance['title'] != '' ) ? apply_filters('title', esc_attr($instance['title'])) : '';
		$post_title_label			= ( $instance['post_title_label'] != '' ) ? esc_attr($instance['post_title_label']) : __('Title', 'quick-post-widget') . ':';
		$post_content_label			= ( $instance['post_content_label'] != '' ) ? esc_attr($instance['post_content_label']) : __('Content', 'quick-post-widget') . ':';
		$post_tags_label			= ( $instance['post_tags_label'] != '' ) ? esc_attr($instance['post_tags_label']) : __('Tags', 'quick-post-widget') . ':';
		$category_label				= ( $instance['category_label'] != '' ) ? esc_attr($instance['category_label']) : __('Category', 'quick-post-widget') . ':';
		$new_cat_label				= ( $instance['new_cat_label'] != '' ) ? esc_attr($instance['new_cat_label']) : __('New category', 'quick-post-widget') . ':';
		$submit_label				= ( $instance['submit_label'] != '' ) ? esc_attr($instance['submit_label']) : __('Post', 'quick-post-widget');
		$editor_title				= ( $instance['editor_title'] != '' ) ? esc_attr($instance['editor_title']) : __('Quick Post Widget Editor', 'quick-post-widget');
		$message_title				= ( $instance['message_title'] != '' ) ? esc_attr($instance['message_title']) : __('Quick Post Widget Message', 'quick-post-widget');
		$post_excerpt_label			= ( $instance['post_excerpt_label'] != '' ) ? esc_attr($instance['post_excerpt_label']) : __('Excerpt', 'quick-post-widget');
		$post_date_label			= ( $instance['post_date_label'] != '' ) ? esc_attr($instance['post_date_label']) : __('Post date', 'quick-post-widget') . ':';
		$post_title_default			= ( $instance['post_title_default'] != '' ) ? esc_attr($instance['post_title_default']) : __('Post title', 'quick-post-widget');
		$post_content_default		= ( $instance['post_content_default'] != '' ) ? esc_attr($instance['post_content_default']) : __('Post content', 'quick-post-widget');
		$post_tag_list_default		= ( $instance['post_tag_list_default'] != '' ) ? esc_attr($instance['post_tag_list_default']) : __('Quickselect a tag', 'quick-post-widget');
		$post_cat_parent_default	= ( $instance['post_cat_parent_default'] != '' ) ? esc_attr($instance['post_cat_parent_default']) : __('Parent category', 'quick-post-widget');
		$widget_style				= ( $instance['widget_style'] != '' ) ? esc_attr($instance['widget_style']) : '';
		$post_title_style			= ( $instance['post_title_style'] != '' ) ? esc_attr($instance['post_title_style']) : '';
		$post_content_style			= ( $instance['post_content_style'] != '' ) ? esc_attr($instance['post_content_style']) : '';
		$post_tags_style			= ( $instance['post_tags_style'] != '' ) ? esc_attr($instance['post_tags_style']) : '';
		$post_date_style			= ( $instance['post_date_style'] != '' ) ? esc_attr($instance['post_date_style']) : '';
		$cat_checklist_style		= ( $instance['cat_checklist_style'] != '' ) ? esc_attr($instance['cat_checklist_style']) : '';
		$label_style				= ( $instance['label_style'] != '' ) ? esc_attr($instance['label_style']) : '';
		$button_style				= ( $instance['button_style'] != '' ) ? esc_attr($instance['button_style']) : '';
		$rb_style					= ( $instance['rb_style'] != '' ) ? esc_attr($instance['rb_style']) : '';
		$new_cat_style				= ( $instance['new_cat_style'] != '' ) ? esc_attr($instance['new_cat_style']) : '';
		$taxonomy_box_style			= ( $instance['taxonomy_box_style'] != '' ) ? esc_attr($instance['taxonomy_box_style']) : '';
		$post_excerpt_style			= ( $instance['post_excerpt_style'] != '' ) ? esc_attr($instance['post_excerpt_style']) : '';
		$error_color				= ( $instance['error_color'] != '' ) ? esc_attr($instance['error_color']) : '#FF0000';
		$cat_list_type				= ( $instance['cat_list_type'] != '' ) ? esc_attr($instance['cat_list_type']) : 'droplist';
		$publish_status				= ( $instance['publish_status'] != '' ) ? esc_attr($instance['publish_status']) : 'publish';
		$top_content_code			= ( $instance['top_content_code'] != '' ) ? $instance['top_content_code'] : '';
		$bottom_content_code		= ( $instance['bottom_content_code'] != '' ) ? $instance['bottom_content_code'] : '';
		$disable_new_cat			= ( $instance['disable_new_cat'] != '' ) ? esc_attr($instance['disable_new_cat']) : '';
		$show_tags					= ( $instance['show_tags'] != '' ) ? esc_attr($instance['show_tags']) : 'hide';
		$show_post_date				= ( $instance['show_post_date'] != '' ) ? esc_attr($instance['show_post_date']) : 'hide';
		$show_excerpt				= ( $instance['show_excerpt'] != '' ) ? esc_attr($instance['show_excerpt']) : 'hide';
		$post_mail					= ( $instance['post_mail'] != '' ) ? esc_attr($instance['post_mail']) : 'no';
		$post_type					= ( $instance['post_type'] != '' ) ? esc_attr($instance['post_type']) : 'post';
		$disable_editor				= ( $instance['disable_editor'] != '' ) ? esc_attr($instance['disable_editor']) : '';
		$disable_plugins			= ( $instance['disable_plugins'] != '' ) ? esc_attr($instance['disable_plugins']) : '';
		$editor_label				= ( $instance['editor_label'] != '' ) ? esc_attr($instance['editor_label']) : __('Visual Editor', 'quick-post-widget');
		$new_lines					= ( $instance['new_lines'] != '' ) ? esc_attr($instance['new_lines']) : 'P';
		$editor_type				= ( $instance['editor_type'] != '' ) ? esc_attr($instance['editor_type']) : 'inline';
		$disable_media_upload		= ( $instance['disable_media_upload'] != '' ) ? esc_attr($instance['disable_media_upload']) : '';
		$shared_upload_dirs			= ( $instance['shared_upload_dirs'] != '' ) ? esc_attr($instance['shared_upload_dirs']) : '';
		$post_confirmation			= ( $instance['post_confirmation'] != '' ) ? esc_attr($instance['post_confirmation']) : '';
		$post_fail_message			= ( $instance['post_fail_message'] != '' ) ? esc_attr($instance['post_fail_message']) : '';
		$use_captcha				= ( $instance['use_captcha'] != '' ) ? esc_attr($instance['use_captcha']) : '';
		$captcha_label				= ( $instance['captcha_label'] != '' ) ? esc_attr($instance['captcha_label']) : __('Captcha security', 'quick-post-widget') . ':';
		$captcha_input_style		= ( $instance['captcha_input_style'] != '' ) ? esc_attr($instance['captcha_input_style']) : '';
		$captcha_image_style		= ( $instance['captcha_image_style'] != '' ) ? esc_attr($instance['captcha_image_style']) : '';
		$show_text					= ( $instance['show_text'] != '' ) ? esc_attr($instance['show_text']) : '';
		$hide_text					= ( $instance['hide_text'] != '' ) ? esc_attr($instance['hide_text']) : '';
		$initial_visibility			= ( $instance['initial_visibility'] != '' ) ? esc_attr($instance['initial_visibility']) : 'show';
		$redirect_to				= ( $instance['redirect_to'] != '' ) ? esc_attr($instance['redirect_to']) : qpw_curPageURL();
		$inexclude					= ( $instance['inexclude'] != '' ) ? esc_attr($instance['inexclude']) : 'include';
		$cat_list					= ( $instance['cat_list'] != '' ) ? esc_attr($instance['cat_list']) : '';
		$cat_default				= ( $instance['cat_default'] != '' ) ? esc_attr($instance['cat_default']) : '';
		$cat_order					= ( $instance['cat_order'] != '' ) ? esc_attr($instance['cat_order']) : 'name';
		$taxonomy_list				= ( $instance['taxonomy_list'] != '' ) ? esc_attr($instance['taxonomy_list']) : '';
		$post_custom_field_style	= ( $instance['post_custom_field_style'] != '' ) ? esc_attr($instance['post_custom_field_style']) : 'border: 1px solid #9F9F9F; padding: 0';
		for ($cfn = 1; $cfn <= $qpw_custom_fields_count; $cfn++) {
			$customfield = 'customfield_' . $cfn;
			$label_customfield = 'label_customfield_' . $cfn;
			$req_customfield = 'req_customfield_' . $cfn;
			$$customfield = ( $instance[$customfield] != '#NONE#' ) ? esc_attr($instance[$customfield]) : '';
			$$label_customfield = ( $instance[$label_customfield] != '' ) ? esc_attr($instance[$label_customfield]) : '';
			$$req_customfield = ( $instance[$req_customfield] != '' ) ? esc_attr($instance[$req_customfield]) : '';
		}
		if (function_exists('get_taxonomies')) {
			$taxonomies=get_taxonomies(array('public' => true, '_builtin' => false), 'names');
			if  ($taxonomies) {
				foreach ($taxonomies as $taxonomy ) {
					$req_taxonomy = 'req_taxonomy_' . $taxonomy;
					$$req_taxonomy = ( $instance[$req_taxonomy] != '' ) ? esc_attr($instance[$req_taxonomy]) : '';
					$new_taxonomy = 'new_taxonomy_' . $taxonomy;
					$$new_taxonomy = ( $instance[$new_taxonomy] != '' ) ? esc_attr($instance[$new_taxonomy]) : '';
					$type_taxonomy = 'type_taxonomy_' . $taxonomy;
					$$type_taxonomy = ( $instance[$type_taxonomy] != '' ) ? esc_attr($instance[$type_taxonomy]) : '';
				}
			}
		}

		$error = 'no';

		if ( (!is_user_logged_in()) && ($allow_guests == 'yes') ) {
			$guest_info = get_userdata($guest_account);
			$qpw_user_id = $guest_info->ID;
			$qpw_user_login = $guest_info->user_login;
			if ( $guest_info->user_level >= 5 ) {
				$qpw_user_can_manage_categories = 'yes';
				$qpw_user_can_publish_posts = 'yes';
			} elseif ( $guest_info->user_level >= 2 ) { 	
				$qpw_user_can_manage_categories = '';
				$qpw_user_can_publish_posts = 'yes';
			} elseif ( $guest_info->user_level == 1 ) { 	
				$qpw_user_can_manage_categories = '';
				$qpw_user_can_publish_posts = 'yes';
				$publish_status = 'pending';
				$disable_media_upload = 'yes';
			}
			if ( $guest_info->rich_editing == 'true' )
				$qpw_user_rich_editing = 'yes';
		} else {
			global $current_user;
			get_currentuserinfo();
			$qpw_user_id = $current_user->ID;
			$qpw_user_login = $current_user->user_login;
			if ( current_user_can('publish_posts') )
				$qpw_user_can_publish_posts = 'yes';
			if ( current_user_can('manage_categories') )
				$qpw_user_can_manage_categories = 'yes';
			if ( $current_user->rich_editing == 'true' )
				$qpw_user_rich_editing = 'yes';
		}

		if ( $qpw_user_can_publish_posts == 'yes' ) {

			if ( ($qpw_user_can_manage_categories == 'yes') && ($disable_new_cat != 'yes') )
				require_once(WP_PLUGIN_DIR . '/../../wp-admin/includes/taxonomy.php');

			require_once(WP_PLUGIN_DIR . '/../../wp-admin/includes/template.php');

			echo $before_widget;

			echo $before_title . $title . $after_title;

			if ( $before_title == '<!-- quick_post_page -->' ) {
				$quick_post_placement = 'page';
				if ( ($disable_editor != 'yes') && ($editor_type == 'inline') ) {
					$post_content_style = 'visibility: hidden; height: 409px;';
				}
			} else {
				$quick_post_placement = 'sidebar';
				$editor_type = 'popup';
			}

			if ($_SESSION['qpw_visibility'] == '') $_SESSION['qpw_visibility'] = $initial_visibility;

			if ( ($show_text != '') && ($hide_text != '') ) {
				if ($_SESSION['qpw_visibility'] == 'hide') { ?>
					<a href="javascript: void(0)" class="showhide_text"><?php echo $show_text; ?></a>
					<div id="quick_post_form" class="showhide_div" style="display:none; <?php echo $widget_style; ?>">
				<?php } else { ?>
					<a href="javascript: void(0)" class="showhide_text"><?php echo $hide_text; ?></a>
					<div id="quick_post_form" class="showhide_div" style="<?php echo $widget_style; ?>">
				<?php }
			} else { ?>
				<div id="quick_post_form" style="<?php echo $widget_style; ?>">
			<?php }

			$cats = get_categories('get=all&'. $inexclude . '='. $cat_list);
			if ((count($cats) == 1) && ($disable_new_cat == 'yes') && ($cat_default == $cats[0]->term_id))
				$showhide_categories = 'hide';
			else
				$showhide_categories = 'show';
			?>

				<form autocomplete="off" method="post" name=quickpostwidget action="" >

					<p><label for="quick_post_title" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $post_title_label; ?></label>
					<br />
					<input type="text" name="quick_post_title" id="quick_post_title" style="<?php echo $post_title_style; ?>" value="<?php if ($_POST['quick_post_title'] != '') echo $_POST['quick_post_title']; else echo '<' . $post_title_default . '>'; ?>"<?php if ( $post_title_default != '' ) { ?> onblur="if(this.value=='') this.value='<?php echo '<' . $post_title_default . '>'; ?>'; return false;" onfocus="if(this.value=='<?php echo '<' . $post_title_default . '>'; ?>') this.value=''; return false;"<?php } ?> /></p>
					<p><label for="quick_post_content" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $post_content_label; ?></label>
					<br />
					<textarea name="quick_post_content" rows="3" id="quick_post_content" style="<?php echo $post_content_style; ?>" <?php if ( $post_content_default != '' ) { ?> onblur="if(this.value=='') this.value='<?php echo '<' . $post_content_default . '>'; ?>'; return false;" onfocus="if(this.value=='<?php echo '<' . $post_content_default . '>'; ?>') this.value=''; return false;"<?php } ?> ><?php if (stripslashes($_POST['quick_post_content']) != '') echo stripslashes($_POST['quick_post_content']); else echo '<' . $post_content_default . '>'; ?></textarea></p>
					<?php

					if ( ($disable_editor != 'yes') && ($qpw_user_rich_editing == 'yes') ) {
						if ($shared_upload_dirs == 'yes')
							$_SESSION['upath'] = str_replace('//', '/', qpw_upload_dir() . '/shared');
						else
							$_SESSION['upath'] = str_replace('//', '/', qpw_upload_dir() . '/' . str_replace(' ', '_', $qpw_user_login));
						if ( $editor_type == 'popup' ) {
							?><p>
							<input type='button' id="quick_post_load" style="<?php echo $button_style; ?>" value="<?php echo $editor_label; ?>" title="<?php echo $editor_label; ?>" />
							</p><?php
						}
					}

					if ($show_excerpt != 'hide') { ?>
						<p><label for="quick_post_excerpt" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $post_excerpt_label; ?></label>
						<br />
						<textarea name="quick_post_excerpt" rows="3" id="quick_post_excerpt" style="<?php echo $post_excerpt_style; ?>"></textarea></p>
					<?php
					}

					if ( $quick_post_placement == 'page' )
						echo '<div id="quick_post_page" >';
					
					if ($show_tags != 'hide') { ?>
						<label for="quick_post_tags" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $post_tags_label; ?></label>
						<br />
						<input type="text" name="quick_post_tags" id="quick_post_tags" style="<?php echo $post_tags_style; ?>" value="<?php echo $_POST['quick_post_tags'] ?>" />
						<select id="quick_post_tag_list" onChange="if (this.options[this.selectedIndex].text != '<?php echo $post_tag_list_default; ?>') document.getElementById('quick_post_tags').value=ltrim(document.getElementById('quick_post_tags').value + ',' + this.options[this.selectedIndex].text,','); ">
						<?php
						echo "<option>".$post_tag_list_default."</option>\n";
						foreach (get_tags('hide_empty=0') as $tag)
						{
							echo "<option value=\"";
							echo $tag->term_id;
							echo "\">" . $tag->name . "</option>\n";
						} ?>
						</select>
						<?php
					}

					if ($showhide_categories == 'show') { ?>
						<span>
						<?php if ( ($qpw_user_can_manage_categories == 'yes') && ($disable_new_cat != 'yes') ) { ?>
							<input type=radio name="quick_post_rb" class="quick_post_rb" autocomplete="off" value="existing" style="<?php echo $rb_style; ?>" <?php if ($_POST['quick_post_rb'] != 'new') echo 'checked' ?> onclick="disableIt('quick_post_new_cat',true); disableIt('quick_post_new_cat_parent',true); <?php if ($cat_list_type == 'droplist') echo 'disableIt(\'quick_post_cat\',false);'; else echo 'disableIt(\'quick_post_cat_checklist\', false);'; ?>" />
						<?php } ?>

						<label for="quick_post_cat" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $category_label; ?></label>
						<br />
						<?php if ($cat_list_type == 'droplist')
							wp_dropdown_categories('hide_empty=0&name=quick_post_cat&hierarchical=1&selected=' . ((isset($_POST['quick_post_cat'])) ? $_POST['quick_post_cat'] : $cat_default) . '&' . $inexclude . '=' . $cat_list . '&orderby=' . $cat_order);
						?>
						</span>

						<?php
						if ($cat_list_type == 'checklist') { ?>
							<div id="quick_post_cat_checklist" style="<?php echo $cat_checklist_style; ?>">
								<ul id="cats">
									<?php qpw_category_checklist((($_POST) ? $_POST['post_category'] : explode(',', $cat_default)), $inexclude, $cat_list, $cat_order );?>
								</ul>
							</div>
						<?php
						}
					}

					if ( ($qpw_user_can_manage_categories == 'yes') && ($disable_new_cat != 'yes') ) { ?>
						<p>
						<input type=radio name="quick_post_rb" class="quick_post_rb" value="new" autocomplete="off" style="<?php echo $rb_style; ?>" <?php if ($_POST['quick_post_rb'] == 'new') echo 'checked' ?> onclick="disableIt('quick_post_new_cat',false); disableIt('quick_post_new_cat_parent',false); <?php if ($cat_list_type == 'droplist') echo 'disableIt(\'quick_post_cat\',true);'; else echo 'disableIt(\'quick_post_cat_checklist\', true);'; ?>" />
						<label for="quick_post_new_cat" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $new_cat_label; ?></label> <br />
						<input type="text" name="quick_post_new_cat" id="quick_post_new_cat" style="<?php echo $new_cat_style; ?>" value="<?php echo $_POST['quick_post_new_cat'] ?>" <?php if ($_POST['quick_post_rb'] != 'new') echo 'disabled' ?> />

						<?php wp_dropdown_categories('hide_empty=0&name=quick_post_new_cat_parent&hierarchical=1&show_option_none=' . $post_cat_parent_default . '&selected=' . $_POST['quick_post_new_cat_parent'] . '&' . $inexclude . '=' . $cat_list . '&orderby=' . $cat_order);?>

						</p>
					<?php }

					if ($show_post_date != 'hide') { ?>
						<label for="quick_post_date" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $post_date_label; ?></label>
						<br />
						<input type="text" name="quick_post_date" id="quick_post_date" style="<?php echo $post_date_style; ?>" value="<?php echo $_POST['quick_post_date']; ?>">
						<?php
					}

					for ($cfn = 1; $cfn <= $qpw_custom_fields_count; $cfn++) {
						$customfield = 'customfield_' . $cfn;
						$label_customfield = 'label_customfield_' . $cfn;
						if ( ($$customfield != '') && ($$label_customfield != '') ) {
							?>
							<label for="<?php echo $customfield; ?>" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $$label_customfield; ?></label><br />
							<input type="text" class="quick_post_custom_field" name="<?php echo $customfield; ?>" id="<?php echo $customfield; ?>" style="<?php echo $post_custom_field_style; ?>" value="<?php echo $_POST[$customfield]; ?>" /></p>
							<?php
						}
					}

					if ($taxonomy_list != '') {
						$taxonomies=explode(',' , $taxonomy_list); 
						foreach ($taxonomies as $taxonomy) {
							if (taxonomy_exists($taxonomy)) {
								$the_tax = get_taxonomy($taxonomy);
								echo '<label for="quick_post_' . $taxonomy . '_box" class="quick_post_label" style="' . $label_style . '">' . $the_tax->labels->name . '</label><br />';
								echo '<div id="quick_post_' . $taxonomy . '_box" class="quick_post_taxonomy_box" style="' . $taxonomy_box_style . '">';
								$type_taxonomy = 'type_taxonomy_' . $taxonomy;
								if ( $$type_taxonomy == 'checklist' ) {
									echo '<div id="quick_post_' . $taxonomy . '_checklist" class="quick_post_taxonomy_checklist">';
									echo '<ul class="cats">';
									qpw_category_checklist($_POST['tax_input'][$taxonomy], '', '', 'name', $taxonomy);
									echo '</ul>';
									echo '</div>';
								} else {
									wp_dropdown_categories('taxonomy=' . $taxonomy . '&hide_empty=0&class=quick_post_taxonomy_droplist&name=quick_post_' . $taxonomy . '_droplist&hierarchical=1&show_option_none=' . __('- Select -') . '&selected=' . $_POST['quick_post_' . $taxonomy . '_droplist'] . '&orderby=name');
								}
								$new_taxonomy = 'new_taxonomy_' . $taxonomy;
								if ( ($qpw_user_can_manage_categories == 'yes') && ($$new_taxonomy == 'yes') ) { ?>
									<label for="quick_post_new_<?php echo $taxonomy; ?>" class="quick_post_label" style="<?php echo $label_style; ?>"><?php _e('New', 'quick-post-widget'); echo ':'; ?></label>
									<br />
									<input type="text" name="quick_post_new_<?php echo $taxonomy;?>" id="quick_post_new_<?php echo $taxonomy;?>" class="quick_post_new_taxonomy" style="<?php echo $new_cat_style; ?>" value="<?php echo $_POST['quick_post_new_'. $taxonomy] ?>" />
									<?php
									if ($the_tax->hierarchical) {
										wp_dropdown_categories('taxonomy=' . $taxonomy . '&hide_empty=0&class=quick_post_taxonomy_droplist&name=quick_post_new_' . $taxonomy . '_parent&hierarchical=1&show_option_none=' . $post_cat_parent_default . '&selected=' . $_POST['quick_post_new_' . $taxonomy . '_parent'] . '&orderby=name');
									}
								}
								echo '</div>';
							}
						}
					}

					if ($use_captcha == 'yes') { ?>
						<p><label for="quick_post_captcha_input" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $captcha_label; ?></label><br />
						<input id="quick_post_captcha_input" name="quick_post_captcha_input" type="text" style="<?php echo $captcha_input_style; ?>"/><br />
						<img id="quick_post_captcha_image" style="<?php echo $captcha_image_style; ?>" src=<?php echo $qpw_plugin_url . 'utils/captcha/CaptchaSecurityImages.php?characters=6' ?> /></p>
					<?php } ?>

					<p><input type="submit" id="quick_post_submit" style="<?php echo $button_style; ?>" value="<?php echo $submit_label; ?>" title="<?php echo $submit_label; ?>" /></p>
					<input type="hidden" id="quick_post_tinymce_path" value="<?php echo get_bloginfo('wpurl') . '/wp-includes/js/tinymce'; ?>" />
					<input type="hidden" id="quick_post_plugin_path" value="<?php echo $qpw_plugin_url ?>" />
					<input type="hidden" id="quick_post_plugins" value="<?php if ($disable_plugins != 'yes') echo 'safari,paste,spellchecker,-media,-preview,-advlink,-advimage,-emotions,-searchreplace,-inlinepopups,-style'; else echo ''; ?>" />
					<input type="hidden" id="quick_post_buttons1" value="<?php if ($disable_plugins != 'yes') echo 'preview,|,spellchecker,|,bold,italic,strikethrough,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,backcolor,|,fontselect,|,fontsizeselect'; else echo 'bold,italic,strikethrough,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,backcolor,|,fontselect,|,fontsizeselect'; ?>" />
					<input type="hidden" id="quick_post_buttons2" value="<?php if ($disable_plugins != 'yes') echo 'pastetext,pasteword,selectall,|,outdent,indent,|,bullist,numlist,|,undo,redo,|,media,link,unlink,image,charmap,|,search,replace,|,emotions,|,styleprops,removeformat,cleanup,code'; else echo 'outdent,indent,|,bullist,numlist,|,undo,redo,|,link,unlink,image,charmap,|,removeformat,cleanup,code'; ?>" />
					<input type="hidden" id="quick_post_newlines" value="<?php echo $new_lines; ?>" />
					<input type="hidden" id="quick_post_file_manager" value="<?php if ($disable_media_upload != 'yes') echo 'tinyBrowser'; else echo ''; ?>" />
					<input type="hidden" id="quick_post_language" value="<?php echo $qpw_locale; ?>" />
					<input type="hidden" id="quick_post_ok" value="<?php _e('OK', 'quick-post-widget') ?>" />
					<input type="hidden" id="quick_post_cancel" value="<?php _e('Cancel', 'quick-post-widget') ?>" />
					<input type="hidden" id="qpw_visibility" value="<?php echo $_SESSION['qpw_visibility'] ?>" />
					<input type="hidden" id="quick_post_show_text" value="<?php echo $show_text ?>" />
					<input type="hidden" id="quick_post_hide_text" value="<?php echo $hide_text ?>" />
					<input type="hidden" id="quick_post_redirect_to" value="<?php echo $redirect_to ?>" />
					<input type="hidden" id="quick_post_editor_title" value="<?php echo $editor_title ?>" />
					<input type="hidden" id="quick_post_directionality" value="<?php echo get_bloginfo('text_direction') ?>" />
					<input type="hidden" id="editor_type" value="<?php echo $editor_type ?>" />
					<input type="hidden" id="quick_post_content_value" value="<?php echo (stripslashes($_POST['quick_post_content'])); ?>" />
					<input type="hidden" id="post_content_default" value="<?php echo $post_content_default; ?>" />
					<input type="hidden" id="quick_post_posted" value="<?php if ($_POST) echo 'true'; else echo 'false'; ?>" />
					<input type="hidden" id="quick_post_error_color" value="<?php echo $error_color; ?>" />
					<input type="hidden" id="quick_post_disable_editor" value="<?php echo $disable_editor; ?>" />
					<?php if ( $quick_post_placement == 'page' ) echo '</div>'; ?>
				</form>
			</div>

			<?php if ($_POST['quick_post_rb'] == 'new') { ?>
				<script type="text/javascript">
					<?php if ($cat_list_type == 'droplist') echo 'disableIt(\'quick_post_cat\',true);'; else echo 'disableIt(\'quick_post_cat_checklist\', true);'; ?>
				</script>	
			<?php } else { ?>
				<script type="text/javascript">
					<?php if ($cat_list_type != 'droplist') echo 'disableIt(\'quick_post_cat_checklist\', false);'; ?>
				</script> 
				<?php if ( ($qpw_user_can_manage_categories == 'yes') && ($disable_new_cat != 'yes') ) { ?>
					<script type="text/javascript">
						disableIt('quick_post_new_cat_parent',true);
					</script>	
				<?php }
			}

			echo $after_widget;

			if ($_POST) {

				if ($_POST['quick_post_title'] == '<' . $post_title_default . '>') {
					$error = 'yes'; ?>
					<script type="text/javascript">
						document.getElementById('quick_post_title').style.border="solid 1px <?php echo $error_color; ?>";
					</script> <?php
				}

				if ( ($editor_type == 'popup') && ($_POST['quick_post_content'] == '<' . $post_content_default . '>') ) {
					$error = 'yes'; ?>
					<script type="text/javascript">
						document.getElementById('quick_post_content').style.border="solid 1px <?php echo $error_color; ?>";
					</script> <?php
				}

				if ( ($editor_type == 'inline') && strpos($_POST['quick_post_content'], '&lt;' . $post_content_default . '&gt;') ) {
					$error = 'yes';
				}

				if ( ($show_excerpt == 'required') && ($_POST['quick_post_excerpt'] == '') ) {
					$error = 'yes'; ?>
					<script type="text/javascript">
						document.getElementById('quick_post_excerpt').style.border="solid 1px <?php echo $error_color; ?>";
					</script> <?php
				}

				if ( ($show_tags == 'required') && ($_POST['quick_post_tags'] == '') ) {
					$error = 'yes'; ?>
					<script type="text/javascript">
						document.getElementById('quick_post_tags').style.border="solid 1px <?php echo $error_color; ?>";
					</script> <?php
				}

				if ( !qpw_isValidDateTime($_POST['quick_post_date']) || (($show_post_date == 'required') && ($_POST['quick_post_date'] == '')) ) {
					$error = 'yes'; ?>
					<script type="text/javascript">
						document.getElementById('quick_post_date').style.border="solid 1px <?php echo $error_color; ?>";
					</script> <?php
				}

				for ($cfn = 1; $cfn <= $qpw_custom_fields_count; $cfn++) {
					$customfield = 'customfield_' . $cfn;
					$req_customfield = 'req_customfield_' . $cfn;
					if ( ($$customfield != '') && ($_POST[$customfield] == '') && ($$req_customfield == 'yes') ) {
						$error = 'yes'; ?>
						<script type="text/javascript">
							document.getElementById(<?php echo '\'' . $customfield . '\''; ?>).style.border="solid 1px <?php echo $error_color; ?>";
						</script> <?php
					}
				}

				if ($taxonomy_list != '') {
					$taxonomies=explode(',' , $taxonomy_list); 
					foreach ($taxonomies as $taxonomy) {
						$req_taxonomy = 'req_taxonomy_' . $taxonomy;
						$type_taxonomy = 'type_taxonomy_' . $taxonomy;
						if ( ($$req_taxonomy == 'yes') && ((($$type_taxonomy == 'checklist') && (count($_POST['tax_input'][$taxonomy]) == 0)) || (($$type_taxonomy != 'checklist') && ($_POST['quick_post_' . $taxonomy . '_droplist'] == -1))) && ($_POST['quick_post_new_' . $taxonomy] == '') ) {
							$error = 'yes'; ?>
							<script type="text/javascript">
								document.getElementById(<?php echo '\'quick_post_' . $taxonomy . '_box\''; ?>).style.border="solid 1px <?php echo $error_color; ?>";	
							</script> <?php
						}
					}
				}
				
				if (($_POST['quick_post_rb'] == 'new') && ($_POST['quick_post_new_cat'] == '')) {
					$error = 'yes'; ?>
					<script type="text/javascript">
						document.getElementById('quick_post_new_cat').style.border="solid 1px <?php echo $error_color; ?>";
					</script> <?php
				}

				if (($_POST['quick_post_rb'] != 'new') && ($cat_list_type == 'checklist') && ($showhide_categories == 'show') && (count($_POST['post_category']) == 0) ) {
					$error = 'yes'; ?>
					<script type="text/javascript">
						document.getElementById('quick_post_cat_checklist').style.border="solid 1px <?php echo $error_color; ?>";
					</script> <?php
				}

				if( ($_SESSION['security_code'] == $_POST['quick_post_captcha_input'] && !empty($_SESSION['security_code'] )) || $use_captcha != 'yes' ) {
					unset($_SESSION['security_code']);
				} else {
					$error = 'yes'; ?>
					<script type="text/javascript">
						document.getElementById('quick_post_captcha_input').style.border="solid 1px <?php echo $error_color; ?>";
					</script> <?php
				}

				if ($error == 'no') {

					if ($_POST['quick_post_rb'] == 'new') {
						if ($_POST['quick_post_new_cat'] != '') {
							$cat_id = get_cat_ID($_POST['quick_post_new_cat']);
							if ($cat_id == 0) {
								if ($_POST['quick_post_new_cat_parent'] == -1 ) {
									$cat_id = wp_create_category($_POST['quick_post_new_cat']);
								} else {
									$cat_id = wp_create_category($_POST['quick_post_new_cat'], $_POST['quick_post_new_cat_parent']);
								}
							}
							$_cats = array($cat_id);
						}
					} else {
						if ($cat_list_type == 'droplist') {
							$cat_id = $_POST['quick_post_cat'];
							if (!isset($cat_id)) $cat_id = $cat_default;
							$_cats = array($cat_id);
						} else {
							if (!isset($_POST['post_category']))
								$_cats = array($cat_default);
							else
								$_cats = $_POST['post_category'];
						}
					}

					$post_id = wp_insert_post( array(
						'post_author'		=> $qpw_user_id,
						'post_title'		=> $_POST['quick_post_title'],
						'post_content'		=> $top_content_code . $_POST['quick_post_content'] . $bottom_content_code,
						'post_type'			=> $post_type,
						'tags_input'		=> $_POST['quick_post_tags'],
						'post_category'		=> $_cats,
						'post_status'		=> $publish_status,
						'post_excerpt'		=> $_POST['quick_post_excerpt'],
						'post_date'			=> $_POST['quick_post_date'],
						'post_date_gmt'		=> get_gmt_from_date($_POST['quick_post_date'])
					) );

					if ($post_id > 0) {

						for ($cfn = 1; $cfn <= $qpw_custom_fields_count; $cfn++) {
							$customfield = 'customfield_' . $cfn;
							if ( $$customfield != '' ) {
								update_post_meta($post_id, $$customfield, $_POST[$customfield]);
							}
						}

						if ($taxonomy_list != '') {
							$taxonomies=explode(',' , $taxonomy_list); 
							foreach ($taxonomies as $taxonomy) {
								$type_taxonomy = 'type_taxonomy_' . $taxonomy;
								if ( $$type_taxonomy == 'checklist' ) {
									if ( count($_POST['tax_input'][$taxonomy]) != 0 )  {
										wp_set_object_terms( $post_id, array_map('intval', $_POST['tax_input'][$taxonomy]), $taxonomy, true );
									}
								} else {
									$term = get_term_by('id', $_POST['quick_post_' . $taxonomy . '_droplist'], $taxonomy);
									wp_set_object_terms( $post_id, $term->name, $taxonomy, true );
								}
								if ( $_POST['quick_post_new_' . $taxonomy] != '' ) {
									if ( ($_POST['quick_post_new_' . $taxonomy . '_parent'] == -1) || !isset($_POST['quick_post_new_' . $taxonomy . '_parent']) ) {
										wp_insert_term ($_POST['quick_post_new_' . $taxonomy], $taxonomy);
									} else {
										wp_insert_term ($_POST['quick_post_new_' . $taxonomy], $taxonomy, array('parent'=> $_POST['quick_post_new_' . $taxonomy . '_parent']));
									}
									wp_set_object_terms( $post_id, $_POST['quick_post_new_' . $taxonomy], $taxonomy, true);
								}
							}
						}
						
						if ($post_confirmation == '') {
						?>
							<script type="text/javascript">
								location.href = "<?php echo $redirect_to; ?>"
							</script>	
						<?php
						} else { ?>
							<div id="quick_post_success" title="<?php echo $message_title; ?>"> 
								<p><?php echo $post_confirmation; ?></p> 
							</div>
					<?php }
						if ( (!is_user_logged_in() && ($post_mail == 'guest_posts')) || ($post_mail == 'all_posts') ) {
							qpw_sendmail ($post_id, $_POST['quick_post_title']);
						}
					} else {
						if ( ($post_fail_message != '') ) { ?>
							<div id="quick_post_fail" title="<?php echo $message_title; ?>"> 
								<p><?php echo $post_fail_message; ?></p> 
							</div>
						<?php
						}
					}
				}
			}
		}
	}
}

add_action('widgets_init', create_function('', 'return register_widget("Quick_Post_Widget");'));
add_action('init', 'qpw_init');
load_plugin_textdomain('quick-post-widget', 'wp-content/plugins/quick-post-widget/langs');
?>