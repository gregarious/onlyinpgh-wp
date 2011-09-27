<?php
require_once 'include/calendar.class.php';

if(!array_key_exists('anchor',$_GET)) {
	$anchor_date_str = date('Y-m-d');
}
else {
	$anchor_date_str = $_GET['anchor'];
}

$ajax = FALSE;
if(array_key_exists('ajax',$_GET)) {
	$ajax = $_GET['ajax'] == TRUE;
}

$cal = new TwoWeekCalendar($anchor_date_str);
$twowks = new DateInterval('P14D');

$prev_anchor = $cal->getAnchorDate()->sub($twowks)->format('Y-m-d');
$next_anchor = $cal->getAnchorDate()->add($twowks)->format('Y-m-d');

// Don't print the container again if it's an ajax request
if(!$ajax): ?>
<div id="cal-container">
<?php endif; ?>

<div id="cal-header">
	<span id="cal-datespan-text"><?php echo $cal->getFirstDate()->format('M j'); ?> - <?php echo $cal->getLastDate()->format('M j'); ?></span>
	<span id="cal-buttons">
		<a href="calendar.php?anchor=<?php echo $prev_anchor; ?>" class="cal-nav-link" id="cal-nav-prev">&lt;</a>
		<a href="calendar.php?anchor=<?php echo $next_anchor; ?>" class="cal-nav-link" id="cal-nav-next">&gt;</a>
	</span>
</div>

<?php
$cal->display();

if(!$ajax): ?>
</div>
<?php endif; ?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
<script>

/* Function designed to be called when a link is clicked to short circuit the 
 *	normal browser of following the link and instead call the link's "href" via AJAX
 * Arguments:
 *  element - jQuery element for the link clicked
 *  callback - function to execute on successful AJAX return
 */
function ajaxClick(element,callback) {
	$.ajax( {
		url: element.attr('href') + '&ajax=1',
		success: callback,
		datatype: 'html' } );
	return false;
}

// when DOM is loaded, add the click events
$(document).ready(
	function() {
		$('#cal-nav-prev').click( function() { 
			return ajaxClick( 	$('#cal-nav-prev'), 
								function(data) { $('#cal-container').html(data); } ) } 
		);
		$('#cal-nav-next').click( function() { 
			return ajaxClick( 	$('#cal-nav-next'), 
								function(data) { $('#cal-container').html(data); } ) }
		);
	}
);
</script>