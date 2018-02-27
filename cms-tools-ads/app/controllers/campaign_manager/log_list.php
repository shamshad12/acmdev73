<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Log_list extends Admin_Controller {

	var $base_url;
		
	public function __construct() {

		parent::__construct();
		$this->load->model('campaign-manager/log/log_model');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/log/view_log_list";
		$this->load->view('layout/main', $this->data);
	}

	public function loadLogList(){
		$params = $_POST;
		$result = $this->log_model->loadlogList($params);
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'load_log_list');
		echo json_encode($result);
	}

	public function generate(){
		$params = $_POST;
			
		$result = $this->log_model->generate($params);
		
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
