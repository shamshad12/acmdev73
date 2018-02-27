<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class language extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_language');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_language";
		$this->load->view('layout/main', $this->data);
	}

	public function loadLanguage(){

		$params = $_POST;
		$result = $this->model_language->loadLanguage($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadLanguage');
		
		echo json_encode($result);
	}
	
	public function saveLanguage(){
		$params = $_POST; 
		
		if(sizeof($params)){ 
			$result = $this->model_language->saveLanguage($params);
			echo json_encode($result);
		}
	}
	
	public function deleteLanguage(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_language->deleteLanguage($params);
			echo json_encode($result);
		}
	}
	
	public function getLanguageData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_language->getLanguageData($params);
			echo json_encode($result);
		}
	}
}
?>
