<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_model extends CI_Model{
	private $EVENT_TABLE = 'event';
	private $USER_TABLE = 'user';
	private $DATE_FORMAT;
	private $ROW_LIMIT = 50;
	
	function __construct(){
		parent::__construct();
		$this->DATE_FORMAT = $this->techniart->getDateFormat();
	}
	
	//	$filter[]	(optional)	
	//	$filter['sort_by']
	//	$filter['direction']
	//	$filter['filter_criteria']
	//	$filter['filter_field']
	function index($filter=NULL){
		if( is_null( $filter ) ){
			$rows = $this->db->get( $this->EVENT_TABLE )->result_array();	
		} else {
			$this->db->order_by($filter['sort_by'], $filter['direction']);
			
			//	filtering by value
			if(isset($filter['filter_criteria'])){
				
				//	determine field to filter on and filter operator accordingly
				switch($filter['filter_field']){
					//	filter by # employees >= X
					case 'employees':
						$this->db->where('employees >= ',  $filter['filter_criteria']);
						break;
					//	filter by dates on or after X
					case 'date_start':
					case 'date_end':
					case 'site_visit':
						$this->db->where($filter['filter_field'] . ' >= ', strtotime( $filter['filter_criteria'] ) );
						break;
					//	filter by string match on column
					default:
						$this->db->like($filter['filter_field'], $filter['filter_criteria']);
				}
				
			}
			
			///	get rows and return as array
			$this->db->limit( $this->ROW_LIMIT );
			$rows = $this->db->get( $this->EVENT_TABLE )->result_array();
		}
		
		
		//	convert all dates
		foreach( $rows as &$r ){
			$r['date_start'] = date( $this->DATE_FORMAT, $r['date_start'] );
			$r['date_end'] = date( $this->DATE_FORMAT, $r['date_end'] );
			$r['site_visit'] = date( $this->DATE_FORMAT, $r['site_visit'] );
		}
		return $rows;
	}
	
	/*
	|	Create a new event.
	|	$eventData['name']			(required)
	|	$eventData['status']		(required)
	|	$eventData['date_start']	(required)
	|	$eventData['date_end']
	|	$eventData['site_visit']
	|	$eventData['display_date']
	|	$eventData['address']
	|	$eventData['contact_name']
	|	$eventData['contact_phone']
	|	$eventData['contact_email']
	|	$eventData['notes']
	|	$eventData['employees']
	|	$eventData['am']
	*/
	function create( $eventData=array() ){
		if( empty($eventData) ){
			return FALSE;	
		}
		
		if( empty($eventData['name']) || empty($eventData['status']) || empty($eventData['date_start']) ){
			return FALSE;
		}
		
		//	convert data if needed
		if( empty( $eventData['date_end'] ) ){
			$eventData['date_end'] = $eventData['date_start'];
		}
		if( !$eventData['site_visit'] ){	
			$eventData['site_visit'] = NULL;
		} else {
			$eventData['site_visit'] = strtotime($eventData['site_visit']);
		}
		$eventData['date_end'] = strtotime($eventData['date_end']);
		$eventData['date_start'] = strtotime($eventData['date_start']);
		$eventData['date_modified'] = time();
		$eventData['id_user'] = $this->techniart->getCurrentUserId();
		
		if( !$eventData['employees'] ){
			unset($eventData['employees']);
		}
		
		//	add to table
		$q = $this->db->insert( $this->EVENT_TABLE, $eventData );
		return $this->db->insert_id();
	}
	
	/*
	|	Returns an event represented as an array.
	*/
	function read( $id ){
		
		//	get table references for readability
		$e = $this->EVENT_TABLE;
		$u = $this->USER_TABLE;
		
		//	query
		$this->db->select( "$e.*, $u.email" );
		$this->db->join( $u, "$u.id = $e.id_user", 'left' );
		$q = $this->db->get_where( $this->EVENT_TABLE, array("$e.id"=>$id) );
		$row = $q->row_array();
		
		//	convert dates
		if( $row ){
			$row['date_start'] = date( $this->DATE_FORMAT, $row['date_start']);
			$row['date_end'] = date( $this->DATE_FORMAT, $row['date_end']);
			if( $row['site_visit'] ){
				$row['site_visit'] = date( $this->DATE_FORMAT, $row['site_visit']);	
			} else {
				$row['site_visit'] = '';	
			}
			$row['date_modified'] = date( $this->DATE_FORMAT . ' h:i:s a', $row['date_modified']);
			
			//	include the event_users
			$this->load->model('event_users_model');
			$row['users'] = $this->event_users_model->load($id);
		}
		return $row;
	}
	
	/*
	|	Update an existing event.
	|	$id_event					(required)
	|	$eventData['name']			(required)
	|	$eventData['status']		(required)
	|	$eventData['date_start']	(required)
	|	$eventData['date_end']
	|	$eventData['site_visit']
	|	$eventData['display_date']
	|	$eventData['address']
	|	$eventData['contact_name']
	|	$eventData['contact_phone']
	|	$eventData['contact_email']
	|	$eventData['notes']
	|	$eventData['am']
	*/
	function update( $eventData=NULL, $id_event=NULL ){
		if( is_null($eventData) || is_null($id_event) ){
			return FALSE;
		}
		
		//	update event
		$this->db->where('id', $id_event);
		
		//	convert data if needed
		if( empty( $eventData['date_end'] ) ){
			$eventData['date_end'] = $eventData['date_start'];
		}
		$eventData['date_end'] = strtotime($eventData['date_end']);
		$eventData['date_start'] = strtotime($eventData['date_start']);
		if( !$eventData['site_visit'] ){	
			$eventData['site_visit'] = NULL;
		} else {
			$eventData['site_visit'] = strtotime($eventData['site_visit']);
		}
		if( !$eventData['employees'] ){	
			unset($eventData['employees']);
		}
		
		$eventData['date_modified'] = time();
		$eventData['id_user'] = $this->techniart->getCurrentUserId();
		
		//	TODO:: handle any existing event days that fall outside new timeframe
		return $this->db->update( $this->EVENT_TABLE, $eventData );
	}
	
	/*
	|	Delete event and all associated data.
	*/
	function delete( $id ){
		//	images
		$this->load->model('image_model');
		$this->image_model->deleteAllEventImages( $id );
		
		//	event summary
		$this->load->model('event_summary_model');
		if( !$this->event_summary_model->delete( $id ) ){
			return FALSE;	
		}
		
		//	event day
		$this->load->model('day_model');
		if( !$this->day_model->delete($id) ){
			return FALSE;
		}
		
		//	event product
		$this->load->model('event_products_model');
		if( !$this->event_products_model->delete($id) ){
			return FALSE;	
		}
		
		//	event user
		$this->load->model('event_users_model');
		if( !$this->event_users_model->delete($id) ){
			return FALSE;	
		}
		
		//	delete event
		return $this->db->delete( $this->EVENT_TABLE, array( 'id'=>$id ) );
	}
	
	/*
	|	Returns an array of days to build a calendar.
	|	$days['date']			Unix timestamp
	|	$days['pretty_date']	M/D
	|	$days['event_day']		'event_day' if this is part of the event
	|	$days['submitted']		'submitted' if this day has already been submitted to event_day
	*/
	public function getCalendar($event=NULL){
		if( is_null($event) ){
			return FALSE;
		}
		
		function prettyDate($time){
			return date('n/j', $time);	
		}
		
		$this->load->model('day_model');
		
		$DAY = 86400;
		$date_start = strtotime($event['date_start']);
		$date_end = strtotime($event['date_end']);
		
		//	first Sunday <= $date_start
		$calendar_start = $date_start - ( date('w', $date_start) * $DAY );
		//	Saturday >= $date_end
		$calendar_end = $date_end + ( ( 6 - date('w', $date_end) ) * $DAY );
		
		//	load an array of times between calendar's start and end
		$days = array();
		for($i=$calendar_start; $i<=$calendar_end; $i+=$DAY){
			$this_day['date'] = $i;
			$this_day['pretty_date'] = prettyDate($i);
			$this_day['event_day'] = '';
			//	if this is a day of the event, check to see if it has been submitted yet
			if( $date_start <= $i && $i <= $date_end ){
				$this_day['event_day'] = 'event_day';
				$this_day['submitted'] = ( $this->day_model->daySubmitted($event['id'], $i) ) ? 'submitted' : '';
			}
			array_push($days, $this_day);
			unset($this_day);
		}
		
		return $days;
		
	}
}
