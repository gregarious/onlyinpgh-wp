<?php
/**
 * Default Geo Mashup Search results template.
 *
 * THIS FILE WILL BE OVERWRITTEN BY AUTOMATIC UPGRADES
 * See the geo-mashup-search.php plugin file for license
 *
 * Copy this to a file named geo-mashup-search-results.php in your active theme folder
 * to customize. For bonus points delete this message in the copy!
 *
 * Variables in scope:
 * $geo_mashup_search  object   The managing search object
 * $search_text        string   The search text entered in the form
 * $radius             int      The search radius
 * $units              string   'mi' or 'km'
 * $near_location      array    The location searched, including 'lat' and 'lng'
 * $distance_factor    float    The multiplier to convert the radius to kilometers
 * $approximate_zoom   int      A guess at a zoom level that will include all results
 *
 * Methods of $geo_mashup_search mimic WordPress Loop functions have_posts()
 * and the_post() (see http://codex.wordpress.org/The_Loop). This makes post
 * template functions like the_title() work as expected. For distance:
 *
 * $geo_mashup_search->the_distance();
 *
 * will echo the distance with units. Its output can be modified:
 *
 * $geo_mashup_search->the_distance( 'decimal_places=1&append_units=0&echo=0' );
 */
?>
<div id="geo-mashup-search-results">

	<?php if ( $geo_mashup_search->have_posts() ) : ?>

	<?php echo GeoMashup::map( array(
		'name' => 'search-results-map',
		'object_ids' => $geo_mashup_search->get_the_ID_list(),
		'center_lat' => $near_location['lat'],
		'center_lng' => $near_location['lng'],
		'zoom' => 	$approximate_zoom + 1 // Adjust to taste
		) ); ?>
<?php echo GeoMashup::category_legend() ?>

	<?php while ( $geo_mashup_search->have_posts() ) : $geo_mashup_search->the_post(); ?>

	<?php endwhile; ?>

	<?php else : ?>

<p class="noresults"><?php _e( 'No results found.', 'GeoMashupSearch' ); ?></p>
<?php echo GeoMashup::map() ?>

	<?php endif; ?>
</div>