<?php


/*
Template Name: Single Event
*/

get_header(); 


/* events.php has already been called in the header, but for whatever
	Wordpressy reason its ouput $results var isn't available here. */
require(ABSPATH . 'events.php'); 

$eid = $results[0]['id'];
$name =  $results[0]['name'];
$desc = $results[0]['description'];
$categories = $results[0]['categories'];
$img = $results[0]['image_url'];
$address = $results[0]['address'];
$org_nm = $results[0]['org_name'];
$org_url = $results[0]['org_url'];
$lat = $results[0]['lat'];
$long = $results[0]['long'];
	
	
// Reformattng start and end dates
$start_dt = $results[0]['start_dt'];
$start_date = $start_dt->format('l, F j, Y');

$end_dt = $results[0]['end_dt'];
$end_date = $end_dt->format('l, F j, Y');
?>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAIAxlYmeYiqMAw_4FlxKuAxSJeLfqeCfYhfLsvdVofOLeDmN8-RSBM0x9k_TkSngZ5_R9YOO_GyNARw" type="text/javascript"></script>


<script type="text/javascript">

    function initialize() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map_canvas"));
        map.setCenter(new GLatLng("<?php echo $lat; ?>", "<?php echo $long; ?>"), 15);
        map.openInfoWindow(map.getCenter(),
                           document.createTextNode("<?php echo $name ?>"));
      } 
    }

    /*function eventInfoWindow() {
    	var html = "<div id='event-infowindow'>";
    	var html += 
    }

    initialize();*/

</script>

<div id="wrapper" class="cal">

	<div class="padder">

			<div class="content" id="single-event">

				<h2><?php echo $name; ?></h2>
				<h4 class="time">Sept 5 10:00pm<?php echo $start_date; ?> - Sept 10, 10:00pm</h4>
				
				<div id="details-container">
					<img src="<?php echo $img ?>" class="alignleft">
					<div id="host-address" class="alignleft">
						<p class="hostedby">Hosted By</p>
						<h4 class="host"><a href="<?php $org_url ?>"><?php echo $org_nm; ?></a></h4>
						<p class="address"><?php echo $address; ?></p>
						<?php
						if ($address != "") { ?>
							<h4 class="directions"><a href="http://maps.google.com/maps?saddr=&daddr=<?php echo $address ?>" target="_blank">Get Directions...</a></h4> <?php
						} ?>
					</div> <!-- #host-address -->

					<!-- Facebook "like" button infrastructure -->
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
  						var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) {return;}
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
						  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
					</script>

					<div class="fb-like" data-href="<?php echo getCanonicalEventURL($eid); ?>" data-send="true" data-width="450" data-show-faces="true" data-font="lucida grande"></div>
					<!-- end of Facebook stuff -->

				<p class="desc alignleft"><?php echo $desc ?></p>
				</div> <!-- #details-container -->
			<?php

			// Need to ask about this more?

			/*if ($results[0]['attending'] == 1) { ?>
				<input class="attend-button alignright" type="button" value="Add to MyPgh" class="attend-button alignright" id="<?php echo $results[0]['id']; ?>window" onclick="attendEvent(<?php echo $results[0]['id']; ?>')"> <?php 
			} else { ?>
				<input class="attend-button-in alignright" type="button" value="Added" class="attend-button" id="<?php echo $results[0]['id']; ?>window"> <?php
			} */?>

			<div id="event-single-map-holder" class="alignright">
				<div id="map_canvas"></div>
			</div>
			
		</div> <!-- #single-event.content -->

	</div><!-- .padder -->

</div><!-- #wrapper -->
			
<?php get_footer() ?>
