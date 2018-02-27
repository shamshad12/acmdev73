<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_shortcodes extends MY_Model {

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

	}

	public function loadShortcodes($params){
		$result = array();

		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('shortcodes ps');
		$this->dbAds->join('partners p', 'ps.partner_id = p.id');
		if(!empty($params['search']))
		$this->dbAds->where("(ps.id LIKE '%".$params['search']."%' OR ps.code LIKE '%".$params['search']."%' OR ps.description LIKE '%".$params['search']."%')");

		if(!empty($params['partner']))
		$this->dbAds->where("ps.partner_id", $params['partner']);
		$this->dbAds->where("p.status", 1);

		$query = $this->dbAds->get();

		$result['total'] = 0;
		foreach($query->result_array() as $row)
		$result['total'] = $row['count'];

		$this->dbAds->select('ps.id, ps.code,ps.price_code, ps.status, ps.description,p.name AS partner_name,ps.entry_user,ps.entry_time,ps.update_user,ps.update_time');
		$this->dbAds->from('shortcodes ps');
		$this->dbAds->join('partners p', 'ps.partner_id = p.id');
		if(!empty($params['search']))
		$this->dbAds->where("(ps.id LIKE '%".$params['search']."%' OR ps.code LIKE '%".$params['search']."%' OR ps.description LIKE '%".$params['search']."%')");
		if(!empty($params['partner']))
		$this->dbAds->where("ps.partner_id", $params['partner']);
		$this->dbAds->where("p.status", 1);
		$this->dbAds->order_by('CAST(ps.code AS SIGNED)', 'asc');
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				$result['rows'][$i]['code'] 	= $row['code'];
				$result['rows'][$i]['price_code'] 	= $row['price_code'];
				$result['rows'][$i]['partner_name'] = $row['partner_name'];
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

	public function loadShortcodesSelect(){
		$result = array();

		$this->dbAds->select('op.id, op.code, op.description,p.name AS partner_name');
		$this->dbAds->from('shortcodes op');
		$this->dbAds->join('partners p', 'op.partner_id = p.id');
		$this->dbAds->where('op.status', '1');
		$this->dbAds->order_by('CAST(op.code AS SIGNED)', 'asc');
        $this->dbAds->group_by('op.code');
		$query = $this->dbAds->get();

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['code'] 	= $row['code'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['partner_name'] 	= $row['partner_name'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function saveShortcodes($params){
		$this->dbAds->select('id');
		$this->dbAds->from('shortcodes');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateShortcodes($params);
		} else {
			$result = $this->insertShortcodes($params);
		}
		return $result;
	}

	private function updateShortcodes($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'code' 		=> $this->dbAds->escape_str($params['code']),
                         'price_code' 		=> $this->dbAds->escape_str($params['price_code']),
						 'partner_id' 		=> $this->dbAds->escape_str($params['partner_id']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' => $this->dbAds->escape_str($params['description']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
		);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('shortcodes', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Shortcode",$update_message);
			$update_message=str_replace("{TITLE}",$params['code'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	private function insertShortcodes($params){

		 
		$result = array();
		$this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('shortcodes');
		$this->dbAds->where('code', $params['code']);
		$this->dbAds->where('partner_id', $params['partner_id']);
		 
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();die('lop');
		foreach($query->result_array() as $row)
		{
			if($row[exist_data] !=0)
			{
				$result['errors_message'] = "Partner and Shortcode Code already added, so use different code.";
				$result['duplicat_data'] =  true;
				return $result;
			}
		}

		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'price_code' 		=> $this->dbAds->escape_str($params['price_code']),
                        'partner_id' 		=> $this->dbAds->escape_str($params['partner_id']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' => $this->dbAds->escape_str($params['description']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
		);
		$this->dbAds->insert('shortcodes', $update);
		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Shortcode",$add_message);
			$add_message=str_replace("{TITLE}",$params['code'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}

		return $result;
	}

	public function deleteShortcodes($params){
		$result = array();
		$isExist=$this->CheckExistData('partners_services','id_shortcode',$params['id']);
		if($isExist['duplicat_data']){
			$result['errors_message'] =   $isExist['errors_message'];
			$result['duplicat_data'] =  $isExist['duplicat_data'];
			return $result;
			exit;
		}
		$service_data=$this->getShortcodesData($params);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('shortcodes');
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Shortcode",$update_message);
			$update_message=str_replace("{TITLE}",$service_data['code'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function getShortcodesData($params){
		$this->dbAds->select('id, description,code,price_code,status,partner_id');
		$this->dbAds->from('shortcodes');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['description'] = $row['description'];
				$result['code'] = $row['code'];
				$result['price_code'] = $row['price_code'];
				$result['status'] = $row['status'];
				$result['partner_id'] = $row['partner_id'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
}
?>
