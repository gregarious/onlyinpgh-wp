<?php

require_once('etc/config.php');

class TwoWeekCalendar {
	public function __construct($anchor_date_string) {
		$this->anchor_date = new DateTime($anchor_date_string);

		$dt_iter = clone $this->anchor_date;
		$day_interval = new DateInterval('P1D');

		// determine the beginning and end of the two week interval
		while($dt_iter->format('N')!='7') {
			$dt_iter->sub($day_interval);
		}
		$this->start_date = clone $dt_iter;

		$cal_end_str = $dt_iter->add(new DateInterval('P14D'))->format('Y-m-d');

		$events = $this->findAllEvents($this->start_date->format('Y-m-d'),$cal_end_str);

		$this->single_date_events = array();
		$this->multi_date_events = array();
		// cycle thru each event and put each in a bucket
		foreach($events as $event) {
			$event_start = $this->getDateClassification($event['start_dt']);
			$event_end = $this->getDateClassification($event['end_dt']);
			// if event start and end date are the same, save it in the single bucket
			if($event_start === $event_end) {
				$this->single_date_events[$event_start][] = $event;
			}
			// otherwise, save it as a multi day event stored with all others sharing the same day
			else {
				$dt_iter = new DateTime($event_start);
				$dt_str = $dt_iter->format('Y-m-d');
				do {
					$this->multi_date_events[$dt_str][] = $event;
					$dt_iter->add($day_interval);
					$dt_str = $dt_iter->format('Y-m-d');
				} while ( $dt_str <= $event_end );
			}
		}
	}

	// returns a DateTime object representing the first day on the calendar
	public function getFirstDate() {
		return clone $this->start_date;
	}

	// returns a DateTime object representing the last day on the calendar
	public function getLastDate() {
		$dt = clone $this->start_date;
		return $dt->add(new DateInterval('P13D'));
	}

	public function getAnchorDate() {
		return clone $this->anchor_date;
	}

	public function display() {
		// determine the calendar dates to cycle through
		$day_interval = new DateInterval('P1D');
		$dt_iter = $this->start_date;
		$wk1_days = array();
		$i = 0;
		for(;$i < 7;$i++) {
			$wk1_days[] = $dt_iter->format('Y-m-d');
			$dt_iter->add($day_interval);
		}
		$wk2_days = array();
		for(;$i < 14;$i++) {
			$wk2_days[] = $dt_iter->format('Y-m-d');
			$dt_iter->add($day_interval);
		}

		// begin actual HTML rendering
		print '<ul id="wk1-list">';
		$this->printWeek($wk1_days);
		print '</ul>';

		print '<ul id="wk2-list">';
		$this->printWeek($wk2_days);
		print '</ul>';
	}

	private function printWeek($day_list) {
		foreach($day_list as $day) {
			// link to eventlist.php?date=$day
			$today = date('Y-m-d');
			$relative_class = '';
			if($day < $today) {
				$relative_class = 'past';
			}
			elseif($day == $today) {
				$relative_class = 'today';
			}
			else {
				$relative_class = 'future';
			}

			$day_num = date('d',strtotime($day));
			print "<li id='events-list-$day' class='day-list $relative_class'>";
			print "<a href='eventlist.php?date=$day'>$day_num</a>";
			if(array_key_exists($day,$this->single_date_events)) {
				print '<ul class="single-day-events">';
				$this->printEventList($this->single_date_events[$day]);
				print '</ul>';
			}
			if(array_key_exists($day,$this->multi_date_events)) {
				print '<ul class="multi-day-events">';
				$this->printEventList($this->multi_date_events[$day]);
				print '</ul>';
			}
			print "</li>\n";
		}
	}
	private function printEventList($events) {		
		foreach($events as $event) {
			$id = $event['id'];
			$name = $event['name'];
			print "<li><a href='events.php?eid=$id'>$name</a></li>";
		}
	}

	// return an array of events, each event has the following fields:
	//  - id, name, start_dt, end_dt
	// Note that the $end day is exclusive (i.e. function will return
	//  events up to BUT NOT INCLUDING that day).
	private function findAllEvents($start,$end) {
		// TODO: when doing a DB query, keep the whole 4 AM cutoff thing in mind?
		$sql = "SELECT event_id, event_name, event_start_date, event_start_time, event_end_date, event_end_time 
					FROM wp_em_events 
					WHERE event_start_date < :enddate AND event_end_date >= :startdate
					ORDER BY event_end_date ASC";

		try {
			$pdo = new PDO('mysql:host='.OIP_DB_HOST.';dbname='.OIP_DB_NAME, 
							OIP_DB_USER, OIP_DB_PASSWORD);
			$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$statement = $pdo->prepare($sql);
			$statement->execute(array('startdate'=>$start,'enddate'=>$end));
		}
		catch(PDOException $e) {  
		    die('PDO MySQL error: ' . $e->getMessage());  
		} 

		$events = array();
		while($row = $statement->fetch()) {
			$events[] = array(
					'id' 		=> intval($row['event_id']),
					'name'		=> htmlentities($row['event_name'],ENT_QUOTES,'UTF-8',FALSE),
					'start_dt'	=> new DateTime($row['event_start_date'] . ' ' . $row['event_start_time']),
					'end_dt'	=> new DateTime($row['event_end_date'] . ' ' . $row['event_end_time'])
			);
		}
		return $events;
	}

	// returns a Y-m-d date string for the date a particular DateTime should be 
	//  considered to fall under. Any DateTime that happens before 4:01 AM is considered
	//  part of the previous day
	private function getDateClassification($dt) {
		if( $dt->format('H:i') < '04:01' ) {
			return $dt->sub(new DateInterval('P1D'))->format('Y-m-d');
		}
		return $dt->format('Y-m-d');
	}
}

