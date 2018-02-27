<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class test extends Admin_Controller {

	var $base_url;

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$redisParams = array('KEYS', "*");
		$data = $this->redisCommand('default', $redisParams);
		echo "<pre>";
		print_r($data);
	}

}