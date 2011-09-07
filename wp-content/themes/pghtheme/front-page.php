<?php get_header(); ?>

<div id="wrapper"> 
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div id="main-content" style="padding: 0px;">
<?php the_content(); ?>
<div id="mapcontentholder">
<div style="float:left;">
<div id="map"></div>
<div id="maplocationkey">
<table id="locationtypes"><tr>
<td style="padding-left:5px;"><p class="searchbar_head" style="float: left;font-size: 17px;">Also Show me...</p></td>

<td><input type="checkbox" name="locations" id="food" onclick="placeRequest('food')"><img src="http://onlyinpgh.com/place_dots/food.png"><p>Bars & Restaurants</p></td>

<td><input type="checkbox" name="locations" id="music" onclick="placeRequest('music')"><img src="http://onlyinpgh.com/place_dots/music.png"><p>Music Venues</p></td>

<td><input type="checkbox" name="locations" id="museum" onclick="placeRequest('museum')"><img src="http://onlyinpgh.com/place_dots/museum.png"><p>Museums & Galleries</p></td></tr><tr>

<td><input type="checkbox" name="locations" id="sports" onclick="placeRequest('sports')"><img src="http://onlyinpgh.com/place_dots/spots.png"><p>Sports & Outdoors</p></td>

<td><input type="checkbox" name="locations" id="shops" onclick="placeRequest('shops')"><img src="http://onlyinpgh.com/place_dots/shopping.png"><p>Shops</p></td>

<td><input type="checkbox" name="locations" id="theater" onclick="placeRequest('theater')"><img src="http://onlyinpgh.com/place_dots/theaterfilm.png"><p>Theaters</p></td>

<td><input type="checkbox" name="locations" id="attractions" onclick="placeRequest('attractions')"><img src="http://onlyinpgh.com/place_dots/attractions.png"><p>Attractions</p></td>

</tr>
</table>
</div>
</div>
<div>
<div onclick="showEvent()"><p id="event" class="sidebartogglecurrent">Upcoming Events</p></div>
<div id="resultsholder"><div id="event_sidebar"></div><div id="morebutton"><input id="loadmore"  type="button" onclick="searchTen()" value="Load Next 100 Events"></div></div>
</div>
</div>
</div>
<?php endwhile; else: ?>
<?php endif; ?>
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
</div>
<?php get_footer(); ?>