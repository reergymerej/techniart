<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_day extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('day_model');
	}
	
	/*
	|	Save an event_day.
	|	This is called through AJAX.
	*/
	public function save($event_id=NULL, $date=NULL){
		if( is_null($event_id) || is_null($date) ){
			echo 'error: invalid event id or date';
			return FALSE;
		}
		if( $this->input->post('product') ){
			//	load model
			$this->load->model('day_model');
			if( $this->day_model->create( $event_id, $date, $this->input->post('product')) ){
				echo 'Day saved.';
				return TRUE;
			}
		}
		echo 'error: invalid products';
		return FALSE;
	}
	
	/*
	|	View the products sold this day.
	*/
	public function view( $event_id=NULL, $date=NULL ){
		$this->load->model('event_model');
		if( !$event = $this->event_model->read($event_id) ){
			show_error('Unable to load event.');
		}
		if( is_null($date) ){
			show_error('No day specified.');	
		}
		
		if( $aggregatedViewData = $this->day_model->read( $event_id, $date ) ){
		//	show the day
			
			//	aggregated view
			$aggregatedView = 'event/day/view';
			$aggregatedViewData['event_id'] = $event_id;
			
		} elseif( !$event['finished'] ) {
		//	show the form to submit the day
			
			//	get event products in order to build form
			$this->load->model('event_products_model');
			
			//	aggregated view
			$aggregatedView = 'event/day/create';
			
			//	load form to submit data if user has control rights
			$data['products'] = $this->event_products_model->load($event_id);
			$data['event_id'] = $event_id;
			$data['date'] = $date;
			
			//	build the rest of the aggregated view
			$aggregatedViewData['event_id'] = $event_id;
			$aggregatedViewData['controls'] = $this->techniart->loadControls( $aggregatedView, NULL, $data );
		} else {
			//	This is hackish, but show the default "no data" view that we'd show for non-admins.
			$aggregatedView = 'event/day/create';
			$data['event_id'] = $event_id;
			$aggregatedViewData['controls'] = $this->load->view('event/day/create_controls', $data, TRUE);
		}
			
		//	pass elements to day view
		$templateData['title'] = 'view day';
		$templateData['js'] = array('event_day.js');
		$templateData['content'] = $this->load->view($aggregatedView, $aggregatedViewData, TRUE);
		
		//	load main template
		$this->techniart->load( $templateData );
		
	}

}