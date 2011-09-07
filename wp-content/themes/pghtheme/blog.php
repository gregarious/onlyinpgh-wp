<?php get_header() ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							<div class="blog_image"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo get_post_meta($post->ID, 'featuredimage', true); ?>" alt="Blog Preview Image"/></a></div>
<div class="blog_list">
<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
<p class="date"><?php the_time('m.d.y'); ?></p>
<p><?php the_content( __( '<p class="serif">Read more &rarr;</p>', 'buddypress' ) ); ?></p>
</div>

<div class="pagenav"><?php posts_nav_link('','&laquo; Next','Back &raquo;'); ?></div>

			<?php endwhile; endif; ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
</div><!-- #content -->
<?php get_footer() ?>
