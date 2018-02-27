<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class account extends Admin_Controller {
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('user-management/model_account');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		$this->data['account']			= $this->model_account->getAccountData();
		$this->data['pageBreadCumb'] 	= "";
		$this->data['pageTemplate']		= "user-management/view_account";
		$this->load->view('layout/main', $this->data);
	}
	
	public function saveAccount(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_account->saveAccount($params);
			echo json_encode($result);
		}
	}
	
	public function getAccountData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_account->getAccountData($params);
			echo json_encode($result);
		}
	}
}
?>
