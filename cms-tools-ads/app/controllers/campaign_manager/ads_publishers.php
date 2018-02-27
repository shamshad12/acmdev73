<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ads_publishers extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_ads_publishers');
		$this->load->model('campaign-manager/parameter/model_country');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['country'] = $this->model_country->loadCountrySelect();
		
		$this->data['utc_timezone'] = $this->setUtcTimezone();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_ads_publishers";
		$this->load->view('layout/main', $this->data);
	}

	public function loadAds_Publishers(){
		$params = $_POST;
		$result = $this->model_ads_publishers->loadAds_Publishers($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadAds_Publishers');
		
		echo json_encode($result);
	}
	
	public function loadAds_PublishersSelect(){
		$params = $_POST;
		$result = $this->model_ads_publishers->loadAds_PublishersSelect($params);		
		
		echo json_encode($result);
	}
	
	public function saveAds_Publishers(){
		$params = $_POST;
		
		if(sizeof($params)){
			$result = $this->model_ads_publishers->saveAds_Publishers($params);
			
			if($result['success'])
				file_get_contents(URL_CAMPAIGN_REDIS);
			
			echo json_encode($result);
		}
	}
	
	public function deleteAds_Publishers(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_ads_publishers->deleteAds_Publishers($params);
			echo json_encode($result);
		}
	}
	
	public function getAds_PublishersData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_ads_publishers->getAds_PublishersData($params);
			echo json_encode($result);
		}
	}
}
?>
