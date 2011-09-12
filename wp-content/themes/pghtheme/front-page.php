<?php 
$img_dir = get_bloginfo('stylesheet_directory') . '/images/';
get_header(); ?>

<div id="wrapper"> 
<?php 
// Removing loop - visual editor messes everything up
//if (have_posts()) : while (have_posts()) : the_post(); 
?>

	<div id="eventsearch_bar">
		<input id="limitvalue" type="hidden" value="0" />
		<div id="dropdown_search">
			<p class="searchbar_head" style="float: left;">Find upcoming
events in</p>

			<ul>
				<li class="border"> <select id="addressInput" onkeydown="javascript:return submitonEnter(event);"> <option value="all">All Regions</option> <option value="301-347 Fifth Ave 15222">City</option> <option value="40.57522,-80.004">North</option> <option value="40.35582,-79.995">South</option> <option value="40.44054,-79.819">East</option> <option value="40.44315,-80.135">West</option> </select></li>
				<li style="margin: 0px; padding: 0px;">					
					<div id="bydate" style="display: block;">					
						<p class="searchbar_header" style="float: left;">Happening</p>
						<select id="spanSelect" onkeydown="javascript:return submitonEnter(event);"> 
							<option value="0">Today</option> 
							<option value="1">Tomorrow</option> 
							<option selected="selected" value="7">Next 7 days</option> 
							<option value="14">Next 14 days</option> 
							<option value="30">Next 30 days</option> 
						</select>
						<p class="advancesearch" onclick="showSearch()">Advanced Date Search</p>
					</div>
				</li>
				<li style="margin: 0px; padding: 0px;">
					<div id="datepicksearch" style="display: none;">
						<p class="searchbar_header">Start:</p>
						<input id="startdate" class="datepicker" type="text" />
						<p class="searchbar_header">End:</p>
						<input id="enddate" class="datepicker" type="text" />&nbsp;
						<p class="advancesearch" onclick="hideSearch()">Basic Date Search</p>
					</div>
				</li>
				<li class="border"> 
					<input id="searchword" style="margin: 0px 10px 0px 10px;" onkeydown="javascript:return submitonEnter(event);" size="20" type="text" value="Add a keyword (optional)" />
				</li>
			</ul>
			
			<button onclick="beginSearch();clearChecks();jqCheckAll('event_types','events',1)">Submit</button>

		</div>

		<div id="placesearch">
				<p class="refinesearch" style="float: left;">Events types</p>
				<form id="event_types" name="event_types" action="" method="post">
				<table id="eventtypes">
					<tbody>
						<tr>
							<td>
								<input onclick="toggleMarkers('category', 'oip_feeds')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>newplace_markers/featured_icon.png" alt="" />
								<div class="typeholder">
									<p>Featured</p>
								</div>
							</td>

							<td>
								<input onclick="toggleMarkers('category', 'Food &amp; Drink')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>newplace_markers/food.png" alt="" />
								<div class="typeholder">
									<p>Food &amp; Drink</p>
								</div>
							</td>

							<td>
								<input onclick="toggleMarkers('category', 'Shopping')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>newplace_markers/retail.png" alt="" />
								<div class="typeholder">
									<p>Shopping</p>
								</div>
							</td>

							<td>
								<input onclick="toggleMarkers('category', 'Music')" checked="checked" name="events" type="checkbox" value="locations" /><img src="<?php echo $img_dir ?>newplace_markers/music.png" alt="" />
								<div class="typeholder">
									<p>Music</p>
								</div>
							</td>

							<td>
								<input onclick="toggleMarkers('category', 'Theater')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>newplace_markers/theaterfilm.png" alt="" />
								<div class="typeholder">
									<p>Theater &amp; Film</p>
								</div>
							</td>
							
							<td>
								<input onclick="toggleMarkers('category', 'Educational')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>newplace_markers/education.png" alt="" />
								<div class="typeholder">
									<p>Education</p>
								</div>
							</td>
	
							<td>
								<input onclick="toggleMarkers('category', 'Arts')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>newplace_markers/arts.png" alt="" />
								<div class="typeholder">
									<p>Arts</p>
								</div>
							</td>

							<td>
								<input onclick="toggleMarkers('category','General Fun')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>newplace_markers/genfun.png" alt="" />
								<div class="typeholder">
									<p>General Fun</p>
								</div>
							</td>
							
							<td>
								<input onclick="toggleMarkers('category', 'Sports &amp; Outdoors')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>newplace_markers/outdoors.png" alt="" />
								<div class="typeholder">
									<p>Sports &amp; Outdoors</p>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</form>	
		</div> <!-- #dropdown_search -->
	</div> <!-- #eventsearch_bar -->

	<div id="main-content" style="padding: 0px;">
		<?php //the_content(); -- not needed without loop ?>
		<div id="mapcontentholder">
			<div style="float:left;">
				<div id="map"></div>
				<div id="maplocationkey">
					<table id="locationtypes">
						<tr>
							<td style="padding-left:5px;"><p class="searchbar_head" style="float: left;font-size: 17px;">Also Show me...</p></td>
								<td>
									<input type="checkbox" name="locations" id="food" onclick="placeRequest('food')">
									<img src="<?php echo $img_dir ?>place_dots/food.png"><p>Bars & Restaurants</p>
							</td>
		
							<td>
								<input type="checkbox" name="locations" id="music" onclick="placeRequest('music')">
								<img src="<?php echo $img_dir ?>place_dots/music.png">
								<p>Music Venues</p>
							</td>
		
							<td>
								<input type="checkbox" name="locations" id="museum" onclick="placeRequest('museum')">
								<img src="<?php echo $img_dir ?>place_dots/museum.png">
								<p>Museums & Galleries</p>
							</td>						
						</tr>			
						<tr>
							<td>
								<input type="checkbox" name="locations" id="sports" onclick="placeRequest('sports')">
								<img src="<?php echo $img_dir ?>place_dots/spots.png">
								<p>Sports & Outdoors</p>
							</td>
	
							<td>
								<input type="checkbox" name="locations" id="shops" onclick="placeRequest('shops')">
								<img src="<?php echo $img_dir ?>place_dots/shopping.png">
								<p>Shops</p>
							</td>
	
							<td>
								<input type="checkbox" name="locations" id="theater" onclick="placeRequest('theater')">
								<img src="<?php echo $img_dir ?>place_dots/theaterfilm.png">
								<p>Theaters</p>
							</td>
	
							<td>
								<input type="checkbox" name="locations" id="attractions" onclick="placeRequest('attractions')">
								<img src="<?php echo $img_dir ?>place_dots/attractions.png">
								<p>Attractions</p>
							</td>
						</tr>
					</table>
				</div> <!-- #maplocation key-->
			</div> <!-- #mapcontentholder -->
		<div> <!-- #main-content -->
		
		<div onclick="showEvent()">
			<p id="event" class="sidebartogglecurrent">Upcoming Events</p>
		</div>
		<div id="resultsholder">
			<div id="event_sidebar"></div>
			<div id="morebutton">
				<input id="loadmore"  type="button" onclick="searchTen()" value="Load Next 100 Events">
			</div>
		</div>
	</div>
</div> 

</div>
<?php //endwhile; else: ?>
<?php //endif; ?>
<div id="space">
<div class="spacecolumn"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Ad Space 1') ) : ?>
<?php endif; ?>
</div>
<div class="spacecolumn"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Ad Space 2') ) : ?>
<?php endif; ?></div>
<div class="spacecolumn"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Ad Space 3') ) : ?>
<?php endif; ?></div>
<div class="spacecolumnlast"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Ad Space 4') ) : ?>
<?php endif; ?></div>
</div>

</div> <! #wrapper -->
<script scr="<?php bloginfo('stylesheet_url'); ?>/scripts/map.js"></script>

<?php get_footer(); ?>