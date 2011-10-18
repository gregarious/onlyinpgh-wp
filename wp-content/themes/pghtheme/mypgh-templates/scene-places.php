		
<h2 class="scene-part-title">Where to go?</h2> 
<!--<span id="cal-buttons" class="alignright">
	<a href="" class="day-nav-link" id="cal-nav-prev">&larr; Previous</a>
	<a href="" class="day-nav-link" id="cal-nav-next">Next &rarr;</a>
</span>-->

<ul class="scene-places">
<?php

//http://wordpress.org/support/topic/adding-pagination-to-a-wp_query-loop

//The Query
$category_id = get_cat_ID('Profiles');
$cat_link = get_category_link($category_id);
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$new_query = new WP_Query();
$new_query->query( 'showposts=6&cat='.$category_id.'&paged='.$paged );

//The Loop
while ($new_query->have_posts()) : $new_query->the_post(); ?>
	
	<li class="photo-post"><div class="photo-container"><a href="<?php the_permalink() ?>" rel="bookmark"> <?php
		
		if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) {
			the_post_thumbnail();
		} else { ?>
			<a href="<?php the_permalink();?>"><?php wpf_get_first_thumb_url(); ?></a> 
		<?php } ?></a></div>
		
		<h5 class="thumb-title"><?php the_title(); ?></h5>
		
	</li>
	<?php

endwhile; ?>

<div class="prev-next-bottom">
	<div id="next"><a href="<?php echo $cat_link; ?>">See more &rarr;</div>
</div>

</ul>