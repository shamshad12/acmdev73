<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_Partner extends MY_Model {
	
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadPartner($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('api_partner');
		if(!empty($params['search']))
			$this->dbAds->where("(id LIKE '%".$params['search']."%' OR name LIKE '%".$params['search']."%' OR code LIKE '%".$params['search']."%')");
		$this->dbAds->where('status != 2');
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('id, name, code, status');
		$this->dbAds->from('api_partner');
		if(!empty($params['search']))
               $this->dbAds->where("(id LIKE '%".$params['search']."%' OR name LIKE '%".$params['search']."%' OR code LIKE '%".$params['search']."%')");
		$this->dbAds->where('status != 2');
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die;
		
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
	
	public function loadPartnerSelect(){		
		$result = array();
				
		$this->dbAds->select('id, name, code, description');
		$this->dbAds->from('api_partner');
		$this->dbAds->where('status',1);
		$this->dbAds->order_by("name","asc");
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
	
	public function savePartner($params){
           
		$this->dbAds->select('id');
		$this->dbAds->from('api_partner');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updatePartner($params);	
		} else {
                    
			$result = $this->insertPartner($params);
		}
		return $result;
	}
	
	private function updatePartner($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status'])
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('api_partner', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Partner",$update_message);
			$update_message=str_replace("{TITLE}",$params['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertPartner($params){

		$result = array();
		//$where = "(name='".$this->dbAds->escape_str($params['name'])."' or code ='".$this->dbAds->escape_str($params['code'])."' or prefix ='".$this->dbAds->escape_str($params['prefix'])."' ) ";
		$this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('api_partner');
		//$this->dbAds->where('name',$this->dbAds->escape_str($params['name']));
                //$this->dbAds->where_or('code',$this->dbAds->escape_str($params['code']));
                $this->dbAds->where("(name='".$this->dbAds->escape_str($params['name'])."') OR (code='".$this->dbAds->escape_str($params['code'])."')" );
		$query = $this->dbAds->get();
		
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "Partner Name or partner code you are using already added, so you can not use same name.";  
				$result['duplicat_data'] =  true;
				return $result;
			}
		}

		
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status'])
					   );
		$this->dbAds->insert('api_partner', $update);
		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Partner",$add_message);
			$add_message=str_replace("{TITLE}",$params['name'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deletePartner($params){
		/*$contry_data=$this->getPartnerData($params);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('api_partner');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Partner",$update_message);
			$update_message=str_replace("{TITLE}",$contry_data['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$contry_data);
		} else {
			$result['success'] = false;
		}
		return $result;*/

		$update = array();
		$update['status'] = 2;
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('api_partner', $update);
		
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getPartnerData($params){
		$this->dbAds->select('c.id, c.name, c.code,  c.status');
		$this->dbAds->from('api_partner c');
		$this->dbAds->where('c.id', $this->dbAds->escape_str($params['id']));
		//$this->dbAds->join('user_log u', 'u.id = c.update_user', 'left');
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['name'] = $row['name'];
				$result['code'] = $row['code'];
				//$result['description'] = $row['description'];
				//$result['prefix'] = $row['prefix'];
				$result['status'] = $row['status'];
				//$result['entry_time'] = $row['entry_time'];
				//$result['update_time'] = $row['update_time'];
				//$result['user_name'] = $row['user_name'];
				//$result['entry_user'] = $row['entry_user'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
}
?>
