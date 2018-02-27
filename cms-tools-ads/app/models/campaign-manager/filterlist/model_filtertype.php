<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_filtertype extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	} 
	
	public function loadFiltertype($params){ 
            
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		   $this->dbAds->from('setting_filter_country c'); 
		
		if(!empty($params['search']))
			$this->dbAds->where("c.id LIKE '%".$params['search']."%' OR c.list_name LIKE '%".$params['search']."%'  ");
		//print_r($this->dbAds);exit();
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('f.id, f.list_name,f.status');
		$this->dbAds->from('setting_filter_country f');
		
		if(!empty($params['search']))
			$this->dbAds->where("f.id LIKE '%".$params['search']."%' OR f.list_name LIKE '%".$params['search']."%'");
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['list_name'] 	= $row['list_name'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadFiltertypeSelect(){		
		$result = array();
				
		$this->dbAds->select('id, list_name');
		$this->dbAds->from('setting_filter_country');
		$this->dbAds->where('status', '1');
               
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['list_name'] 	= $row['list_name'];
				
				
				$i++;
			}
                        
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveFiltertype($params){
		$this->dbAds->select('id');
		$this->dbAds->from('setting_filter_country');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateFiltertype($params);	
		} else {
			$result = $this->insertFiltertype($params);
		}
		return $result;
	}
	
	private function updateFiltertype($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'list_name' 		=> $this->dbAds->escape_str($params['list_name']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('setting_filter_country', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertFiltertype($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'list_name' 		=> $this->dbAds->escape_str($params['list_name']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s')) 
					   );
		$this->dbAds->insert('setting_filter_country', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
                    $result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteFiltertype($params){
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('setting_filter_country');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result; 
	}
	
	public function getFiltertypeData($params){
		$this->dbAds->select('id, list_name, status');
		$this->dbAds->from('setting_filter_country');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['list_name'] = $row['list_name'];
				
				
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
   