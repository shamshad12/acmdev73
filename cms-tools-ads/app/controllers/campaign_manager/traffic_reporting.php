<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class traffic_reporting extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_traffic_reporting');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_ads_publishers');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		#Get Data Operator
		$this->data['operators'] = $this->model_operators->loadOperatorsSelect();
		
		#Get Data Partner
		$this->data['partners'] = $this->model_partners->loadPartnersSelect();
		
		#Get Data Ads Publisher
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/report/view_traffic_reporting";
		$this->load->view('layout/main', $this->data);
	}

	public function loadTraffic_Reporting(){
		$params = $_POST;
		
		$result = $this->model_traffic_reporting->loadTraffic_Reporting($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadTraffic_Reporting');
		
		echo json_encode($result);
	}

	public function generate(){
		$params = $_POST;
			
		$result = $this->model_traffic_reporting->generate($params);
		
		header('Content-Type: application/excel'); 
		header('Content-Disposition: attachment; filename="Traffic-Reporting-'.date('t').'.csv"');
		
		$fp = fopen('php://output', 'w');
 
		for($i=0;$i<count($result); $i++) {
			$val = explode(",", $result[$i]);
			fputcsv($fp, $val);
		}
		fclose($fp);
	}

	public function getTraficDetail()
	{
		$params = $_POST;
		//$params = array('id'=>'201505201316501046');
		$result = $this->model_traffic_reporting->loadTraffic_detail($params);
		echo json_encode($result);
	}

	public function resendPubreq()
	{
		$params = $_POST;
		$result = $this->model_traffic_reporting->resendPubreq($params);
		echo json_encode($result);
	}

	
}
?>
