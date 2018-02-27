<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class campaigns_categories extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/campaigns/model_campaigns_categories');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/campaigns/view_campaigns_categories";
		$this->load->view('layout/main', $this->data);
	}

	public function loadCampaigns_Categories(){
		$params = $_POST;
		$result = $this->model_campaigns_categories->loadCampaigns_Categories($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadCampaigns_Categories');
		
		echo json_encode($result);
	}
	
	public function saveCampaigns_Categories(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_campaigns_categories->saveCampaigns_Categories($params);
			echo json_encode($result);
		}
	}
	
	public function deleteCampaigns_Categories(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_campaigns_categories->deleteCampaigns_Categories($params);
			echo json_encode($result);
		}
	}
	
	public function getCampaigns_CategoriesData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_campaigns_categories->getCampaigns_CategoriesData($params);
			echo json_encode($result);
		}
	}
}
?>
