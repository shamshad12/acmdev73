<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_publisher extends MY_Model {

	public function __construct () {
		parent::__construct();
		
		$this->loadDbCms();
		$this->loadDbAds();
	}
	
	public function loadPublisher($params){
		$result = array();
		
		$this->dbCms->select(' COUNT(1) AS count ');
		$this->dbCms->from($this->tableKaryawan.' k');
		$this->dbCms->where('status', '1');
		$this->dbCms->where('k.type_account', 'Publisher');
		if(!empty($params['search']))
			$this->dbCms->where("k.id LIKE '%".$params['search']."%' OR k.nama_karyawan LIKE '%".$params['search']."%' OR k.email LIKE '%".$params['search']."%' OR k.telepon LIKE '%".$params['search']."%'");
		
		$query = $this->dbCms->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
		
		$this->dbCms->select('k.id, k.nama_karyawan, k.email, k.telepon');
		$this->dbCms->from($this->tableKaryawan.' k');
		$this->dbCms->where('status', '1');
		$this->dbCms->where('k.type_account', 'Publisher');
		if(!empty($params['search']))
			$this->dbCms->where("k.id LIKE '%".$params['search']."%' OR k.nama_karyawan LIKE '%".$params['search']."%' OR k.email LIKE '%".$params['search']."%' OR k.telepon LIKE '%".$params['search']."%'");
		$this->dbCms->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbCms->get();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] = $row['id'];
				$result['rows'][$i]['nama_publisher'] = $row['nama_karyawan'];
				$result['rows'][$i]['email'] = $row['email'];
				$result['rows'][$i]['telepon'] = $row['telepon'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
	
	public function savePublisher($params){
		$this->dbCms->select('id');
		$this->dbCms->from($this->tableKaryawan);
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$data = $this->dbCms->get();
		if($data->num_rows() != 0){
			$result = $this->updatePublisher($params);	
		} else {
			$result = $this->insertPublisher($params);
		}
		return $result;
	}
	
	private function updatePublisher($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'publisher_id' => $this->dbCms->escape_str($params['store_id']),
						'nama_karyawan' => $this->dbCms->escape_str($params['nama_publisher']),
						'tempat_lahir' => $this->dbCms->escape_str($params['tempat_lahir']),
						'tanggal_lahir' => $this->dbCms->escape_str($params['tanggal_lahir']),
						'jenis_kelamin' => $this->dbCms->escape_str($params['jenis_kelamin']),
						'agama' => $this->dbCms->escape_str($params['agama']),
						'alamat' => $this->dbCms->escape_str($params['alamat']),
						'email' => $this->dbCms->escape_str($params['email']),
						'avatar_thumb' => $this->dbCms->escape_str($params['avatar_thumb']),
						'avatar_ori' => $this->dbCms->escape_str($params['avatar_ori']),
						'telepon' => $this->dbCms->escape_str($params['telepon']),
						'status' => $this->dbCms->escape_str($params['status']),
						'created' => $this->dbCms->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->update($this->tableKaryawan, $update);
		
		if(!empty($params['userpass'])){
			$update = array(
							'username' => $this->dbCms->escape_str($params['username']),
							'userpass' => $this->dbCms->escape_str(md5($params['userpass'])),
							'tipe_user_id' => $this->dbCms->escape_str($params['tipe_user_id']),
							'created' => $this->dbCms->escape_str(date('Y-m-d H:i:s'))
						   );
		} else {
			$update = array(
							'username' => $this->dbCms->escape_str($params['username']),
							'tipe_user_id' => $this->dbCms->escape_str($params['tipe_user_id']),
							'created' => $this->dbCms->escape_str(date('Y-m-d H:i:s'))
						   );
		}
		$this->dbCms->where('karyawan_id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->update($this->tableUser, $update);
		
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertPublisher($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'type_account' => $this->dbCms->escape_str('Publisher'),
						'publisher_id' => $this->dbCms->escape_str($params['store_id']),
						'nama_karyawan' => $this->dbCms->escape_str($params['nama_publisher']),
						'tempat_lahir' => $this->dbCms->escape_str($params['tempat_lahir']),
						'tanggal_lahir' => $this->dbCms->escape_str($params['tanggal_lahir']),
						'jenis_kelamin' => $this->dbCms->escape_str($params['jenis_kelamin']),
						'agama' => $this->dbCms->escape_str($params['agama']),
						'alamat' => $this->dbCms->escape_str($params['alamat']),
						'email' => $this->dbCms->escape_str($params['email']),
						'avatar_thumb' => $this->dbCms->escape_str($params['avatar_thumb']),
						'avatar_ori' => $this->dbCms->escape_str($params['avatar_ori']),
						'status' => $this->dbCms->escape_str($params['status']),
						'telepon' => $this->dbCms->escape_str($params['telepon']),
						'created' => $this->dbCms->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbCms->insert($this->tableKaryawan, $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
			
			$publisher_id = $this->dbCms->insert_id();
			
			#Insert into User table
			$update = array(
							'karyawan_id' => $this->dbCms->escape_str($publisher_id),
							'username' => $this->dbCms->escape_str($params['username']),
							'userpass' => $this->dbCms->escape_str(md5($params['userpass'])),
							'tipe_user_id' => $this->dbCms->escape_str($params['tipe_user_id']),
							'status' => $this->dbCms->escape_str('1'),
							'created' => $this->dbCms->escape_str(date('Y-m-d H:i:s'))
						   );
			$this->dbCms->insert($this->tableUser, $update);
			
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deletePublisher($params){
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->delete($this->tableKaryawan);
		
		$this->dbCms->where('karyawan_id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->delete($this->tableUser);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getPublisherData($params){
		$this->dbCms->select('id, publisher_id, nama_karyawan, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat, email, telepon, status');
		$this->dbCms->from($this->tableKaryawan);
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$query = $this->dbCms->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['store_id'] = $row['publisher_id'];
				$result['nama_publisher'] = $row['nama_karyawan'];
				$result['tempat_lahir'] = $row['tempat_lahir'];
				$result['tanggal_lahir'] = $row['tanggal_lahir'];
				$result['jenis_kelamin'] = $row['jenis_kelamin'];
				$result['agama'] = $row['agama'];
				$result['alamat'] = $row['alamat'];
				$result['email'] = $row['email'];
				$result['telepon'] = $row['telepon'];
				$result['status'] = $row['status'];
				$i++;
			}
			
			$this->dbCms->select('username, tipe_user_id');
			$this->dbCms->from($this->tableUser);
			$this->dbCms->where('karyawan_id', $this->dbCms->escape_str($params['id']));
			$query = $this->dbCms->get();
			
			foreach($query->result_array() as $row) {
				$result['username'] = $row['username'];
				$result['tipe_user_id'] = $row['tipe_user_id'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
	
	public function reportPublisher($params = array()){
		$this->dbCms->select('id, nama_karyawan, email, telepon, jenis_kelamin');
		$this->dbCms->from($this->tableKaryawan);
		
		$query = $this->dbCms->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] = $row['id'];
				$result['rows'][$i]['nama_publisher'] = $row['nama_karyawan'];
				$result['rows'][$i]['email'] = $row['email'];
				$result['rows'][$i]['telepon'] = $row['telepon'];
				$result['rows'][$i]['jenis_kelamin'] = ($row['jenis_kelamin'] == 1)?'Pria':'Wanita';
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
}
?>