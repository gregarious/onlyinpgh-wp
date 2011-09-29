<?php

require_once 'etc/config.php';

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

// assert that any event starts after 4 AM
$start_dt = new DateTime($date_str);
$end_dt = clone $start_dt;
$end_dt->add(new DateInterval('P1D'));
$time_cutoff = '04:01';

$sql = "SELECT event_id, event_name, event_start_date, event_start_time, event_end_date, event_end_time, event_notes, event_pic
			FROM wp_em_events 
			WHERE event_start_date < :enddate AND event_end_date >= :startdate
			ORDER BY event_end_date ASC
			";

try {
	$pdo = new PDO('mysql:host='.OIP_DB_HOST.';dbname='.OIP_DB_NAME, 
					OIP_DB_USER, OIP_DB_PASSWORD);
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$statement = $pdo->prepare($sql);
	$statement->execute(array(	'startdate'=>$start_dt->format('Y-m-d H:i'),
								'enddate'=>$end_dt->format('Y-m-d H:i')));
	$statement->setFetchMode(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {  
    die('PDO MySQL error: ' . $e->getMessage());  
} 

$events = array();
while($row = $statement->fetch()) {
	$events[] = array(
			'id' 		=> intval($row['event_id']),
			'name'		=> htmlentities($row['event_name'],ENT_QUOTES,'ISO-8859-1',FALSE),
			'start_dt'	=> new DateTime($row['event_start_date'] . ' ' . $row['event_start_time']),
			'desc'		=> htmlentities($row['event_notes'],ENT_QUOTES,'ISO-8859-1',FALSE),
			'pic'		=> htmlentities($row['event_pic'],ENT_QUOTES,'ISO-8859-1',FALSE), 
			'end_dt'	=> new DateTime($row['event_end_date'] . ' ' . $row['event_end_time']),
			'host'		=> htmlentities($row['organization_name'],ENT_QUOTES,'ISO-8859-1',FALSE),
			''			=> 
	);
}

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
	<!--***** Hardcoding a URL! *****-->
	<a href="/onlyinpgh/event/?eid=<?php echo $event['id']; ?>">
	<li>
		<div id="img-container">
			<img src="<?php echo $event['pic']; ?>" class="alignleft">
		</div>
		<h3 class="el-name">
			<?php echo $event['name']; ?>
		</h3>
	
		<?php	
		// Only show 40 words of description
		$desc =$event['desc'];
		$array = explode(" ",$desc,31);
		unset($array[30]);
		$limited = implode(" ",$array); ?>

		<p class="el-desc alignleft"><?php echo $limited; ?>...</p>

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
			<h4 class="host">Host's Name Here</h4>
			<p class="event-address">2345 Booty Lane</p> 
		</div> <!-- #el-host-address -->

	</li></a><?php
}
?>
</ul>