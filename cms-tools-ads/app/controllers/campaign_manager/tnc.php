<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class tnc extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_templates');
		$this->load->model('campaign-manager/campaigns/model_campaigns_media');
		$this->load->model('campaign-manager/parameter/model_country');
		$this->load->model('campaign-manager/master/model_banners');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_tnc";
		$this->load->view('layout/main', $this->data);
		// die('jui');
	}

}

?>
