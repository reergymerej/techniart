<?php
class User extends CI_Controller {
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
	}
	
	/* 
	*	List all users
	*/	
	public function index(){
		//	view separate elements will be loaded into
		$aggregated_view = 'user/user_index';
		
		//	collect elements
		$data['user_list'] = $this->userList( TRUE );
		$data['controls'] = $this->techniart->loadControls($aggregated_view);
		$data['content'] = $this->load->view($aggregated_view, $data, TRUE);
		$data['js'] = array('user_index.js');
		$data['title'] = 'Users';
		
		//	load main template
		$this->techniart->load( $data );
	}
	
	//	returns a sorted/filtered list
	//	called by ajax and internally by this script
	//	When called internally, the result should be returned as a string
	//	so it can be passed to the master template.
	public function userList( $returnAsString = FALSE ){
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
		
		//	get data from model
		$data['user_list'] = $this->user_model->index($filter);
		$data['filter'] = $filter;
		$this->load->helper('techniart_helper');
		
		//	return view
		return $this->load->view('user/user_list', $data, $returnAsString);
	}
	
	/*
	*	Load editable form with default values.
	*/
	public function create(){
		$data['title'] = 'Create User';
		
		//	is user submitting form or viewing?
		if( $this->input->post() ){
			
			//	validate form
			$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|xss_clean');
			$this->form_validation->set_rules('password', 'password', 'required');
			$this->form_validation->set_rules('first_name', 'first name', 'trim|xss_clean');
			$this->form_validation->set_rules('last_name', 'last name', 'trim|xss_clean');
			$this->form_validation->set_rules('phone', 'phone', 'trim|xss_clean');
			$this->form_validation->set_rules('admin[]');
	
			if( $this->form_validation->run() == TRUE ){
			//	save new user
				$userData = array(
					'password' => set_value('password'),
					'email' => set_value('email'),
					'admin' => $this->input->post('admin') ? 1 : 0,
					'first_name' => set_value('first_name'),
					'last_name' => set_value('last_name'),
					'phone' => set_value('phone')
				);
				
				if( $this->user_model->create($userData) ){
				//	success
					$this->contentData['message'] = 'user created';
					return $this->index();
				} else {
				//	error creating new user
					$this->contentData['message'] = $this->user_model->getMessage();	
				}

				
			} else {
				$this->contentData['message'] = validation_errors();
			}
		}
		
		
		$data['content'] = $this->load->view('user/user_create', $this->contentData, TRUE);
		$this->techniart->load($data);
	}
	
	/*
	*	Load locked form with values from db.
	*/
	public function read( $id ){
		//	is this id valid?
		$this->contentData['user'] = $this->user_model->getUser( $id );
		if( !$this->contentData['user'] ){
			return $this->userNotFound();
		}
		
		//	view elements will be displayed in
		$aggregated_view = 'user/user_view';
		
		//	gather elements
		$this->contentData['controls'] = $this->techniart->loadControls($aggregated_view, array($id));
		$this->contentData['title'] = 'Users';
		$this->contentData['content'] = $this->load->view($aggregated_view, $this->contentData, TRUE);
		
		//	load main template
		$this->techniart->load( $this->contentData );
	}
	
	/*
	*	Load editable form with values from db.
	*	Validate input from form.
	*/
	public function edit( $id=NULL ){
		//	something weird happened, show the index
		if( is_null( $id ) ){
			return $this->userNotFound();
		}
		
		//	handle cancel and delete buttons
		switch( strtolower( $this->input->post('submit') ) ){
			case 'cancel':
				redirect("user/read/$id");
				break;
			case 'delete':
				return $this->delete( $id );
				break;
		}
		
		//	load dependencies
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		if( count( $this->input->post() ) > 1 ){
		//	trying to save
		
			//	validate form
			$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|xss_clean');
			$this->form_validation->set_rules('password', 'password', 'trim');
			$this->form_validation->set_rules('first_name', 'first name', 'trim|xss_clean');
			$this->form_validation->set_rules('last_name', 'last name', 'trim|xss_clean');
			$this->form_validation->set_rules('phone', 'phone', 'trim|xss_clean');
			$this->form_validation->set_rules('admin[]');
			
			if( $this->form_validation->run() == TRUE ){
				if( $this->user_model->update( $id ) ){
					$this->contentData['message'] = 'User updated.';
					return $this->read( $id );
				} else {
					$this->contentData['message'] = $this->user_model->getMessage();
				}
			} else {
				//	validation failed, rebuild event from posted info
				$this->contentData['user']['id'] = $id;
				$this->contentData['user']['email'] = $this->input->post('email');
				if( $this->input->post('password') ){	//	keep existing password if this is not provided
					$this->contentData['user']['password'] = $this->input->post('password');
				}
				$this->contentData['user']['first_name'] = $this->input->post('first_name');
				$this->contentData['user']['last_name'] = $this->input->post('last_name');
				$this->contentData['user']['phone'] = $this->input->post('phone');
				$this->contentData['user']['admin'] = $this->input->post('admin') ? 1 : 0;

				$this->contentData['message'] = validation_errors();
			}
		} else {
		//	loading form to begin editing
			$this->contentData['user'] = $userID = $this->user_model->getUser( $id );
			if( !$this->contentData['user'] ){
				return $this->userNotFound();
			}
		}
		
		$this->contentData['id'] = $id;
		
		$data['title'] = 'Edit User';
		$data['content'] = $this->load->view('user/user_update', $this->contentData, TRUE);
		
		$this->techniart->load( $data );
	}
	
	/*
	*	Delete from db.
	*/
	public function delete( $id ){
		if( $this->user_model->delete( $id ) ){
			$this->contentData['message'] = 'User deleted.';
			return $this->index();
		} else {
			$this->contentData['message'] = 'Unable to delete.';
			return $this->edit( $id );
		}
	}
	
	/*
	*	Reroute to index when an invalid user is encountered.
	*/
	public function userNotFound(){
		$this->contentData['message'] = 'user not found';
		return $this->index();
	}
}