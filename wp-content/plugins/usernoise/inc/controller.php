<?php 
class UN_Controller {
	function __construct(){
		add_action('un_feedback_form', array(&$this, 'feedback_form'));
		add_action('wp_ajax_un_feedback_form_submit', array(&$this, 'form_submit'));
		add_action('wp_ajax_nopriv_un_feedback_form_submit', array(&$this, 'form_submit'));
		add_action('wp_ajax_nopriv_un_get_feedback_form', array(&$this, 'get_feedback_form'));
		add_action('wp_ajax_un_get_feedback_form', array(&$this, 'get_feedback_form'));
		add_action('un_feedback_form_body', array(&$this, 'action_un_feedback_form_body'), 100);
	}
	
	public function get_feedback_form(){
		require usernoise_path('/html/facebox.php');
		exit;
	}
	
	public function feedback_form(){
		require usernoise_path('/html/form-feedback.php');
	}
	
	public function action_un_feedback_form_body(){
		if (get_option(UN_SHOW_POWERED_BY)) require_once(usernoise_path('/html/powered-by.php'));
	}
	
	
	public function form_submit(){
		global $un_model;
		if (isset($_REQUEST['title']) && $_REQUEST['title'] == __('Short summary', 'usernoise'))
			$_REQUEST['title'] = '';
		if (isset($_REQUEST['description']) && $_REQUEST['description'] == __('Your feedback', 'usernoise'))
			$_REQUEST['description'] = '';
		if (isset($_REQUEST['email']) && $_REQUEST['email'] == __('Your email (will not be published)', 'usernoise'))
			$_REQUEST['email'] = '';

		$errors = $un_model->validate_feedback_form(stripslashes_deep($_REQUEST));
		if (empty($errors)){
			$un_model->create_feedback(stripslashes_deep($_REQUEST));
			echo json_encode(array('success' => true, 'html' => capture_html(usernoise_path('/html/thankyou.php'))));
		} else {
			echo json_encode(array('errors' => $errors));
		}
		exit;
	}
}

