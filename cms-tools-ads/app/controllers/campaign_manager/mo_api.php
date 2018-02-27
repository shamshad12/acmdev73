<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class mo_api extends Admin_Controller {

	var $base_url;
		
	public function __construct() { 
		parent::__construct();
		
		$this->load->model('campaign-manager/campaigns/model_mo_api'); 
		$this->load->model('campaign-manager/parameter/model_country'); 
                $this->load->model('campaign-manager/master/model_operators');
                $this->load->model('campaign-manager/master/model_shortcodes'); 
                $this->load->model('campaign-manager/master/model_partners_services');
              	$this->load->model('campaign-manager/filterlist/model_filterlist');
                
		 
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['country'] = $this->model_country->loadCountrySelect();  
                $this->data['operator'] = $this->model_operators->loadOperatorsSelect();
                $this->data['shortcode'] = $this->model_shortcodes->loadShortcodesSelect(); 
                $this->data['keyword'] = $this->model_partners_services->loadPartners_ServicesSelect(); 
               //  print_r($this->data['keyword']);die('sss'); 
		$this->data['pageBreadCumb'] = "";
               
		$this->data['pageTemplate']	= "campaign-manager/campaigns/view_mo_api";
		/*echo "<pre>";
		print_r($this->data);
		die('a1');*/
		$this->load->view('layout/main', $this->data);
	}

	public function loadMo_Api(){
		$params = $_POST;
		$result = $this->model_mo_api->loadMo_Api($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadMo_Api');
		
		echo json_encode($result);
	}
	
	public function loadMo_ApiSelect(){
		$params = $_POST;
		$result = $this->model_mo_api->loadMo_ApiSelect($params);		
				
		echo json_encode($result);
	}
	
	public function savemo_api(){
		$params = $_POST;
		$pin_type = '';
		$gen_val_url = '';
       	// print_r($params);die('sss');
        if(sizeof($params)){
			$result = $this->model_mo_api->savemo_api($params);
            if($result['success']){  
            	$country = explode('__', $params['country_id']);
            	$shortcode = explode('__', $params['shortcode_id']);
				#Set the data in REDIS       
	            $redisParams = array('set',(KEYS_MO_API.strtolower($country[1]).'_'.$shortcode[1].'_'.strtolower($params['keyword']).'_'.strtolower($params['telco_id'])), $params['ccode']);  
	    	    $campaignRedis = $this->redisCommandNew('default', $redisParams);   
	    	    // echo KEYS_MO_API.$country[1].'_'.$shortcode[1].'_'.$params['keyword'].'_'.$params['telco_id'];die('a1');              
			}
			echo json_encode($result); 
		}
	}

	private function getShortcodeFromId($shortcode_id)
	{
		$short_code=explode('__',$shortcode_id);					
		return $short_code[1];
	}

	public function deletemo_api(){
		if(sizeof($_POST)){
			$params = $_POST;
			// print_r($params);die('ssss');
			if(trim($params['status'])==0)
				$status=1;
			else
				$status=0;
			$result = $this->model_mo_api->deletemo_api(array('id'=>$params['id'],'status'=>$status));
			if($result['success']){ 
				if($params['status']==0)
				{
					$redisParams = array('del',KEYS_MO_API.strtolower($params['country_code']).'_'.$params['shortcode_id'].'_'.strtolower($params['keyword']).'_'.strtolower($params['operator_name']));  
		    	    $campaignRedis = $this->redisCommandNew('default', $redisParams);
				}
				else 
				{
					$redisParams = array('set',(KEYS_MO_API.strtolower($params['country_code']).'_'.$params['shortcode_id'].'_'.strtolower($params['keyword']).'_'.strtolower($params['operator_name'])), $params['ccode']);  
		    	    $campaignRedis = $this->redisCommandNew('default', $redisParams);
				}
			}
			echo json_encode($result);
		}
	}
	
    public function duplicatemo_api(){
			if(sizeof($_POST)){
			$params = $_POST; 
			$result = $this->model_mo_api->duplicatemo_api(array('id'=>$params['id']));
			if($result['success']){
            	$redisParams = array('del',KEYS_MO_API.strtolower($params['country_code']).'_'.$params['shortcode_id'].'_'.strtolower($params['keyword']).'_'.strtolower($params['operator_name']));  
		    	$campaignRedis = $this->redisCommandNew('default', $redisParams);
            } 
            echo json_encode($result);
		}
	}
	
        
	public function getmo_apiData(){ 
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_mo_api->getmo_apiData($params);
			echo json_encode($result);
		}
	}
        
    public function loadOperator(){
        if(sizeof($_POST)){
	         $params = $_POST;
	         //$result = $this->model_mo_api->loadOperator($params);
	         $result = $this->model_mo_api->loadOperator($params);
	   		//print_r(json_encode($result));die('lll');
	        $html = '<select name="telco_id" id="telco_id" class="span6 m-wrap"> 
	           
	            <option value="">- Choose Operator -</option>
				"';
	       	for($i=0;$i<count($result)-1;$i++)
	   		{
	    		$html .= '<option value='.strtolower($result[$i]['name']).'>'.$result[$i]['name'].'</option>'; 
	       
	   		}
	        $html .= "</select>";
	   		echo json_encode($html);
  		}
 	}
 
    public function loadKeyword(){
        if(sizeof($_POST)){
	         $params = $_POST;
	         $result = $this->model_mo_api->loadKeyword($params);
	   		//print_r(json_encode($result));die('lll');
	        $html = '<select name="keyword" id="keyword" class="span6 m-wrap"> 
	           
	            <option value="">Choose Keyword -</option>
	            
			"';
	       for($i=0;$i<count($result)-1;$i++)
		   {
		    	$html .= '<option value="'.$result[$i]['name'].'">'.$result[$i]['name'].'</option>'; 
		       
		   }
	       $html .= "</select>";
	   		echo json_encode($html); 
  		}
 	}

 
}
?>
