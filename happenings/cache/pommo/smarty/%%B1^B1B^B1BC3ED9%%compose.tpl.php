<?php /* Smarty version 2.6.13, created on 2010-05-29 21:41:49
         compiled from admin/mailings/mailing/compose.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'admin/mailings/mailing/compose.tpl', 6, false),)), $this); ?>
<div class="output">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/messages.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<div class="compose">
<h4><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>HTML Message<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h4>
<textarea name="body"><?php echo $this->_tpl_vars['body']; ?>
</textarea>
<span class="notes">(<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Leave blank to send text only<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>)</span>
</div>

<ul class="inpage_menu">
<li><a href="#" id="e_toggle"><img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/icons/viewhtml.png" alt="icon" border="0" align="absmiddle" /><span id="toggleText"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Enable WYSIWYG<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span></a></li>
<li><a href="mailing/ajax.personalize.php" id="e_personalize"><img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/icons/subscribers_tiny.png" alt="icon" border="0" align="absmiddle" /> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Add Personalization<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a></li>
<li><a href="mailing/ajax.addtemplate.php" id="e_template"><img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/icons/edit.png" alt="icon" border="0" align="absmiddle" /> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Save as Template<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a></li>
</ul>

<div class="compose">
<h4><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Text Version<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h4>
<textarea name="altbody"><?php echo $this->_tpl_vars['altbody']; ?>
</textarea>
<span class="notes">(<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Leave blank to send HTML only<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>)</span>
</div>

<form id="compose" class="json mandatory" action="<?php echo $_SERVER['PHP_SELF']; ?>
" method="post">
<input type="hidden" name="compose" value="true" />
<ul class="inpage_menu">
<li><a href="#" id="e_altbody"><img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/icons/reload.png" alt="icon" border="0" align="absmiddle" /> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Copy text from HTML Message<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a></li>
<li><input type="submit" id="submit" name="submit" value="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Continue<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" /></li>
</ul>
</form>

<script type="text/javascript">
var onText = '<?php $this->_tag_stack[] = array('t', array('escape' => 'js')); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Disable WYSIWYG<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>';
var offText = '<?php $this->_tag_stack[] = array('t', array('escape' => 'js')); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Enable WYSIWYG<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>';

$().ready(function() {
	
	wysiwyg.init({
		language: '<?php echo $this->_tpl_vars['lang']; ?>
',
		baseURL: '<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
../wysiwyg/',
		t_weblink: '<?php $this->_tag_stack[] = array('t', array('escape' => 'js')); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>View this Mailing on the Web<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>',
		t_unsubscribe: '<?php $this->_tag_stack[] = array('t', array('escape' => 'js')); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Unsubscribe or Update Records<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>',
		textarea: $('textarea[@name=body]')
	});
	
	<?php if ($this->_tpl_vars['wysiwyg'] == 'on'): ?>
		// Enable the WYSIWYG
		wysiwyg.enable();
		$('#toggleText').html(onText);
	<?php endif; ?>
	
	<?php echo '
	
	// Command Buttons (toggle HTML, add personalization, save template, generate altbody)
	$(\'#e_toggle\').click(function() {
		if(wysiwyg.enabled) {
			if(wysiwyg.disable()) {
				$(\'#toggleText\').html(offText) 
				$.getJSON(\'mailing/ajax.rpc.php?call=wysiwyg&disable=true\');
			}
		}
		else {
			if(wysiwyg.enable()) {
				$(\'#toggleText\').html(onText);
				$.getJSON(\'mailing/ajax.rpc.php?call=wysiwyg&enable=true\');
			}
		}
		return false;
	});
	
	$(\'#e_personalize\').click(function() {
		$(\'#dialog\').jqmShow(this);
		return false;
	});
	
	$(\'#e_template\').click(function() {
		
		// submit the bodies
		var post = {
			body: wysiwyg.getBody(),
			altbody: $(\'textarea[@name=altbody]\').val()
		},trigger = this;
		
		poMMo.pause();
		
		$.post(\'mailing/ajax.rpc.php?call=savebody\',post,function(){
			$(\'#dialog\').jqmShow(trigger);
			poMMo.resume();
		});
		
		return false;
	});
	
	
	$(\'#e_altbody\').click(function() {
		
		var post = {
			body: wysiwyg.getBody()
		};
		
		poMMo.pause();
		
		$.post(\'mailing/ajax.rpc.php?call=altbody\',post,function(json){
			$(\'textarea[@name=altbody]\').val(json.altbody);
			poMMo.resume();
		},"json");
		
		return false;
	});
	
	
	$(\'#compose\').submit(function() {
		// submit the bodies
		var post = {
			body: wysiwyg.getBody(),
			altbody: $(\'textarea[@name=altbody]\').val()
		};
		
		poMMo.pause();
		
		$.post(\'mailing/ajax.rpc.php?call=savebody\',post,function(){
			poMMo.resume();
		});
	});
	
});

</script>
'; ?>
