<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class domains extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_domains');
		$this->load->model('campaign-manager/parameter/model_country');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['country'] = $this->model_country->loadCountrySelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_domains";
		$this->load->view('layout/main', $this->data);
	}

	public function loadDomains(){
		$params = $_POST;
		$result = $this->model_domains->loadDomains($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadDomains');
		
		echo json_encode($result);
	}
	
	public function saveDomains(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_domains->saveDomains($params);
			echo json_encode($result);
		}
	}
	
	public function deleteDomains(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_domains->deleteDomains($params);
			echo json_encode($result);
		}
	}
	
	public function getDomainsData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_domains->getDomainsData($params);
			echo json_encode($result);
		}
	}
	
	public function loadDomainsSelect(){
		$params = $_POST;
		$result = $this->model_domains->loadDomainsSelect($params);		
		
		echo json_encode($result);
	}

}
?>
