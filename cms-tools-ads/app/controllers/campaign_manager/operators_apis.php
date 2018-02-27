<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class operators_apis extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_operators_apis');
		$this->load->model('campaign-manager/master/model_operators');
	}
	
	public function index() {
		
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['operator'] = $this->model_operators->loadOperatorsSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_operators_apis";

		$this->load->view('layout/main', $this->data);
	}

	public function loadOperators_Apis(){
		$params = $_POST;
		$result = $this->model_operators_apis->loadOperators_Apis($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadOperators_Apis');
		
		echo json_encode($result);
	}
	
	public function loadOperators_ApisSelect(){
		$params = $_POST;
		$result = $this->model_operators_apis->loadOperators_ApisSelect($params);		
		
		echo json_encode($result);
	}
	
	public function saveOperators_Apis(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_operators_apis->saveOperators_Apis($params);
			echo json_encode($result);
		}
	}
	
	public function deleteOperators_Apis(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_operators_apis->deleteOperators_Apis($params);
			echo json_encode($result);
		}
	}
	
	public function getOperators_ApisData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_operators_apis->getOperators_ApisData($params);
			echo json_encode($result);
		}
	}
}
?>
