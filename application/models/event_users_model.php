<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_users_model extends CI_Model{
	private $EVENT_USERS_TABLE = 'event_user';
	private $USERS_TABLE = 'user';
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	/*
	*	Loads event users from db.
	*	Returns array of user(s) on success, FALSE on failure.
	*	$id_event	:	event id
	*/
	public function load( $id_event ){

		//	shorten table references
		$eu = $this->EVENT_USERS_TABLE;
		$u = $this->USERS_TABLE;

		$this->db->select( "$u.id, $u.email, $eu.date_modified, $eu.id_user_modified, {$u}2.email modified_email" );
		$this->db->join( $eu, "$u.id = $eu.id_user", 'left' );
		$this->db->join( "$u {$u}2", "{$u}2.id = $eu.id_user_modified", 'left');
		return $this->db->get_where( $u, array( 'id_event' => $id_event ) )->result_array();
	}
	
	/*
	|	Returns an array of all regular users.
	|	Those associated with this event already, have a non-null value for ['id_event']
	*/
	public function listUserChoices( $id_event=NULL ){
		if( is_null($id_event) ){
			return FALSE;	
		}
		
		//	shorten table references
		$eu = $this->EVENT_USERS_TABLE;
		$u = $this->USERS_TABLE;
		
		//	build query
		$this->db->select("$u.id, $u.email, $u.first_name, $u.last_name, $eu.date_modified selected");
		$this->db->from($u);
		$this->db->join($eu, "$u.id = $eu.id_user AND $eu.id_event = $id_event", 'left');
		$this->db->where( "$u.admin = 0" );
		$this->db->order_by( "$eu.date_modified, $u.email" );
		$result = $this->db->get()->result_array();
		return $result;
	}
	
	/*
	|	Clears existing event users and saves new data.
	*/
	public function save( $id_event=NULL, $users=array() ){
		if( is_null($id_event) ){
			return FALSE;	
		}
		
		//	clear old
		$this->delete( $id_event );
		
		//	save new (if needed)
		if( !empty( $users ) ){
			$batch = array();
			foreach($users as $u){
				array_push( $batch, array(
					'id_event'=>$id_event,
					'id_user'=>$u,
					'date_modified' => time(),
					'id_user_modified' => $this->techniart->getCurrentUserId()
				));
			}
			return $this->db->insert_batch( $this->EVENT_USERS_TABLE, $batch );
		}
		
		return TRUE;
	}
	
	/*
	*	Deletes event_users from db.
	*	Returns TRUE on success, FALSE on failure.
	*	$id	:	event id to delete
	*/
	public function delete( $id_event=NULL ){
		if( is_null( $id_event ) ){
			return false;	
		}	
		return $this->db->delete( $this->EVENT_USERS_TABLE, array('id_event'=>$id_event) );
	}
}
