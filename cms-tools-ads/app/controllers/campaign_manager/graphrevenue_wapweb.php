<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class graphrevenue_wapweb extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_graphrevenue_wapweb');
		$this->load->model('campaign-manager/campaigns/model_campaigns_media');
		$this->load->model('campaign-manager/parameter/model_country');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		#Load Campaign Media
		$this->data['campaign_media'] = $this->model_campaigns_media->loadCampaigns_MediaSelect();

		#Load Country
		$this->data['country'] = $this->model_country->loadCountrySelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/report/view_graphrevenue_wapweb";
		$this->load->view('layout/main', $this->data);
	}

	public function loadgraphrevenue_wapweb(){
		$params = $_POST;
		
		$result = $this->model_graphrevenue_wapweb->loadgraphrevenue_wapweb($params);
		
		echo json_encode($result);
	}
}
?>
