<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_keyword_groups extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadKeyword_Groups($params=array()){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('keyword_groups c');
		$this->dbAds->join('partners co', 'c.id_partner = co.id');
		if(!empty($params['search']))
			$this->dbAds->where("(c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR c.id_partner LIKE '%".$params['search']."%')");
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('c.id, c.name, co.name AS partner_name, c.code, c.status, c.description');
		$this->dbAds->from('keyword_groups c');
		$this->dbAds->join('partners co', 'c.id_partner = co.id');
		if(!empty($params['search']))
			$this->dbAds->where("(c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR c.id_partner LIKE '%".$params['search']."%')");
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['partner_name'] 	= $row['partner_name'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				$result['rows'][$i]['code'] 	= $row['code'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadKeyword_GroupsSelect($params=array()){		
		$result = array();
				
		$this->dbAds->select('id, code, name');
		$this->dbAds->from('keyword_groups');
		
		$this->dbAds->where('status', '1');
		if(!empty($params['id_partner']))
			$this->dbAds->where('id_partner', $params['id_partner']);
		
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['code'] 	= $row['code'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveKeyword_Groups($params){
		$this->dbAds->select('id');
		$this->dbAds->from('keyword_groups');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateKeyword_Groups($params);	
		} else {
			$result = $this->insertKeyword_Groups($params);
		}
		return $result;
	}
	
	private function updateKeyword_Groups($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'id_partner' 		=> $this->dbAds->escape_str($params['id_partner']),
						'code' 	=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' 		=> $this->dbAds->escape_str($params['description']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('keyword_groups', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
		   $update_message=str_replace("{SECTION}","Keyword Group",$update_message);
		   $update_message=str_replace("{TITLE}",$params['name'],$update_message);
		   $update_message=str_replace("{ID}",$params['id'],$update_message);
		   $this->SaveLogdata($update_message,$params);
				
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertKeyword_Groups($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'id_partner' 		=> $this->dbAds->escape_str($params['id_partner']),
						'code' 	=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' 		=> $this->dbAds->escape_str($params['description']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->insert('keyword_groups', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
		   $add_message=str_replace("{SECTION}","Keyword Group",$add_message);
		   $add_message=str_replace("{TITLE}",$params['name'],$add_message);
		   $add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
		   $this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteKeyword_Groups($params){
                 $service_data=$this->getKeyword_GroupsData($params);
				  $this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
				  $this->dbAds->delete('keyword_groups');
				  $result = array();
				  if($this->dbAds->affected_rows()){
				   $result['success'] = true;
										$update_message=$this->config->item('delete_message');
				   $update_message=str_replace("{SECTION}","Keyword Group",$update_message);
				   $update_message=str_replace("{TITLE}",$service_data['name'],$update_message);
				   $update_message=str_replace("{ID}",$params['id'],$update_message);
				   $this->SaveLogdata($update_message,$service_data);
				  } else {
				   $result['success'] = false;
				  }
				  return $result;
 }
	
	public function getKeyword_GroupsData($params){
		$this->dbAds->select('id, name, id_partner, description,code,status');
		$this->dbAds->from('keyword_groups');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['name'] = $row['name'];
				$result['id_partner'] = $row['id_partner'];
				$result['description'] = $row['description'];
				$result['code'] = $row['code'];
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
