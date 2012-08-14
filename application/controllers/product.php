<?php
class Product extends CI_Controller {
	private $contentData;
	
	public function __construct(){
		parent::__construct();
		
		//	users must be logged in
		$this->techniart->securePage();
	
		//	no messages by default
		$this->contentData['message'] = '';
	
		//	dependencies	
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('product_model');
	}
	
	/* 
	*	List all products
	*/	
	public function index(){
		
		//	view separate elements will be loaded into
		$aggregated_view = 'product/product_index';
		
		//	collect separate elements
		$data['product_list'] = $this->productList(TRUE);
		$data['controls'] = $this->techniart->loadControls($aggregated_view);
		$data['content'] = $this->load->view($aggregated_view, $data, TRUE);
		$data['js'] = array('product_index.js');
		$data['title'] = 'Products';
		
		//	load main template
		$this->techniart->load( $data );
	}
	
	//	returns list of products
	//	called by ajax and internally by this script
	//	When called internally, the result should be returned as a string
	//	so it can be passed to the master template.
	public function productList( $returnAsString = FALSE ){
		//	return sorted/filtered lists
		if(isset($_POST['sort_by'])){
			$filter = array(
				'sort_by'=>$_POST['sort_by'],
				'direction'=>$_POST['direction'],
				'filter_criteria'=>$_POST['filter_criteria']);
		} else {
			$filter = array(
				'sort_by'=>'name',
				'direction'=>'asc'
			);	
		}
		
		//	get data from model
		$data['product_list'] = $this->product_model->index($filter);
		$data['filter'] = $filter;
		$this->load->helper('techniart_helper');
		
		//	return view
		return $this->load->view('product/product_list', $data, $returnAsString);
	}
	
	/*
	*	Load editable form with default values.
	*/
	public function create(){
		$data['title'] = 'Create Product';
		
		//	is user submitting form or viewing?
		if( $this->input->post() ){
			//	handle cancelling
			if( strtolower( $this->input->post('submit') ) == 'cancel' ){
				redirect('product');	
			}
			
			
			//	validate form
			$this->form_validation->set_rules('type', 'type', 'trim|xss_clean');
			$this->form_validation->set_rules('name', 'name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('price', 'price', 'trim|decimal|required|xss_clean');
			$this->form_validation->set_rules('model', 'model', 'trim|required|xss_clean');
	
			if( $this->form_validation->run() == TRUE ){
			//	save new product
				$name = set_value('name');
				$price = set_value('price');
				$model = set_value('model');
				$type = set_value('type') ? set_value('type') : NULL;
				
				if( $this->product_model->create( $name, $price, $model, $type ) ){
				//	success
					$this->contentData['message'] = 'product created';
					return $this->index();
				} else {
				//	error creating new user
					$this->contentData['message'] = $this->product_model->getMessage();	
				}

				
			} else {
				$this->contentData['message'] = validation_errors();
			}
		}
		
		
		$data['content'] = $this->load->view('product/product_create', $this->contentData, TRUE);
		$this->techniart->load($data);
	}
	
	/*
	*	Load locked form with values from db.
	*/
	public function read( $id=NULL ){
		if( is_null( $id ) ){
			return $this->productNotFound();	
		}
		
		//	load model
		$this->contentData['product'] = $this->product_model->getProduct( $id );
		if( !$this->contentData['product'] ){
			return $this->productNotFound();
		}
		
		//	view separate elements will be loaded into
		$aggregated_view = 'product/product_view';
		
		//	collect separate elements
		$this->contentData['controls'] = $this->techniart->loadControls($aggregated_view);

		//	load main template
		$data['title'] = 'Product';
		$data['content'] = $this->load->view($aggregated_view, $this->contentData, TRUE);
		$this->techniart->load( $data );
	}
	
	/*
	*	Load editable form with values from db.
	*	Validate input from form.
	*/
	public function edit( $id=NULL ){
		//	something weird happened, show the index
		if( is_null( $id ) ){
			return $this->productNotFound();
		}
		
		//	handle cancel and delete buttons
		switch( strtolower( $this->input->post('submit') ) ){
			case 'cancel':
				redirect( "product/read/$id" );
				break;
			case 'delete':
				return $this->delete( $id );
				break;
		}
		
//		//	load dependencies
		$this->load->helper('form');
		$this->load->library('form_validation');

		if( count( $this->input->post() ) > 1 ){
		//	trying to save
		
			//	validate form
			$this->form_validation->set_rules('type', 'type', 'trim|xss_clean');
			$this->form_validation->set_rules('name', 'name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('price', 'price', 'trim|decimal|required|xss_clean');
			$this->form_validation->set_rules('model', 'model', 'trim|required|xss_clean');
			
			if( $this->form_validation->run() == TRUE ){

				if( $this->product_model->update( $id ) ){
					$this->contentData['message'] = 'Product updated.';
					return $this->read( $id );
				} else {
					$this->contentData['message'] = $this->product_model->getMessage();
				}
			} else {
				//	validation failed, rebuild from posted info
				$this->contentData['product'] = $this->input->post();
				$this->contentData['product']['id'] = $id;
				$this->contentData['message'] = validation_errors();
			}
		} else {
		//	loading form to begin editing
			$this->contentData['product'] = $this->product_model->getProduct( $id );
			if( !$this->contentData['product'] ){
				return $this->productNotFound();
			}
		}
		
		$data['title'] = 'Edit Product';
		$data['content'] = $this->load->view('product/product_update', $this->contentData, TRUE);
		
		$this->techniart->load( $data );
	}
	
	/*
	*	Delete from db.
	*/
	public function delete( $id=NULL ){
		if( is_null( $id ) ){
			return $this->productNotFound();	
		}
		
		if( $this->product_model->delete( $id ) ){
			$this->contentData['message'] = 'Product deleted.';
			return $this->index();
		} else {
			$this->contentData['message'] = 'Unable to delete.';
			return $this->edit( $id );
		}
	}
	
	/*
	*	Reroute to index when an invalid product is encountered.
	*/
	public function productNotFound(){
		$this->contentData['message'] = 'product not found';
		return $this->index();
	}


}