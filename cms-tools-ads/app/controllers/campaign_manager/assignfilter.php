<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  
                      
class assignfilter extends Admin_Controller { 

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		$this->load->model('campaign-manager/filterlist/model_assignfilter');
		$this->load->model('campaign-manager/filterlist/model_filtertype');
		
		$this->load->model('campaign-manager/parameter/model_country');
	}
	
	public function index() {

		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['country'] = $this->model_country->loadCountrySelect();
		
		$this->data['list_name'] = $this->model_filtertype->loadFiltertypeSelect(); 
      
		 //print_r($this->data['id_listtype']);exit(); 
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/filterlist/view_assignfilter"; 
		$this->load->view('layout/main', $this->data);
	}

	public function loadAssignfilter(){
           /// echo 'sss';return false;
		$params = $_POST;  
		
		$result = $this->model_assignfilter->loadAssignfilter($params);	 	
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadAssignfilter');
		
		echo json_encode($result);
	}
	
	public function saveAssignfilter(){
		$params = $_POST;
       // echo "<pre>";print_r($params);die('hj');     
		if(sizeof($params)){
			$result = $this->model_assignfilter->saveAssignfilter($params);
			if($result['success']){
   			 #Get the data from REDIS
                if(!empty($params['id_listtype']) && !empty($params['validdays'])){
                    $redisParams = array('exists', $params['id_country'].'_'.$params['id_listtype']);
                    $campaignRedis = $this->redisCommand('default', $redisParams);

                    if($campaignRedis==0)
                    {
                    	 $redisParams = array('set', $params['id_country'].'_'.$params['id_listtype'], $params['validdays']);
                    	$campaignRedis = $this->redisCommand('default', $redisParams);
                    }
                    else{
                         $redisParams = array('del', $params['id_country'].'_'.$params['id_listtype'], $params['validdays']);
                    	$campaignRedis = $this->redisCommand('default', $redisParams);
                        $redisParams = array('set', $params['id_country'].'_'.$params['id_listtype'], $params['validdays']);
                    	$campaignRedis = $this->redisCommand('default', $redisParams); 
                    }

                }
            }
			echo json_encode($result);
		}
	}
	
	public function deleteAssignfilter(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_assignfilter->deleteAssignfilter($params);
			echo json_encode($result);
		}
	}
	
	public function getAssignfilterData(){  
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_assignfilter->getAssignfilterData($params);
			echo json_encode($result);
		}
	}
}
?>
  