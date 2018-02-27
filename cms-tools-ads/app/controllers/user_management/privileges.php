<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class privileges extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('user-management/model_privileges');
		$this->load->model('parameter/model_tipeuser');
		$this->load->model('user-management/model_privileges_menu');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		#Bring tipeuser Data
		$this->data['tipeUser'] = $this->model_tipeuser->loadTipeUser();
		
		#Load Default Menu
		$this->data['menus'] = $this->model_privileges_menu->loadPrivilegesMenu(array('p.id' => '2'));
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "user-management/view_privileges";
		$this->load->view('layout/main', $this->data);
	}

	public function loadPrivileges(){
		$params = $_POST;
		$result = $this->model_privileges->loadPrivileges($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadPrivileges');
		
		echo json_encode($result);
	}
	
	public function savePrivileges(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_privileges->savePrivileges($params);
			echo json_encode($result);
		}
	}
	
	public function deletePrivileges(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_privileges->deletePrivileges($params);
			echo json_encode($result);
		}
	}
	
	public function getPrivilegesData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_privileges->getPrivilegesData($params);
			echo json_encode($result);
		}
	}
}
?>
