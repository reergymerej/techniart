<?php
class Main extends CI_Controller {
	private $data;
	private $contentData;
	private $DEFAULT_CONTROLLER = 'event';
	
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->contentData['message'] = '';
	}
	
	public function index(){
		//	attempt to bypass login
		if( $this->user_model->autoLoginUser() ){
			$this->redirect();
		}

		//	validate input
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true){
		//	user is trying to log in
		
			if( $this->user_model->login( $this->input->post('email'), $this->input->post('password') ) ){
			//	login is successful
				$this->redirect();
				
			} else {
			//	login was un-successful
				$this->contentData['message'] = $this->user_model->getMessage();
			}
			
		} elseif( $this->input->post() ) {
		//	the user tried to login and failed validation
			$this->contentData['message'] = validation_errors();
		}
		
		$this->data['js'] = array( 'login.js' );
		$this->data['title'] = 'Login';
		$this->data['content'] = $this->load->view('main', $this->contentData, TRUE);
		$this->techniart->load( $this->data );
		
	}
	
	public function redirect(){
		if( isset( $_COOKIE['redirect'] ) ){
			redirect( $_COOKIE['redirect'], 'refresh');	
		} else {
			redirect( $this->DEFAULT_CONTROLLER, 'refresh');
		}
	}
	
	public function logout(){
		$logout = $this->user_model->logout();
		$this->techniart->logout();
		$this->data['js'] = array( 'login.js' );
		$this->data['title'] = 'Login';
		$this->data['content'] = $this->load->view('main', $this->contentData, TRUE);
		$this->techniart->load( $this->data );
	}
}