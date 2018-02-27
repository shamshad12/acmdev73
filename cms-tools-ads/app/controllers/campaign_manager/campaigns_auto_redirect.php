<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class campaigns_auto_redirect extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/campaigns/model_campaigns_auto_redirect');
		$this->load->model('campaign-manager/campaigns/model_campaigns');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_ads_publishers');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		#Load Country
		$this->data['operator'] = $this->model_operators->loadOperatorsSelect();
		#Load Ads Publishers
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();	
		#Load Campaign
		$this->data['campaign'] = $this->model_campaigns->loadCampaignsSelect(array());
		
		$this->data['time'] = $this->setTime();
		$this->data['day'] = $this->setDayList();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/campaigns/view_campaigns_auto_redirect";
		$this->load->view('layout/main', $this->data);
	}

	public function loadCampaigns_Auto_Redirect(){
		$params = $_POST;
		$result = $this->model_campaigns_auto_redirect->loadCampaigns_Auto_Redirect($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadCampaigns_Auto_Redirect');
		
		$dirAccess = $this->config->item('image_access');
		$result['host']			= $dirAccess['campaigns']['host'];
		
		echo json_encode($result);
	}
	
	public function saveCampaigns_Auto_Redirect(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_campaigns_auto_redirect->saveCampaigns_Auto_Redirect($params);
			echo json_encode($result);
		}
	}
	
	public function deleteCampaigns_Auto_Redirect(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_campaigns_auto_redirect->deleteCampaigns_Auto_Redirect($params);
			echo json_encode($result);
		}
	}
	
	public function getCampaigns_Auto_RedirectData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_campaigns_auto_redirect->getCampaigns_Auto_RedirectData($params);
			echo json_encode($result);
		}
	}
}
?>
