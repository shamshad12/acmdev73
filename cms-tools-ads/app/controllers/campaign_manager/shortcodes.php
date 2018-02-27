<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class shortcodes extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_shortcodes');
		$this->load->model('campaign-manager/parameter/model_country');
                $this->load->model('campaign-manager/master/model_partners');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		$this->data['partner'] = $this->model_partners->loadPartnersSelect();	
		$this->data['country'] = $this->model_country->loadCountrySelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_shortcodes";
		$this->load->view('layout/main', $this->data);
	}

	public function loadShortcodes(){
		$params = $_POST;
		$result = $this->model_shortcodes->loadShortcodes($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadShortcodes');
		
		echo json_encode($result);
	}
	
	public function saveShortcodes(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_shortcodes->saveShortcodes($params);
			echo json_encode($result);
		}
	}
	
	public function deleteShortcodes(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_shortcodes->deleteShortcodes($params);
			echo json_encode($result);
		}
	}
	
	public function getShortcodesData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_shortcodes->getShortcodesData($params);
			echo json_encode($result);
		}
	}  
}
?>
