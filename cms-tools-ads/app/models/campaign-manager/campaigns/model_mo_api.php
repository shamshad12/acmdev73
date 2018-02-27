<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_mo_api extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	 
	public function loadMo_Api($params){  
		$result = array();
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('mo_api op');  
		if(!empty($params['search']))
		$this->dbAds->where("op.country_code LIKE '%".$params['search']."%' OR op.shortcode LIKE '%".$params['search']."%' OR op.keyword LIKE '%".$params['search']."%' OR 
								 op.telco LIKE '%".$params['search']."%' OR op.ccode LIKE '%".$params['search']."%'");
		/*if(!empty($params['operator']))
			$this->dbAds->where("op.telco", $params['operator']);
        if(!empty($params['country']))
			$this->dbAds->where("op.country_code", $params['country']); 
        if(!empty($params['keyword']))
			$this->dbAds->where("op.keyword", $params['keyword']);
		if(!empty($params['shortcode']))
			$this->dbAds->where("op.shortcode", $params['shortcode']);*/
		
                
		$query = $this->dbAds->get();
//                echo $this->dbAds->last_query();die('sss');  
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];  
				 
		$this->dbAds->select('op.id,op.country_code,op.shortcode,op.telco,op.keyword, op.ccode,op.status');
		$this->dbAds->from('mo_api op');
		$this->dbAds->where("op.country_code LIKE '%".$params['search']."%' OR op.shortcode LIKE '%".$params['search']."%' OR op.keyword LIKE '%".$params['search']."%' OR 
								 op.telco LIKE '%".$params['search']."%' OR op.ccode LIKE '%".$params['search']."%'");
			
        /*if(!empty($params['operator']))
			$this->dbAds->where("op.telco", $params['operator']);
        if(!empty($params['country']))
			$this->dbAds->where("op.country_code", $params['country']); 
        if(!empty($params['keyword']))
			$this->dbAds->where("op.keyword", $params['keyword']);
		if(!empty($params['shortcode']))
			$this->dbAds->where("op.shortcode", $params['shortcode']);*/
		
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();   
                // echo $this->dbAds->last_query();die('sss');  
//     
                 $i=0; 
		if($query->num_rows()!= 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
               	$result['rows'][$i]['id'] 	= $row['id'];
				$result['rows'][$i]['ccode'] 	= $row['ccode'];
				$result['rows'][$i]['keyword'] 	= $row['keyword'];
				$result['rows'][$i]['country_code'] 	= $row['country_code'];
                $result['rows'][$i]['operator_name'] 	= $row['telco'];
                $result['rows'][$i]['shortcode_id'] 	= $row['shortcode'];          
				$result['rows'][$i]['status'] 	= $row['status']; 
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
	
	public function loadMo_ApiSelect($params){		
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
	
	public function savemo_api($params){
		$this->dbAds->select('id');
		$this->dbAds->from('mo_api');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updatemo_api($params);	
		} else {
			$result = $this->insertmo_api($params);
		}
		return $result;
	}
	
	public function updatemo_api($params){
		echo "<pre>";
		print_r($params);
		die('12');
	} 
	
	public function insertmo_api($params){
		$country = explode('__', $params['country_id']);
		$params['country_id'] = $country[1];
		$shortcode = explode('__', $params['shortcode_id']);
		$params['shortcode_id'] = $shortcode[1];
		
        $this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('mo_api');
		$this->dbAds->where('country_code',$params['country_id']);
		$this->dbAds->where('shortcode',$params['shortcode_id']);
		$this->dbAds->where('keyword',$params['keyword']);
		$this->dbAds->where('telco',$params['telco_id']);
		$this->dbAds->where('ccode',$params['ccode']);
               
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
				'ccode' => $this->dbAds->escape_str($params['ccode']), 
				'country_code'  => $this->dbAds->escape_str($params['country_id']),
                'shortcode'  => $this->dbAds->escape_str($params['shortcode_id']),
                'keyword'  => $this->dbAds->escape_str($params['keyword']),
				'telco' 	 => $this->dbAds->escape_str($params['telco_id']),
				'status' 		=> $this->dbAds->escape_str($params['status']),
				'entry_user' => $this->dbAds->escape_str($this->profile['id']),
				'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
			   );
		$this->dbAds->insert('mo_api', $update);
                
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} 
		else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deletemo_api($params){
		$update = array('status'=> $this->dbAds->escape_str($params['status'])
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('mo_api', $update);
		
		$result = array();
		if($this->dbAds->affected_rows()){
                 
			 $result['success'] = true;
                        
		} else {
			$result['success'] = false;
		}
		return $result; 
	}
        
    public function duplicatemo_api($params){
    	/*print_r($params);
		die('a12');*/
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('mo_api');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getmo_apiData($params){
		$this->dbAds->select('id,country_code, shortcode,keyword,telco,ccode,status');
		$this->dbAds->from('mo_api');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get(); 
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true; 
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['ccode'] = $row['ccode'];  
				$result['country_code'] = $row['country_code'];
				$result['shortcode'] = $row['shortcode'];
				$result['keyword'] = $row['keyword'];
				$result['telco_id'] = $row['telco_id'];
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
  $this->dbAds->where('sid', $this->dbAds->escape_str($ids[1]));
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

 	public function loadOperator($params){
 		$id = explode('__', $params['id']);
 		$params['id'] = $id[0];
	  $this->dbAds->select('id, name');
	  $this->dbAds->from('operators');
	  $this->dbAds->where('id_country', $this->dbAds->escape_str($params['id']));
	  $this->dbAds->where('status', 1);
	  $query = $this->dbAds->get();
	  //echo $this->dbAds->last_query();die('kl');
	  $result = array();
	  $i=0;
	  if($query->num_rows() != 0){
	   $result['success'] = true;
	   foreach($query->result_array() as $row) {
	    $result[$i]['id'] = $row['id'];
	    $result[$i]['name'] = $row['name'];
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
