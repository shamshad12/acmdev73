<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_banners extends MY_Model {

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

	}

	public function loadBanners($params){
		$result = array();

		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('banners');
		if(!empty($params['search']))
		$this->dbAds->where("(id LIKE '%".$params['search']."%' OR name LIKE '%".$params['search']."%' OR url_ori LIKE '%".$params['search']."%' OR path_ori LIKE '%".$params['search']."%')");

		$query = $this->dbAds->get();

		$result['total'] = 0;
		foreach($query->result_array() as $row)
		$result['total'] = $row['count'];

		$this->dbAds->select('id, name, description, url_ori, url_thumb, path_ori, path_thumb, status,entry_user,entry_time,update_user,update_time');
		$this->dbAds->from('banners');
		if(!empty($params['search']))
		$this->dbAds->where("(id LIKE '%".$params['search']."%' OR name LIKE '%".$params['search']."%' OR url_ori LIKE '%".$params['search']."%' OR path_ori LIKE '%".$params['search']."%')");
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$this->dbAds->order_by('id', 'DESC');
		$query = $this->dbAds->get();

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['path_thumb'] = $row['path_thumb'];
				$result['rows'][$i]['path_ori']  = $row['path_ori'];
				$result['rows'][$i]['url_thumb'] = $row['url_thumb'];
				$result['rows'][$i]['url_ori'] 	 = $row['url_ori'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
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
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function loadBannersSelect(){
		$result = array();

		$this->dbAds->select('id, name, url_thumb, description');
		$this->dbAds->from('banners');
		$this->dbAds->where('status', '1');
		$query = $this->dbAds->get();

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['url_thumb'] 	= $row['url_thumb'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function saveBanners($params){
		$this->dbAds->select('id');
		$this->dbAds->from('banners');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateBanners($params);
		} else {
			$result = $this->insertBanners($params);
		}
		return $result;
	}

	private function updateBanners($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'url_thumb' => $this->dbAds->escape_str($params['url_thumb']),
						'url_ori' => $this->dbAds->escape_str($params['url_ori']),
						'path_thumb' 		=> $this->dbAds->escape_str($params['path_thumb']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'path_ori' 	=> $this->dbAds->escape_str($params['path_ori']),
						'description' => $this->dbAds->escape_str($params['description']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
		);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('banners', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Banner",$update_message);
			$update_message=str_replace("{TITLE}",$params['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	private function insertBanners($params){

		$result = array();
		$this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('banners');
		$this->dbAds->where('name',$this->dbAds->escape_str($params['name']));
			
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();die('lop');
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "This bannner name is already used, please use different one";
				$result['duplicat_data'] =  true;
				return $result;
			}
		}

		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'url_thumb' => $this->dbAds->escape_str($params['url_thumb']),
						'url_ori' => $this->dbAds->escape_str($params['url_ori']),
						'path_thumb' 		=> $this->dbAds->escape_str($params['path_thumb']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'path_ori' 	=> $this->dbAds->escape_str($params['path_ori']),
						'description' => $this->dbAds->escape_str($params['description']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
		);
		$this->dbAds->insert('banners', $update);
		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$result['id'] = $this->dbAds->insert_id();
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Banner",$add_message);
			$add_message=str_replace("{TITLE}",$params['name'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function deleteBanners($params){
		$service_data=$this->getBannersData($params);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('banners');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Banner",$update_message);
			$update_message=str_replace("{TITLE}",$service_data['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function getBannersData($params){
		$this->dbAds->select('id, name, url_thumb, url_ori, description,path_thumb,status, path_ori');
		$this->dbAds->from('banners');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['name'] = $row['name'];
				$result['url_thumb'] = $row['url_thumb'];
				$result['url_ori'] = $row['url_ori'];
				$result['description'] = $row['description'];
				$result['path_thumb'] = $row['path_thumb'];
				$result['path_ori'] = $row['path_ori'];
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
