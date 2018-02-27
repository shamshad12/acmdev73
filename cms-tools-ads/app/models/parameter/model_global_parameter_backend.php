<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_global_parameter_backend extends MY_Model {

	public function __construct () {
		parent::__construct();
		
		$this->loadDbCms();
	}
	
	public function loadGlobalParameter($params){		
		$result = array();
		
		$this->dbCms->select(' COUNT(1) AS count ');
		$this->dbCms->from('cms_global_parameter');
		if(!empty($params['search']))
			$this->dbCms->where("id LIKE '%".$params['search']."%' OR value LIKE '%".$params['search']."%'");
		
		$query = $this->dbCms->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbCms->select('id, value');
		$this->dbCms->from('cms_global_parameter');
		if(!empty($params['search']))
			$this->dbCms->where("id LIKE '%".$params['search']."%' OR value LIKE '%".$params['search']."%'");
		$this->dbCms->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbCms->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['value'] 	= $row['value'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadGlobalParameterSelect(){		
		$result = array();
				
		$this->dbCms->select('id, value');
		$this->dbCms->from('cms_global_parameter');
		$query = $this->dbCms->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['value'] 	= $row['value'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveGlobalParameter($params){
		$this->dbCms->select('id');
		$this->dbCms->from('cms_global_parameter');
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$data = $this->dbCms->get();
		if($data->num_rows() != 0){
			$result = $this->updateGlobalParameter($params);	
		} else {
			$result = $this->insertGlobalParameter($params);
		}
		return $result;
	}
	
	private function updateGlobalParameter($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'value' 		=> $this->dbCms->escape_str($params['value']),
						'update_by' 	=> $this->dbCms->escape_str($input_by),
						'update_time' 	=> $this->dbCms->escape_str($input_time)
					   );
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->update('cms_global_parameter', $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertGlobalParameter($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'id' => $this->dbCms->escape_str($params['id']),
						'value' => $this->dbCms->escape_str($params['value']),
						'created_by' => $this->dbCms->escape_str($input_by),
						'created_time' => $this->dbCms->escape_str($input_time)
					   );
		$this->dbCms->insert('cms_global_parameter', $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteGlobalParameter($params){
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->delete('cms_global_parameter');
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getGlobalParameterData($params){
		$this->dbCms->select('id, value');
		$this->dbCms->from('cms_global_parameter');
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$query = $this->dbCms->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['value'] = $row['value'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
}
?>
