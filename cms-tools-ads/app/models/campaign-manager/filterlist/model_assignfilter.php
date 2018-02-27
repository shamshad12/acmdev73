<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_assignfilter extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	} 
	
	public function loadAssignfilter($params){ 
          //  echo 'dddd';exit();
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('filter_country_list c'); 
		$this->dbAds->join('countries co', 'c.id_country = co.id');
                $this->dbAds->join('setting_filter_country so', 'c.id_listtype = so.id');
                
		if(!empty($params['search']))
			$this->dbAds->where("c.id LIKE '%".$params['search']."%'  OR c.validdays LIKE '%".$params['search']."%'  OR co.name LIKE '%".$params['search']."%' OR so.list_name LIKE '%".$params['search']."%'");
		//print_r($this->dbAds);exit();
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('f.id,  co.name AS co_name,so.list_name AS so_list_name, f.status,f.validdays');
		$this->dbAds->from('filter_country_list f');
		$this->dbAds->join('countries co', 'f.id_country = co.id');
                 $this->dbAds->join('setting_filter_country so', 'f.id_listtype = so.id');
		if(!empty($params['search']))  
			$this->dbAds->where("f.id LIKE '%".$params['search']."%'  OR f.validdays LIKE '%".$params['search']."%'  OR co.name LIKE '%".$params['search']."%' OR so.list_name LIKE '%".$params['search']."%'  ");
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				
				$result['rows'][$i]['co_name'] 	= $row['co_name'];
                                $result['rows'][$i]['so_name'] 	= $row['so_list_name'];
                                 $result['rows'][$i]['validdays']= $row['validdays'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadAssignfilterSelect(){		
		$result = array();
				
		$this->dbAds->select('id, id_listtype, id_country');
		$this->dbAds->from('filter_country_list');
		$this->dbAds->where('status', '1');
               
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['id_listtype'] 	= $row['id_listtype'];
				$result['rows'][$i]['id_country'] 	= $row['id_country'];
                                $result['rows'][$i]['validdays'] 	= $row['validdays'];
				
				$i++;
			}
                        
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveAssignfilter($params){
		$this->dbAds->select('id');
		$this->dbAds->from('filter_country_list');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateAssignfilter($params);	
		} else {
			$result = $this->insertAssignfilter($params);
		}
		return $result;
	}
	
	public function updateAssignfilter($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						
						'id_country' => $this->dbAds->escape_str($params['id_country']),
						'id_listtype' => $this->dbAds->escape_str($params['id_listtype']),
                                                 'validdays' => $this->dbAds->escape_str($params['validdays']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('filter_country_list', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
                    
			$result['success'] = true;

			if(!empty($params['id_listtype']) && !empty($params['validdays'])){
                $redisParams = array('exists', $params['id_country'].'_'.$params['id_listtype']);
                $campaignRedis = $this->redisCommand('default', $redisParams);

                if(!empty($campaignRedis) && $campaignRedis==1)
                {
                	 $redisParams = array('set', $params['id_country'].'_'.$params['id_listtype'], $params['validdays']);
                	$campaignRedis = $this->redisCommand('default', $redisParams);
                }

            }

		} else {
			$result['success'] = false;
		}
		return $result; 
	} 
	
	public function insertAssignfilter($params){
            
                $this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('filter_country_list');
		$this->dbAds->where('id_country',$params['id_country']);
		$this->dbAds->where('id_listtype',$params['id_listtype']);
		$query = $this->dbAds->get();
		$result = array();
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "Country Opetion Already Configured so you cannot create just edit"; 
				$result['duplicat_data'] = true;
				return $result;
			}
		}

            
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
	       $update = array(
						
						'id_country' => $this->dbAds->escape_str($params['id_country']),
						'id_listtype' => $this->dbAds->escape_str($params['id_listtype']),
                                                'validdays' => $this->dbAds->escape_str($params['validdays']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s')) 
					   );
		$this->dbAds->insert('filter_country_list', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
                     
                      
                         $result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteAssignfilter($params){
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('filter_country_list');
		$result = array();
		if($this->dbAds->affected_rows()){
                        $result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getAssignfilterData($params){
		$this->dbAds->select('id, id_country, id_listtype,validdays,status');
		$this->dbAds->from('filter_country_list');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['id_country'] = $row['id_country'];
				$result['id_listtype'] = $row['id_listtype'];
                                $result['validdays'] = $row['validdays'];
				$result['status'] = $row['status'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
}
?>
         