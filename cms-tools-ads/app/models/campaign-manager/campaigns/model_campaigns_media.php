<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_campaigns_media extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadCampaigns_Media($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('campaigns_media');
		if(!empty($params['search']))
			$this->dbAds->where("(id LIKE '%".$params['search']."%' OR name LIKE '%".$params['search']."%' OR description LIKE '%".$params['search']."%')");
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('id, name, status, description');
		$this->dbAds->from('campaigns_media');
		if(!empty($params['search']))
			$this->dbAds->where("(id LIKE '%".$params['search']."%' OR name LIKE '%".$params['search']."%' OR description LIKE '%".$params['search']."%')");
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
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
	
	public function loadCampaigns_MediaSelect(){		
		$result = array();
				
		$this->dbAds->select('id, name, description');
		$this->dbAds->from('campaigns_media');
		$this->dbAds->where('status','1');
		$this->dbAds->order_by('name');
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveCampaigns_Media($params){
		$this->dbAds->select('id');
		$this->dbAds->from('campaigns_media');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateCampaigns_Media($params);	
		} else {
			$result = $this->insertCampaigns_Media($params);
		}
		return $result;
	}
	
	private function updateCampaigns_Media($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' => $this->dbAds->escape_str($params['description']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('campaigns_media', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertCampaigns_Media($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' => $this->dbAds->escape_str($params['description']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->insert('campaigns_media', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteCampaigns_Media($params){
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('campaigns_media');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getCampaigns_MediaData($params){
		$this->dbAds->select('id, name, description, status');
		$this->dbAds->from('campaigns_media');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
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
