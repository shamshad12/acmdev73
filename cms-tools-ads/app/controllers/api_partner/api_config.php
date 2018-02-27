<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class api_config extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		$this->load->model('campaign-manager/master/model_shortcodes');
                $this->load->model('api_partner/model_config');
	}
	
	public function index() {
          
		#Bring Menu for Sidebar
		$this->loadMenu();
		if(!$this->data['accessView'])
                    redirect($this->data['base_url_index'].'login', 'location', 301);
		$this->data['shortcode'] = $this->model_shortcodes->loadShortcodesSelect();
		$this->data['partner'] = $this->model_config->loadPartner();
                $this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "api_partner/view_partner_config";
		$this->load->view('layout/main', $this->data);
	}

	public function loadPartner_config(){
		$params = $_POST;
               
		$result = $this->model_config->loadPartner_config($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadPartner_Config');
		
		echo json_encode($result);
	}
	
	
	
	
	
	public function savePartner_config(){
		$params = $_POST;
		if(sizeof($params)){ 
			$result = $this->model_config->savePartner_config($params);
                        if($result['success'] && $params['status']=='1')
                        {
                            $val_redis=$params['product_id'].'__'.$params['cp_id'].'__'.$params['cc'];
                            $redisParams = array('set',('PARTNER_API_'.$params['partner_code'].'_'.$params['shortcode'].'_'.$params['keyword']), $val_redis);  
                            $campaignRedis = $this->redisCommand('default', $redisParams);
                        }else{
                            $redisParams = array('del',('PARTNER_API_'.$params['partner_code'].'_'.$params['shortcode'].'_'.$params['keyword']));  
                            $campaignRedis = $this->redisCommand('default', $redisParams);
                        }
			echo json_encode($result);
		}
	}
	
	public function deletePartner_config(){
            
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_config->deletePartner_config($params);
                        if($result['success'])
                        {  
                            $redisParams = array('del',('PARTNER_API_'.$params['partner_code'].'_'.$params['shortcode'].'_'.$params['keyword']));  
                            $campaignRedis = $this->redisCommand('default', $redisParams);
                        }
			echo json_encode($result);
		}
	}
	
	public function getPartner_configData(){
		if(sizeof($_POST)){
			$params = $_POST;
                       // print_r($params);die();
			$result = $this->model_config->getPartner_configData($params);
			echo json_encode($result);
		}
	}
	
	public function getserviceVal(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_config->getserviceVal($params);
			echo json_encode($result);
		}
	}
  
        public function loadShortcode(){
        if(sizeof($_POST)){
         $params = $_POST;
         $result = $this->model_config->loadShortcode($params);
         //  print_r($result);die();   
        $html = '<option value="">- Choose Shortcode -</option>"';
       for($i=0;$i<count($result)-1;$i++)
   {
    $html .= '<option value='.$result[$i]['id'].'>'.$result[$i]['name'].'</option>'; 
       
   }
     
   echo json_encode($html);
  } 
 }
 
        public function loadOperator(){
        if(sizeof($_POST)){
         $params = $_POST;
         $result = $this->model_filterlist->loadOperator($params);
         $html = ' 
           
            <option value="">- Choose Operator -</option>"';
       for($i=0;$i<count($result)-1;$i++)
   {
    $html .= '<option value='.$result[$i]['id'].'>'.$result[$i]['name'].'</option>'; 
       
   }
   echo json_encode($html);
  } 
 }
 
 
}
?>
