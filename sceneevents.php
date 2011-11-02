<?php
/* Returns a list of events in a given scene (implemented by grabbing
	scenes from a moderators onlyinpgh account)
*/

// Helper function to push dates before 4AM back one day
if(!defined('getDateClassification')) {
function getDateClassification($dt) {
	if( $dt->format('H:i') < '04:01' ) {
		return $dt->sub(new DateInterval('P1D'))->format('Y-m-d');
	}
	return $dt->format('Y-m-d');
}
}

// if wordpress is running, use its ABSPATH just to be sure
if(defined("ABSPATH")) {
	require_once ABSPATH . '/include/eventsearcher.class.php';
	require_once ABSPATH . '/etc/config.php';
} else {
	require_once 'include/eventsearcher.class.php';
	require_once 'etc/config.php';
}

// grab GET args
$scene_name = $_GET['sc'];
$offset = $_GET['offset'] ? intval($_GET['offset']) : 0;
$limit = 4;

// translate the scene tag to a WP user ID
$scene_id_map = array(	'admin' => '1',
						'art' => '998',
						'music' => '999');
$uid = $scene_id_map[$scene_name];

$searcher = new EventSearcher();
$searcher->queryLocation();		// needed for address
$searcher->queryOrganization();	// needed for org_name
$searcher->setTimezone('US/Eastern');

// Now add filters to only include events scene moderators are attending after the current datetime

// Special logic to make sure WP hasn't screwed with our timezone
// See http://wordpress.stackexchange.com/questions/30946/default-timezone-hardcoded-as-utc
$orig_default_tz = '';
if(function_exists('get_option')) {
	$local_tz = get_option('timezone_string');
	if($local_tz) {
		$orig_default_tz = date_default_timezone_get();
		date_default_timezone_set($local_tz);
	}
} 

$searcher->filterByStartDate(date('Y-m-d H:i'));

if($orig_default_tz) {
	date_default_timezone_set($orig_default_tz);
}

$searcher->filterByAttendance($uid);

$results = $searcher->runQuery($offset,4);
foreach($results as $result) {
	// Figure out what should go into the date tag below
	$startdt = $result['start_dt'];
	$enddt = $result['end_dt'];
	$time_str = $startdt->format('M j g:ia');
	if($enddt) {
		if(getDateClassification($startdt) === getDateClassification($enddt)) {
			$time_str .= ' &ndash; ' . $enddt->format('g:ia');
		}
		else {
			$time_str .= ' &ndash; ' . $enddt->format('M j g:ia');
		}
	}

	// output event instance
	?>
	<a href="http://www.onlyinpgh.com/event/<?php echo $result['id']; ?>" >
	<li>
		<div id="img-container">
			<img src="<?php echo $result['image_url']; ?>" class="alignleft">
		</div>
		<h3 class="el-name">
			<?php echo $result['name']; ?>
		</h3>

		<div class="scene-event">
			<p class="date"><?php echo $time_str; ?></p>
			<h4 class="host"><?php echo $result['org_name']; ?></h4>
			<p class="event-address"><?php echo $result['address']; ?></p> 
		</div> <!-- #el-host-address -->
	</li>
	</a>
	<?php
}
