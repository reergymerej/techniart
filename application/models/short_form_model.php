<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Short_form_model extends CI_Model{
	private $SHORT_FORM_TABLE = 'short_form';
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	/*
	*	Create new short form.
	*	Returns TRUE on success, FALSE on failure.
	*/
	public function create( $id_event, $id_user ){
		$data = array(
			'id_event' => $id_event,
			'id_user' => $id_user,
			'ts_created' => time()
		);
		if( $this->db->insert( $this->SHORT_FORM_TABLE, $data ) ){
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
//	/*
//	*	Loads event products from db.
//	*	Returns array of user(s) on success, FALSE on failure.
//	*	$id_event	:	event id
//	*/
//	public function load( $id_event ){
//		$this->db->select( $this->SHORT_FORM_TABLE . '.id_product, ' . $this->PRODUCTS_TABLE . '.*' );
//		//	join in products' info from products table
//		$this->db->join( $this->PRODUCTS_TABLE, $this->SHORT_FORM_TABLE . '.id_product = ' . $this->PRODUCTS_TABLE . '.id', 'left');
//		return $this->db->get_where( $this->SHORT_FORM_TABLE, array( 'id_event' => $id_event ) )->result_array();
//	}
//	
//	/*
//	*	Deletes event_products from db.
//	*	Returns TRUE on success, FALSE on failure.
//	*	$id	:	event id to delete
//	*/
//	public function delete( $id_event=NULL ){
//		if( is_null( $id_event ) ){
//			return false;	
//		}
//		$this->db->where('id_event', $id_event);
//		return $this->db->delete( $this->SHORT_FORM_TABLE );
//	}
}
