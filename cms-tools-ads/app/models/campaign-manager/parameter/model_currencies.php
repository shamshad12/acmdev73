<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_currencies extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadCurrencies($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('currencies c');
		$this->dbAds->join('countries co', 'c.id_country = co.id');
		if(!empty($params['search']))
			$this->dbAds->where("c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR c.id_country LIKE '%".$params['search']."%' OR c.code LIKE '%".$params['search']."%'");
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('c.id, c.name, co.name AS co_name, c.code, c.status, c.description');
		$this->dbAds->from('currencies c');
		$this->dbAds->join('countries co', 'c.id_country = co.id');
		if(!empty($params['search']))
			$this->dbAds->where("c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR c.id_country LIKE '%".$params['search']."%' OR c.code LIKE '%".$params['search']."%'");
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
	
	public function loadCurrenciesSelect(){		
		$result = array();
				
		$this->dbAds->select('id, code, name, id_country, description');
		$this->dbAds->from('currencies');
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
	
	public function saveCurrencies($params){
		$this->dbAds->select('id');
		$this->dbAds->from('currencies');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateCurrencies($params);	
			

		} else {
			$result = $this->insertCurrencies($params);
		}
		return $result;
	}
	
	private function updateCurrencies($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'id_country'=> $this->dbAds->escape_str($params['id_country']),
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' => $this->dbAds->escape_str($params['description']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('currencies', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Currency",$update_message);
			$update_message=str_replace("{TITLE}",$params['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertCurrencies($params){
           
		$result = array();

        $this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('currencies');
		$this->dbAds->where("id_country = '".$params['id_country']."' OR code = '".$params['code']."'OR name = '".$params['name']."'"); 
               
		$query = $this->dbAds->get();
		
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "Country,Currency Code OR Currency Name already added, so you can not use the same.";  
				$result['duplicat_data'] =  true;
				return $result;
			}
		}
           
            
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'name' 		=> $this->dbAds->escape_str($params['name']),
						'id_country'=> $this->dbAds->escape_str($params['id_country']),
						'code' 		=> $this->dbAds->escape_str($params['code']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'description' 		=> $this->dbAds->escape_str($params['description']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->insert('currencies', $update);
		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Currency",$add_message);
			$add_message=str_replace("{TITLE}",$params['name'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteCurrencies($params){
		$currency_data=$this->getCurrenciesData($params);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('currencies');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Currency",$update_message);
			$update_message=str_replace("{TITLE}",$currency_data['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$currency_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getCurrenciesData($params){
		$this->dbAds->select('id, name, id_country, description,code,status');
		$this->dbAds->from('currencies');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
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
