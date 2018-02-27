<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Partner extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('api_partner/model_partner');
	}
	
	public function index() {
		/*echo '<pre>';
		print_r($this->userdata->session());*/

		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "api_partner/view_partner";
		$this->load->view('layout/main', $this->data);
	}

	public function loadPartner(){
		$params = $_POST;
		$result = $this->model_partner->loadPartner($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadPartner');
		
		echo json_encode($result);
	}
	
	public function savePartner(){
		$params = $_POST; 
                
		if(sizeof($params)){ 
			$result = $this->model_partner->savePartner($params);
			
			echo json_encode($result);
		}
	}
	
	public function deletePartner(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_partner->deletePartner($params);
			echo json_encode($result);
		}
	}
	
	public function getPartnerData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_partner->getPartnerData($params);
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
