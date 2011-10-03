<?php

require_once 'etc/config.php';
require_once 'include/eventsearcher.class.php';

// Returns TRUE if the two DateTimes are on the same day (after taking the 4AM end time cutoff into account)
function onSameDay($dt1,$dt2) {
	if( $dt1->format('H:i') < '04:01' ) {
		$dt1->sub(new DateInterval('P1D'));
	}
	if( $dt2->format('H:i') < '04:01' ) {
		$dt2->sub(new DateInterval('P1D'));
	}

	return $dt1->format('Y-m-d') == $dt2->format('Y-m-d');
}

if(!array_key_exists('date',$_GET)) {
	die();
}

$date_str = $_GET['date'];

// Prev/next day navigation
$date_dt = new DateTime($date_str);
$iter = clone $date_dt;
$prev_day_anchor = $iter->sub(new DateInterval('P1D'))->format('Y-m-d');
$next_day_anchor = $iter->add(new DateInterval('P2D'))->format('Y-m-d');

$date_txt = $date_dt->format('l, F j, Y');

$searcher = new EventSearcher();
$searcher->queryLocation();
$searcher->queryOrganization();
// WP/BP functions -- this means this PHP script won't work without WP calling it
/*if( is_user_logged_in() ) {
	$searcher->queryAttendance(bp_loggedin_user_id());
}*/
$searcher->queryAttendance('1');

$searcher->filterByStartDate($date_str);
$searcher->filterByEndDate($date_str);

$events = $searcher->runQuery(0,10000);		// runQuery needs a result limit, use something huge

echo '<pre>';
echo print_r($events);
echo '</pre>';
?>

<span id="cal-buttons" class="alignright">
	<a href="?date=<?php echo $prev_day_anchor; ?>" class="day-nav-link" id="cal-nav-prev">&lt;&lt;</a>
	<a href="?date=<?php echo $next_day_anchor; ?>" class="day-nav-link" id="cal-nav-next">&gt;&gt;</a>
</span>
<h2 class="page-title"><?php echo $date_txt; ?></h2>
<ul class="eventslist-day">

<?php
foreach($events as $event) {
	?>
	
	<a href="/event/<?php echo $event['id']; ?>" target="_blank">
		<li>
			<div id="img-container">
				<img src="<?php echo $event['image_url']; ?>" class="alignleft">
			</div>
			<h3 class="el-name">
				<?php echo $event['name']; ?>
			</h3>
		
			<?php	
			// Only show 40 words of description
			$desc =$event['description_short'];
			$array = explode(" ",$desc,31);
			unset($array[30]);
			$limited = implode(" ",$array); ?>

			<?php

			$start_date_str = $event['start_dt']->format('M j');
			$start_time_str = $event['start_dt']->format('g:ia');
			$end_date_str = $event['end_dt']->format('M j');
			$end_time_str = $event['end_dt']->format('g:ia');

			echo '<p class="el-time">' . $start_date_str . ' ' . $start_time_str . ' - ';
			if( !onSameDay($event['start_dt'],$event['end_dt']) ) {
				echo $end_date_str . ' ';
			}
			echo $end_time_str . '</p>' . "\n"; ?>

			<div id="el-host-address" class="alignleft">
				<p class="hostedby">Hosted by</h4>
				<h4 class="host"><?php echo $event['org_name']; ?></h4>
				<p class="event-address"><?php echo $event['address'];?></p> 
			</div> <!-- #el-host-address -->

			<p class="el-desc alignleft"><?php echo $limited; ?>...</p>

		</li>
	</a>
		
<?php
}
?>
</ul>