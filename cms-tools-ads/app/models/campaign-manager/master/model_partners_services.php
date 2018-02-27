<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_partners_services extends MY_Model {

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

	}

	public function loadPartners_Services($params){
		$result = array();

		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('partners_services ps');
		$this->dbAds->join('countries c', 'ps.country_id = c.id');
		$this->dbAds->join('shortcodes s', 'ps.id_shortcode = s.id');
		$this->dbAds->join('partners p', 'ps.id_partner = p.id');
		$this->dbAds->join('operators o', 'ps.id_operator = o.id');
		$this->dbAds->join('campaigns_media cm', 'ps.id_campaign_media = cm.id');
		$this->dbAds->join('prices pr', 'ps.id_price = pr.id');
		$this->dbAds->join('currencies cu', 'pr.id_currency = cu.id');
		if(!empty($params['search']))
		$this->dbAds->where("(ps.id LIKE '%".$params['search']."%' OR ps.sid LIKE '%".$params['search']."%' OR
								 ps.keyword LIKE '%".$params['search']."%' OR 
								 cm.code LIKE '%".$params['search']."%' OR o.name LIKE '%".$params['search']."%')");
			
		if(!empty($params['operator']))
		$this->dbAds->where("ps.id_operator", $params['operator']);
		if(!empty($params['country']))
		$this->dbAds->where("ps.country_id", $params['country']);

		if(!empty($params['partner']))
		$this->dbAds->where("ps.id_partner", $params['partner']);

		if(!empty($params['shortcode']))
		$this->dbAds->where("s.code", $params['shortcode']);
			
		if(!empty($params['price']))
		$this->dbAds->where("ps.id_price", $params['price']);

		$query = $this->dbAds->get();

		$result['total'] = 0;
		foreach($query->result_array() as $row)
		$result['total'] = $row['count'];

		$this->dbAds->select('ps.id, cm.code as campaign_media,ps.country_id, s.code AS shortcode, cu.code as cu_name, pr.value, c.name AS country_name, o.name AS operator_name, p.name AS partner_name, ps.keyword, ps.sid, ps.status,ps.entry_user,ps.entry_time,ps.update_user,ps.update_time');
		$this->dbAds->from('partners_services ps');
		$this->dbAds->join('shortcodes s', 'ps.id_shortcode = s.id');
		$this->dbAds->join('countries c', 'ps.country_id = c.id');
		$this->dbAds->join('partners p', 'ps.id_partner = p.id');
		$this->dbAds->join('operators o', 'ps.id_operator = o.id');
		$this->dbAds->join('campaigns_media cm', 'ps.id_campaign_media = cm.id');
		$this->dbAds->join('prices pr', 'ps.id_price = pr.id');
		$this->dbAds->join('currencies cu', 'pr.id_currency = cu.id');
		if(!empty($params['search']))
		$this->dbAds->where("(ps.id LIKE '%".$params['search']."%' OR ps.sid LIKE '%".$params['search']."%' OR
								 ps.keyword LIKE '%".$params['search']."%' OR 
								 cm.code LIKE '%".$params['search']."%' OR o.name LIKE '%".$params['search']."%')");
			
		if(!empty($params['partner']))
		$this->dbAds->where("ps.id_partner", $params['partner']);

		if(!empty($params['country']))
		$this->dbAds->where("ps.country_id", $params['country']);


		if(!empty($params['operator']))
		$this->dbAds->where("ps.id_operator", $params['operator']);

		if(!empty($params['shortcode']))
		$this->dbAds->where("s.code", $params['shortcode']);
			
		if(!empty($params['price']))
		$this->dbAds->where("ps.id_price", $params['price']);
			
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die('kl');
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 			= $row['id'];
				$result['rows'][$i]['price'] 		= $row['cu_name'].' '.$row['value'];
				$result['rows'][$i]['shortcode'] 	= $row['shortcode'];
				$result['rows'][$i]['partner_name'] = $row['partner_name'];
				$result['rows'][$i]['country_name'] 	= $row['country_name'];
				$result['rows'][$i]['country_id'] 	= $row['country_id'];
				$result['rows'][$i]['operator_name'] = $row['operator_name'];
				$result['rows'][$i]['campaign_media'] = $row['campaign_media'];
				$result['rows'][$i]['sid'] 			= $row['sid'];
				$result['rows'][$i]['keyword'] 		= $row['keyword'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
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



	public function loadShortcode($params){
		$this->dbAds->select('id, code');
		$this->dbAds->from('shortcodes');
		$this->dbAds->where('partner_id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->where('status', 1);
		$this->dbAds->order_by('CAST(code AS SIGNED)', 'asc');
		$query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die('kl');
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['success'] = true;
			foreach($query->result_array() as $row) {
				$result[$i]['id'] = $row['id'];
				$result[$i]['name'] = $row['code'];
				$i++;
			}

		}
		else {
			$result['success'] = false;
		}

		return $result;
	}

	public function loadPartners_ServicesSelect($params){
		$result = array();
		$this->dbAds->select('op.id, cm.code as campaign_media,op.country_id,o.id as operator_id, o.name AS operator_name, p.id as partner_id, p.name AS partner_name, cr.code AS currency_name, pr.value, s.code AS shortcode, sid, keyword');
		$this->dbAds->from('partners_services op');
		$this->dbAds->join('operators o', 'op.id_operator = o.id');
		$this->dbAds->join('countries c', 'op.country_id = c.id');
		$this->dbAds->join('campaigns_media cm', 'op.id_campaign_media = cm.id');
		$this->dbAds->join('shortcodes s', 'op.id_shortcode = s.id');
		$this->dbAds->join('partners p', 'op.id_partner = p.id');
		$this->dbAds->join('prices pr', 'op.id_price = pr.id');
		$this->dbAds->join('currencies cr', 'pr.id_currency = cr.id');
		$this->dbAds->where('op.status', '1');
		if(isset($params['id_country']))
		$this->dbAds->where("op.country_id", $params['id_country']);
		if(isset($params['id_campaign_media']))
		$this->dbAds->where("op.id_campaign_media", $params['id_campaign_media']);
		//$this->dbAds->order_by('keyword', 'ASC');//op.id
		$this->dbAds->order_by('op.id', 'ASC');
		$query = $this->dbAds->get();
			$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			$user = $this->session->userdata('profile');
			if($user['tipe_user_id'] == 11)
			{
				$partner_permissions = json_decode($this->session->userdata('partner_permissions'));
				foreach($query->result_array() as $row) {
					if(in_array($row['partner_id'],$partner_permissions->partner_ids) && in_array($row['shortcode'],$partner_permissions->shortcode) && in_array($row['keyword'],$partner_permissions->keyword) && in_array($row['country_id'],$partner_permissions->country))
					{
						$result['rows'][$i]['id'] 		= $row['id'];
						$result['rows'][$i]['partner_id'] 	= $row['partner_id'];
						$result['rows'][$i]['operator_name'] 	= $row['operator_name'];
						$result['rows'][$i]['operator_id'] 	= $row['operator_id'];
						$result['rows'][$i]['partner_name'] 	= $row['partner_name'];
						$result['rows'][$i]['price'] 		= $row['currency_name'].' '.number_format($row['value'],2);
						$result['rows'][$i]['shortcode'] 	= $row['shortcode'];
						$result['rows'][$i]['sid'] 		= $row['sid'];
						$result['rows'][$i]['keyword'] 		= $row['keyword'];
						$result['rows'][$i]['campaign_media'] 	= $row['campaign_media'];
						$result['rows'][$i]['country'] 		= $row['country_id'];
						$i++;
					}
				}
			}
			else
			{
				foreach($query->result_array() as $row) {
					$result['rows'][$i]['id'] 		= $row['id'];
					$result['rows'][$i]['operator_name'] 	= $row['operator_name'];
					$result['rows'][$i]['operator_id'] 	= $row['operator_id'];
					$result['rows'][$i]['partner_name'] 	= $row['partner_name'];
					$result['rows'][$i]['price'] 			= $row['currency_name'].' '.number_format($row['value'],2);
					$result['rows'][$i]['shortcode'] 		= $row['shortcode'];
					$result['rows'][$i]['sid'] 				= $row['sid'];
					$result['rows'][$i]['keyword'] 			= $row['keyword'];
					$result['rows'][$i]['campaign_media'] 	= $row['campaign_media'];
					$result['rows'][$i]['country'] 		= $row['country_id'];
					$i++;
				}
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function savePartners_Services($params){
		$this->dbAds->select('id');
		$this->dbAds->from('partners_services');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updatePartners_Services($params);
		} else {
			$result = $this->insertPartners_Services($params);
		}
		return $result;
	}

	private function updatePartners_Services($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'code' 			=> $this->dbAds->escape_str(md5($params['id_operator'].$params['id_partner'].$params['id_shortcode'].$params['keyword'])),
						'id_price' 		=> $this->dbAds->escape_str($params['id_price']),
						'id_shortcode' 	=> $this->dbAds->escape_str($params['id_shortcode']),
						'id_operator' 	=> $this->dbAds->escape_str($params['id_operator']),
						'id_campaign_media' 	=> $this->dbAds->escape_str($params['id_campaign_media']),
						'id_operator_api' 	=> $this->dbAds->escape_str($params['id_operator_api']),
						'id_partner' 	=> $this->dbAds->escape_str($params['id_partner']),
						'price_code' 	=> $this->dbAds->escape_str($params['price_code']),
						'id_service_type' 	=> $this->dbAds->escape_str($params['id_service_type']),
						'id_keyword_group' 	=> $this->dbAds->escape_str($params['id_keyword_group']),
						'ncontent' 	 	=> $this->dbAds->escape_str($params['ncontent']),
						'keyword' 	 	=> $this->dbAds->escape_str($params['keyword']),
						'sid'  			=> $this->dbAds->escape_str($params['sid']),
						'status' 		=> $this->dbAds->escape_str($params['status']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
                         'country_id'  => $this->dbAds->escape_str($params['country_id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s')),
						'service_prefix' => $this->dbAds->escape_str($params['sprefix'])
		);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('partners_services', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Partner Service",$update_message);
			$update_message=str_replace("{TITLE}",' ',$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	private function insertPartners_Services($params){

		$result = array();
		$this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('partners_services');
		$this->dbAds->where('country_id',$params['country_id']);
		$this->dbAds->where('id_campaign_media', $this->dbAds->escape_str($params['id_campaign_media']));
		$this->dbAds->where('id_operator', $this->dbAds->escape_str($params['id_operator']));
		$this->dbAds->where('id_operator_api', $this->dbAds->escape_str($params['id_operator_api']));
		$this->dbAds->where('id_partner', $this->dbAds->escape_str($params['id_partner']));
		$this->dbAds->where('id_shortcode', $this->dbAds->escape_str($params['id_shortcode']));
		$this->dbAds->where('id_price', $this->dbAds->escape_str($params['id_price']));
		$this->dbAds->where('keyword', $this->dbAds->escape_str($params['keyword']));
		$this->dbAds->where('sid', $this->dbAds->escape_str($params['sid']));
			
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

		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'code' 			=> $this->dbAds->escape_str(md5($params['id_operator'].$params['id_partner'].$params['id_shortcode'].$params['keyword'])),
						'country_id'  => $this->dbAds->escape_str($params['country_id']),
                        'id_price' 		=> $this->dbAds->escape_str($params['id_price']),
						'id_shortcode' 	=> $this->dbAds->escape_str($params['id_shortcode']),
						'id_operator' 	=> $this->dbAds->escape_str($params['id_operator']),
						'id_campaign_media' 	=> $this->dbAds->escape_str($params['id_campaign_media']),
						'id_operator_api' 	=> $this->dbAds->escape_str($params['id_operator_api']),
						'id_partner' 	=> $this->dbAds->escape_str($params['id_partner']),
						'price_code' 	=> $this->dbAds->escape_str($params['price_code']),
						'id_service_type' => $this->dbAds->escape_str($params['id_service_type']),
						'id_keyword_group' => $this->dbAds->escape_str($params['id_keyword_group']),
						'keyword' 	 	=> $this->dbAds->escape_str($params['keyword']),
						'sid'  			=> $this->dbAds->escape_str($params['sid']),
						'status' 		=> $this->dbAds->escape_str($params['status']),
						'ncontent' 		=> $this->dbAds->escape_str($params['ncontent']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s')),
						'service_prefix' => $this->dbAds->escape_str($params['sprefix'])
		);
		$this->dbAds->insert('partners_services', $update);
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

	public function deletePartners_Services($params){

		$result = array();
		$isExist=$this->CheckExistData('campaigns_details','id_partner_service',$params['id']);
		if($isExist['duplicat_data']){
			$result['errors_message'] =   $isExist['errors_message'];
			$result['duplicat_data'] =  $isExist['duplicat_data'];
			return $result;
			exit;
		}
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('partners_services');

		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Price",$update_message);
			$update_message=str_replace("{TITLE}",' ',$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function getPartners_ServicesData($params){
		$this->dbAds->select('id, id_price, country_id,id_shortcode, sid, id_partner,price_code,ncontent, id_operator, id_campaign_media, id_operator_api, id_service_type, id_keyword_group, keyword, status');
		$this->dbAds->from('partners_services');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();die('lop');
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;

			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['id_price'] = $row['id_price'];
				$result['ncontent'] = $row['ncontent'];
				$result['id_shortcode'] = $row['id_shortcode'];
				$result['sid'] = $row['sid'];
				$result['id_partner'] = $row['id_partner'];
				$result['id_operator'] = $row['id_operator'];
				$result['price_code'] = $row['price_code'];
				$result['id_campaign_media'] = $row['id_campaign_media'];
				$result['id_operator_api'] = $row['id_operator_api'];
				$result['id_service_type'] = $row['id_service_type'];
				$result['id_keyword_group'] = $row['id_keyword_group'];
				$result['keyword'] = $row['keyword'];
				$result['status'] = $row['status'];
				$result['country_id'] = $row['country_id'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function getserviceVal($params){

		$this->dbAds->select('code');
		$this->dbAds->from('shortcodes');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id_shortcode']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['code'] = $row['code'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		//echo "<pre>";print_r($result);die();
		return $result;
	}

}
?>
