<?php do_action( 'bp_after_container' ) ?>
<?php do_action( 'bp_before_footer' ) ?>

<div id="footer">
	<div id="footer_content">
		<div id="footer_left">
			<span class="footerlogo">onlyinpgh</span><br>
			<p><a href="mailto:contact@onlyinpgh.com">contact@onlyinpgh.com</a></p>
			<div id="search-bar">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Sidebar One') ) :
				endif; ?>
			</div> <!-- #search-bar -->
		</div> <!-- #footer_left -->
		<div id="footer_right">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Sidebar Two') ) :
			endif; ?>
		</div> <!-- #footer_right -->
		<div id="footer_right2">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Sidebar Three') ) :
			endif; ?>
		</div> <!-- #footer_right2 -->
	</div> <!-- #footer_content -->
	<div id="footer_textnav"><ul><?php wp_list_pages('title_li='); ?></ul></div>

	<?php do_action( 'bp_footer' ) ?>

</div> <!-- #footer -->

<?php do_action( 'bp_after_footer' ) ?>
<?php wp_footer(); ?>

<script type="text/javascript">
  var host = ('https:' == document.location.protocol
    ? 'https://ssl.' : 'http://met.');
  document.write(unescape("%3Cscript src='" + host 
    + "picnet.com.au/resources/scripts/met.client.min.js?usercode=668-1428633436' type='text/javascript'%3E%3C/script%3E"));
</script>

</body>
</html>