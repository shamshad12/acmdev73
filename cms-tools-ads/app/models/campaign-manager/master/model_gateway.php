<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_gateway extends MY_Model {

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

	}

	public function loadGateway($params){
		$result = array();

		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('sms_gateway sg');
		$this->dbAds->join('operators o', 'sg.telco_id = o.id', 'LEFT');
		$this->dbAds->join('countries c', 'sg.country_id = c.id');
		//$this->dbAds->join('shortcodes sh', 'sg.shortcode_id = sh.id');
		if(!empty($params['search']))
		$this->dbAds->where("(sg.id LIKE '%".$params['search']."%' OR sg.api_url LIKE '%".$params['search']."%' OR
								 o.name LIKE '%".$params['search']."%'  OR
								 c.name LIKE '%".$params['search']."%' OR c.description LIKE '%".$params['search']."%')");
		if(!empty($params['country']))
		$this->dbAds->where("sg.country_id = '".$params['country']."'");
		if(!empty($params['operator']))
		$this->dbAds->where("sg.telco_id = '".$params['operator']."'");
		if(!empty($params['shortcode']))
		$this->dbAds->where("sg.shortcode_id = '".$params['shortcode']."'");
		$query = $this->dbAds->get();

		$result['total'] = 0;
		foreach($query->result_array() as $row)
		$result['total'] = $row['count'];

		$this->dbAds->select('sg.g_name,sg.shortcode_id as code, sg.id,sg.country_id,sg.telco_id, c.name country_name, o.name AS operator_name, sg.api_url,sg.status');
		$this->dbAds->from('sms_gateway sg');
		$this->dbAds->join('operators o', 'sg.telco_id = o.id', 'LEFT');
		$this->dbAds->join('countries c', 'sg.country_id = c.id');
		//$this->dbAds->join('shortcodes sh', 'sg.shortcode_id = sh.id');
		if(!empty($params['search']))
		$this->dbAds->where("(sg.id LIKE '%".$params['search']."%' OR sg.api_url LIKE '%".$params['search']."%' OR
								 o.name LIKE '%".$params['search']."%'  OR
								 c.name LIKE '%".$params['search']."%' OR c.description LIKE '%".$params['search']."%')");
		if(!empty($params['country']))
		$this->dbAds->where("sg.country_id = '".$params['country']."'");
		if(!empty($params['operator']))
		$this->dbAds->where("sg.telco_id = '".$params['operator']."'");
		if(!empty($params['shortcode']))
		$this->dbAds->where("sg.shortcode_id = '".$params['shortcode']."'");

		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				if(trim($row['telco_id'])==0)
				{
					$row['operator_name']='ALL';
				}
				$result['rows'][$i]['id'] = $row['id'];
				$result['rows'][$i]['g_name'] = $row['g_name'];
				$result['rows'][$i]['country_name'] = $row['country_name'];
				$result['rows'][$i]['operator_name'] 	= $row['operator_name'];
				$result['rows'][$i]['api_url'] 	= $row['api_url'];
				$result['rows'][$i]['country_id'] 	= $row['country_id'];
				$result['rows'][$i]['telco_id'] 	= $row['telco_id'];
				$result['rows'][$i]['shortcode_id'] 	= $row['shortcode_id'];
				$result['rows'][$i]['code'] 	= $row['code'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}


	public function saveGateway($params){
		$this->dbAds->select('id');
		$this->dbAds->from('sms_gateway');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateGateway($params);
		} else {
			$result = $this->insertGateway($params);
		}
		return $result;
	}



	private function updateGateway($params){
		$this->dbAds->select('id');
		$this->dbAds->from('sms_gateway');

		$this->dbAds->where("country_id = ".$this->dbAds->escape_str($params['country_id']));
		if($params['telco_id']!=0)
		{
			$this->dbAds->where("( telco_id = ".$this->dbAds->escape_str($params['telco_id'])." OR telco_id=0)"   );
		}
		/*else
		 {
			$this->dbAds->where("( telco_id = ".$this->dbAds->escape_str($params['telco_id'])." )"   );
			}*/
		$this->dbAds->where("shortcode_id = ".$this->dbAds->escape_str($params['shortcode_id']) );;
		$this->dbAds->where("id <> ".$this->dbAds->escape_str($params['id']) );

		$query = $this->dbAds->get();

		if(count($query->result_array())>0)
		{
			$result['success'] = false;
			if(trim($params['telco_id'])==0)
			{
				$result['message'] = "There is already a entry for selected country shortcode.";
			}else
			{
				$result['message'] = "There is already a entry for selected country and shortcode.";
			}
		}else{
			$input_by = $this->session->userdata('id_user');
			$input_time = strtotime(date('Y-m-d H:i:s'));
			$update = array(
						'country_id' 		=> $this->dbAds->escape_str($params['country_id']),
						'g_name' 		=> $this->dbAds->escape_str($params['g_name']),
						'telco_id' 	=> $this->dbAds->escape_str($params['telco_id']),
						'shortcode_id' 	=> $this->dbAds->escape_str($params['shortcode_id']),
						'api_url' => $this->dbAds->escape_str($params['api_url']),
						'pin_type' => $this->dbAds->escape_str($params['pin_type']),
						'status'=>$this->dbAds->escape_str($params['status']),
						'updation_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
			);
			$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
			$this->dbAds->update('sms_gateway', $update);
			$result = array();
			if($this->dbAds->affected_rows()){
				$result['success'] = true;
				$update_message=$this->config->item('update_message');
				$update_message=str_replace("{SECTION}","Gateway",$update_message);
				$update_message=str_replace("{TITLE}",$params['g_name'],$update_message);
				$update_message=str_replace("{ID}",$params['id'],$update_message);
				$this->SaveLogdata($update_message,$params);
				$result['id'] = $params['id'];
			} else {
				$result['success'] = false;
			}
		}
		return $result;
	}

	private function insertGateway($params){
		$this->dbAds->select('id');
		$this->dbAds->from('sms_gateway');

		$this->dbAds->where("country_id = ".$this->dbAds->escape_str($params['country_id']));
		if($params['telco_id']!=0)
		{
			$this->dbAds->where("( telco_id = ".$this->dbAds->escape_str($params['telco_id'])." OR telco_id=0)"   );
		}
		/*else
		 {
			$this->dbAds->where("( telco_id = ".$this->dbAds->escape_str($params['telco_id'])." )"   );
			}*/
		$this->dbAds->where("shortcode_id = ".$this->dbAds->escape_str($params['shortcode_id']) );

		$query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die();

		if(count($query->result_array())>0)
		{
			$result['success'] = false;
			if(trim($params['telco_id'])==0)
			{
				$result['message'] = "There is already a entry for selected country shortcode.";
			}else
			{
				$result['message'] = "There is already a entry for selected country, telco and shortcode.";
			}
		}else{
			$input_by = $this->session->userdata('id_user');
			$input_time = strtotime(date('Y-m-d H:i:s'));
			$update = array(
						'country_id' 		=> $this->dbAds->escape_str($params['country_id']),
						'g_name' 		=> $this->dbAds->escape_str($params['g_name']),
						'telco_id' 	=> $this->dbAds->escape_str($params['telco_id']),
						'shortcode_id'=>$this->dbAds->escape_str($params['shortcode_id']),
						'api_url' => $this->dbAds->escape_str($params['api_url']),
						'status'=>$this->dbAds->escape_str($params['status']),
						'user_id' => $this->dbAds->escape_str($this->profile['id']),
						'create_ts' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))

			);
			$this->dbAds->insert('sms_gateway', $update);
			$insert_id=$this->dbAds->insert_id();

			$result = array();
			if($this->dbAds->affected_rows()){
				$result['success'] = true;
				$add_message=$this->config->item('add_message');
				$add_message=str_replace("{SECTION}","Gateway",$add_message);
				$add_message=str_replace("{TITLE}",$params['g_name'],$add_message);
				$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
				$this->SaveLogdata($add_message,$params);
				$result['id'] =$insert_id;
			} else {
				$result['success'] = false;
			}
		}
		return $result;
	}

	public function deleteGateway($params){
		$service_data=$this->getGatewayData($params);

		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('sms_gateway');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Gateway",$update_message);
			$update_message=str_replace("{TITLE}",$service_data['g_name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	public function getGatewayData($params){

		$this->dbAds->select('sg.id,sg.g_name, sg.country_id,sg.shortcode_id ,sg.telco_id , sg.api_url,sg.params ,sg.status,sg.type,sg.pin_type');
		$this->dbAds->from('sms_gateway sg');
		/*$this->dbAds->join('operators o', 'sg.telco_id = o.id');
		 $this->dbAds->join('countries c', 'sg.country_id = c.id');*/
		$this->dbAds->where('sg.id', $this->dbAds->escape_str($params['id']));

		$query = $this->dbAds->get();

		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['g_name'] = $row['g_name'];
				$result['country_id'] = $row['country_id'];
				$result['telco_id'] = $row['telco_id'];
				$result['api_url'] = $row['api_url'];
				$result['status'] = $row['status'];
				$result['shortcode_id'] = $row['shortcode_id'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
	public function loadOperator($params){
		$this->dbAds->select('id, name');
		$this->dbAds->from('operators');
		$this->dbAds->where('id_country', $this->dbAds->escape_str($params['id']));
		$this->dbAds->where('status', 1);
		$query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die('kl');
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['success'] = true;
			foreach($query->result_array() as $row) {
				$result[$i]['id'] = $row['id'];
				$result[$i]['name'] = $row['name'];
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
