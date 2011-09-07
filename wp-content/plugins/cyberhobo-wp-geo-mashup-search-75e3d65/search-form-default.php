<?php
/**
 * Default Geo Mashup Search widget form template.
 *
 * THIS FILE WILL BE OVERWRITTEN BY AUTOMATIC UPGRADES
 * See the geo-mashup-search.php plugin file for license
 *
 * Copy this to a file named geo-mashup-search-form.php in your active theme folder
 * to customize. For bonus points delete this message in the copy!
 *
 * Variables in scope:
 * $widget     object      The widget generating this form
 * $widget_id	string		The unique widget identifier
 * $instance   array       The widget instance data
 * $action_url string      The URL of the page chosen to display results
 * $categories array       Category objects to include in the category menu, if any.
 * $radii      array       Radius distances to include in the radius menu, if any.
 */
?>
<form class="geo-mashup-search-form" method="post" action="<?php echo $action_url; ?>">

<?php if ( !empty( $categories ) ) : ?>
	<label for="<?php echo $widget_id; ?>-categories"><?php _e( 'find', 'GeoMashupSearch' ); ?>
	<select id="<?php echo $widget_id; ?>-categories" name="map_cat">
	<?php foreach ( $categories as $cat ) : ?>
		<option value="<?php echo $cat->term_id; ?>"<?php
			if ( $widget->get_default_value( $_REQUEST, 'map_cat' ) == $cat->term_id )
				echo ' selected="selected"';
		?>><?php echo $cat->name; ?></option>;
	<?php endforeach; ?>
	</select>
	<?php _e( 'posts', 'GeoMashupSearch' ); ?></label>
<?php endif; // Categories ?>

<?php if ( !empty( $radii ) ) : ?>
	<label for="<?php echo $widget_id; ?>-radius"><?php _e( 'within', 'GeoMashupSearch' ); ?></label>
	<select id="<?php echo $widget_id; ?>-radius" name="radius">
	<?php foreach ( $radii as $radius ) : ?>
		<option value="<?php echo $radius; ?>"<?php
			if ( $widget->get_default_value( $_REQUEST, 'radius' ) == $radius )
					echo ' selected="selected"';
		?>><?php echo $radius; ?></option>
	<?php endforeach; ?>
	</select>
	<?php echo esc_html( $instance['units'] ); ?>
<?php endif; // Radius ?>

	<input name="units" type="hidden" value="<?php echo esc_attr( $instance['units'] ); ?>" />

	<label for="<?php echo $widget_id; ?>-input"><?php _e( empty( $radii ) ? '' : 'of', 'GeoMashupSearch' ); ?></label>
	<select id="<?php echo $widget_id; ?>-input" name="location_text" value="<?php echo esc_attr( $_REQUEST['location_text'] ); ?>" />
 <option value="all">All Regions</option> <option value="301-347 Fifth Ave 15222">City</option> <option value="40.57522,-80.004">North</option> <option value="40.35582,-79.995">South</option> <option value="40.44054,-79.819">East</option> <option value="40.44315,-80.135">West</option></select>
	
	<input id="<?php echo $widget_id; ?>-submit" name="geo_mashup_search_submit" type="submit" value="<?php _e( 'Search', 'GeoMashupSearch' ); ?>" />
</form>