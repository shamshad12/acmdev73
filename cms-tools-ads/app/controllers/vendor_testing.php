<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class vendor_testing extends Public_Controller {

	public function __construct() {
		parent::__construct();
		
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$result['success'] = "true";
		echo json_encode($result);
	}
}