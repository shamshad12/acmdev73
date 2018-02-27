<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class country extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/parameter/model_country');
	}
	
	public function index() {
		/*echo '<pre>';
		print_r($this->userdata->session());*/

		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/parameter/view_country";
		$this->load->view('layout/main', $this->data);
	}

	public function loadCountry(){
		$params = $_POST;
		$result = $this->model_country->loadCountry($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadCountry');
		
		echo json_encode($result);
	}
	
	public function saveCountry(){
		$params = $_POST; 
		if(sizeof($params)){ 
			$result = $this->model_country->saveCountry($params);
			
			echo json_encode($result);
		}
	}
	
	public function deleteCountry(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_country->deleteCountry($params);
			echo json_encode($result);
		}
	}
	
	public function getCountryData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_country->getCountryData($params);
			echo json_encode($result);
		}
	}
	public function test()
	{
				//$this->load->library("session");
		///echo '<pre>';
		//echo $string=implode(" , ",$this->session->userdata('profile'));
		 //echo http_build_query($this->session->userdata('profile'),'',', ');

		//print_r(explode($this->session->userdata('profile'));  
		//$this->userdata->session();
		exit;	
	}
}
?>
