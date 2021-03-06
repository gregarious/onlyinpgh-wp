<?php get_header() ?>

<div id="wrapper">
	<div id="content">
		<div class="padder" id="sidebar-page">
		<div class="blog_content">

			<?php do_action( 'bp_before_blog_home' ) ?>

			<div class="page" id="blog-latest">

				<?php if ( have_posts() ) :
						$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
						query_posts("cat=256&showposts=5&paged=$page");
					
						while (have_posts()) : the_post(); ?>

							<?php do_action( 'bp_before_blog_post' ) ?>

							<div class="post" id="post-<?php the_ID(); ?>">
								<div class="blog_image"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>
							</div> <!-- .post -->
							<div class="blog_list">
								<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
								<p class="date"><?php the_time('m.d.y'); ?></p>
								<?php the_excerpt(); ?>
								<a href="<?php the_permalink(); ?>">Read More..</a>
							</div> <!-- .blog_list -->

							</div>

							<?php do_action( 'bp_after_blog_post' ) ?>

						<?php endwhile; ?>

						<div class="navigation">

							<div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'buddypress' ) ) ?></div>
							<div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'buddypress' ) ) ?></div>

						</div> <!-- .navigation -->

					<?php else : ?>

					<h2 class="center"><?php _e( 'Not Found', 'buddypress' ) ?></h2>
					<p class="center"><?php _e( 'Sorry, but you are looking for something that isn\'t here.', 'buddypress' ) ?></p>

					<?php endif; ?>
			</div> <!-- #blog-latest -->

			<?php do_action( 'bp_after_blog_home' ) ?>

		</div> <!-- .blog-content -->
		</div>

		<div id="sidebar"><?php locate_template( array( 'sidebar-blog.php' ), true ) ?></div>
		
	</div><!-- #content -->
	
	

</div><!-- #wrapper -->

<?php get_footer() ?>