<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class privileges_menu extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('user-management/model_privileges_menu');
		$this->load->model('user-management/model_privileges');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		#Bring tipeuser Data
		$this->data['privileges'] = $this->model_privileges->loadPrivilegesSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "user-management/view_privileges_menu";
		$this->load->view('layout/main', $this->data);
	}

	public function loadPrivilegesMenu(){
		$params = $_POST;
		$result = $this->model_privileges_menu->loadPrivilegesMenu($params);		
		#print_r($result);
		echo json_encode($result);
	}
	
	public function setAccess(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_privileges_menu->setAccess($params);
			echo json_encode($result);
		}
	}
	
	public function savePrivilegesMenu(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_privileges_menu->savePrivilegesMenu($params);
			echo json_encode($result);
		}
	}
	
	public function deletePrivilegesMenu(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_privileges_menu->deletePrivilegesMenu($params);
			echo json_encode($result);
		}
	}
	
	public function getPrivilegesMenuData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_privileges_menu->getPrivilegesMenuData($params);
			echo json_encode($result);
		}
	}
}
?>
