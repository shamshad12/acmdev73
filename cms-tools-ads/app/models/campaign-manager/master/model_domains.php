<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_domains extends MY_Model {

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

	}

	public function loadDomains($params){
		$result = array();

		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('domains c');
		$this->dbAds->join('countries co', 'c.id_country = co.id');
		if(!empty($params['search']))
		$this->dbAds->where("(c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR c.id_country LIKE '%".$params['search']."%' OR co.name LIKE '%".$params['search']."%')");
		$this->dbAds->group_by('c.code');
		$query = $this->dbAds->get();

		$result['total'] = 0;
		if($query->num_rows() != 0)
			$result['total'] = $query->num_rows();

		$this->dbAds->select('c.name, GROUP_CONCAT(co.name) as co_name, c.code, c.status, c.description',false);
		$this->dbAds->from('domains c');
		$this->dbAds->join('countries co', 'c.id_country = co.id');
		if(!empty($params['search']))
		$this->dbAds->where("(c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR c.id_country LIKE '%".$params['search']."%' OR co.name LIKE '%".$params['search']."%')");
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
				$result['rows'][$i]['description'] 	= $row['description'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				$result['rows'][$i]['code'] 	= $row['code'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function loadDomainsSelect($params){
		$result = array();

		$this->dbAds->select('id, code, name, id_country, description');
		$this->dbAds->from('domains');
		//if user is partner
		if($this->session->userdata['profile']['isPartner']){
			$partner_permissions = json_decode($this->session->userdata('partner_permissions'),true);
			if($partner_permissions['domain']){
				$this->dbAds->where_in('id', $this->dbAds->escape_str($partner_permissions['domain']));
			}
		}
		$this->dbAds->where('status', '1');
		if($params['id_country'])
		$this->dbAds->where('id_country', $params['id_country']);
		$this->dbAds->order_by("name","asc");
		$query = $this->dbAds->get();

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['code'] 	= $row['code'];
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

	/*public function saveDomains($params){
		$this->dbAds->select('id');
		$this->dbAds->from('domains');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateDomains($params);
		} else {
			$result = $this->insertDomains($params);
		}
		return $result;
	}*/

	public function saveDomains($params){
		$result = array(); 
		if($params['id'] == '' || $params['id'] == 0)
		{
			$checkCode=$this->checkCode('domains',$params['code']);
			if($checkCode['duplicat_data'])
			return $checkCode;
			
		}
		$countries = $params['id_country'];
		$db_countries = array();

		$this->dbAds->select('id_country');
		$this->dbAds->from('domains');
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
					$this->deleteDomains($del_params);
				}
			}
		}
		
		foreach($countries as $c)
		{
			$this->dbAds->select('id');
			$this->dbAds->from('domains');
			$this->dbAds->where('name', $this->dbAds->escape_str($params['name']));
			$this->dbAds->where('id_country', $this->dbAds->escape_str($c));
			$data = $this->dbAds->get();
			$res = $data->result_array();

			$params['id'] = $res[0]['id'];
			$params['id_country'] = $c;

			if($data->num_rows() != 0){
				$result = $this->updateDomains($params);

			} else {
				$result = $this->insertDomains($params);
			}
		}
		return $result;
	}

	private function updateDomains($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'id_country' 		=> $this->dbAds->escape_str($params['id_country']),
						'code' 	=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' 		=> $this->dbAds->escape_str($params['description']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
		);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('domains', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Domain",$update_message);
			$update_message=str_replace("{TITLE}",$params['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	private function insertDomains($params){

		$result = array();
		$this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('domains');
		$this->dbAds->where("id_country = '".$params['id_country']."' AND (code = '".$params['code']."'OR name = '".$params['name']."')");
			
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();die('lop');
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "For same Country you can not use same Domain Code or same Name";
				$result['duplicat_data'] =  true;
				return $result;
			}
		}

		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'id_country' 		=> $this->dbAds->escape_str($params['id_country']),
						'code' 	=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' 		=> $this->dbAds->escape_str($params['description']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
		);
		$this->dbAds->insert('domains', $update);
		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Domain",$add_message);
			$add_message=str_replace("{TITLE}",$params['name'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function deleteDomains($params){
		$service_data=$this->getDomainsData($params);
		$this->dbAds->where('code', $this->dbAds->escape_str($params['code']));
		if(isset($params['id_country']) && !empty($params['id_country']))
			$this->dbAds->where('id_country', $this->dbAds->escape_str($params['id_country']));
		$this->dbAds->delete('domains');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Domain",$update_message);
			$update_message=str_replace("{TITLE}",$service_data['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	public function getDomainsData($params){
		$this->dbAds->select('GROUP_CONCAT(id) as id, d.name, GROUP_CONCAT(id_country) as id_country, d.description, d.code, d.status',false);
		$this->dbAds->from('domains d');
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
				$result['description'] = $row['description'];
				$result['code'] = $row['code'];
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
