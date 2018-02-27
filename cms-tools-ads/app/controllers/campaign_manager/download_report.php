<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class download_report extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_download_report');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_ads_publishers');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/report/view_download_report";
		$this->load->view('layout/main', $this->data);
	}

	public function loaddownload_report(){
		$params = $_POST;
		
		$result = $this->model_download_report->loaddownload_report($params);		
		
		if($params['selectdate']==2)
		{
			$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loaddownload_report');
		}
		else
			$result['pagination'] = '';
		
		echo json_encode($result);
	}

	public function generate(){
		$params = $_POST;
			
		$result = $this->model_download_report->generate($params);
		
		header('Content-Type: application/excel'); 
		header('Content-Disposition: attachment; filename="Download-Report-'.date('t').'.csv"');
		
		$fp = fopen('php://output', 'w');
 
		for($i=0;$i<count($result); $i++) {
			$val = explode(",", $result[$i]);
			fputcsv($fp, $val);
		}
		fclose($fp);
	}

	
}
?>
