<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class tipeuser extends Admin_Controller {
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('parameter/model_tipeuser');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "parameter/view_tipeuser";
		$this->load->view('layout/main', $this->data);
	}

	public function loadTipeUser(){
		$result = $this->model_tipeuser->loadTipeUser();
		echo json_encode($result);
	}
	
	public function saveTipeUser(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_tipeuser->saveTipeUser($params);
			echo json_encode($result);
		}
	}
	
	public function deleteTipeUser(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_tipeuser->deleteTipeUser($params);
			echo json_encode($result);
		}
	}
	
	public function getTipeUserData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_tipeuser->getTipeUserData($params);
			echo json_encode($result);
		}
	}
}
?>
