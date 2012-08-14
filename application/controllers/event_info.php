<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_info extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('event_model');
	}
	
	public function edit( $id_event=NULL ){
		//	is user cancelling?
		if( strtolower( $this->input->post('submit') ) == 'cancel' ){
			redirect( "event/read/$id_event/#info" );
		}
		
		//	load model
		if( !$event = $this->event_model->read($id_event) ){
			show_error("event '$id_event' not found");
		}
		
		// submitted form
		if( strtolower( $this->input->post('submit') ) == 'save' ){
			
			$this->_validateInfoForm();
			
			if( $this->form_validation->run() == TRUE ){
			//	update values
				$infoFormData = $this->_getInfoContentFormData();
				
				if( $this->event_model->update( $infoFormData, $id_event ) ){
					redirect( "event/read/$id_event/#info" );
				} else {
					show_error("unable to update event '$id_event'");
				}
			} else {
			//	validation failed
				$data = $this->input->post();
				$data['id'] = $id_event;	
				$data['message'] = validation_errors();
				$infoView = 'event/info/info_edit';
				$templateData['content'] = $this->load->view( $infoView, $data, TRUE );
			}
		} else {
		//	only viewing form
			$event['message'] = '';
			$templateData['content'] = $this->load->view('event/info/info_edit', $event, TRUE);	
		}
		
		
		$templateData['title'] = 'event info';
		
		
		//	load main template
		$this->techniart->load( $templateData );
		
		
		
	}
	
	/*	
	|	Submit the basic info required for an event.
	|	$event	event to base basic info on
	*/
	public function create($event=NULL){
		
		// submitted form
		if($this->input->post()){
			//	is user cancelling?
			if( strtolower( $this->input->post('submit') ) == 'cancel' ){
				redirect( 'event' );
			}
			
			$this->_validateInfoForm();
			
			if( $this->form_validation->run() == TRUE ){
			//	add new event
				$infoFormData = $this->_getInfoContentFormData();
				
				if( $event_id = $this->event_model->create( $infoFormData ) ){
					redirect( "event/read/$event_id" );
				} else {
					show_error('unable to create event');	
				}
			} else {
			//	validation failed
				$data['event'] = $event;
				$data['info'] = $this->input->post();
				$data['message'] = validation_errors();
				$templateData['content'] = $this->load->view('event/info/info_create', $data, TRUE);
			}
		} else {
			//	viewing for the first time
			//	pass the event here to pre-populate if cloning
			if( $event ){
				$event['date_start'] = '';
				$event['date_end'] = '';	
			} else {
				//	set blank values so set_value() will have something to work with
				$event['name'] = '';
				$event['address'] = '';
				$event['contact_name'] = '';
				$event['contact_phone'] = '';
				$event['contact_email'] = '';
				$event['notes'] = '';
				$event['date_start'] = '';
				$event['date_end'] = '';
				$event['site_visit'] = '';
				$event['display_date'] = '';
				$event['employees'] = '';	
				$event['pm'] = '';
				$event['apm'] = '';
				$event['am'] = '';
			}
			
			$data['event'] = $event;
			$data['message'] = '';
			$templateData['content'] = $this->load->view('event/info/info_create', $data, TRUE);
			
		}
		
		$templateData['title'] = 'new event';
		
		//	load main template
		$this->techniart->load( $templateData );
	}
	
	/*
	|	Makes it easy to create a new event with the info from an existing.
	|	$event_id	(required)
	*/
	public function cloneEvent($event_id=NULL){
		//	get event
		if( !$event = $this->event_model->read($event_id) ){
			show_error("unable to clone event '$event_id'");
		}
		
		//	pass event to the create method
		$this->create($event);
		
	}
	
	/*
	|	Validate event info form.  Abstracted because it is used in multiple places.
	*/
	private function _validateInfoForm(){
		//	TODO::	validate values more stictly (dates, etc.)
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('status', 'Status', 'required|xss_clean');
		$this->form_validation->set_rules('date_start', 'Start Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('date_end', 'End Date', 'trim|xss_clean');
		$this->form_validation->set_rules('site_visit', 'Site Visit', 'trim|xss_clean');
		$this->form_validation->set_rules('display_date', 'Display Date/Time', 'trim|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
		$this->form_validation->set_rules('contact_name', 'Contact Name', 'trim|xss_clean');
		$this->form_validation->set_rules('contact_phone', 'Contact Phone', 'trim|xss_clean');
		$this->form_validation->set_rules('contact_email', 'Contact Email', 'trim|valid_email|xss_clean');
		$this->form_validation->set_rules('notes', 'Notes', 'trim|xss_clean');
		$this->form_validation->set_rules('employees', '# of Employees', 'trim|xss_clean|integer');
		$this->form_validation->set_rules('pm', 'Project Manager', 'trim|xss_clean');
		$this->form_validation->set_rules('apm', 'Operations Manager', 'trim|xss_clean');
		$this->form_validation->set_rules('am', 'Account Manager', 'trim|xss_clean');
	}
	
	/*
	|	Return an array with values ready to pass to event model.
	*/
	private function _getInfoContentFormData(){
		$eventData['name'] = set_value( 'name' );
		$eventData['status'] = set_value('status');
		$eventData['date_start'] = set_value( 'date_start' );
		$eventData['date_end'] = set_value( 'date_end' );
		$eventData['site_visit'] = set_value( 'site_visit' );
		$eventData['display_date'] = set_value( 'display_date' );
		$eventData['address'] = set_value('address');
		$eventData['contact_name'] = set_value('contact_name');
		$eventData['contact_phone'] = set_value('contact_phone');
		$eventData['contact_email'] = set_value('contact_email');
		$eventData['notes'] = set_value('notes');
		$eventData['employees'] = set_value('employees');
		$eventData['pm'] = set_value('pm');
		$eventData['apm'] = set_value('apm');
		$eventData['am'] = set_value('am');
		return $eventData;
	}
	

}