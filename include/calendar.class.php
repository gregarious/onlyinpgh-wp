<?php

require_once('etc/config.php');

class TwoWeekCalendar {
	public function __construct($anchor_date_str) {
		$this->anchor_dt = new DateTime($anchor_date_str);

		$iter_dt = clone $this->anchor_dt;
		$day_interval = new DateInterval('P1D');

		// determine the beginning and end of the two week interval
		while($iter_dt->format('N')!='7') {
			$iter_dt->sub($day_interval);
		}
		$this->start_dt = clone $iter_dt;
		$iter_dt->add(new DateInterval('P14D'));
		$this->end_dt = clone $iter_dt;
		$this->date_list = array();

		// Create the master date_list
		$iter_dt = clone $this->start_dt;
		$end_str = $this->end_dt->format('Y-m-d');
		do {
			$iter_dt_str = $iter_dt->format('Y-m-d');	
			$this->date_list[] = $iter_dt_str;
			$iter_dt->add($day_interval);
		} while( $iter_dt_str < $end_str );

		$first_date_str = $this->start_dt->format('Y-m-d');
		$events = $this->findAllEvents($first_date_str,$end_str);

		// Build the three main event arrays
		$this->single_date_events = array();
		$this->multi_date_events = array();
		$this->ongoing_events = array();

		// cycle thru each event and put each in one or more of the arrays
		$iter_dt = clone $this->end_dt;
		$last_date_str = $iter_dt->sub($day_interval)->format('Y-m-d');
		foreach($events as $event) {
			// clamp each start/end date to one within the calendar
			$event_start_str = max($this->getDateClassification($event['start_dt']),$first_date_str);
			$event_end_str = min($this->getDateClassification($event['end_dt']),$last_date_str);

			// if event start and end date are the same, save it in the single bucket
			if($event_start_str === $event_end_str) {
				$this->single_date_events[$event_start_str][] = $event;
			}
			// otherwise, save it as a multi day event stored with all others sharing the same day
			else {
				if($event_start_str == $first_date_str && $event_end_str == $last_date_str) {
					$this->ongoing_events[] = $event;
				}
				$iter_dt = new DateTime($event_start_str);
				$dt_str = $event_start_str;
				do {
					$this->multi_date_events[$dt_str][] = $event;
					$iter_dt->add($day_interval);
					$dt_str = $iter_dt->format('Y-m-d');
				} while ( $dt_str <= $event_end_str );

			}
		}
	}

	// returns a DateTime object representing the first day on the calendar
	public function getFirstDate() {
		return (clone $this->start_dt);
	}

	// returns a DateTime object representing the last day on the calendar
	public function getLastDate() {
		$dt = clone $this->end_dt;
		return $dt->sub(new DateInterval('P1D'));
	}

	// returns a DateTime object for the "anchor" date
	public function getAnchorDate() {
		return clone $this->anchor_dt;
	}

	/* 
	 * All HTML rendering code 
	 */
	public function display() {
		print '<ul id="wk1-list">';
		$this->printWeek(array_slice($this->date_list,0,7));
		print '</ul>';

		print '<ul id="wk2-list">';
		$this->printWeek(array_slice($this->date_list,7,7));
		print '</ul>';

		$this->printOngoingEvents();
	}

	private function printWeek($day_list) {
		foreach($day_list as $day) {
			$this->printDay($day,TRUE);
		}
	}

	private function printDay($day,$omit_ongoing=FALSE) {
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
		$daymap = array('S','M','T','W','H','F','S');
		$weekday = $daymap[intval(date('w',strtotime($day)))];
		
		print "<li id='events-list-$day' class='day-list $relative_class'>";

////*** Hard coding a URL! ***///
		print "<a href='onlyinpgh/event-list/?date=$day' class='day-num'>$day_num<span class='alignright'>$weekday</span></a>";
		if(array_key_exists($day,$this->single_date_events)) {
			$events = $this->single_date_events[$day];
			print '<ul class="single-day-events">';
			$this->printEventList($events);
			print '</ul>';
		}
		if(array_key_exists($day,$this->multi_date_events)) {
			if($omit_ongoing) {
				$events = array_diff($this->multi_date_events[$day],$this->ongoing_events);
			}
			else {
				$events = $this->multi_date_events[$day];
			}

			print '<ul class="multi-day-events">';
			$this->printEventList($events);
			print '</ul>';
		}
		print "</li>\n";
	}

	private function printEventList($events) {		
		foreach($events as $event) {
			$id = $event['id'];
			$name = $event['name'];
			$type = explode(',',htmlentities($row['event_type'],ENT_QUOTES,'ISO-8859-1',FALSE));
			$single_type = $type[0];
			$start_time = $event['start_dt']->format('g:i a');
////*** Hardcoding a URL! ****///
			print "<li><a href='onlyinpgh/event/?eid=$id'><span>$single_type</span><br>$name</a></li>";
		}
	}

	private function printOngoingEvents() {
		if( count($this->ongoing_events) ) {
			print '<h3><a href="#" class="btn-slide">Ongoing Events</a></h3>';
			print '<ul id="ongoing-list">';
			$this->printEventList($this->ongoing_events);
			print '</ul>';
		}
	}

	/* Returns an array of events, each event has the following fields:
	 *  - id, name, start_dt, end_dt
	 * Note that the $end day is exclusive (i.e. function will return
	 *  events up to BUT NOT INCLUDING that day).
	 */
	private function findAllEvents($start,$end) {
		// TODO: when doing a DB query, keep the whole 4 AM cutoff thing in mind?
		$sql = "SELECT event_id, event_name, event_start_date, event_start_time, event_end_date, event_end_time,event_type
					FROM wp_em_events 
					WHERE event_start_date < :enddate AND event_end_date >= :startdate
					ORDER BY event_end_date ASC";

		try {
			$pdo = new PDO('mysql:host='.OIP_DB_HOST.';dbname='.OIP_DB_NAME, 
							OIP_DB_USER, OIP_DB_PASSWORD);
			$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$statement = $pdo->prepare($sql);
			$statement->execute(array('startdate'=>$start,'enddate'=>$end));
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
					'end_dt'	=> new DateTime($row['event_end_date'] . ' ' . $row['event_end_time']),
					'type'		=> htmlentities($row['event_type'],ENT_QUOTES,'ISO-8859-1',FALSE),
			);
		}
		return $events;
	}

	/* Returns a Y-m-d date string for the date a particular DateTime should be 
	 *  considered to fall under. Any DateTime that happens before 4:01 AM is considered
	 *  part of the previous day
	 */
	private function getDateClassification($dt) {
		if( $dt->format('H:i') < '04:01' ) {
			return $dt->sub(new DateInterval('P1D'))->format('Y-m-d');
		}
		return $dt->format('Y-m-d');
	}
}

