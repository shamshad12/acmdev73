<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_account extends MY_Model {

	public function __construct () {
		parent::__construct();
		
		$this->loadDbCms();
	}
	
	public function saveAccount($params){
		$this->dbCms->select('id');
		$this->dbCms->from($this->tableUser);
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->where('userpass', $this->dbCms->escape_str(md5($params['old_userpass'])));
		
		$data = $this->dbCms->get();
		if($data->num_rows() != 0){
			$result = $this->updateAccount($params);	
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	private function updateAccount($params){
		$input_by = $this->session->userdata('profile');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		
		if(!empty($params['userpass'])){
			$update = array(
							'userpass' => $this->dbCms->escape_str(md5($params['userpass'])),
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
	
	public function getAccountData(){
		$profile = $this->session->userdata('profile');
		
		$this->dbCms->select('u.id, u.username, tu.nama_tipe_user, k.nama_karyawan');
		$this->dbCms->from($this->tableUser.' u');
		$this->dbCms->join($this->tableKaryawan.' k', 'k.id = u.karyawan_id');
		$this->dbCms->join($this->tableTipeUser.' tu', 'tu.id = u.tipe_user_id');	
		$this->dbCms->where('u.id', $this->dbCms->escape_str($profile['id']));
		$query = $this->dbCms->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['nama_tipe_user'] = $row['nama_tipe_user'];
				$result['username'] = $row['username'];
				$result['nama_karyawan'] = $row['nama_karyawan'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
}
?>
