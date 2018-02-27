<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class xsim_api extends MY_Controller {
		
	public function __construct() {
		parent::__construct();
		//$this->load->model('xsim_mapping/model_clientmapping');
                //$this->load->model('campaign-manager/parameter/model_country');
                $this->load->model('xsim_mapping/model_xsim_map');
	}
	
	public function index($client_id='') {
           
            
            $result=array();
            if(!empty($client_id)){
                 $result['status']=1;
                $data=$this->model_xsim_map->getCampaignUrl($client_id);
                $result=$data;
            }
            else{
                
                $result['status']=0;
                $result['type']='Client id missing';
            }
		echo json_encode($result);
	}    

	
	
	
}
?>
