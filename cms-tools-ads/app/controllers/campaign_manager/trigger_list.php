<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class trigger_list extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/triggerlist/model_trigger_list'); 
		$this->load->model('campaign-manager/parameter/model_country'); 
                $this->load->model('campaign-manager/master/model_operators');
                $this->load->model('campaign-manager/master/model_language');
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
                $this->data['language'] = $this->model_language->loadLanguageSelect();  
                $this->data['operator'] = $this->model_operators->loadOperatorsSelect();
                $this->data['shortcode'] = $this->model_shortcodes->loadShortcodesSelect(); 
                $this->data['keyword'] = $this->model_partners_services->loadPartners_ServicesSelect(); 
               //  print_r($this->data['keyword']);die('sss');
		$this->data['pageBreadCumb'] = "";
               
		$this->data['pageTemplate']	= "campaign-manager/triggerlist/view_trigger_list";
		$this->load->view('layout/main', $this->data);
	}

	public function loadTrigger_List(){
		$params = $_POST;
		$result = $this->model_trigger_list->loadTrigger_List($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadTrigger_List');
		
		echo json_encode($result);
	}
	
	public function loadTrigger_ListSelect(){
		$params = $_POST;
		$result = $this->model_trigger_list->loadTrigger_ListSelect($params);		
				
		echo json_encode($result);
	}
	
	public function saveTrigger_List(){
		$params = $_POST;
		$pin_type = '';
		$gen_val_url = '';
       //print_r($params);die('sss');
        if(sizeof($params)){
			$result = $this->model_trigger_list->saveTrigger_List($params);
            if($result['success']){  
				#Get the data from REDIS       
                //print_r($params['message']);die('sss');

            	if($params['type']=='pin')
            		$pin_type = '_'.$params['pin_type'];

            	if($params['pin_type']=='remote')
            		$gen_val_url = '_'.$params['generate_url'].'_'.$params['validate_url'];

	            $val_redis =$params['message'].'_'.$params['language_id'].$pin_type.$gen_val_url;
	            $keyword = str_replace(' ', '', $params['keyword']);
	            $redisParams = array('set',(KEYS_TRIGGER.$params['country_id'].'_'.$this->getShortcodeFromId($params['shortcode_id']).'_'.$params['telco_id'].'_'.$keyword.'_'.$params['type']), $val_redis);  
	    	    $campaignRedis = $this->redisCommandNew('default', $redisParams);                 
			}
			echo json_encode($result); 
		}
	}
private function getShortcodeFromId($shortcode_id)
{
$short_code=explode('__',$shortcode_id);					
return $short_code[1];
}
	public function deleteTrigger_List(){
			if(sizeof($_POST)){
			$params = $_POST; 
			$pin_type = '';
			$gen_val_url = '';
			//print_r($params);die('ssss');
			$status=0;
			if(trim($params['status'])==0)
			{
				$status=1; 
			}
			$result = $this->model_trigger_list->deleteTrigger_List(array('id'=>$params['id'],'status'=>$status));
			if($result['success']){
				if(isset($params['status']) && trim($params['status'])==0)
				{
                            $keyword = str_replace(' ', '', $params['keyword']); 
							
                            $redisParams = array('del',(KEYS_TRIGGER.$params['country_id'].'_'.$this->getShortcodeFromId($params['shortcode_id']).'_'.$params['telco_id'].'_'.$keyword.'_'.$params['type_key']));  
                	        $campaignRedis = $this->redisCommand('default', $redisParams);
                               
                             }
			     elseif(isset($params['status']) && trim($params['status'])==1) {
					#Get the data from REDIS 


				            	if($params['type']=='pin')
				            		$pin_type = '_'.$params['pin_type'];

				            	if($params['pin_type']=='remote')
				            		$gen_val_url = '_'.$params['generate_url'].'_'.$params['validate_url'];


                                  $val_redis =$params['message'].'_'.$params['language_id'].$pin_type.$gen_val_url;
                                  $keyword = str_replace(' ', '', $params['keyword']);    
				 $redisParams = array('set',(KEYS_TRIGGER.$params['country_id'].'_'.$this->getShortcodeFromId($params['shortcode_id']).'_'.$params['telco_id'].'_'.$keyword.'_'.$params['type_key']), $val_redis);  
                	     $campaignRedis = $this->redisCommandNew('default', $redisParams);
                            
			}
		}

			echo json_encode($result);
		}
	}
	
    public function duplicateTrigger_List(){
			if(sizeof($_POST)){
			$params = $_POST; 
			$result = $this->model_trigger_list->duplicateTrigger_List(array('id'=>$params['id']));
			if($result['success']){
                     $keyword = str_replace(' ', '', $params['keyword']);    
			         $redisParams = array('del',(KEYS_TRIGGER.$params['country_id'].'_'.$this->getShortcodeFromId($params['shortcode_id']).'_'.$params['telco_id'].'_'.$keyword.'_'.$params['type_key'])); 
                                 
                	         $campaignRedis = $this->redisCommand('default', $redisParams);
             } 
            echo json_encode($result);
		}
	}
	
        
	public function getTrigger_ListData(){ 
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_trigger_list->getTrigger_ListData($params);
			echo json_encode($result);
		}
	}
        
         public function loadOperator(){
        if(sizeof($_POST)){
         $params = $_POST;
         //$result = $this->model_trigger_list->loadOperator($params);
         $result = $this->model_filterlist->loadOperator($params);
   //print_r(json_encode($result));die('lll');
        $html = '<select name="telco_id" id="telco_id" class="span6 m-wrap"> 
           
            <option value="">- Choose Operator -</option>
             <option value="0">ALL</option>  
"';
       for($i=0;$i<count($result)-1;$i++)
   {
    $html .= '<option value='.$result[$i]['id'].'>'.$result[$i]['name'].'</option>'; 
       
   }
        $html .= "</select>";
   echo json_encode($html);
  }
 }
 
    public function loadKeyword(){
        if(sizeof($_POST)){
         $params = $_POST;
         $result = $this->model_trigger_list->loadKeyword($params);
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
