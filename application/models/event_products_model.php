<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_products_model extends CI_Model{
	private $EVENT_PRODUCTS_TABLE = 'event_product';
	private $PRODUCTS_TABLE = 'product';
	private $USER_TABLE = 'user';
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	/*
	*	Add rows for each event_product.
	*	Returns TRUE on success, FALSE on failure.
	*	$products[ array( id_product => count_start ) ]
	*/
	public function save( $id_event=NULL, $products=array()){
		if( is_null($id_event) ){
			return FALSE;
		}
		
		//	delete old entries
		$this->db->delete( $this->EVENT_PRODUCTS_TABLE, array( 'id_event'=>$id_event ) );
		
		//	add new entries
		if( !empty($products) ){
			$batch = array();
			
			foreach($products as $id=>$count){
				$row = array(
					'id_event'=>$id_event,
					'id_product'=>$id,
					'count_start'=>$count,
					'date_modified'=>time(),
					'id_user'=>$this->techniart->getCurrentUserId()
				);
				array_push($batch, $row);
			}
			return $this->db->insert_batch( $this->EVENT_PRODUCTS_TABLE, $batch );
		}
		
		return TRUE;
	}
	
	/*
	*	Loads event products from db.
	*	Returns array of user(s) on success, FALSE on failure.
	*	$id_event	:	event id
	*/
	public function load( $id_event ){
		//	make table refs readable
		$ep = $this->EVENT_PRODUCTS_TABLE;
		$p = $this->PRODUCTS_TABLE;
		$u = $this->USER_TABLE;
		
		$this->db->select( "$ep.date_modified, $ep.count_start, $u.email, $u.id id_user, $p.*" );
		$this->db->join( $u, "$u.id = $ep.id_user", 'left' );
		$this->db->join( $p, "$p.id = $ep.id_product", 'right' );
		$this->db->order_by( "$ep.count_start", 'desc' );
		$this->db->order_by( "$p.name", 'asc' );
				
		return $this->db->get_where( $ep, array( "$ep.id_event" => $id_event ) )->result_array();
	}
	
	/*
	*	Deletes event_products from db.
	*	Returns TRUE on success, FALSE on failure.
	*	$id	:	event id to delete
	*/
	public function delete( $id_event=NULL ){
		if( is_null( $id_event ) ){
			return false;	
		}
		$this->db->where('id_event', $id_event);
		return $this->db->delete( $this->EVENT_PRODUCTS_TABLE );
	}
}
