<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class webapi extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/webapi/model_webapi');
		$this->load->model('campaign-manager/parameter/model_country');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['code_affiliate'] = $this->model_webapi->loadWebAPISelect();
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/webapi/view_webapi";
		$this->load->view('layout/main', $this->data);
	}

	public function loadWebAPI(){
		$params = $_POST;
		$result = $this->model_webapi->loadWebAPI($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadWebAPI');
		
		echo json_encode($result);
	}
	
	public function saveWebAPI(){
		$params = $_POST;
		if(sizeof($params)){

			if($params['id'])
				file_get_contents(URL_WEBAPI_REDIS_DEL.$params['id']);
			
			$result = $this->model_webapi->saveWebAPI($params);

			// print_r($result);die('ll');
			if($result['success'])
				file_get_contents(URL_WEBAPI_REDIS.$result['id']);
			
			echo json_encode($result);
		}
	}

	public function saveTestwebAPI(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_webapi->saveTestwebAPI($params);
			
			echo json_encode($result);
		}
	}
	
	public function deleteWebAPI(){
		if(sizeof($_POST)){
			$params = $_POST;

			if($params['id'])
				file_get_contents(URL_WEBAPI_REDIS_DEL.$params['id']);
			
			$result = $this->model_webapi->deleteWebAPI($params);
			
			echo json_encode($result);
		}
	}
	
	public function getWebAPIData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_webapi->getWebAPIData($params);
			echo json_encode($result);
		}
	}
}
?>
