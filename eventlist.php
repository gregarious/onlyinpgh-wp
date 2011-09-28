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

// assert that any event starts after 4 AM
$start_dt = new DateTime($date_str);
$end_dt = clone $start_dt;
$end_dt->add(new DateInterval('P1D'));

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

/* Cycle through the returned query and put each result into an array with the
 *  following keys:
 *	- id 		: integer event ID
 *  - name 		: event name
 *  - start_dt 	: DateTime object
 *  - end_dt 	:   DateTime object
 */
them into 
$events = array();
while($row = $statement->fetch()) {
	$events[] = array(
			'id' 		=> intval($row['event_id']),
			'name'		=> htmlentities($row['event_name'],ENT_QUOTES,'ISO-8859-1',FALSE),
			'start_dt'	=> new DateTime($row['event_start_date'] . ' ' . $row['event_start_time']),
			'desc'		=> htmlentities($row['event_notes'],ENT_QUOTES,'ISO-8859-1',FALSE),
			'pic'		=> htmlentities($row['event_pic'],ENT_QUOTES,'ISO-8859-1',FALSE), 
			'end_dt'	=> new DateTime($row['event_end_date'] . ' ' . $row['event_end_time'])
	);
}

?>
<h3><?php echo $date_str; ?></h3>
<ul class="eventslist-day">
<?php
foreach($events as $event) {
	?>
	<!--***** Hardcoding a URL! *****-->
	<li>
		<img src="<?php echo $event['pic']; ?>" class="alignleft">
		<a href="/onlyinpgh/event/?eid=<?php echo $event['id']; ?>" class="el-name"><?php echo $event['name']; ?></a><br>
		<?php

		// Only show 40 words of description
		$desc =$event['desc'];
		$array = explode(" ",$desc,41);
		unset($array[40]);
		$limited = implode(" ",$array);
		
		$eventlist_pic = '<img src="'.$event['pic'].'">';
		$start_date_str = $event['start_dt']->format('n/j/Y');
		$start_time_str = $event['start_dt']->format('g:ia');
		$end_date_str = $event['end_dt']->format('n/j/Y');
		$end_time_str = $event['end_dt']->format('g:ia');

		echo '<p class="el-time">' . $start_date_str . ' ' . $start_time_str . ' - ';
		if( !onSameDay($event['start_dt'],$event['end_dt']) ) {
			echo $end_date_str . ' ';
		}
		echo $end_time_str . '</p>' . "\n"; ?>
		<p class="el-desc alignright"><?php echo $limited; ?>...</p>
	</li> <?php
}
?>
</ul>