<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class operators_partners extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_operators_partners');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_partners');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['operator'] = $this->model_operators->loadOperatorsSelect();
		$this->data['partner'] = $this->model_partners->loadPartnersSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_operators_partners";
		$this->load->view('layout/main', $this->data);
	}

	public function loadOperators_Partners(){
		$params = $_POST;
		$result = $this->model_operators_partners->loadOperators_Partners($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadOperators_Partners');
		
		echo json_encode($result);
	}
	
	public function loadOperators_PartnersSelect(){
		$params = $_POST;
		$result = $this->model_operators_partners->loadOperators_PartnersSelect($params);		
				
		echo json_encode($result);
	}
	
	public function saveOperators_Partners(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_operators_partners->saveOperators_Partners($params);
			echo json_encode($result);
		}
	}
	
	public function deleteOperators_Partners(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_operators_partners->deleteOperators_Partners($params);
			echo json_encode($result);
		}
	}
	
	public function getOperators_PartnersData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_operators_partners->getOperators_PartnersData($params);
			echo json_encode($result);
		}
	}
}
?>
