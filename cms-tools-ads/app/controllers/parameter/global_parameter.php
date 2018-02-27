<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class global_parameter extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('parameter/model_global_parameter_backend');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "parameter/view_global_parameter";
		$this->load->view('layout/main', $this->data);
	}

	public function loadGlobal_Parameter(){
		$params = $_POST;
		$result = $this->model_global_parameter_backend->loadGlobalParameter($params);	
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadGlobal_Parameter');
		
		echo json_encode($result);
	}
	
	public function saveGlobal_Parameter(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_global_parameter_backend->saveGlobalParameter($params);
			echo json_encode($result);
		}
	}
	
	public function deleteGlobal_Parameter(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_global_parameter_backend->deleteGlobalParameter($params);
			echo json_encode($result);
		}
	}
	
	public function getGlobal_ParameterData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_global_parameter_backend->getGlobalParameterData($params);
			echo json_encode($result);
		}
	}
}
?>
