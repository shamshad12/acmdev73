<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class report_by_campaign extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_report_by_campaign');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_ads_publishers');
		$this->load->model('campaign-manager/parameter/model_country');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		#Get Data Operator
		$this->data['operators'] = $this->model_operators->loadOperatorsSelect();
		
		#Get Data Partner
		$this->data['partners'] = $this->model_partners->loadPartnersSelect();
		$this->data['country'] = $this->model_country->loadCountrySelect();
		#Get Data Ads Publisher
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/report/view_report_by_campaign";
		$this->load->view('layout/main', $this->data);
	}

	public function loadReport_By_Campaign(){
		$params = $_POST;
		
		$result = $this->model_report_by_campaign->loadReport_By_Campaign($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadReport_By_Campaign');
		
		echo json_encode($result);
	}

	public function generate(){
		$params = $_POST;
			
		$result = $this->model_report_by_campaign->generate($params);
		
		header('Content-Type: application/excel'); 
		header('Content-Disposition: attachment; filename="Revenue-By-Campaign-'.date('t').'.csv"');
		
		$fp = fopen('php://output', 'w');
 
		for($i=0;$i<count($result); $i++) {
			$val = explode(",", $result[$i]);
			fputcsv($fp, $val);
		}
		fclose($fp);
	}
 
}
?>
