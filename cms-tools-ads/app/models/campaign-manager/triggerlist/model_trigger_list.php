<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_trigger_list extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	 
	public function loadTrigger_List($params){
            
		$result = array();
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('trigger_list op'); 
		$this->dbAds->join('operators o', 'op.telco_id = o.id', 'LEFT'); 
		$this->dbAds->join('countries p', 'op.country_id = p.id');
                $this->dbAds->join('shortcodes s', 'op.shortcode_id = s.id');  
                 
                $this->dbAds->join('language l', 'op.language_id = l.id');  
		if(!empty($params['search']))
			$this->dbAds->where("op.id LIKE '%".$params['search']."%' OR op.message LIKE '%".$params['search']."%' OR op.type LIKE '%".$params['search']."%' OR 
								 o.name LIKE '%".$params['search']."%' OR op.shortcode_id LIKE '%".$params['search']."%' OR op.keyword LIKE '%".$params['search']."%' OR
								 p.name LIKE '%".$params['search']."%' OR l.language LIKE '%".$params['search']."%'
								 ");
								 
		
		if(!empty($params['operator']))
			$this->dbAds->where("op.telco_id", $params['operator']);
		
                if(!empty($params['country']))
			$this->dbAds->where("op.country_id", $params['country']); 
		
                if(!empty($params['language']))
			$this->dbAds->where("op.language_id", $params['language']);
                if(!empty($params['keyword']))
			$this->dbAds->where("op.keyword", $params['keyword']);
		if(!empty($params['shortcode']))
			$this->dbAds->where("op.shortcode_id", $params['shortcode']);
		
                
		$query = $this->dbAds->get();
//                echo $this->dbAds->last_query();die('sss');  
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];  
				 
		$this->dbAds->select('op.id,op.type,op.message,op.language_id,op.country_id,s.code as shortcode_id,s.id as short_id,op.telco_id,op.keyword, o.name AS operator_name, p.name AS country_name, op.status, l.language AS language');
		$this->dbAds->from('trigger_list op');
		$this->dbAds->join('operators o', 'op.telco_id = o.id', 'LEFT');
		$this->dbAds->join('countries p', 'op.country_id = p.id');
                $this->dbAds->join('language l', 'op.language_id = l.id'); 
                $this->dbAds->join('shortcodes s', 'op.shortcode_id = s.id');
		$this->dbAds->where("(op.id LIKE '%".$params['search']."%' OR op.message LIKE '%".$params['search']."%'  OR op.type LIKE '%".$params['search']."%' OR 
								 o.name LIKE '%".$params['search']."%' OR op.shortcode_id LIKE '%".$params['search']."%' OR op.keyword LIKE '%".$params['search']."%' OR
								 p.name LIKE '%".$params['search']."%' OR  l.language LIKE '%".$params['search']."%')");
			
                if(!empty($params['shortcode']))
			$this->dbAds->where("op.shortcode_id", $params['shortcode']);
		
		if(!empty($params['operator']))
			$this->dbAds->where("op.telco_id", $params['operator']);
		
                if(!empty($params['country']))
			$this->dbAds->where("op.country_id", $params['country']); 
		
                if(!empty($params['language']))
			$this->dbAds->where("op.language_id", $params['language']);
                 
                if(!empty($params['keyword']))
			$this->dbAds->where("op.keyword", $params['keyword']); 
		
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();   
                // echo $this->dbAds->last_query();die('sss');  
//     
                 $i=0; 
		if($query->num_rows()!= 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
                           
				$result['rows'][$i]['id'] 	= $row['id'];
				$result['rows'][$i]['message'] 	= $row['message'];
				$result['rows'][$i]['keyword'] 	= $row['keyword'];
				$result['rows'][$i]['country_name'] 	= $row['country_name'];
                                if(empty($row['operator_name']))
                                    $result['rows'][$i]['operator_name'] 	= 'ALL'; 
                                else
                                    $result['rows'][$i]['operator_name'] 	= $row['operator_name'];
                               
                                $result['rows'][$i]['language'] 	= $row['language'];
                                if($row['telco_id']==0)
                                    $result['rows'][$i]['telco_id'] 	= 0; 
                                else
                                    $result['rows'][$i]['telco_id'] 	= $row['telco_id'];
                                $result['rows'][$i]['country_id'] 	= $row['country_id'];
                                $result['rows'][$i]['shortcode_id'] 	= $row['shortcode_id'];
                                $result['rows'][$i]['short_id'] 	= $row['short_id'];     
                                $result['rows'][$i]['language_id'] 	= $row['language_id'];
                                $result['rows'][$i]['type'] 	= $row['type'];
				$result['rows'][$i]['status'] 	= $row['status']; 
                               // print_r($result);die('sss');
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadTrigger_ListSelect($params){		
		$result = array();
				
		$this->dbAds->select('op.id, p.id AS partner_id,op.country_id,op.type,op.shortcode_id,op.telco_id,o.name AS operator_name, p.name AS partner_name,l.name AS language');
		$this->dbAds->from('operators_partners op');
		$this->dbAds->join('operators o', 'op.id_operator = o.id');
		$this->dbAds->join('countries p', 'op.country_id = p.id');
                $this->dbAds->join('shortcodes s', 'op.shortcode_id = s.id');
                $this->dbAds->join('language l', 'op.language_id = l.id');
		$this->dbAds->where('op.status', '1');
		if(isset($params['id_operator']))
			$this->dbAds->where('op.id_operator', $params['operator']);
                
                if(isset($params['country']))
			$this->dbAds->where("op.country_id", $params['country']);
		
                if(isset($params['shortcode']))
			$this->dbAds->where("op.shortcode_id", $params['shortcode']);
			
		if(isset($params['language']))
			$this->dbAds->where("op.language_id", $params['language']);
			
		$query = $this->dbAds->get(); 
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
                           
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['country'] 		= $row['country'];
				$result['rows'][$i]['operator_name'] 	= $row['operator_name'];
				$result['rows'][$i]['shortcode'] 	= $row['shortcode'];
                                $result['rows'][$i]['message'] 	= $row['message'];
                                $result['rows'][$i]['keyword'] 	= $row['keyword'];
                                $result['rows'][$i]['type'] 	= $row['type']; 
                                 $result['rows'][$i]['language'] 	= $row['language'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveTrigger_List($params){
		$this->dbAds->select('id');
		$this->dbAds->from('trigger_list');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateTrigger_List($params);	
		} else {
			$result = $this->insertTrigger_List($params);
		}
		return $result;
	}
	
	public function updateTrigger_List($params){
		
		$getData = $this->getTrigger_ListData(array('id'=>$params['id']));
		$getKeyword = str_replace(' ', '', $getData['keyword']);
   		$redisParams = array('del',(KEYS_TRIGGER.$getData['country_id'].'_'.$getData['shortcode_id'].'_'.$getData['telco_id'].'_'.$getKeyword.'_'.str_replace(' ', '', $getData['type'])));  
       	$campaignRedis = $this->redisCommand('default', $redisParams);

		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
                $update = array(
		                'message' =>$this->dbAds->escape_str($params['message']),
						'country_id'  => $this->dbAds->escape_str($params['country_id']),
                        'shortcode_id'  => $this->dbAds->escape_str($params['shortcode_id']),
                        'keyword'  => $this->dbAds->escape_str($params['keyword']),
						'type' 	 => $this->dbAds->escape_str($params['type']),
						'pin_type' 	 => $this->dbAds->escape_str($params['pin_type']),
						'generate_url' 	 => $this->dbAds->escape_str($params['generate_url']),
						'validate_url' 	 => $this->dbAds->escape_str($params['validate_url']),
						'telco_id' 	 => $this->dbAds->escape_str($params['telco_id']),
						'language_id' 	 	=> $this->dbAds->escape_str($params['language_id']),
						'status' 		=> $this->dbAds->escape_str($params['status']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('trigger_list', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$pin_type = '';
			$gen_val_url = '';

        	if($params['type']=='pin')
        		$pin_type = '_'.$params['pin_type'];

        	if($params['pin_type']=='remote')
        		$gen_val_url = '_'.$params['generate_url'].'_'.$params['validate_url'];


			$result['success'] = true;
           	$result['message'] = $message;  
           	$messagestring = str_replace(' ', '', $result['message']);   
           	$type = str_replace(' ', '', $params['type']); 
           	$keyword = str_replace(' ', '', $params['keyword']); 

           	$redisParams = array('set',(KEYS_TRIGGER.$params['country_id'].'_'.$params['shortcode_id'].'_'.$params['telco_id'].'_'.$keyword.'_'.$params['type']),($messagestring.'_'.$params['language_id'].$pin_type.$gen_val_url));  
           	$campaignRedis = $this->redisCommand('default', $redisParams);
		} 
		else {
			$result['success'] = false;
		}  
		return $result;
	} 
	
	public function insertTrigger_List($params){
        
        $this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('trigger_list');
		$this->dbAds->where('country_id',$params['country_id']);
		$this->dbAds->where('shortcode_id',$params['shortcode_id']);
		$this->dbAds->where('keyword',$params['keyword']);
		$this->dbAds->where('telco_id',$params['telco_id']);
		$this->dbAds->where('type',$params['type']);
               
		$query = $this->dbAds->get();
		$result = array();
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "Already Configured in this Settings So You can Edit not add";  
				$result['duplicat_data'] =  true;
				return $result;
			}
		}
           
            
		 $input_by = $this->session->userdata('id_user');
		 $input_time = strtotime(date('Y-m-d H:i:s'));
         $update = array( 
				'message' => $this->dbAds->escape_str($params['message']), 
				'country_id'  => $this->dbAds->escape_str($params['country_id']),
                'shortcode_id'  => $this->dbAds->escape_str($params['shortcode_id']),
                'keyword'  => $this->dbAds->escape_str($params['keyword']),
				'type' 	 => $this->dbAds->escape_str($params['type']),
				'pin_type' 	 => $this->dbAds->escape_str($params['pin_type']),
				'generate_url' 	 => $this->dbAds->escape_str($params['generate_url']),
				'validate_url' 	 => $this->dbAds->escape_str($params['validate_url']),
				'telco_id' 	 => $this->dbAds->escape_str($params['telco_id']),
				'language_id' 	 	=> $this->dbAds->escape_str($params['language_id']),
				'status' 		=> $this->dbAds->escape_str($params['status']),
				'entry_user' => $this->dbAds->escape_str($this->profile['id']),
				'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
			   );
		$this->dbAds->insert('trigger_list', $update);
                
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
                        $result['message'] = $message;
		} 
		else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteTrigger_List($params){
		$update = array('status'=> $this->dbAds->escape_str($params['status'])
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('trigger_list', $update);
		
		$result = array();
		if($this->dbAds->affected_rows()){
                 
			 $result['success'] = true;
                        
		} else {
			$result['success'] = false;
		}
		return $result; 
	}
        
    public function duplicateTrigger_List($params){
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('trigger_list');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getTrigger_ListData($params){
		$this->dbAds->select('id, message,country_id, shortcode_id,keyword,type,pin_type,generate_url,validate_url,telco_id,language_id,status');
		$this->dbAds->from('trigger_list');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get(); 
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true; 
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['messagetype'] = $row['message'];  
				$result['country_id'] = $row['country_id'];
				$result['shortcode_id'] = $row['shortcode_id'];
				$result['keyword'] = $row['keyword'];
				$result['type'] = $row['type'];
				$result['pin_type'] = $row['pin_type'];
				$result['generate_url'] = $row['generate_url'];
				$result['validate_url'] = $row['validate_url'];
				$result['telco_id'] = $row['telco_id'];
                $result['language_id'] = $row['language_id'];
				$result['status'] = $row['status']; 
                                
				
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
        
      public function loadKeyword($params){ 
	$ids=explode('__',$params['id']);
   $this->dbAds->distinct(); 
  $this->dbAds->select('id, keyword');
  $this->dbAds->from('partners_services');
  $this->dbAds->group_by('keyword');    
  $this->dbAds->where('id_shortcode', $this->dbAds->escape_str($ids[0]));
  $this->dbAds->where('status', 1);
  $query = $this->dbAds->get();
 //echo $this->dbAds->last_query();die('kl');
  $result = array();
  $i=0;
  if($query->num_rows() != 0){
   $result['success'] = true;
   foreach($query->result_array() as $row) {
    $result[$i]['id'] = $row['id'];
    $result[$i]['name'] = $row['keyword'];
    $i++;
   }
   
  }
  else {
   $result['success'] = false;
  }

  return $result;
 }
 
 
}
?>
