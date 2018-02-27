<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class upload_apk extends Admin_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('campaign-manager/master/model_upload_apk');
		$this->load->model('campaign-manager/parameter/model_country');
	}

	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();

		if(!$this->data['accessView'])
		redirect($this->data['base_url_index'].'login', 'location', 301);
		$this->data['template_id'] = '';
		if(isset($_GET['template_id']) && trim($_GET['template_id']) !='')
		{
			$this->data['template_id'] = $_GET['template_id'];
		}
			
		#Load User List
		$this->data['user_list'] = $this->model_upload_apk->getUserList();

		#Load Country
		$this->data['country'] = $this->model_country->loadCountrySelect();

		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_upload_apk";
		$this->load->view('layout/main', $this->data);
	}

	public function loadApk(){
		$params = $_POST;
		$result = $this->model_upload_apk->loadApk($params);

		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadApk');

		echo json_encode($result);
	}

	public function loadApkSelect(){
		$params = $_POST;
		$result = $this->model_upload_apk->loadApkSelect($params);
		echo json_encode($result);
	}

	public function saveUploadApk(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_upload_apk->saveUploadApk($params);

			if($result['success'])
				file_get_contents(URL_APK_REDIS.$result['id']);

			echo json_encode($result);
		}
	}

	public function getApkData(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_upload_apk->getApkData($params);
			echo json_encode($result);
		}
	}

	public function deleteApk(){
		if(sizeof($_POST)){
			$params = $_POST;
			file_get_contents(URL_APK_REDIS_DEL.$params['id']);
			$result = $this->model_upload_apk->deleteApk($params);
			echo json_encode($result);
		}
	}

	public function uploadBackground(){
		
		$dirUpload = $this->config->item('upload_apk');
		$apkip = $this->config->item('image_access');
        $result=array();
        $result['view']='';
		
		$physicName = date('YmdHis');
        $nam = $physicName . $_FILES[0]['name'];
        $ext = explode('.', $_FILES[0]['name']);
        $file = $dirUpload['apk'] . $nam;
        if($ext[1]=='apk')
        {
			exec('chmod 0777 '.$dirUpload['apk']);
			if(move_uploaded_file($_FILES[0]['tmp_name'], $file)){
				$result['url'] 		= $apkip['campaigns']['host'].'apk/'.$nam;
				$result['status']	= true;
			} else {
				$result['error'] 	= 'Uploading error.';
				$result['status'] 	= false;
			}
			exec('chmod 0755 '.$dirUpload['apk']);
		}
		else
		{
			$result['error'] 	= 'Uploading error, Please upload apk file only.';
			$result['status'] 	= false;
		}
		
		echo json_encode($result);
	}

	public function Status(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_upload_apk->changeStatus($params);
			echo json_encode($result);
		}
	}

}

?>
