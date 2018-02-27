<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class pixelfire_report extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_pixelfire_report');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_ads_publishers');
		
		#Get Data Operator
		$this->data['operators'] = $this->model_operators->loadOperatorsSelect();
		
		#Get Data Partner
		$this->data['partners'] = $this->model_partners->loadPartnersSelect();
		
		#Get Data Ads Publisher
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/report/view_pixelfire_report";
		$this->load->view('layout/main', $this->data);
	}

	public function loadpixelfire_report(){
		$params = $_POST;
		
		$result = $this->model_pixelfire_report->loadpixelfire_report($params);		
		
		if($params['selectdate']==2)
		{
			$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadpixelfire_report');
		}
		else
			$result['pagination'] = '';
		
		echo json_encode($result);
	}

	public function generate(){
		$params = $_POST;
			
		$result = $this->model_pixelfire_report->generate($params);
		
		header('Content-Type: application/excel'); 
		header('Content-Disposition: attachment; filename="Pixelfire_Report-'.date('t').'.csv"');
		
		$fp = fopen('php://output', 'w');
 
		for($i=0;$i<count($result); $i++) {
			$val = explode(",", $result[$i]);
			fputcsv($fp, $val);
		}
		fclose($fp);
	}

	
}
?>
