<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class reporting_profit_cost extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_reporting_profit_cost');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_ads_publishers');
		$this->load->model('campaign-manager/parameter/model_country');
		$this->load->model('campaign-manager/master/model_shortcodes');  //SAM
		$this->load->model('campaign-manager/report/model_campaign_overview');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		#Get Data Country
		$this->data['country'] = $this->model_country->loadCountrySelect(array());
		
		#Get Data Operator
		$this->data['operators'] = $this->model_operators->loadOperatorsSelect();
		
		#Get Data Partner
		$this->data['partners'] = $this->model_partners->loadPartnersSelect();
		
		#Get Data Ads Publisher
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();
		
		#Get Data Shortcode
		$this->data['shortcode'] = $this->model_shortcodes->loadShortcodesSelect();		//SAM
		#Get Data Keyword
		$this->data['keyword'] = $this->model_campaign_overview->loadkeyword_select();		//SAM

		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/report/view_reporting_profit_cost";
		$this->load->view('layout/main', $this->data);
	}

	public function loadReporting_Profit_Cost(){
		$params = $_POST;
		
		$result = $this->model_reporting_profit_cost->loadReporting_Profit_Cost($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadReporting_Profit_Cost');
		
		echo json_encode($result);
	}

	public function generate(){
		$params = $_POST;
			
		$result = $this->model_reporting_profit_cost->generate($params);
		
		header('Content-Type: application/excel'); 
		header('Content-Disposition: attachment; filename="Reporting-Profit-Cost-'.date('t').'.csv"');
		
		$fp = fopen('php://output', 'w');
 
		for($i=0;$i<count($result); $i++) {
			$val = explode(",", $result[$i]);
			fputcsv($fp, $val);
		}
		fclose($fp);
	}
 
}
?>
