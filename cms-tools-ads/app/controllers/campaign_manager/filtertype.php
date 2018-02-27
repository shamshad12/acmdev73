<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');   
  
class filtertype extends Admin_Controller { 

	var $base_url;
		 
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/filterlist/model_filtertype');
		$this->load->model('campaign-manager/parameter/model_country');
	}
	
	public function index() {

		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['country'] = $this->model_country->loadCountrySelect();
		
		
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/filterlist/view_filtertype"; 
		$this->load->view('layout/main', $this->data);
	}

	public function loadFiltertype(){
		$params = $_POST;  
		$result = $this->model_filtertype->loadFiltertype($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadFiltertype');
		
		echo json_encode($result);
	}
	
	public function saveFiltertype(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_filtertype->saveFiltertype($params);
			echo json_encode($result);
		}
	}
	
	public function deleteFiltertype(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_filtertype->deleteFiltertype($params);
			echo json_encode($result);
		}
	}
	
	public function getFiltertypeData(){ 
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_filtertype->getFiltertypeData($params);
			echo json_encode($result);
		}
	}
}
?>
 