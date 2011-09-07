<?php
class UN_Settings {
	public function __construct(){
		add_action('admin_init', array(&$this,'action_admin_init'));
		add_action('admin_menu', array(&$this, 'action_admin_menu'));
		add_action('admin_enqueue_scripts', array(&$this, 'action_admin_enqueue_scripts'));
	}
	
	public function action_admin_enqueue_scripts($hook){
		if ($hook != 'settings_page_usernoise')
			return;
		wp_enqueue_style('farbtastic');
		wp_enqueue_script('farbtastic');
		wp_enqueue_script('usernoise-settings', usernoise_url('/js/settings.js'), 'jquery');
	}
	
	public function action_admin_menu(){
		add_options_page(__('Usernoise'), __('Usernoise'), 'manage_options', 'usernoise', 
			array(&$this, 'render'));
	}
	
	public function render(){
		?>
		<div class="wrap">
			<h2><?php _e('Usernoise settings', 'usernoise')?></h2>
			<form action="options.php" method="post">
				<?php settings_fields('usernoise') ?>
				<?php do_settings_sections('usernoise') ?>
				<?php if (function_exists('submit_button')): ?>
				<?php submit_button() ?>
				<?php else: ?>
					<p class="submit"><input type="submit" name="submit" valeu="<?php _e('Save Changes') ?>"></p>
				<?php endif ?>
			</form>
		</div>
		<?php
	}
	
