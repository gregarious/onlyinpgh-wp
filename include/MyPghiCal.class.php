<?php

require_once("include/eventsearcher.class.php");
require_once("include/phpicalendar/iCalcreator.class.php");

function dt_to_array($dt) {
	return array(
		'year' => $dt->format('Y'),
		'month' => $dt->format('m'),
		'day' => $dt->format('d'),
		'hour' => $dt->format('H'),
		'min' => $dt->format('i'),
		'sec' => $dt->format('s'),
		);
}

class MyPghiCal {
	public function __construct($userid,$filename) {
		$this->uid = $userid;
		$this->filename = $filename;
	}

	public function generate($title="OnlyinPgh Cal",$desc="OnlyinPgh Calendar") {
		$searcher = new EventSearcher(FALSE);

		$searcher->queryLocation();
		$searcher->queryOrganization();
		$searcher->filterByAttendance($this->uid);

		$results = $searcher->runQuery(0,100000);

		$ic_config = array( "unique_id" => "onlyinpgh.com",
							'filename' =>  $this->filename);

		// Since Wordpress is so damn fun, we need to see if it exists and has screwed
		//	with the default local time. If it has, we're temporarily changing it.
		// See http://wordpress.stackexchange.com/questions/30946/default-timezone-hardcoded-as-utc
		$orig_default_tz = '';
		if(function_exists('get_option')) {
			$local_tz = get_option('timezone_string');
			if($local_tz) {
				$orig_default_tz = date_default_timezone_get();
				date_default_timezone_set($local_tz);
			}
		} 
		$vcal = new vcalendar( $ic_config );

		$vcal->setProperty("method", "PUBLISH");
		$vcal->setProperty( "X-WR-CALNAME", $title );
		$vcal->setProperty( "X-WR-CALDESC", $desc );

		foreach($results as $event) {
			$vevent = & $vcal->newComponent( 'vevent' );
			if($event['start_dt']) {
				$vevent->setProperty('dtstart', dt_to_array($event['start_dt']));
			}
			if($event['end_dt']) {
				$vevent->setProperty('dtend', dt_to_array($event['end_dt']));
			}			$vevent->setProperty('summary',$event['name']);
			$vevent->setProperty('description',$event['description']);
			$vevent->setProperty('location',$event['address']);
			$vevent->setProperty('url','http://www.onlyinpgh.com/event/' . $event['id'] . '/');
		}

		$vcal->returnCalendar();

		// see Wordpress rant above
		if($orig_default_tz) {
			date_default_timezone_set($orig_default_tz);
		}
	}
}