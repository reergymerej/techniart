<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
	private $USER_TABLE = 'user';
	private $message;
	private $COOKIE_DAYS = 15;
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	//	$filter[]	(optional)	
	//	$filter['sort_by']
	//	$filter['direction']
	//	$filter['filter_criteria']
	function index($filter=NULL){
		if( is_null( $filter ) ){
			$rows = $this->db->get( $this->USER_TABLE )->result_array();
		} else {
			//	filtering by value
			if(isset($filter['filter_criteria'])){
				$this->db->like('email', $filter['filter_criteria']);
				$this->db->or_like('first_name', $filter['filter_criteria']);
				$this->db->or_like('last_name', $filter['filter_criteria']);
				$this->db->or_like('phone', $filter['filter_criteria']);
				$this->db->or_like('admin', $filter['filter_criteria']);
			}
			$this->db->order_by($filter['sort_by'], $filter['direction']);
			$rows = $this->db->get( $this->USER_TABLE )->result_array();
		}
		
		return $rows;
	}
	
	/*
	*	Add new user to db.
	*	Returns boolean - TRUE on success, FALSE on error.
	*	Use getMessage for error.
	*/
	public function create($userData){
		//	is this email in use already?
		$this->db->like('email', $userData['email']);
		$this->db->from( $this->USER_TABLE );
		if($this->db->count_all_results()){
			$this->message = 'The email address ' . $userData['email'] . ' is already in use.';
			return FALSE;
		}
		
		$userData['password'] = $this->encrypt( $userData['password'] );
		$userData['created_on'] = time();
		
		if( $this->db->insert( $this->USER_TABLE, $userData ) ){
			$this->message = $this->db->insert_id();
			return TRUE;
		} else {
			$this->message = 'unable to save user';
			return FALSE;
		}
	}
	
	/*
	*	Updates db values for existing user.
	*	Returns TRUE on success, FALSE on failure.
	*	$id	:	user id to be updated
	*/
	public function update( $id=NULL ){
		if( is_null( $id ) ){
			return false;	
		}
		
		//	build data from post
		$updateData['email'] = $this->input->post('email');
		if( $this->input->post('password') ){	//	keep existing password if this is not provided
			$updateData['password'] = $this->encrypt( $this->input->post('password') );
		}
		$updateData['first_name'] = $this->input->post('first_name');
		$updateData['last_name'] = $this->input->post('last_name');
		$updateData['phone'] = $this->input->post('phone');
		$updateData['admin'] = $this->input->post('admin') ? 1 : 0;

		$this->db->where('id', $id);
		if( $this->db->update( $this->USER_TABLE, $updateData ) ){
			return TRUE;
		} else {
			$this->message = 'unable to update user';
			return FALSE;
		}
	}
	
	/*
	*	Login user.
	*/
	public function login( $email, $password ){
		$q = $this->db->get_where( $this->USER_TABLE, array( 'email'=>$email ) );
		if( $q->num_rows() == 0 ){
		//	email not found	
			$this->message = 'Email address ' . $email . ' was not found.';
			return FALSE;
		} else {
			$password = $this->encrypt( $password );
			$q = $this->db->get_where( $this->USER_TABLE, array( 'email'=>$email, 'password'=>$password ) );	
			if( $q->num_rows() == 0 ){
			//	password did not match	
				$this->message = 'The password provided did not match.';
				return FALSE;
			} else {
			//	success
				$user = $q->row_array();
				$this->startUserSession( $user['id'] );
				return TRUE;
			}
		}
	}
	
	/*
	*	Get data for a specific user.
	*/
	public function getUser( $id ){
		$q = $this->db->get_where( $this->USER_TABLE, array( 'id'=>$id ) );
		return $q->row_array();
	}
	
	/*
	*	Deletes user from db.
	*	Returns TRUE on success, FALSE on failure.
	*	$id	:	user id to delete
	*/
	public function delete( $id=NULL ){
		if( is_null( $id ) ){
			return FALSE;	
		}
		
		$this->db->where('id', $id);
		return $this->db->delete( $this->USER_TABLE );
	}
	
	public function logout(){
		//	kill session
		$_SESSION = array();
		session_destroy();
		
		//	kill cookies
		setcookie('id', '', time() - 36000, '/' );
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 36000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}
	
	public function getCurrentUser(){
		if( empty( $_SESSION['id'] ) ){
			return FALSE;
		} else {
			return $this->getUser( $_SESSION['id'] );
		}
	}
	
	/*
	*	If user id is in cookie, authenticate user.
	*	Returns TRUE on success, FALSE on failure.
	*/
	public function autoLoginUser(){
		if( isset( $_COOKIE['id'] ) ){
			$this->startUserSession( $_COOKIE['id'] );	
			return TRUE;
		} else {
			return FALSE;	
		}
	}
	
	public function getMessage(){
		return $this->message;	
	}
	
	private function encrypt( $value ){
		return md5( $value );	
	}
	
	private function startUserSession( $id ){
		$_SESSION['id'] = $id;
		setcookie('id', $id, time() + 86400 * $this->COOKIE_DAYS, '/' );		
	}
}