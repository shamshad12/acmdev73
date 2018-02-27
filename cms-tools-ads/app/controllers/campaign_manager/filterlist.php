<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
# $redis=new redis();
# $redis->connect('127.0.0.1',6379);

class filterlist extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/filterlist/model_filterlist');
		$this->load->model('campaign-manager/parameter/model_country'); 
                $this->load->model('campaign-manager/master/model_operators');
	}
	
	public function index() {
//
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		$this->data['country'] = $this->model_country->loadCountrySelect();
                $this->data['operator'] = $this->model_operators->loadOperatorsSelect();
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/filterlist/view_filterlist";
		$this->load->view('layout/main', $this->data);
	}

	public function loadFilterlist(){
		$params = $_POST;  
		$result = $this->model_filterlist->loadFilterlist($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadFilterlist');
		
		echo json_encode($result);
	}
	
	public function saveFilterlist($bulkUpload='')
	{
			if($bulkUpload)
			{
				for($i=0; $i<count($bulkUpload); $i++)
				{
					$params['id_country'] = $bulkUpload[$i]['country_id'];
					$params['filter_type'] = $bulkUpload[$i]['filter_type'];
					$params['msisdn'] = $bulkUpload[$i]['msisdn'];
					$params['id_telco'] = $bulkUpload[$i]['operator_id'];
					$params['status'] = $bulkUpload[$i]['status'];
					$result = $this->model_filterlist->saveFilterlist($params);
					if($result['success'])
					{
						#Get the data from REDIS
		                             if(!empty($params['filter_type']) && $params['filter_type']=='Blacklist'){ 
		        	                $redisParams = array('sadd',($params['id_country'].'_'.$params['filter_type']), ($params['msisdn']));  
		                	        $campaignRedis = $this->redisCommand('default', $redisParams);
		                             }
		                             elseif(!empty($params['filter_type']) && $params['filter_type']=='Whitelist'){
		                                $redisParams = array('sadd',($params['id_country'].'_'.$params['filter_type']), ($params['msisdn']));  
		                	        $campaignRedis = $this->redisCommand('default', $redisParams);   
		                             }
                                         }


                                 }
                                redirect('/campaign_manager/filterlist', 302);
                          }
			else
			{
				$params = $_POST;
				if(sizeof($params)){
					$result = $this->model_filterlist->saveFilterlist($params);
					if($result['success']){
                                            
						#Get the data from REDIS
		                             if(!empty($params['filter_type']) && $params['filter_type']=='Blacklist'){ 
		        	                $redisParams = array('sadd',($params['id_country'].'_'.$params['filter_type']), ($result['msisdn']));  
		                	        $campaignRedis = $this->redisCommand('default', $redisParams);
		                             }
		                             elseif(!empty($params['filter_type']) && $params['filter_type']=='Whitelist'){
		                                $redisParams = array('sadd',($params['id_country'].'_'.$params['filter_type']), ($result['msisdn']));  
		                	        $campaignRedis = $this->redisCommand('default', $redisParams);   
		                             }
					}

					echo json_encode($result);
				}
			}
	}

    public function deleteFilterlistNew()
    {
    	if(sizeof($_POST))
    	{
			$params = $_POST;
			$result = $this->model_filterlist->deleteFilterlistNew($params['id']);
			if($result['success'])
			{

				 $redisParams = array('srem',($params['id_country'].'_'.$params['filter_type']), ($params['msisdn']));  
              $campaignRedis = $this->redisCommand('default', $redisParams);
			}
			
		}
		echo json_encode($result);

    }    
	public function deleteFilterlist(){
		if(sizeof($_POST)){
			$params = $_POST;
			$status=0;
			if(trim($params['status'])==0)
			{
				$status=1;
			}
			$result = $this->model_filterlist->deleteFilterlist(array('id'=>$params['id'],'status'=>$status));
			if($result['success']){
				if(isset($params['status']) && trim($params['status'])==0)
				{
                                 $redisParams = array('srem',($params['id_country'].'_'.$params['filter_type']), ($params['msisdn']));  
                	        $campaignRedis = $this->redisCommand('default', $redisParams);
                              
                             }
			     elseif(isset($params['status']) && trim($params['status'])==1) {
					#Get the data from REDIS 
				
        	               
				$redisParams = array('sadd',($params['id_country'].'_'.$params['filter_type']), ($params['msisdn']));  
                	        $campaignRedis = $this->redisCommand('default', $redisParams);
                            
			}
		}

			echo json_encode($result);
		}
	}
	
	public function getFilterlistData(){ 
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_filterlist->getFilterlistData($params);
			echo json_encode($result);
		}
	}
        
        
        public function loadOperator(){
        if(sizeof($_POST)){
         $params = $_POST;
         $result = $this->model_filterlist->loadOperator($params);
   //print_r(json_encode($result));die('lll');
        $html = '<select name="id_telco" id="id_telco" class="span6 m-wrap"> 
            <option value="">- Choose Operator -</option>"';
       for($i=0;$i<count($result)-1;$i++)
	   {
	    $html .= '<option value='.$result[$i]['id'].'>'.$result[$i]['name'].'</option>';
	       
	   }
        $html .= "</select>";
   echo json_encode($html);
  }
 }
	 public function filterBulkUpload()
	 {
	 	
	 	//error_reporting(E_ALL);
	 	//echo '<pre>';
	 	//print_r($_FILES);
	 	//die;
	 	$temp_file_name = $_FILES['upload_filter']['tmp_name'];
	 	$result_bulk = $this->model_filterlist->filterBulkUpload($temp_file_name);
		$this->saveFilterlist($result_bulk);




	 }
}
?>
