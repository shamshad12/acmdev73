<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class vendor_indo_reporting extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_vendor_indo_reporting');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/parameter/model_country');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_ads_publishers');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		#Get Data Operator
		$this->data['operators'] = $this->model_operators->loadOperatorsSelect();

		#Get Data Country
		$this->data['country'] = $this->model_country->loadCountrySelect();
		
		#Get Data Partner
		$this->data['partners'] = $this->model_partners->loadPartnersSelect();
		
		#Get Data Ads Publisher
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/report/view_vendor_indo_reporting";
		$this->load->view('layout/main', $this->data);
	}

	public function loadvendor_indo_reporting(){
		$params = $_POST;
		$result = $this->model_vendor_indo_reporting->loadvendor_indo_reporting($params);		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadvendor_indo_reporting');
		echo json_encode($result);
	}
}
?>
