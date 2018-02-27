<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_client extends CI_Model {

	public function __construct () {
		parent::__construct();
		
		$this->loadDbCms();
	}
	
	public function loadClient(){
		$this->dbCms->select('id, alamat, telepon, nama_client, fax');
		$this->dbCms->from('client');
		$query = $this->dbCms->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] = $row['id'];
				$result['rows'][$i]['alamat'] = $row['alamat'];
				$result['rows'][$i]['telepon'] = $row['telepon'];
				$result['rows'][$i]['nama_client'] = $row['nama_client'];
				$result['rows'][$i]['fax'] = $row['fax'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
	
	public function saveClient($params){
		$this->dbCms->select('id');
		$this->dbCms->from('client');
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$data = $this->dbCms->get();
		if($data->num_rows() != 0){
			$result = $this->updateClient($params);	
		} else {
			$result = $this->insertClient($params);
		}
		return $result;
	}
	
	private function updateClient($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'alamat' => $this->dbCms->escape_str($params['alamat']),
						'telepon' => $this->dbCms->escape_str($params['telepon']),
						'nama_client' => $this->dbCms->escape_str($params['nama_client']),
						'fax' => $this->dbCms->escape_str($params['fax'])
					   );
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->update('client', $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertClient($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'id' => $this->dbCms->escape_str($params['id']),
						'alamat' => $this->dbCms->escape_str($params['alamat']),
						'telepon' => $this->dbCms->escape_str($params['telepon']),
						'nama_client' => $this->dbCms->escape_str($params['nama_client']),
						'fax' => $this->dbCms->escape_str($params['fax'])
					   );
		$this->dbCms->insert('client', $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteClient($params){
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->delete('client');
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getClientData($params){
		$this->dbCms->select('id, alamat, telepon, nama_client, fax');
		$this->dbCms->from('client');
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$query = $this->dbCms->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['alamat'] = $row['alamat'];
				$result['telepon'] = $row['telepon'];
				$result['nama_client'] = $row['nama_client'];
				$result['fax'] = $row['fax'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
}
?>
