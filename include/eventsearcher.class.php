<?php  

#require_once '../etc/config.php';

class EventSearcher {
	public function __construct() {
		$this->q_loc = FALSE;
		$this->q_org = FALSE;
		$this->q_att = FALSE;	

		$this->f_loc = NULL;	// will be 3-tuple of (lat,long,rad) if set
		$this->f_eid = NULL;	// will be simple event id string if set
		$this->f_sdate = NULL;	// will be date string (no time) if set
		$this->f_edate = NULL;	// will be date string (no time) if set
		$this->f_att = NULL;	// will be user id string if set
		$this->f_kw = NULL;		// will be an array of keywords if set

		$this->query_uid = NULL; // will be a user id if q_att/f_att is set
		
		$this->more_results_exist = FALSE;

		$this->query_args = array();
	}

	public function queryLocation() {
		$this->q_loc = TRUE;
	}
	public function queryOrganization() {
		$this->q_org = TRUE;	
	}

	// if filterByAttendance has already been called with a userid, it can be omitted
	public function queryAttendance($userid=NULL) {
		$this->q_att = TRUE;
		$this->query_uid = $userid;
	}

	public function filterByDistance($lat,$long,$radius) {
		$this->f_loc = array($lat,$long,$radius);
	}
	public function filterByEventId($eid) {
		$this->f_eid = $eid;
	}

	public function filterByStartDate($date) {
		$this->f_sdate = $date;
	}

	public function filterByEndDate($date) {
		$this->f_edate = $date;	
	}

	// if queryAttendance has already been called with a userid, it can be omitted
	public function filterByAttendance($userid=NULL) {
		if($userid!==NULL) {
			$this->query_uid = $userid;	// just ensure genereal WHERE and bookings WHERE clauses match on ID
		}
		$this->f_att = $this->query_uid;
	}

	public function filterByKeyword($kw) {
		$this->filterByKeywords(array($kw));
	}
	public function filterByKeywords($kw_array) {
		$this->f_kw = $kw_array;
	}


/* QUERY BUILDING 

	There are two basic kinds of queries: One with all event info in entire 
	DB, and one with a specified center point. Either of these queries can be
	made more selective by adding the following filters:
	- startdate and/or enddate
	- keyword search
	- offset and/or limit (default limit is 100)

	The query will be built piecemeal in different variables as follows:
	1. the SELECT statement with the optional distance calculation
	2. the optional LEFT JOIN with the bookings table for logged-in "Count me in" status
	3. static clauses for FROM, WHERE, and ORDER BY
	4. a HAVING clause built using only the search criteia provided
	5. a LIMIT clause to limit the results as specified by the caller, or a
			default setting of "LIMIT 0,100"

	Also, an array of values to bind to the PDO query will be built in 
	$query_args when the option's text is added to a clause.
*/

	// returns array of event dicts
	public function runQuery($offset=NULL,$limit=20) {
		$offset = intval($offset);
		$limit = intval($limit);	
		
		$query = $this->buildSelect() . ' ' .
				$this->buildFrom() . ' ' .
				$this->buildWhere() . ' ' .
				'ORDER BY e.event_end_date ASC, e.event_start_date DESC' . ' ' .
		 		"LIMIT $offset, " . ($limit+1);
		 
		 // connect to DB and run query
		try {
			$pdo = new PDO('mysql:host='.OIP_DB_HOST.';dbname='.OIP_DB_NAME, 
							OIP_DB_USER, OIP_DB_PASSWORD);
			$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$statement = $pdo->prepare($query);
			$statement->execute($this->query_args);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e) {  
		    die('PDO MySQL error: ' . $e->getMessage());  
		} 

		$all_events = array();
		$counter = 0;
		while($row = $statement->fetch()) {
			$counter++;
			if($counter > $limit) break;
			// GDN: this isn't the cleanest way to do this. Other better ways might exist (e.g. PDO 
			// 	FETCH_CLASS with a constructor), but we'll worry about that after the site overhaul

			// Fill in the NOT NULL entries currently include event_id, event_name, event_slug, event_start_date, 
			//		event_start_time, event_end_time, address, organization
			
			$new_event =
				array(	'id'			=> intval($row['event_id']),
						'name'			=> htmlentities($row['event_name'],ENT_QUOTES,'ISO-8859-1',FALSE),
						'wp_slug'		=> $row['event_slug'],
						'description_short' => ($row['event_notes'] !== NULL) ? 
												htmlentities($row['event_notes'],ENT_QUOTES,'ISO-8859-1',FALSE) : NULL,
						'description'   => ($row['event_notes_full'] !== NULL) ? 
												htmlentities($row['event_notes_full'],ENT_QUOTES,'ISO-8859-1',FALSE) : NULL,
						'categories'	=> ($row['event_type'] !== NULL) ?
												explode(',',htmlentities($row['event_type'],ENT_QUOTES,'ISO-8859-1',FALSE)) : NULL,
						'image_url'		=> $row['event_pic'],
						'start_dt'	=> NULL,
						'end_dt'		=> NULL );

			if($row['event_start_date'] && $row['event_start_time']) {
				$new_event['start_dt'] = new DateTime( 
					date("F j, Y", strtotime($row['event_start_date'])) . ' ' . 
					date("g:i a", strtotime($row['event_start_time']))
					);
			}
			if($row['event_end_date'] && $row['event_end_time']) {
				$new_event['end_dt'] = new DateTime( 
					date("F j, Y", strtotime($row['event_end_date'])) . ' ' . 
					date("g:i a", strtotime($row['event_end_time']))
					);
			}

			if($this->q_att) {
				$new_event['attending'] = $row['booking_spaces']==1;
			}

			if($this->q_loc) {
				$new_event['address'] = htmlentities($row['location_address'],ENT_QUOTES,'ISO-8859-1',FALSE);
				$new_event['lat'] = ($row['location_latitude'] !== NULL) ?
												floatval($row['location_latitude']) : NULL;
				$new_event['long'] = ($row['location_longitude'] !== NULL) ?
												floatval($row['location_longitude']) : NULL;
			}

			if($this->q_org) {
				$new_event['org_name'] = htmlentities ($row['organization_name'],ENT_QUOTES,'ISO-8859-1',FALSE);
				$new_event['org_url'] = $row['organization_link_url'];
				$new_event['org_fancount'] = $row['organization_fan_count'];
			}

			$all_events[] = $new_event;
		}

		$this->more_results_exist = ($counter>$limit);
		return $all_events;
	}

