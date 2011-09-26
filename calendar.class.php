<?php

// TODO: extract GET args

require_once('etc/config.php');

$input = date('Y-m-d');

class TwoWeekCalendar {
	public function __construct($focus_date_string) {
		$this->focus_date = new DateTime($focus_date_string);

		$dt_iter = clone $this->focus_date;
		$day_interval = new DateInterval('P1D');

		// determine the beginning and end of the two week interval
		while($dt_iter->format('N')!='7') {
			$dt_iter->sub($day_interval);
		}
		$start_date = clone $dt_iter;

		$cal_end_str = $dt_iter->add(new DateInterval('P14D'))->format('Y-m-d');

		$events = $this->findAllEvents($start_date->format('Y-m-d'),$cal_end_str);

		$single_date_events = array();
		$multi_date_events = array();
		// cycle thru each event and put each in a bucket
		foreach($events as $event) {
			$event_start = $this->getDateClassification($event['start_dt']);
			$event_end = $this->getDateClassification($event['end_dt']);
			// if event start and end date are the same, save it in the single bucket
			if($event_start === $event_end) {
				$single_date_events[$event_start][] = $event;
			}
			// otherwise, save it as a multi day event stored with all others sharing the same day
			else {
				$dt_iter = new DateTime($event_start);
				$dt_str = $dt_iter->format('Y-m-d');
				do {
					$multi_date_events[$dt_str][] = $event;
					$dt_iter->add($day_interval);
					$dt_str = $dt_iter->format('Y-m-d');
				} while ( $dt_str <= $event_end );
			}
		}

		$dt_iter = $start_date;
		$wk1_days = array();
		$i = 0;
		for(;$i < 7;$i++) {
			$wk1_days[] = $dt_iter->add($day_interval)->format('Y-m-d');
		}
		$wk2_days = array();
		for(;$i < 14;$i++) {
			$wk2_days[] = $dt_iter->add($day_interval)->format('Y-m-d');
		}

		print '<ul>';
		foreach($wk1_days as $day) {
			print '<li>' . $day ; 
			if(array_key_exists($day,$single_date_events)) {
				print '<ul>';
				foreach($single_date_events[$day] as $event) {
					print '<li>' . $event['name'] . '</li>';
				}
				print '</ul>';
			}
			if(array_key_exists($day,$multi_date_events)) {
				print '<ul>';
				foreach($multi_date_events[$day] as $event) {
					print '<li><i>' . $event['name'] . '</i></li>';
				}
				print '</ul>';
			}
			print '</li>';
		}
		print '</ul>';

		print '<ul>';
		foreach($wk2_days as $day) {
			print '<li>' . $day ; 
			if(array_key_exists($day,$single_date_events)) {
				print '<ul>';
				foreach($single_date_events[$day] as $event) {
					print '<li>' . $event['name'] . '</li>';
				}
				print '</ul>';
			}
			if(array_key_exists($day,$multi_date_events)) {
				print '<ul>';
				foreach($multi_date_events[$day] as $event) {
					print '<li><i>' . $event['name'] . '</i></li>';
				}
				print '</ul>';
			}
			print '</li>';
		}
		print '</ul>';
	}

	// return an array of events, each event has the following fields:
	//  - id, name, start_dt, end_dt
	private function findAllEvents($start_date,$end_date) {
		// TODO: when doing a DB query, keep the whole 4 AM cutoff thing in mind?
		$sql = "SELECT event_id, event_name, event_start_date, event_start_time, event_end_date, event_end_time 
					FROM wp_em_events 
					WHERE event_start_date <= :enddate AND event_end_date >= :startdate";

		try {
			$pdo = new PDO('mysql:host='.OIP_DB_HOST.';dbname='.OIP_DB_NAME, 
							OIP_DB_USER, OIP_DB_PASSWORD);
			$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$statement = $pdo->prepare($sql);
			$statement->execute(array('startdate'=>$start_date,'enddate'=>$end_date));;
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

new TwoWeekCalendar('2011-09-24');