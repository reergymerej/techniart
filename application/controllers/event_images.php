<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_images extends CI_Controller {
	public function __construct(){
		parent::__construct();	
		$this->load->model('event_model');
		$this->load->model('image_model');
	}
	
	public function index($event_id = NULL){
		if( !$event = $this->event_model->read($event_id) ){
			show_error("event '$event_id' not found");
		}
		
		$imagesView = 'event/images/images_edit';
		$imagesViewData['event'] = $event;
		$imagesViewData['images'] = $this->getThumbs($event_id, FALSE );
		$imagesViewContent = $this->load->view( $imagesView, $imagesViewData, TRUE );
		
		//	show master view
		$contentData['title'] = 'event images';
		$contentData['content'] = $imagesViewContent;
		$contentData['js'] = array('images_edit.js');
		$this->techniart->load($contentData);
	}
	
	/*
	|	upload and save images/thumbnails
	*/
	public function upload($event_id = NULL){
		if( !$event = $this->event_model->read($event_id) ){
			show_error("event '$event_id' not found");
		}
		
		//	prep data for model
		$data['event_id'] = $event_id;
		$data['image'] = $_FILES['image'];
		
		//	save images
		$result = $this->image_model->upload($data);
		
		$imagesView = 'event/images/images_upload_result';
		$imagesViewData['event'] = $event;
		$imagesViewData['result'] = $result;
		$imagesViewContent = $this->load->view( $imagesView, $imagesViewData, TRUE );
		
		//	show master view
		$contentData['title'] = 'event images uploaded';
		$contentData['content'] = $imagesViewContent;
		$this->techniart->load($contentData);
	}
	
	/*
	|	delete a single image from an event
	|	POST image id
	*/
	public function delete($event_id = NULL){
		if( !$event = $this->event_model->read($event_id) ){
			show_error("event '$event_id' not found");
		}
		
		//	get image/event data
		$image_id = $this->input->post('id');
		
		//	call model to clean up
		$this->image_model->delete( $event_id, $image_id );
	}
	
	/*
	|	Get all thumbnails for event images.
	|	This is called internally and through AJAX.
	*/
	public function getThumbs($event_id=NULL, $AJAX=TRUE){
		if( !$event = $this->event_model->read($event_id) ){
			show_error("event '$event_id' not found");
		}
		
		$data['images'] = $this->image_model->loadEventImages($event_id);
		if($AJAX){
			echo $this->load->view('event/images/images_thumbs', $data);
		} else {
			return $this->load->view('event/images/images_thumbs', $data, TRUE);
		}
	}
}