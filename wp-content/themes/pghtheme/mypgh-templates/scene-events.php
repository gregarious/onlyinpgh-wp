<h2 class="scene-part-title">What to do?</h2> 
<!--<span id="cal-buttons" class="alignright">
	<a href="" class="day-nav-link" id="cal-nav-prev">&larr; Previous</a>
	<a href="" class="day-nav-link" id="cal-nav-next">Next &rarr;</a>
</span> -->

<ul class="scene-event-list">
<?php

global $bp;
$group = $bp->groups->current_group->name;
$scene_tag = '';
switch($group) {
	case 'Art Scene':
		$scene_tag = 'art';
		break;
	case 'Music Scene':
		$scene_tag = 'music';
		break;
}

// TESTING ONLY: TAKE THIS OUT ZOMG
$scene_tag = 'admin';

?>

<?php
$_GET['sc'] = $scene_tag;
include ABSPATH . '/sceneevents.php';	// print the initial event listings
?>
</ul>

<div class="prev-next-bottom">
	<div id="prev-events">&larr; Earlier</div>
	<div id="next-events">Later &rarr;</div>
</div>

<script type="text/javascript">
// evil globals
event_offset = 0;
scene_tag = "<?php echo $scene_tag; ?>";

function eventsAjaxCall(offset) {
	jQuery.get( '/sceneevents.php', { sc:scene_tag, offset: offset },
				function(data) {
					jQuery('.scene-event-list').html(data);
				}
			);
}

jQuery('#prev-events').click( function() {
	if(event_offset >= 4) {
		event_offset = event_offset - 4;
		eventsAjaxCall(event_offset)
	}
});

jQuery('#next-events').click( function() {
	event_offset = event_offset + 4;
	eventsAjaxCall(event_offset)
});
</script>