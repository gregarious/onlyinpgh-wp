<?php

class UN_Integration {
	
	public function __construct(){
		if (!is_admin() && (!$this->is_mobile() || !get_option(UN_DISABLE_ON_MOBILES))){
			add_action('wp_head', array(&$this, 'action_init'), 1);
			add_action('wp_footer', array(&$this, 'action_wp_footer'));
		}
	}
	
	public function action_init(){
		wp_enqueue_style('facebox', usernoise_url('/vendor/facebox/facebox.css'));
		wp_enqueue_style('usernoise', usernoise_url('/css/usernoise.css'));
		wp_enqueue_style('usernoise-fixes', usernoise_url('/css/fixes.css'));
		wp_enqueue_script('facebox', usernoise_url('/vendor/facebox/facebox.js'), 'jquery', '1.3', true);
		wp_enqueue_script('usernoise', usernoise_url('/js/usernoise.js'), array('jquery', 'facebox'), '0.4', true);
		wp_localize_script('usernoise', 'usernoise', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'closeImageUrl' => usernoise_url("/vendor/facebox/closelabel.png"),
			'ajaxLoaderUrl' => usernoise_url("/images/loader.gif"),
			'buttonText' => get_option(UN_FEEDBACK_BUTTON_TEXT, __('Feedback', 'usernoise')),
			'buttonStyle' => sprintf("background-color: %s; color: %s", 
					get_option(UN_FEEDBACK_BUTTON_COLOR), get_option(UN_FEEDBACK_BUTTON_TEXT_COLOR)),
			'buttonClass' => implode(' ', un_button_class())
			));
		if (is_user_logged_in()){
			wp_enqueue_style('usernoise-adminbar', usernoise_url('/css/admin-bar.css'));
		}
	}
	
	public function is_mobile(){
		if (function_exists('bnc_wptouch_is_mobile')){
			return bnc_wptouch_is_mobile();
		}
		$useragents = apply_filters('un_mobile_agents', array(		
			"iPhone",  				 	// Apple iPhone
			"iPod", 						// Apple iPod touch
			"incognito", 				// Other iPhone browser
			"webmate", 				// Other iPhone browser
			"Android", 			 	// 1.5+ Android
			"dream", 				 	// Pre 1.5 Android
			"CUPCAKE", 			 	// 1.5+ Android
			"blackberry9500",	 	// Storm
			"blackberry9530",	 	// Storm
			"blackberry9520",	 	// Storm v2
			"blackberry9550",	 	// Storm v2
			"blackberry 9800",	// Torch
			"webOS",					// Palm Pre Experimental
			"s8000", 				 	// Samsung Dolphin browser
			"bada",				 		// Samsung Dolphin browser
			"Googlebot-Mobile"	// the Google mobile crawler
		));
		
		foreach($useragents as $agent){
			if (preg_match("/$agent/i", $_SERVER['HTTP_USER_AGENT']))
				return true;
		}
		return false;
	}
	
	public function action_wp_footer(){
		require(usernoise_path('/html/facebox.php'));
	}
	
}

