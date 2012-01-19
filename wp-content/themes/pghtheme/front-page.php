<?php 

$img_dir = get_bloginfo('stylesheet_directory') . '/images';
get_header(); ?>

<div id="wrapper"> 
	<div id="eventsearch_bar">
		<div id="dropdown_search">
			<ul>
				<li class="border">
					<p class="searchbar_head">Find upcoming
events</p></li>
				<li class="border"> 
					<input id="keywordsearch" style="margin: 0px 10px 0px 10px;" onkeydown="javascript:return submitonEnter(event);" size="30" type="text" value="Searching by keyword" onfocus="clearkeywordtip(this)" />
				</li>
			</ul>
			
			<button class="submit-button alignright" onclick="newSearchRequested();clearChecks();jqCheckAll('event_types','events',1)">Submit</button>

		</div> <!-- #dropdown_search -->
		<div id="placesearch">
		<p>Event<br>Types</p>
				<ul class="eventbuttons">
<li onclick="typeButtonSearch('food & drink')"><img class="buttonicon" src="http://onlyinpgh.com/wp-content/themes/pghtheme/images/event_markers/food.png"> Food</li>
<li onclick="typeButtonSearch('shopping')"><img class="buttonicon" src="http://onlyinpgh.com/wp-content/themes/pghtheme/images/event_markers/retail.png"> Shopping</li>
<li onclick="typeButtonSearch('music')"><img class="buttonicon" src="http://onlyinpgh.com/wp-content/themes/pghtheme/images/event_markers/music.png"> Music</li>
<li onclick="typeButtonSearch('theater')"><img class="buttonicon" src="http://onlyinpgh.com/wp-content/themes/pghtheme/images/event_markers/theaterfilm.png"> Theater & Film</li>
<li onclick="typeButtonSearch('educational')"><img class="buttonicon" src="http://onlyinpgh.com/wp-content/themes/pghtheme/images/event_markers/education.png"> Education</li>
<li onclick="typeButtonSearch('arts')"><img class="buttonicon" src="http://onlyinpgh.com/wp-content/themes/pghtheme/images/event_markers/arts.png"> Arts</li>
<li onclick="typeButtonSearch('general fun')"><img class="buttonicon" src="http://onlyinpgh.com/wp-content/themes/pghtheme/images/event_markers/genfun.png"> General Fun</li>
<li onclick="typeButtonSearch('sports')"><img class="buttonicon" src="http://onlyinpgh.com/wp-content/themes/pghtheme/images/event_markers/outdoors.png"> Sports & Outdoors</li>
<li class="clear" onclick="clearMarkers()">Clear Map</li>
</ul>
				
				
						</div>
	</div> <!-- #eventsearch_bar -->

	<div id="main-content" style="padding: 0px;">
		<div id="mapcontentholder">
			<div id="map"></div>
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