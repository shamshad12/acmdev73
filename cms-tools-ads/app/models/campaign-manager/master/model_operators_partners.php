<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_operators_partners extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadOperators_Partners($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('operators_partners op');
		$this->dbAds->join('operators o', 'op.id_operator = o.id');
		$this->dbAds->join('partners p', 'op.id_partner = p.id');
		if(!empty($params['search']))
			$this->dbAds->where("(op.id LIKE '%".$params['search']."%' OR op.share_operator LIKE '%".$params['search']."%' OR 
								 o.name LIKE '%".$params['search']."%' OR op.share_partner LIKE '%".$params['search']."%' OR
								 p.name LIKE '%".$params['search']."%' OR op.vat LIKE '%".$params['search']."%')");
								 
		if(!empty($params['partner']))
			$this->dbAds->where("op.id_partner", $params['partner']);
		
		if(!empty($params['operator']))
			$this->dbAds->where("op.id_operator", $params['operator']);
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('op.id, op.share_operator, op.share_partner, o.name AS operator_name, p.name AS partner_name, op.vat, op.status');
		$this->dbAds->from('operators_partners op');
		$this->dbAds->join('operators o', 'op.id_operator = o.id');
		$this->dbAds->join('partners p', 'op.id_partner = p.id');
		if(!empty($params['search']))
			$this->dbAds->where("(op.id LIKE '%".$params['search']."%' OR op.share_operator LIKE '%".$params['search']."%' OR 
								 o.name LIKE '%".$params['search']."%' OR op.share_partner LIKE '%".$params['search']."%' OR
								 p.name LIKE '%".$params['search']."%' OR op.vat LIKE '%".$params['search']."%')");
								 
		if(!empty($params['partner']))
			$this->dbAds->where("op.id_partner", $params['partner']);
		
		if(!empty($params['operator']))
			$this->dbAds->where("op.id_operator", $params['operator']);
			
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 				= $row['id'];
				$result['rows'][$i]['share_operator'] 	= $row['share_operator'].' %';
				$result['rows'][$i]['share_partner'] 	= $row['share_partner'].' %';
				$result['rows'][$i]['vat'] 				= $row['vat'].' %';
				$result['rows'][$i]['partner_name'] 	= $row['partner_name'];
				$result['rows'][$i]['operator_name'] 	= $row['operator_name'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadOperators_PartnersSelect($params){		
		$result = array();
				
		$this->dbAds->select('op.id, p.id AS partner_id, o.name AS operator_name, p.name AS partner_name');
		$this->dbAds->from('operators_partners op');
		$this->dbAds->join('operators o', 'op.id_operator = o.id');
		$this->dbAds->join('partners p', 'op.id_partner = p.id');
		
		$this->dbAds->where('op.status', '1');
		if(isset($params['id_operator']))
			$this->dbAds->where('op.id_operator', $params['id_operator']);
		
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['partner_id'] 		= $row['partner_id'];
				$result['rows'][$i]['operator_name'] 	= $row['operator_name'];
				$result['rows'][$i]['partner_name'] 	= $row['partner_name'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveOperators_Partners($params){
		$this->dbAds->select('id');
		$this->dbAds->from('operators_partners');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateOperators_Partners($params);	
		} else {
			$result = $this->insertOperators_Partners($params);
		}
		return $result;
	}
	
	private function updateOperators_Partners($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'share_operator' => $this->dbAds->escape_str($params['share_operator']),
						'share_partner'  => $this->dbAds->escape_str($params['share_partner']),
						'id_operator' 	 => $this->dbAds->escape_str($params['id_operator']),
						'id_partner' 	 => $this->dbAds->escape_str($params['id_partner']),
						'vat' 	 		=> $this->dbAds->escape_str($params['vat']),
						'status' 		=> $this->dbAds->escape_str($params['status']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('operators_partners', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Operator Partner",$update_message);
			$update_message=str_replace("{TITLE}",' ',$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertOperators_Partners($params){
            
                 $this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('operators_partners');
		$this->dbAds->where('id_operator',$params['id_operator']);
		$this->dbAds->where('id_partner',$params['id_partner']);   
		
               
		$query = $this->dbAds->get();
		$result = array();
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "This Opertor and Partner combination is already Configured in this Settings So You can Edit not add.";  
				$result['duplicat_data'] =  true;
				return $result;
			}
		}
           
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'share_operator' => $this->dbAds->escape_str($params['share_operator']),
						'share_partner'  => $this->dbAds->escape_str($params['share_partner']),
						'id_operator' 	 => $this->dbAds->escape_str($params['id_operator']),
						'id_partner' 	 => $this->dbAds->escape_str($params['id_partner']),
						'vat' 	 		=> $this->dbAds->escape_str($params['vat']),
						'status' 		=> $this->dbAds->escape_str($params['status']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->insert('operators_partners', $update);
		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Operator Partner",$add_message);
			$add_message=str_replace("{TITLE}",' ',$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteOperators_Partners($params){
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('operators_partners');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Operator Partner",$update_message);
			$update_message=str_replace("{TITLE}",' ',$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getOperators_PartnersData($params){
		$this->dbAds->select('id, share_operator, id_operator, share_partner, id_partner, vat, status');
		$this->dbAds->from('operators_partners');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['share_operator'] = $row['share_operator'];
				$result['id_operator'] = $row['id_operator'];
				$result['share_partner'] = $row['share_partner'];
				$result['id_partner'] = $row['id_partner'];
				$result['vat'] = $row['vat'];
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
