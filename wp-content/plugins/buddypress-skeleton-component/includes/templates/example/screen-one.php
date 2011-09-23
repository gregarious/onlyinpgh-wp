<?php 
get_header();
$img_dir = get_bloginfo('stylesheet_directory') . '/images';
?>

<div id="content">
	<div class="padder">

		<div id="item-header">
			<?php locate_template( array( 'members/single/member-header.php' ), true ) ?>
		</div>

		<div id="item-nav">
			<div class="item-list-tabs no-ajax" id="object-nav">
				<ul>
					<?php bp_get_displayed_user_nav() ?>
				</ul>
			</div>
		</div>

		<div id="item-body">
			<div id="profilemapcontentholder">
				<div id="map" class="profilemap"></div>
				<div id="maplocationkey">
					<table id="locationtypes">
					<tbody>
						<tr>
							<td style="padding-left: 5px;">
								<p class="searchbar_head" style="float: left; font-size: 17px;">Also Show me...</p>
							</td>

							<td>
								<input id="food" onclick="placeRequest('food')" name="locations" type="checkbox" value="food" />
								<img src="<?php echo $img_dir; ?>/place_markers/food.png" alt="" />
								<p>Bars &amp; Restaurants</p>
							</td>

							<td>
								<input id="music" onclick="placeRequest('music')" name="locations" type="checkbox" value="music" />
								<img src="<?php echo $img_dir; ?>/place_markers/music.png" alt="" />
								<p>Music Venues</p>
							</td>

							<td>
								<input id="museum" onclick="placeRequest('museum')" name="locations" type="checkbox" value="museum" />
								<img src="<?php echo $img_dir; ?>/place_markers/museum.png" alt="" />
								<p>Museums &amp; Galleries</p>
							</td>
						</tr>
						<tr>
							<td>
								<input id="sports" onclick="placeRequest('sports')" name="locations" type="checkbox" value="sports" />
								<img src="<?php echo $img_dir; ?>/place_markers/sports.png" alt="" />
								<p>Sports &amp; Outdoors</p>
							</td>

							<td>
								<input id="shops" onclick="placeRequest('shops')" name="locations" type="checkbox" value="shops" />
								<img src="<?php echo $img_dir; ?>/place_markers/shopping.png" alt="" />
								<p>Shops</p>
							</td>

							<td>
								<input id="theater" onclick="placeRequest('theater')" name="locations" type="checkbox" value="theater" />
								<img src="<?php echo $img_dir; ?>/place_markers/theaterfilm.png" alt="" />
								<p>Theaters</p>
							</td>

							<td>
								<input id="attraction" onclick="placeRequest('attraction')" name="locations" type="checkbox" value="attractions" />
								<img src="<?php echo $img_dir; ?>/place_markers/attractions.png" alt="" />
								<p>Attractions</p>
							</td>
						</tr>
					</tbody>
					</table>
				</div>
			</div>
			<div id="event-sidebar">
				<div id="sidebar-header">
					<p class="sidebartogglecurrent">Upcoming Events</p>
				</div>
				<div id="sidebar-content"></div> <!-- Actual event results dynamically loaded into here -->
				<div id="sidebar-search-status"> <!-- Only visible while waiting for an AJAX response -->
					Searching... <img src="<?php bloginfo('stylesheet_directory'); ?>/images/loading.gif"/>
				</div>
				<div id="sidebar-footer"></div>	<!-- Load more events button will be rendered in here if applicable -->
			</div>
		</div> <!-- #item-body -->
	</div><!-- .padder -->
</div><!-- #content -->

<script src="<?php bloginfo('stylesheet_directory'); ?>/scripts/map.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/scripts/profilepage.js"></script>

<?php get_footer() ?>