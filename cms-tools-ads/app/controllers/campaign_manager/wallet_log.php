<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class wallet_log extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('zing-wallet/wallet-info/model_wallet_log');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'backend/login', 'location', 301);
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "zing-wallet/wallet-info/view_wallet_log";
		$this->load->view('layout/main', $this->data);
	}

	public function loadWallet_Log(){
		$params = $_POST;
		
		$result = $this->model_wallet_log->loadWallet_Log($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadWallet_Log');
		
		echo json_encode($result);
	}
	
	public function getDetail(){
		$params = $_POST;
		
		$result = $this->model_wallet_log->getDetail($params);
		
		echo json_encode($result);
	}
}
?>
