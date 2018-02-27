<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class campaigns_media extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/campaigns/model_campaigns_media');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/campaigns/view_campaigns_media";
		$this->load->view('layout/main', $this->data);
	}

	public function loadCampaigns_Media(){
		$params = $_POST;
		$result = $this->model_campaigns_media->loadCampaigns_Media($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadCampaigns_Media');
		
		echo json_encode($result);
	}
	
	public function saveCampaigns_Media(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_campaigns_media->saveCampaigns_Media($params);
			echo json_encode($result);
		}
	}
	
	public function deleteCampaigns_Media(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_campaigns_media->deleteCampaigns_Media($params);
			echo json_encode($result);
		}
	}
	
	public function getCampaigns_MediaData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_campaigns_media->getCampaigns_MediaData($params);
			echo json_encode($result);
		}
	}
}
?>
