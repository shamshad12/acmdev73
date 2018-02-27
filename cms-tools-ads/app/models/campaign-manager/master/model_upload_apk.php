<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_upload_apk extends MY_Model {

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

	}

	public function loadApk($params){
		$result = array();
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('uploadapk c');
		$this->dbAds->join('countries ct', 'c.id_country = ct.id');
		if(!empty($params['search']))
		$this->dbAds->where("(c.name LIKE '%".$params['search']."%' OR ct.name LIKE '%".$params['search']."%')");

		if(!empty($params['search_user']))
		$this->dbAds->where("c.entry_user", $params['search_user']);

		$this->dbAds->where('c.status != 2');

		$query = $this->dbAds->get();

		$result['total'] = 0;
		foreach($query->result_array() as $row)
		$result['total'] = $row['count'];

		$this->dbAds->select('c.id, c.name, c.apkurl, ct.name AS name_country, c.status, c.edit_user,c.edit_type,c.description,c.entry_user,c.entry_time,c.update_user,c.update_time,ct.id AS country_id');
		$this->dbAds->from('uploadapk c');
		$this->dbAds->join('countries ct', 'c.id_country = ct.id');
		if(!empty($params['search']))
		$this->dbAds->where("(c.name LIKE '%".$params['search']."%' OR ct.name LIKE '%".$params['search']."%')");
			
		if(!empty($params['search_user']))
		$this->dbAds->where("c.entry_user", $params['search_user']);

		$this->dbAds->where('c.status != 2');
			
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$this->dbAds->order_by('c.id', 'DESC');
		$query = $this->dbAds->get();

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 			= $row['id'];
				$result['rows'][$i]['name_country'] 	= $row['name_country'];
				$result['rows'][$i]['country_id']  = $row['country_id'];
				$result['rows'][$i]['apkurl'] 		= $row['apkurl'];
				$result['rows'][$i]['name'] 		= $row['name'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['status'] 		= ($row['status']==1)?'Active':'Inactive';
				if(isset($row['update_user']))
				{
					$username = $this->getCampaignUser($row['update_user']);
					$result['rows'][$i]['user_updated']  = isset($username['username'])?$username['username']:'';
					$result['rows'][$i]['user_updated_time']  = $row['update_time'];
				}
				else
				{
					$result['rows'][$i]['user_updated']  = 'N/A';
					$result['rows'][$i]['user_updated_time']  = '';
				}
				if(isset($row['entry_user']))
				{
					$username = $this->getCampaignUser($row['entry_user']);
					$result['rows'][$i]['user_enter']  = isset($username['username'])?$username['username']:'';
					$result['rows'][$i]['user_enter_time']  = $row['entry_time'];
				}
				else
				{
					$result['rows'][$i]['user_enter']  = 'anb';
					$result['rows'][$i]['user_enter_time']  = '';
				}
				$session_user=$this->dbAds->escape_str($this->profile['id']);
				$session_user_id = $this->getUserid($session_user);
				$result['rows'][$i]['session_user_typeid'] 	= $session_user_id['tipe_user_id'];
				$result['rows'][$i]['edit_type'] 	=     $row['edit_type'];
				$result['rows'][$i]['edit_user'] = $row['edit_user'];
				$result['rows'][$i]['login_user'] = $this->profile['id'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}

	public function loadApkSelect($params = array()){
		$result = array();

		$this->dbAds->select('id, name, apkurl');
		$this->dbAds->from('uploadapk');
		$this->dbAds->where('status', '1');

		if(!empty($params['id_country']))
		$this->dbAds->where('id_country', $params['id_country']);
		
		$this->dbAds->order_by("name","asc");
		$query = $this->dbAds->get();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['apkurl'] 	= $row['apkurl'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function changeStatus($params){
			
		if($this->dbAds->escape_str($params['edit_type']==0))
		{
			$edit_user='';
		}
		else{
			$edit_user=$this->dbAds->escape_str($this->profile['id']);
		}
		$update = array('edit_type'=> $this->dbAds->escape_str($params['edit_type']),'edit_user'=> $edit_user);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('templates', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;

	}
	public function checkeditTemplates($params){
		$result = array();
		$this->dbAds->select('edit_type,edit_user');
		$this->dbAds->from('templates');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['edit_type'] = $row['edit_type'];
				$result['rows'][$i]['edit_user'] = $row['edit_user'];
				$result['rows'][$i]['login_user'] = $this->profile['id'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}

	public function getApkData($params){
		$result = array();
		if(isset($params['id']) && trim($params['id'])!='')
		{
			$this->dbAds->select('id, name, description, apkurl, id_country, status');
			$this->dbAds->from('uploadapk');
			$this->dbAds->where('id',$this->dbAds->escape_str($params['id']));

			$query = $this->dbAds->get();
			if($query->num_rows() != 0){
				$result['count'] = true;
				foreach($query->result_array() as $row) {
					$result['id'] 			= $row['id'];
					$result['name'] 		= $row['name'];
					$result['id_country'] 	= $row['id_country'];
					$result['apkurl'] = $row['apkurl'];
					$result['description'] 	= $row['description'];
					$result['status'] 	= $row['status'];
				}
			}
			else {
				$result['count'] = false;
			}
		}
		else
		{
			$result['count'] = false;
			$result['msg'] = "Apk id not found";
		}
		return $result;
	}

	public function saveUploadApk($params){

		$result = array();
		if(isset($params['id']) && trim($params['id'])!='')
		{
			$result=$this->updateApkUpload($params);
		}
		else
		{
			$this->dbAds->select('COUNT(1) as exist_data');
			$this->dbAds->from('templates');
			$this->dbAds->where('name',$this->dbAds->escape_str($params['name']));

			$query = $this->dbAds->get();

			foreach($query->result_array() as $row)
			{
				if($row['exist_data'] !=0)
				{
					$result['errors_message'] = "Template Name you are using already added, so you can not use same name.";
					$result['duplicat_data'] =  true;
					return $result;
				}
			}
			$input_by = $this->session->userdata('id_user');
			$input_time = strtotime(date('Y-m-d H:i:s'));
			$update = array(
						'id_country' 	=> $this->dbAds->escape_str($params['id_country']),
						'name' 			=> $this->dbAds->escape_str($params['name']),
						'description' 	=> $this->dbAds->escape_str($params['description']),
						'apkurl' 	=> $params['apkurl'],
						'status' 		=> $this->dbAds->escape_str($params['status']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
			);
			$this->dbAds->insert('uploadapk', $update);
			//$result = array();
			if($this->dbAds->affected_rows()){
				$result['success'] = true;
				$result['id'] = $this->dbAds->insert_id();
				$add_message=$this->config->item('add_message');
				$add_message=str_replace("{SECTION}","APK",$add_message);
				$add_message=str_replace("{TITLE}",$params['name'],$add_message);
				$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
				$this->SaveLogdata($add_message,$params);

			} else {
				$result['success'] = false;
			}
		}
		return $result;
	}

	public function updateApkUpload($params)
	{
		$update = array(
	      'id_country'  => $this->dbAds->escape_str($params['id_country']),
	      'name'    => $this->dbAds->escape_str($params['name']),
	      'description'  => $this->dbAds->escape_str($params['description']),
	      'apkurl' 	=> $params['apkurl'],
	      'status'   => $this->dbAds->escape_str($params['status']),
	      'update_user' => $this->session->userdata('id_user'),
	      'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s')),
	      'edit_type'  => '0',
	      'edit_user'  => ''    
      	);
	    $this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
	    $this->dbAds->update('uploadapk', $update);

      	$result = array();
      	if($this->dbAds->affected_rows()){
      		$result['success'] 	= true;
      		$result['id']		= $params['id'];
      		$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","APK",$add_message);
			$add_message=str_replace("{TITLE}",$params['name'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
      	} else {
      		$result['success'] = false;
      	}
      	return $result;
	}


	public function deleteApk($params){

		$result = array();
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('uploadapk');

		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function getUserList(){
		$this->db->select('id, username');
		$this->db->from('campaign_cms.cms_user');
		$this->db->where('status', 1);
		$this->db->order_by('username', 'ASC');
		$query = $this->db->get();
		$result = array();
		$i=0;
		foreach($query->result_array() as $row){

			$result['rows'][$i]['id'] = $row['id'];
			$result['rows'][$i]['username'] = $row['username'];
			$i++;
		}

		return $result;
	}

}
?>
