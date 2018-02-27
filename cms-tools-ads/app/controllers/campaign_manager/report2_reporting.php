<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class report2_reporting extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_report2_reporting');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/parameter/model_country');
		$this->load->model('campaign-manager/campaigns/model_campaigns_media');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_ads_publishers');
		$this->load->model('campaign-manager/master/model_shortcodes');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		#Get Data Operator
		$this->data['operators'] = $this->model_operators->loadOperatorsSelect();

		#Load Campaign Media
		$this->data['campaign_media'] = $this->model_campaigns_media->loadCampaigns_MediaSelect();
		
		#Get Data Country
		$this->data['country'] = $this->model_country->loadCountrySelect(array());

		#Get Data Shortcode
		$this->data['shortcodes'] = $this->model_shortcodes->loadShortcodesSelect();
		
		#Get Data Partner
		$this->data['partners'] = $this->model_partners->loadPartnersSelect();
		
		#Get Data Ads Publisher
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/report/view_report2_reporting";
		$this->load->view('layout/main', $this->data);
	}

	public function loadreport2_reporting(){
		$params = $_POST;
		
		//$result = $this->model_report2_reporting->loadreport2_reporting($params);		
	if($params['days']==7 || $params['days']==30)
		{
			$result = $this->model_report2_reporting->loadreport2_reportinglastdays($params);
			$result['pagination'] = '';
		}
		else
		{
			$result = $this->model_report2_reporting->loadreport2_reporting($params);		
			$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadreport2_reporting',$params['days']);
		}
	
			
		echo json_encode($result);
	}

	public function generate(){
		$params = $_POST;
			
		$result = $this->model_report2_reporting->generate($params);
		
		header('Content-Type: application/excel'); 
		header('Content-Disposition: attachment; filename="Report2-Reporting-'.date('t').'.csv"');
		
		$fp = fopen('php://output', 'w');
 
		for($i=0;$i<count($result); $i++) {
			$val = explode(",", $result[$i]);
			fputcsv($fp, $val);
		}
		fclose($fp);
	}

}
?>
