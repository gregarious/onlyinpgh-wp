<div id="un-feedback-content">
	<a href="#" id="un-feedback-close"><img src="<?php echo usernoise_url("/vendor/facebox/closelabel.png") ?>" title="close" class="close_image" ></a>
	<div id="un-feedback-form-wrapper">
		<h2>
			<?php echo get_option(UN_FEEDBACK_FORM_TITLE) ?>
			<?php if (current_user_can('edit_others_posts')): ?>
				<a class="un-button-gray" id="un-button-settings" href="<?php echo admin_url('options-general.php?page=usernoise')?>">
					<?php echo strtolower(_('Settings')) ?></a>
			<?php endif ?>
		</h2>
		<p><?php echo get_option(UN_FEEDBACK_FORM_TEXT) ?></p>
		<?php do_action('un_fedback_form_before')?>
		<form data-action="<?php echo admin_url('admin-ajax.php') ?>?action=un_feedback_form_submit" method="post" id="un-feedback-form" class="un-feedback-form">
			<?php if (get_option(UN_FEEDBACK_FORM_SHOW_TYPE)): ?>
			<div id="un-types-wrapper" class="un-types-wrapper">
					<?php link_to(__('Idea', 'usernoise') . '<span class="selection"></span>', '#', array('id' => 'un-type-idea', 'class' => 'selected'))?>
					<?php link_to(__('Problem', 'usernoise') . '<span class="selection"></span>', '#', array('id' => 'un-type-problem'))?>
					<?php link_to(__('Question', 'usernoise') . '<span class="selection"></span>', '#', array('id' => 'un-type-question'))?>
					<?php link_to(__('Praise', 'usernoise') . '<span class="selection"></span>', '#', array('id' => 'un-type-praise'))?>
					<?php hidden_field('type', 'idea')?>
			</div>
			<?php endif ?>
			<?php textarea('description', __('Your feedback', 'usernoise'), array('id' => 'un-description', 'class' => 'text text-empty'))?>
			<?php if (get_option(UN_FEEDBACK_FORM_SHOW_SUMMARY)): ?>
				<?php text_field('title', __('Short summary', 'usernoise'), array('id' => 'un-title', 'class' => 'text text-empty'))?>
			<?php endif ?>
			<?php if (get_option(UN_FEEDBACK_FORM_SHOW_EMAIL)): ?>
				<?php text_field('email', __('Your email (will not be published)', 'usernoise'), array('id' => 'un-title', 'class' => 'text text-empty'))?>
			<?php endif ?>
			<?php do_action('un_feedback_form_body') ?>
			<input type="submit" class="submit" value="<?php echo esc_attr(un_submit_feedback_button_text()) ?>" id="un-feedback-submit">
			&nbsp;<img src="<?php echo usernoise_url('/images/loader.gif') ?>" id="un-feedback-loader" class="loader" style="display: none;">
			<div id="un-feedback-errors-wrapper" class="un-errors-wrapper" style="display: none;">
				<div id="un-feedback-errors" class="un-errors" ></div>
				<span id="feedback-errors-corner"></span>
			</div>
		</form>
		<?php do_action('un_feedback_form_after')?>
	</div>
</div>