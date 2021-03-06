<?php get_header() ?>

<div id="wrapper">
	<div id="content">
		<div class="padder" id="sidebar-page">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<h2 class="pagetitle"><?php the_title(); ?></h2>

				<div class="post" id="post-<?php the_ID(); ?>">

					<div class="entry">

						<?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); ?>

						<?php wp_link_pages( array( 'before' => __( '<p><strong>Pages:</strong> ', 'buddypress' ), 'after' => '</p>', 'next_or_number' => 'number')); ?>
						<?php edit_post_link( __( 'Edit this entry.', 'buddypress' ), '<p>', '</p>'); ?>

					</div>

				</div>

			<?php endwhile; endif; ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->

		<div id="sidebar"><?php 
			// Display sidebar on all pages except 'Events'
			if ( ! is_page('events')) {
				locate_template( array( 'sidebar-blog.php' ), true ); 
			} ?>
		</div>
		
	</div><!-- #content -->

</div><!-- #wrapper -->
<?php get_footer() ?>