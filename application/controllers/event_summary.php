<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_summary extends CI_Controller {
	public function __construct(){
		parent::__construct();
		//	users must be logged in
		$this->techniart->securePage();
	}
	
	/*
	|	Add a final summary to the event and prevent it from being edited further.
	*/
	public function create($event_id = NULL){
		//	load the event
		$this->load->model('event_model');
		if( !$event = $this->event_model->read($event_id) ){
			show_error("unable to finish event : '$event_id' not found");
		}
		
		if( strtolower( $this->input->post('submit') ) == 'cancel' ){
			redirect("event/read/$event_id#summary");
		}
		
		if( strtolower( $this->input->post('submit') ) == 'finish event' ){
			//	saving event summary
			$this->load->model('event_summary_model');
			
			$data['id_event'] = $event_id;
			$data['notes'] = $this->input->post('notes');
			if( $this->event_summary_model->create($data) ){
				redirect("event/read/$event_id#summary");
			} else {
				show_error("unable to finish event : '$event_id'");
			}
				
		} else {
			//	viewing summary form
			$view = 'event/summary/summary_create';
			$templateData['title'] = 'summary';
			$templateData['content'] = $this->load->view($view, $event, TRUE);
			$this->techniart->load( $templateData );	
		}
	}
}