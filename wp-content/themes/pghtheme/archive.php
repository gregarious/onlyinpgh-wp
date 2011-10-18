<?php get_header(); ?>

<div id="wrapper">
	<div id="content">
		<div class="padder" id="sidebar-page">

		<?php do_action( 'bp_before_archive' ) ?>

		<div class="page" id="blog-latest">

			<h2><?php printf( __( '%1$s', 'buddypress' ), wp_title( false, false ) ); ?></h2>

			<?php if ( have_posts() ) : ?>

				<?php while (have_posts()) : the_post(); ?>

					<?php do_action( 'bp_before_blog_post' ) ?>

							<div class="post" id="post-<?php the_ID(); ?>">
								<div class="blog_image"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>
							</div> <!-- .post -->
							<div class="blog_list">
								<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
								
								<?php the_excerpt(); ?>
								<a href="<?php the_permalink(); ?>">Read More..</a>
							</div> <!-- .blog_list -->

							</div>

							<?php do_action( 'bp_after_blog_post' ) ?>

				<?php endwhile; ?>

				<div class="navigation">

					<div class="alignleft"><?php next_posts_link( __( '&larr; Previous', 'buddypress' ) ) ?></div>
					<div class="alignright"><?php previous_posts_link( __( 'Next &rarr;', 'buddypress' ) ) ?></div>

				</div>

			<?php else : ?>

				<h2 class="center"><?php _e( 'Not Found', 'buddypress' ) ?></h2>
				<?php locate_template( array( 'searchform.php' ), true ) ?>

			<?php endif; ?>

		</div>

		<?php do_action( 'bp_after_archive' ) ?>

		</div> <!-- .padder -->

		<div id="sidebar"><?php locate_template( array( 'sidebar-blog.php' ), true ) ?></div>
		
	</div> <!-- #content -->
</div> <!-- #wrapper -->
<?php get_footer() ?>