<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_users extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('event_model');
		$this->load->model('event_users_model');
	}
	
	/*
	|	List all users for this event.
	*/
	public function read( $id_event=NULL ){
		$event = $this->validateEventID( $id_event );
		
		//	build aggregate view
		$aggregatedView = 'event/event_users_list';
		$aggregatedViewData['users'] = $this->listUsers( $id_event );
		$aggregatedViewData['controls'] = $this->techniart->loadControls( $aggregatedView, NULL, array('id'=>$id_event) );
		$aggregatedViewData['id'] = $event['id'];
		$aggregatedViewData['name'] = $event['name'];
		
		//	pass elements to main view
		$templateData['title'] = 'event users';
		$templateData['content'] = $this->load->view($aggregatedView, $aggregatedViewData, TRUE);
		$templateData['js'] = array('event_user.js');
		
		//	load main template
		$this->techniart->load( $templateData );
	}
	
	/*
	|	Load interface for selecting users for an event.
	|	Handle POSTs to cancel and update.
	*/
	public function edit( $id_event=NULL ){
		$event = $this->validateEventID( $id_event );
		
		//	viewing or submitting?
		if( strtolower($this->input->post('submit')) == 'cancel' ){
			redirect("event/read/$id_event#users");
		}
		
		//	saving
		if( strtolower($this->input->post('submit')) == 'save' ){
			$this->event_users_model->save( $id_event, $this->input->post('users') );
			redirect("event/read/$id_event#users");
		}
		
		//	build aggregate view
		$aggregatedView = 'event/users/users_edit';
		$aggregatedViewData['id'] = $event['id'];
		$aggregatedViewData['name'] = $event['name'];
		$usersChoices = $this->event_users_model->listUserChoices( $id_event );
		//	massage data so the view doesn't have to use any logic to show if this user is selected for this event already
		foreach($usersChoices as &$u){
			$u['selected'] = !empty( $u['selected'] ) ? 'selected="selected"' : '';
		}
		$aggregatedViewData['users'] = $usersChoices;
		
		
		//	pass elements to main view
		$templateData['title'] = 'edit event users';
		$templateData['content'] = $this->load->view($aggregatedView, $aggregatedViewData, TRUE);
		$templateData['css'] = 'event_users.css';
		$templateData['js'] = array('event_user.js');
		
		//	load main template
		$this->techniart->load( $templateData );
	}
	
	/*
	|	Returns the list of event users (table only).
	*/
	public function listUsers( $id_event=NULL ){
		$this->validateEventID( $id_event );
		
		//	return sorted/filtered lists
		if(isset($_POST['sort_by'])){
			$filter = array(
				'sort_by'=>$_POST['sort_by'],
				'direction'=>$_POST['direction'],
				'filter_criteria'=>$_POST['filter_criteria']);
		} else {
			$filter = array(
				'sort_by'=>'email',
				'direction'=>'asc'
			);
		}
		
		//	gather data for view
		$data['user_list'] = $this->event_users_model->load( $id_event );
		$data['filter'] = $filter;
		
		$this->load->helper('techniart_helper');
		

		return $this->load->view('user/user_list', $data, TRUE);
	}
	
	/*
	|	Returns event from model.
	|	Ends program if null or invalid event id is provided.
	*/
	private function validateEventID( $id_event=NULL ){
		//	is this id valid?
		if( is_null($id_event) ){
			show_error('invalid event id');
		}
		if( !$e = $this->event_model->read( $id_event ) ){
			show_error('event not found');
		}
		return $e;
	}
	
}