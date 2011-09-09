<?php get_header(); ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_404' ) ?>

		<div class="page 404">

<img style="margin-left:120px;" src="<?php bloginfo('template_url'); ?>/images/404signal.jpg"/>
<p class="notfound">The page you were looking for couldn't be found</p>
			<div id="message" class="info">

			</div>

			<?php do_action( 'bp_404' ) ?>

		</div>

		<?php do_action( 'bp_after_404' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer(); ?>