	public function action_admin_init(){
		register_setting('usernoise', UN_FEEDBACK_BUTTON_TEXT, 'stripslashes');
		register_setting('usernoise', UN_FEEDBACK_BUTTON_COLOR, 'stripslashes');
		register_setting('usernoise', UN_FEEDBACK_BUTTON_TEXT_COLOR, 'stripslashes');
		register_setting('usernoise', UN_FEEDBACK_BUTTON_SHOW_BORDER, 'stripslashes');
		register_setting('usernoise', UN_FEEDBACK_FORM_TITLE, 'stripslashes');
		register_setting('usernoise', UN_FEEDBACK_FORM_TEXT, 'stripslashes');
		register_setting('usernoise', UN_FEEDBACK_FORM_SHOW_SUMMARY);
		register_setting('usernoise', UN_FEEDBACK_FORM_SHOW_TYPE);
		register_setting('usernoise', UN_FEEDBACK_FORM_SHOW_EMAIL);
		register_setting('usernoise', UN_THANKYOU_TITLE, 'stripslashes');
		register_setting('usernoise', UN_THANKYOU_TEXT, 'stripslashes');
		register_setting('usernoise', UN_USE_FONT, 'stripslashes');
		register_setting('usernoise', UN_ADMIN_NOTIFY_ON_FEEDBACK, 'stripslashes');
		register_setting('usernoise', UN_SUBMIT_FEEDBACK_BUTTON_TEXT, 'stripslashes');
		register_setting('usernoise', UN_FEEDBACK_BUTTON_POSITION, 'stripslashes');
		register_setting('usernoise', UN_SHOW_POWERED_BY, 'stripslashes');
		register_setting('usernoise', UN_DISABLE_ON_MOBILES, 'stripslashes');
		add_settings_section('general', __('General', 'usernoise'), '__return_false', 'usernoise');
		
		add_settings_field(UN_SHOW_POWERED_BY, __('Show Powered by', 'usernoise'), 
			array(&$this, 'un_checkbox_setting'), 'usernoise', 'general', 
			array('name' => UN_SHOW_POWERED_BY, 
				'value' => get_option(UN_SHOW_POWERED_BY),
				'after' => __('I like Usernoise and want to show <em>"Powered by Usernoise"</em>  link at the modal window.', 'usernoise')));
		$fonts = apply_filters('un_fonts', array('Helvetica Neue', 'Helvetica', 'Verdana', 'Tahoma', 'Arial', 'Georgia', 'Palatino'));
		sort($fonts);
		add_settings_field(UN_USE_FONT, __('Font', 'usernoise'), 
			array(&$this, 'un_select_setting'), 'usernoise', 'general', 
			array('name' => UN_USE_FONT, 
				'value' => get_option(UN_USE_FONT), 'class' => 'regular-text code', 
				'values' => array2options($fonts),
				'empty' => __('Site default font', 'usernoise')));
		add_settings_section('button', __('Button', 'usernoise'), '__return_false', 'usernoise');
		add_settings_field(UN_FEEDBACK_BUTTON_COLOR, __('Background color', 'usernoise'), 
			array(&$this, 'un_color_setting'), 'usernoise', 'button', 
			array('name' => UN_FEEDBACK_BUTTON_COLOR, 
				'value' => get_option(UN_FEEDBACK_BUTTON_COLOR), 'class' => 'regular-text code'));
		add_settings_field(UN_FEEDBACK_BUTTON_TEXT, __('Text', 'usernoise'), 
			array(&$this, 'un_text_setting'), 'usernoise', 'button', 
			array('name' => UN_FEEDBACK_BUTTON_TEXT, 
				'value' => get_option(UN_FEEDBACK_BUTTON_TEXT), 'class' => 'regular-text code'));
		add_settings_field(UN_FEEDBACK_BUTTON_TEXT_COLOR, __('Text color', 'usernoise'), 
			array(&$this, 'un_color_setting'), 'usernoise', 'button', 
			array('name' => UN_FEEDBACK_BUTTON_TEXT_COLOR, 
				'value' => get_option(UN_FEEDBACK_BUTTON_TEXT_COLOR), 'class' => 'regular-text code'));
		$positions = array('left' => __('Left'), 'right' => __('Right'), 'top' => __('Top'), 
			'bottom' => __('Bottom'));
		add_settings_field(UN_FEEDBACK_BUTTON_SHOW_BORDER, __('Show border', 'usernoise'), 
			array(&$this, 'un_checkbox_setting'), 'usernoise', 'button', 
			array('name' => UN_FEEDBACK_BUTTON_SHOW_BORDER, 
				'value' => get_option(UN_FEEDBACK_BUTTON_SHOW_BORDER)));
		add_settings_field(UN_FEEDBACK_BUTTON_POSITION, __('Position', 'usernoise'), 
			array(&$this, 'un_select_setting'), 'usernoise', 'button', 
			array('name' => UN_FEEDBACK_BUTTON_POSITION, 
				'value' => get_option(UN_FEEDBACK_BUTTON_POSITION), 'class' => 'regular-text code', 
				'values' => hash2options($positions)));
		add_settings_field(UN_DISABLE_ON_MOBILES, __('Hide on mobile devices', 'usernoise'), 
			array(&$this, 'un_checkbox_setting'), 'usernoise', 'button', 
			array('name' => UN_DISABLE_ON_MOBILES, 
				'value' => get_option(UN_DISABLE_ON_MOBILES)));
		add_settings_section('feedback_form', __('Feedback form', 'usernoise'), '__return_false', 'usernoise');
		add_settings_field(UN_FEEDBACK_FORM_TITLE, __('Form title', 'usernoise'), 
			array(&$this, 'un_text_setting'), 'usernoise', 'feedback_form', 
			array('name' => UN_FEEDBACK_FORM_TITLE, 
				'value' => get_option(UN_FEEDBACK_FORM_TITLE), 'class' => 'regular-text code'));
		add_settings_field(UN_FEEDBACK_FORM_TEXT, __('Introductional text', 'usernoise'), 
			array(&$this, 'un_textarea_setting'), 'usernoise', 'feedback_form', 
			array('name' => UN_FEEDBACK_FORM_TEXT, 
				'value' => get_option(UN_FEEDBACK_FORM_TEXT), 'class' => 'regular-text code'));
		add_settings_field(UN_FEEDBACK_FORM_SHOW_TYPE, __('Ask for feedback type', 'usernoise'), 
			array(&$this, 'un_checkbox_setting'), 'usernoise', 'feedback_form', 
			array('name' => UN_FEEDBACK_FORM_SHOW_TYPE, 
				'value' => get_option(UN_FEEDBACK_FORM_SHOW_TYPE)));
		add_settings_field(UN_FEEDBACK_FORM_SHOW_SUMMARY, __('Ask for a summary', 'usernoise'), 
			array(&$this, 'un_checkbox_setting'), 'usernoise', 'feedback_form', 
			array('name' => UN_FEEDBACK_FORM_SHOW_SUMMARY, 
				'value' => get_option(UN_FEEDBACK_FORM_SHOW_SUMMARY)));
		add_settings_field(UN_FEEDBACK_FORM_SHOW_EMAIL, __('Ask for an email', 'usernoise'), 
			array(&$this, 'un_checkbox_setting'), 'usernoise', 'feedback_form', 
			array('name' => UN_FEEDBACK_FORM_SHOW_EMAIL, 
				'value' => get_option(UN_FEEDBACK_FORM_SHOW_EMAIL)));
		add_settings_field(UN_SUBMIT_FEEDBACK_BUTTON_TEXT, __('Submit button text', 'usernoise'), 
			array(&$this, 'un_text_setting'), 'usernoise', 'feedback_form', 
			array('name' => UN_SUBMIT_FEEDBACK_BUTTON_TEXT, 
				'value' => get_option(UN_SUBMIT_FEEDBACK_BUTTON_TEXT), 'class' => 'regular-text code'));
		add_settings_section('thankyou_screen', __('Thank you screen', 'usernoise'), '__return_false', 'usernoise');
		add_settings_field(UN_THANKYOU_TITLE, __('Thank you screen title', 'usernoise'), 
			array(&$this, 'un_text_setting'), 'usernoise', 'thankyou_screen', 
			array('name' => UN_THANKYOU_TITLE, 
				'value' => get_option(UN_THANKYOU_TITLE), 'class' => 'regular-text code'));
		add_settings_field(UN_THANKYOU_TEXT, __('Thank you text', 'usernoise'), 
			array(&$this, 'un_textarea_setting'), 'usernoise', 'thankyou_screen', 
			array('name' => UN_THANKYOU_TEXT, 
				'value' => get_option(UN_THANKYOU_TEXT), 'class' => 'regular-text code'));
		add_settings_section('email_notifications', __('Admin notifications', 'usernoise'), array(&$this, 'admin_notification_tagline'), 'usernoise');
		add_settings_field(UN_ADMIN_NOTIFY_ON_FEEDBACK, __('New feedback received', 'usernoise'), 
			array(&$this, 'un_checkbox_setting'), 'usernoise', 'email_notifications', 
			array('name' => UN_ADMIN_NOTIFY_ON_FEEDBACK, 
				'value' => get_option(UN_ADMIN_NOTIFY_ON_FEEDBACK)));
	}
	
	
	public function admin_notification_tagline(){
		echo "<p>" . __('Notification emails will be sent to:', 'usernoise') . '&nbsp;&nbsp;<a href="mailto:' .
		 get_option('admin_email') . '">' . get_option('admin_email') . "</a>&nbsp;&nbsp;(" .
		 __('you can change it at', 'usernoise') . " <a href=\"" . admin_url('options-general.php')  . '">' . __('General Options') . '</a> page).' . "</p>";
	}
	
	public function un_text_setting($options){
		text_field($options['name'], $options['value'], array('class' => $options['class']));
	}
	public function un_textarea_setting($options){
		textarea($options['name'], $options['value'], array('class' => 'large-text code', 'rows' => 10));
	}
	public function un_checkbox_setting($options){
		hidden_field($options['name'], '');
		checkbox($options['name'], 1, $options['value']);
		if (isset($options['after']) && $options['after'])
			echo " " . $options['after'];
	}
	
	public function un_select_setting($options){
		select($options['name'], $options['values'], $options['value'], array(), 
			isset($options['empty']) ? array('empty' => $options['empty']) : array());
	}
	
	public function un_color_setting($options) {
		?><div class="un-color-picker">
			<?php text_field($options['name'], $options['value']); ?>
			<input type="button" class="pickcolor button hide-if-no-js" value="<?php esc_attr_e( 'Select a Color'); ?>" />
			<div class="picker" id="picker-<?php echo $options['name'] ?>" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
		</div>
		<?php
	}
	
}
