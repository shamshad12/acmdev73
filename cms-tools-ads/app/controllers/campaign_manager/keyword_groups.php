<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class keyword_groups extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_keyword_groups');
		$this->load->model('campaign-manager/master/model_partners');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['partner'] = $this->model_partners->loadPartnersSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_keyword_groups";
		$this->load->view('layout/main', $this->data);
	}

	public function loadKeyword_Groups(){
		$params = $_POST;
		$result = $this->model_keyword_groups->loadKeyword_Groups($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadKeyword_Groups');
		
		echo json_encode($result);
	}
	
	public function loadKeyword_GroupsSelect(){
		$params = $_POST;
		$result = $this->model_keyword_groups->loadKeyword_GroupsSelect($params);
				
		echo json_encode($result);
	}
	
	public function saveKeyword_Groups(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_keyword_groups->saveKeyword_Groups($params);
			echo json_encode($result);
		}
	}
	
	public function deleteKeyword_Groups(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_keyword_groups->deleteKeyword_Groups($params);
			echo json_encode($result);
		}
	}
	
	public function getKeyword_GroupsData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_keyword_groups->getKeyword_GroupsData($params);
			echo json_encode($result);
		}
	}
}
?>
