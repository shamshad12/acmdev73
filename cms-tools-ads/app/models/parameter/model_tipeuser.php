<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_tipeuser extends MY_Model {

	public function __construct () {
		parent::__construct();
		
		$this->loadDbCms();
	}
	
	public function loadTipeUser(){
		$this->dbCms->select('id, nama_tipe_user');
		$this->dbCms->from($this->tableTipeUser);
		$this->dbCms->where('status', '1');
		$query = $this->dbCms->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] = $row['id'];
				$result['rows'][$i]['nama_tipe_user'] = $row['nama_tipe_user'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
	
	public function saveTipeUser($params){
		$this->dbCms->select('id');
		$this->dbCms->from($this->tableTipeUser);
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$data = $this->dbCms->get();
		if($data->num_rows() != 0){
			$result = $this->updateTipeUser($params);	
		} else {
			$result = $this->insertTipeUser($params);
		}
		return $result;
	}
	
	private function updateTipeUser($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'nama_tipe_user' => $this->dbCms->escape_str($params['nama_tipe_user']),
						'created' => $this->dbCms->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->update($this->tableTipeUser, $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertTipeUser($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'nama_tipe_user' => $this->dbCms->escape_str($params['nama_tipe_user']),
						'created' => $this->dbCms->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbCms->insert($this->tableTipeUser, $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteTipeUser($params){
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->delete($this->tableTipeUser);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getTipeUserData($params){
		$this->dbCms->select('id, nama_tipe_user');
		$this->dbCms->from($this->tableTipeUser);
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$query = $this->dbCms->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['nama_tipe_user'] = $row['nama_tipe_user'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
}
?>
