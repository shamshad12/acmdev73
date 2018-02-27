<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class partners extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_partners');
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
		$this->data['pageTemplate']	= "campaign-manager/master/view_partners";
		$this->load->view('layout/main', $this->data);
	}

	public function loadPartners(){
		$params = $_POST;
		$result = $this->model_partners->loadPartners($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadPartners');
		
		echo json_encode($result);
	}
	
	public function savePartners(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_partners->savePartners($params);
			echo json_encode($result);
		}
	}
	
	public function deletePartners(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_partners->deletePartners($params);
			echo json_encode($result);
		}
	}
	
	public function getPartnersData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_partners->getPartnersData($params);
			echo json_encode($result);
		}
	}
}
?>
