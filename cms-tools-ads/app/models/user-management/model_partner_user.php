<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_partner_user extends MY_Model {

	public function __construct () {
		parent::__construct();
		$this->loadDbAds();
		$this->loadDbCms();
	}

	public function loadUser($params){
		$result = array();

		$this->dbCms->select(' COUNT(1) AS count ');
		$this->dbCms->from($this->tableUser.' u');
		$this->dbCms->join($this->tableKaryawan.' k', 'k.id = u.karyawan_id');
		$this->dbCms->join($this->tableTipeUser.' tu', 'tu.id = u.tipe_user_id');
		$this->dbCms->where('k.status', '1');
		$this->dbCms->where('u.tipe_user_id','11');
		if(!empty($params['search']))
		$this->dbCms->where("(u.id LIKE '%".$params['search']."%' OR k.nama_karyawan LIKE '%".$params['search']."%' OR tu.nama_tipe_user LIKE '%".$params['search']."%' OR u.username LIKE '%".$params['search']."%')");

		$query = $this->dbCms->get();
		$result['total'] = 0;
		foreach($query->result_array() as $row)
		$result['total'] = $row['count'];

		$this->dbCms->select('u.id, k.nama_karyawan, tu.nama_tipe_user, u.username, u.status, pp.partner_access_level');
		$this->dbCms->from($this->tableUser.' u');
		$this->dbCms->join($this->tableKaryawan.' k', 'k.id = u.karyawan_id');
		$this->dbCms->join($this->tableTipeUser.' tu', 'tu.id = u.tipe_user_id');
		$this->dbCms->join('partner_permissions pp','pp.user_id=u.id','LEFT');
		$this->dbCms->where('k.status', '1');
		$this->dbCms->where('u.tipe_user_id','11');
		if(!empty($params['search']))
		$this->dbCms->where("(u.id LIKE '%".$params['search']."%' OR k.nama_karyawan LIKE '%".$params['search']."%' OR tu.nama_tipe_user LIKE '%".$params['search']."%' OR u.username LIKE '%".$params['search']."%')");

		$this->dbCms->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbCms->get();
		//echo $this->dbCms->last_query();die;			
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] = $row['id'];
				$result['rows'][$i]['nama_karyawan'] = $row['nama_karyawan'];
				$result['rows'][$i]['nama_tipe_user'] = $row['nama_tipe_user'];
				$result['rows'][$i]['username'] = $row['username'];
				$result['rows'][$i]['status'] = ($row['status']==1)?'Active':'Suspend';
				$partner_permissions = json_decode($row['partner_access_level']);
				$result['rows'][$i]['partner_name'] = $partner_permissions->partner_name;
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}

	public function saveUser($params){

		$this->dbCms->select('id');
		$this->dbCms->from('partner_permissions');
		$this->dbCms->where('user_id', $this->dbCms->escape_str($params['id']));
		$data = $this->dbCms->get();
		if($data->num_rows() != 0){
			$result = $this->updateUser($params);
		} else {
			$result = $this->insertUser($params);
		}
		return $result;
	}

	private function updateUser($params){
		$update = array(
                                'partner_access_level' => json_encode($this->dbCms->escape_str($params))
		);

		$this->dbCms->where('user_id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->update('partner_permissions', $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	private function insertUser($params){

		$update = array(
                                'user_id'=>$this->dbCms->escape_str($params['id']),
                                'partner_access_level' => json_encode($this->dbCms->escape_str($params))
		);

		$this->dbCms->insert('partner_permissions', $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	/*public function deleteUser($params){
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->delete($this->tableUser);
		$result = array();
		if($this->dbCms->affected_rows()){
		$result['success'] = true;
		} else {
		$result['success'] = false;
		}
		return $result;
		}*/

	public function getUserData($params){

		$this->dbCms->select('u.id,pp.user_id, pp.partner_access_level,u.username');
		$this->dbCms->from('partner_permissions pp');
		$this->dbCms->join('cms_user u','pp.user_id=u.id','right');
		$this->dbCms->where('u.id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->where('u.status', '1');
		$query = $this->dbCms->get();

		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['username'] = $row['username'];
				$result['partner_access_level'] = json_decode($row['partner_access_level'],true);
				//$result['status'] = $row['status'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
			
		return $result;
	}
	public function getKeywordList($params)
	{
		if(!empty($params['shortcode']))
		{
			$this->dbAds->select('ps.keyword');
			$this->dbAds->from('partners_services ps');
			$this->dbAds->group_by('ps.keyword');
			$this->dbAds->order_by('ps.keyword','ASC');
			$this->dbAds->where_in('id_shortcode', $params['shortcode'] );
			$query = $this->dbAds->get();

			$result = array();

			if($query->num_rows() != 0){
				$result['count'] = true;
				$i=0;
				foreach($query->result_array() as $row) {
					$result['row'][$i]['keyword'] = $row['keyword'];
					$i++;
				}
			} else {
				$result['count'] = false;
			}
		}
		else
		$result['count'] = false;

		return $result;

	}
public function loadShortcode($params){
		$this->dbAds->select('id, code');
		$this->dbAds->from('shortcodes');
		$this->dbAds->where_in('partner_id', $this->dbAds->escape_str($params['partner_ids']));
		$this->dbAds->where('status', 1);
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['success'] = true;
			foreach($query->result_array() as $row) {
				$result[$i]['id'] = $row['id'];
				$result[$i]['name'] = $row['code'];
				$i++;
			}

		}
		else {
			$result['success'] = false;
		}

		return $result;
	}	

	public function loadDomain($params){ 
		$this->dbAds->select('d.id,d.name,d.code,con.code as country_code,con.name as country_name');
		$this->dbAds->from('domains d');
		$this->dbAds->join('countries con','d.id_country=con.id','left');
		if(isset($params['id'])){
			$this->dbAds->where_in('d.id', $this->dbAds->escape_str($params['id']));
		}
		if(isset($params['id_country'])){
			$this->dbAds->where_in('d.id_country', $this->dbAds->escape_str($params['id_country']));
		}		
		$this->dbAds->where('d.status', 1);
		$this->dbAds->order_by("con.name","asc");
		$query = $this->dbAds->get();
		// print $this->dbAds->last_query();	
		// exit();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['success'] = true;
			foreach($query->result_array() as $row) {
				$result[$i]['id'] = $row['id'];
				$result[$i]['name'] = $row['name'];				
				$result[$i]['code'] = $row['code'];				
				$result[$i]['country_name'] = $row['country_name'];				
				$result[$i]['country_code'] = $row['country_code'];				
				$i++;
			}
		}
		else {
			$result['success'] = false;
		}

		return $result;
	}
	
	public function loadAdsPublisher($params){
		$this->dbAds->select('ads.id,ads.name,ads.code,ads.ads_type,con.code as country_code,con.name as country_name');
		$this->dbAds->from('ads_publishers ads');
		$this->dbAds->join('countries con','ads.id_country=con.id','left');
		if(isset($params['id'])){
			$this->dbAds->where_in('ads.id', $this->dbAds->escape_str($params['id']));
		}
		if(isset($params['id_country'])){
			$this->dbAds->where_in('ads.id_country', $this->dbAds->escape_str($params['id_country']));
		}
		$this->dbAds->where('ads.status', 1);
		$this->dbAds->order_by("con.name","asc");		
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['success'] = true;
			foreach($query->result_array() as $row) {
				$result[$i]['id'] = $row['id'];
				$result[$i]['name'] = $row['name'];				
				$result[$i]['code'] = $row['code'];				
				$result[$i]['country_name'] = $row['country_name'];				
				$result[$i]['country_code'] = $row['country_code'];		
				$i++;
			}

		}
		else {
			$result['success'] = false;
		}

		return $result;
	}
	
}
?>
