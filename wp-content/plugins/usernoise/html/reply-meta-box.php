<div id="replywrapper">
	<?php textarea('message', '', array('rows' => 7, 'id' => 'replybody'))?>
</div>
<label><?php _e('Subject')?></label>
<?php text_field('subject', __('Feedback', 'usernoise', 'admin') . ': ' . $post->post_title)?>

<div class="hide-if-no-js">
	<input type="button" class="button-primary" id="un-reply-submit" value="<?php _e('Send')?>">
	&nbsp;<img src="<?php echo usernoise_url('/images/loader.gif') ?>" id="un-reply-loader" style="display: none;">
</div>
