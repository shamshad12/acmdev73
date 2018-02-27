<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class all_records_reporting extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_all_records_reporting');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_ads_publishers');
		$this->load->model('campaign-manager/campaigns/model_campaigns');
		$this->load->model('campaign-manager/master/model_shortcodes');
		
		//$this->output->enable_profiler(TRUE);
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		#Get Data Campaign
		$this->data['campaigns'] = $this->model_campaigns->loadCampaignsSelect(array());
		
		#Get Data Shortcode
		$this->data['shortcodes'] = $this->model_shortcodes->loadShortcodesSelect();
		
		#Get Data Operator
		$this->data['operators'] = $this->model_operators->loadOperatorsSelect();
		
		#Get Data Partner
		$this->data['partners'] = $this->model_partners->loadPartnersSelect();
		
		#Get Data Ads Publisher
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/report/view_all_records_reporting";
		$this->load->view('layout/main', $this->data);
	}

	public function loadAllRecords_Reporting(){
		$params = $_POST;
		
		$result = $this->model_all_records_reporting->loadAllRecords_Reporting($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadAllRecords_Reporting');
		
		echo json_encode($result);
	}
	
	public function generate(){
		$params = $_POST;
			
		$result = $this->model_all_records_reporting->generate($params);
		
		header('Content-Type: application/excel'); 
		header('Content-Disposition: attachment; filename="All-Records-Reporting-'.date('t').'.csv"');
		
		$fp = fopen('php://output', 'w');
 
		for($i=0;$i<count($result); $i++) {
			$val = explode(",", $result[$i]);
			fputcsv($fp, $val);
		}
		fclose($fp);
	}
 
}
?>
