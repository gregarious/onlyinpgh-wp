<?php 

/*
Template Name: Photos page
*/

get_header() ?>

<div id="wrapper">

<div id="main-content" style="padding: 0px;width:955px;">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div id="eventsearch_bar" style="height: 60px;margin-bottom: 20px;width:650px;">
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
					<a href="/">Photos Key</a><img src="<?php bloginfo('stylesheet_directory'); ?>/images/expandarrow.png"/>
				</dt>
				<dd>
					<?php echo GeoMashup::category_legend() ?>
				</dd>
				<dt>
					<a href="/">Submit a Photo</a><img src="<?php bloginfo('stylesheet_directory'); ?>/images/expandarrow.png"/>
				</dt>
				<dd>
					<div id="submitwidget">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Submit Photos') ) : ?>
						<?php endif; ?>
					</div>
				</dd>
			</dl>
		</div> <!-- #photos-sidebar-->
	
		<?php endwhile; endif; ?>

		<!-- Show the 10 most recent posts from the Photos category -->
		<!-- NOTE: Will not show photos on local machine if they are not in wp-content/uploads -->
		<div class="thumb-list">
			<h2 class="page-title">Recent Photos</h2>		
			<?php
			$id = get_cat_ID( 'Photos' ); 
			
			// http://www.readywpthemes.com/wordpress-recent-posts-from-specific-category/
			$recent = new WP_Query("cat=$id&showposts=12"); while($recent->have_posts()) : $recent->the_post();?>
			
			<div class="photo-post"><a href="<?php the_permalink() ?>" rel="bookmark"> <?php
				
				if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) {
					the_post_thumbnail();
				} else { ?>
					<a href="<?php the_permalink();?>"><?php wpf_get_first_thumb_url(); ?></a> 
				<?php } ?></a>
				
				<h5 class="thumb-title"><?php the_title(); ?></h5>
			</div>
			
			<?php endwhile; ?>
		</div> <!-- .thumb-list -->


	</div> <!-- #main-content -->
</div> <!-- #wrapper -->

<?php get_footer() ?>
