<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_country extends MY_Model {
	
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadCountry($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('countries');
		if(!empty($params['search']))
			$this->dbAds->where("id LIKE '%".$params['search']."%' OR name LIKE '%".$params['search']."%' OR code LIKE '%".$params['search']."%' OR prefix LIKE '%".$params['search']."%' OR description LIKE '%".$params['search']."%'");
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('id, name, code, prefix, status, description');
		$this->dbAds->from('countries');
		if(!empty($params['search']))
			$this->dbAds->where("id LIKE '%".$params['search']."%' OR name LIKE '%".$params['search']."%' OR code LIKE '%".$params['search']."%' OR prefix LIKE '%".$params['search']."%' OR description LIKE '%".$params['search']."%'");
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['code'] 	= $row['code'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				$result['rows'][$i]['prefix'] 	= $row['prefix'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadCountrySelect(){		
		$result = array();
				
		$this->dbAds->select('id, name, code, description');
		$this->dbAds->from('countries');
		$this->dbAds->where('status',1);
		$this->dbAds->order_by('name');
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['code'] 	= $row['code'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveCountry($params){
           
		$this->dbAds->select('id');
		$this->dbAds->from('countries');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateCountry($params);	
		} else {
                    
			$result = $this->insertCountry($params);
		}
		return $result;
	}
	
	private function updateCountry($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'prefix' 	=> $this->dbAds->escape_str($params['prefix']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' => $this->dbAds->escape_str($params['description']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('countries', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Country",$update_message);
			$update_message=str_replace("{TITLE}",$params['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertCountry($params){

		$result = array();
		//$where = "(name='".$this->dbAds->escape_str($params['name'])."' or code ='".$this->dbAds->escape_str($params['code'])."' or prefix ='".$this->dbAds->escape_str($params['prefix'])."' ) ";
		$this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('countries');
		$this->dbAds->where('name',$this->dbAds->escape_str($params['name']));

		$query = $this->dbAds->get();
		
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "Country Name you are using already added, so you can not use same name.";  
				$result['duplicat_data'] =  true;
				return $result;
			}
		}

		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'prefix' 	=> $this->dbAds->escape_str($params['prefix']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' => $this->dbAds->escape_str($params['description']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->insert('countries', $update);
		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Country",$add_message);
			$add_message=str_replace("{TITLE}",$params['name'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteCountry($params){
		$contry_data=$this->getCountryData($params);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('countries');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Country",$update_message);
			$update_message=str_replace("{TITLE}",$contry_data['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$contry_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getCountryData($params){
		$this->dbAds->select('id, name, code, description,prefix,status');
		$this->dbAds->from('countries');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['name'] = $row['name'];
				$result['code'] = $row['code'];
				$result['description'] = $row['description'];
				$result['prefix'] = $row['prefix'];
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
