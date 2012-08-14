<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model{
	private $PRODUCTS_TABLE = 'product';
	private $message;
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	/*
	*	Add products.
	*	Returns TRUE on success, FALSE on failure.
	*/
	public function create($name, $price, $model, $type=NULL){
		$data = array(
			'name' => $name,
			'price' => $price,
			'model' => $model,
			'type' => $type
		);

		if( $this->db->insert( $this->PRODUCTS_TABLE, $data ) ){
			return TRUE;
		} else {
			$this->message = 'unable to add product to database';
			return FALSE;
		}
	}
		
	//	$filter[]	(optional)	
	//	$filter['sort_by']
	//	$filter['direction']
	//	$filter['filter_criteria']
	function index($filter=NULL){
		if( is_null( $filter ) ){
			$rows = $this->db->get( $this->PRODUCTS_TABLE )->result_array();
		} else {
			//	filtering by value
			if(isset($filter['filter_criteria'])){
				$this->db->like('name', $filter['filter_criteria']);
				$this->db->or_like('model', $filter['filter_criteria']);
				$this->db->or_like('type', $filter['filter_criteria']);
			}
			$this->db->order_by($filter['sort_by'], $filter['direction']);
			$rows = $this->db->get( $this->PRODUCTS_TABLE )->result_array();
		}
		
		return $rows;
	}
	
	
//	*	Loads products from db.
//	*	Returns array of product(s) on success, FALSE on failure.
//	*	$product_id	:	event id

	public function getProduct( $product_id=NULL ){
		if( is_null( $product_id ) ){
			return FALSE;	
		}
		$this->db->order_by( 'type', 'asc' );
		$this->db->order_by( 'name', 'asc' );
		return $query = $this->db->get_where( $this->PRODUCTS_TABLE, array( 'id' => $product_id ) )->row_array();
	}

	
//	*	Updates db values for existing product.
//	*	Returns TRUE on success, FALSE on failure.
//	*	$id	:	event id to be updated
//	
	public function update( $id=NULL ){
		if( is_null( $id ) ){
			$this->message = 'unable to find product';
			return FALSE;	
		}
		
		$this->db->where('id', $id);
		/*
		$this->form_validation->set_rules('type', 'type', 'trim|xss_clean');
		$this->form_validation->set_rules('name', 'name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('price', 'price', 'trim|decimal|required|xss_clean');
		$this->form_validation->set_rules('model', 'model', 'trim|required|xss_clean');
		*/
		$updateData = array(
			'type' => set_value('type') ? set_value('type') : NULL,
			'name' => set_value('name'),
			'price' => set_value('price'),
			'model' => set_value('model')
		);
		return $this->db->update( $this->PRODUCTS_TABLE, $updateData );
	}
	
	
//	*	Deletes product from db.
//	*	Returns TRUE on success, FALSE on failure.
//	*	$product_id	:	product id to delete
	
	public function delete( $product_id=NULL ){
		if( is_null( $product_id ) ){
			return false;	
		}
		$this->db->where('id', $product_id);
		return $this->db->delete( $this->PRODUCTS_TABLE );
	}

	public function getMessage(){
		return $this->message;	
	}
}
