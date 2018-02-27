<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class telcos extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('zing-wallet/master/model_telcos');
		$this->load->model('zing-wallet/parameter/model_country');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		#Load Country Data
		$this->data['country'] = $this->model_country->loadCountrySelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "zing-wallet/master/view_telcos";
		$this->load->view('layout/main', $this->data);
	}

	public function loadTelcos(){		
		$params = $_POST;
		$result = $this->model_telcos->loadTelcos($params);
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadTelcos');
		echo json_encode($result);
	}
	
	public function saveTelcos(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_telcos->saveTelcos($params);
			echo json_encode($result);
		}
	}
	
	public function deleteTelcos(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_telcos->deleteTelcos($params);
			echo json_encode($result);
		}
	}
	
	public function getTelcosData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_telcos->getTelcosData($params);
			echo json_encode($result);
		}
	}
}
?>
