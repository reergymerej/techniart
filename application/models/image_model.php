<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image_model extends CI_Model {
	private $IMAGE_TABLE = 'image';
	
	private $EXTENSIONS = array('jpg', 'png', 'gif', 'jpeg');
	private $THUMB_SIZE = 100;
	private $UPLOAD_DIR_NAME = 'upload';
	private $MAX_FILE_SIZE;
	private $STORAGE_DIR;
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->MAX_FILE_SIZE = 1024 * 1024 * 2;	//	2 MB
		$this->STORAGE_DIR = getcwd() . DIRECTORY_SEPARATOR . $this->UPLOAD_DIR_NAME;
	}
	
	/*
	|	Uploads images and creates thumbnails
	|	$data['image']		(required)	$_FILES['image'] array
	|	$data['event_id']	(required)	corresponding event id
	|
	|	Returns array with info about results.
	|	for each $data['image']:
	|	$result['name']['result']	(success or error message)
	*/
	public function upload( $data = array() ){
		if( !$data['image'] || !$data['event_id'] ){
			return FALSE;
		}
		
		$MAX_FILES = 5;
		$snowflake = $data['event_id'] . '_' . time() . '_' . rand();

		$result = array();
		
		//	loop over each file
		for( $f = 0; $f < count( $data['image']['name'] ) && $f < $MAX_FILES; $f++ ){
			
			//	easier references to file
			$name 		= $data['image']['name'][$f];
			$type 		= $data['image']['type'][$f];
			$tmp_name 	= $data['image']['tmp_name'][$f];
			$error 		= $data['image']['error'][$f];
			$size 		= $data['image']['size'][$f];
			
			$result[$f] = array('name'=>$name, 'result'=>NULL);
			
			//	was a file provided?
			if( !$name ){
				$result[$f]['result'] = "file name not found";
				continue;
			}
			
			//	check for error
			if( $error ){
				$result[$f]['result'] = "upload error $error";
				continue;	
			}
			
			//	verify type
			//	TODO:: more robust file type checking
			$extension = strtolower( substr( $name, strpos( $name, '.' ) + 1 ) );
			if( array_search( $extension, $this->EXTENSIONS ) === FALSE ){
				$result[$f]['result'] = "$extension file types not allowed";
				continue;
			}
			
			//	verify size
			if( $size > $this->MAX_FILE_SIZE ){
				$result[$f]['result'] = "file too large ($size)";
				continue;
			}
			
			//	verify access to storage directory
			if( is_writable( $this->STORAGE_DIR ) ){
				
				//	give file a unique name
				$name = "{$snowflake}_$f.$extension";
				$thumb_name = "{$snowflake}_{$f}_thumb.$extension";
				
				//	save file
				//	move_uploaded_file($tmp_name, "$this->STORAGE_DIR/$name");	//	not working on Windows (unable to access moved file)
				copy($tmp_name, "$this->STORAGE_DIR/$name");
				
				//	add record to db
				//	do this now so there is a record to reference in case there are problems with the thumbnail generation
				$image_data = array('id_event'=>$data['event_id'], 'name'=>$name);
				$this->db->insert($this->IMAGE_TABLE, $image_data);
				
				//	create a thumbnail
				list($w, $h) = getimagesize("$this->STORAGE_DIR/$name");
				
				//	calculate new dimensions (max out lesser of width or height, trim the rest)
				$x_offset = 0;
				$y_offset = 0;
				$new_w = $w;
				$new_h = $h;
				
				if( $w < $h ){
					$new_h = $w;
					$y_offset = ($h - $w) / 2;
				} else {
					$new_w = $h;
					$x_offset = ($w - $h) / 2;
				}
				
				if($thumb = imagecreatetruecolor($this->THUMB_SIZE, $this->THUMB_SIZE) ){
					//	create based on image type
					switch($extension){
						case 'jpg':
							$source = imagecreatefromjpeg("$this->STORAGE_DIR/$name");	
							break;	
						case 'png':
							$source = imagecreatefrompng("$this->STORAGE_DIR/$name");
							break;
						case 'gif':
							$source = imagecreatefromgif("$this->STORAGE_DIR/$name");
							break;
					}
					
					imagecopyresampled(
						$thumb, $source, 
						0, 0,
						$x_offset,	$y_offset,
						$this->THUMB_SIZE, $this->THUMB_SIZE, 
						$new_w, $new_h);
					
					imagejpeg($thumb, "$this->STORAGE_DIR/$thumb_name");	
					$result[$f]['result'] = "success";
					
				} else {
					$result[$f]['result'] = "unable to generate thumbnail";
				}
			} else {
				show_error("unable to save image(s)");
			}
		}
		
		//	clear out any empty files from the result
		for($i=0; $i<count($result); $i++){
			if( !$result[$i]['name'] ){
				array_splice( $result, $i, 1);
				$i--;
			}
		}
		return $result;
	}
	
	public function deleteAllEventImages( $event_id = NULL ){
		//	look up all images
		$q = $this->db->get_where( $this->IMAGE_TABLE, array('id_event'=>$event_id) );
		
		if( $images = $q->result_array() ){
			//	loop through images and delete full & thumb
			foreach($images as $image){
				$image['thumb'] = implode('_thumb.', explode('.', $image['name']));
				//	TODO:: this seems sloppy, find a better way
				$image['thumb'] = $this->UPLOAD_DIR_NAME . '/' . $image['thumb'];
				$image['name'] = $this->UPLOAD_DIR_NAME . '/' . $image['name'];
		
				//	delete file
				unlink($image['thumb']);
				unlink($image['name']);	
			}
			
			//	delete db references
			$this->db->delete( $this->IMAGE_TABLE, array( 'id_event'=>$event_id ) );	
		}
		
		return TRUE;
	}
	
	public function delete( $event_id = NULL, $image_id = NULL ){
		//	look up image details
		$q = $this->db->get_where( $this->IMAGE_TABLE, array('id_event'=>$event_id, 'id'=>$image_id) );
		$image = $q->row_array();

		$image['thumb'] = implode('_thumb.', explode('.', $image['name']));	
		//	TODO:: this seems sloppy, find a better way
		$image['thumb'] = $this->UPLOAD_DIR_NAME . '/' . $image['thumb'];
		$image['name'] = $this->UPLOAD_DIR_NAME . '/' . $image['name'];

		//	delete file
		unlink($image['thumb']);
		unlink($image['name']);
		
		//	delete data
		$this->db->delete( $this->IMAGE_TABLE, array( 'id'=>$image_id ) );	
	}
	
	/*
	|	Get an array of images for $event_id
	*/
	public function loadEventImages($event_id=NULL){
		if(!$event_id){
			return FALSE;	
		}
		
		$this->db->select('id, name');
		$query = $this->db->get_where($this->IMAGE_TABLE, array('id_event'=>$event_id));
		if( $images = $query->result_array() ){
			//	add thumb references here so the controllers/views don't have to worry about how it's done
			foreach($images as &$i){
				$i['thumb'] = implode('_thumb.', explode('.', $i['name']));
				
				//	TODO:: this seems sloppy, find a better way
				$i['thumb'] = base_url() . $this->UPLOAD_DIR_NAME . '/' . $i['thumb'];
				$i['name'] = base_url() . $this->UPLOAD_DIR_NAME . '/' . $i['name'];
			}
			return $images;
		} else {
			return FALSE;	
		}
	}
	
	
}