<?php /* Smarty version 2.6.13, created on 2010-05-29 21:41:13
         compiled from support/support.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'support/support.tpl', 3, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/admin.header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<h2><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Support Page<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>

<p><a href="support.lib.php">poMMo Support Library</a></p>

<p>poMMo version: <?php echo $this->_tpl_vars['version']; ?>
 +<?php echo $this->_tpl_vars['revision']; ?>
</p>

<p><i>Coming to a theatre near you</i></p>

<h3>My NOTES:</h3>

<pre>
+ Enhanced support library
+ PHPInfo()  (or specifically mysql, php, gettext, safemode, webserver, etc. versions)
+ Database dump (allow selection of tables.. provide a dump of them)
+ Link to README.HTML  +  local documentation
+ Link to WIKI documentation
	+ Make a user-contributed open WIKI documentation system
	+ When support page is clicked, show specific support topics for that page
+ Clear All Subscribers
+ Reset Database
+ Backup Database
+ Ensure max run time is 30 seconds if safe mode is enabled
</pre>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/admin.footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>