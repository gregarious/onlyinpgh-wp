		
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
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$new_query = new WP_Query();
$new_query->query( 'showposts=6&cat='.$category_id.'&paged='.$paged );

//The Loop
while ($new_query->have_posts()) : $new_query->the_post(); ?>
	
	
		<li> <a href="<?php the_permalink();?>">
		
		<?php
		
		if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) {
			the_post_thumbnail();
		} else { ?>
			<?php wpf_get_first_thumb_url(); ?></a> 
		
		<?php } ?>
		
		<h3><?php the_title() . '<br>'; ?></h3>
		<p>1234 PerryPie Lane<br>
			Braddock, PA 15638</p>
		<p><?php the_excerpt(); ?></p>
		</li> </a>
	<?php

endwhile; ?>

<div class="prev-next-bottom">
	<div id="prev"><?php next_posts_link('&larr; Previous', $new_query->max_num_pages);?></div>
	<div id="next"><?php previous_posts_link('Next &rarr;'); ?></div>
</div>

</ul>