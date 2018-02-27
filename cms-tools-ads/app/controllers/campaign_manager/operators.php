<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class operators extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/parameter/model_country');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['country'] = $this->model_country->loadCountrySelect();
		
		$this->data['utc_timezone'] = $this->setUtcTimezone();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_operators";
		$this->load->view('layout/main', $this->data);
	}

	public function loadOperators(){
		$params = $_POST;
		$result = $this->model_operators->loadOperators($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadOperators');
		
		echo json_encode($result);
	}
	
	public function saveOperators(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_operators->saveOperators($params);
			echo json_encode($result);
		}
	}
	
	public function deleteOperators(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_operators->deleteOperators($params);
			echo json_encode($result);
		}
	}
	
	public function getOperatorsData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_operators->getOperatorsData($params);
			echo json_encode($result);
		}
	} 
}
?>
