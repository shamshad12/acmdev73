<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_services_types extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadServices_Types($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('services_types');
		if(!empty($params['search']))
			$this->dbAds->where("id LIKE '%".$params['search']."%' OR code LIKE '%".$params['search']."%' OR name LIKE '%".$params['search']."%' OR description LIKE '%".$params['search']."%'");
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('id, code, name, status, description');
		$this->dbAds->from('services_types');
		if(!empty($params['search']))
			$this->dbAds->where("id LIKE '%".$params['search']."%' OR code LIKE '%".$params['search']."%' OR  name LIKE '%".$params['search']."%' OR description LIKE '%".$params['search']."%'");
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['code'] 	= $row['code'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadServices_TypesSelect(){		
		$result = array();
				
		$this->dbAds->select('id, code, name, description');
		$this->dbAds->from('services_types');
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['code'] 	= $row['code'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveServices_Types($params){
		$this->dbAds->select('id');
		$this->dbAds->from('services_types');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateServices_Types($params);	
		} else {
			$result = $this->insertServices_Types($params);
		}
		return $result;
	}
	
	private function updateServices_Types($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' => $this->dbAds->escape_str($params['description']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('services_types', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Service",$update_message);
			$update_message=str_replace("{TITLE}",$params['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertServices_Types($params){
            
                $result = array();
		
                $this->dbAds->select('COUNT(1) as exist_data');
                $this->dbAds->from('services_types');
                $this->dbAds->where("(name='".$this->dbAds->escape_str($params['name'])."' or code ='".$this->dbAds->escape_str($params['code'])."' ) ");
		$query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die('kl');
		foreach($query->result_array() as $row)
		{
                   // print_r($row);exit(); 
			if($row[exist_data]!=0) 
			{
				$result['errors_message'] = "Service Name and code  already  your added, so you can not use same name.";  
				$result['duplicat_data'] =  true; 
				return $result;
			}
		}    
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' => $this->dbAds->escape_str($params['description']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->insert('services_types', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Service",$add_message);
			$add_message=str_replace("{TITLE}",$params['name'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteServices_Types($params){
		$service_data=$this->getServices_TypesData($params);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('services_types');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Service",$update_message);
			$update_message=str_replace("{TITLE}",$service_data['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getServices_TypesData($params){
		$this->dbAds->select('id, code, name, description, status');
		$this->dbAds->from('services_types');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['code'] = $row['code'];
				$result['name'] = $row['name'];
				$result['description'] = $row['description'];
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
