<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class operator_overview extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/report/model_operator_overview');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_shortcodes');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		#Get Data Partner
		$this->data['partners'] = $this->model_partners->loadPartnersSelect();

		#Get Data Shortcode
		$this->data['shortcodes'] = $this->model_shortcodes->loadShortcodesSelect();
		
		$this->data['pageTemplate']	= "campaign-manager/report/view_operator_overview";
		$this->load->view('layout/main', $this->data);
	}

	public function loadoperator_overview(){
		$params = $_POST;
		
		$result = $this->model_operator_overview->loadoperator_overview($params);		

		echo json_encode($result);
	}

	public function generate(){
		$params = $_POST;
			
		$result = $this->model_operator_overview->generate($params);
		
		header('Content-Type: application/excel'); 
		header('Content-Disposition: attachment; filename="Traffic-Reporting-'.date('t').'.csv"');
		
		$fp = fopen('php://output', 'w');
 
		for($i=0;$i<count($result); $i++) {
			$val = explode(",", $result[$i]);
			fputcsv($fp, $val);
		}
		fclose($fp);
	}
 
}
?>
