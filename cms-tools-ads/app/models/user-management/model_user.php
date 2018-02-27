<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_user extends MY_Model {

	public function __construct () {
		parent::__construct();
		
		$this->loadDbCms();
	}
	
	public function loadUser($params){
		$result = array();
		
		$this->dbCms->select(' COUNT(1) AS count ');
		$this->dbCms->from($this->tableUser.' u');
		$this->dbCms->join($this->tableKaryawan.' k', 'k.id = u.karyawan_id');
		$this->dbCms->join($this->tableTipeUser.' tu', 'tu.id = u.tipe_user_id');
		$this->dbCms->where('k.status', '1');
		if(!empty($params['search']))
			$this->dbCms->where("u.id LIKE '%".$params['search']."%' OR k.nama_karyawan LIKE '%".$params['search']."%' OR tu.nama_tipe_user LIKE '%".$params['search']."%' OR u.username LIKE '%".$params['search']."%'");
		
		$query = $this->dbCms->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
		
		$this->dbCms->select('u.id, k.nama_karyawan, tu.nama_tipe_user, u.username, u.status');		
		$this->dbCms->from($this->tableUser.' u');
		$this->dbCms->join($this->tableKaryawan.' k', 'k.id = u.karyawan_id');
		$this->dbCms->join($this->tableTipeUser.' tu', 'tu.id = u.tipe_user_id');		
		$this->dbCms->where('k.status', '1');
		if(!empty($params['search']))
			$this->dbCms->where("u.id LIKE '%".$params['search']."%' OR k.nama_karyawan LIKE '%".$params['search']."%' OR tu.nama_tipe_user LIKE '%".$params['search']."%' OR u.username LIKE '%".$params['search']."%'");
		$this->dbCms->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbCms->get();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] = $row['id'];
				$result['rows'][$i]['nama_karyawan'] = $row['nama_karyawan'];
				$result['rows'][$i]['nama_tipe_user'] = $row['nama_tipe_user'];
				$result['rows'][$i]['username'] = $row['username'];
				$result['rows'][$i]['status'] = ($row['status']==1)?'Active':'Suspend';
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
	
	public function saveUser($params){
		$this->dbCms->select('id');
		$this->dbCms->from($this->tableUser);
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$data = $this->dbCms->get();
		if($data->num_rows() != 0){
			$result = $this->updateUser($params);	
		} else {
			$result = $this->insertUser($params);
		}
		return $result;
	}
	
	private function updateUser($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		
		if(!empty($params['userpass'])){
			$update = array(
							'karyawan_id' => $this->dbCms->escape_str($params['karyawan_id']),
							'username' => $this->dbCms->escape_str($params['username']),
							'userpass' => $this->dbCms->escape_str(md5($params['userpass'])),
							'tipe_user_id' => $this->dbCms->escape_str($params['tipe_user_id']),
							'status' => $this->dbCms->escape_str($params['status']),
							'created' => $this->dbCms->escape_str(date('Y-m-d H:i:s'))
						   );
		} else {
			$update = array(
							'karyawan_id' => $this->dbCms->escape_str($params['karyawan_id']),
							'username' => $this->dbCms->escape_str($params['username']),
							'tipe_user_id' => $this->dbCms->escape_str($params['tipe_user_id']),
							'status' => $this->dbCms->escape_str($params['status']),
							'created' => $this->dbCms->escape_str(date('Y-m-d H:i:s'))
						   );
		}
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->update($this->tableUser, $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertUser($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'karyawan_id' => $this->dbCms->escape_str($params['karyawan_id']),
						'username' => $this->dbCms->escape_str($params['username']),
						'userpass' => $this->dbCms->escape_str(md5($params['userpass'])),
						'tipe_user_id' => $this->dbCms->escape_str($params['tipe_user_id']),
						'status' => $this->dbCms->escape_str($params['status']),
						'created' => $this->dbCms->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbCms->insert($this->tableUser, $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteUser($params){
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->delete($this->tableUser);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getUserData($params){
		$this->dbCms->select('id, karyawan_id, username, userpass, tipe_user_id, status');
		$this->dbCms->from($this->tableUser);
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$query = $this->dbCms->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['karyawan_id'] = $row['karyawan_id'];
				$result['username'] = $row['username'];
				$result['userpass'] = '';
				$result['tipe_user_id'] = $row['tipe_user_id'];
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
