<?php 

/*
Template Name: Photos page
*/

get_header() ?>

<div id="wrapper">

	<div id="content" class="page-photos">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div id="eventsearch_bar" class="page-photos">
			<p class="searchbar_head" style="float: left;">Find photos<br>taken</p>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Search Photos') ) : ?>
			<?php endif; ?>
		</div> 
		
		<div id="contestinfo_bar">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Contest Info') ) : ?>
			<?php endif; ?>
		</div>
		
		<?php the_content(); ?>

		<div id="photos_sidebar">
			<dl>
				<dt>
					<a class="photos-widget" href="/">Photos Key</a>
				</dt>
				<dd>
					<?php echo GeoMashup::category_legend() ?>
				</dd>
				<dt>
					<a class="photos-widget" href="/">Submit a Photo</a>
				</dt>
				<dd>
					<div id="submitwidget">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Submit Photos') ) : ?>
						<?php endif; ?>
					</div>
				</dd>
			</dl>
		</div> <!-- #photos-sidebar-->
	
		
		<!-- Show the 10 most recent posts from the Photos category -->
		<!-- NOTE: Will not show photos on local machine if they are not in wp-content/uploads -->
		
		<div class="thumb-list">	
		
			<?php

			// http://wordpress.org/support/topic/adding-pagination-to-a-wp_query-loop

			$catname = wp_title('', false);
			$new_query = new WP_Query();
			$new_query->query('category_name=Photos&showposts=12'.'&paged='.$paged);
			?>
			<div class="prev-next">
				<div id="prev"><?php next_posts_link('&larr; Older Photos', $new_query->max_num_pages);?></div>
				<div id="next"><?php previous_posts_link('Newer Photos &rarr;'); ?></div>
			</div>
			
			<h2 class="page-title">Recent Photos</h2>	
			
			<?php						
			while ($new_query->have_posts()) : $new_query->the_post(); ?>			
			
			<div class="photo-post"><a href="<?php the_permalink() ?>" rel="bookmark"> <?php
				
				if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) {
					the_post_thumbnail();
				} else { ?>
					<a href="<?php the_permalink();?>"><?php wpf_get_first_thumb_url(); ?></a> 
				<?php } ?></a>
				
				<h5 class="thumb-title"><?php the_title(); ?></h5>
				
			</div>
			
			<?php endwhile; // End $new_query loop ?>
			
		</div> <!-- .thumb-list -->

		<div class="prev-next-bottom">
			<div id="prev"><?php next_posts_link('&larr; Older Photos', $new_query->max_num_pages);?></div>
			<div id="next"><?php previous_posts_link('Newer Photos &rarr;'); ?></div>
		</div>

	 <?php endwhile; endif; // End The Loop ?>
	
	</div> <!-- #main-content -->
</div> <!-- #wrapper -->

<?php get_footer() ?>
