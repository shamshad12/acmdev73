<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_ads_publishers extends MY_Model {

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

	}

	public function loadAds_Publishers($params){
		$result = array();

		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('ads_publishers c');
		$this->dbAds->join('countries co', 'c.id_country = co.id');
		if(!empty($params['search']))
			$this->dbAds->where("(c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR co.name LIKE '%".$params['search']."%' OR c.ads_type LIKE '%".$params['search']."%' OR c.utc_timezone LIKE '%".$params['search']."%')");
		$this->dbAds->group_by('c.code');
		$query = $this->dbAds->get();

		$result['total'] = 0;
		if($query->num_rows() != 0)
			$result['total'] = $query->num_rows();

		$this->dbAds->select('c.name, GROUP_CONCAT(co.name) as co_name, c.code, c.status, c.utc_timezone, c.description, c.ads_type', false);
		$this->dbAds->from('ads_publishers c');
		$this->dbAds->join('countries co', 'c.id_country = co.id');
		if(!empty($params['search']))
			$this->dbAds->where("(c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR co.name LIKE '%".$params['search']."%' OR c.ads_type LIKE '%".$params['search']."%' OR c.utc_timezone LIKE '%".$params['search']."%')");
		$this->dbAds->group_by('c.code');
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['co_name'] 	= $row['co_name'];
				$result['rows'][$i]['utc_timezone'] = ($row['utc_timezone'] < 10)?'UTC +0'.$row['utc_timezone'].'.00':'UTC +'.$row['utc_timezone'].'.00';
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				$result['rows'][$i]['code'] 	= $row['code'];
				$result['rows'][$i]['ads_type'] = $row['ads_type'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function loadAds_PublishersSelect($params = array()){
		$result = array();

		$this->dbAds->select('id, name, code, id_country, description');
		$this->dbAds->from('ads_publishers');
		//if user is partner
		if($this->session->userdata['profile']['isPartner']){
			$partner_permissions = json_decode($this->session->userdata('partner_permissions'),true);
			if($partner_permissions['ads_publisher']){
				$this->dbAds->where_in('id', $this->dbAds->escape_str($partner_permissions['ads_publisher']));
			}
		}
		$this->dbAds->where('status', '1');
		if(isset($params['id_country']))
		$this->dbAds->where('id_country', $params['id_country']);
		$this->dbAds->order_by("name","asc");
		$query = $this->dbAds->get();

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$code = $this->getCountryCode($row['id_country']);

				$result['rows'][$i]['id'] 		= $row['id'];
				if($code['code'])
				$result['rows'][$i]['name'] 	= $row['name'].'('.$row['code'].')-'.$code['code'];//$row['name'].'-'.$code['code'];
				else
				$result['rows'][$i]['name'] 	= $row['name'];
				$result['rows'][$i]['id_country'] 	= $row['id_country'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function getCountryCode($id)
	{
		$this->dbAds->select('code');
		$this->dbAds->from('countries');

		$this->dbAds->where('status', '1');
		$this->dbAds->where('id', $id);

		$query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die('kl');
		$result = array();
		foreach($query->result_array() as $row) {
			$result['code'] 		= $row['code'];
		}
		return $result;
	}

	/*public function saveAds_Publishers($params){
		$this->dbAds->select('id');
		$this->dbAds->from('ads_publishers');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateAds_Publishers($params);
		} else {
			$result = $this->insertAds_Publishers($params);
		}
		return $result;
	}*/

	public function saveAds_Publishers($params){
		$result = array(); 
		if($params['id'] == '' || $params['id'] == 0)
		{
			$checkCode=$this->checkCode('ads_publishers',$params['code']);
			if($checkCode['duplicat_data'])
			return $checkCode;
			
		}
		$countries = $params['id_country'];
		$db_countries = array();

		$this->dbAds->select('id_country');
		$this->dbAds->from('ads_publishers');
		$this->dbAds->where('name', $this->dbAds->escape_str($params['name']));
		$data = $this->dbAds->get();

		if($data->num_rows() != 0)
		{
			foreach ($data->result_array() as $r)
				$db_countries[] = $r['id_country'];
		}

		if(!empty($db_countries))
		{
			$del_countries = array_diff($db_countries, $countries);
			if(!empty($del_countries))
			{
				foreach ($del_countries as $dc) {
					$del_params = array();
					$del_params['code'] = $params['code'];
					$del_params['id_country'] = $dc;
					$this->deleteAds_Publishers($del_params);
				}
			}
		}

		foreach($countries as $c)
		{

			$this->dbAds->select('id');
			$this->dbAds->from('ads_publishers');
			$this->dbAds->where('code', $this->dbAds->escape_str($params['code']));
			$this->dbAds->where('id_country', $this->dbAds->escape_str($c));
			$data = $this->dbAds->get();
			$res = $data->result_array();

			$params['id'] = $res[0]['id'];
			$params['id_country'] = $c;
			
			if($data->num_rows() != 0){
				$result = $this->updateAds_Publishers($params);

			} else {
				$result = $this->insertAds_Publishers($params);
			}
		}

		return $result;
	}


	private function updateAds_Publishers($params){

		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'id_country' => $this->dbAds->escape_str($params['id_country']),
						'utc_timezone' => $this->dbAds->escape_str($params['utc_timezone']),
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'ads_type' 	=> $this->dbAds->escape_str($params['ads_type']),
						'trans_id_params' 	=> $this->dbAds->escape_str($params['trans_id_params']),
						'affiliate_params' 	=> $this->dbAds->escape_str($params['affiliate_params']),
						'affiliate_values' 	=> $this->dbAds->escape_str($params['affiliate_values']),
						'url_confirm' 	=> $this->dbAds->escape_str($params['url_confirm']),
						'loading_url' 	=> $this->dbAds->escape_str($params['loading_url']),
						'updation_url' 	=> $this->dbAds->escape_str($params['updation_url']),
						'description' => $this->dbAds->escape_str($params['description']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
		);

		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('ads_publishers', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Vendor",$update_message);
			$update_message=str_replace("{TITLE}",$params['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	private function insertAds_Publishers($params){

		$result = array();
		$this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('ads_publishers');
		//$this->dbAds->where("id_country = '".$params['id_country']."' AND (code = '".$params['code']."'OR name = '".$params['name']."')");
		$this->dbAds->where("id_country = '".$params['id_country']."' AND (code = '".$params['code']."')");
			
		$query = $this->dbAds->get();

		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "For this country this publisher Code is already added, so use different code.";
				$result['duplicat_data'] =  true;
				return $result;
			}
		}
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'id_country' => $this->dbAds->escape_str($params['id_country']),
						'utc_timezone' => $this->dbAds->escape_str($params['utc_timezone']),
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'ads_type' 	=> $this->dbAds->escape_str($params['ads_type']),
						'trans_id_params' 	=> $this->dbAds->escape_str($params['trans_id_params']),
						'affiliate_params' 	=> $this->dbAds->escape_str($params['affiliate_params']),
						'affiliate_values' 	=> $this->dbAds->escape_str($params['affiliate_values']),
						'url_confirm' 	=> $this->dbAds->escape_str($params['url_confirm']),
						'loading_url' 	=> $this->dbAds->escape_str($params['loading_url']),
						'updation_url' 	=> $this->dbAds->escape_str($params['updation_url']),
						'description' => $this->dbAds->escape_str($params['description']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
		);

		$this->dbAds->insert('ads_publishers', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Vendor",$add_message);
			$add_message=str_replace("{TITLE}",$params['name'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function deleteAds_Publishers($params){
		$service_data=$this->getAds_PublishersData($params);
		$this->dbAds->where('code', $this->dbAds->escape_str($params['code']));
		if(isset($params['id_country']) && !empty($params['id_country']))
			$this->dbAds->where('id_country', $this->dbAds->escape_str($params['id_country']));
		$this->dbAds->delete('ads_publishers');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Vendor",$update_message);
			$update_message=str_replace("{TITLE}",$service_data['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	public function getAds_PublishersData($params){
		$this->dbAds->select('GROUP_CONCAT(id) as id, ap.name, GROUP_CONCAT(id_country) as id_country, ap.description, ap.code, ap.status, ap.utc_timezone, ap.ads_type, ap.trans_id_params, ap.affiliate_params, ap.affiliate_values, ap.url_confirm, ap.loading_url, ap.updation_url', false);
		$this->dbAds->from('ads_publishers ap');
		$this->dbAds->where('code', $this->dbAds->escape_str($params['code']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['name'] = $row['name'];
				$result['id_country'] = $row['id_country'];
				$result['utc_timezone'] = $row['utc_timezone'];
				$result['description'] = $row['description'];
				$result['code'] = $row['code'];
				$result['ads_type'] = $row['ads_type'];
				$result['trans_id_params'] = $row['trans_id_params'];
				$result['affiliate_params'] = $row['affiliate_params'];
				$result['affiliate_values'] = $row['affiliate_values'];
				$result['url_confirm'] = $row['url_confirm'];
				$result['updation_url'] = $row['updation_url'];
				$result['loading_url'] = $row['loading_url'];
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
