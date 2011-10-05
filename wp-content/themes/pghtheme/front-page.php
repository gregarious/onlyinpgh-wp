<?php 

$img_dir = get_bloginfo('stylesheet_directory') . '/images';
get_header(); ?>

<div id="wrapper"> 
	<div id="eventsearch_bar">
		<div id="dropdown_search">
			<p class="searchbar_head">Find upcoming
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
						<p class="searchbar_header alignleft">Happening</p>
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
						<input id="enddate" class="datepicker" type="text" />
						<p class="advancesearch" onclick="hideSearch()">Basic Date Search</p>
					</div>
				</li>
				<li class="border"> 
					<input id="keywordsearch" style="margin: 0px 10px 0px 10px;" onkeydown="javascript:return submitonEnter(event);" size="30" type="text" value="Keyword search (optional)" onfocus="clearkeywordtip(this)" />
				</li>
			</ul>
			
			<button class="submit-button alignright" onclick="newSearchRequested();clearChecks();jqCheckAll('event_types','events',1)">Submit</button>

		</div>

		<div id="placesearch">
				<p class="refinesearch">Events types</p>
				<form id="event_types" name="event_types" action="" method="post">
				<table id="eventtypes">
					<tbody>
						<tr>
							<td>
								<input onclick="toggleEventMarkers('oip_feeds')" checked="checked" name="events" type="checkbox" value="locations" id="oip_feeds-toggle"/>
								<img src="<?php echo $img_dir ?>/event_markers/featured_icon.png" alt="" />
								<div class="typeholder">
									<p>Featured</p>
								</div>
							</td>

							<td>
								<input onclick="toggleEventMarkers('Food &amp; Drink')" checked="checked" name="events" type="checkbox" value="locations" id="Food-toggle"/>
								<img src="<?php echo $img_dir ?>/event_markers/food.png" alt="" />
								<div class="typeholder">
									<p>Food &amp; Drink</p>
								</div>
							</td>

							<td>
								<input onclick="toggleEventMarkers('Shopping')" checked="checked" name="events" type="checkbox" value="locations" id="Shopping-toggle"/>
								<img src="<?php echo $img_dir ?>/event_markers/retail.png" alt="" />
								<div class="typeholder">
									<p>Shopping</p>
								</div>
							</td>

							<td>
								<input onclick="toggleEventMarkers('Music')" checked="checked" name="events" type="checkbox" value="locations" id="Music-toggle"/><img src="<?php echo $img_dir ?>/event_markers/music.png" alt="" />
								<div class="typeholder">
									<p>Music</p>
								</div>
							</td>

							<td>
								<input onclick="toggleEventMarkers('Theater')" checked="checked" name="events" type="checkbox" value="locations" id="Theater-toggle"/>
								<img src="<?php echo $img_dir ?>/event_markers/theaterfilm.png" alt="" />
								<div class="typeholder">
									<p>Theater &amp; Film</p>
								</div>
							</td>
							
							<td>
								<input onclick="toggleEventMarkers('Educational')" checked="checked" name="events" type="checkbox" value="locations" id="Educational-toggle"/>
								<img src="<?php echo $img_dir ?>/event_markers/education.png" alt="" />
								<div class="typeholder">
									<p>Education</p>
								</div>
							</td>
	
							<td>
								<input onclick="toggleEventMarkers('Arts')" checked="checked" name="events" type="checkbox" value="locations" id="Arts-toggle"/>
								<img src="<?php echo $img_dir ?>/event_markers/arts.png" alt="" />
								<div class="typeholder">
									<p>Arts</p>
								</div>
							</td>

							<td>
								<input onclick="toggleEventMarkers('General Fun')" checked="checked" name="events" type="checkbox" value="locations" id="General-toggle"/>
								<img src="<?php echo $img_dir ?>/event_markers/genfun.png" alt="" />
								<div class="typeholder">
									<p>General Fun</p>
								</div>
							</td>
							
							<td>
								<input onclick="toggleEventMarkers('Sports &amp; Outdoors')" checked="checked" name="events" type="checkbox" value="locations" id="Sports-toggle"/>
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
		<div id="mapcontentholder">
			<div id="map"></div>
			<div id="maplocationkey">
				<table id="locationtypes">
					<tr>
						<td style="padding-left:5px;"><p class="searchbar_head" style="float: left;font-size: 17px;">Also Show me...</p></td>
							<td>
								<input type="checkbox" name="locations" id="food" onclick="placeRequestClicked('food')">
								<img src="<?php echo $img_dir ?>/place_markers/food.png"><p>Bars &amp; Restaurants</p>
						</td>
	
						<td>
							<input type="checkbox" name="locations" id="music" onclick="placeRequestClicked('music')">
							<img src="<?php echo $img_dir ?>/place_markers/music.png">
							<p>Music Venues</p>
						</td>
	
						<td>
							<input type="checkbox" name="locations" id="museum" onclick="placeRequestClicked('museum')">
							<img src="<?php echo $img_dir ?>/place_markers/museum.png">
							<p>Museums &amp; Galleries</p>
						</td>						
					</tr>			
					<tr>
						<td>
							<input type="checkbox" name="locations" id="sports" onclick="placeRequestClicked('sports')">
							<img src="<?php echo $img_dir ?>/place_markers/sports.png">
							<p>Sports &amp; Outdoors</p>
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
				</table> <!-- table#locationtypes -->
			</div> <!-- #maplocationkey-->
		</div> <!-- #mapcontentholder -->
		
		<div id="sidebar-header" class="alignright">
			<p class="sidebartogglecurrent">Upcoming Events</p>
		</div>
		<div id="event-sidebar" class="alignright">
			<div id="sidebar-content"></div> <!-- Actual event results dynamically loaded into here -->
			<div id="sidebar-search-status"> <!-- Only visible while waiting for an AJAX response -->
				<img src="<?php bloginfo('stylesheet_directory'); ?>/images/loading.gif"/>
				<h4>Loading...</h4> 
			</div>
			<div id="sidebar-footer"></div>	<!-- Load more events button will be rendered in here if applicable -->
		</div> <!-- #event-sidebar -->
	</div> <!-- #main-content -->
	
	<div id="space">
		<div class="spacecolumn alignleft"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Ad Space 1') ) : endif; ?></div>
		<div class="spacecolumn alignleft"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Ad Space 2') ) : endif; ?></div>
		<div class="spacecolumn alignleft"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Ad Space 3') ) : endif; ?></div>
		<div class="spacecolumnlast alignleft"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Ad Space 4') ) : endif; ?></div>
	</div> <!-- #space -->

</div> <!-- #wrapper -->

<script src="<?php bloginfo('stylesheet_directory'); ?>/scripts/map.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/scripts/frontpage.js"></script>

<?php get_footer(); ?>