<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class publisher_account extends Admin_Controller {
		
	public function __construct() {
		parent::__construct();
			
		$this->load->model('user-management/model_publisher');
		$this->load->model('campaign-manager/master/model_ads_publishers');
		$this->load->model('parameter/model_tipeuser');
	}
	
	public function index() {		
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		$this->data['pageBreadCumb'] 	= "";
		$this->data['pageTemplate']		= "user-management/view_publisher";
		
		$this->data['tipe_user']		= $this->model_tipeuser->loadTipeUser();
		
		$this->data['publishers']			= $this->model_ads_publishers->loadAds_PublishersSelect();
		
		$this->load->view('layout/main', $this->data);
	}

	public function loadPublisher(){
		$params = $_POST;
		$result = $this->model_publisher->loadPublisher($params);
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadPublisher');
		
		echo json_encode($result);
	}
	
	public function savePublisher(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_publisher->savePublisher($params);
			echo json_encode($result);
		}
	}
	
	public function deletePublisher(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_publisher->deletePublisher($params);
			echo json_encode($result);
		}
	}
	
	public function getPublisherData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_publisher->getPublisherData($params);
			echo json_encode($result);
		}
	}
	
	public function uploadBackgroundAvatar(){
		if(!isset($_GET['files']))
			echo json_encode(array('error' => 'Missing Parameter', 'status' => false));
		
		$dirUpload = $this->config->item('image_upload');
		$dirAccess = $this->config->item('image_access');
		
		foreach($_FILES as $file)
		{
			$physicName = strtolower(str_replace(' ', '',($file['name'])));
			
			#Upload Original Image
			if(move_uploaded_file($file['tmp_name'], $dirUpload['ori'].$physicName)){			
				$result['files']['ori'] 	= $dirAccess['ori'].$physicName;
				$result['files']['thumb'] 	= $dirAccess['thumb'].$physicName;
				$result['show'] 			= $file['name'];
				$result['status'] 			= true;
			} else {
				$result['error'] 	= 'Uploading error.';
				$result['status'] 	= false;
			}
			
			if($result['status']){				
				//identitas file asli  
				if ($file['type']=="image/jpeg" ){
					$im_src = imagecreatefromjpeg($dirUpload['ori'].$physicName);
				} elseif ($file['type']=="image/png" ){
					$im_src = imagecreatefrompng($dirUpload['ori'].$physicName);
				}elseif ($file['type']=="image/gif" ){
					$im_src = imagecreatefromgif($dirUpload['ori'].$physicName);
				}elseif ($file['type']=="image/wbmp" ){
					$im_src = imagecreatefromwbmp($dirUpload['ori'].$physicName);
				}
				
				$src_width = imageSX($im_src);
				$src_height = imageSY($im_src);

				//Simpan dalam versi small 110 pixel
				//$dst_width = 110;
				//$dst_height = ($dst_width/$src_width)*$src_height;
				$dst_height = 50;
				$dst_width = ($dst_height/$src_height)*$src_width;
				
				//proses perubahan ukuran
				$im = imagecreatetruecolor($dst_width,$dst_height);
				imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
				
				//Simpan gambar
				if ($file['type']=="image/jpeg" ){
					imagejpeg($im, $dirUpload['thumb'].$physicName);
				} elseif ($file['type']=="image/png" ){
					imagepng($im, $dirUpload['thumb'].$physicName);
				} elseif ($file['type']=="image/gif" ){
					imagegif($im, $dirUpload['thumb'].$physicName);
				} elseif($file['type']=="image/wbmp" ){
					imagewbmp($im, $dirUpload['thumb'].$physicName);
				}
				
				$result['files']['thumb'] 	= $dirAccess['thumb'].$physicName;
				
				//Hapus gambar di memori komputer
				imagedestroy($im_src);
				imagedestroy($im);
			}
		}
		echo json_encode($result);
	}
}
?>
