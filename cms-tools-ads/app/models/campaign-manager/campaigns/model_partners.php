<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_partners extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadPartners($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('partners c');
		$this->dbAds->join('countries co', 'c.id_country = co.id');
		if(!empty($params['search']))
			$this->dbAds->where("c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR co.name LIKE '%".$params['search']."%' OR c.description LIKE '%".$params['search']."%' OR c.code LIKE '%".$params['search']."%'");
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('c.id, c.name, co.name AS co_name, c.code, c.status, c.utc_timezone, c.description');
		$this->dbAds->from('partners c');
		$this->dbAds->join('countries co', 'c.id_country = co.id');
		if(!empty($params['search']))
			$this->dbAds->where("c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR co.name LIKE '%".$params['search']."%' OR c.description LIKE '%".$params['search']."%' OR c.code LIKE '%".$params['search']."%'");
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['co_name'] 	= $row['co_name'];
				$result['rows'][$i]['utc_timezone'] = ($row['utc_timezone'] < 10)?'UTC +0'.$row['utc_timezone'].'.00':'UTC +'.$row['utc_timezone'].'.00';
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
	
	public function loadPartnersSelect(){		
		$result = array();
				
		$this->dbAds->select('id, name, id_country, description');
		$this->dbAds->from('partners');
		$this->dbAds->where('status', '1');
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$this->load->model('campaign-manager/master/model_ads_publishers');
				$code = $this->model_ads_publishers->getCountryCode($row['id_country']);
				
				$result['rows'][$i]['id'] 		= $row['id'];
				if($code[code])
					$result['rows'][$i]['name'] 	= $row['name'].'-'.$code[code];
				else
					$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['id_country'] 	= $row['id_country'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function savePartners($params){
		$this->dbAds->select('id');
		$this->dbAds->from('partners');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updatePartners($params);	
		} else {
			$result = $this->insertPartners($params);
		}
		return $result;
	}
	
	private function updatePartners($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'id_country' => $this->dbAds->escape_str($params['id_country']),
						'utc_timezone' => $this->dbAds->escape_str($params['utc_timezone']),
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'description' 		=> $this->dbAds->escape_str($params['description']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('partners', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertPartners($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'id_country' => $this->dbAds->escape_str($params['id_country']),
						'utc_timezone' => $this->dbAds->escape_str($params['utc_timezone']),
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'description' 		=> $this->dbAds->escape_str($params['description']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s')) 
					   );
		$this->dbAds->insert('partners', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deletePartners($params){
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('partners');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getPartnersData($params){
		$this->dbAds->select('id, name, id_country, description,code,status,utc_timezone');
		$this->dbAds->from('partners');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['name'] = $row['name'];
				$result['id_country'] = $row['id_country'];
				$result['utc_timezone'] = $row['utc_timezone'];
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
