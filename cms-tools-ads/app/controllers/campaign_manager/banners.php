<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class banners extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_banners');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_banners";
		$this->load->view('layout/main', $this->data);
	}

	public function loadBanners(){
		$params = $_POST;
		$result = $this->model_banners->loadBanners($params);		
		
		$result['pagination'] 	= $this->pagination($params['page'], $result['total'], $params['limit'], 'loadBanners');
		
		$dirAccess = $this->config->item('image_access');
		$result['host']			= $dirAccess['campaigns']['host'];
		
		echo json_encode($result);
	}
	
	public function saveBanners(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_banners->saveBanners($params);
			echo json_encode($result);
		}
	}
	
	public function deleteBanners(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_banners->deleteBanners($params);
			echo json_encode($result);
		}
	}
	
	public function getBannersData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_banners->getBannersData($params);
			echo json_encode($result);
		}
	}
	
public function uploadBackgroundGallery(){
		if(!isset($_GET['files']))
			echo json_encode(array('error' => 'Missing Parameter', 'status' => false));
		
		$dirUpload = $this->config->item('image_upload');
		$dirAccess = $this->config->item('image_access');
		
		foreach($_FILES as $file)
		{
			// Detect allowed file extentions
		        $valid_file_extensions = array(".jpg", ".jpeg", ".png", ".gif",".wbmp");
		        $file_extension = strtolower(strrchr($file["name"], "."));
		        // Check that the uploaded file is actually an image
		        if (!in_array($file_extension, $valid_file_extensions)) {
			    $result['error'] 	= 'Please upload png, jpeg and gif files only.';
			    $result['status'] 	= false;
		        }
			else
			{ 
				$physicName = strtolower(str_replace(' ', '',($file['name'])));
			
				#Upload Original Image
				if(move_uploaded_file($file['tmp_name'], $dirUpload['campaigns']['ori'].$physicName)){			
					$result['url']['ori'] 		= $dirAccess['campaigns']['ori'].$physicName;
					$result['url']['thumb'] 	= $dirAccess['campaigns']['thumb'].$physicName;
					$result['path']['ori'] 		= $dirUpload['campaigns']['ori'].$physicName;
					$result['host'] 			= $dirAccess['campaigns']['host'];
				
					$result['show'] 			= $file['name'];
					$result['status'] 			= true;
				} else {
					$result['error'] 	= 'Uploading error.';
					$result['status'] 	= false;
				}
			
				if($result['status']){				
					//identitas file asli  
					if ($file['type']=="image/jpeg" ){
						$im_src = imagecreatefromjpeg($dirUpload['campaigns']['ori'].$physicName);
					} elseif ($file['type']=="image/png" ){
						$im_src = imagecreatefrompng($dirUpload['campaigns']['ori'].$physicName);
					}elseif ($file['type']=="image/gif" ){
						$im_src = imagecreatefromgif($dirUpload['campaigns']['ori'].$physicName);
					}elseif ($file['type']=="image/wbmp" ){
						$im_src = imagecreatefromwbmp($dirUpload['campaigns']['ori'].$physicName);
					}
				
					$src_width = imageSX($im_src);
					$src_height = imageSY($im_src);

					//Simpan dalam versi small 110 pixel
					$dst_width = 100;
					$dst_height = ($dst_width/$src_width)*$src_height;
					//$dst_height = 200;
					//$dst_width = ($dst_height/$src_height)*$src_width;
				
					//proses perubahan ukuran
					$im = imagecreatetruecolor($dst_width,$dst_height);
					imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
				
					//Simpan gambar
					if ($file['type']=="image/jpeg" ){
						imagejpeg($im, $dirUpload['campaigns']['thumb'].$physicName);
					} elseif ($file['type']=="image/png" ){
						imagepng($im, $dirUpload['campaigns']['thumb'].$physicName);
					} elseif ($file['type']=="image/gif" ){
						imagegif($im, $dirUpload['campaigns']['thumb'].$physicName);
					} elseif($file['type']=="image/wbmp" ){
						imagewbmp($im, $dirUpload['campaigns']['thumb'].$physicName);
					}
				
					$result['url']['thumb'] 	= $dirAccess['campaigns']['thumb'].$physicName;
					$result['path']['thumb'] 	= $dirUpload['campaigns']['thumb'].$physicName;
				
					//Hapus gambar di memori komputer
					imagedestroy($im_src);
					imagedestroy($im);
				}
			}
		}
		echo json_encode($result);
	}
}
?>
