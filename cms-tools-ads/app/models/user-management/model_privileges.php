<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_privileges extends MY_Model {

	public function __construct () {
		parent::__construct();
		
		$this->loadDbCms();
	}
	
	public function loadPrivileges($params){		
		$result = array();
		
		$this->dbCms->select(' COUNT(1) AS count ');
		$this->dbCms->from($this->tablePrivileges.' p');
		$this->dbCms->join($this->tableTipeUser.' tu', 'p.tipe_user_id = tu.id');
		$this->dbCms->join($this->tableMenu.' m', 'p.default_menu = m.id', 'LEFT OUTER');
		$this->dbCms->where('p.status IN(0,1)');
		if(!empty($params['search']))
			$this->dbCms->where("p.id LIKE '%".$params['search']."%' OR tu.nama_tipe_user LIKE '%".$params['search']."%' OR p.description LIKE '%".$params['search']."%' OR m.name LIKE '%".$params['search']."%'");
		
		$query = $this->dbCms->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbCms->select('p.id, tu.nama_tipe_user AS tipe_user_id, p.description, m.name AS default_menu, p.status');
		$this->dbCms->from($this->tablePrivileges.' p');
		$this->dbCms->join($this->tableTipeUser.' tu', 'p.tipe_user_id = tu.id');
		$this->dbCms->join($this->tableMenu.' m', 'p.default_menu = m.id', 'LEFT OUTER');
		$this->dbCms->where('p.status IN(0,1)');		
		if(!empty($params['search']))
			$this->dbCms->where("p.id LIKE '%".$params['search']."%' OR tu.nama_tipe_user LIKE '%".$params['search']."%' OR p.description LIKE '%".$params['search']."%' OR m.name LIKE '%".$params['search']."%'");
		$this->dbCms->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbCms->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 			= $row['id'];
				$result['rows'][$i]['tipe_user_id'] = $row['tipe_user_id'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['default_menu'] = $row['default_menu'];
				$result['rows'][$i]['status'] 		= ($row['status'])?'Active':'Inactive';
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadPrivilegesSelect(){		
		$result = array();
				
		$this->dbCms->select('p.id, tu.nama_tipe_user AS tipe_user_id, p.description, m.name AS default_menu, p.status');
		$this->dbCms->from($this->tablePrivileges.' p');
		$this->dbCms->join($this->tableTipeUser.' tu', 'p.tipe_user_id = tu.id');
		$this->dbCms->join($this->tableMenu.' m', 'p.default_menu = m.id', 'LEFT OUTER');
		$this->dbCms->where('p.status IN(0,1)');
		$query = $this->dbCms->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 			= $row['id'];
				$result['rows'][$i]['tipe_user_id'] = $row['tipe_user_id'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['default_menu'] = $row['default_menu'];
				$result['rows'][$i]['status'] 		= ($row['status'])?'Active':'Inactive';
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function savePrivileges($params){
		$this->dbCms->select('id');
		$this->dbCms->from($this->tablePrivileges);
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$data = $this->dbCms->get();
		if($data->num_rows() != 0){
			$result = $this->updatePrivileges($params);	
		} else {
			$result = $this->insertPrivileges($params);
		}
		return $result;
	}
	
	private function updatePrivileges($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'tipe_user_id' 	=> $this->dbCms->escape_str($params['tipe_user_id']),
						'description' 	=> $this->dbCms->escape_str($params['description']),
						'default_menu' 	=> $this->dbCms->escape_str($params['default_menu']),
						'status' 		=> $this->dbCms->escape_str($params['status']),
						'update_by' 	=> $this->dbCms->escape_str($input_by),
						'update_time' 	=> $this->dbCms->escape_str($input_time)
					   );
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->update($this->tablePrivileges, $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertPrivileges($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'tipe_user_id' => $this->dbCms->escape_str($params['tipe_user_id']),
						'description' => $this->dbCms->escape_str($params['description']),
						'default_menu' => $this->dbCms->escape_str($params['default_menu']),
						'status' => $this->dbCms->escape_str($params['status']),
						'created_by' => $this->dbCms->escape_str($input_by),
						'created_time' => $this->dbCms->escape_str($input_time)
					   );
		$this->dbCms->insert($this->tablePrivileges, $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$id = $this->dbCms->insert_id();
			
			$this->dbCms->query("INSERT INTO cms_privilege_menu (id_privilege, id_menu, status, access, created_by, created_time) (SELECT '".$id."', id, '1', '0', '".$input_by."', '".$input_time."' FROM cms_menu)");
			
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deletePrivileges($params){
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->delete($this->tablePrivileges);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getPrivilegesData($params){
		$this->dbCms->select('id, tipe_user_id, description, default_menu, status');
		$this->dbCms->from($this->tablePrivileges);
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$query = $this->dbCms->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['tipe_user_id'] = $row['tipe_user_id'];
				$result['description'] = $row['description'];
				$result['default_menu'] = $row['default_menu'];
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
