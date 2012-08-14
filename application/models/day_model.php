<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Day_model extends CI_Model {
	private $DAY_TABLE = 'event_day';
	private $PRODUCT_TABLE = 'product';
	private $USER_TABLE = 'user';
	private $DAY_SECONDS = 86400;
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	//	returns an array of info about the event day
	//	['products']		result_array
	//	['user']['id']		user id
	//	['user']['email']	user email
	//	['created']			ts when day was saved
	public function read( $id_event=NULL, $date=NULL ){
		if( is_null($id_event) || is_null($date) ){
			return FALSE;	
		}

		//	copy these so you can actually read this
		$p = $this->PRODUCT_TABLE;
		$d = $this->DAY_TABLE;
		$u = $this->USER_TABLE;

		$this->db->select( "$p.*, $d.ts_created, $d.count, $u.email, $u.id \"id_user\"" );
		$this->db->join( $d, "$d.id_product = $p.id" );
		$this->db->join( $u, "$u.id = $d.id_user" );
		
		if( $data['products'] = $this->db->get_where( $p, array( 'id_event' => $id_event, 'date'=>$date ) )->result_array() ){
			$data['user']['id'] = $data['products'][0]['id_user'];
			$data['user']['email'] = $data['products'][0]['email'];
			$data['created'] = date( 'n/j/Y h:i:s a', $data['products'][0]['ts_created'] );
//			$data['total_count'] = 
			return $data;
		} else {
			return FALSE;
		}
	}
	
	//	$products['id_product']['count']
	public function create( $id_event=NULL, $date=NULL, $products=NULL ){
		if( is_null($id_event) || is_null($date) || is_null($products) ){
			return FALSE;	
		}
		$id_user = $this->techniart->getCurrentUserId();
		$ts_created = time();
		foreach($products as $id_product=>$count){
			$data = array(
				'id_user'=>$id_user,
				'ts_created'=>$ts_created,
				'id_event'=>$id_event,
				'id_product'=>$id_product,
				'count'=>$count,
				'date'=>$date
			);
			if( !$q = $this->db->insert( $this->DAY_TABLE, $data ) ){
				return FALSE;
			}
		}
		return TRUE;
	}
	
	/*
	|	Remove all event day data.
	*/
	public function delete( $id_event ){
		return $this->db->delete( $this->DAY_TABLE, array( 'id_event'=>$id_event ) );	
	}
	
	/*
	|	Returns a boolean if the day has been submitted already.
	|	This is much leaner than using read() for each day when building the calendar.
	*/
	public function daySubmitted( $id_event = NULL, $date = NULL ){
		$this->db->where( array( 'id_event'=>$id_event, 'date'=>$date ) );
		$this->db->from( $this->DAY_TABLE);
		return $this->db->count_all_results() > 0;
	}
	
}