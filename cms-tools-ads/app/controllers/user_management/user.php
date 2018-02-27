<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class user extends Admin_Controller {
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('user-management/model_user');
		$this->load->model('user-management/model_karyawan');
		$this->load->model('parameter/model_tipeuser');
	}
	
	public function index() {		
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		$this->data['karyawan']			= $this->model_karyawan->reportKaryawan();
		$this->data['tipe_user']		= $this->model_tipeuser->loadTipeUser();
		$this->data['pageBreadCumb'] 	= "";
		$this->data['pageTemplate']		= "user-management/view_user";
		$this->load->view('layout/main', $this->data);
	}

	public function loadUser(){
		$params = $_POST;
		$result = $this->model_user->loadUser($params);
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadUser');
		
		echo json_encode($result);
	}
	
	public function saveUser(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_user->saveUser($params);
			echo json_encode($result);
		}
	}
	
	public function deleteUser(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_user->deleteUser($params);
			echo json_encode($result);
		}
	}
	
	public function getUserData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_user->getUserData($params);
			echo json_encode($result);
		}
	}
}
?>
