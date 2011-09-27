<?php get_header(); ?>
<div id="wrapper">
	<div id="content">

		<?php do_action( 'bp_before_404' ) ?>

		<div class="page 404">

<img style="margin-left:120px;" src="<?php bloginfo('stylesheet_directory'); ?>/images/404signal.jpg"/>
<p class="notfound">The page you were looking for couldn't be found</p>
			<div id="message" class="info">

			</div>

			<?php do_action( 'bp_404' ) ?>

		</div>

		<?php do_action( 'bp_after_404' ) ?>

	</div><!-- #content -->
</div><!-- #wrapper -->
<?php get_footer(); ?>