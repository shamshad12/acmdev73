<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_telcos extends MY_Model {

	public function __construct () {
		parent::__construct();

		$this->loadDbWN();
	}

	public function loadTelcos($params){
		$result = array();

		$this->dbWN->select(' COUNT(1) AS count ');
		$this->dbWN->from('telco');
		if(!empty($params['search']))
		$this->dbWN->where("(id LIKE '%".$params['search']."%' OR code LIKE '%".$params['search']."%' OR prefix_list LIKE '%".$params['search']."%' OR name LIKE '%".$params['search']."%' OR status LIKE '%".$params['search']."%')");

		$query = $this->dbWN->get();

		$result['total'] = 0;
		foreach($query->result_array() as $row)
		$result['total'] = $row['count'];


		$this->dbWN->select('id, code, description, prefix_list, name, status');
		$this->dbWN->from('telco');
		if(!empty($params['search']))
		$this->dbWN->where("(id LIKE '%".$params['search']."%' OR code LIKE '%".$params['search']."%' OR prefix_list LIKE '%".$params['search']."%' OR name LIKE '%".$params['search']."%' OR status LIKE '%".$params['search']."%')");

		$query = $this->dbWN->get();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 			= $row['id'];
				$result['rows'][$i]['country_id'] 	= $row['country_id'];
				$result['rows'][$i]['code'] 		= $row['code'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['prefix_list'] 	= $row['prefix_list'];
				$result['rows'][$i]['name'] 		= $row['name'];
				$result['rows'][$i]['status'] 		= ($row['status'])?'Active':'Inactive';
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}

	public function saveTelcos($params){
		$this->dbWN->select('id');
		$this->dbWN->from('telco');
		$this->dbWN->where('id', $this->dbWN->escape_str($params['id']));
		$data = $this->dbWN->get();
		if($data->num_rows() != 0){
			$result = $this->updateTelcos($params);
		} else {
			$result = $this->insertTelcos($params);
		}
		return $result;
	}

	private function updateTelcos($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'country_id' => $this->dbWN->escape_str($params['country_id']),
						'code' => $this->dbWN->escape_str($params['code']),
						'description' => $this->dbWN->escape_str($params['description']),
						'name' => $this->dbWN->escape_str($params['name']),
						'prefix_list' => $this->dbWN->escape_str($params['prefix_list']),
						'status' => $this->dbWN->escape_str($params['status']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
		);
		$this->dbWN->where('id', $this->dbWN->escape_str($params['id']));
		$this->dbWN->update('telco', $update);
		$result = array();
		if($this->dbWN->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Operator",$update_message);
			$update_message=str_replace("{TITLE}",$params['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	private function insertTelcos($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'country_id' => $this->dbWN->escape_str($params['country_id']),
						'code' => $this->dbWN->escape_str($params['code']),
						'description' => $this->dbWN->escape_str($params['description']),
						'name' => $this->dbWN->escape_str($params['name']),
						'prefix_list' => $this->dbWN->escape_str($params['prefix_list']),
						'status' => $this->dbWN->escape_str($params['status']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
		);
		$this->dbWN->insert('telco', $update);
		$result = array();
		if($this->dbWN->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Operator",$add_message);
			$add_message=str_replace("{TITLE}",$params['name'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function deleteTelcos($params){
		$service_data=$this->getTelcosData($params);
		$this->dbWN->where('id', $this->dbWN->escape_str($params['id']));
		$this->dbWN->delete('telco');
		$result = array();
		if($this->dbWN->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Operator",$update_message);
			$update_message=str_replace("{TITLE}",$service_data['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function getTelcosData($params){
		$this->dbWN->select('id, country_id, code, description, name, prefix_list, status');
		$this->dbWN->from('telco');
		$this->dbWN->where('id', $this->dbWN->escape_str($params['id']));
		$query = $this->dbWN->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['country_id'] = $row['country_id'];
				$result['code'] = $row['code'];
				$result['description'] = $row['description'];
				$result['name'] = $row['name'];
				$result['prefix_list'] = $row['prefix_list'];
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
