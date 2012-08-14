<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Techniart {
	private $ci;	//	instance of CodeIgniter
	private $user;
	private $currentUser;
	
	private $JS_URL = 'application/views/js/';
	private $CSS_URL = 'application/views/css/';
	private $PRE_TITLE = 'TechniArt - ';
	private $DATE_FORMAT = 'n/j/Y';
	private $TIME_FORMAT = 'n/j/Y h:i:s a';

    public function __construct(){
		$this->ci =& get_instance();
		$this->ci->load->helper('url');
		
		$this->JS_URL = base_url() . $this->JS_URL;
		$this->CSS_URL = base_url() . $this->CSS_URL;
		
		session_start();
		$this->ci->load->model('user_model');
		$this->currentUser = $this->ci->user_model->getCurrentUser();
    }
	
	/* 
	*	Redirect visitors to login page if they are not authenticated.
	*/
	public function securePage(){
		if( !$this->currentUser && !$this->ci->user_model->autoLoginUser() ){
			setcookie('redirect', $this->ci->uri->uri_string(), 0, '/' );
			redirect('main', 'refresh');
		}
	}
	
	/*
	*	Load the template view.
	*	$data['title']				page title
	*	$data['content']			view returned as string
	*	$data['js']		(optional)	array of javascripts to be loaded in addition to default
	*	$data['css']	(optional)	name of css file to load, use @import for inheritance
	*/
	public function load( $data ){
		
		//	show current user
		if($this->currentUser){
			$data['currentUser'] = $this->ci->load->view('templates/current_user', $this->currentUser, TRUE);
		} else {
			$data['currentUser'] = '';
		}
		
		$data['title'] = $this->PRE_TITLE . $data['title'];
		
		//	prep JavaScript
		if( empty( $data['js'] ) || !isset( $data['js'] ) ){
			$data['js'] = array( 'main.js' );
		} else {
			array_unshift( $data['js'], 'main.js' );	
		}
		//	prepend JS url to each
		for($i=0; $i<count($data['js']); $i++){
			if( strpos( $data['js'][$i], 'http' ) === FALSE ){
				$data['js'][$i] = $this->JS_URL . $data['js'][$i];	
			}
		}
		
		//	prep CSS
		if( empty( $data['css'] ) || !isset( $data['css'] ) ){
			$data['css'] = $this->CSS_URL . 'main.css';
		} else {
			$data['css'] = $this->CSS_URL . $data['css'];
		}
		
		$this->ci->load->view('templates/template', $data);
	}
	
	public function isAdmin(){
		return isset( $this->currentUser ) ? $this->currentUser['admin'] == 1 : FALSE;
	}
	
	public function getDateFormat(){
		return $this->DATE_FORMAT;	
	}
	
	public function getTimeFormat(){
		return $this->TIME_FORMAT;
	}
	
	//	return the appropriate view of controls as string for use in parent view
	//	views must conform to this naming convention:
	//	admin views:	[parent_view_name]_controls_admin
	//	user views:		[parent_view_name]_controls
	//	views must exist even if returning nothing
	//	$user_array[]	if the current user is in this list, they will be treated as an admin
	public function loadControls($parentView=NULL, $user_array=array(), $dataForControlsView=NULL){
		if(is_null($parentView)){
			return;	
		}
		//	always give admins admin rights
		$admin = $this->isAdmin() ? '_admin' : '';
		//	if the current user is in this list, treat them as an admin
		if(!empty($user_array)){
			foreach($user_array as $u){
				if( $this->currentUser['id'] == $u['id'] ){
					$admin = '_admin';
					break;	
				}
			}
		}
		return $this->ci->load->view($parentView . '_controls' . $admin, $dataForControlsView, TRUE);
	}
	
	public function getCurrentUserId(){
		return $this->currentUser['id'];	
	}
	
	public function logout(){
		$this->currentUser = NULL;	
	}
	
}