<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends CI_Controller {
	private $id_event;
	private $contentData;
	private $LABELS;
	
	public function __construct(){
		parent::__construct();
		
		//	users must be logged in
		$this->techniart->securePage();
		
		//	pre-load models for convenience
		$this->load->model('event_model', '', TRUE);
		$this->load->model('product_model', '', TRUE);
		
		//	default views to be shown for each piece of the page
		$this->infoView = 'event/info_read';
		$this->productsView = 'event/products_read';
		$this->daysView = 'event/days_read';
		
		//	populate LABELS
		$this->LABELS['date_start'] = 'Start Date';
		$this->LABELS['date_end'] = 'End Date';
		$this->LABELS['site_visit'] = 'Site Visit';
		$this->LABELS['display_date'] = 'Display Date/Time';
		$this->LABELS['employees'] = '# of Employees';
		$this->LABELS['name'] = 'Name';
		$this->LABELS['status'] = 'Status';
		$this->LABELS['address'] = 'Address';
		$this->LABELS['contact_name'] = 'Contact Name';
		$this->LABELS['contact_phone'] = 'Contact Phone';
		$this->LABELS['contact_email'] = 'Contact Email';
		$this->LABELS['notes'] = 'Notes';
		$this->LABELS['pm'] = 'Project Manager';
		$this->LABELS['apm'] = 'Operations Manager';
		$this->LABELS['am'] = 'Account Manager';
	}
	
	/*
	|	List all events
	|	This method loads the actual list from eventList() for reuse by AJAX calls.
	*/
	public function index(){
		
		//	view separate elements will be loaded into
		$aggregated_view = 'event/event_index';
		
		//	pass elements to main view
		$data['event_list'] = $this->eventList( TRUE );
		$data['controls'] = $this->techniart->loadControls( $aggregated_view );
		$data['content'] = $this->load->view($aggregated_view, $data, TRUE);
		$data['js'] = array('event_index.js');
		$data['css'] = 'event_index.css';
		$data['title'] = 'event_index controller';
		
		//	load main template
		$this->techniart->load( $data );
	}
	
	/*
	|	View the dashboard for an event
	|	$event_id	(required)
	*/
	public function read( $event_id=NULL ){
		//	load model
		if( !$event = $this->event_model->read( $event_id ) ){
			show_error("event '$event_id' not found");
		}
		
		//	view sub views will be combined into
		$aggregatedView = 'event/dashboard/event_read';
		$aggregatedViewData['id_event'] = $event['id'];
		$aggregatedViewData['event_name'] = $event['name'];
		
		//	gather elements of page
		$aggregatedViewData['eventControls'] = $this->techniart->loadControls( $aggregatedView, NULL, $event );
		
		
		$aggregatedViewData['info'] = $this->_getInfoContent( $event );
		$aggregatedViewData['usersContent'] = $this->_getUsersContent( $event );
		$aggregatedViewData['products'] = $this->_getProductsContent( $event );
		$aggregatedViewData['calendar'] = $this->_getCalendarContent( $event );
		$aggregatedViewData['images'] = $this->_getImagesContent( $event );
		$aggregatedViewData['summary'] = $this->_getSummaryContent( $event );

		//	pass elements to event view
		$templateData['title'] = 'view event';
		$templateData['content'] = $this->load->view($aggregatedView, $aggregatedViewData, TRUE);
		$templateData['css'] = 'event.css';
		$templateData['js'] = array('http://maps.googleapis.com/maps/api/js?key=' . $this->config->item('google_api_key') . '&sensor=false', 'event_view.js');
		
		//	load main template
		$this->techniart->load( $templateData );
	}
	
	/*
	|	Deletes an event and all associated data.
	|	$event_id	(required)
	*/
	public function delete( $event_id=NULL, $confirmed=0 ){
		if( is_null($event_id) ){
			show_error('missing event id');	
		}
		
		if( $confirmed === '1' ){
			//	user has confirmed, delete the sucka
			if( !$this->event_model->delete( $event_id ) ){
				echo 'problem deleting';
			} else {
				$data['content'] = $this->load->view('event/delete/delete_success', NULL, TRUE);
				$data['title'] = 'event deleted';
				$this->techniart->load( $data );
			}
		} else {
			//	show confirmation
			$event = $this->event_model->read( $event_id );
			$data['content'] = $this->load->view('event/delete/delete_confirmation', $event, TRUE);
			$data['title'] = 'delete event';
			$this->techniart->load( $data );			
		}
	}
	
	
	//	these methods return the individual "sub-views" for the event dashboard
	private function _getInfoContent( $event ){
		$infoView = 'event/dashboard/info/info';
		$event['message'] = '';
		if( !$event['finished'] ){
			$event['controls'] = $this->techniart->loadControls( $infoView, $event['users'], $event );
		} else {
			$event['controls'] = '';	
		}
		return $this->load->view( $infoView, $event, TRUE );
	}
	private function _getUsersContent( $event ){
		//	get users from model
		$this->load->model('event_users_model');
		
		$usersView = 'event/dashboard/users/users';
		
		//	load controls
		if( !$event['finished'] ){
			$usersViewContent['usersControls'] = $this->techniart->loadControls( $usersView, $event['users'], $event['id'] );
		} else {
			$usersViewContent['usersControls'] = '';	
		}
		
		if( $usersViewData['users'] = $this->event_users_model->load( $event['id'] ) ){
			
			$usersViewData['modified_date'] = date( $this->techniart->getTimeFormat(), $usersViewData['users'][0]['date_modified'] );
			$usersViewData['modified_user']['id'] = $usersViewData['users'][0]['id_user_modified'];
			$usersViewData['modified_user']['email'] = $usersViewData['users'][0]['modified_email'];
			
			$usersViewContent['userListContent'] = $this->load->view( 'event/dashboard/users/users_list', $usersViewData, TRUE );
			
		} else {
			$usersViewContent['userListContent'] = '';
		}
		return $this->load->view( $usersView, $usersViewContent, TRUE);
	}
	private function _getProductsContent( $event ){
		//	dashboard view
		$productsView = 'event/dashboard/products/products';
		
		//	get controls
		if( !$event['finished'] ){
			$productsViewContent['productsControls'] = $this->techniart->loadControls( $productsView, $event['users'] );
		} else {
			$productsViewContent['productsControls'] = '';	
		}
		
		//	show products if they exist
		$this->load->model('event_products_model');
		if( $data['products'] = $this->event_products_model->load( $event['id'] ) ){
			$data['modified_date'] = date( $this->techniart->getTimeFormat(), $data['products'][0]['date_modified'] );
			$data['modified_user']['id'] = $data['products'][0]['id_user'];
			$data['modified_user']['email'] = $data['products'][0]['email'];
			
			$productsViewContent['productListContent'] = $this->load->view('event/dashboard/products/products_list', $data, TRUE);
		} else {
			$productsViewContent['productListContent'] = '';
		}
		
		return $this->load->view( $productsView, $productsViewContent, TRUE );
	}
	private function _getCalendarContent( $event ){
		//	create an array of days (Sunday <= event start - Saturday >= event end)
		//	handle this here so I don't junk up the view with excess logic

		$days = $this->event_model->getCalendar( $event );
		
		//	cache users who are allowed to submit for performance
		$current_user = $this->techniart->getCurrentUserId();
		$allowed_users = array();
		foreach($event['users'] as $u){
			array_push($allowed_users, $u['id']);
		}
		
		//	determine what the hyperlinks will say
		foreach($days as &$d){
			$d['link_text'] = '';
			if( $d['event_day'] ){
				//	view submitted data
				if( $d['submitted'] ){
					$d['link_text'] = 'view';
				} elseif( !$event['finished'] ){
					if( array_search($current_user, $allowed_users) !== FALSE 
						|| $this->techniart->isAdmin() ){
						$d['link_text'] = 'submit';
					}
				} else {
					$d['link_text'] = '';
				}
			}
		}
		
		return $this->load->view( 'event/dashboard/calendar/calendar', array('days'=>$days, 'event_id'=>$event['id']), TRUE );
	}
	private function _getImagesContent( $event ){
		$this->load->model('image_model');
		$imagesView = 'event/dashboard/images/images';
		$imagesViewData['images'] = $this->image_model->loadEventImages($event['id']);
		$imagesViewData['controls'] = $this->techniart->loadControls( $imagesView, $event['users'], $event );
		return $this->load->view($imagesView, $imagesViewData, TRUE);
	}
	private function _getSummaryContent( $event ){
		//	Show event summary if it exists.
		$this->load->model('event_summary_model');
		$summary = $this->event_summary_model->load( $event['id'] );
		
		//	view
		$summaryView = 'event/dashboard/summary/summary_read';
		
		//	Load summary content.
		if($summary){
			$viewData['summary_content'] = $this->load->view($summaryView, $summary, TRUE);
		} else {
			$viewData['summary_content'] = '';
		}
		
		//	Load summary controls if the event has not been finished already.
		if( !$event['finished'] ){
			$viewData['summary_controls'] = $this->techniart->loadControls($summaryView, $event['users']);
		} else {
			$viewData['summary_controls'] = '';	
		}
		
		$viewData['event'] = $event;
		return $this->load->view( 'event/dashboard/summary/summary', $viewData, TRUE );
	}
	
	/*	
	|	Returns list of events (just the table, not the surrounding view)
	|	Called by ajax and internally by index()
	|	$returnAsString	must be set to TRUE when calling from index
	*/
	public function eventList( $returnAsString = FALSE ){
		//	return sorted/filtered lists
		if(isset($_POST['sort_by'])){
			$filter = array(
				'sort_by'=>$_POST['sort_by'],
				'direction'=>$_POST['direction'],
				'filter_criteria'=>$_POST['filter_criteria'],
				'filter_field'=>$_POST['filter_field']);
		} else {
			$filter = array(
				'sort_by'=>'name',
				'direction'=>'asc'
			);	
		}
		
		//	get data from model
		$data['event_list'] = $this->event_model->index($filter);
		
		//	pass search_field
		if( isset( $filter['filter_field'] ) ){
			$data['search_field'] = $filter['filter_field'];	
		} else {
			$data['search_field'] = 'address';	
		}
		$data['search_field_label'] = $this->LABELS[$data['search_field']];
		
		$data['filter'] = $filter;
		$this->load->helper('techniart_helper');
		
		//	return view
		return $this->load->view('event/event_list', $data, $returnAsString);
	}
	
}