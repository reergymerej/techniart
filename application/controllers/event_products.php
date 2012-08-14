<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_products extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('event_model');
		$this->load->model('product_model');
		$this->load->model('event_products_model');
	}
	
	/*
	*	List all of the products for this event.
	*/
	public function read( $id_event=NULL ){
		//	view
		$aggregateView = 'event/products_read';
		
		//	load sub view
		$data['event'] = $this->_getEvent( $id_event );
		$data['controls'] = $this->techniart->loadControls( $aggregateView, NULL, $data );
		$data['products'] = $this->event_products_model->load( $id_event );
		
		$aggregateViewData['content'] = $this->load->view( $aggregateView, $data, TRUE );
		
		//	load main template
		$aggregateViewData['title'] = 'event products';
		$this->techniart->load( $aggregateViewData );
	}
	
	/*
	*	Choose which products will be used in this event.
	*/
	public function edit( $id_event=NULL ){
		$data['event'] = $this->_getEvent( $id_event );
		
		//	cancelling?
		if( strtolower( $this->input->post('submit') ) == 'cancel' ){
			redirect("event/read/$id_event/#products");
		}
		
		//	is user trying to save?
		if( count($this->input->post()) > 1 ){
			//	gather the products we're interested in
			$products = array();
			foreach($this->input->post() as $product_id => $count){
				if( strtolower($product_id) == 'save' ){
					continue;
				}
				if( is_numeric($product_id) && intval($product_id) >= 0 ){
					if( is_numeric($count) && intval($count) > 0 ){
						$products[$product_id] = $count;
					}
				}
			}
			
			if( $this->event_products_model->save( $id_event, $products) ){
				redirect("event/read/$id_event/#products");
			} else {
				show_error('unable to save');
			}
			

		}
		
		//	get all products
		$data['products'] = $this->product_model->index();
		
		//	load view
		$data['message'] = '';
		$data['content'] = $this->load->view('event/products/products_edit', $data, TRUE );
		
		//	load main template
		$data['title'] = 'Products';
		$this->techniart->load( $data );	
	}
	
	/*
	|	Return event from model.
	|	If id is invalid, throw error.
	*/
	private function _getEvent( $id_event=NULL ){
		if( is_null( $id_event ) || ! $event = $this->event_model->read( $id_event ) ){
			show_error('invalid event id');	
		}

		return $event;
	}
}