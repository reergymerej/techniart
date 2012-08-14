<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_summary_model extends CI_Model{
	private $EVENT_SUMMARY_TABLE = 'event_summary';
	private $EVENT_TABLE = 'event';
	private $USER_TABLE = 'user';
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function load( $id_event=NULL ){
		if( is_null($id_event) ){
			return FALSE;	
		}

		//	shorten table refs for readability
		$e = $this->EVENT_SUMMARY_TABLE;
		$u = $this->USER_TABLE;
		
		//	query
		$this->db->select( "$e.*, $u.id id_user, $u.email" );
		$this->db->join( $u, "$u.id = $e.id_user", 'left' );
		if( $result = $this->db->get_where( $this->EVENT_SUMMARY_TABLE, array('id_event'=>$id_event) )->row_array() ){
			//	convert date
			$result['date_closed'] = date( $this->techniart->getTimeFormat(), $result['date_closed'] );
			return $result;	
		} else {
			return FALSE;
		}
	}
	
	/*
	|	Save the summary for an event.
	|	Returns FALSE on failure, TRUE on success.
	|	$data['id_event']	(required)
	|	$data['notes']
	*/
	public function create($data = array()){
		if( empty($data) || !isset($data['id_event']) ){
			return FALSE;	
		}
		
		//	add default data
		$data['id_user'] = $this->techniart->getCurrentUserId();
		$data['date_closed'] = time();
		if( empty($data['notes']) ){
			unset( $data['notes'] );	
		}
		
		//	add the summary
		if( !$this->db->insert( $this->EVENT_SUMMARY_TABLE, $data ) ){
			return FALSE;	
		}
		
		//	mark the event as finished
		$e = $this->EVENT_TABLE;
		return $this->db->update( $e, array('finished'=>1, 'status'=>'finished'), "$e.id = {$data['id_event']}" );
		
	}
	
	public function delete( $id_event ){
		return $this->db->delete( $this->EVENT_SUMMARY_TABLE, array( 'id_event'=> $id_event ) );	
	}
}