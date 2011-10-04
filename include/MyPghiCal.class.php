<?php
//TODO: !!!!! REMOVE BEFORE COMMIT 
define('ABSPATH','/Users/gdn/Sites/public/onlyinpgh/');

require_once(ABSPATH . "include/eventsearcher.class.php");
require_once(ABSPATH . "include/iCalcreator.class.php");

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
		$vcal = new vcalendar( $ic_config );

		$vcal->setProperty("method", "PUBLISH");
		$vcal->setProperty( "X-WR-CALNAME", $title );
		$vcal->setProperty( "X-WR-CALDESC", $desc );

		foreach($results as $event) {
			$vevent = & $vcal->newComponent( 'vevent' );
			$vevent->setProperty('dtstart', dt_to_array($event['start_dt']));
			$vevent->setProperty('dtend', dt_to_array($event['end_dt']));
			$vevent->setProperty('summary',$event['name']);
			$vevent->setProperty('description',$event['description']);
			$vevent->setProperty('location',$event['address']);
			$vevent->setProperty('url','http://www.onlyinpgh.com/event/' . $event['id'] . '/');
		}

		$vcal->returnCalendar();
	}
}