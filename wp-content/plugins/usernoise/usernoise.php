<?php
/*
Plugin Name: Usernoise
Plugin URI: mailto: hello@karevn.com
Description: Usernoise is a modal contact / feedback form with smooth interface.
Version: 0.4.1
Author: Nikolay Karev
Author URI: http://karevn.com
*/

load_plugin_textdomain('usernoise', false, basename(dirname(__FILE__)) . '/languages/');

define('FEEDBACK', 'un_feedback');
define('FEEDBACK_TYPE', 'feedback_type');
define('USERNOISE', 'usernoise');
define('USERNOISE_DIR', dirname(plugin_basename(__FILE__)));
define('UN_FEEDBACK_FORM_TITLE', 'un_feedback_form_title');
define('UN_USE_FONT', 'un_use_font');
define('UN_FEEDBACK_FORM_TEXT', 'un_feedback_form_text');
define('UN_FEEDBACK_BUTTON_TEXT', 'un_feedback_button_text');
define('UN_FEEDBACK_BUTTON_COLOR', 'un_feedback_button_color');
define('UN_FEEDBACK_BUTTON_POSITION', 'un_feedback_button_position');
define('UN_FEEDBACK_BUTTON_TEXT_COLOR', 'un_feedback_button_text_color');
define('UN_FEEDBACK_BUTTON_SHOW_BORDER', 'un_feedback_button_show_border');
define('UN_SUBMIT_FEEDBACK_BUTTON_TEXT', 'un_submit_feedback_button_text');
define('UN_FEEDBACK_FORM_SHOW_SUMMARY', 'un_feedback_form_show_summary');
define('UN_FEEDBACK_FORM_SHOW_TYPE', 'un_feedback_form_show_type');
define('UN_FEEDBACK_FORM_SHOW_EMAIL', 'un_feedback_form_show_email');
define('UN_SHOW_POWERED_BY', 'un_show_powered_by');
define('UN_ADMIN_NOTIFY_ON_FEEDBACK', 'un_admin_notify_on_feedback');
define('UN_THANKYOU_TITLE', 'un_thankyou_title');
define('UN_THANKYOU_TEXT', 'un_thankyou_text');
define('UN_DISABLE_ON_MOBILES', 'un_disable_on_mobiles');

define('UN_VERSION', '0.4.1');

require_once('vendor/html-helpers/html-helpers.php');
require_once('inc/model.php');
require_once('inc/template.php');
require_once('inc/migrations.php');
if (is_admin()){
	require_once('admin/editor-page.php');
	require_once('admin/menu.php');
	require_once('admin/feedback-list.php');
	require_once('admin/settings.php');
	require_once('admin/dashboard.php');
	if (defined('DOING_AJAX')){
		require_once('inc/controller.php');
	}
} else {
	require_once('admin/admin-bar.php');
	require_once('inc/integration.php');
	require_once('inc/controller.php');
}

if (file_exists(usernoise_path('/pro/pro.php'))){
	require_once(usernoise_path('/pro/pro.php'));
}

require('inc/globals.php');

if (file_exists(usernoise_path("/pro/inc/globals.php"))){
	require_once(usernoise_path("/pro/inc/globals.php"));
}

function un_get_default_options(){
	return array(
		UN_USE_FONT => '',
		UN_FEEDBACK_BUTTON_TEXT => _x('Feedback', 'button', 'usernoise'),
		UN_FEEDBACK_BUTTON_COLOR => '#404040',
		UN_FEEDBACK_BUTTON_TEXT_COLOR => '#FFFFFF',
		UN_FEEDBACK_BUTTON_POSITION => 'left',
		UN_FEEDBACK_FORM_TITLE => _x('Feedback', 'form', 'usernoise'),
		UN_FEEDBACK_FORM_TEXT => __('Please tell us what do you think, any kind of feedback is highly appreciated.', 'usernoise'),
		UN_FEEDBACK_FORM_SHOW_SUMMARY => '1',
		UN_FEEDBACK_FORM_SHOW_TYPE => '1',
		UN_FEEDBACK_FORM_SHOW_EMAIL => '1',
		UN_SUBMIT_FEEDBACK_BUTTON_TEXT => __('Submit feedback', 'usernoise'), 
		UN_THANKYOU_TITLE => __('Thank you', 'usernoise'),
		UN_THANKYOU_TEXT => __('Your feedback have been received.', 'usernoise'),
		UN_ADMIN_NOTIFY_ON_FEEDBACK => '1',
		UN_SUBMIT_FEEDBACK_BUTTON_TEXT => __('Submit feedback', 'usernoise'),
		UN_SHOW_POWERED_BY => '1',
		UN_FEEDBACK_BUTTON_SHOW_BORDER => '1',
		UN_DISABLE_ON_MOBILES => '0'
	);
}

function un_get_feedback_capabilities(){
	return array('edit_un_feedback_items', 'edit_un_feedback', 'delete_un_feedback', 
		'publish_un_feedback', 'publish_un_feedback_items', 
		'edit_others_un_feedback_items', 'edit_published_feedback');
}
function un_get_capable_roles(){
	return array('administrator', 'editor');
}

function un_activation_hook(){
	global $un_default_options;
	foreach(array(
		'idea' => __('Idea'), 'question' => __('Question', 'usernoise'), 'problem' => __('Problem', 'usernoise'),
		'praise' => __('Praise', 'usernoise')) as $type => $value){
		if (null == get_term_by('slug', $type, 'feedback_type')){
			wp_insert_term($value, FEEDBACK_TYPE, array('slug' => $type));
		}
	}
}

function un_deactivation_hook(){
	foreach(array_keys(un_get_default_options()) as $default_option){
		delete_option($default_option);
	}
	delete_option('un_version');
	global $wp_roles;
	if ( ! isset( $wp_roles ) )
		$wp_roles = new WP_Roles();
	foreach(un_get_capable_roles() as $role)
		foreach(un_get_feedback_capabilities() as $cap)
			$wp_roles->remove_cap($role, $cap);
}

register_deactivation_hook(__FILE__, 'un_deactivation_hook');
register_activation_hook(__FILE__, 'un_activation_hook');