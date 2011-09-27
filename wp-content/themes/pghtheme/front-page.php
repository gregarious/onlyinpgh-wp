<?php 

$img_dir = get_bloginfo('stylesheet_directory') . '/images';
get_header(); ?>

<div id="wrapper"> 
<?php 
// Removing loop - visual editor messes everything up
//if (have_posts()) : while (have_posts()) : the_post(); 
?>

	<div id="eventsearch_bar">
		<div id="dropdown_search">
			<p class="searchbar_head" style="float: left;">Find upcoming
events in</p>

			<ul>
				<li class="border">
					<select id="regionSelector" onkeydown="javascript:return submitonEnter(event);"> 
						<option value="all">All Regions</option> 
						<option value="city">City</option> 
						<option value="north">North</option> 
						<option value="south">South</option> 
						<option value="east">East</option> 
						<option value="west">West</option> </select></li>
				<li style="margin: 0px; padding: 0px;">					
					<div id="bydate" style="display: block;">					
						<p class="searchbar_header" style="float: left;">Happening</p>
						<select id="timespanSelect" onkeydown="javascript:return submitonEnter(event);"> 
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
					<input id="keywordsearch" style="margin: 0px 10px 0px 10px;" onkeydown="javascript:return submitonEnter(event);" size="30" type="text" value="Keyword search (optional)" onfocus="clearkeywordtip(this)" />
				</li>
			</ul>
			
			<button onclick="newSearchRequested();clearChecks();jqCheckAll('event_types','events',1)">Submit</button>

		</div>

		<div id="placesearch">
				<p class="refinesearch">Events types</p>
				<form id="event_types" name="event_types" action="" method="post">
				<table id="eventtypes">
					<tbody>
						<tr>
							<td>
								<input onclick="toggleMarkers('category', 'oip_feeds')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>/event_markers/featured_icon.png" alt="" />
								<div class="typeholder">
									<p>Featured</p>
								</div>
							</td>

							<td>
								<input onclick="toggleMarkers('category', 'Food &amp; Drink')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>/event_markers/food.png" alt="" />
								<div class="typeholder">
									<p>Food &amp; Drink</p>
								</div>
							</td>

							<td>
								<input onclick="toggleMarkers('category', 'Shopping')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>/event_markers/retail.png" alt="" />
								<div class="typeholder">
									<p>Shopping</p>
								</div>
							</td>

							<td>
								<input onclick="toggleMarkers('category', 'Music')" checked="checked" name="events" type="checkbox" value="locations" /><img src="<?php echo $img_dir ?>/event_markers/music.png" alt="" />
								<div class="typeholder">
									<p>Music</p>
								</div>
							</td>

							<td>
								<input onclick="toggleMarkers('category', 'Theater')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>/event_markers/theaterfilm.png" alt="" />
								<div class="typeholder">
									<p>Theater &amp; Film</p>
								</div>
							</td>
							
							<td>
								<input onclick="toggleMarkers('category', 'Educational')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>/event_markers/education.png" alt="" />
								<div class="typeholder">
									<p>Education</p>
								</div>
							</td>
	
							<td>
								<input onclick="toggleMarkers('category', 'Arts')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>/event_markers/arts.png" alt="" />
								<div class="typeholder">
									<p>Arts</p>
								</div>
							</td>

							<td>
								<input onclick="toggleMarkers('category','General Fun')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>/event_markers/genfun.png" alt="" />
								<div class="typeholder">
									<p>General Fun</p>
								</div>
							</td>
							
							<td>
								<input onclick="toggleMarkers('category', 'Sports &amp; Outdoors')" checked="checked" name="events" type="checkbox" value="locations" />
								<img src="<?php echo $img_dir ?>/event_markers/outdoors.png" alt="" />
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
			<div id="map"></div>
			<div id="maplocationkey">
				<table id="locationtypes">
					<tr>
						<td style="padding-left:5px;"><p class="searchbar_head" style="float: left;font-size: 17px;">Also Show me...</p></td>
							<td>
								<input type="checkbox" name="locations" id="food" onclick="placeRequestClicked('food')">
								<img src="<?php echo $img_dir ?>/place_markers/food.png"><p>Bars & Restaurants</p>
						</td>
	
						<td>
							<input type="checkbox" name="locations" id="music" onclick="placeRequestClicked('music')">
							<img src="<?php echo $img_dir ?>/place_markers/music.png">
							<p>Music Venues</p>
						</td>
	
						<td>
							<input type="checkbox" name="locations" id="museum" onclick="placeRequestClicked('museum')">
							<img src="<?php echo $img_dir ?>/place_markers/museum.png">
							<p>Museums & Galleries</p>
						</td>						
					</tr>			
					<tr>
						<td>
							<input type="checkbox" name="locations" id="sports" onclick="placeRequestClicked('sports')">
							<img src="<?php echo $img_dir ?>/place_markers/sports.png">
							<p>Sports & Outdoors</p>
						</td>

						<td>
							<input type="checkbox" name="locations" id="shops" onclick="placeRequestClicked('shops')">
							<img src="<?php echo $img_dir ?>/place_markers/shopping.png">
							<p>Shops</p>
						</td>

						<td>
							<input type="checkbox" name="locations" id="theater" onclick="placeRequestClicked('theater')">
							<img src="<?php echo $img_dir ?>/place_markers/theaterfilm.png">
							<p>Theaters</p>
						</td>

						<td>
							<input type="checkbox" name="locations" id="attractions" onclick="placeRequestClicked('attractions')">
							<img src="<?php echo $img_dir ?>/place_markers/attractions.png">
							<p>Attractions</p>
						</td>
					</tr>
				</table>
			</div> <!-- #maplocation key-->
		</div> <!-- #mapcontentholder -->
		
		<div id="sidebar-header">
				<p class="sidebartogglecurrent">Upcoming Events</p>
			</div>
		<div id="event-sidebar">
			<div id="sidebar-content"></div> <!-- Actual event results dynamically loaded into here -->
			<div id="sidebar-search-status"> <!-- Only visible while waiting for an AJAX response -->
				Searching... <img src="<?php bloginfo('stylesheet_directory'); ?>/images/loading.gif"/>
			</div>
			<div id="sidebar-footer"></div>	<!-- Load more events button will be rendered in here if applicable -->
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


</div> <!-- #wrapper -->

<script src="<?php bloginfo('stylesheet_directory'); ?>/scripts/map.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/scripts/frontpage.js"></script>
<?php get_footer(); ?>