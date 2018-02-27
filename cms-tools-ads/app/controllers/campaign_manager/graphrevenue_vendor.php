<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class graphrevenue_vendor extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_graphrevenue_vendor');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_ads_publishers');
		$this->load->model('campaign-manager/parameter/model_country');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		#Get Data Partner
		$this->data['partners'] = $this->model_partners->loadPartnersSelect();
		
		#Get Data Ads Publisher
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/report/view_graphrevenue_vendor";
		$this->load->view('layout/main', $this->data);
	}

	public function loadgraphrevenue_vendor(){
		$params = $_POST;
		
		$result = $this->model_graphrevenue_vendor->loadgraphrevenue_vendor($params);
		
		echo json_encode($result);
	}
}
?>