	// returns TRUE if more results were available than the current query returned
	public function moreResultsAvailable() {
		return $this->more_results_exist;
	}

	private function buildSelect() {
		$select = "SELECT DISTINCT e.event_name, 
							e.event_id, 
							e.event_notes as event_notes_full,
							SUBSTRING_INDEX(e.event_notes, ' ', 30) as event_notes,
							e.event_start_date, 
							e.event_end_date, 
							e.event_start_time, 
							e.event_end_time, 
							e.event_slug, 
							e.event_pic,
							e.event_type";
		if($this->q_org||$this->f_kw!==NULL) {
			$select .= ", o.name AS organization_name, 
							o.link_url AS organization_link_url,
							o.fan_count AS organization_fan_count";
		}
		if($this->q_loc||$this->f_loc!==NULL) {
			$select .= ", l.location_address, 
							l.location_latitude, 
							l.location_longitude";

			if($this->f_loc!==NULL) {
				$select .= ", ( 3959 * acos( cos( radians(:lat) ) * cos( radians( l.location_latitude ) ) * cos( radians( l.location_longitude ) - radians(:long) ) + sin( radians(:lat) ) * sin( radians( l.location_latitude ) ) ) ) AS distance";
				$this->query_args['lat'] = $this->f_loc[0];	
				$this->query_args['long'] = $this->f_loc[1];
			}
		}

		if($this->q_att||$this->f_att!==NULL) {
			$select .= ", b.booking_spaces";
		}
		return $select;
	}

	private function buildFrom() {
		$from = "FROM wp_em_events e";
		$bookings_clause = 'JOIN wp_em_bookings b ON (e.event_id = b.event_id) AND (b.person_id = :uid) AND (b.booking_spaces = 1)';
		

		if($this->f_att!==NULL) {
			$from .= ' JOIN wp_em_bookings b ON (e.event_id = b.event_id) AND (b.person_id = :uid) AND (b.booking_spaces = 1)';
			$this->query_args['uid'] = $this->query_uid;
		}
		elseif($this->q_att) {
			$from .= ' LEFT JOIN wp_em_bookings b ON (e.event_id = b.event_id) AND (b.person_id = :uid)';
			$this->query_args['uid'] = $this->query_uid;
		}

		if($this->q_loc||$this->f_loc!==NULL) {
			$from .= " JOIN wp_em_locations l USING (location_id)";
		}

		if($this->q_org||$this->f_kw!==NULL) {
			$from .= " JOIN wp_em_organizations o";	
		}
		return $from;
	}

	// builds where and having clauses
	private function buildWhere() {
		$where_clauses = array();
		
		if($this->q_org||$this->f_kw!==NULL) {	// need to include this clause any time org table is queried
			$where_clauses[] = 'o.id = e.event_creator';
		}
		if($this->f_eid!==NULL) {
			$where_clauses[] = 'e.event_id = :eid';
			$this->query_args['eid'] = $this->f_eid;
		}
		if(count($where_clauses) > 0)
		{
			$where = "WHERE (" . implode($where_clauses,") AND (") . ")";
		}
		else {
			$where = 'WHERE 1';
		}

		// build HAVING clauses for date/radius/keyword filtering
		$having_clauses = array();
		if($this->f_sdate!==NULL) {
			# this is not a typo: we want all events that end after our query's start date
			$having_clauses[] = 'e.event_end_date >= :startdate';
			$this->query_args['startdate'] = $this->f_sdate;
		}
		if($this->f_edate!==NULL) {
			# this is not a typo: we want all events that start before our end date
			$having_clauses[] = 'e.event_start_date <= :enddate';
			$this->query_args['enddate'] = $this->f_edate;
		}
		if($this->f_loc!==NULL) {
			$having_clauses[] = "distance < :rad";
			$this->query_args['rad'] = $this->f_loc[2];
		}

		if($this->f_kw!==NULL) {
			$term_clauses = array();
			$i = 0;
			foreach ($this->f_kw as $term) {
				$term_clauses[] = "organization_name rLIKE :keyword$i OR 
									e.event_name rLIKE :keyword$i OR 
									e.event_type rLIKE :keyword$i OR 
									event_notes_full rLIKE :keyword$i";
				$this->query_args["keyword$i"] = $term;
				$i++;
			}
			$having_clauses[] = implode(' OR ', $term_clauses);
		}

		if( $where == 'WHERE' ) {
			$where = 'WHERE 1';	
		}

		// if any having subclause was set above, create a HAVING clause here
		if(count($having_clauses) > 0)
		{
			$having = "HAVING (" . implode($having_clauses,") AND (") . ")";
			$where .= " " . $having;
		}
		return $where;
	}
}