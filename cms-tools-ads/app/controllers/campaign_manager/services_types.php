<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class services_types extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/parameter/model_services_types');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/parameter/view_services_types";
		$this->load->view('layout/main', $this->data);
	}

	public function loadServices_Types(){
		$params = $_POST;
		$result = $this->model_services_types->loadServices_Types($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadServices_Types');
		
		echo json_encode($result);
	}
	
	public function saveServices_Types(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_services_types->saveServices_Types($params);
			echo json_encode($result);
		}
	}
	
	public function deleteServices_Types(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_services_types->deleteServices_Types($params);
			echo json_encode($result);
		}
	}
	
	public function getServices_TypesData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_services_types->getServices_TypesData($params);
			echo json_encode($result);
		}
	}
}
?>
