<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_prices extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadPrices($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('prices p');
		$this->dbAds->join('currencies cu', 'p.id_currency = cu.id');
		if(!empty($params['search']))
			$this->dbAds->where("(p.value LIKE '%".$params['search']."%' OR cu.name LIKE '%".$params['search']."%' OR cu.code LIKE '%".$params['search']."%' OR p.value LIKE '%".$params['search']."%' OR p.value_with_tax LIKE '%".$params['search']."%')");
		$this->dbAds->where('p.status', '1');
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('p.id, cu.code as cu_name, p.status, p.value, p.value_with_tax');
		$this->dbAds->from('prices p');
		$this->dbAds->join('currencies cu', 'p.id_currency = cu.id');
		if(!empty($params['search']))
				$this->dbAds->where("(p.value LIKE '%".$params['search']."%' OR cu.name LIKE '%".$params['search']."%' OR cu.code LIKE '%".$params['search']."%' OR p.value LIKE '%".$params['search']."%' OR p.value_with_tax LIKE '%".$params['search']."%')");
		$this->dbAds->where('p.status', '1');
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['cu_name'] 	= $row['cu_name'];
				$result['rows'][$i]['value'] 	= number_format($row['value'],2);
				$result['rows'][$i]['value_with_tax'] 	= number_format($row['value_with_tax'],2);
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadPricesSelect($params = array()){		
		$result = array();
		$id=$this->loadCurrenciesSelect($params);
		$this->dbAds->select('p.id, cu.code AS cu_code, value, value_with_tax');
		$this->dbAds->from('prices p');
		$this->dbAds->join('currencies cu', 'p.id_currency = cu.id');
		
		$this->dbAds->where('p.status', '1');
		if(isset($params['id_country']))
			$this->dbAds->where('cu.id_country', $params['id_country']);
		if(isset($id[0]['id']))
		$this->dbAds->where('cu.id', $id[0]['id']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['cu_code'] 	= $row['cu_code'];
				$result['rows'][$i]['value'] 	= number_format($row['value'], 2);
				$result['rows'][$i]['value_with_tax'] 	= number_format($row['value_with_tax'], 2);
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	public function loadCurrenciesSelect($params){  
	  $result = array();
	  $this->dbAds->select('id');
	  $this->dbAds->from('currencies');
	  if(isset($params))
	   $this->dbAds->where('id_country', $params['id']);
	  $query = $this->dbAds->get();
	  $i=0;
	  if($query->num_rows() != 0){
	   $result['count'] = true;
	   foreach($query->result_array() as $row) {
		$result[$i]['id']   = $row['id'];
		$i++;
	   }
	  } else {
	   $result['count'] = false;
	  }
	  
  return $result; 
	}
	public function savePrices($params){
		$this->dbAds->select('id');
		$this->dbAds->from('prices');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updatePrices($params);	
		} else {
			$result = $this->insertPrices($params);
		}
		return $result;
	}
	
	private function updatePrices($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'id_currency' 		=> $this->dbAds->escape_str($params['id_currency']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'value' => $this->dbAds->escape_str($params['value']),
						'value_with_tax' => $this->dbAds->escape_str($params['value_with_tax']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('prices', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Price",$update_message);
			$update_message=str_replace("{TITLE}",$params['value'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertPrices($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'id_currency' 		=> $this->dbAds->escape_str($params['id_currency']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'value' => $this->dbAds->escape_str($params['value']),
						'value_with_tax' => $this->dbAds->escape_str($params['value_with_tax']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->insert('prices', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Price",$add_message);
			$add_message=str_replace("{TITLE}",$params['value'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deletePrices($params){
		$service_data=$this->getPricesData($params);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('prices');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Price",$update_message);
			$update_message=str_replace("{TITLE}",$service_data['value'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getPricesData($params){
		$this->dbAds->select('id, id_currency, value, value_with_tax, status');
		$this->dbAds->from('prices');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['id_currency'] = $row['id_currency'];
				$result['value'] = $row['value'];
				$result['value_with_tax'] = $row['value_with_tax'];
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
