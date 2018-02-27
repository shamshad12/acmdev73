<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class campaigns extends Admin_Controller {

	var $base_url;

	public function __construct() {
		parent::__construct();

		$this->load->model('partner_campaign_manager/campaigns/model_campaigns');
		$this->load->model('campaign-manager/parameter/model_country');
		$this->load->model('campaign-manager/campaigns/model_campaigns_media');
		$this->load->model('campaign-manager/campaigns/model_campaigns_categories');
		$this->load->model('campaign-manager/master/model_banners');
		$this->load->model('campaign-manager/master/model_prices');
		$this->load->model('campaign-manager/master/model_ads_publishers');
		$this->load->model('partner_campaign_manager/templates/model_templates');
		$this->load->model('campaign-manager/master/model_language');
	}

	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();

		if(!$this->data['accessView'])
		redirect($this->data['base_url_index'].'login', 'location', 301);

		$this->data['template_id'] = '';
		$this->data['media_id']='';
		$this->data['temp_country_id']='';
		if(isset($_GET['template_id']) && trim($_GET['template_id']) !='')
		{
			$this->data['template_id'] = $_GET['template_id'];
			$this->data['media_id']=$_GET['media_id'];
			$this->data['temp_country_id']=$_GET['country_id'];
		}
			
		#Load Country
		$this->data['country'] = $this->model_country->loadCountrySelect();
		#Load Campaign Media
		$this->data['campaign_media'] = $this->model_campaigns_media->loadCampaigns_MediaSelect();
		#Load Campaign Category
		$this->data['campaign_category'] = $this->model_campaigns_categories->loadCampaigns_CategoriesSelect();
		#Load Price
		$this->data['price'] = $this->model_prices->loadPricesSelect();
		#Load Ads Publishers
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();
		#Load Banners
		$this->data['banner'] = $this->model_banners->loadBannersSelect();
		#Load Templates
		$this->data['template'] = $this->model_templates->loadTemplatesSelect();
		#Load Language
		$this->data['language'] = $this->model_language->loadLanguageSelect();

		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "partner_campaign_manager/campaigns/view_campaigns";
		$this->load->view('layout/main', $this->data);
	}

	public function loadCampaigns(){
		$params = $_POST;
		$result = $this->model_campaigns->loadCampaigns($params);

		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadCampaigns');

		$dirAccess = $this->config->item('image_access');
		$result['host']			= $dirAccess['campaigns']['host'];

		echo json_encode($result);
	}

	public function loadCampaignsSelect(){
		$params = $_POST;
		$result = $this->model_campaigns->loadCampaignsSelect($params);

		echo json_encode($result);
	}

	public function getUrlData(){
		$params = $_POST;
		$result = $this->model_campaigns->getUrlData($params);

		echo json_encode($result);
	}

	public function getUrlDataWithValues(){
		$params = $_POST;
		$result = $this->model_campaigns->getUrlDataWithValues($params);

		echo json_encode($result);
	}

	public function saveCampaigns(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_campaigns->saveCampaigns($params);
				
			if($result['success'])
			file_get_contents(URL_CAMPAIGN_REDIS.$result['id']);
				
			echo json_encode($result);
		}
	}

	public function deleteCampaigns(){
		if(sizeof($_POST)){
			$params = $_POST;
				
				
				
			$result = $this->model_campaigns->checkeditCampaigns($params);
			if($result['rows'][0]['edit_type']==0)
			{
				file_get_contents(URL_CAMPAIGN_REDIS_DEL.$params['id']);
				$result = $this->model_campaigns->deleteCampaigns($params);
				 
			}
			elseif($result['rows'][0]['edit_type']==1 && $result['rows'][0]['edit_user']==$result['rows'][0]['login_user'])
			{
				file_get_contents(URL_CAMPAIGN_REDIS_DEL.$params['id']);
				$result = $this->model_campaigns->deleteCampaigns($params);
				 
			}
			else{
				$result['edit_message'] = "Some of the user already editing this Campaigns so you cannot edit or delete";
				$result['duplicateedit_data'] =  true;
			}
			 
				
			//print_r($result);die();
				
			echo json_encode($result);
		}
	}

	public function duplicateCampaigns(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_campaigns->duplicateCampaigns($params);
				
			if($result['success'])
			{
				file_get_contents(URL_CAMPAIGN_REDIS.$result['id']);
			}
				
			echo json_encode($result);
		}
	}

	public function getCampaignsData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_campaigns->checkeditCampaigns($params);
			//  print_r($result);
			if($result['rows'][0]['edit_type']==0 && empty($result['rows'][0]['edit_user']))
			{
				$result = $this->model_campaigns->getCampaignsData($params);
			}
			elseif($result['rows'][0]['edit_type']==1 && $result['rows'][0]['edit_user']==$result['rows'][0]['login_user'])
			{
				$result = $this->model_campaigns->getCampaignsData($params);
			}
			else{
				$result['edit_message'] = "Some of the user already editing this Campaigns so you cannot edit";
				$result['duplicateedit_data'] =  true;
			}
			 
			echo json_encode($result);
		}
	}

	public function Status(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_campaigns->changeStatus($params);
			echo json_encode($result);
		}
	}
	 
}
?>
