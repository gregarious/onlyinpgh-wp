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
$desc = nl2br($results[0]['description']);
$categories = $results[0]['categories'];
$img = $results[0]['image_url'];
$address = $results[0]['address'];
$org_nm = $results[0]['org_name'];
$org_url = $results[0]['org_url'];
$lat = $results[0]['lat'];
$long = $results[0]['long'];
	

// Reformattng start and end dates
$start_dt = $results[0]['start_dt'];
$start_date = $start_dt->format('F j, g:00 a');
$end_dt = $results[0]['end_dt'];

if (onSameDay($start_dt,$end_dt) == true) {
	$end_date = $end_dt->format('g:00 a');
} else {
	$end_date = $end_dt->format('F j, g:00 a');
}

?>

<script type="text/javascript">

function initializeMap() {

	map = new google.maps.Map(document.getElementById("map_canvas"), {
		center: new google.maps.LatLng(<?php echo $lat . ', ' . $long; ?>),
		zoom: 15,
		maxZoom: 18,
		minZoom: 11,
		mapTypeId: 'roadmap',
		scrollwheel: false,
		mapTypeControl: false,
		zoomControl: true,
		zoomControlOptions: {style: google.maps.ZoomControlStyle.LARGE},
	});

	marker = new google.maps.Marker({  
  		position: new google.maps.LatLng(<?php echo $lat . ', ' . $long;  ?>),  
  		map: map,  
  		title: '<?php echo $address; ?>',
  	});

}

//The script for the Count Me in button in the sidebar
function attendEvent(eid) {
	var isloggedin = document.getElementById("isloggedin").value;
	if(isloggedin=='y') {
		//Uses event_id and the id of the logged in user
	   	var user = document.getElementById("loggedinid").value;
	   	var eventid = eid;
	   	jQuery.getJSON('/insertattend.php',
	   					{ 'eventid':eventid,
	   					  'userid':user },
	   					  function(json_data) {
	   					  		if(json_data['status'] == 'success') {
	   					  			updateEventAttendance(eventid);							
	   					  		}
							});
	}
}

// hacky little thing put together to make sure the Count me in buttons are always in sync.
// There are MUCH better solutions, but it would be obsolete in a month.
function updateEventAttendance(eid) {
	document.getElementById(eid + 'window').value = "Added!";
   	document.getElementById(eid + 'window').onclick = '';		
}  

jQuery(document).ready(function() {
	initializeMap();
});

</script>

<div id="wrapper" class="cal">

	<div class="padder">

			<div class="content" id="single-event">

				<h2><?php echo $name; ?></h2>
				
				<h4 class="time"><?php 
					echo $start_date; ?> - <?php echo $end_date; ?>
				</h4>
				
				<div id="details-container">
					<img src="<?php echo $img ?>" class="alignleft">
					<div id="host-address" class="alignleft">
						<p class="hostedby">Hosted By</p>
						<h4 class="host"><a href="<?php $org_url ?>"><?php echo $org_nm; ?></a></h4>
						<p class="address"><?php echo $address; ?></p>
						<?php
						if ($address != "") { ?>
							<h4 class="directions"><a href="http://maps.google.com/maps?saddr=&amp;daddr=<?php echo $address ?>" target="_blank">Get Directions...</a></h4> <?php
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
				
				<?php
					if ($results[0]['attending'] === true && is_user_logged_in()) { ?>
						<input class="attend-button-in alignright" type="button" value="Added!" id="<?php echo $results[0]['id']; ?>window"> <?php
					} else if (!is_user_logged_in()) { ?>
						<a class="simplemodal-login" href="/wp-login.php?redirect_to=<?php echo $_SERVER["REQUEST_URI"]; ?>">
							<input class="attend-button alignright" type="button" value="Login to Add">
						</a><?php
					} else { ?>
						<input class="attend-button alignright" type="button" value="Add to MyPgh" id="<?php echo $results[0]['id']; ?>window" onclick="attendEvent(<?php echo $results[0]['id']; ?>)"> <?php
					}
				?>
				
				<p class="desc alignleft"><?php echo $desc ?></p>
				</div> <!-- #details-container -->

			<div id="event-single-map-holder" class="alignright">
				<div id="map_canvas"></div>
			</div>
			
		</div> <!-- #single-event.content -->

	</div><!-- .padder -->

</div><!-- #wrapper -->
			
<?php get_footer() ?>
