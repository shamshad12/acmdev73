<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_privileges_menu extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbCms();
	}
	
	public function loadPrivilegesMenu($params){		
		$result = array();
				
		$this->dbCms->select('pm.id_menu, pm.id_privilege, p.description, m.name, m.url, m.level, m.status, pm.access');
		$this->dbCms->select('(SELECT COUNT(1) FROM cms_menu WHERE parent=pm.id_menu) AS count');
		$this->dbCms->from($this->tablePrivilegesMenu.' pm');
		$this->dbCms->join($this->tableMenu.' m', 'pm.id_menu = m.id');
		$this->dbCms->join($this->tablePrivileges.' p', 'pm.id_privilege = p.id');
		if(!empty($params['privilege']))
			$this->dbCms->where('pm.id_privilege', $params['privilege']);
		$this->dbCms->order_by('pm.id_privilege', 'ASC');
	
		$this->dbCms->where('p.status',1);

		$query = $this->dbCms->get();
		
		$this->dbCms->group_by('pm.id_menu');
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id_menu'] 		= $row['id_menu'];
				$result['rows'][$i]['id_privilege'] = $row['id_privilege'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['name'] 		= $row['name'];
				$result['rows'][$i]['url'] 			= $row['url'];
				$result['rows'][$i]['level'] 		= $this->getLevel($row['level']);
				$result['rows'][$i]['status'] 		= ($row['status'])?'Active':'Inactive';
				$result['rows'][$i]['access'] 		= $this->getAccess($row['access']);
				$result['rows'][$i]['child'] 		= $row['count'];
				if($row['count'] != 0){
					$result['child'][$row['id_privilege'].$row['id_menu']] = $this->loadPrivilegesMenuChild($row['id_menu'], $row['id_privilege']);
				} else {
					$result['child'][$row['id_privilege'].$row['id_menu']] = false;
				}
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	private function loadPrivilegesMenuChild($parent, $privilege = ''){
		$result = array();
				
		$this->dbCms->select('pm.id_menu, pm.id_privilege, p.description, m.name, m.url, m.level, m.status, pm.access');
		$this->dbCms->select('(SELECT COUNT(1) FROM cms_menu WHERE parent=pm.id_menu) AS count');
		$this->dbCms->from($this->tablePrivilegesMenu.' pm');
		$this->dbCms->join($this->tableMenu.' m', 'pm.id_menu = m.id');
		$this->dbCms->join($this->tablePrivileges.' p', 'pm.id_privilege = p.id');
		if(!empty($privilege))
			$this->dbCms->where('pm.id_privilege', $privilege);
		$this->dbCms->where('m.parent', $parent);	
		
		$this->dbCms->group_by('pm.id_menu');
		
		$this->dbCms->where('p.status',1);
	
		$query = $this->dbCms->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id_menu'] 		= $row['id_menu'];
				$result['rows'][$i]['id_privilege'] = $row['id_privilege'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['name'] 		= $row['name'];
				$result['rows'][$i]['url'] 			= $row['url'];
				$result['rows'][$i]['level'] 		= $this->getLevel($row['level']);
				$result['rows'][$i]['status'] 		= ($row['status'])?'Active':'Inactive';
				$result['rows'][$i]['access'] 		= $this->getAccess($row['access']);
				if($row['count'] != 0){
					$result['child'][$row['id_privilege'].$row['id_menu']] = $this->loadPrivilegesMenuChild($row['id_menu'], $row['id_privilege']);
				} else {
					$result['child'][$row['id_privilege'].$row['id_menu']] = false;
				}
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadMenuSelect(){
		$this->dbCms->select('id, name');
		$this->dbCms->from('cms_menu');
		$query = $this->dbCms->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id_menu'] 		= $row['id_'];
				$result['rows'][$i]['name'] 		= $row['name'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
	}
	
	public function setAccess($params){		
		$this->dbCms->select('access');
		$this->dbCms->from($this->tablePrivilegesMenu);
		$this->dbCms->where('id_privilege', $params['privilege']);
		$this->dbCms->where('id_menu', $params['menu']);
		$query = $this->dbCms->get();
		
		if($query->num_rows() == 0)
			return false;
			
		foreach($query->result_array() as $row)
			$access = $row['access'];
					
		$n = (int) $params['access'] & (int) $access;
		
		$newAccess = ($n==0)?$access+$params['access']:$access-$params['access'];
			
		$query = "UPDATE ".$this->tablePrivilegesMenu." SET access=".$newAccess." WHERE id_menu=".$params['menu']." AND id_privilege=".$params['privilege'];
		
		$this->dbCms->query($query);
		
		return true;
	} 
	
	public function savePrivilegesMenu($params){
		if(!empty($params['id'])){
			$this->dbCms->select('id');
			$this->dbCms->from($this->tableMenu);
			$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
			$data = $this->dbCms->get();
						
			if($data->num_rows() != 0){
				$result = $this->updatePrivilegesMenu($params);	
			} else {
				$result = $this->insertPrivilegesMenu($params);
			}
		} else {
			$result = $this->insertPrivilegesMenu($params);
		}
		return $result;
	}
	
	private function updatePrivilegesMenu($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = date('Y-m-d H:i:s');
		
		$update = array(
						'url' => $this->dbCms->escape_str($params['url']),
						'name' 	=> $this->dbCms->escape_str($params['name']),
						'description' 	=> $this->dbCms->escape_str($params['description']),
						'icon' 	=> $this->dbCms->escape_str($params['icon']),
						'parent' => $this->dbCms->escape_str($params['parent']),
						'status' 		=> $this->dbCms->escape_str($params['status']),
						'update_by' 	=> $this->dbCms->escape_str($input_by),
						'update_time' 	=> $this->dbCms->escape_str($input_time)
					   );
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$this->dbCms->update($this->tableMenu, $update);
		$result = array();
		if($this->dbCms->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertPrivilegesMenu($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = date('Y-m-d H:i:s');
		
		#Get Last Sort
		$select = "SELECT (SELECT COUNT(1) FROM ".$this->tableMenu." WHERE parent='".$params['parent']."') AS sort, level FROM ".$this->tableMenu." WHERE id='".$params['parent']."' ORDER BY sort DESC LIMIT 1";
		$query = $this->dbCms->query($select);
		$sort = 0;
		$level = 1;
		if($query->num_rows() != 0){
			foreach($query->result_array() as $row){
				$sort = $row['sort'];
				$level = $row['level']+1;
			}
		}
		
		$insert = array(
						'url' => $this->dbCms->escape_str($params['url']),
						'name' => $this->dbCms->escape_str($params['name']),
						'description' => $this->dbCms->escape_str($params['description']),
						'icon' => $this->dbCms->escape_str($params['icon']),
						'parent' => $this->dbCms->escape_str($params['parent']),
						'sort' => $this->dbCms->escape_str($sort+1),
						'level' => $this->dbCms->escape_str($level),
						'status' => $this->dbCms->escape_str($params['status']),
						'created_by' => $this->dbCms->escape_str($input_by),
						'created_time' => $this->dbCms->escape_str($input_time)
					   );
		$this->dbCms->insert('cms_menu', $insert);
		
		$idMenu = $this->dbCms->insert_id();
		
		$this->dbCms->select('id');
		$this->dbCms->from($this->tablePrivileges);
		$query = $this->dbCms->get();
		
		$i=0;
		if($query->num_rows() != 0){
			foreach($query->result_array() as $row) {
				$insertPM = array(
								'id_privilege' => $this->dbCms->escape_str($row['id']),
								'id_menu' => $this->dbCms->escape_str($idMenu),
								'access' => $this->dbCms->escape_str('0'),
								'status' => $this->dbCms->escape_str('1'),
								'created_by' => $this->dbCms->escape_str($input_by),
								'created_time' => $this->dbCms->escape_str($input_time)
							   );
				$this->dbCms->insert('cms_privilege_menu', $insertPM);
			}
		}		
		
		$result = array();
		
		$result['success'] = ($this->dbCms->affected_rows())?true:false;
		
		return $result;
	}
	
	public function deletePrivilegesMenu($params){
		$this->dbCms->where('id_menu', $this->dbCms->escape_str($params['id_menu']));
		$this->dbCms->delete('cms_privilege_menu');
		
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id_menu']));
		$this->dbCms->delete('cms_menu');
		
		$result = array();
		$result['success'] = ($this->dbCms->affected_rows())?true:false;
		return $result;
	}
	
	public function getPrivilegesMenuData($params){		
		$this->dbCms->select('id, url, name, description, icon, status, parent, sort, level');
		$this->dbCms->from($this->tableMenu);
		$this->dbCms->where('id', $this->dbCms->escape_str($params['id']));
		$query = $this->dbCms->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] 		= $row['id'];
				$result['url'] 		= $row['url'];
				$result['name'] 	= $row['name'];
				$result['description'] = $row['description'];
				$result['icon'] 	= $row['icon'];
				$result['status'] 	= $row['status'];
				$result['parent'] 	= $row['parent'];
				$result['sort'] 	= $row['sort'];
				$result['level'] 	= $row['level'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
	
	private function getAccess($access){
		$result = array(
						'view' => (!(1&$access) != 0)?'grey':'blue',
						'save' => (!(2&$access) != 0)?'grey':'blue',
						'edit' => (!(4&$access) != 0)?'grey':'blue',
						'delete' => (!(8&$access) != 0)?'grey':'blue'
					   );
		return $result;
	}
	
	private function getLevel($level){
		switch ($level) {
				case 1:
					$level = '';
					break;
				case 2:
					$level = '&nbsp;&nbsp;- ';
					break;
				case 3:
					$level = '&nbsp;&nbsp;&nbsp;&nbsp;-- ';
					break;
				case 4:
					$level = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--- ';
					break;
				case 5:
					$level = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---- ';
					break;
				default:
					$level = '';
					break;
		}	
		return $level;
	}
}
?>
