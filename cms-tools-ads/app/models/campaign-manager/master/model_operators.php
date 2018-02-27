<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_operators extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadOperators($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('operators c');
		$this->dbAds->join('countries co', 'c.id_country = co.id');
		if(!empty($params['search']))
			$this->dbAds->where("(c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR co.name LIKE '%".$params['search']."%' OR c.description LIKE '%".$params['search']."%' OR c.code LIKE '%".$params['search']."%')");
		$this->dbAds->group_by('c.code');
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
		if($query->num_rows() != 0)
			$result['total'] = $query->num_rows();
				
		$this->dbAds->select('c.name, GROUP_CONCAT(co.name) as co_name, c.utc_timezone, c.code, c.status, c.description', false);
		$this->dbAds->from('operators c');
		$this->dbAds->join('countries co', 'c.id_country = co.id');
		if(!empty($params['search']))
			$this->dbAds->where("(c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR co.name LIKE '%".$params['search']."%' OR c.description LIKE '%".$params['search']."%' OR c.code LIKE '%".$params['search']."%')");
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
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadOperatorsSelect(){		
		$result = array();
				
		$this->dbAds->select('op.id, CONCAT(co.code,"-",op.name) as opname, op.id_country, op.code, op.description',false);
		$this->dbAds->from('operators op');
		$this->dbAds->join('countries co', 'op.id_country = co.id');
		$this->dbAds->where('op.status', '1');
		$this->dbAds->order_by("opname");
		//$this->dbAds->order_by("op.name","asc");
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				//$this->load->model('campaign-manager/master/model_ads_publishers');
				//$code = $this->model_ads_publishers->getCountryCode($row['id_country']);
				
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['name'] 	= $row['opname'];
				$result['rows'][$i]['id_country'] 	= $row['id_country'];
				$result['rows'][$i]['description'] 	= $row['description'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	/*public function saveOperators($params){
		$this->dbAds->select('id');
		$this->dbAds->from('operators');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateOperators($params);	
		} else {
			$result = $this->insertOperators($params);
		}
		return $result;
	}*/

	public function saveOperators($params){
		$result = array(); 
		if($params['id'] == '' || $params['id'] == 0)
		{
			$checkCode=$this->checkCode('operators',$params['code']);
			if($checkCode['duplicat_data'])
			return $checkCode;
			
		}
		$countries = $params['id_country'];
		$db_countries = array();

		$this->dbAds->select('id_country');
		$this->dbAds->from('operators');
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
					$this->deleteOperators($del_params);
				}
			}
		}

		foreach($countries as $c)
		{
			$this->dbAds->select('id');
			$this->dbAds->from('operators');
			$this->dbAds->where('name', $this->dbAds->escape_str($params['name']));
			$this->dbAds->where('id_country', $this->dbAds->escape_str($c));
			$data = $this->dbAds->get();
			$res = $data->result_array();

			$params['id'] = $res[0]['id'];
			$params['id_country'] = $c;

			if($data->num_rows() != 0){
				$result = $this->updateOperators($params);	
			} else {
				$result = $this->insertOperators($params);
			}
		}

		return $result;
	}
	
	private function updateOperators($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'id_country' => $this->dbAds->escape_str($params['id_country']),
						'utc_timezone' => $this->dbAds->escape_str($params['utc_timezone']),
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'prefix' 	=> $this->dbAds->escape_str($params['prefix']),
						'description' => $this->dbAds->escape_str($params['description']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('operators', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Operator",$update_message);
			$update_message=str_replace("{TITLE}",$params['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertOperators($params){

		$result = array();    
        $this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('operators');
		$this->dbAds->where("id_country = '".$params['id_country']."' AND (code = '".$params['code']."'OR name = '".$params['name']."')"); 
               
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();die('lop');
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "For same Country you can not use same Operator Code or same Name";  
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
						'prefix' 	=> $this->dbAds->escape_str($params['prefix']),
						'description' => $this->dbAds->escape_str($params['description']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->insert('operators', $update);
		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Operator",$add_message);
			$add_message=str_replace("{TITLE}",$params['name'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteOperators($params){
		$service_data=$this->getOperatorsData($params);
		$this->dbAds->where('code', $this->dbAds->escape_str($params['code']));
		if(isset($params['id_country']) && !empty($params['id_country']))
			$this->dbAds->where('id_country', $this->dbAds->escape_str($params['id_country']));
		$this->dbAds->delete('operators');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Operator",$update_message);
			$update_message=str_replace("{TITLE}",$service_data['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getOperatorsData($params){
		$this->dbAds->select('GROUP_CONCAT(id) as id, o.name, GROUP_CONCAT(id_country) as id_country, o.utc_timezone, o.description, o.code, o.status, o.prefix', false);
		$this->dbAds->from('operators o');
		$this->dbAds->where('code', $this->dbAds->escape_str($params['code']));
		if(isset($params['id_country']) && !empty($params['id_country']))
			$this->dbAds->where('id_country', $this->dbAds->escape_str($params['id_country']));
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
				$result['prefix'] = $row['prefix'];
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
