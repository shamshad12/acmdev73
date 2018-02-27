<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class reporting_acquisition extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_reporting_acquisition');
		
	}
	
	public function index() {
		
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_ads_publishers');
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		#Get Data Operator
		$this->data['operators'] = $this->model_operators->loadOperatorsSelect();
		
		#Get Data Partner
		$this->data['partners'] = $this->model_partners->loadPartnersSelect();
		
		#Get Data Ads Publisher
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/report/view_reporting_acquisition";
		$this->load->view('layout/main', $this->data);
	}

	public function loadReporting_Acquisition(){
		$params = $_POST;
		
		$result = $this->model_reporting_acquisition->loadReporting_Acquisition($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadReporting_Acquisition');
		
		echo json_encode($result);
	}

	public function generate(){
		$params = $_POST;
			
		$result = $this->model_reporting_acquisition->generate($params);
		
		header('Content-Type: application/excel'); 
		header('Content-Disposition: attachment; filename="Reporting-Acquisition-'.date('t').'.csv"');
		
		$fp = fopen('php://output', 'w');
 
		for($i=0;$i<count($result); $i++) {
			$val = explode(",", $result[$i]);
			fputcsv($fp, $val);
		}
		fclose($fp);
	}
 
}
?>
