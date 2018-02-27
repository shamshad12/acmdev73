<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_config extends MY_Model {

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

	}

	public function loadPartner_config($params){
            
		$result = array();

		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('api_partner_config ps');
		
		if(!empty($params['search']))
		$this->dbAds->where("(ps.id LIKE '%".$params['search']."%' OR ps.partner_code LIKE '%".$params['search']."%' OR
								 ps.shortcode LIKE '%".$params['search']."%' OR 
								 ps.shortcode LIKE '%".$params['search']."%' OR ps.cc LIKE '%".$params['search']."%' OR ps.cp_id LIKE '%".$params['search']."%')");
			
		
		if(!empty($params['partner']))
		$this->dbAds->where("ps.partner_code", $params['partner']);

		if(!empty($params['shortcode']))
		$this->dbAds->where("ps.shortcode", $params['shortcode']);
			
		$this->dbAds->where('ps.status != 2');

		$query = $this->dbAds->get();

		$result['total'] = 0;
		foreach($query->result_array() as $row)
		$result['total'] = $row['count'];

		$this->dbAds->select('ps.id, ps.partner_code,ps.shortcode, ps.keyword,ps.product_id, ps.cc, ps.cp_id, ps.cp_id, ps.status');
		$this->dbAds->from('api_partner_config ps');
		
		if(!empty($params['search']))
		$this->dbAds->where("(ps.id LIKE '%".$params['search']."%' OR ps.partner_code LIKE '%".$params['search']."%' OR
								 ps.shortcode LIKE '%".$params['search']."%' OR 
								 ps.shortcode LIKE '%".$params['search']."%' OR ps.cc LIKE '%".$params['search']."%' OR ps.cp_id LIKE '%".$params['search']."%')");
			
			if(!empty($params['partner']))
		$this->dbAds->where("ps.partner_code", $params['partner']);

		if(!empty($params['shortcode']))
		$this->dbAds->where("ps.shortcode", $params['shortcode']);
			
		$this->dbAds->where('ps.status != 2');
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		
                $query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die('kl');
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
                            
				$result['rows'][$i]['id'] 			= $row['id'];
				$result['rows'][$i]['partner_code'] 		= $row['partner_code'];
				$result['rows'][$i]['shortcode'] 	= $row['shortcode'];
				$result['rows'][$i]['keyword'] = $row['keyword'];
				$result['rows'][$i]['product_id'] 	= $row['product_id'];
				$result['rows'][$i]['cc'] 	= $row['cc'];
				$result['rows'][$i]['cp_id'] = $row['cp_id'];
				
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';

				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}



	
	public function savePartner_Config($params){
		//print_r($params);exit;
		$this->dbAds->select('id');
		$this->dbAds->from('api_partner_config');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updatePartner_Config($params);
		} else {
			$result = $this->insertPartner_Config($params);
		}
		return $result;
	}

	private function updatePartner_Config($params){
		
		$update = array(
						'partner_code' 			=>$this->dbAds->escape_str($params['partner_code']),
						'shortcode'  => $this->dbAds->escape_str($params['shortcode']),
                                                'keyword' 		=> $this->dbAds->escape_str($params['keyword']),
						'product_id' 	=> $this->dbAds->escape_str($params['product_id']),
						'cp_id' 	=> $this->dbAds->escape_str($params['cp_id']),
						'cc' 	=> $this->dbAds->escape_str($params['cc']),
						'status' 		=> $this->dbAds->escape_str($params['status'])
		);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('api_partner_config', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Partner Config",$update_message);
			$update_message=str_replace("{TITLE}",' ',$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	private function insertPartner_Config($params){

		$result = array();
		$this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('api_partner_config');
		$this->dbAds->where('partner_code',$params['partner_code']);
		$this->dbAds->where('shortcode', $this->dbAds->escape_str($params['shortcode']));
		$this->dbAds->where('keyword', $this->dbAds->escape_str($params['keyword']));
		$this->dbAds->where('product_id', $this->dbAds->escape_str($params['product_id']));
		$this->dbAds->where('cc', $this->dbAds->escape_str($params['cc']));
		$this->dbAds->where('cp_id', $this->dbAds->escape_str($params['cp_id']));
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();die('lop');
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "This entry already exist, please try different.";
				$result['duplicat_data'] =  true;
				return $result;
			}
		}
                $this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('api_partner_config');
		$this->dbAds->where('( product_id="'.$params['product_id'].'" AND status<>2 )');
		$this->dbAds->where('partner_code',$params['partner_code']);
                $query = $this->dbAds->get();
                
                foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "This Product id exist, please try with different Product ID.";
				$result['duplicat_data'] =  true;
				return $result;
			}
		}

		$update = array(
						'partner_code' 			=>$this->dbAds->escape_str($params['partner_code']),
						'shortcode'  => $this->dbAds->escape_str($params['shortcode']),
                                                'keyword' 		=> $this->dbAds->escape_str($params['keyword']),
						'product_id' 	=> $this->dbAds->escape_str($params['product_id']),
						'cp_id' 	=> $this->dbAds->escape_str($params['cp_id']),
						'cc' 	=> $this->dbAds->escape_str($params['cc']),
						'status' 		=> $this->dbAds->escape_str($params['status'])
		);
		$this->dbAds->insert('api_partner_config', $update);
		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Price",$add_message);
			$add_message=str_replace("{TITLE}",' ',$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function deletePartner_Config($params){

		$result = array();
		

		$update = array();
		$update['status'] = 2;
		
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('api_partner_config', $update);
		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function getPartner_ConfigData($params){
		$this->dbAds->select('id,partner_code , shortcode,keyword, product_id, cp_id, cc, status');
		$this->dbAds->from('api_partner_config');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();die('lop');
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;

			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['partner_code'] = $row['partner_code'];
				$result['shortcode'] = $row['shortcode'];
				$result['keyword'] = $row['keyword'];
				$result['product_id'] = $row['product_id'];
				$result['cp_id'] = $row['cp_id'];
				$result['cc'] = $row['cc'];
				$result['status'] = $row['status'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

        public function loadPartner(){
            $this->dbAds->select('code,name');
		$this->dbAds->from('api_partner');
		$this->dbAds->where('status = 1');
                //$this->dbAds->where('status != 0');
		$query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die;
		
		$i=0;
		if($query->num_rows() != 0){
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['code'] = $row['code'];
                                $result['rows'][$i]['name'] = $row['name'];
				$i++;
			}

        }
        return $result;
        
                        }

}
?>
