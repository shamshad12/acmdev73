<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class prices extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_prices');
		$this->load->model('campaign-manager/parameter/model_country');
		$this->load->model('campaign-manager/parameter/model_currencies');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['country'] = $this->model_country->loadCountrySelect();
		$this->data['currency'] = $this->model_currencies->loadCurrenciesSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_prices";
		$this->load->view('layout/main', $this->data);
	}

	public function loadPrices(){
		$params = $_POST;
		$result = $this->model_prices->loadPrices($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadPrices');
		
		echo json_encode($result);
	}
	
	public function loadPricesSelect(){
		$params = $_POST;
		$result = $this->model_prices->loadPricesSelect($params);		
		
		echo json_encode($result);
	}
	
	public function savePrices(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_prices->savePrices($params);
			echo json_encode($result);
		}
	}
	
	public function deletePrices(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_prices->deletePrices($params);
			echo json_encode($result);
		}
	}
	
	public function getPricesData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_prices->getPricesData($params);
			echo json_encode($result);
		}
	}
}
?>
