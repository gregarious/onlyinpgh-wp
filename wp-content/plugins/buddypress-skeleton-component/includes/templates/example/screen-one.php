<?php get_header() ?>

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
				</div>
				<div onload="profileMap();" id="profilemapcontentholder">
<div style="float:left;">
<div id="map" class="profilemap"></div>
<div id="maplocationkey">
<table id="locationtypes">
<tbody>
<tr>
<td style="padding-left: 5px;">
<p class="searchbar_head" style="float: left; font-size: 17px;">Also Show me...</p>
</td>


<td><input id="food" onclick="placeRequest('food')" name="locations" type="checkbox" value="food" /><img src="<?php bloginfo('url'); ?>/place_dots/food.png" alt="" /><p>Bars &amp; Restaurants</p></td>


<td><input id="music" onclick="placeRequest('music')" name="locations" type="checkbox" value="music" /><img src="<?php bloginfo('url'); ?>/place_dots/music.png" alt="" /><p>Music Venues</p></td>


<td><input id="museum" onclick="placeRequest('museum')" name="locations" type="checkbox" value="museum" /><img src="<?php bloginfo('url'); ?>/place_dots/museum.png" alt="" /><p>Museums &amp; Galleries</p></td>
</tr>
<tr>
<td><input id="sports" onclick="placeRequest('sports')" name="locations" type="checkbox" value="sports" /><img src="<?php bloginfo('url'); ?>/place_dots/spots.png" alt="" /><p>Sports &amp; Outdoors</p></td>


<td><input id="shops" onclick="placeRequest('shops')" name="locations" type="checkbox" value="shops" /><img src="<?php bloginfo('url'); ?>/place_dots/shopping.png" alt="" /><p>Shops</p></td>


<td><input id="theater" onclick="placeRequest('theater')" name="locations" type="checkbox" value="theater" /><img src="<?php bloginfo('url'); ?>/place_dots/theaterfilm.png" alt="" /><p>Theaters</p></td>


<td><input id="attraction" onclick="placeRequest('attraction')" name="locations" type="checkbox" value="attractions" /><img src="<?php bloginfo('url'); ?>/place_dots/attractions.png" alt="" /><p>Attractions</p></td>
</tr>
</tbody>
</table>
</div>
</div>
<div style="float:right;">
<div onclick="showEvent()"><p id="event" class="sidebartogglecurrent">Your Upcoming Events</p></div>
<div id="resultsholder"><div id="event_sidebar"></div><div id="morebutton"><input id="loadmore"  type="button" onclick="searchTen()" value="Load My Next 10 Events"></div></div>
</div>
</div>

			</div><!-- #item-body -->

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer() ?>