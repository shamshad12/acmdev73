<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class currencies extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/parameter/model_currencies');
		$this->load->model('campaign-manager/parameter/model_country');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['country'] = $this->model_country->loadCountrySelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/parameter/view_currencies";
		$this->load->view('layout/main', $this->data);
	}

	public function loadCurrencies(){
		$params = $_POST;
		$result = $this->model_currencies->loadCurrencies($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadCurrencies');
		
		echo json_encode($result);
	}
	
	public function saveCurrencies(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_currencies->saveCurrencies($params);
			echo json_encode($result);
		}
	}
	
	public function deleteCurrencies(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_currencies->deleteCurrencies($params);
			echo json_encode($result);
		}
	}
	
	public function getCurrenciesData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_currencies->getCurrenciesData($params);
			echo json_encode($result);
		}
	}
}
?>
