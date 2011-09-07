<?php

class UN_Admin_Editor_Page{
	
	public function __construct(){
		add_action('admin_print_styles-post.php', array(&$this, 'action_print_styles'));
		add_action('add_meta_boxes_un_feedback', array(&$this, 'action_add_meta_boxes'));
		add_action('post_updated_messages', array(&$this, 'filter_post_updated_messages'));
		add_action('admin_enqueue_scripts', array(&$this, 'action_admin_enqueue_scripts'));
		add_action('wp_ajax_un_feedback_reply', array(&$this, 'action_feedback_reply'));
	}
	
	public function action_add_meta_boxes($post){
		global $post_new_file;
		if (isset($post_new_file)){
			$post_new_file = null;
		}
		remove_meta_box('submitdiv', FEEDBACK, 'side');
		add_meta_box('submitdiv', __('Publish'), array(&$this, 'post_submit_meta_box'), 
			FEEDBACK, 'side', 'default');
		$title = un_get_feedback_type_span($post->ID);
		add_meta_box('un-feedback-body', 
			$title . ($title ? ": " : '') . esc_html($post->post_title), 
			array(&$this, 'description_meta_box'),
			FEEDBACK);
		if (get_post_meta($post->ID, '_email', true) || get_post_meta($post->ID, '_author', true)){
			add_meta_box('un-feedback-reply', __('Reply'), array(&$this, 'reply_meta_box'), FEEDBACK);
		}
	}
	
	public function action_admin_enqueue_scripts($hook){
		global $post_type;
		if (!($post_type == FEEDBACK && $hook == 'post.php'))
			return;
		wp_enqueue_script('quicktags');
		wp_enqueue_script('un-editor-page', usernoise_url('/js/editor-page.js'));
	}
	
	public function action_feedback_reply(){
		$id = (int)$_REQUEST['id'];
		if (!current_user_can(get_post_type_object(FEEDBACK)->cap->edit_posts))
			_e('Hacking, huh?');
		$message = trim(stripslashes($_REQUEST['message']));
		if (!$message){
			_e('Please enter some message.');
			exit;
		}
		$email = get_post_meta($id, '_email', true);
		$author = get_post_meta($id, '_author', true);
		if (!$email){
			if ($user = get_userdata($author)){
				$email = $user->user_email;
			}
		}
		if (!$email){
			_e('Feedback author email is unknown.');
			exit;
		}
		wp_mail($email, stripslashes($_REQUEST['subject']), stripslashes($_REQUEST['message']),
			'Content-type: text/html');
		_e('Email sent successfully.');
		exit;
	}
	
	public function filter_post_updated_messages($messages){
		$messages[FEEDBACK][6] = __('Feedback was marked as reviewed');
		return $messages;
	}
	
	

	public function action_print_styles(){
		global $post_type;
		if ($post_type == FEEDBACK) {
				wp_enqueue_style('un-admin', usernoise_url('/css/admin.css'));
		}
	}
	
	public function reply_meta_box($post){
		require(usernoise_path('/html/reply-meta-box.php'));
	}
	
	public function description_meta_box($post){
		do_action('description_meta_box_top', &$post);
		if (un_feedback_has_author($post->ID)){
			echo "<h4>" . __('Author') . ': ';
			un_feedback_author_link($post->ID);
			echo "</h4><br>";
		}
			
		do_action('description_meta_box_before_content', &$post);
		echo nl2br(esc_html($post->post_content));
		do_action('description_meta_box_bottom', &$post);
	}
		
	public function post_submit_meta_box($post) {
		global $action;
		$post_type = $post->post_type;
		$post_type_object = get_post_type_object($post_type);
		$can_publish = current_user_can($post_type_object->cap->publish_posts);
		require(usernoise_path('/html/publish-meta-box.php'));
	}
}

