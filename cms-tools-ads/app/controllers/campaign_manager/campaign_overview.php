<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class campaign_overview extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_campaign_overview');
		$this->load->model('campaign-manager/parameter/model_country');
		$this->load->model('campaign-manager/triggerlist/model_trigger_list');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_ads_publishers');
		$this->load->model('campaign-manager/master/model_shortcodes');
		
		//$this->output->enable_profiler(TRUE);
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		#Get Data Campaign
		$this->data['campaigns'] = $this->model_campaign_overview->loadcampaign_select(array());

		#Get Data Country
		$this->data['country'] = $this->model_country->loadCountrySelect(array());

		#Get Data Keywords
		$this->data['keywords'] = $this->model_campaign_overview->loadkeyword_select(array());

		#Get Data Shortcode
		$this->data['shortcodes'] = $this->model_shortcodes->loadShortcodesSelect();
		
		#Get Data Operator
		$this->data['operators'] = $this->model_operators->loadOperatorsSelect();
		
		#Get Data Partner
		$this->data['partners'] = $this->model_partners->loadPartnersSelect();
		
		#Get Data Ads Publisher
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/report/view_campaign_overview";
		$this->load->view('layout/main', $this->data);
	}

	public function loadcampaign_overview(){
		$params = $_POST;
		
		$result = $this->model_campaign_overview->loadcampaign_overview($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadcampaign_overview');
		
		echo json_encode($result);
	}
	
	public function getcampaign_hourlydetail(){
		$params = $_POST;
		
		$result = $this->model_campaign_overview->getcampaign_hourlydetail($params);		
		
		echo json_encode($result);
	}
	
	public function generate(){
		$params = $_POST;
			
		$result = $this->model_campaign_overview->generate($params);
		
		header('Content-Type: application/excel'); 
		header('Content-Disposition: attachment; filename="Campaign-overview-'.date('t').'.csv"');
		
		$fp = fopen('php://output', 'w');
 
		for($i=0;$i<count($result); $i++) {
			$val = explode(",", $result[$i]);
			fputcsv($fp, $val);
		}
		fclose($fp);
	}
 
}
?>
