<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_login extends MY_Model {

	public function __construct () {
		parent::__construct();
		
		$this->loadDbCms();
		$this->loadDbAds();
	}

	public function auth($_username){
		$this->dbCms->select('u.id, k.nama_karyawan, k.store_id, tu.nama_tipe_user, u.karyawan_id, u.tipe_user_id, u.username, u.userpass, u.status, tu.isAdmin, k.id_country');
		$this->dbCms->from($this->tableUser.' u');
		$this->dbCms->join($this->tableTipeUser.' tu', 'u.tipe_user_id = tu.id');
		$this->dbCms->join($this->tableKaryawan.' k', 'u.karyawan_id = k.id');
		$this->dbCms->where('u.username', $_username);
		$query = $this->dbCms->get();		
		$result = array();
		if($query->num_rows() != 0){
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['karyawan_id'] = $row['karyawan_id'];
				$result['store_id'] = $row['store_id'];
				$result['tipe_user_id'] = $row['tipe_user_id'];
				$result['nama_karyawan'] = $row['nama_karyawan'];
				$result['nama_tipe_user'] = $row['nama_tipe_user'];
				$result['username'] = $row['username'];
				$result['userpass'] = $row['userpass'];
				$result['status'] = $row['status'];
				$result['isAdmin'] = $row['isAdmin'];
				$result['isPartner'] = !empty($row['store_id'])?true:false;
				$result['id_country'] = $row['id_country'];
			}
			$this->SaveLogdata('User Login [IP : '.$_SERVER['REMOTE_ADDR'].' , id:'.$row['id'].' ]',$result,$row['nama_karyawan'],$row['username']);
		}
		return $result;
	}
	
	public function getPrivilege ($tipe_user_id)
    {
		$this->dbCms->select('p.status, pm.id_privilege, pm.id_menu, pm.access, m.url, m.name, m.description, m.icon, m.parent, m.sort, p.default_menu');
		$this->dbCms->select('(SELECT count(1) FROM cms_menu WHERE parent=pm.id_menu) AS child');
		$this->dbCms->from($this->tablePrivileges.' p');
		$this->dbCms->join($this->tablePrivilegesMenu.' pm', 'p.id = pm.id_privilege');
		$this->dbCms->join($this->tableMenu.' m', 'm.id = pm.id_menu');
		$this->dbCms->where('m.status', '1');
		$this->dbCms->where('p.tipe_user_id', $tipe_user_id);
		$this->dbCms->order_by('m.parent, m.sort', 'ASC');
		$query = $this->dbCms->get();
		$result = array();
		if($query->num_rows() != 0){
			$i = 0;
			$row['default_url'] = "";
			foreach($query->result_array() as $row) {
				$result['status'] 					= $row['status'];
				$result['id_privilege'] 			= $row['id_privilege'];
				/*$result['rows'][$i]['id_menu'] 		= $row['id_menu'];
				$result['rows'][$i]['access'] 		= $row['access'];
				$result['rows'][$i]['url'] 			= $row['url'];
				$result['rows'][$i]['name'] 		= $row['name'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['icon'] 		= $row['icon'];
				$result['rows'][$i]['parent'] 		= $row['parent'];
				$result['rows'][$i]['child'] 		= $row['child'];
				$result['rows'][$i]['sort'] 		= $row['sort'];*/
				
				$result['menu'][$i] = $row['id_menu'];
				
				if($row['default_menu'] == $row['id_menu'])
					$result['default_url']	= $row['url'];
				$i++;
			}
		}
		return $result;
	}

	public function partnerPermissions($user_id){
		$this->dbCms->select('partner_access_level');
		$this->dbCms->from('partner_permissions');
		$this->dbCms->where('user_id', $user_id);
		$query = $this->dbCms->get();
		$result = "";
		if($query->num_rows() != 0){
			$result = $query->result_array();
			$result = $result[0]['partner_access_level'];
		}
		return $result;
	}
	
	public function loadExpireDomain()
	{
  $this->dbAds->select('name, description',false);
  $this->dbAds->from('domains');
  $this->dbAds->where('description<=DATE_ADD(CURDATE(), INTERVAL 7 DAY) and status=1');
  $query = $this->dbAds->get();
  $result = array();
  $i=0;
  foreach($query->result_array() as $row)
  {
   $result[$i]['name'] =  $row['name'];
   $result[$i]['description'] =  $row['description'];
   //description is date
   $i++;
  
  }
  return $result;
  
 }
	
}
?>
