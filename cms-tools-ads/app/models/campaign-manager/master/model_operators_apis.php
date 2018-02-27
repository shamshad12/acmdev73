<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_operators_apis extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadOperators_Apis($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('operators_apis ps');
		$this->dbAds->join('operators o', 'ps.id_operator = o.id');
		if(!empty($params['search']))
			$this->dbAds->where("(ps.id LIKE '%".$params['search']."%' OR ps.params_request LIKE '%".$params['search']."%' OR 
								 ps.params_response LIKE '%".$params['search']."%' OR ps.url_api LIKE '%".$params['search']."%' OR
								 ps.type LIKE '%".$params['search']."%'  OR ps.method LIKE '%".$params['search']."%' OR 
								 ps.name LIKE '%".$params['search']."%')");
			
		if(!empty($params['operator']))
			$this->dbAds->where("ps.id_operator", $params['operator']);
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('ps.id, ps.name, o.name AS operator_name, ps.params_request, ps.params_response, ps.type, ps.method, ps.status, ps.url_api');
		$this->dbAds->from('operators_apis ps');
		$this->dbAds->join('operators o', 'ps.id_operator = o.id');
		if(!empty($params['search']))
			$this->dbAds->where("(ps.id LIKE '%".$params['search']."%' OR ps.params_request LIKE '%".$params['search']."%' OR 
								 ps.params_response LIKE '%".$params['search']."%' OR ps.url_api LIKE '%".$params['search']."%' OR
								 ps.type LIKE '%".$params['search']."%'  OR ps.method LIKE '%".$params['search']."%' OR 
								 ps.name LIKE '%".$params['search']."%')");

		if(!empty($params['operator']))
			$this->dbAds->where("ps.id_operator", $params['operator']);
			
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 				= $row['id'];
				$result['rows'][$i]['name'] 			= $row['name'];
				$result['rows'][$i]['params_request'] 	= $row['params_request'];
				$result['rows'][$i]['params_response'] 	= $row['params_response'];
				$result['rows'][$i]['method'] 			= $row['method'];
				$result['rows'][$i]['type'] 			= $row['type'];
				$result['rows'][$i]['operator_name'] 	= $row['operator_name'];
				$result['rows'][$i]['url_api'] 			= $row['url_api'];
				$result['rows'][$i]['status'] 			= ($row['status']==1)?'Active':'Inactive';
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadOperators_ApisSelect($params){		
		$result = array();
				
		$this->dbAds->select('op.id, op.name, o.name AS operator_name');
		$this->dbAds->from('operators_apis op');
		$this->dbAds->join('operators o', 'op.id_operator = o.id','LEFT');
		
		$this->dbAds->where('op.status', '1');
		if(!empty($params['id_country']))
			$this->dbAds->where('o.id_country',  $params['id_country']);
		if(!empty($params['id_operator']))
			$this->dbAds->where("( op.id_operator=".$params['id_operator']." OR op.id_operator='-1' )");
		
		$query = $this->dbAds->get();

		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 			= $row['id'];
				$result['rows'][$i]['operator_name']= $row['operator_name'];
				$result['rows'][$i]['name'] 		= $row['name'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveOperators_Apis($params){
		$this->dbAds->select('id');
		$this->dbAds->from('operators_apis');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateOperators_Apis($params);	
		} else {
			$result = $this->insertOperators_Apis($params);
		}
		return $result;
	}
	
	private function updateOperators_Apis($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 			=> $this->dbAds->escape_str($params['name']),
						'id_operator' 	=> $this->dbAds->escape_str($params['id_operator']),
						'url_api' 		=> $this->dbAds->escape_str($params['url_api']),
						'method' 		=> $this->dbAds->escape_str($params['method']),
						'type' 			=> $this->dbAds->escape_str($params['type']),
						'params_response' => $this->dbAds->escape_str($params['params_response']),
						'params_request'=> $this->dbAds->escape_str($params['params_request']),
						'status' 		=> $this->dbAds->escape_str($params['status']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('operators_apis', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Operator Apis",$update_message);
			$update_message=str_replace("{TITLE}",$params['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);

		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertOperators_Apis($params){

		$result = array();    
        $this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('operators_apis');
		$this->dbAds->where("id_operator = '".$params['id_operator']."' AND (name = '".$params['name']."'OR url_api = '".$params['url_api']."')"); 
               
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();die('lop');
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "For this operator, this name or URL API is already used. So use different one.";  
				$result['duplicat_data'] =  true;
				return $result;
			}
		}

		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 			=> $this->dbAds->escape_str($params['name']),
						'id_operator' 	=> $this->dbAds->escape_str($params['id_operator']),
						'url_api' 		=> $this->dbAds->escape_str($params['url_api']),
						'method' 		=> $this->dbAds->escape_str($params['method']),
						'type' 			=> $this->dbAds->escape_str($params['type']),
						'params_response' => $this->dbAds->escape_str($params['params_response']),
						'params_request'=> $this->dbAds->escape_str($params['params_request']),
						'status' 		=> $this->dbAds->escape_str($params['status']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->insert('operators_apis', $update);
		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Operator Apis",$add_message);
			$add_message=str_replace("{TITLE}",$params['name'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteOperators_Apis($params){
		$service_data=$this->getOperators_ApisData($params);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('operators_apis');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Operator Apis",$update_message);
			$update_message=str_replace("{TITLE}",$service_data['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getOperators_ApisData($params){
		$this->dbAds->select('id, name, method, type, params_request, url_api, id_operator, params_response, status');
		$this->dbAds->from('operators_apis');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] 				= $row['id'];
				$result['name'] 			= $row['name'];
				$result['method'] 			= $row['method'];
				$result['type'] 			= $row['type'];
				$result['params_request'] 	= $row['params_request'];
				$result['url_api'] 			= $row['url_api'];
				$result['id_operator'] 		= $row['id_operator'];
				$result['params_response'] 	= $row['params_response'];
				$result['status'] 			= $row['status'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
}
?>
